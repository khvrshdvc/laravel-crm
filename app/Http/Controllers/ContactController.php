<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequest;
use App\Http\Requests\UpdateContactRequest;
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

    // Retrieve paginated contacts with filters and search
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Contact::class);

        $contacts = $this->contactService->getPaginated(
            filters: $request->only(['company_id', 'search']),
            perPage: 20
        );

        return view('contacts.index', compact('contacts'));
    }

    // Show form to create a new contact
    public function create(): View
    {
        $this->authorize('create', Contact::class);

        $companies = $this->contactService->getCompanyOptions();

        return view('contacts.create', compact('companies'));
    }

    // Store a new contact record
    public function store(StoreContactRequest $request): RedirectResponse
    {
        $this->authorize('create', Contact::class);

        $contact = $this->contactService->create(
            data: $request->validated(),
            user: $request->user()
        );

        return redirect()
            ->route('contacts.show', $contact)
            ->with('success', 'Contact created successfully.');
    }

    // Display contact details with relations
    public function show(Contact $contact): View
    {
        $this->authorize('view', $contact);

        $contact = $this->contactService->getContactDetails($contact);

        return view('contacts.show', compact('contact'));
    }

    // Show form to edit an existing contact
    public function edit(Contact $contact): View
    {
        $this->authorize('update', $contact);

        $companies = $this->contactService->getCompanyOptions();

        return view('contacts.edit', compact('contact', 'companies'));
    }

    // Update contact details
    public function update(UpdateContactRequest $request, Contact $contact): RedirectResponse
    {
        $this->authorize('update', $contact);

        $this->contactService->update($contact, $request->validated());

        return redirect()
            ->route('contacts.show', $contact)
            ->with('success', 'Contact updated successfully.');
    }

    // Delete a contact record
    public function destroy(Contact $contact): RedirectResponse
    {
        $this->authorize('delete', $contact);

        $this->contactService->delete($contact);

        return redirect()
            ->route('contacts.index')
            ->with('success', 'Contact deleted successfully.');
    }
}