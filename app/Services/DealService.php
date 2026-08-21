<?php

namespace App\Services;

use App\Models\Deal;
use App\Models\User;

class DealService
{
    public function create(array $data, User $user): Deal
    {
        return Deal::create([
            ...$data,
            'created_by' => $user->id,
        ]);
    }

    public function update(Deal $deal, array $data): Deal
    {
        $deal->update($data);

        return $deal->fresh();
    }

    public function delete(Deal $deal): void
    {
        $deal->delete();
    }
}
