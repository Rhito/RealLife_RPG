<?php

namespace Database\Seeders;

use App\Models\Achievement;
use Illuminate\Database\Seeder;

class AchievementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. First Step
        Achievement::firstOrCreate(
            ['name' => 'First Blood'],
            [
                'description' => 'Complete your very first task.',
                'condition' => '{"total_tasks_completed": 1}', 
                'reward_exp' => 50,
                'reward_coins' => 10,
                'icon' => 'star-outline', 
            ]
        );

        // 2. High Roller
        Achievement::firstOrCreate(
            ['name' => 'High Roller'],
            [
                'description' => 'Accumulate 1000 Coins.',
                'condition' => '{"total_coins": 1000}',
                'reward_exp' => 200,
                'reward_coins' => 0,
                'icon' => 'cash-outline', 
            ]
        );

        // 3. Level 5
        Achievement::firstOrCreate(
            ['name' => 'Apprentice Hero'],
            [
                'description' => 'Reach Level 5.',
                'condition' => '{"level": 5}',
                'reward_exp' => 0,
                'reward_coins' => 100,
                'icon' => 'shield-checkmark-outline', 
            ]
        );
         // 4. Streak Master
         Achievement::firstOrCreate(
            ['name' => 'Streak Master'],
            [
                'description' => 'Reach a 7-day streak.',
                'condition' => '{"streak": 7}',
                'reward_exp' => 150,
                'reward_coins' => 50,
                'icon' => 'flame-outline', 
            ]
        );

        // --- NEW ACHIEVEMENTS (Total 16) ---

        // 5. Novice Taskmaster
        Achievement::firstOrCreate(
            ['name' => 'Novice Taskmaster'],
            [
                'description' => 'Complete 10 tasks.',
                'condition' => '{"total_tasks_completed": 10}',
                'reward_exp' => 100,
                'reward_coins' => 20,
                'icon' => 'checkmark-circle-outline',
            ]
        );

        // 6. Productivity Machine
        Achievement::firstOrCreate(
            ['name' => 'Productivity Machine'],
            [
                'description' => 'Complete 50 tasks.',
                'condition' => '{"total_tasks_completed": 50}',
                'reward_exp' => 500,
                'reward_coins' => 100,
                'icon' => 'construct-outline',
            ]
        );

        // 7. Task Legend
        Achievement::firstOrCreate(
            ['name' => 'Task Legend'],
            [
                'description' => 'Complete 100 tasks.',
                'condition' => '{"total_tasks_completed": 100}',
                'reward_exp' => 1500,
                'reward_coins' => 300,
                'icon' => 'trophy-outline',
            ]
        );

        // 8. Adventurer
        Achievement::firstOrCreate(
            ['name' => 'Adventurer'],
            [
                'description' => 'Reach Level 10.',
                'condition' => '{"level": 10}',
                'reward_exp' => 0,
                'reward_coins' => 200,
                'icon' => 'compass-outline',
            ]
        );

        // 9. Veteran
        Achievement::firstOrCreate(
            ['name' => 'Veteran'],
            [
                'description' => 'Reach Level 25.',
                'condition' => '{"level": 25}',
                'reward_exp' => 0,
                'reward_coins' => 500,
                'icon' => 'medal-outline',
            ]
        );

        // 10. Legend
        Achievement::firstOrCreate(
            ['name' => 'Legend'],
            [
                'description' => 'Reach Level 50.',
                'condition' => '{"level": 50}',
                'reward_exp' => 0,
                'reward_coins' => 2000,
                'icon' => 'ribbon-outline',
            ]
        );

        // 11. Getting Rich
        Achievement::firstOrCreate(
            ['name' => 'Getting Rich'],
            [
                'description' => 'Accumulate 500 Coins.',
                'condition' => '{"total_coins": 500}',
                'reward_exp' => 100,
                'reward_coins' => 0,
                'icon' => 'wallet-outline',
            ]
        );

        // 12. Wealthy
        Achievement::firstOrCreate(
            ['name' => 'Wealthy'],
            [
                'description' => 'Accumulate 2000 Coins.',
                'condition' => '{"total_coins": 2000}',
                'reward_exp' => 500,
                'reward_coins' => 0,
                'icon' => 'diamond-outline',
            ]
        );

        // 13. Coin Hoarder
        Achievement::firstOrCreate(
            ['name' => 'Coin Hoarder'],
            [
                'description' => 'Accumulate 5000 Coins.',
                'condition' => '{"total_coins": 5000}',
                'reward_exp' => 1000,
                'reward_coins' => 0,
                'icon' => 'server-outline',
            ]
        );

        // 14. Small Streak
        Achievement::firstOrCreate(
            ['name' => 'Small Streak'],
            [
                'description' => 'Reach a 3-day streak.',
                'condition' => '{"streak": 3}',
                'reward_exp' => 50,
                'reward_coins' => 20,
                'icon' => 'bonfire-outline',
            ]
        );

        // 15. Solid Streak
        Achievement::firstOrCreate(
            ['name' => 'Solid Streak'],
            [
                'description' => 'Reach a 14-day streak.',
                'condition' => '{"streak": 14}',
                'reward_exp' => 300,
                'reward_coins' => 100,
                'icon' => 'trending-up-outline',
            ]
        );

        // 16. Monthly Master
        Achievement::firstOrCreate(
            ['name' => 'Monthly Master'],
            [
                'description' => 'Reach a 30-day streak.',
                'condition' => '{"streak": 30}',
                'reward_exp' => 1000,
                'reward_coins' => 500,
                'icon' => 'calendar-outline',
            ]
        );

        // 17. Big Spender
        Achievement::firstOrCreate(
            ['name' => 'Big Spender'],
            [
                'description' => 'Spend 200 Coins in the shop.',
                'condition' => '{"coins_spent": 200}',
                'reward_exp' => 150,
                'reward_coins' => 0,
                'icon' => 'cart-outline',
            ]
        );

        // 18. Potion Addict (Giver/User replacement)
        Achievement::firstOrCreate(
            ['name' => 'Potion Master'],
            [
                'description' => 'Use 5 items.',
                'condition' => '{"items_used": 5}',
                'reward_exp' => 100,
                'reward_coins' => 50,
                'icon' => 'flask-outline',
            ]
        );

        // 19. Task Failed Successfully
        Achievement::firstOrCreate(
            ['name' => 'Task Failed Successfully'],
            [
                'description' => 'Fail 1 task. It happens to the best of us.',
                'condition' => '{"tasks_failed": 1}',
                'reward_exp' => 10,
                'reward_coins' => 0,
                'icon' => 'skull-outline',
            ]
        );
        
        // 20. Weekend Warrior
        Achievement::firstOrCreate(
            ['name' => 'Weekend Warrior'],
            [
                'description' => 'Complete 5 tasks on weekends.',
                'condition' => '{"weekend_tasks": 5}',
                'reward_exp' => 150,
                'reward_coins' => 50,
                'icon' => 'sunny-outline',
            ]
        );

        // 21. Early Bird
        Achievement::firstOrCreate(
            ['name' => 'Early Bird'],
            [
                'description' => 'Complete 10 tasks before 9 AM.',
                'condition' => '{"early_morning_tasks": 10}',
                'reward_exp' => 200,
                'reward_coins' => 75,
                'icon' => 'sunrise-outline',
            ]
        );

        // 22. Night Owl
        Achievement::firstOrCreate(
            ['name' => 'Night Owl'],
            [
                'description' => 'Complete 10 tasks after 10 PM.',
                'condition' => '{"late_night_tasks": 10}',
                'reward_exp' => 200,
                'reward_coins' => 75,
                'icon' => 'moon-outline',
            ]
        );

        // 23. Perfectionist
        Achievement::firstOrCreate(
            ['name' => 'Perfectionist'],
            [
                'description' => 'Complete 20 tasks without failing any.',
                'condition' => '{"perfect_tasks_streak": 20}',
                'reward_exp' => 300,
                'reward_coins' => 150,
                'icon' => 'star-outline',
            ]
        );

        // 24. Social Butterfly
        Achievement::firstOrCreate(
            ['name' => 'Social Butterfly'],
            [
                'description' => 'Add 5 friends.',
                'condition' => '{"total_friends": 5}',
                'reward_exp' => 100,
                'reward_coins' => 50,
                'icon' => 'people-outline',
            ]
        );

        // 25. Squad Goals
        Achievement::firstOrCreate(
            ['name' => 'Squad Goals'],
            [
                'description' => 'Add 20 friends.',
                'condition' => '{"total_friends": 20}',
                'reward_exp' => 500,
                'reward_coins' => 200,
                'icon' => 'people-circle-outline',
            ]
        );

        // 26. Shopaholic
        Achievement::firstOrCreate(
            ['name' => 'Shopaholic'],
            [
                'description' => 'Buy 10 different items from the shop.',
                'condition' => '{"unique_items_bought": 10}',
                'reward_exp' => 250,
                'reward_coins' => 100,
                'icon' => 'bag-handle-outline',
            ]
        );

        // 27. Comeback Kid
        Achievement::firstOrCreate(
            ['name' => 'Comeback Kid'],
            [
                'description' => 'Recover from 10 HP or less by using items.',
                'condition' => '{"hp_recoveries": 1}',
                'reward_exp' => 150,
                'reward_coins' => 50,
                'icon' => 'heart-outline',
            ]
        );

        // 28. Immortal
        Achievement::firstOrCreate(
            ['name' => 'Immortal'],
            [
                'description' => 'Maintain full HP for 7 consecutive days.',
                'condition' => '{"full_hp_streak": 7}',
                'reward_exp' => 400,
                'reward_coins' => 200,
                'icon' => 'shield-outline',
            ]
        );

        // 29. Marathon Runner
        Achievement::firstOrCreate(
            ['name' => 'Marathon Runner'],
            [
                'description' => 'Complete 10 tasks in a single day.',
                'condition' => '{"tasks_in_one_day": 10}',
                'reward_exp' => 300,
                'reward_coins' => 100,
                'icon' => 'speedometer-outline',
            ]
        );

        // 30. Ultra Grinder
        Achievement::firstOrCreate(
            ['name' => 'Ultra Grinder'],
            [
                'description' => 'Complete 25 tasks in a single day.',
                'condition' => '{"tasks_in_one_day": 25}',
                'reward_exp' => 1000,
                'reward_coins' => 500,
                'icon' => 'flash-outline',
            ]
        );

        // 31. Experience Hunter
        Achievement::firstOrCreate(
            ['name' => 'Experience Hunter'],
            [
                'description' => 'Earn a total of 5000 XP.',
                'condition' => '{"total_exp_earned": 5000}',
                'reward_exp' => 0,
                'reward_coins' => 250,
                'icon' => 'analytics-outline',
            ]
        );

        // 32. XP Collector
        Achievement::firstOrCreate(
            ['name' => 'XP Collector'],
            [
                'description' => 'Earn a total of 20000 XP.',
                'condition' => '{"total_exp_earned": 20000}',
                'reward_exp' => 0,
                'reward_coins' => 1000,
                'icon' => 'fitness-outline',
            ]
        );

        // 33. Comeback Master
        Achievement::firstOrCreate(
            ['name' => 'Comeback Master'],
            [
                'description' => 'Rebuild your streak to 7 days after breaking it.',
                'condition' => '{"streak_comebacks": 1}',
                'reward_exp' => 250,
                'reward_coins' => 100,
                'icon' => 'refresh-outline',
            ]
        );

        // 34. Diversity Champion
        Achievement::firstOrCreate(
            ['name' => 'Diversity Champion'],
            [
                'description' => 'Complete tasks of all difficulty levels (10 each).',
                'condition' => '{"diverse_tasks": 10}',
                'reward_exp' => 400,
                'reward_coins' => 200,
                'icon' => 'color-palette-outline',
            ]
        );

        // 35. The Minimalist
        Achievement::firstOrCreate(
            ['name' => 'The Minimalist'],
            [
                'description' => 'Complete 50 tasks without buying any items.',
                'condition' => '{"no_items_tasks": 50}',
                'reward_exp' => 500,
                'reward_coins' => 300,
                'icon' => 'leaf-outline',
            ]
        );
    }
}
