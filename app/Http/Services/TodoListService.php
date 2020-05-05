<?php


namespace App\Http\Services;


use App\Item;
use App\TodoList;
use App\User;

class TodoListService
{

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

    public function addItem(User $user, string $name, string $content)
    {
        if (is_null($user) || !$user->isValid()) {
            return false;
        } elseif (is_null($user->todoList)) {
            return false;
        }

        $item = Item::make([
            'name' => $name,
            'content' => $content
        ]);

        if ($user->todoList->canAddItem($item)) {
            $user->todoList->items()->save($item);
            return true;
        }

        return false;
    }

}