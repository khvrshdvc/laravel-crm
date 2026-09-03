<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status->value ?? $this->status,
            'priority' => $this->priority->value ?? $this->priority,
            'due_date' => $this->due_date,
            'assigned_user' => $this->whenLoaded('assignedUser', fn() => [
                'id' => $this->assignedUser->id,
                'name' => $this->assignedUser->name,
            ]),
            'related_to' => $this->whenLoaded('taskable', fn() => $this->taskable ? [
                'type' => class_basename($this->taskable_type),
                'id' => $this->taskable->id,
                'name' => $this->taskable->name ?? $this->taskable->title ?? null,
            ] : null),
            'created_at' => $this->created_at,
        ];
    }
}
