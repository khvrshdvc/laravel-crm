<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDealRequest;
use App\Http\Requests\UpdateDealRequest;
use App\Models\Deal;
use App\Services\DealService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DealController extends Controller
{
    public function __construct(
        protected DealService $dealService
    ) {}

    // Retrieve paginated deals with search and filters
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Deal::class);

        $deals = $this->dealService->getPaginatedDeals(
            filters: $request->only(['search', 'status', 'assigned_to']),
            perPage: 10
        );

        $formData = $this->dealService->getFormDataOptions();
        $users = $formData['users'];

        return view('deals.index', compact('deals', 'users'));
    }

    // Show form to create a new deal
    public function create(Request $request): View
    {
        $this->authorize('create', Deal::class);

        $options = $this->dealService->getFormDataOptions();
        $selectedCompanyId = $request->query('company_id');
        $selectedContactId = $request->query('contact_id');

        return view('deals.create', [
            'companies' => $options['companies'],
            'contacts' => $options['contacts'],
            'users' => $options['users'],
            'selectedCompanyId' => $selectedCompanyId,
            'selectedContactId' => $selectedContactId,
        ]);
    }

    // Store a new deal record
    public function store(StoreDealRequest $request): RedirectResponse
    {
        $this->authorize('create', Deal::class);

        $deal = $this->dealService->create(
            $request->validated(),
            $request->user()
        );

        return redirect()
            ->route('deals.show', $deal)
            ->with('success', 'Deal created successfully.');
    }

    // Display deal details
    public function show(Deal $deal): View
    {
        $this->authorize('view', $deal);

        $deal = $this->dealService->getDealDetails($deal);

        return view('deals.show', compact('deal'));
    }

    // Show form to edit an existing deal
    public function edit(Deal $deal): View
    {
        $this->authorize('update', $deal);

        $options = $this->dealService->getFormDataOptions();

        return view('deals.edit', [
            'deal' => $deal,
            'companies' => $options['companies'],
            'contacts' => $options['contacts'],
            'users' => $options['users'],
        ]);
    }

    // Update deal details
    public function update(UpdateDealRequest $request, Deal $deal): RedirectResponse
    {
        $this->authorize('update', $deal);

        $this->dealService->update($deal, $request->validated());

        return redirect()
            ->route('deals.show', $deal)
            ->with('success', 'Deal updated successfully.');
    }

    // Delete a deal record
    public function destroy(Deal $deal): RedirectResponse
    {
        $this->authorize('delete', $deal);

        $this->dealService->delete($deal);

        return redirect()
            ->route('deals.index')
            ->with('success', 'Deal deleted successfully.');
    }
}
