<?php

namespace App\Dtos;

use Carbon\Carbon;
use Carbon\CarbonInterface;

class QueryDateInterval
{
    /**
     * The start of the interval.
     */
    public readonly CarbonInterface $startDate;

    /**
     * The end of the interval.
     */
    public readonly CarbonInterface $endDate;

    /**
     * Create a new QueryDateInterval instance.
     *
     * @param  string  $date
     */
    public function __construct(string $date = null)
    {
        $date = empty($date) ? now() : Carbon::parse($date);

        if ($date->isToday()) {
            $this->startDate = now()->subDay();
            $this->endDate = now();
        } else {
            $this->startDate = $date->copy()->startOfDay();
            $this->endDate = $date->copy()->endOfDay();
        }
    }
}
