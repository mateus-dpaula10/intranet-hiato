<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $table = 'feedbacks';

    protected $fillable = [
        'user_id',
        'completion_dates',
        'types',
        'descriptions',
        'visibles'
    ];

    protected $casts = [
        'completion_dates' => 'array',
        'types'            => 'array',
        'descriptions'     => 'array',
        'visibles'         => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
