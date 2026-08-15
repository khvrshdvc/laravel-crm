<?php

namespace App\Models;

use App\Traits\HasNotes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory, HasNotes;

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    
}
