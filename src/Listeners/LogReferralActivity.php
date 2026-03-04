<?php

namespace Juzaweb\Modules\Referral\Listeners;

use Illuminate\Auth\Events\Registered;
use Juzaweb\Modules\Referral\Contracts\Referralable;
use Juzaweb\Modules\Referral\Models\ReferralCode;

class LogReferralActivity
{
    /**
     * Handle the event.
     *
     * @param  Registered  $event
     * @return void
     */
    public function handle(Registered $event)
    {
        if (!$event->user instanceof Referralable) {
            return;
        }

        $referralCode = request()->cookie('referral_code');

        if (!$referralCode) {
            return;
        }

        try {
            $referrerCodeModel = ReferralCode::where('code', $referralCode)->first();

            if (!$referrerCodeModel || !$referrerCodeModel->referrer) {
                return;
            }

            $referrer = $referrerCodeModel->referrer;

            $referrer->refer($event->user);
        } catch (\Exception $e) {
            // Log the exception or handle it as needed
            report($e);
        }
    }
}
