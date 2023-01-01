<?php

namespace App\Traits;

trait PriceCoin
{
    public function PriceCoinToUsd($CoinName): int{
        return (\Illuminate\Support\Facades\Http::get('https://open.er-api.com/v6/latest/USD'))['rates'][$CoinName];
    }

}
