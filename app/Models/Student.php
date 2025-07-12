<?php

namespace App\Models;

use App\Models\Result;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Student extends Model
{
    protected $fillable = [
        'name',
        'matricule',
        'promotion_id',
    ];

    public function results(): HasMany
    {
        return $this->hasMany(Result::class);
    }

    public function promotion() : BelongsTo
    {
        return $this->belongsTo(Promotion::class);
    }
}
