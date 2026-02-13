<?php

use Juzaweb\Modules\Referral\Http\Controllers\ReferralController;

Route::admin('referrals', ReferralController::class)->only(['index', 'bulk']);

Route::post('referrals/toggle-system', [ReferralController::class, 'toggleSystem'])
    ->name('referrals.toggle-system');
