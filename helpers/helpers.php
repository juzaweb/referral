<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com
 * @license    GNU V2
 */

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Juzaweb\CMS\Models\User;
use Juzaweb\Referral\Models\ReferralUserEarnHistory;

function generate_referral_code(): string
{
    do {
        $code = Str::random(8);
    } while (User::where('referral_code', $code)->exists());

    return $code;
}

function referral_earn(
    User $user,
    User $referredUser,
    string $type,
    float $number,
    string $title
): void {
    DB::transaction(
        function () use ($user, $referredUser, $type, $number, $title) {
            $user->increment($type, $number);

            ReferralUserEarnHistory::create([
                'title' => $title,
                'user_id' => $user->id,
                'referred_user_id' => $referredUser->id,
                'earn_type' => $type,
                'earn_number' => $number,
                'status' => ReferralUserEarnHistory::STATUS_COMPLETED,
            ]);
        }
    );
}
