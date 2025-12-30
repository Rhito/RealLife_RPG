<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('user can view their own profile', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->getJson("/api/v1/users/{$user->id}");

    $response->assertStatus(200)
             ->assertJsonPath('data.id', $user->id)
             ->assertJsonStructure([
                 'data' => [
                     'id', 'name', 'level', 'avatar', 'stats'
                 ]
             ]);
});

test('user can update their profile', function () {
    $user = User::factory()->create(['name' => 'Old Name']);

    $response = $this->actingAs($user)->postJson("/api/v1/profile", [
        'name' => 'New Name',
        'avatar' => 'new_avatar.png'
    ]);

    $response->assertStatus(200);
    
    expect($user->fresh()->name)->toBe('New Name');
    expect($user->fresh()->avatar)->toBe('new_avatar.png');
});
