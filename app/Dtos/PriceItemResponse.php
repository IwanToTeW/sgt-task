<?php

namespace App\Dtos;

use App\Actions\BitFinex\GetMidPrice;
use Carbon\Carbon;
use Carbon\CarbonInterface;

readonly class PriceItemResponse
{
    public string $pair;
    public float $bid;
    public float $ask;
    public string $date;
    public function __construct(array $data)
    {
        $this->pair = $data[0];
        $this->date = $data[12];
        $this->bid = $data[1];
        $this->ask = $data[3];
    }
}
