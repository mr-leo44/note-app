<?php

namespace App\Models;

use App\Models\Course;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CourseCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'short_name',
        'category_alias',
        'ue',
    ];

    public function courses (): HasMany
    {
        return $this->hasMany(Course::class);
    }
}
