<?php

namespace App\Models;

use App\Models\Account;
use App\Models\Promotion;
use App\Models\JuryPromotion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Jury extends Model
{
    use HasFactory, SoftDeletes;
    
    public function account(): MorphOne
    {
        return $this->morphOne(Account::class, 'accountable');
    }

    public function promotions(): BelongsToMany
    {
        return $this->belongsToMany(Promotion::class)
            ->using(JuryPromotion::class)
            ->withTimestamps();
    }
}
