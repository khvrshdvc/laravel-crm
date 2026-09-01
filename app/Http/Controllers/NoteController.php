<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNoteRequest;
use App\Http\Requests\UpdateNoteRequest;
use App\Models\Note;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    public function store(StoreNoteRequest $request)
    {
        $validated = $request->validated();

        
        Note::create([
            'content'       => $validated['content'],
            'noteable_type' => $validated['noteable_type'],
            'noteable_id'   => $validated['noteable_id'],
            'user_id'       => Auth::id(), // <-- SHU YERDA SAQLANISHI SHART
        ]);

        return back()->with('success', 'Note qo\'shildi!');
    }
    public function update(UpdateNoteRequest $request, Note $note): RedirectResponse
    {
        $validated = $request->validated();

        $note->update([
            'content' => $validated['content'],
        ]);

        return redirect()->back()->with('success', 'Izoh muvaffaqiyatli yangilandi.');
    }

    public function destroy(Note $note): RedirectResponse
    {
        $note->delete();

        return redirect()->back()->with('success', 'Izoh o\'chirildi.');
    }
}
