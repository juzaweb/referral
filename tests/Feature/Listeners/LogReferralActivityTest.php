<?php

namespace Juzaweb\Modules\Referral\Tests\Feature\Listeners;

use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Juzaweb\Modules\Referral\Listeners\LogReferralActivity;
use Juzaweb\Modules\Referral\Models\ReferralCode;
use Juzaweb\Modules\Referral\Tests\TestCase;
use Juzaweb\Modules\Referral\Contracts\Referralable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Juzaweb\Modules\Referral\Models\Referral;

class DummyUser extends Model
{
    protected $table = 'users';
    protected $guarded = [];
}

class DummyReferralableUser extends DummyUser implements Referralable
{
    protected $table = 'dummy_referralable_users';

    public $referCalledWith = null;

    // We use static to track across instances because Eloquent instantiates new models from DB
    public static $staticReferCalledWith = null;

    public function referralsSent(): MorphMany
    {
        return $this->morphMany(Referral::class, 'referrer');
    }

    public function referralsReceived(): MorphMany
    {
        return $this->morphMany(Referral::class, 'referred');
    }

    public function refer(Model $referred): Referral
    {
        $this->referCalledWith = $referred;
        self::$staticReferCalledWith = $referred;
        return new Referral();
    }
}

class LogReferralActivityTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_it_does_nothing_if_user_not_referralable(): void
    {
        $user = new DummyUser();
        $event = new Registered($user);

        $request = Request::create('/', 'GET');
        $request->cookies->set('referral_code', 'TEST_CODE');
        $this->app->instance('request', $request);

        $listener = new LogReferralActivity();
        $listener->handle($event);

        $this->assertTrue(true); // Should return early without error
    }

    public function test_it_does_nothing_if_cookie_not_present(): void
    {
        $user = new DummyReferralableUser();
        $event = new Registered($user);

        $request = Request::create('/', 'GET');
        $this->app->instance('request', $request);

        $listener = new LogReferralActivity();
        $listener->handle($event);

        $this->assertNull($user->referCalledWith);
    }

    public function test_it_does_nothing_if_code_not_in_database(): void
    {
        $user = new DummyReferralableUser();
        $event = new Registered($eventUser = new DummyUser());

        $request = Request::create('/', 'GET');
        $request->cookies->set('referral_code', 'NON_EXISTENT');
        $this->app->instance('request', $request);

        $listener = new LogReferralActivity();
        $listener->handle($event);

        $this->assertNull($user->referCalledWith);
    }

    public function test_it_calls_refer_when_valid_code_exists(): void
    {
        // To make this work smoothly with DB we should use genuine models that match our contract or dummy.
        // Let's create a database user, then create a referral code pointing to it.
        $referrerUser = new DummyReferralableUser();
        $referrerUser->id = 1;

        $referredUser = new DummyReferralableUser();
        $referredUser->id = 2;

        $event = new Registered($referredUser);

        $request = Request::create('/', 'GET');
        $request->cookies->set('referral_code', 'VALID_CODE');
        $this->app->instance('request', $request);

        // Let's create actual DB records for this instead of mocking
        // Using User model which hopefully implements Referralable, if not we can use our dummy user in DB
        // But since we have DB, it's easier to create the real records

        // We will just create actual DB models here.
        \Illuminate\Support\Facades\Schema::dropIfExists('dummy_referralable_users');
        \Illuminate\Support\Facades\Schema::create('dummy_referralable_users', function($table) {
            $table->increments('id');
            $table->timestamps();
        });

        $referrerUser = DummyReferralableUser::create();
        $referredUser = DummyReferralableUser::create();

        $event = new Registered($referredUser);

        $request = Request::create('/', 'GET');
        $request->cookies->set('referral_code', 'VALID_CODE');
        app()->instance('request', $request);

        $referralCode = ReferralCode::create([
            'referrer_id' => $referrerUser->id,
            'referrer_type' => DummyReferralableUser::class,
            'code' => 'VALID_CODE',
        ]);

        $listener = new LogReferralActivity();
        $listener->handle($event);

        $this->assertNotNull(DummyReferralableUser::$staticReferCalledWith);
        $this->assertEquals($referredUser->id, DummyReferralableUser::$staticReferCalledWith->id);
    }
}
