<?php

namespace App\Models;

use App\Enums\LeadStatus;
use App\Traits\HasNotes;
use App\Traits\HasTasks;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lead extends Model
{
    use HasFactory, HasNotes, HasTasks;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'source',
        'status',
        'company_id',
        'contact_id',
        'assigned_to',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'status' => LeadStatus::class,
        ];
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function deal()
    {
        return $this->hasOne(Deal::class);
    }

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function notes()
    {
        return $this->morphMany(Note::class, 'noteable')->latest();
    }
}
