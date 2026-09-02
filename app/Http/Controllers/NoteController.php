<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNoteRequest;
use App\Http\Requests\UpdateNoteRequest;
use App\Models\Note;
use Illuminate\Http\RedirectResponse;

class NoteController extends Controller
{
    // Store a new note for a polymorphic relation
    public function store(StoreNoteRequest $request): RedirectResponse
    {
        $this->authorize('create', Note::class);

        $validated = $request->validated();

        Note::create([
            'content' => $validated['content'],
            'noteable_type' => $validated['noteable_type'],
            'noteable_id' => $validated['noteable_id'],
            'created_by' => $request->user()->id,
        ]);

        return back()->with('success', 'Note created successfully.');
    }

    // Update an existing note content
    public function update(UpdateNoteRequest $request, Note $note): RedirectResponse
    {
        $this->authorize('update', $note);

        $note->update($request->validated());

        return back()->with('success', 'Note updated successfully.');
    }

    // Delete a note
    public function destroy(Note $note): RedirectResponse
    {
        $this->authorize('delete', $note);

        $note->delete();

        return back()->with('success', 'Note deleted successfully.');
    }
}