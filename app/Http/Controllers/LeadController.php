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
        $leads = $this->leadService->getPaginated(
            filters: $request->only(['company_id', 'status']),
            perPage: 15
        );

        return view('leads.index', compact('leads'));
    }

    public function create(): View
    {
        $companies = Company::select('id', 'name')->orderBy('name')->get();
        $contacts = Contact::select('id', 'first_name', 'last_name')->get();
        $users = User::select('id', 'name')->orderBy('name')->get();

        return view('leads.create', compact('companies', 'contacts', 'users'));
    }

    public function store(StoreLeadRequest $request): RedirectResponse
    {
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
        $lead->load(['company', 'contact', 'deal', 'notes.user']);

        $users = User::query()
            ->orderBy('name')
            ->get();

        return view('leads.show', compact('lead', 'users'));
    }

    public function edit(Lead $lead): View
    {
        $companies = Company::select('id', 'name')->orderBy('name')->get();
        $contacts = Contact::select('id', 'first_name', 'last_name')->get();
        $users = User::select('id', 'name')->orderBy('name')->get();

        return view('leads.edit', compact('lead', 'companies', 'contacts', 'users'));
    }

    public function update(StoreLeadRequest $request, Lead $lead): RedirectResponse
    {
        $this->leadService->update($lead, $request->validated());

        return redirect()
            ->route('leads.show', $lead)
            ->with('success', 'Lead muvaffaqiyatli yangilandi.');
    }

    public function updateStatus(Request $request, Lead $lead): RedirectResponse
    {
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
        $this->leadService->delete($lead);

        return redirect()
            ->route('leads.index')
            ->with('success', 'Lead muvaffaqiyatli o\'chirildi.');
    }

    public function showConvertForm(Lead $lead)
    {
        $users = User::all();
        return view('leads.convert', compact('lead', 'users'));
    }

    public function convert(ConvertLeadRequest $request, Lead $lead, LeadService $service)
    {
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
