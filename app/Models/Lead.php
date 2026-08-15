<?php

namespace App\Models;

use App\Traits\HasNotes;
use App\Traits\HasTasks;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory, HasTasks, HasNotes;

    public function company()
    {
       return $this->belongsTo(Company::class);
    }

    public function deal()
    {
        return $this->hasOne(Deal::class);
    }
}
