<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vacation extends Model
{
    protected $fillable = [
        'start_date',
        'end_date',
        'user_id',
        'is_read'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
