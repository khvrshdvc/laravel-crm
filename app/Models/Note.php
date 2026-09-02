<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'created_by',
        'noteable_id',
        'noteable_type',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function noteable()
    {
        return $this->morphTo();
    }
}
