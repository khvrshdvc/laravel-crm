<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Contact;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class NoteFactory extends Factory
{
    public function definition(): array
    {
        $noteable = fake()->randomElement([
            Company::class,
            Contact::class,
            Lead::class,
        ]);

        return [
            'content' => fake()->paragraph(),
            'created_by' => User::factory(),

            'noteable_type' => $noteable,
            'noteable_id' => $noteable::factory(),
        ];
    }
}
