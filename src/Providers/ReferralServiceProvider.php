<?php

namespace Juzaweb\Referral\Providers;

use Illuminate\Support\Facades\Event;
use Juzaweb\Backend\Events\Users\RegisterSuccessful;
use Juzaweb\CMS\Support\ServiceProvider;
use Juzaweb\Referral\Actions\ConfigAction;
use Juzaweb\Referral\Actions\FrontendAction;
use Juzaweb\Referral\Listeners\AddCreditWhenRegisterSuccess;

class ReferralServiceProvider extends ServiceProvider
{
    protected array $listen = [
        RegisterSuccessful::class => [
            AddCreditWhenRegisterSuccess::class,
        ]
    ];

    public function boot(): void
    {
        $this->registerHookActions([FrontendAction::class, ConfigAction::class]);

        foreach ($this->listen as $event => $listeners) {
            foreach ($listeners as $listener) {
                Event::listen($event, $listener);
            }
        }
    }

    public function register(): void
    {
        //
    }
}
