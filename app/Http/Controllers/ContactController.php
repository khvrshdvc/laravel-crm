<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequest;
use App\Http\Requests\UpdateContactRequest;
use App\Models\Company;
use App\Models\Contact;
use App\Services\ContactService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;


class ContactController extends Controller
{

    public function __construct(
        protected ContactService $contactService
    ) {}

    public function index(Request $request): View
    {
        $this->authorize('viewAny', Contact::class);

        $contacts = $this->contactService->getPaginated(
            filters: $request->only(['company_id', 'search']),
            perPage: 20
        );

        return view('contacts.index', compact('contacts'));
    }

    public function create(): View
    {
        $this->authorize('create', Contact::class);

        $companies = Company::select('id', 'name')->orderBy('name')->get();

        return view('contacts.create', compact('companies'));
    }

    public function store(StoreContactRequest $request): RedirectResponse
    {
        $this->authorize('create', Contact::class);

        $contact = $this->contactService->create(
            data: $request->validated(),
            user: $request->user()
        );

        return redirect()
            ->route('contacts.show', $contact)
            ->with('success', 'Kontakt muvaffaqiyatli yaratildi.');
    }

    public function show(Contact $contact): View
    {
        $this->authorize('view', $contact);

        $contact->load(['company', 'createdBy', 'notes.user']);

        return view('contacts.show', compact('contact'));
    }

    public function edit(Contact $contact): View
    {
        $this->authorize('update', $contact);

        $companies = Company::select('id', 'name')->orderBy('name')->get();

        return view('contacts.edit', compact('contact', 'companies'));
    }

    public function update(UpdateContactRequest $request, Contact $contact): RedirectResponse
    {
        $this->authorize('update', $contact);

        $this->contactService->update($contact, $request->validated());

        return redirect()
            ->route('contacts.show', $contact)
            ->with('success', 'Kontakt muvaffaqiyatli yangilandi.');
    }

    public function destroy(Contact $contact): RedirectResponse
    {
        $this->authorize('delete', $contact);

        $this->contactService->delete($contact);

        return redirect()
            ->route('contacts.index')
            ->with('success', 'Kontakt muvaffaqiyatli o\'chirildi.');
    }
}
