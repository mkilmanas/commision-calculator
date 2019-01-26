<?php

namespace spec\App\Model;

use App\Model\Currency;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CurrencySpec extends ObjectBehavior
{
    function it_is_initializable_via_factory_methods()
    {
        $this->beConstructedThrough('EUR');
        $this->shouldHaveType(Currency::class);
    }

    function it_returns_its_code()
    {
        $this->beConstructedThrough('USD');
        $this->getCode()->shouldReturn('USD');
    }

    function it_returns_exchange_rate_from_EUR_to_USD()
    {
        $this->beConstructedThrough('EUR');
        $this->rateTo(Currency::USD())->shouldReturn(Currency::RATE_EUR_USD);
    }

    function it_returns_exchange_rate_from_EUR_to_JPY()
    {
        $this->beConstructedThrough('EUR');
        $this->rateTo(Currency::JPY())->shouldReturn(Currency::RATE_EUR_JPY);
    }

    function it_returns_exchange_rate_from_USD_to_EUR()
    {
        $this->beConstructedThrough('USD');
        $this->rateTo(Currency::EUR())->shouldReturn(1 / Currency::RATE_EUR_USD);
    }

    function it_returns_exchange_rate_from_JPY_to_EUR()
    {
        $this->beConstructedThrough('JPY');
        $this->rateTo(Currency::EUR())->shouldReturn(1 / Currency::RATE_EUR_JPY);
    }

    function it_returns_exchange_rate_from_USD_to_JPY()
    {
        $this->beConstructedThrough('USD');
        $this->rateTo(Currency::JPY())->shouldReturn(1 / Currency::RATE_EUR_USD * Currency::RATE_EUR_JPY);
    }

    function it_returns_exchange_rate_from_JPY_to_USD()
    {
        $this->beConstructedThrough('JPY');
        $this->rateTo(Currency::USD())->shouldReturn(1 / Currency::RATE_EUR_JPY * Currency::RATE_EUR_USD);
    }

    function it_returns_2_decimal_places_precision_for_EUR()
    {
        $this->beConstructedThrough('EUR');
        $this->getDecimalPlaces()->shouldReturn(2);
    }

    function it_returns_2_decimal_places_precision_for_USD()
    {
        $this->beConstructedThrough('USD');
        $this->getDecimalPlaces()->shouldReturn(2);
    }

    function it_returns_0_decimal_places_precision_for_JPY()
    {
        $this->beConstructedThrough('JPY');
        $this->getDecimalPlaces()->shouldReturn(0);
    }
}
