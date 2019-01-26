<?php

namespace App\Strategy;

use App\Helper\EuroForex;
use App\Model\Account;
use App\Model\Currency;
use App\Model\Transaction;

class LegalCashOutFeeStrategy implements FeeStrategyInterface
{
    const STANDARD_RATE = 0.003;
    const MINIMUM_FEE = 0.50;

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
        return $transaction->getAccountType() === Account::TYPE_LEGAL
            && $transaction->getTransactionType() === Transaction::TYPE_CASH_OUT;
    }

    function calculateFee(Account $account, Transaction $transaction)
    {
        $standardFee = $transaction->getAmount() * static::STANDARD_RATE;
        $minFee = $this->forex->exchange(static::MINIMUM_FEE, Currency::EUR(), $transaction->getCurrency());

        $transaction->setFee(max($standardFee, $minFee));
    }
}
