<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiscQuestion extends Model
{
    protected $fillable = ['blocks'];

    protected $casts = [
        'blocks' => 'array'
    ];
}
