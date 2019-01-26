<?php

namespace App\Helper;

use App\Model\Currency;

class CurrencyRounding
{
    public function roundUp(float $value, Currency $currency) : float
    {
        $multiplier = 10 ** $currency->getDecimalPlaces();

        return ceil($value * $multiplier) / $multiplier;
    }
}
