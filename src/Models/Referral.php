<?php

namespace Juzaweb\Modules\Referral\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Juzaweb\Modules\Core\Models\Model;
use Juzaweb\Modules\Core\Traits\HasAPI;

class Referral extends Model
{
    use HasUuids, HasAPI;

    protected $table = 'referrals';

    protected $fillable = [
        'referrer_id',
        'referrer_type',
        'referred_id',
        'referred_type',
    ];

    public function referrer()
    {
        return $this->morphTo();
    }

    public function referred()
    {
        return $this->morphTo();
    }
}
