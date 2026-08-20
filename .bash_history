php artisan migrate:fresh --seed
php artisan migrate:fresh --seed
php artisan make:enum LeadStatus
php artisan migrate
php artisan migrate:fresh --seed
php artisan migrate:fresh --seed
php artisan make:request StoreLeadRequest
php artisan make:controller LeadController
php artisan migrate:fresh --seed
php artisan make:test LeadTest
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
{     use RefreshDatabase;     protected User $user;     protected function setUp(): void
    {         parent::setUp();
        // Create an authenticated user for tests
        $this->user = User::factory()->create();
    }
    /** @test */
    public function guests_cannot_access_leads_index(): void
    {         $response = $this->get(route('leads.index'));
        $response->assertRedirect(route('login'));
    }
    /** @test */
    public function authenticated_user_can_view_leads_index(): void
    {         Lead::factory()->count(3)->create();
        $response = $this->actingAs($this->user)->get(route('leads.index'));
        $response->assertStatus(200);
        $response->assertViewIs('leads.index');
        $response->assertViewHas('leads');
    }
    /** @test */
    public function leads_can_be_filtered_by_company_id(): void
    {         $company1 = Company::factory()->create();
        $company2 = Company::factory()->create();
        $lead1 = Lead::factory()->create(['company_id' => $company1->id]);
        $lead2 = Lead::factory()->create(['company_id' => $company2->id]);
        $response = $this->actingAs($this->user)
            ->get(route('leads.index', ['company_id' => $company1->id]));
        $response->assertStatus(200);
        $response->assertSee($lead1->name);
        $response->assertDontSee($lead2->name);
    }
    /** @test */
    public function lead_creation_page_can_be_rendered(): void
    {         $response = $this->actingAs($this->user)->get(route('leads.create'));
        $response->assertStatus(200);
        $response->assertViewIs('leads.create');
    }
    /** @test */
    public function new_lead_can_be_created_successfully(): void
    {         $company = Company::factory()->create();
        $contact = Contact::factory()->create();
        $data = [
            'name' => 'Acme Lead',
            'email' => 'lead@example.com',
            'phone' => '+1234567890',
            'source' => 'Website',
            'status' => LeadStatus::NEW->value,
            'company_id' => $company->id,
            'contact_id' => $contact->id,
            'assigned_to' => $this->user->id,
        ];
        $response = $this->actingAs($this->user)
            ->post(route('leads.store'), $data);
        $response->assertRedirect();
        $this->assertDatabaseHas('leads', [
            'name' => 'Acme Lead',
            'email' => 'lead@example.com',
            'created_by' => $this->user->id,
        ]);
    }
    /** @test */
    public function lead_creation_requires_validation(): void
    {         $response = $this->actingAs($this->user)
            ->post(route('leads.store'), []);
        $response->assertSessionHasErrors(['name']);
    }
    /** @test */
    public function lead_details_can_be_viewed(): void
    {         $lead = Lead::factory()->create();
        $response = $this->actingAs($this->user)
            ->get(route('leads.show', $lead));
        $response->assertStatus(200);
        $response->assertViewIs('leads.show');
        $response->assertSee($lead->name);
    }
    /** @test */
    public function lead_can_be_updated(): void
    {         $lead = Lead::factory()->create(['name' => 'Old Lead Name']);
        $response = $this->actingAs($this->user)
            ->put(route('leads.update', $lead), [
                'name' => 'Updated Lead Name',
                'status' => $lead->status->value ?? $lead->status,
            ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('leads', [
            'id' => $lead->id,
            'name' => 'Updated Lead Name',
        ]);
    }
    /** @test */
    public function lead_status_can_be_updated(): void
    {         $lead = Lead::factory()->create(['status' => LeadStatus::NEW->value]);
        $response = $this->actingAs($this->user)
            ->patch(route('leads.update-status', $lead), [
                'status' => LeadStatus::IN_PROGRESS->value,
            ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('leads', [
            'id' => $lead->id,
            'status' => LeadStatus::IN_PROGRESS->value,
        ]);
    }
    /** @test */
    public function lead_can_be_deleted(): void
    {         $lead = Lead::factory()->create();
        $response = $this->actingAs($this->user)
            ->delete(route('leads.destroy', $lead));
        $response->assertRedirect(route('leads.index'));
        $this->assertDatabaseMissing('leads',
clear
php artisan test --filter=LeadTest
php artisan test --filter=LeadTest
php artisan test --filter=LeadTest
php artisan migrate:fresh
php artisan test --filter=LeadTest
php artisan test --filter=CompanyTest
php artisan test --filter=ContactTest
php artisan test --filter=CompanyTest
