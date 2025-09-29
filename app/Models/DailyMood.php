<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyMood extends Model
{
    protected $fillable = [
        'user_id',
        'date',
        'mood'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function moodsList()
    {
        return [
            'triste'       => '😢 Triste',
            'angustiado'   => '😟 Angustiado(a)',
            'feliz'        => '😃 Feliz',
            'introvertido' => '🤫 Introvertido(a)',
            'ansioso'      => '😰 Ansioso(a)',
            'animado'      => '😄 Animado(a)',
            'neutro'       => '😐 Neutro(a)'
        ];
    }
}
