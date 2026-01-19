<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AiChatController extends Controller
{
    public function chat(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        $message = $request->input('content');
        $apiKey = env('GEMINI_API_KEY');

        if (!$apiKey) {
            return response()->json([
                'id' => rand(1000, 99999), 
                'sender_id' => 0, 
                'receiver_id' => auth()->id(),
                'content' => "I need a brain! Please set GEMINI_API_KEY in your .env file.",
                'created_at' => now(),
            ]);
        }

        try {
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
                            ['text' => $message]
                        ]
                    ]
                ]
            ]);
    
            if ($response->successful()) {
                $data = $response->json();
                $reply = $data['candidates'][0]['content']['parts'][0]['text'] ?? "I am speechless (Model: $modelToUse).";
                
                return response()->json([
                    'id' => rand(1000, 99999),
                    'sender_id' => 0,
                    'receiver_id' => auth()->id(),
                    'content' => $reply,
                    'created_at' => now(),
                ]);
            } else {
                $errorBody = $response->json();
                $errorMessage = $errorBody['error']['message'] ?? $response->body();
                
                return response()->json([
                    'id' => rand(1000, 99999),
                    'sender_id' => 0,
                    'receiver_id' => auth()->id(),
                    'content' => "API Error with model '{$modelToUse}': " . $errorMessage,
                    'created_at' => now(),
                ]);
            }
        } catch (\Exception $e) {
             return response()->json([
                'id' => rand(1000, 99999),
                'sender_id' => 0,
                'receiver_id' => auth()->id(),
                'content' => "Connection error: " . $e->getMessage(),
                'created_at' => now(),
            ]);
        }
    }
}
