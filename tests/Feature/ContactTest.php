<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_contact(): void
    {
        $user = User::factory()->create();
        $company = Company::factory()->create();

        $response = $this->actingAs($user)->post('/contacts', [
            'first_name' => 'John',
            'last_name' => 'Smith',
            'phone' => '+998901234567',
            'email' => 'john@google.com',
            'company_id' => $company->id,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('contacts', [
            'first_name' => 'John',
            'last_name' => 'Smith',
            'phone' => '+998901234567',
            'email' => 'john@google.com',
            'company_id' => $company->id,
        ]);
    }

    public function test_contact_requires_a_company(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/contacts', [
            'first_name' => 'John',
            'last_name' => 'Smith',
        ]);

        $response->assertSessionHasErrors('company_id');
    }

    public function test_guest_cannot_create_contact(): void
    {
        $company = Company::factory()->create();

        $response = $this->post('/contacts', [
            'first_name' => 'John',
            'last_name' => 'Smith',
            'company_id' => $company->id,
        ]);

        $response->assertRedirect('/login');
    }
}
