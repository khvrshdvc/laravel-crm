<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CompanyTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
       
        $this->user = User::factory()->create();
    }

    public function test_user_can_view_companies_list(): void
    {
        $response = $this->actingAs($this->user)->get('/companies');

        $response->assertStatus(200);
    }

    public function test_user_can_create_company(): void
    {
        $data = [
            'name' => 'Google LLC',
            'email' => 'info@google.com',
            'phone' => '+123456789',
            'website' => 'https://google.com',
            'address' => 'Mountain View, CA',
        ];

        $response = $this->actingAs($this->user)->post('/companies', $data);

        $response->assertRedirect();
        $this->assertDatabaseHas('companies', [
            'name' => 'Google LLC',
            'email' => 'info@google.com',
        ]);
    }

    public function test_user_can_update_company(): void
    {
        $company = Company::factory()->create(['name' => 'Old Name']);

        $updateData = [
            'name' => 'New Name',
            'email' => 'new@google.com',
        ];

        $response = $this->actingAs($this->user)->put("/companies/{$company->id}", $updateData);

        $response->assertRedirect();
        $this->assertDatabaseHas('companies', [
            'id' => $company->id,
            'name' => 'New Name',
        ]);
    }

    public function test_user_can_delete_company(): void
    {
        $company = Company::factory()->create();

        $response = $this->actingAs($this->user)->delete("/companies/{$company->id}");

        $response->assertRedirect();
        $this->assertDatabaseMissing('companies', [
            'id' => $company->id,
        ]);
    }
}