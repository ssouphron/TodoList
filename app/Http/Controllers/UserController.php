<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Services\UserService;
use App\User;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    protected function show(User $user): JsonResponse
    {
        return response()->json(['user' => $this->userService->getUserWithTodoList($user)]);
    }

    protected function store(UserRequest $request): JsonResponse
    {
        $user = new User();
        $user->fill($request->all());
        $user->save();

        return response()
            ->json(['user' => $this->userService->getUserWithTodoList($user)])
            ->setStatusCode(201);
    }
}
