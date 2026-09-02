<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Contact;
use App\Models\Lead;
use App\Models\Note;
use App\Models\User;
use Illuminate\Database\Seeder;

class NoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        Company::all()->each(function ($company) use ($users) {
            Note::factory()->count(2)->create([
                'noteable_type' => Company::class,
                'noteable_id' => $company->id,
                'created_by' => $users->random()->id ?? User::factory(),
            ]);
        });

        Lead::all()->each(function ($lead) use ($users) {
            Note::factory()->count(2)->create([
                'noteable_type' => Lead::class,
                'noteable_id' => $lead->id,
                'created_by' => $users->random()->id ?? User::factory(),
            ]);
        });

        Contact::all()->each(function ($contact) use ($users) {
            Note::factory()->count(2)->create([
                'noteable_type' => Contact::class,
                'noteable_id' => $contact->id,
                'created_by' => $users->random()->id ?? User::factory(),
            ]);
        });
    }
}
