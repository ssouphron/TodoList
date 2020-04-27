<?php

namespace Tests\Unit;

use App\User;
use Carbon\Carbon;
use Illuminate\Support\Str;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    private $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = new User([
            'first_name' => 'seb',
            'last_name' => 'sso',
            'email' => 'test@test.fr',
            'password' => 'good_password',
            'birthday' => Carbon::now()->subDecades(3)->subMonths(7)->subDays(24)->toDateString()
        ]);
    }

    public function testIsValidNominal()
    {
        $this->assertTrue($this->user->isValid());
    }

    public function testIsNotValidEmailBadFormat()
    {
        $this->user->email = 'test';
        $this->assertFalse($this->user->isValid());
    }

    public function testIsNotValidFirstNameEmpty()
    {
        $this->user->first_name = '';
        $this->assertFalse($this->user->isValid());
    }

    public function testIsNotValidLastNameNull()
    {
        $this->user->last_name = null;
        $this->assertFalse($this->user->isValid());
    }

    public function testIsNotValidMinor()
    {
        $this->user->birthday = Carbon::now()->subDecade()->toDateString();
        $this->assertFalse($this->user->isValid());
    }

    public function testIsNotValidPasswordToShort()
    {
        $this->user->password = '1234567';
        $this->assertFalse($this->user->isValid());
    }

    public function testIsNotValidPasswordToLong()
    {
        $this->user->password = Str::random(45);
        $this->assertFalse($this->user->isValid());
    }
}
