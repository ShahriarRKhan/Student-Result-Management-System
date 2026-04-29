<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    protected $table = 'subjects';

    protected $fillable = [
        'subject_code',
        'subject_name',
        'credit'
        
    ];

    public function results(): HasMany
    {
        return $this->hasMany(Result::class, 'subject_id', 'subject_code');
    }
}
