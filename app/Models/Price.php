<?php

namespace App\Models;

use App\Enums\CurrencyPair;
use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    protected $guarded = [
        'id'
    ];
    public $timestamps = false;

    protected $casts = [
        'date' => 'datetime',
        'ask' => 'decimal:2',
        'bid' => 'decimal:2',
        'pair' => CurrencyPair::class,
    ];
}
