<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = [
        'accountable_type',
        'accountable_id',
        'user_id',
    ];
}
