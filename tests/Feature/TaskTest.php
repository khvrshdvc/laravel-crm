<?php

namespace Tests\Feature;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Enums\UserRole;
use App\Models\Company;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'role' => UserRole::Admin,
        ]);
    }

    public function test_user_can_view_tasks_list(): void
    {
        $response = $this->actingAs($this->user)->get(route('tasks.index'));

        $response->assertStatus(200);
    }

    public function test_user_can_create_task(): void
    {
        $company = Company::factory()->create();

        $data = [
            'title' => 'Loyiha arxitekturasini ko\'rib chiqish',
            'description' => 'Yangi modul bo\'yicha texnik topshiriqni tahlil qilish',
            'status' => TaskStatus::cases()[0]->value,
            'priority' => TaskPriority::cases()[0]->value,
            'taskable_type' => 'company',
            'taskable_id' => $company->id,
            'assigned_to' => $this->user->id,
            'due_date' => now()->addDays(3)->format('Y-m-d'),
        ];

        $response = $this->actingAs($this->user)->post(route('tasks.store'), $data);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $this->assertDatabaseHas('tasks', [
            'title' => 'Loyiha arxitekturasini ko\'rib chiqish',
            'taskable_id' => $company->id,
            'taskable_type' => 'company',
            'assigned_to' => $this->user->id,
        ]);
    }

    public function test_user_can_view_task_details(): void
    {
        $task = Task::factory()->create([
            'title' => 'Muhim muloqot o\'tkazish',
            'description' => 'Mijoz bilan shartnoma shartlarini kelishish',
            'assigned_to' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)->get(route('tasks.show', $task));

        $response->assertStatus(200);
        $response->assertSee('Muhim muloqot o\'tkazish');
        $response->assertSee('Mijoz bilan shartnoma shartlarini kelishish');
    }

    public function test_user_can_update_task(): void
    {
        $task = Task::factory()->create([
            'title' => 'Eski topshiriq sarlavhasi',
            'assigned_to' => $this->user->id,
        ]);

        $newCompany = Company::factory()->create();

        $updateData = [
            'title' => 'Yangilangan topshiriq sarlavhasi',
            'description' => 'Topshiriq tavsifi yangilandi',
            'status' => TaskStatus::cases()[0]->value,
            'priority' => TaskPriority::cases()[0]->value,
            'taskable_type' => 'company',
            'taskable_id' => $newCompany->id,
            'assigned_to' => $this->user->id,
            'due_date' => now()->addDays(5)->format('Y-m-d'),
        ];

        $response = $this->actingAs($this->user)->put(route('tasks.update', $task), $updateData);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Yangilangan topshiriq sarlavhasi',
            'taskable_id' => $newCompany->id,
            'taskable_type' => 'company',
            'assigned_to' => $this->user->id,
        ]);
    }

    public function test_user_can_delete_task(): void
    {
        $task = Task::factory()->create([
            'assigned_to' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)->delete(route('tasks.destroy', $task));

        $response->assertRedirect();
        $this->assertDatabaseMissing('tasks', [
            'id' => $task->id,
        ]);
    }
}
