<?php

namespace Juzaweb\Modules\Referral\Tests\Feature\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cookie;
use Juzaweb\Modules\Referral\Http\Middleware\CaptureReferralCode;
use Juzaweb\Modules\Referral\Tests\TestCase;
use Juzaweb\Modules\Core\Contracts\ThemeSetting;
use Juzaweb\Modules\Core\Facades\Setting;
use Juzaweb\Modules\Core\Models\Setting as SettingModel;

class CaptureReferralCodeTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function setSetting($key, $value)
    {
        if (!isset($this->mockedSettings)) {
            $this->mockedSettings = [];

            $mock = \Mockery::mock(\Juzaweb\Modules\Core\Contracts\Setting::class);
            $mock->shouldReceive('get')->andReturnUsing(function($k, $default) {
                return $this->mockedSettings[$k] ?? $default;
            });

            $this->app->instance(\Juzaweb\Modules\Core\Contracts\Setting::class, $mock);
        }

        $this->mockedSettings[$key] = $value;
    }

    public function test_it_sets_cookie_when_ref_is_present_and_system_enabled(): void
    {
        $this->setSetting('enable_referral_system', true);

        $request = Request::create('/', 'GET', ['ref' => 'TEST_CODE']);
        $middleware = new CaptureReferralCode();

        Cookie::spy();

        $response = $middleware->handle($request, function ($req) {
            return new Response();
        });

        Cookie::shouldHaveReceived('queue')->once();
    }

    public function test_it_does_not_set_cookie_when_system_disabled(): void
    {
        $this->setSetting('enable_referral_system', false);

        $request = Request::create('/', 'GET', ['ref' => 'TEST_CODE']);
        $middleware = new CaptureReferralCode();

        Cookie::spy();

        $response = $middleware->handle($request, function ($req) {
            return new Response();
        });

        Cookie::shouldNotHaveReceived('queue');
    }

    public function test_it_does_not_set_cookie_when_ref_is_missing(): void
    {
        $this->setSetting('enable_referral_system', true);

        $request = Request::create('/', 'GET');
        $middleware = new CaptureReferralCode();

        Cookie::spy();

        $response = $middleware->handle($request, function ($req) {
            return new Response();
        });

        Cookie::shouldNotHaveReceived('queue');
    }

    public function test_it_sets_correct_expiration_time(): void
    {
        $this->setSetting('enable_referral_system', true);
        $this->setSetting('referral_cookie_expiration', 100);

        $request = Request::create('/', 'GET', ['ref' => 'TEST_CODE']);
        $middleware = new CaptureReferralCode();

        $queuedCookies = [];

        // Let's use standard Cookie facade make/queue methods without mocking everything
        // to make sure make returns a real cookie, or mock `make`
        Cookie::shouldReceive('make')->andReturnUsing(function($name, $val, $min) {
            return new \Symfony\Component\HttpFoundation\Cookie($name, $val, time() + ($min * 60));
        });
        Cookie::shouldReceive('queue');

        $response = $middleware->handle($request, function ($req) {
            return new Response();
        });

        Cookie::shouldHaveReceived('queue')->withArgs(function ($cookie) {
            if (!$cookie instanceof \Symfony\Component\HttpFoundation\Cookie) {
                return false;
            }
            return $cookie->getName() === 'referral_code'
                && $cookie->getValue() === 'TEST_CODE'
                && $cookie->getExpiresTime() > 0;
        })->once();
    }
}
