<?php

namespace App\Models;

use App\Models\Promotion;
use App\Models\CourseCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Course extends Model
{
    protected $fillable = [
        'name',
        'course_category_id',
    ];

    public function promotions(): BelongsToMany
    {
        return $this->belongsToMany(Promotion::class)->withPivot('maxima');
    }

    public function course_category(): BelongsTo
    {
        return $this->belongsTo(CourseCategory::class);
    }
}
