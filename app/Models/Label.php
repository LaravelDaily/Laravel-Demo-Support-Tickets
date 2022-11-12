<?php

namespace App\Models;

use Illuminate\Support\Str;
use Coderflex\LaravelTicket\Models\Label as TicketLabel;

class Label extends TicketLabel
{
    protected $casts = [
        'is_visible' => 'boolean',
    ];

    public static function boot()
    {
        parent::boot();

        static::saving(function (Label $category) {
            $category->slug = Str::slug($category->name);
        });
    }
}