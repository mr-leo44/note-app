<?php

namespace App\Models;

use App\Models\Period;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ResultSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'current',
        'period_id',
    ];

    public function period(): BelongsTo
    {
        return $this->belongsTo(Period::class);
    }
}
