<?php

use App\Enums\CurrencyPair;
use App\Enums\ViewInterval;
use Illuminate\Support\Carbon;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    $this->createUser();
});

it('can see chart for a day', function (string $pair) {
    $this->get('/dashboard?interval=day&pair=' . $pair)
        ->assertStatus(200)
        ->assertInertia(function (Assert $page) {
            $page->component('Dashboard')
                ->has('labels')
                ->has('data', 24)
                ->has('queryParams')
                ->has('pairs.data', collect(CurrencyPair::cases())->count())
                ->has('intervals.data', collect(ViewInterval::cases())->count());
        });
})->with(collect(CurrencyPair::cases())->pluck('value')->toArray());

it('returns today if future date is provided for interval: ', function (string $interval) {
    $this->get('/dashboard?interval='.$interval.'&date=' . Carbon::now()->addDay()->format('Y-m-d'))
        ->assertStatus(200)
        ->assertInertia(function (Assert $page) use ($interval) {
            $page->component('Dashboard')
                ->has('labels')
                ->has('data', $interval === 'day' ? 24 : 8)
                ->has('queryParams', function (Assert $page) use($interval) {
                    $page->where('interval', $interval)
                        ->where('date', Carbon::now()->format('Y-m-d'))
                        ->has('pair');
                })
                ->has('pairs.data', collect(CurrencyPair::cases())->count())
                ->has('intervals.data', collect(ViewInterval::cases())->count());
        });
})->with(['day']);
//todo replace with week when week view is implemented
//})->with(collect(ViewInterval::cases())->pluck('value')->toArray());

it('returns daily btc/usd chart as a default', function () {
    $this->get('/dashboard')
        ->assertStatus(200)
        ->assertInertia(function (Assert $page) {
            $page->component('Dashboard')
                ->has('labels')
                ->has('data', 24)
                ->has('queryParams', function (Assert $page) {
                    $page->where('interval', ViewInterval::Day->value)
                        ->where('date', Carbon::now()->format('Y-m-d'))
                        ->where('pair', CurrencyPair::BTC_USD->value);
                })
                ->has('pairs.data', collect(CurrencyPair::cases())->count())
                ->has('intervals.data', collect(ViewInterval::cases())->count());
        });
});

it('throws validation errors when invalid params are provided', function ($data) {
    $this->get($data['url'])->assertSessionHasErrors($data['errors']);
})->with([
    'invalid interval' => fn () => [
        'url' => '/dashboard?interval=invalid',
        'errors' => ['interval']
    ],
    'invalid pair' => fn () => [
        'url' => '/dashboard?pair=invalid',
        'errors' => ['pair']
    ],
    'invalid date' => fn () => [
        'url' => '/dashboard?date=invalid',
        'errors' => ['date']
    ],
]);

