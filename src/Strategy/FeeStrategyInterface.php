<?php

namespace App\Strategy;


use App\Model\Account;
use App\Model\Transaction;

interface FeeStrategyInterface
{
    function isApplicable(Account $account, Transaction $transaction);
    function calculateFee(Account $account, Transaction $transaction);
}