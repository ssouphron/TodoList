<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'name', 'content'
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

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_at = Carbon::now();
        });
    }
}
