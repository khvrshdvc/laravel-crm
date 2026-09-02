<?php

namespace Tests\Feature;

use App\Enums\DealStatus;
use App\Enums\UserRole;
use App\Models\Company;
use App\Models\Contact;
use App\Models\Deal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DealTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        // 403 Forbidden xatosini oldini olish uchun foydalanuvchini Admin roli bilan yaratamiz
        $this->user = User::factory()->create([
            'role' => UserRole::Admin,
        ]);
    }

    public function test_user_can_view_deals_list(): void
    {
        $response = $this->actingAs($this->user)->get(route('deals.index'));

        $response->assertStatus(200);
    }

    public function test_user_can_create_deal(): void
    {
        $company = Company::factory()->create();
        $contact = Contact::factory()->create();

        $data = [
            'title' => 'Enterprise Software Purchase',
            'amount' => 25000.00,
            'status' => DealStatus::cases()[0]->value,
            'company_id' => $company->id,
            'contact_id' => $contact->id,
            'assigned_to' => $this->user->id,
        ];

        $response = $this->actingAs($this->user)->post(route('deals.store'), $data);

        $response->assertRedirect();
        $this->assertDatabaseHas('deals', [
            'title' => 'Enterprise Software Purchase',
            'amount' => 25000.00,
            'contact_id' => $contact->id,
            'created_by' => $this->user->id,
        ]);
    }

    public function test_user_can_view_deal_details(): void
    {
        $contact = Contact::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);

        $deal = Deal::factory()->create([
            'contact_id' => $contact->id,
            'created_by' => $this->user->id,
            'assigned_to' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)->get(route('deals.show', $deal));

        $response->assertStatus(200);
        $response->assertSee('John');
        $response->assertSee('Doe');
    }

    public function test_user_can_update_deal(): void
    {
        $deal = Deal::factory()->create([
            'title' => 'Old Deal Title',
            'created_by' => $this->user->id,
            'assigned_to' => $this->user->id,
        ]);
        $newContact = Contact::factory()->create();

        $updateData = [
            'title' => 'New Deal Title',
            'amount' => 30000.00,
            'status' => $deal->status->value,
            'contact_id' => $newContact->id,
            'assigned_to' => $this->user->id,
        ];

        $response = $this->actingAs($this->user)->put(route('deals.update', $deal), $updateData);

        $response->assertRedirect();
        $this->assertDatabaseHas('deals', [
            'id' => $deal->id,
            'title' => 'New Deal Title',
            'contact_id' => $newContact->id,
        ]);
    }

    public function test_user_can_delete_deal(): void
    {
        $deal = Deal::factory()->create([
            'created_by' => $this->user->id,
            'assigned_to' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)->delete(route('deals.destroy', $deal));

        $response->assertRedirect();
        $this->assertDatabaseMissing('deals', [
            'id' => $deal->id,
        ]);
    }
}
