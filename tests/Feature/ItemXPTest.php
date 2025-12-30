<?php

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('user can use XP boost item', function () {
    $user = User::factory()->create([
        'exp' => 100,
        'coins' => 500,
    ]);

    // Create XP boost item
    $xpItem = Item::create([
        'name' => 'Scroll of Wisdom',
        'description' => 'Grants 50 XP',
        'cost' => 150,
        'type' => 'consumable',
        'effects' => ['exp' => 50],
    ]);

    // User buys the item
    $this->actingAs($user)->post("/api/v1/items/{$xpItem->id}/buy");

    // Get the user_item_id
    $userItem = \App\Models\UserItem::where('user_id', $user->id)
        ->where('item_id', $xpItem->id)
        ->first();

    // Use the item
    $response = $this->actingAs($user)->post('/api/v1/inventory/use', [
        'user_item_id' => $userItem->id,
    ]);

    $response->assertStatus(200);
    
    // Verify XP was gained
    $user->refresh();
    expect($user->exp)->toBe(150); // 100 + 50
    
    // Verify response includes updated stats
    $response->assertJsonStructure([
        'message',
        'data' => ['hp', 'max_hp', 'exp', 'level', 'coins']
    ]);
});
