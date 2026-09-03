<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DealResource;
use App\Models\Deal;
use App\Services\DealService;
use Illuminate\Http\Request;

class DealController extends Controller
{
    public function __construct(
        protected DealService $dealService
    ) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', Deal::class);

        $deals = $this->dealService->getPaginatedDeals(
            filters: $request->only(['search', 'status', 'assigned_to']),
            perPage: 10
        );

        return DealResource::collection($deals);
    }

    public function show(Deal $deal)
    {
        $this->authorize('view', $deal);

        $deal->load(['company', 'contact', 'assignedUser']);

        return new DealResource($deal);
    }
}
