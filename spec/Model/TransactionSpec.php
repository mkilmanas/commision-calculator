<?php

namespace spec\App\Model;

use App\Model\Account;
use App\Model\Currency;
use App\Model\Transaction;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TransactionSpec extends ObjectBehavior
{
    function let() {
        $this->beConstructedWith(
            new \DateTimeImmutable(),
            12,
            Account::TYPE_NATURAL,
            Transaction::TYPE_CASH_IN,
            200.0,
            Currency::EUR()
        );
    }

    function it_is_initializable_with_data_parameters()
    {
        $this->shouldHaveType(Transaction::class);
    }

    function it_throws_exception_when_initialized_without_parameters()
    {
        $this->beConstructedWith();
        $this->shouldThrow()->duringInstantiation();
    }

    function it_throws_exception_when_initialized_with_wrong_parameter_types()
    {
        $this->beConstructedWith(1, 'foo', 20, 50, 'BAR', 'GBP');
        $this->shouldThrow()->duringInstantiation();
    }

    function it_returns_its_date()
    {
        $date = new \DateTimeImmutable();
        $this->beConstructedWith($date, 1, Account::TYPE_NATURAL, Transaction::TYPE_CASH_IN, 200, Currency::EUR());
        $this->getDate()->shouldReturn($date);
    }

    function it_returns_account_id()
    {
        $this->getAccountId()->shouldReturn(12);
    }

    function it_returns_account_type()
    {
        $this->getAccountType()->shouldReturn(Account::TYPE_NATURAL);
    }

    function it_returns_transaction_type()
    {
        $this->getTransactionType()->shouldReturn(Transaction::TYPE_CASH_IN);
    }

    function it_returns_amount()
    {
        $this->getAmount()->shouldReturn(200.0);
    }

    function it_returns_currency()
    {
        $this->getCurrency()->shouldReturn(Currency::EUR());
    }

    function its_fee_is_zero_by_default()
    {
        $this->getFee()->shouldReturn(0.0);
    }

    function its_fee_can_be_set()
    {
        $this->setFee(12.99);
        $this->getFee()->shouldReturn(12.99);
    }
}
