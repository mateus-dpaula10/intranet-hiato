<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiscAnswer extends Model
{
    protected $fillable = ['user_id', 'scores', 'totals', 'profile'];

    protected $casts = [
        'scores' => 'array',
        'totals' => 'array'
    ];
}
