<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vacation extends Model
{
    protected $fillable = [
        'user_id',
        'periods'
    ];

    protected $casts = [
        'periods' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
