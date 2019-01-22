<?php

namespace spec\App\Strategy;

use App\Model\Account;
use App\Model\Transaction;
use App\Strategy\FeeStrategyInterface;
use App\Strategy\LegalCashOutFeeStrategy;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class LegalCashOutFeeStrategySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(LegalCashOutFeeStrategy::class);
    }

    function it_is_a_fee_strategy()
    {
        $this->shouldImplement(FeeStrategyInterface::class);
    }

    function it_is_applicable_for_legal_accounts_and_cash_out_transactions(
        Transaction $transaction,
        Account $account
    ) {
        $transaction->getAccountType()->willReturn(Account::TYPE_LEGAL);
        $transaction->getTransactionType()->willReturn(Transaction::TYPE_CASH_OUT);

        $this->isApplicable($account, $transaction)->shouldReturn(true);
    }


    function it_is_not_applicable_for_natural_accounts(
        Transaction $transaction,
        Account $account
    ) {
        $transaction->getAccountType()->willReturn(Account::TYPE_NATURAL);
        $transaction->getTransactionType()->willReturn(Transaction::TYPE_CASH_OUT);

        $this->isApplicable($account, $transaction)->shouldReturn(false);
    }

    function it_is_not_applicable_for_cash_in_transactions(
        Transaction $transaction,
        Account $account
    ) {
        $transaction->getAccountType()->willReturn(Account::TYPE_LEGAL);
        $transaction->getTransactionType()->willReturn(Transaction::TYPE_CASH_IN);

        $this->isApplicable($account, $transaction)->shouldReturn(false);
    }

    function it_calculates_fee_by_percentage(
        Transaction $transaction,
        Account $account
    ) {
        $transaction->getAmount()->willReturn(5000);

        $transaction->setFee(15.0)->shouldBeCalled();

        $this->calculateFee($account, $transaction);
    }

    function it_applies_minimum_fee(
        Transaction $transaction,
        Account $account
    )
    {
        $transaction->getAmount()->willReturn(10);

        $transaction->setFee(0.5)->shouldBeCalled();

        $this->calculateFee($account, $transaction);
    }
}
