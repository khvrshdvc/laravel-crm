<?php

namespace App\Models;

use App\Traits\HasNotes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contact extends Model
{
    use HasFactory, HasNotes;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'position',
        'company_id',
        'created_by',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
