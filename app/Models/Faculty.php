<?php

namespace App\Models;

use App\Models\Department;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Faculty extends Model
{
    protected $fillable = [
        'name',
        'short_name',
    ];

    public function departments(): HasMany
    {
        return $this->hasMany(Department::class);
    }
}
