<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AiChatController extends Controller
{
    private function getAiUser()
    {
        // Use a unique email for the bot
        $email = 'ai@realliferpg.online';
        $aiUser = \App\Models\User::firstOrCreate(
            ['email' => $email],
            [
                'name' => 'Gemini AI',
                'password' => bcrypt('gemini_ai_secret_password'),
                'level' => 999,
                'exp' => 0,
                'coins' => 0,
                'avatar' => 'https://upload.wikimedia.org/wikipedia/commons/8/8a/Google_Gemini_logo.svg'
            ]
        );
        return $aiUser;
    }

    public function index()
    {
        return response()->json($this->getAiUser());
    }

    public function chat(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        $user = auth()->user();
        $messageContent = $request->input('content');
        $apiKey = env('GEMINI_API_KEY');
        $aiUser = $this->getAiUser();

        // 1. Save User's Message to DB
        \App\Models\Message::create([
            'sender_id' => $user->id,
            'receiver_id' => $aiUser->id,
            'content' => $messageContent,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        if (!$apiKey) {
            $reply = "I need a brain! Please set GEMINI_API_KEY in your .env file.";
            $this->saveAiResponse($reply, $aiUser->id, $user->id);
            return $this->formatResponse($reply, $aiUser->id, $user->id);
        }

        try {
            // ... (Model discovery logic) ...
            // 1. First, try to discover a valid model
            $modelToUse = 'gemini-1.5-flash'; // Default preference
            
            // List models to find what's actually available
            $modelsResponse = Http::get("https://generativelanguage.googleapis.com/v1beta/models?key={$apiKey}");
            
            if ($modelsResponse->successful()) {
                $models = $modelsResponse->json()['models'] ?? [];
                
                // prefer gemini-1.5-flash, then gemini-pro, then any gemini
                $availableModels = array_map(function($m) { return str_replace('models/', '', $m['name']); }, $models);
                
                if (in_array('gemini-1.5-flash', $availableModels)) {
                    $modelToUse = 'gemini-1.5-flash';
                } elseif (in_array('gemini-pro', $availableModels)) {
                    $modelToUse = 'gemini-pro';
                } elseif (in_array('gemini-1.0-pro', $availableModels)) {
                    $modelToUse = 'gemini-1.0-pro';
                } else {
                    // Pick the first one that supports generateContent
                    foreach ($models as $m) {
                        if (in_array('generateContent', $m['supportedGenerationMethods'] ?? [])) {
                            $modelToUse = str_replace('models/', '', $m['name']);
                            break;
                        }
                    }
                }
            }

            // 2. Send the request using the discovered model
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/{$modelToUse}:generateContent?key={$apiKey}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $messageContent] // Use extracted content
                        ]
                    ]
                ]
            ]);
    
            if ($response->successful()) {
                $data = $response->json();
                $reply = $data['candidates'][0]['content']['parts'][0]['text'] ?? "I am speechless (Model: $modelToUse).";
                
                // Save and Return
                $savedMsg = $this->saveAiResponse($reply, $aiUser->id, $user->id);
                return response()->json($savedMsg);

            } else {
                $errorBody = $response->json();
                $errorMessage = $errorBody['error']['message'] ?? $response->body();
                $reply = "API Error with model '{$modelToUse}': " . $errorMessage;
                
                $savedMsg = $this->saveAiResponse($reply, $aiUser->id, $user->id);
                return response()->json($savedMsg);
            }
        } catch (\Exception $e) {
            $reply = "Connection error: " . $e->getMessage();
            $savedMsg = $this->saveAiResponse($reply, $aiUser->id, $user->id);
            return response()->json($savedMsg);
        }
    }

    private function saveAiResponse($content, $aiId, $userId)
    {
        return \App\Models\Message::create([
            'sender_id' => $aiId,
            'receiver_id' => $userId,
            'content' => $content,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function formatResponse($content, $aiId, $userId) 
    {
         return [
            'id' => rand(1000, 99999), // Temporary ID if not saving immediately
            'sender_id' => $aiId,
            'receiver_id' => $userId,
            'content' => $content,
            'created_at' => now(),
        ];
    }
}
