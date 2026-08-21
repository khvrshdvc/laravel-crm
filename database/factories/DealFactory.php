<?php

namespace Database\Factories;

use App\Enums\DealStatus;
use App\Models\Company;
use App\Models\Contact;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DealFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title'       => fake()->sentence(3),
            'amount'      => fake()->randomFloat(2, 500, 50000),
            'status'      => fake()->randomElement(DealStatus::cases())->value,
            'lead_id'     => Lead::factory(),
            'company_id'  => Company::factory(),
            'contact_id'  => Contact::factory(),
            'assigned_to' => User::factory(),
            'created_by'  => User::factory(),
        ];
    }
}
