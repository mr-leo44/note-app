<?php

namespace App\Models;

use App\Models\Period;
use App\Models\ResultSession;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Semester extends Model
{
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

    public function result_sessions(): HasMany
    {
        return $this->hasMany(ResultSession::class);
    }
}
