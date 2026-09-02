<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Models\Company;
use App\Services\CompanyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CompanyController extends Controller
{
    public function __construct(
        protected CompanyService $companyService
    ) {}

    // Retrieve paginated companies with search
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Company::class);

        $companies = $this->companyService->getPaginatedCompanies($request->search);

        return view('companies.index', compact('companies'));
    }

    // Show form to create a new company
    public function create(): View
    {
        $this->authorize('create', Company::class);

        return view('companies.create');
    }

    // Store a new company record
    public function store(StoreCompanyRequest $request): RedirectResponse
    {
        $this->authorize('create', Company::class);

        $company = $this->companyService->create($request->validated(), $request->user());

        return redirect()->route('companies.show', $company)
            ->with('success', 'Company created successfully.');
    }

    // Display company details
    public function show(Company $company): View
    {
        $this->authorize('view', $company);

        $company = $this->companyService->getCompanyDetails($company);

        return view('companies.show', compact('company'));
    }

    // Show form to edit an existing company
    public function edit(Company $company): View
    {
        $this->authorize('update', $company);

        return view('companies.edit', compact('company'));
    }

    // Update company details
    public function update(UpdateCompanyRequest $request, Company $company): RedirectResponse
    {
        $this->authorize('update', $company);

        $this->companyService->update($company, $request->validated());

        return redirect()->route('companies.show', $company)
            ->with('success', 'Company updated successfully.');
    }

    // Delete a company record
    public function destroy(Company $company): RedirectResponse
    {
        $this->authorize('delete', $company);

        $this->companyService->delete($company);

        return redirect()->route('companies.index')
            ->with('success', 'Company deleted successfully.');
    }
}