<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCompanyRequest;
use App\Models\Company;
use App\Services\CompanyService;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::latest()->paginate(15);

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
        $company->load(['contacts', 'leads', 'deals', 'user']);

        return view('companies.show', compact('company'));
    }

    public function edit(Company $company)
    {
        return view('companies.edit', compact('company'));
    }

    public function update(Request $request, Company $company)
    {
        $company->update($request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'address' => 'nullable|string|max:500',
        ]));

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
