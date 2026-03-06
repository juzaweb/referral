<?php
namespace Juzaweb\Modules\Referral\Tests\Feature;
use Juzaweb\Modules\Referral\Tests\TestCase;
use Juzaweb\Modules\Core\Models\User;
use Juzaweb\Modules\Referral\Models\Referral;

class ReferralControllerTest extends TestCase
{
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    protected function getPackageAliases($app): array
    {
        return array_merge(parent::getPackageAliases($app), [
            'Menu' => \Juzaweb\Modules\Core\Facades\Menu::class,
        ]);
    }

    public function test_index()
    {
        $this->withoutMiddleware();
        $response = $this->actingAs($this->user)->get('admin/referrals');
        $response->assertStatus(200);
    }

    public function test_bulk()
    {
        $this->withoutMiddleware();
        $referral1 = Referral::create([
            'referrer_id' => $this->user->id,
            'referrer_type' => User::class,
            'referred_id' => 2,
            'referred_type' => User::class,
        ]);

        $referral2 = Referral::create([
            'referrer_id' => $this->user->id,
            'referrer_type' => User::class,
            'referred_id' => 3,
            'referred_type' => User::class,
        ]);

        $response = $this->actingAs($this->user)->postJson('admin/referrals/bulk', [
            'action' => 'delete',
            'ids' => [$referral1->id, $referral2->id],
        ]);

        $response->assertStatus(200);
        $this->assertEquals(0, Referral::whereIn('id', [$referral1->id, $referral2->id])->count());
    }

    public function test_toggle_system()
    {
        $this->withoutMiddleware();
        $response = $this->actingAs($this->user)->postJson('admin/referrals/toggle-system', [
            'enabled' => 1,
        ]);
        $response->assertStatus(200);
    }
}
