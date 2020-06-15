<?php

namespace Tests\Unit;

use App\Item;
use App\TodoList;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Tests\TestCase;

class TodoListTest extends TestCase
{
    private $item;
    private $user;
    private $todoList;

    protected function setUp(): void
    {
        parent::setUp();

        $this->item = new Item([
            'name' => 'Nom de ma todo',
            'content' => 'Description de ma todo list',
            'created_at' => Carbon::now()->subHour()
        ]);

        $this->user = new User([
            'first_name' => 'seb',
            'last_name' => 'sso',
            'email' => 'test@test.fr',
            'password' => 'good_password',
            'birthday' => Carbon::now()->subDecades(3)->subMonths(7)->subDays(24)->toDateString()
        ]);

        $this->todoList = $this->getMockBuilder(TodoList::class)
            ->onlyMethods(['actualItemsCount', 'getLastItem'])
            ->getMock();
        $this->todoList->user = $this->user;
    }

    public function testCanAddItemNominal()
    {
        $this->todoList->expects($this->once())->method('actualItemsCount')->willReturn(1);
        $this->todoList->expects($this->any())->method('getLastItem')->willReturn($this->item);

        $canAddItem = $this->todoList->canAddItem($this->item);
        $this->assertNotNull($canAddItem);
        $this->assertEquals('Nom de ma todo', $canAddItem->name);
    }

    public function testCannotAddItemMaxNumberReached()
    {
        $this->todoList->expects($this->any())->method('actualItemsCount')->willReturn(10);

        $this->expectException('Exception');
        $this->expectExceptionMessage('Todo list has too many items');

        $this->todoList->canAddItem($this->item);
    }

    public function testCannotAddItemLastTooRecent()
    {
        $this->todoList->expects($this->any())->method('actualItemsCount')->willReturn(0);

        $recentItem = $this->item->replicate();
        $recentItem->created_at = Carbon::now()->subMinutes(10);
        $this->todoList->expects($this->any())->method('getLastItem')->willReturn($recentItem);

        $this->expectException('Exception');
        $this->expectExceptionMessage('Last item is too recent');

        $this->todoList->canAddItem($this->item);
    }

    public function testCannotAddItemUserNotValid()
    {
        $this->todoList->user->email = 'test';

        $this->todoList->expects($this->any())->method('actualItemsCount')->willReturn(0);
        $this->todoList->expects($this->any())->method('getLastItem')->willReturn($this->item);

        $this->expectException('Exception');
        $this->expectExceptionMessage('User is null or invalid');

        $this->todoList->canAddItem($this->item);
    }

    public function testCannotAddItemIsNull()
    {
        $this->expectException('Exception');
        $this->expectExceptionMessage('Item is null or invalid');

        $badItem = $this->item->replicate();
        $badItem->name = '';

        $this->todoList->canAddItem($badItem);
    }

    public function testIsValidNominal()
    {
        $todoList = TodoList::make([
            'name' => 'good_name',
            'description' => 'good_description'
        ]);

        $this->assertTrue($todoList->isValid());
    }

    public function testIsValidWithoutDescription()
    {
        $todoList = TodoList::make([
            'name' => 'good_name',
        ]);

        $this->assertTrue($todoList->isValid());
    }

    public function testIsNotValidNoName()
    {
        $todoList = TodoList::make([
            'description' => 'good_description'
        ]);

        $this->assertFalse($todoList->isValid());
    }

    public function testIsNotValidDescriptionTooLong()
    {
        $todoList = TodoList::make([
            'name' => 'good_name',
            'description' => Str::random(300)
        ]);

        $this->assertFalse($todoList->isValid());
    }
}
