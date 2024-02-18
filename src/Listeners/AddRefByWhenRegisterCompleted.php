<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Referral\Listeners;

use Illuminate\Support\Facades\Cookie;
use Juzaweb\Backend\Events\Users\RegisterCompleted;
use Juzaweb\CMS\Models\User;

class AddRefByWhenRegisterCompleted
{
    public function handle(RegisterCompleted $event): void
    {
        if (!get_config('referral_enable')) {
            return;
        }

        try {
            $this->referralHandle($event);
        } catch (\Throwable $e) {
            report($e);
        }
    }

    protected function referralHandle($event): void
    {
        if (!($code = Cookie::get('ref_code'))) {
            return;
        }

        $refUser = User::where('referral_code', $code)->first();

        if ($refUser === null) {
            return;
        }

        $event->user->setAttribute('ref_by', $refUser->id);
        $event->user->save();

        Cookie::queue(Cookie::forget('ref_code'));

        if (
            get_config('referral_credit_on_register', 0)
            && get_config('referral_credit_on_register_number', 0) > 0
            && plugin_enabled('juzaweb/user-credit')
        ) {
            referral_earn(
                $refUser,
                $event->user,
                'credit',
                get_config('referral_credit_on_register_number'),
                'Referral credit on register'
            );
        }
    }
}
