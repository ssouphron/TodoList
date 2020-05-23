<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function testUserCreationNominal()
    {
        $response = $this->postJson('/api/v1/users', [
            'email' => 'test@test.fr',
            'password' => 'password',
            'first_name' => 'fname',
            'last_name' => 'lname',
            'birthday' => Carbon::now()->subDecades(3)->format('Y-m-d')
        ]);

        $response->assertCreated();

        $this->assertDatabaseHas('users', [
            'email' => 'test@test.fr',
            'first_name' => 'fname',
            'last_name' => 'lname'
        ]);
    }

    public function testUserCreationDuplicateEmail()
    {
        $response = $this->postJson('/api/v1/users', [
            'email' => 'test@test.fr',
            'password' => 'password',
            'first_name' => 'fname',
            'last_name' => 'lname',
            'birthday' => Carbon::now()->subDecades(3)->format('Y-m-d')
        ]);

        $response->assertCreated();

        $response2 = $this->postJson('/api/v1/users', [
            'email' => 'test@test.fr',
            'password' => 'password',
            'first_name' => 'fnameDuplicate',
            'last_name' => 'lnameDuplicate',
            'birthday' => Carbon::now()->subDecades(3)->format('Y-m-d')
        ]);

        $response2->assertStatus(422);
        $response2->assertJsonValidationErrors('email');
        $this->assertDatabaseMissing('users', [
            'email' => 'testDuplicate@test.fr',
            'first_name' => 'fnameDuplicate',
            'last_name' => 'lnameDuplicate'
        ]);
    }
}
