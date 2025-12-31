<?php

use App\Models\User;
use App\Models\Item;
use App\Models\UserItem;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);


test('user can buy an item', function () {
    $user = User::factory()->create(['coins' => 200]);
    $item = Item::factory()->create(['cost' => 100, 'name' => 'Health Potion']);

    $response = $this->actingAs($user)->postJson("/api/v1/items/{$item->id}/buy");

    $response->assertStatus(200)
             ->assertJsonPath('message', 'Purchased Health Potion!');
    
    $this->assertDatabaseHas('user_items', [
        'user_id' => $user->id,
        'item_id' => $item->id,
        'quantity' => 1,
    ]);

    expect($user->fresh()->coins)->toBe(100);
});

test('buying same item stacks quantity', function () {
    $user = User::factory()->create(['coins' => 200]);
    $item = Item::factory()->create(['cost' => 50]);

    // Buy first time
    $this->actingAs($user)->postJson("/api/v1/items/{$item->id}/buy");

    // Buy second time
    $response = $this->actingAs($user)->postJson("/api/v1/items/{$item->id}/buy");

    $response->assertStatus(200);

    $this->assertDatabaseHas('user_items', [
        'user_id' => $user->id,
        'item_id' => $item->id,
        'quantity' => 2,
    ]);
    
    // Should be same row, so total 1 row for this user/item
    expect(UserItem::where('user_id', $user->id)->where('item_id', $item->id)->count())->toBe(1);
    expect($user->fresh()->coins)->toBe(100);
});

test('user can use an item', function () {
    $user = User::factory()->create(['hp' => 10, 'max_hp' => 100]);
    $item = Item::factory()->create([
        'name' => 'Healing Potion',
        'effects' => ['hp' => 50]
    ]);
    
    $userItem = UserItem::create([
        'user_id' => $user->id,
        'item_id' => $item->id,
        'quantity' => 1,
        'acquired_at' => now(),
    ]);

    $response = $this->actingAs($user)->postJson("/api/v1/inventory/use", [
        'user_item_id' => $userItem->id
    ]);

    $response->assertStatus(200);
    
    // Consumed
    expect($user->fresh()->hp)->toBe(60);
    
    // Quantity should be 0 and marked used
    $this->assertDatabaseHas('user_items', [
        'id' => $userItem->id,
        'quantity' => 0,
        'used' => true,
    ]);
});

test('using stacked item decrements quantity', function () {
    $user = User::factory()->create(['hp' => 10, 'max_hp' => 100]);
    $item = Item::factory()->create(['effects' => ['hp' => 10]]);
    
    $userItem = UserItem::create([
        'user_id' => $user->id,
        'item_id' => $item->id,
        'quantity' => 5,
        'acquired_at' => now(),
    ]);

    $response = $this->actingAs($user)->postJson("/api/v1/inventory/use", [
        'user_item_id' => $userItem->id
    ]);

    $response->assertStatus(200);

    $this->assertDatabaseHas('user_items', [
        'id' => $userItem->id,
        'quantity' => 4,
        'used' => false,
    ]);
});
