<?php

namespace Database\Factories;

use App\Enums\LeadStatus;
use App\Models\Company;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LeadFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'        => $this->faker->catchPhrase(),
            'email'       => $this->faker->safeEmail(),
            'phone'       => $this->faker->phoneNumber(),
            'source'      => $this->faker->randomElement(['Website', 'Recommendation', 'Cold Call', 'Social Media']),
            'status'      => $this->faker->randomElement(LeadStatus::cases()),
            'company_id'  => Company::inRandomOrder()->first()?->id ?? Company::factory(),
            'contact_id'  => Contact::inRandomOrder()->first()?->id ?? Contact::factory(),
            'assigned_to' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'created_by'  => User::inRandomOrder()->first()?->id ?? User::factory(),
        ];
    }
}
