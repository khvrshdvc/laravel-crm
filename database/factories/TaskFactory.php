<?php

namespace Database\Factories;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'assigned_to' => User::factory(),
            'due_date' => fake()->dateTimeBetween('now', '+2 weeks'),
            'priority' => fake()->randomElement(TaskPriority::cases())->value,
            'status' => fake()->randomElement(TaskStatus::cases())->value,
        ];
    }
}
