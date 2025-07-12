<?php

namespace App\Models;

use App\Models\Period;
use App\Models\Student;
use App\Enums\ResultMention;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Result extends Model
{
    protected $fillable = [
        'period_id',
        'student_id',
        'notes',
        'mention',
        'percentage', // Assuming a percentage field for the result
    ];

    protected $casts = [
        'notes' => 'array',
        'mention' => ResultMention::class, // Enum cast
    ];

    public function period()
    {
        return $this->belongsTo(Period::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
