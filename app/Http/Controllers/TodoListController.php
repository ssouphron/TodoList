<?php

namespace App\Http\Controllers;

use App\Http\Requests\ItemRequest;
use App\Http\Requests\TodoListRequest;
use App\Http\Services\TodoListService;
use App\User;
use Exception;
use Illuminate\Http\JsonResponse;

class TodoListController extends Controller
{
    private $todoListService;

    public function __construct(TodoListService $todoListService)
    {
        $this->todoListService = $todoListService;
    }

    protected function show(User $user): JsonResponse
    {
        $userWithTodoList = User::whereId($user->id)->with('todoList.items')->first();
        return response()->json(['todo_list' => $userWithTodoList->todoList]);
    }

    /**
     * @param TodoListRequest $request
     * @param User $user
     * @return JsonResponse
     * @throws Exception
     */
    protected function store(TodoListRequest $request, User $user): JsonResponse
    {
        $name = $request->name;
        $desc = $request->description;

        if (!$this->todoListService->createTodoList($user, $name, $desc)) {
            throw new Exception("Error occurred while user todo list creation");
        }

        return response()->json(['todo_list' => User::whereId($user->id)->with('todoList.items')->first()->todoList]);
    }

    /**
     * @param TodoListRequest $request
     * @param User $user
     * @return JsonResponse
     * @throws Exception
     */
    protected function storeItem(ItemRequest $request, User $user): JsonResponse
    {
        $name = $request->name;
        $content = $request->item_content;

        if (!$this->todoListService->addItem($user, $name, $content)) {
            throw new Exception("Error occurred while adding item to todo list");
        }

        return response()->json(['todo_list' => User::whereId($user->id)->with('todoList.items')->first()->todoList]);
    }
}
