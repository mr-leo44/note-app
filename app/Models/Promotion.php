<?php

namespace App\Models;

use App\Models\Course;
use App\Models\Student;
use App\Models\Department;
use App\Models\JuryPromotion;
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

    public function juries(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\Jury::class)
            ->using(JuryPromotion::class)
            ->withTimestamps();
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class);
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'promotion_student')
            ->withPivot(['period', 'status'])
            ->withTimestamps();
    }
}
