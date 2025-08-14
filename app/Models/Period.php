<?php

namespace App\Models;

use App\Models\Semester;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Period extends Model
{
    protected $fillable = [
        'name',
        'current',
    ];

    public function semesters(): HasMany
    {
        return $this->hasMany(Semester::class);
    }
}
