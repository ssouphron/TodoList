<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class UserApiTest extends TestCase
{
    use DatabaseMigrations;

    public function testBasicTest()
    {
        $this->withoutExceptionHandling();

        $response = $this->postJson('/api/v1/users', [
            'email' => 'test@test.fr',
            'password' => 'password',
            'first_name' => 'fname',
            'last_name' => 'lname',
            'birthday' => Carbon::now()->subDecades(3)->format('Y-m-d')
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('users', [
            'email' => 'test@test.fr',
            'first_name' => 'fname',
            'last_name' => 'lname'
        ]);
    }
}
