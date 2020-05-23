<?php


namespace App\Http\Services;


use App\User;

class UserService
{
    public function getUserWithTodoList(User $user): User
    {
        return User::whereId($user->id)->with('todoList.items')->first();
    }
}