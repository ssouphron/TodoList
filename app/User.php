<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $fillable = [
        'first_name', 'last_name', 'email', 'password', 'birthday'
    ];

    protected $hidden = [
        'password'
    ];

    public function todoList()
    {
        return $this->hasOne(TodoList::class);
    }

    public function isValid()
    {
        return !empty($this->first_name)
            && !empty($this->last_name)
            && !empty($this->email)
            && filter_var($this->email, FILTER_VALIDATE_EMAIL)
            && !empty($this->birthday)
            && !empty($this->password)
            && strlen($this->password) >= 8
            && strlen($this->password) <= 40
            && Carbon::now()->subYears(13)->isAfter($this->birthday);
    }
}
