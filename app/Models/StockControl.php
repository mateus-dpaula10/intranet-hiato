<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockControl extends Model
{
    protected $fillable = [
        'product_name',
        'type',
        'quantidade'
    ];

    public function getFormattedQuantityAttribute()
    {
        return $this->quantidade . ' ' . ($this->type === 'units' ? 'unidades' : 'pacotes');
    }
}
