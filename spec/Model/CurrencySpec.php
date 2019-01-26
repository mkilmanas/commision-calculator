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
