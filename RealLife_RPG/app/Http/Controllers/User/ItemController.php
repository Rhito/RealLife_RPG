<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::all();
        return response()->json(['data' => $items]);
    }

    public function buy(Request $request, string $id)
    {
        $user = Auth::user();
        $item = Item::where('id', $id)->firstOrFail();

        if ($user->coins < $item->cost) {
            return response()->json(['message' => 'Not enough coins'], 400);
        }

        DB::transaction(function () use ($user, $item) {
            $user->decrement('coins', $item->cost);
            
            // Check for existing stack
            $existingItem = \App\Models\UserItem::where('user_id', $user->id)
                ->where('item_id', $item->id)
                ->where('used', false)
                ->first();

            if ($existingItem) {
                $existingItem->increment('quantity');
            } else {
                $user->items()->attach($item->id, [
                    'acquired_at' => now(), 
                    'used' => false,
                    'quantity' => 1
                ]);
            }
        });

        return response()->json([
            'message' => "Purchased {$item->name}!",
            'data' => [
                'coins' => $user->fresh()->coins,
            ]
        ]);
    }

    public function inventory()
    {
        $user = Auth::user();
        // Fetch active inventory items (quantity > 0) AND item still exists (not soft deleted)
        $items = \App\Models\UserItem::where('user_id', $user->id)
            ->where('used', false)
            ->where('quantity', '>', 0)
            ->whereHas('item') 
            ->with('item')
            ->get();
            
        return response()->json(['data' => $items]);
    }

    public function use(Request $request)
    {
        $request->validate([
            'user_item_id' => 'required|exists:user_items,id',
        ]);

        $user = Auth::user();
        $userItem = \App\Models\UserItem::where('id', $request->user_item_id)
            ->where('user_id', $user->id)
            ->where('used', false)
            ->with('item')
            ->firstOrFail();

        $item = $userItem->item;
        $effects = $item->effects;
        $message = "Used {$item->name}.";
        $updates = [];

        DB::transaction(function() use ($user, $userItem, $effects, &$updates, &$message, $item) {
            $usedSuccessfully = false;

            // Apply HP Effects
            if (isset($effects['hp'])) {
                $maxHp = $user->max_hp ?? 50; 
                $newHp = min($maxHp, $user->hp + $effects['hp']);
                $healedAmount = $newHp - $user->hp;
                
                if ($healedAmount <= 0 && $user->hp >= $maxHp) {
                     // Maybe prevent use if full HP?
                }
                
                $user->hp = $newHp;
                $message .= " Healed {$healedAmount} HP.";
                $usedSuccessfully = true;
            }

            // Apply XP Effects
            if (isset($effects['exp'])) {
                $user->exp += $effects['exp'];
                $message .= " Gained {$effects['exp']} XP.";
                $usedSuccessfully = true;
            }

            // Apply Coins Effects
            if (isset($effects['coins'])) {
                $user->coins += $effects['coins'];
                $message .= " Gained {$effects['coins']} coins.";
                $usedSuccessfully = true;
            }

            if (isset($effects['streak_freeze'])) {
                 // Passive item, usually not clicked to use
                throw new \Exception("This item is automatically used when you miss a daily task!");
            }

            if (!$usedSuccessfully && empty($effects)) {
                $usedSuccessfully = true; 
            }

            if ($usedSuccessfully) {
                $user->save();
                
                // Decrement quantity
                if ($userItem->quantity > 1) {
                    $userItem->decrement('quantity');
                } else {
                    $userItem->update(['used' => true, 'quantity' => 0]);
                }
                
                
                \App\Models\ActivityFeed::create([
                    'user_id' => $user->id,
                    'activity_type' => 'item_used', 
                    'visibility' => 'private',
                    'data' => [
                        'item_name' => $item->name,
                        'effect' => $message,
                    ],
                    'created_at' => now(), 
                ]);
            }
        });

        return response()->json([
            'message' => $message,
            'data' => [
                'hp' => $user->hp,
                'max_hp' => $user->max_hp,
                'exp' => $user->exp,
                'level' => $user->level,
                'coins' => $user->coins,
            ]
        ]);
    }
}
