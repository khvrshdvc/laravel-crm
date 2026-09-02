<?php

namespace Tests\Feature;

use App\Enums\LeadStatus;
use App\Enums\UserRole;
use App\Models\Company;
use App\Models\Contact;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeadTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Policy / Middleware to'siqlaridan o'tishi uchun foydalanuvchini Admin roli bilan yaratamiz
        $this->user = User::factory()->create([
            'role' => UserRole::Admin,
        ]);
    }

    public function test_guest_cannot_access_leads(): void
    {
        $response = $this->get('/leads');

        $response->assertRedirect('/login');
    }

    public function test_user_can_view_leads_list(): void
    {
        Lead::factory()->count(3)->create();

        $response = $this->actingAs($this->user)->get('/leads');

        $response->assertStatus(200);
        $response->assertViewIs('leads.index');
        $response->assertViewHas('leads');
    }

    public function test_user_can_filter_leads_by_company(): void
    {
        $company1 = Company::factory()->create();
        $company2 = Company::factory()->create();

        $lead1 = Lead::factory()->create(['company_id' => $company1->id, 'created_by' => $this->user->id]);
        $lead2 = Lead::factory()->create(['company_id' => $company2->id, 'created_by' => $this->user->id]);

        $response = $this->actingAs($this->user)->get("/leads?company_id={$company1->id}");

        $response->assertStatus(200);
        $response->assertSee($lead1->name);
        $response->assertDontSee($lead2->name);
    }

    public function test_user_can_create_lead(): void
    {
        $company = Company::factory()->create();
        $contact = Contact::factory()->create();

        $response = $this->actingAs($this->user)->post('/leads', [
            'name' => 'John Doe',
            'email' => 'john@google.com',
            'phone' => '+998901234567',
            'source' => 'Website',
            'status' => LeadStatus::New->value,
            'company_id' => $company->id,
            'contact_id' => $contact->id,
            'assigned_to' => $this->user->id,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('leads', [
            'name' => 'John Doe',
            'email' => 'john@google.com',
            'company_id' => $company->id,
            'created_by' => $this->user->id,
        ]);
    }

    public function test_lead_requires_a_name(): void
    {
        $response = $this->actingAs($this->user)->post('/leads', [
            'email' => 'john@google.com',
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_user_can_update_lead(): void
    {
        $lead = Lead::factory()->create([
            'name' => 'Old Name',
            'created_by' => $this->user->id,
            'assigned_to' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)->put("/leads/{$lead->id}", [
            'name' => 'Updated Name',
            'status' => $lead->status->value ?? $lead->status,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('leads', [
            'id' => $lead->id,
            'name' => 'Updated Name',
        ]);
    }

    public function test_user_can_update_lead_status(): void
    {
        $lead = Lead::factory()->create([
            'status' => LeadStatus::New->value,
            'created_by' => $this->user->id,
            'assigned_to' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)->patch("/leads/{$lead->id}/status", [
            'status' => LeadStatus::Contacted->value,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('leads', [
            'id' => $lead->id,
            'status' => LeadStatus::Contacted->value,
        ]);
    }

    public function test_user_can_delete_lead(): void
    {
        $lead = Lead::factory()->create([
            'created_by' => $this->user->id,
            'assigned_to' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)->delete("/leads/{$lead->id}");

        $response->assertRedirect('/leads');
        $this->assertDatabaseMissing('leads', [
            'id' => $lead->id,
        ]);
    }
}
