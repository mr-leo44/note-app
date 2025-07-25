<?php

namespace App\Models;

use App\Models\Period;
use App\Models\Result;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ResultSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'short_name',
        'current',
        'period_id',
    ];

    public function period(): BelongsTo
    {
        return $this->belongsTo(Period::class);
    }

    public function results(): HasMany
    {
        return $this->hasMany(Result::class);
    }
    
}
