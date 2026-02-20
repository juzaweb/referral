<?php

namespace Juzaweb\Modules\Referral\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\File;
use Juzaweb\Modules\Admin\Events\WebsiteCreated;
use Juzaweb\Modules\Core\Facades\Menu;
use Juzaweb\Modules\Core\Providers\ServiceProvider;
use Juzaweb\Modules\Referral\Http\Middleware\CaptureReferralCode;
use Juzaweb\Modules\Referral\Listeners\LogReferralActivity;
use Juzaweb\Modules\Referral\Listeners\LogWebsiteReferralActivity;

class ReferralServiceProvider extends ServiceProvider
{
    public function boot(Router $router): void
    {
        $router->pushMiddlewareToGroup('theme', CaptureReferralCode::class);

        Event::listen(
            Registered::class,
            LogReferralActivity::class
        );

        Event::listen(
            WebsiteCreated::class,
            LogWebsiteReferralActivity::class
        );

        $this->booted(
            function () {
                $this->registerMenus();
            }
        );
    }

    public function register(): void
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(__DIR__ . '/../../Database/migrations');
        $this->app->register(RouteServiceProvider::class);
    }

    protected function registerMenus(): void
    {
        if (File::missing(storage_path('app/installed'))) {
            return;
        }

        // Menu::make('referral-management', function () {
        //     return [
        //         'title' => __('referral::translation.refferral'),
        //         'priority' => 50,
        //         'icon' => 'fas fa-user-friends',
        //     ];
        // });

        Menu::make('referrals', function () {
            return [
                'title' => __('referral::translation.refferrals'),
                'priority' => 50,
                'icon' => 'fas fa-user-friends',
            ];
        });
    }

    protected function registerConfig(): void
    {
        $this->publishes([
            __DIR__ . '/../../config/referral.php' => config_path('referral.php'),
        ], 'referral-config');
        $this->mergeConfigFrom(__DIR__ . '/../../config/referral.php', 'referral');
    }

    protected function registerTranslations(): void
    {
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'referral');
        $this->loadJsonTranslationsFrom(__DIR__ . '/../resources/lang');
    }

    protected function registerViews(): void
    {
        $viewPath = resource_path('views/modules/referral');

        $sourcePath = __DIR__ . '/../resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ], ['views', 'referral-module-views']);

        $this->loadViewsFrom($sourcePath, 'referral');
    }
}
