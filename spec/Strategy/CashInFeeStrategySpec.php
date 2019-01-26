<?php

namespace spec\App\Strategy;

use App\Helper\EuroForex;
use App\Model\Account;
use App\Model\Currency;
use App\Model\Transaction;
use App\Strategy\CashInFeeStrategy;
use App\Strategy\FeeStrategyInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CashInFeeStrategySpec extends ObjectBehavior
{
    function let(EuroForex $euroForex)
    {
        $this->beConstructedWith($euroForex);

        $euroForex->exchange(5.0, Currency::EUR(), Currency::EUR())->willReturn(5.0);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(CashInFeeStrategy::class);
    }

    function it_is_a_fee_strategy()
    {
        $this->shouldImplement(FeeStrategyInterface::class);
    }

    function it_is_applicable_for_cash_in_transactions(Transaction $transaction, Account $account)
    {
        $transaction->getTransactionType()->willReturn(Transaction::TYPE_CASH_IN);

        $this->isApplicable($account, $transaction)->shouldReturn(true);
    }

    function it_is_not_applicable_for_cash_out_transactions(Transaction $transaction, Account $account)
    {
        $transaction->getTransactionType()->willReturn(Transaction::TYPE_CASH_OUT);

        $this->isApplicable($account, $transaction)->shouldReturn(false);
    }

    function it_calculates_percentage_fee(Account $account, Transaction $transaction)
    {
        $transaction->getAmount()->willReturn(1000);
        $transaction->getCurrency()->willReturn(Currency::EUR());

        $transaction->setFee(0.3)->shouldBeCalled();

        $this->calculateFee($account, $transaction);
    }

    function it_caps_the_fee(Account $account, Transaction $transaction)
    {
        $transaction->getAmount()->willReturn(1000000);
        $transaction->getCurrency()->willReturn(Currency::EUR());

        $transaction->setFee(5.0)->shouldBeCalled();

        $this->calculateFee($account, $transaction);
    }

    function it_caps_the_fee_in_other_currencies_to_the_equivalent_value(
        Account $account,
        Transaction $transaction,
        EuroForex $euroForex
    ) {
        $transaction->getAmount()->willReturn(1000000);
        $transaction->getCurrency()->willReturn(Currency::USD());

        $euroForex->exchange(5.0, Currency::EUR(), Currency::USD())->willReturn(5.7485);

        $transaction->setFee(5.7485)->shouldBeCalled();

        $this->calculateFee($account, $transaction);
    }
}
