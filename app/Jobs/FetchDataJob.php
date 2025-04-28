<?php

namespace App\Jobs;

use App\Actions\BitFinex\GetHistoryData;
use App\Enums\CurrencyPair;
use App\Enums\ViewInterval;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class FetchDataJob implements ShouldQueue
{
    use Queueable;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
//        todo test if the job is running as expected
        collect(CurrencyPair::cases())
            ->each(fn($pair) => (new GetHistoryData)->execute([
                'interval' => ViewInterval::Day->value,
                'pair' => $pair->value,
                'date' => now()
            ]));
    }
}
