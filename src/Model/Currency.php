<?php

namespace App\Model;

class Currency
{
    const RATE_EUR_USD = 1.1497;
    const RATE_EUR_JPY = 129.53;

    private static $instances = [];

    private $code;

    protected function __construct(string $code)
    {
        $this->code = $code;
    }

    private static function getInstance(string $code) : Currency
    {
        if (!isset(static::$instances[$code])) {
            static::$instances[$code] = new static($code);
        }
        return static::$instances[$code];
    }

    public static function EUR() : Currency
    {
        return static::getInstance('EUR');
    }


    public static function JPY() : Currency
    {
        return static::getInstance('JPY');
    }

    public static function USD() : Currency
    {
        return static::getInstance('USD');
    }

    public function getCode() : string
    {
        return $this->code;
    }

    public function rateTo(Currency $toCurrency)
    {
        $rate = 1.0;
        if ($this->code !== 'EUR') {
            $rate = 1.0 / Currency::EUR()->rateTo($this);
        }
        switch ($toCurrency->code) {
            case 'USD': return $rate * static::RATE_EUR_USD;
            case 'JPY': return $rate * static::RATE_EUR_JPY;
            default: return $rate;
        }
    }
}
