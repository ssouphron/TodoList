<?php

namespace Tests\Feature;

use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Str;
use Tests\TestCase;

class TodoListControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function testTodoListCreationNominal()
    {
        $user = factory(User::class)->create();
        $name = Str::random();
        $desc = Str::random();

        $response = $this->postJson('/api/v1/users/' . strval($user->id) . '/todo_lists', [
            'name' => $name,
            'description' => $desc
        ]);

        $response->assertCreated();

        $this->assertDatabaseHas('todo_lists', [
            'user_id' => $user->id,
            'name' => $name,
            'description' => $desc
        ]);
    }

    public function testAddItemNominal()
    {
        $user = factory(User::class)->create();

        $response = $this->postJson('/api/v1/users/' . strval($user->id) . '/todo_lists', [
            'name' => 'name',
            'description' => 'desc'
        ]);

        $response->assertCreated();

        $name = Str::random();
        $content = Str::random();
        $response = $this->postJson('/api/v1/users/' . strval($user->id) . '/todo_lists/items', [
            'name' => $name,
            'item_content' => $content
        ]);

        $response->assertCreated();
        $response->assertJsonFragment(['content' => $content]);
        $this->assertDatabaseHas('items', [
            'name' => $name,
            'content' => $content
        ]);
    }
}
