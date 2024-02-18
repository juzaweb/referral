<?php

namespace Juzaweb\Referral\Providers;

use Juzaweb\CMS\Support\ServiceProvider;
use Juzaweb\Referral\Actions\ConfigAction;
use Juzaweb\Referral\Actions\FrontendAction;

class ReferralServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerHookActions([FrontendAction::class, ConfigAction::class]);
    }

    public function register(): void
    {
        //
    }
}
