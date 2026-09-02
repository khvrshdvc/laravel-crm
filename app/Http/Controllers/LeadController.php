<?php

namespace App\Http\Controllers;

use App\Enums\LeadStatus;
use App\Http\Requests\ConvertLeadRequest;
use App\Http\Requests\StoreLeadRequest;
use App\Models\Company;
use App\Models\Contact;
use App\Models\Lead;
use App\Models\User;
use App\Services\LeadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;
use Illuminate\View\View;

class LeadController extends Controller
{
    public function __construct(
        protected LeadService $leadService
    ) {}

    public function index(Request $request): View
    {
        $this->authorize('viewAny', Lead::class);

        $leads = $this->leadService->getPaginated(
            filters: $request->only(['company_id', 'status', 'search', 'assigned_to']),
            perPage: 15
        );

        $users = User::orderBy('name')->get();

        return view('leads.index', compact('leads', 'users'));
    }

    public function create(): View
    {
        $this->authorize('create', Lead::class);

        $companies = Company::select('id', 'name')->orderBy('name')->get();
        $contacts = Contact::select('id', 'first_name', 'last_name')->get();
        $users = User::select('id', 'name')->orderBy('name')->get();

        return view('leads.create', compact('companies', 'contacts', 'users'));
    }

    public function store(StoreLeadRequest $request): RedirectResponse
    {
        $this->authorize('create', Lead::class);

        $lead = $this->leadService->create(
            data: $request->validated(),
            user: $request->user()
        );

        return redirect()
            ->route('leads.show', $lead)
            ->with('success', 'Lead muvaffaqiyatli yaratildi.');
    }

    public function show(Lead $lead)
    {
        $this->authorize('view', $lead);

        $lead->load(['company', 'contact', 'deal', 'notes.user']);

        $users = User::query()
            ->orderBy('name')
            ->get();

        return view('leads.show', compact('lead', 'users'));
    }

    public function edit(Lead $lead): View
    {
        $this->authorize('update', $lead);

        $companies = Company::select('id', 'name')->orderBy('name')->get();
        $contacts = Contact::select('id', 'first_name', 'last_name')->get();
        $users = User::select('id', 'name')->orderBy('name')->get();

        return view('leads.edit', compact('lead', 'companies', 'contacts', 'users'));
    }

    public function update(StoreLeadRequest $request, Lead $lead): RedirectResponse
    {
        $this->authorize('update', $lead);

        $this->leadService->update($lead, $request->validated());

        return redirect()
            ->route('leads.show', $lead)
            ->with('success', 'Lead muvaffaqiyatli yangilandi.');
    }

    public function updateStatus(Request $request, Lead $lead): RedirectResponse
    {
        $this->authorize('update', $lead);

        $request->validate([
            'status' => ['required', new Enum(LeadStatus::class)],
        ]);

        $this->leadService->updateStatus(
            lead: $lead,
            status: LeadStatus::from($request->status)
        );

        return back()->with('success', 'Status muvaffaqiyatli yangilandi.');
    }

    public function destroy(Lead $lead): RedirectResponse
    {
        $this->authorize('delete', $lead);

        $this->leadService->delete($lead);

        return redirect()
            ->route('leads.index')
            ->with('success', 'Lead muvaffaqiyatli o\'chirildi.');
    }

    public function showConvertForm(Lead $lead)
    {
        $this->authorize('update', $lead);

        $users = User::all();
        return view('leads.convert', compact('lead', 'users'));
    }

    public function convert(ConvertLeadRequest $request, Lead $lead, LeadService $service)
    {
        $this->authorize('update', $lead);

        $deal = $service->convertToDeal(
            $lead,
            $request->validated(),
            $request->user()
        );

        return redirect()
            ->route('deals.show', $deal)
            ->with('success', 'Lead successfully converted to deal!');
    }
}
