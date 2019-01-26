<?php

namespace spec\App\Strategy;

use App\Helper\EuroForex;
use App\Model\Account;
use App\Model\Currency;
use App\Model\Transaction;
use App\Strategy\FeeStrategyInterface;
use App\Strategy\NaturalCashOutFeeStrategy;
use Doctrine\Common\Collections\ArrayCollection;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class NaturalCashOutFeeStrategySpec extends ObjectBehavior
{
    function let(EuroForex $euroForex)
    {
        $this->beConstructedWith($euroForex);

        $euroForex->exchange(Argument::type('float'), Currency::EUR(), Currency::EUR())
            ->will(function ($params) { return $params[0]; });
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(NaturalCashOutFeeStrategy::class);
    }

    function it_is_a_fee_strategy()
    {
        $this->shouldImplement(FeeStrategyInterface::class);
    }

    function it_is_applicable_to_natural_accounts_and_cash_out_transactions(
        Transaction $transaction,
        Account $account
    ) {
        $transaction->getAccountType()->willReturn(Account::TYPE_NATURAL);
        $transaction->getTransactionType()->willReturn(Transaction::TYPE_CASH_OUT);

        $this->isApplicable($account, $transaction)->shouldReturn(true);
    }

    function it_is_not_applicable_to_legal_accounts(
        Transaction $transaction,
        Account $account
    ) {
        $transaction->getAccountType()->willReturn(Account::TYPE_LEGAL);
        $transaction->getTransactionType()->willReturn(Transaction::TYPE_CASH_OUT);

        $this->isApplicable($account, $transaction)->shouldReturn(false);
    }

    function it_is_not_applicable_to_cash_in_transactions(
        Transaction $transaction,
        Account $account
    ) {
        $transaction->getAccountType()->willReturn(Account::TYPE_NATURAL);
        $transaction->getTransactionType()->willReturn(Transaction::TYPE_CASH_IN);

        $this->isApplicable($account, $transaction)->shouldReturn(false);
    }

    function it_sets_zero_fee_for_a_first_transaction_under_1000_EUR(
        Transaction $transaction,
        Account $account,
        \DateTimeImmutable $date
    ) {
        $transaction->getAmount()->willReturn(100);
        $transaction->getCurrency()->willReturn(Currency::EUR());
        $transaction->getDate()->willReturn($date);
        $date->format('Y-W')->willReturn('2018-51');
        $account->getTransactionHistory()->willReturn(new ArrayCollection());

        $transaction->setFee(0.0)->shouldBeCalled();

        $this->calculateFee($account, $transaction);
    }

    function it_calculates_standard_fee_for_a_fourth_transaction_in_a_week(
        Transaction $currentTransaction,
        \DateTimeImmutable $date
    ) {
        $account = new Account(1, Account::TYPE_NATURAL);

        $account->addTransaction(new Transaction(new \DateTimeImmutable('2018-12-24'), 1, 'natural', 'cash_out', 100, Currency::EUR()));
        $account->addTransaction(new Transaction(new \DateTimeImmutable('2018-12-25'), 1, 'natural', 'cash_out', 100, Currency::EUR()));
        $account->addTransaction(new Transaction(new \DateTimeImmutable('2018-12-26'), 1, 'natural', 'cash_out', 100, Currency::EUR()));

        $currentTransaction->getAmount()->willReturn(100);
        $currentTransaction->getCurrency()->willReturn(Currency::EUR());
        $currentTransaction->getDate()->willReturn($date);
        $date->format('Y-W')->willReturn('2018-52');

        $currentTransaction->setFee(0.3)->shouldBeCalled();

        $this->calculateFee($account, $currentTransaction);
    }

    function it_considers_transactions_only_from_the_same_week_for_free_tier_calculation(
        Transaction $currentTransaction,
        \DateTimeImmutable $date
    ) {
        $account = new Account(1, Account::TYPE_NATURAL);

        $account->addTransaction(new Transaction(new \DateTimeImmutable('2018-10-10'), 1, 'natural', 'cash_out', 100, Currency::EUR()));
        $account->addTransaction(new Transaction(new \DateTimeImmutable('2018-12-25'), 1, 'natural', 'cash_out', 100, Currency::EUR()));
        $account->addTransaction(new Transaction(new \DateTimeImmutable('2018-12-26'), 1, 'natural', 'cash_out', 100, Currency::EUR()));

        $currentTransaction->getAmount()->willReturn(100);
        $currentTransaction->getCurrency()->willReturn(Currency::EUR());
        $currentTransaction->getDate()->willReturn($date);
        $date->format('Y-W')->willReturn('2018-52');

        $currentTransaction->setFee(0.0)->shouldBeCalled();

        $this->calculateFee($account, $currentTransaction);
    }

    function it_calculates_standard_fee_for_excess_of_1000(
        Transaction $transaction,
        \DateTimeImmutable $date,
        Account $account
    ) {
        $transaction->getAmount()->willReturn(2000);
        $transaction->getDate()->willReturn($date);
        $transaction->getCurrency()->willReturn(Currency::EUR());
        $account->getTransactionHistory()->willReturn(new ArrayCollection());

        $transaction->setFee(3.0)->shouldBeCalled();

        $this->calculateFee($account, $transaction);
    }

    function it_sets_zero_fee_for_a_first_transaction_under_1000_EUR_equivalent_in_other_currency(
        EuroForex $euroForex,
        Transaction $transaction,
        Account $account,
        \DateTimeImmutable $date
    ) {
        $transaction->getAmount()->willReturn(1100);
        $transaction->getCurrency()->willReturn(Currency::USD());
        $transaction->getDate()->willReturn($date);
        $date->format('Y-W')->willReturn('2018-51');
        $account->getTransactionHistory()->willReturn(new ArrayCollection());

        $euroForex->exchange(1100, Currency::USD(), Currency::EUR())->willReturn(956.77133);

        $transaction->setFee(0.0)->shouldBeCalled();

        $this->calculateFee($account, $transaction);
    }

    function it_calculates_standard_fee_for_excess_of_1000_EUR_equivalent_in_other_currency(
        EuroForex $euroForex,
        Transaction $transaction,
        \DateTimeImmutable $date,
        Account $account
    ) {
        $transaction->getAmount()->willReturn(150000);
        $transaction->getDate()->willReturn($date);
        $transaction->getCurrency()->willReturn(Currency::JPY());
        $account->getTransactionHistory()->willReturn(new ArrayCollection());

        $e = 10 ** (-10);
        $euroForex->exchange(150000, Currency::JPY(), Currency::EUR())->willReturn(1219.5121);
        $euroForex->exchange(Argument::approximate(219.5121, $e), Currency::EUR(), Currency::JPY())->willReturn(26999.9883);

        $transaction->setFee(Argument::approximate(80.9999649, $e))->shouldBeCalled();

        $this->calculateFee($account, $transaction);
    }
}
