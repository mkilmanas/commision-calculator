<?php

namespace spec\App\Helper;

use App\Helper\EuroForex;
use App\Model\Currency;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class EuroForexSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(EuroForex::class);
    }

    function it_converts_value_from_EUR_to_USD()
    {
        $this->exchange(1.00, Currency::EUR(), Currency::USD())->shouldBeApproximately(1.1497, 0.00001);
    }

    function it_converts_value_from_USD_to_EUR()
    {
        $this->exchange(114.97, Currency::USD(), Currency::EUR())->shouldBeApproximately(100.00, 0.00001);
    }

    function it_converts_value_from_EUR_to_JPY()
    {
        $this->exchange(10.00, Currency::EUR(), Currency::JPY())->shouldBeApproximately(1295.3, 0.00001);
    }

    function it_converts_value_from_JPY_to_EUR()
    {
        $this->exchange(12953, Currency::JPY(), Currency::EUR())->shouldBeApproximately(100.00, 0.00001);
    }

    function it_returns_the_same_value_when_convertinig_EUR_to_EUR()
    {
        $this->exchange(1234.56, Currency::EUR(), Currency::EUR())->shouldBeApproximately(1234.56, 0.00001);
    }

    function it_throws_exception_if_neither_currency_is_EUR()
    {
        $this->shouldThrow()->during('exchange', [9.99, Currency::USD(), Currency::JPY()]);
    }
}
