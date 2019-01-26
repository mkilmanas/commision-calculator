<?php

namespace App\Model;

class Currency
{
    const DECIMAL_PLACES = [
        'EUR' => 2,
        'USD' => 2,
        'JPY' => 0,
    ];

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

    public function getDecimalPlaces() : int
    {
        return self::DECIMAL_PLACES[$this->code];
    }
}
