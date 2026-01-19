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
            // Fallback mock response if no key (for testing)
            return response()->json([
                'id' => rand(1000, 99999), 
                'sender_id' => 0, 
                'receiver_id' => auth()->id(),
                'content' => "I need a brain! Please set GEMINI_API_KEY in your .env file.",
                'created_at' => now(),
            ]);
        }

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash-latest:generateContent?key={$apiKey}", [
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
                $reply = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'I am contemplating the void.';
                
                return response()->json([
                    'id' => rand(1000, 99999),
                    'sender_id' => 0,
                    'receiver_id' => auth()->id(),
                    'content' => $reply,
                    'created_at' => now(),
                ]);
            } else {
                return response()->json([
                    'id' => rand(1000, 99999),
                    'sender_id' => 0,
                    'receiver_id' => auth()->id(),
                    'content' => "Error: " . $response->body(),
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
