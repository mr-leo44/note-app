<?php

namespace App\Models;

use App\Models\Result;
use App\Models\Promotion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Student extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'name',
        'matricule',
        'gender',
    ];

    public function results(): HasMany
    {
        return $this->hasMany(Result::class);
    }

    public function promotions(): BelongsToMany
    {
        return $this->belongsToMany(Promotion::class, 'promotion_student')
            ->withPivot(['period', 'status'])
            ->withTimestamps();
    }
}
