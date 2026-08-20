<?php

namespace Tests\Feature;

use App\Enums\LeadStatus;
use App\Models\Company;
use App\Models\Contact;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeadTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_leads(): void
    {
        $response = $this->get('/leads');

        $response->assertRedirect('/login');
    }

    public function test_user_can_view_leads_list(): void
    {
        $user = User::factory()->create();
        Lead::factory()->count(3)->create();

        $response = $this->actingAs($user)->get('/leads');

        $response->assertStatus(200);
        $response->assertViewIs('leads.index');
        $response->assertViewHas('leads');
    }

    public function test_user_can_filter_leads_by_company(): void
    {
        $user = User::factory()->create();
        $company1 = Company::factory()->create();
        $company2 = Company::factory()->create();

        $lead1 = Lead::factory()->create(['company_id' => $company1->id]);
        $lead2 = Lead::factory()->create(['company_id' => $company2->id]);

        $response = $this->actingAs($user)->get("/leads?company_id={$company1->id}");

        $response->assertStatus(200);
        $response->assertSee($lead1->name);
        $response->assertDontSee($lead2->name);
    }

    public function test_user_can_create_lead(): void
    {
        $user = User::factory()->create();
        $company = Company::factory()->create();
        $contact = Contact::factory()->create();

        $response = $this->actingAs($user)->post('/leads', [
            'name' => 'John Doe',
            'email' => 'john@google.com',
            'phone' => '+998901234567',
            'source' => 'Website',
            'status' => LeadStatus::New->value,
            'company_id' => $company->id,
            'contact_id' => $contact->id,
            'assigned_to' => $user->id,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('leads', [
            'name' => 'John Doe',
            'email' => 'john@google.com',
            'company_id' => $company->id,
            'created_by' => $user->id,
        ]);
    }

    public function test_lead_requires_a_name(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/leads', [
            'email' => 'john@google.com',
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_user_can_update_lead(): void
    {
        $user = User::factory()->create();
        $lead = Lead::factory()->create(['name' => 'Old Name']);

        $response = $this->actingAs($user)->put("/leads/{$lead->id}", [
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
        $user = User::factory()->create();
        $lead = Lead::factory()->create(['status' => LeadStatus::New->value]);

        $response = $this->actingAs($user)->patch("/leads/{$lead->id}/status", [
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
        $user = User::factory()->create();
        $lead = Lead::factory()->create();

        $response = $this->actingAs($user)->delete("/leads/{$lead->id}");

        $response->assertRedirect('/leads');
        $this->assertDatabaseMissing('leads', [
            'id' => $lead->id,
        ]);
    }
}