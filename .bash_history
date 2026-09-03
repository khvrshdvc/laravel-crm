php artisan make:controller Api/DealController
php artisan make:controller Api/TaskController
php artisan test
php artisan test
php artisan test
clear
head -25 tests/Feature/CompanyTest.php
clear
<?php
namespace Tests\Feature;
use App\Enums\UserRole;
use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class CompanyTest extends TestCase
{     use RefreshDatabase;     protected User $user;     protected function setUp(): void
    {         parent::setUp();
        $this->user = User::factory()->create([
            'role' => UserRole::Admin,
        ]);
    }
    public function test_user_can_view_companies_list(): void
    {         $response = $this->actingAs($this->user)->get('/companies');cl
clear
php artisan test --filter=CompanyTest
clear
php artisan test --filter=CompanyTest
php artisan route:list --name=leads
php artisan route:list --name=leads
clear
php artisan test --filter=LeadTest
php artisan test
