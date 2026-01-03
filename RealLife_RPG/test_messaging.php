<?php

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Message;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

// Create two users if they don't exist
$user1 = User::firstOrCreate(
    ['email' => 'sender@example.com'],
    ['name' => 'Sender', 'password' => Hash::make('password')]
);

$user2 = User::firstOrCreate(
    ['email' => 'receiver@example.com'],
    ['name' => 'Receiver', 'password' => Hash::make('password')]
);

echo "User 1 ID: " . $user1->id . "\n";
echo "User 2 ID: " . $user2->id . "\n";

// Login as User 1
Auth::login($user1);

// Send Message
$controller = new \App\Http\Controllers\User\MessageController();
$request = new \Illuminate\Http\Request();
$request->replace(['content' => 'Hello from Sender!']);
$request->setMethod('POST');

echo "Sending message...\n";
$response = $controller->store($request, $user2->id);
echo "Response Status: " . $response->getStatusCode() . "\n";
echo "Response Content: " . $response->getContent() . "\n";

// Login as User 2
Auth::login($user2);

// Read Messages
echo "Reading messages for User 2...\n";
$response = $controller->index($user1->id);
echo "Messages: " . $response->getContent() . "\n";
