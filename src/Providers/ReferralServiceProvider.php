<?php

namespace Juzaweb\Referral\Providers;

use Juzaweb\CMS\Support\ServiceProvider;
use Juzaweb\Referral\Actions\FrontendAction;

class ReferralServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerHookActions([FrontendAction::class]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        //
    }
}
