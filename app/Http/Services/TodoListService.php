<?php


namespace App\Http\Services;


use App\Item;
use App\TodoList;
use App\User;

class TodoListService
{

    private $emailService;

    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    public function createTodoList(User $user, string $name, ?string $description): bool
    {
        if (is_null($user) || !$user->isValid()) {
            return false;
        } elseif (!is_null($user->todoList)) {
            return false;
        }

        $todoList = TodoList::make([
            'name' => $name,
            'description' => $description
        ]);

        $user->todoList()->save($todoList);
        return true;
    }

    public function addItem(User $user, string $name, string $content): bool
    {
        if (is_null($user) || !$user->isValid()) {
            return false;
        } elseif (is_null($user->todoList)) {
            return false;
        }

        foreach ($user->todoList->items as $item) {
            if ($item->name == $name) {
                return false;
            }
        }

        $item = Item::make([
            'name' => $name,
            'content' => $content
        ]);

        if ($user->todoList->canAddItem($item)) {
            $user->todoList->items()->save($item);
             $user->todoList->load('items');

            if ($this->emailService->shouldSend($user)) {
                $this->emailService->send($user, $item);
            }

            return true;
        }

        return false;
    }

    public function getTodoListWithItems(User $user): TodoList
    {
        return User::whereId($user->id)->with('todoList.items')->first()->todoList;
    }

}