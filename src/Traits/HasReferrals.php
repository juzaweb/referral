<?php

namespace Juzaweb\Modules\Referral\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Str;
use Juzaweb\Modules\Referral\Models\Referral;

trait HasReferrals
{
    /**
     * Maximum referral code length to prevent infinite loops.
     */
    protected const MAX_REFERRAL_CODE_LENGTH = 16;

    /**
     * Generate a unique referral code.
     */
    public static function generateReferralCode(): string
    {
        $maxAttempts = 10;
        $attempts = 0;
        $length = 8;

        do {
            $code = Str::random($length);
            $attempts++;

            // If we can't find a unique code after max attempts, try with longer length
            if ($attempts >= $maxAttempts) {
                $length = 12;
                $attempts = 0; // Reset attempts for new length
            }

            // Safety check: don't loop forever
            if ($length >= self::MAX_REFERRAL_CODE_LENGTH) {
                break;
            }
        } while (static::where('referral_code', $code)->exists());

        return $code;
    }

    /**
     * All referrals sent by this model (plural).
     */
    public function referralsSent(): MorphMany
    {
        return $this->morphMany(Referral::class, 'referrer');
    }

    /**
     * All referrals received by this model.
     */
    public function referralsReceived(): MorphMany
    {
        return $this->morphMany(Referral::class, 'referred');
    }

    /**
     * Create a referral.
     *
     * @param  Model  $referred
     * @return Referral
     */
    public function refer(Model $referred): Referral
    {
        return Referral::create(
            [
                'referrer_id'   => $this->getKey(),
                'referrer_type' => $this->getMorphClass(),
                'referred_id'   => $referred->getKey(),
                'referred_type' => $referred->getMorphClass(),
            ]
        );
    }
}
