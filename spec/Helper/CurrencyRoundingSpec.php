<?php

namespace spec\App\Helper;

use App\Helper\CurrencyRounding;
use App\Model\Currency;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CurrencyRoundingSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(CurrencyRounding::class);
    }

    function it_rounds_up_to_a_whole_number_when_currency_has_no_decimal_places(Currency $currency)
    {
        $currency->getDecimalPlaces()->willReturn(0);
        $this->roundUp(12.759, $currency)->shouldReturn(13.0);
    }

    function it_rounds_up_to_a_valid_number_when_currency_has_decimal_places(Currency $currency)
    {
        $currency->getDecimalPlaces()->willReturn(2);
        $this->roundUp(5.1274, $currency)->shouldReturn(5.13);
    }

    function it_rounds_up_even_if_value_is_closer_to_smaller_value(Currency $currency)
    {
        $currency->getDecimalPlaces()->willReturn(2);
        $this->roundUp(8.00000001, $currency)->shouldReturn(8.01);
    }

    function it_does_not_modify_values_which_are_already_valid_according_to_decimal_places(Currency $currency)
    {
        $currency->getDecimalPlaces()->willReturn(2);
        $this->roundUp(7.99, $currency)->shouldReturn(7.99);
    }
}
