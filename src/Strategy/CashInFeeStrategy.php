<?php

namespace App\Strategy;

use App\Model\Account;
use App\Model\Transaction;

class CashInFeeStrategy implements FeeStrategyInterface
{
    const STANDARD_RATE = 0.0003;
    const CAP_AMOUNT = 5.0;

    function isApplicable(Account $account, Transaction $transaction)
    {
        return $transaction->getTransactionType() === Transaction::TYPE_CASH_IN;
    }

    public function calculateFee(Account $account, Transaction $transaction)
    {
        $standardFee = $transaction->getAmount() * self::STANDARD_RATE;
        $transaction->setFee(min($standardFee, self::CAP_AMOUNT));
    }
}
