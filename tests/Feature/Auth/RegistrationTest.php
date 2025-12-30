<?php

test('new users can register', function () {
    $response = $this->post('/api/v1/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertStatus(200);
    $response->assertJsonStructure([
        'data' => [
            'user',
            'token',
        ],
    ]);
});
