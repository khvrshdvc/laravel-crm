<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactFactory extends Factory
{
    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'position' => fake()->jobTitle(),
            'company_id' => Company::inRandomOrder()->first()?->id ?? Company::factory(),
            'created_by' => User::factory(),
        ];
    }
}