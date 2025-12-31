<?php

use App\Models\User;
use App\Notifications\CustomResetPasswordNotification;
use Illuminate\Support\Facades\Notification;

test('reset password link can be requested', function () {
    Notification::fake();

    $user = User::factory()->create();

    $this->post('/api/v1/forgot-password', ['email' => $user->email]);

    Notification::assertSentTo($user, CustomResetPasswordNotification::class);
});

test('password can be reset with valid token', function () {
    Notification::fake();

    $user = User::factory()->create();

    $this->post('/api/v1/forgot-password', ['email' => $user->email]);

    Notification::assertSentTo($user, CustomResetPasswordNotification::class, function (object $notification) use ($user) {
        $response = $this->post('/api/v1/reset-password', [
            'token' => $notification->token,
            'email' => $user->email,
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'user',
                'token',
            ],
        ]);

        return true;
    });
});
