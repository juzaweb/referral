<?php

namespace Juzaweb\Referral\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Juzaweb\CMS\Models\Model;
use Juzaweb\CMS\Models\User;

class ReferralUserEarnHistory extends Model
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_COMPLETED = 'completed';

    public const EARN_TYPE_REGISTER = 'register';

    protected $table = 'referral_user_earn_histories';

    protected $fillable = [
        'title',
        'user_id',
        'earn_type',
        'earn_number',
        'referred_user_id',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function referredUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referred_user_id', 'id');
    }
}
