<?php

namespace spec\App\Model;

use App\Model\Account;
use App\Model\AccountRegistry;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AccountRegistrySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(AccountRegistry::class);
    }

    function it_returns_null_if_account_is_not_found()
    {
        $this->find(99)->shouldReturn(null);
    }

    function it_returns_the_account_if_it_was_added(Account $account)
    {
        $account->getId()->willReturn(78);
        $this->add($account);
        $this->find(78)->shouldReturn($account);
    }
}
