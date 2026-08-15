<?php

namespace App\Models;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Traits\HasNotes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Override;

class Task extends Model
{
    use HasFactory, HasNotes;


    protected $fillable = [
        'title',
        'description',
        'assigned_to',
        'due_date',
        'priority',
        'status',
        'taskable_id',
        'taskable_type',
    ];

    public function casts()
    {
        return [
            'status' => TaskStatus::class,
            'priority' => TaskPriority::class,
            'dues_date' => 'date'
        ];
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function taskable(): MorphTo
    {
        return $this->morphTo();
    }
}
