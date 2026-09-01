<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Note;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NoteTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function test_user_can_create_note_for_company(): void
    {
        $company = Company::factory()->create();

        $data = [
            'content'       => 'Bu kompaniya bo\'yicha muhim izoh',
            'noteable_type' => 'company',
            'noteable_id'   => $company->id,
        ];

        $response = $this->actingAs($this->user)->post(route('notes.store'), $data);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $this->assertDatabaseHas('notes', [
            'content'       => 'Bu kompaniya bo\'yicha muhim izoh',
            'created_by'    => $this->user->id,
            'noteable_id'   => $company->id,
            'noteable_type' => 'company',
        ]);
    }

    public function test_user_can_update_note(): void
    {
        $company = Company::factory()->create();

        $note = Note::factory()->create([
            'content'       => 'Eski izoh matni',
            'created_by'    => $this->user->id,
            'noteable_type' => 'company',
            'noteable_id'   => $company->id,
        ]);

        $updateData = [
            'content' => 'Yangilangan izoh matni',
        ];

        $response = $this->actingAs($this->user)->put(route('notes.update', $note), $updateData);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $this->assertDatabaseHas('notes', [
            'id'      => $note->id,
            'content' => 'Yangilangan izoh matni',
        ]);
    }

    public function test_user_can_delete_note(): void
    {
        $company = Company::factory()->create();

        $note = Note::factory()->create([
            'created_by'    => $this->user->id,
            'noteable_type' => 'company',
            'noteable_id'   => $company->id,
        ]);

        $response = $this->actingAs($this->user)->delete(route('notes.destroy', $note));

        $response->assertRedirect();

        $this->assertDatabaseMissing('notes', [
            'id' => $note->id,
        ]);
    }
}
