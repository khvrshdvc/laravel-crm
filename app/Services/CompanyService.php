<?php

namespace App\Services;

use App\Models\Company;
use App\Models\User;

class CompanyService
{
    public function create(array $data, User $user): Company
    {
        return Company::create([
            ...$data,
            'created_by' => $user->id,
        ]);
    }

    public function update(Company $company, array $data): Company
    {
        $company->update($data);
        return $company;
    }

    public function delete(Company $company): void
    {
        $company->delete();
    }
}