<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use App\Services\CompanyService;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function __construct(
        protected CompanyService $companyService
    ) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', Company::class);

        $companies = $this->companyService->getPaginatedCompanies($request->search);

        return CompanyResource::collection($companies);
    }

    public function show(Company $company)
    {
        $this->authorize('view', $company);

        return new CompanyResource($company);
    }
}