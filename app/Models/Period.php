<?php

namespace App\Models;

use App\Models\ResultSession;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Period extends Model
{
    protected $fillable = [
        'name',
        'current',
    ];

    public function resultSessions(): HasMany
    {
        return $this->hasMany(ResultSession::class);
    }
}
