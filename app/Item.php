<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'name', 'content', 'created_at'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function todoList()
    {
        return $this->belongsTo(TodoList::class);
    }

    public function isValid(): bool
    {
        return !empty($this->name)
            && !empty($this->content)
            && strlen($this->content) <= 1000;
    }
}
