<?php


namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class ItemRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'item_content' => 'required|string|max:1000'
        ];
    }
}