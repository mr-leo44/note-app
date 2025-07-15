<?php

namespace App\Models;

use App\Models\Result;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Period extends Model
{
    protected $fillable = [
        'name',
    ];

    public function results(): HasMany
    {
        return $this->hasMany(Result::class);
    }
}
