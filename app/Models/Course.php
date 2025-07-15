<?php

namespace App\Models;

use App\Models\Promotion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Course extends Model
{
    protected $fillable = [
        'name',
    ];

    public function promotions(): BelongsToMany
    {
        return $this->belongsToMany(Promotion::class)->withPivot('maxima');
    }
}
