<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequest;
use App\Http\Requests\UpdateContactRequest;
use App\Models\Company;
use App\Models\Contact;
use App\Services\ContactService;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::with('company')->paginate(20);
        return view('contacts.index', compact('contacts'));
    }

    public function create()
    {
        $companies = Company::all();
        return view('contacts.create', compact('companies'));
    }

    public function store(StoreContactRequest $request, ContactService $service)
    {
        $contact = $service->create($request->validated(), $request->user());

        return redirect()->route('contacts.show', $contact)
            ->with('success', 'Contact created.');
    }

    public function show(Contact $contact)
    {
        return view('contacts.show', compact('contact'));
    }

    public function edit(Contact $contact)
    {
        $companies = Company::all();
        return view('contacts.edit', compact('contact', 'companies'));
    }

    public function update(UpdateContactRequest $request, Contact $contact, ContactService $service)
    {
        $service->update($contact, $request->validated());

        return redirect()->route('contacts.show', $contact)
            ->with('success', 'Contact updated.');
    }

    public function destroy(Contact $contact, ContactService $service)
    {
        $service->delete($contact);

        return redirect()->route('contacts.index')
            ->with('success', 'Contact deleted.');
    }
}
