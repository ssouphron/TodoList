<?php


namespace Tests\Unit\Services;


use App\Http\Services\EmailService;
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

    /** @var $emailServiceMocked EmailService */
    private $emailServiceMocked;

    protected function setUp(): void
    {
        parent::setUp();

        $this->emailServiceMocked = $this->getMockBuilder(EmailService::class)
            ->onlyMethods(['send', 'shouldSend'])
            ->getMock();

        $this->sut = new TodoListService($this->emailServiceMocked);

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
        $this->emailServiceMocked->expects($this->once())->method('shouldSend')->willReturn(true);
        $this->emailServiceMocked->expects($this->once())->method('send');

        $this->user->todoList()->save(TodoList::make(['name' => 'name', 'description' => 'desc']));
        $this->assertTrue($this->sut->addItem($this->user, 'itemName', 'content'));
    }

    public function testAddItemInvalidItem()
    {
        $this->expectException('Exception');
        $this->expectExceptionMessage('Item is null or invalid');

        $this->emailServiceMocked->expects($this->never())->method('send');

        $this->user->todoList()->save(TodoList::make(['name' => 'name', 'description' => 'desc']));
        $this->assertFalse($this->sut->addItem($this->user, 'itemName', Str::random(1050)));
    }

    public function testAddItemInvalidUser()
    {
        $this->emailServiceMocked->expects($this->never())->method('send');

        $this->user->todoList()->save(TodoList::make(['name' => 'name', 'description' => 'desc']));
        $this->user->email = 'test';
        $this->assertFalse($this->sut->addItem($this->user, 'itemName', 'content'));
    }

    public function testAddNonUniqueItem()
    {
        $this->emailServiceMocked->expects($this->never())->method('send');

        $this->user->todoList()->save(TodoList::make(['name' => 'name', 'description' => 'desc']));
        $this->assertTrue($this->sut->addItem($this->user, 'itemName', 'contentFirstItem'));
        $this->assertDatabaseHas('items', [
            'name' => 'itemName',
            'content' => 'contentFirstItem'
        ]);
        $this->assertFalse($this->sut->addItem($this->user, 'itemName', 'contentSecondItem'));
        $this->assertDatabaseMissing('items', [
            'content' => 'contentSecondItem'
        ]);
    }
}