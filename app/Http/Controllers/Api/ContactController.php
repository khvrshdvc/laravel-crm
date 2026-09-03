<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ContactResource;
use App\Models\Contact;
use App\Services\ContactService;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function __construct(
        protected ContactService $contactService
    ) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', Contact::class);

        $contacts = $this->contactService->getPaginated(
            filters: $request->only(['company_id', 'search']),
            perPage: 20
        );

        return ContactResource::collection($contacts);
    }

    public function show(Contact $contact)
    {
        $this->authorize('view', $contact);

        $contact->load('company');

        return new ContactResource($contact);
    }
}
