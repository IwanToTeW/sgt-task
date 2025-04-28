<?php

namespace App\Actions\BitFinex;

use App\Dtos\PriceItemResponse;
use App\Dtos\QueryDateInterval;
use App\Enums\CurrencyPair;
use App\Enums\ViewInterval;
use App\Models\Price;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class GetHistoryData
{
    /**
     * @throws \Exception
     */
    public function execute(array $params): array
    {
        $interval = $params['interval'] ?? ViewInterval::Day->value;
        $isDayView = $interval === ViewInterval::Day->value;

        $interval = new QueryDateInterval($params['date'] ?? null);
        $start = $interval->startDate;
        $end = $interval->endDate;


        if ($start->isFuture()) {
            throw(new \Exception('Start Date cannot be in the future'));
        }

//        todo
//        check if we have the results in db and return them instead of making a request again

        $response = Http::bitFinex()
            ->get($this->getEndpoint($isDayView, $params['pair'] ?? CurrencyPair::BTC_USD->value, $start->timestamp, $end->timestamp));

        return $this->mapDailyDataForChart($response);
    }

    private function getEndpoint(bool $isDayView, string $pair, int $startTimestamp, int $endTimestamp): string
    {
        $startTimestamp = $startTimestamp * 1000;
        $endTimestamp = $endTimestamp * 1000;

// todo potentially use the pair to determine the limit when week view is implemented
//        $limit = ($pair === ViewInterval::Day->value) ? 24 : 168;
        $limit = 24;
        if ($isDayView) {
            return 'tickers/hist?symbols='.$pair.'&limit='.$limit.'&start='.$startTimestamp.'&end='.$endTimestamp;
        }
        return 'tickers/hist?symbols='.$pair.'&limit='.$limit.'&end='.$endTimestamp;
    }

    private function parseDailyData($response): Collection
    {
        $midPriceAction = new GetMidPrice();

        return collect($response->json())->map(function ($item) use ($midPriceAction) {
            $dto = new PriceItemResponse($item);
            $price = new Price([
                'pair' => $dto->pair,
                'date' => Carbon::parse($dto->date / 1000),
                'ask' => $dto->ask,
                'bid' => $dto->bid,
            ]);

            $price->save();
            return [
                'pair' => $dto->pair,
                'date' => Carbon::parse($dto->date / 1000)->format('y-m-d'),
                'hour' => Carbon::parse($dto->date / 1000)->format('H'),
                'mid' => $midPriceAction->execute($dto->ask, $dto->bid)
            ];
        })->reverse();
    }

    private function mapDailyDataForChart($response): array
    {
        $mapped = $this->parseDailyData($response);

        return [
            'labels' => $mapped->pluck('hour'),
            'data' => $mapped->pluck('mid'),
        ];
    }
}
