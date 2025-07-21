<?php

namespace App\Models;

use App\Models\Period;
use App\Models\Student;
use App\Enums\ResultMention;
use App\Models\ResultSession;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Result extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'student_id',
        'notes',
        'mention',
        'result_session_id',
        'percentage',
        'published_by'
    ];

    protected $casts = [
        'notes' => 'array',
        'mention' => ResultMention::class, // Enum cast
    ];

    public function resultSession()
    {
        return $this->belongsTo(ResultSession::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
