<?php

namespace App\Models;

use App\Models\Account;
use App\Models\Promotion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Jury extends Model
{
    public function account(): MorphOne
    {
        return $this->morphOne(Account::class, 'accountable');
    }

    public function promotions(): BelongsToMany
    {
        return $this->belongsToMany(Promotion::class);
    }
}
