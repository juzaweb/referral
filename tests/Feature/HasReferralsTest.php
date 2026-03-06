<?php
namespace Juzaweb\Modules\Referral\Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Juzaweb\Modules\Referral\Tests\TestCase;
use Juzaweb\Modules\Core\Models\User;
use Juzaweb\Modules\Referral\Models\Referral;
use Juzaweb\Modules\Referral\Models\ReferralCode;
use Juzaweb\Modules\Referral\Traits\HasReferrals;

class ReferralUser extends User
{
    use HasReferrals;

    protected $table = 'users';
}

class HasReferralsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_generate_referral_code()
    {
        $user = new ReferralUser();
        $user->name = 'test';
        $user->email = 'test@example.com';
        $user->password = 'test';
        $user->save();

        $code = $user->generateReferralCode();
        $this->assertIsString($code);
        $this->assertNotEmpty($code);
    }

    public function test_referrals_sent_and_received()
    {
        $referrer = new ReferralUser();
        $referrer->name = 'test';
        $referrer->email = 'test@example.com';
        $referrer->password = 'test';
        $referrer->save();

        $referred = new ReferralUser();
        $referred->name = 'test2';
        $referred->email = 'test2@example.com';
        $referred->password = 'test2';
        $referred->save();

        $referral = $referrer->refer($referred);

        $this->assertInstanceOf(Referral::class, $referral);
        $this->assertEquals($referrer->id, $referral->referrer_id);
        $this->assertEquals($referred->id, $referral->referred_id);

        $this->assertEquals(1, $referrer->referralsSent()->count());
        $this->assertEquals(1, $referred->referralsReceived()->count());
    }
}
