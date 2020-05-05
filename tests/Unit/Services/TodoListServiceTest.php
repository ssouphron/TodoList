<?php


namespace Tests\Unit\Services;


use App\Http\Services\TodoListService;
use App\TodoList;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Str;
use Tests\TestCase;

class TodoListServiceTest extends TestCase
{
    use DatabaseMigrations;

    private $sut;
    private $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sut = new TodoListService();

        $this->user = factory(User::class)->create();
    }

    public function testCreateTodoListNominal()
    {
        $this->assertTrue($this->sut->createTodoList($this->user, 'name', 'my description'));
    }

    public function testCreateTodoListWhichAlreadyExists()
    {
        $this->user->todoList()->save(TodoList::make(['name' => 'name', 'description' => 'desc']));
        $this->assertFalse($this->sut->createTodoList($this->user, 'name', 'my description'));
    }

    public function testAddItemNominal()
    {
        $this->user->todoList()->save(TodoList::make(['name' => 'name', 'description' => 'desc']));
        $this->assertTrue($this->sut->addItem($this->user, 'name', 'content'));
    }

    public function testAddItemInvalidItem()
    {
        $this->expectException('Exception');
        $this->expectExceptionMessage('Item is null or invalid');

        $this->user->todoList()->save(TodoList::make(['name' => 'name', 'description' => 'desc']));
        $this->assertFalse($this->sut->addItem($this->user, 'name', Str::random(1050)));
    }

    public function testAddItemInvalidUser()
    {
        $this->user->todoList()->save(TodoList::make(['name' => 'name', 'description' => 'desc']));
        $this->user->email = 'test';
        $this->assertFalse($this->sut->addItem($this->user, 'name', 'content'));
    }
}