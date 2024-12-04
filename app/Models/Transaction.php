<?php

namespace App\Models;

use App\Enums\ErrorCodes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperTransaction
 */
class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'amount',
        'description',
        'is_successful',
        'failure_reason',
        'error_code',
        'completed_at',
    ];

    protected $casts = [
        'is_successful' => 'boolean',
        'completed_at'  => 'datetime',
        'error_code'    => ErrorCodes::class,
    ];

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
