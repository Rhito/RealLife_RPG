<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        // Random loại task
        $type = $this->faker->randomElement(['daily', 'once', 'habit']);

        return [
            'user_id'     => User::factory(), // Tự động gắn user giả
            'title'       => $this->faker->sentence(3),
            'description' => $this->faker->optional()->paragraph(),
            'type'        => $type,
            'difficulty'  => $this->faker->randomElement(['easy', 'medium', 'hard']),
            'repeat_days' => $type === 'daily' || $type === 'habit'
                ? json_encode($this->faker->randomElements(
                    ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'],
                    $this->faker->numberBetween(1, 7)
                ))
                : null,
            'due_date'    => $type === 'once'
                ? $this->faker->dateTimeBetween('now', '+1 month')
                : null,
            'is_active'   => $this->faker->boolean(80), // 80% là active
        ];
    }
}
