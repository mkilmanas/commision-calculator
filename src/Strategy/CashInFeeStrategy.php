<?php

namespace App\Strategy;

use App\Helper\EuroForex;
use App\Model\Account;
use App\Model\Currency;
use App\Model\Transaction;

class CashInFeeStrategy implements FeeStrategyInterface
{
    const STANDARD_RATE = 0.0003;
    const CAP_AMOUNT = 5.0;

    /**
     * @var EuroForex
     */
    private $forex;

    public function __construct(EuroForex $forex)
    {
        $this->forex = $forex;
    }

    function isApplicable(Account $account, Transaction $transaction)
    {
        return $transaction->getTransactionType() === Transaction::TYPE_CASH_IN;
    }

    public function calculateFee(Account $account, Transaction $transaction)
    {
        $standardFee = $transaction->getAmount() * self::STANDARD_RATE;
        $maxFee = $this->forex->exchange(self::CAP_AMOUNT, Currency::EUR(), $transaction->getCurrency());

        $transaction->setFee(min($standardFee, $maxFee));
    }
}
