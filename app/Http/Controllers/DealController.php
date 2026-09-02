<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDealRequest;
use App\Http\Requests\UpdateDealRequest;
use App\Models\Company;
use App\Models\Contact;
use App\Models\Deal;
use App\Models\User;
use App\Services\DealService;
use Illuminate\Http\Request;

class DealController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Deal::class);

        $deals = Deal::with(['company', 'contact', 'assignedUser'])
            ->when($request->search, fn($query, $search) => $query->where('title', 'like', "%{$search}%"))
            ->when($request->status, fn($query, $status) => $query->where('status', $status))
            ->when($request->assigned_to, fn($query, $user) => $query->where('assigned_to', $user))
            ->latest()
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        $users = User::orderBy('name')->get();

        return view('deals.index', compact('deals', 'users'));
    }

    public function create(Request $request)
    {
        $this->authorize('create', Deal::class);

        $companies = Company::query()->orderBy('name')->get();
        $contacts  = Contact::query()->orderBy('first_name')->get();
        $users     = User::query()->orderBy('name')->get();


        $selectedCompanyId = $request->query('company_id');
        $selectedContactId = $request->query('contact_id');

        return view('deals.create', compact(
            'companies',
            'contacts',
            'users',
            'selectedCompanyId',
            'selectedContactId'
        ));
    }

    public function store(StoreDealRequest $request, DealService $service)
    {
        $this->authorize('create', Deal::class);

        $deal = $service->create(
            $request->validated(),
            $request->user()
        );

        return redirect()
            ->route('deals.show', $deal)
            ->with('success', 'Deal created successfully!');
    }

    public function show(Deal $deal)
    {
        $this->authorize('view', $deal);

        $deal->load(['lead', 'company', 'contact', 'assignedUser', 'creator', 'notes.user']);

        return view('deals.show', compact('deal'));
    }

    public function edit(Deal $deal)
    {
        $this->authorize('update', $deal);

        $companies = Company::query()->orderBy('name')->get();
        $contacts  = Contact::query()->orderBy('first_name')->get();
        $users     = User::query()->orderBy('name')->get();

        return view('deals.edit', compact('deal', 'companies', 'contacts', 'users'));
    }

    public function update(UpdateDealRequest $request, Deal $deal, DealService $service)
    {
        $this->authorize('update', $deal);

        $service->update(
            $deal,
            $request->validated()
        );

        return redirect()
            ->route('deals.show', $deal)
            ->with('succes', 'Deal updated successfully!');
    }

    public function destroy(
        Deal $deal,
        DealService $service
    ) {
        $this->authorize('delete', $deal);

        $service->delete($deal);

        return redirect()
            ->route('deals.index')
            ->with('success', 'Deal deleted successfully!');
    }
}
