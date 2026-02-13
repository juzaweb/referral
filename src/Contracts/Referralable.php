<?php

namespace Juzaweb\Modules\Referral\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Juzaweb\Modules\Referral\Models\Referral;

interface Referralable
{
    public function referralsSent(): MorphMany;

    public function referralsReceived(): MorphMany;

    public function refer(Model $referred): Referral;
}
