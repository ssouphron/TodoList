<?php

namespace Tests\Unit;

use App\Item;
use App\TodoList;
use App\User;
use Carbon\Carbon;
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
            'content' => 'Description de ma todo list'
        ]);

        $this->user = new User([
            'first_name' => 'seb',
            'last_name' => 'sso',
            'email' => 'test@test.fr',
            'password' => 'good_password',
            'birthday' => Carbon::now()->subDecades(3)->subMonths(7)->subDays(24)->toDateString()
        ]);

        $this->todoList = $this->getMockBuilder(TodoList::class)
            ->onlyMethods(['user', 'actualItemsCount', 'getLastItem'])
            ->getMock();
    }

    public function testCanAddItemNominal()
    {
        $this->todoList->expects($this->any())->method('user')->willReturn($this->user);
        $this->todoList->expects($this->any())->method('actualItemsCount')->willReturn(0);
        $this->todoList->expects($this->any())->method('getLastItem')->willReturn($this->item);

        $this->assertNotNull($this->todoList->canAddItem($this->item));
    }

    public function testCannotAddItemMaxNumberReached()
    {

        $this->todoList->expects($this->any())->method('user')->willReturn($this->user);
        $this->todoList->expects($this->any())->method('actualItemsCount')->willReturn(10);
        $this->todoList->expects($this->any())->method('getLastItem')->willReturn($this->item);

        $this->expectException('Exception');
        $this->expectExceptionMessage('Todo list has too many items');

        $this->todoList->canAddItem($this->item);
    }

    public function testCannotAddItemLastTooRecent()
    {
        $this->todoList->expects($this->any())->method('user')->willReturn($this->user);
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
        $this->user->email = 'test';

        $this->todoList->expects($this->any())->method('user')->willReturn($this->user);
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
}
