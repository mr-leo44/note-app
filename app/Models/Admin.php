<?php

namespace App\Models;

use App\Models\Account;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Admin extends Model
{
    public function account(): MorphOne
    {
        return $this->morphOne(Account::class, 'accountable');
    }
}
