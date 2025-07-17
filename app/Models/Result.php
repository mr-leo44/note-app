<?php

namespace App\Models;

use App\Models\Period;
use App\Models\Student;
use App\Enums\ResultMention;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Result extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'period_id',
        'student_id',
        'notes',
        'mention',
        'percentage',
        'published_by'
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
