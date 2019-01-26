<?php

namespace App\Helper;

use App\Model\Currency;

class EuroForex
{
    const EUR_RATES = [
        'EUR' => 1.0000,
        'USD' => 1.1497,
        'JPY' => 129.53,
    ];

    public function exchange(float $value, Currency $from, Currency $to) : float
    {
        if ($from === Currency::EUR()) {
            return self::EUR_RATES[$to->getCode()] * $value;
        } elseif ($to === Currency::EUR()) {
            return 1.0 / self::EUR_RATES[$from->getCode()] * $value;
        } else {
            throw new \InvalidArgumentException(__CLASS__ . " can only exchange values to and from EUR. Could not exchange '{$from->getCode()}' for '{$to->getCode()}'");
        }
    }
}
