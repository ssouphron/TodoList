<?php


namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function rules()
    {
        return [
            'email' => 'required|email|unique:users|max:128',
            'password' => 'required|min:8|max:40',
            'first_name' => 'required|max:128',
            'last_name' => 'required|max:128',
            'birthday' => 'required|date_format:Y-m-d'
        ];
    }
}