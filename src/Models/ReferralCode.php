<?php

namespace Juzaweb\Modules\Referral\Models;

use Illuminate\Database\Eloquent\Relations\MorphTo;
use Juzaweb\Modules\Core\Models\Model;

class ReferralCode extends Model
{
    protected $table = 'referral_codes';

    protected $fillable = [
        'referrer_id',
        'referrer_type',
        'code',
    ];

    public function referrer(): MorphTo
    {
        return $this->morphTo();
    }
}
