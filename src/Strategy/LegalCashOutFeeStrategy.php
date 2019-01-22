<?php

namespace App\Strategy;

use App\Model\Account;
use App\Model\Transaction;

class LegalCashOutFeeStrategy implements FeeStrategyInterface
{
    const STANDARD_RATE = 0.003;
    const MINIMUM_FEE = 0.50;

    function isApplicable(Account $account, Transaction $transaction)
    {
        return $transaction->getAccountType() === Account::TYPE_LEGAL
            && $transaction->getTransactionType() === Transaction::TYPE_CASH_OUT;
    }

    function calculateFee(Account $account, Transaction $transaction)
    {
        $standardFee = $transaction->getAmount() * static::STANDARD_RATE;
        $transaction->setFee(max($standardFee, static::MINIMUM_FEE));
    }
}
