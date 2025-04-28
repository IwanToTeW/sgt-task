<?php

namespace App\Actions\BitFinex;

class GetMidPrice
{
    /**
     * Calculate the mid price from bid and ask prices.
     * This is abstracted as a separate class because from here the formatting could be changed
     * which will affect the whole app.
     *
     * @param float $bid The bid price
     * @param float $ask The ask price
     * @return float The mid price
     */
    public function execute(float $bid, float $ask): float
    {
        return ($ask + $bid) / 2;
    }
}
