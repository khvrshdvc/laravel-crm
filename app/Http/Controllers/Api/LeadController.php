<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LeadResource;
use App\Models\Lead;
use App\Services\LeadService;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function __construct(
        protected LeadService $leadService
    ) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', Lead::class);

        $leads = $this->leadService->getPaginated(
            filters: $request->only(['company_id', 'status', 'search', 'assigned_to']),
            perPage: 15
        );

        return LeadResource::collection($leads);
    }

    public function show(Lead $lead)
    {
        $this->authorize('view', $lead);

        $lead->load(['company', 'assignedTo']);

        return new LeadResource($lead);
    }
}
