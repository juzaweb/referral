<?php
namespace Juzaweb\Modules\Referral\Tests\Feature;
use Juzaweb\Modules\Referral\Tests\TestCase;
use Juzaweb\Modules\Core\Models\User;
class DummyUserTest extends TestCase
{
    public function test_user()
    {
        $user = User::factory()->create();
        $this->assertNotNull($user);
    }
}
