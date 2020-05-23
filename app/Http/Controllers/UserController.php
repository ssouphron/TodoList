<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\User;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{

    protected function show(User $user): JsonResponse
    {
        $userWithTodoList = User::whereId($user->id)->with('todoList')->first();
        return response()->json(['user' => $userWithTodoList]);
    }

    protected function store(UserRequest $request): JsonResponse
    {
        $user = new User();
        $user->fill($request->all());
        $user->save();

        return response()
            ->json(['user' => User::whereId($user->id)->with('todoList')->first()])
            ->setStatusCode(201);
    }
}
