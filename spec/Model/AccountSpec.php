<?php

namespace spec\App\Model;

use App\Model\Account;
use App\Model\Transaction;
use PhpSpec\ObjectBehavior;

class AccountSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(1, Account::TYPE_NATURAL);
    }

    function it_is_initializable_with_id_and_type()
    {
        $this->beConstructedWith(1, Account::TYPE_NATURAL);
        $this->shouldHaveType(Account::class);
    }

    function it_throws_exception_when_initalized_without_parameters()
    {
        $this->beConstructedWith();
        $this->shouldThrow()->duringInstantiation();
    }

    function it_throws_exception_when_initalized_with_wrong_parameter_types()
    {
        $this->beConstructedWith('foo', 123);
        $this->shouldThrow()->duringInstantiation();
    }

    function it_throws_exception_when_initalized_with_invalid_type()
    {
        $this->beConstructedWith(1, 'random');
        $this->shouldThrow()->duringInstantiation();
    }

    function it_returns_provided_id()
    {
        $this->beConstructedWith(121, Account::TYPE_LEGAL);
        $this->getId()->shouldReturn(121);
    }

    function it_returns_its_type()
    {
        $this->beConstructedWith(121, Account::TYPE_LEGAL);
        $this->getType()->shouldReturn(Account::TYPE_LEGAL);
    }

    function its_transaction_history_is_empty_by_default()
    {
        $this->getTransactionHistory()->shouldBeEmpty();
    }

    function it_adds_transaction_to_the_history(Transaction $transaction)
    {
        $transaction->getDate()->willReturn(new \DateTimeImmutable());
        $this->addTransaction($transaction);
        $this->getTransactionHistory()->shouldContain($transaction);
    }

    function it_keeps_transaction_history_in_chronological_order(Transaction $transactionA, Transaction $transactionB)
    {
        $transactionA->getDate()->willReturn(new \DateTimeImmutable('2019-01-01'));
        $transactionB->getDate()->willReturn(new \DateTimeImmutable('2018-12-31'));

        $this->addTransaction($transactionA);
        $this->addTransaction($transactionB);

        $this->getTransactionHistory()->first()->shouldBe($transactionB);
        $this->getTransactionHistory()->last()->shouldBe($transactionA);
    }
}
