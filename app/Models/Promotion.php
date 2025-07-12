<?php

namespace App\Models;

use App\Models\Course;
use App\Models\Department;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Promotion extends Model
{
    protected $fillable = [
        'name',
        'short_name',
        'department_id',
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function promotions(): BelongsToMany
    {
        return $this->belongsToMany(Course::class);
    }
}
