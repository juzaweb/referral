<?php

namespace Juzaweb\Modules\Referral\Listeners;

use Illuminate\Auth\Events\Registered;
use Juzaweb\Modules\Referral\Contracts\Referralable;

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
            $referrer = get_class($event->user)::where('referral_code', $referralCode)->first();

            if (!$referrer) {
                return;
            }

            $referrer->refer($event->user);
        } catch (\Exception $e) {
            // Log the exception or handle it as needed
            report($e);
        }
    }
}
