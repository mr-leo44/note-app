<?php
namespace App\Models;

use App\Models\Promotion;
use App\Enums\ResultByPromotionStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResultStatus extends Model
{
    use HasFactory;

    protected $table = 'result_status';
    protected $fillable = [
        'promotion_id',
        'status',
        'session',
    ];

    protected $casts = [
        'status' => ResultByPromotionStatus::class,
    ];
    /**
     * Get the promotion associated with the ResultStatus
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function promotion(): BelongsTo
    {
        return $this->belongsTo(Promotion::class);
    }

    /**
     * Get the result associated with the ResultStatus
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function result(): BelongsTo
    {
        return $this->belongsTo(Result::class);
    }
}
