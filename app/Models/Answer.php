<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $fillable = ['diagnostic_id', 'user_id', 'option_id', 'points'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function diagnostic() {
        return $this->belongsTo(Diagnostic::class);
    }

    public function option() {
        return $this->belongsTo(Option::class);
    }
}
