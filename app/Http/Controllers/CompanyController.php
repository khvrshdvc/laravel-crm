<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Models\Company;
use App\Services\CompanyService;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::latest()->paginate(10);

        return view('companies.index', compact('companies'));
    }

    public function create()
    {
        return view('companies.create');
    }

    public function store(StoreCompanyRequest $request, CompanyService $service)
    {
        $company = $service->create($request->validated(), $request->user());

        return redirect()->route('companies.show', $company)
            ->with('success', 'Company created!');
    }

    public function show(Company $company)
    {
        $company->loadCount(['contacts','leads','deals','tasks','notes']);
        $company->load(['contacts','deals','tasks','notes.user']);

        return view('companies.show', compact('company'));
    }

    public function edit(Company $company)
    {
        return view('companies.edit', compact('company'));
    }

    public function update(UpdateCompanyRequest $request, Company $company)
    {
        $company->update($request->validated());

        return redirect()->route('companies.show', $company)
            ->with('success', 'Company updated!');
    }

    public function destroy(Company $company)
    {
        $company->delete();

        return redirect()->route('companies.index')
            ->with('success', 'Company deleted!');
    }
}
