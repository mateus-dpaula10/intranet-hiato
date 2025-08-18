<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    protected $fillable = ['diagnostic_id', 'text', 'points'];

    public function diagnostic() {
        return $this->belongsTo(Diagnostic::class);
    }
}
