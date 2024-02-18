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
use Juzaweb\Backend\Events\Users\RegisterSuccessful;
use Juzaweb\CMS\Models\User;

class AddRefByWhenRegisterSuccess
{
    public function handle(RegisterSuccessful $event): void
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
    }
}
