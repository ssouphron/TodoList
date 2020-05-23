<?php


namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class TodoListRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|max:255'
        ];
    }
}