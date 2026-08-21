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
    public function index()
    {
        $deals = Deal::with([
            'company',
            'contact',
            'assignedUser',
        ])->latest()->paginate(10);

        return view('deals.index', compact('deals'));
    }

    public function create(Request $request)
    {
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
        $deal->load([
            'lead',
            'company',
            'contact',
            'assignedUser',
            'creator',
        ]);

        return view('deals.show', compact('deal'));
    }

    public function edit(Deal $deal)
    {
    
        $companies = Company::query()->orderBy('name')->get();
        $contacts  = Contact::query()->orderBy('first_name')->get();
        $users     = User::query()->orderBy('name')->get();

        return view('deals.edit', compact('deal', 'companies', 'contacts', 'users'));
    }

    public function update(UpdateDealRequest $request, Deal $deal, DealService $service)
    {
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
        $service->delete($deal);

        return redirect()
            ->route('deals.index')
            ->with('success', 'Deal deleted successfully!');
    }
}
