<?php

namespace spec\App;

use App\CommissionCalculator;
use App\Helper\CurrencyRounding;
use App\Model\Account;
use App\Model\AccountRegistry;
use App\Model\Currency;
use App\Model\Transaction;
use App\Strategy\FeeStrategyInterface;
use phpDocumentor\Reflection\DocBlock\Tags\Factory\Strategy;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CommissionCalculatorSpec extends ObjectBehavior
{
    function let(AccountRegistry $registry, CurrencyRounding $rounding, FeeStrategyInterface $dummyStrategy)
    {
        $this->beConstructedWith($registry, $rounding, $dummyStrategy);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(CommissionCalculator::class);
    }

    function it_accepts_any_number_of_strategies_as_constructor_parameters(
        AccountRegistry $registry,
        CurrencyRounding $rounding,
        FeeStrategyInterface $feeStrategyA,
        FeeStrategyInterface $feeStrategyB
    ) {
        $this->beConstructedWith($registry, $rounding, $feeStrategyA, $feeStrategyB);
        $this->shouldHaveType(CommissionCalculator::class);
    }

    function it_throws_exception_if_passing_an_invalid_strategy_parameter(
        AccountRegistry $registry,
        CurrencyRounding $rounding
    ) {
        $this->beConstructedWith($registry, $rounding, $registry);
        $this->shouldThrow()->duringInstantiation();
    }

    function it_uses_registry_to_find_account_for_transaction(
        AccountRegistry $registry,
        Transaction $transaction,
        Account $account
    ) {
        $transaction->getAccountId()->willReturn(29);
        $registry->find(29)->shouldBeCalled()->willReturn($account);

        $this->calculateFee($transaction);
    }

    function it_creates_a_new_account_and_adds_it_to_the_registry_if_it_was_not_found(
        AccountRegistry $registry,
        Transaction $transaction
    ) {
        $transaction->getAccountId()->willReturn(47);
        $transaction->getAccountType()->willReturn(Account::TYPE_NATURAL);
        $transaction->getDate()->willReturn(new \DateTimeImmutable());

        $registry->find(47)->willReturn(null);
        $registry->add(
            Argument::that(
                function (Account $a)
                {
                    return $a->getId() === 47 && $a->getType() === Account::TYPE_NATURAL;
                }
            )
        )->shouldBeCalled();

        $this->calculateFee($transaction);
    }

    function it_iterates_over_strategies_to_find_applicable_one_and_uses_it_for_calculation(
        AccountRegistry $registry,
        CurrencyRounding $rounding,
        Account $account,
        Transaction $transaction,
        Currency $currency,
        FeeStrategyInterface $strategyA,
        FeeStrategyInterface $strategyB
    ) {
        $this->beConstructedWith($registry, $rounding, $strategyA, $strategyB);

        $transaction->getAccountId()->willReturn(29);

        $registry->find(29)->willReturn($account);

        $strategyA->isApplicable($account, $transaction)->willReturn(false);
        $strategyA->calculateFee(Argument::any(), Argument::any())->shouldNotBeCalled();

        $strategyB->isApplicable($account, $transaction)->willReturn(true);
        $strategyB->calculateFee($account, $transaction)->shouldBeCalled();

        $transaction->getFee()->willReturn(7.99);
        $transaction->getCurrency()->willReturn($currency);
        $rounding->roundUp(7.99, $currency)->willReturn(7.99);
        $transaction->setFee(7.99)->shouldBeCalled();

        $this->calculateFee($transaction);
    }

    function it_calculates_fees_for_multiple_transactions(
        AccountRegistry $registry,
        Account $account,
        Transaction $transactionA,
        Transaction $transactionB
    ) {
        $transactionA->getAccountId()->willReturn(1);
        $transactionB->getAccountId()->willReturn(1);

        $registry->find(1)->willReturn($account);

        $this->calculateAllFees([$transactionA, $transactionB]);
    }
}
