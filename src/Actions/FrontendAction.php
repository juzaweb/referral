<?php

namespace Juzaweb\Referral\Actions;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Juzaweb\CMS\Abstracts\Action;
use Juzaweb\CMS\Models\User;

class FrontendAction extends Action
{
    public function handle(): void
    {
        if (!get_config('referral_enable')) {
            return;
        }

        $this->addAction(self::FRONTEND_INIT, [$this, 'frontendInit']);
        $this->addAction('theme.profile.index', [$this, 'generateReferralCode']);
        $this->addFilter('user.resouce_data', [$this, 'addReferralCodeToTheme'], 20, 2);
    }

    public function frontendInit(): void
    {
        $this->registerProfilePage(
            'referral',
            [
                'title' => __('Referral'),
                //'contents' => 'referral::frontend.profile.referral',
                'icon' => 'globe',
            ]
        );

        // Set cookie referral code
        if (($ref = request()?->get('ref')) && !auth()->check()) {
            Cookie::queue('ref_code', $ref, time() + 86400, '/');
        }
    }

    public function generateReferralCode(): void
    {
        /** @var User $user */
        $user = auth()->user();
        if (empty($user->referral_code)) {
            DB::transaction(
                function () use ($user) {
                    $user->setAttribute('referral_code', generate_referral_code());
                    $user->save();
                }
            );
        }
    }

    public function addReferralCodeToTheme(array $data, User $user): array
    {
        $data['referral_code'] = $user->referral_code;

        return $data;
    }
}
