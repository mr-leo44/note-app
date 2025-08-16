<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class JuryPromotion extends Pivot
{
    use SoftDeletes;

    protected $fillable = ['jury_id', 'promotion_id', 'semester_id'];
    protected $table = 'jury_promotion';
    protected $dates = ['deleted_at'];
}
