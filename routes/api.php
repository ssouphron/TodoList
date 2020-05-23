<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1')->group(function () {

    /* User APIs */
    Route::get('users/{user}', 'UserController@show');
    Route::post('users', 'UserController@store');

    /* TodoList APIs */
    Route::get('users/{user}/todo_lists', 'TodoListController@show');
    Route::post('users/{user}/todo_lists', 'TodoListController@store');
    Route::post('users/{user}/todo_lists/items', 'TodoListController@storeItem');

});
