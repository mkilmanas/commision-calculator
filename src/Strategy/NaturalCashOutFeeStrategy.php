<?php

namespace App\Strategy;

use App\Helper\EuroForex;
use App\Model\Account;
use App\Model\Currency;
use App\Model\Transaction;
use Doctrine\Common\Collections\Collection;

class NaturalCashOutFeeStrategy implements FeeStrategyInterface
{
    const STANDARD_RATE = 0.003;
    const FREE_TIER = 1000;

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
        return $transaction->getAccountType() === Account::TYPE_NATURAL
            && $transaction->getTransactionType() === Transaction::TYPE_CASH_OUT;
    }

    function calculateFee(Account $account, Transaction $transaction)
    {
        /** @var Transaction[] $thisWeeksHistory */
        $thisWeeksHistory = $this->getThisWeekCashOutHistory($account, $transaction);

        if ($thisWeeksHistory->count() >= 3) {
            $transaction->setFee($transaction->getAmount() * static::STANDARD_RATE);
            return;
        }

        $thisWeekAmount = 0;
        foreach ($thisWeeksHistory as $t) {
            $thisWeekAmount += $this->forex->exchange($t->getAmount(), $t->getCurrency(), Currency::EUR());
        }

        if ($thisWeekAmount >= static::FREE_TIER) {
            $transaction->setFee($transaction->getAmount() * static::STANDARD_RATE);
            return;
        }
        $thisWeekAmount += $this->forex->exchange($transaction->getAmount(), $transaction->getCurrency(), Currency::EUR());

        if ($thisWeekAmount >= static::FREE_TIER) {
            $billableAmount = $this->forex->exchange($thisWeekAmount - static::FREE_TIER, Currency::EUR(), $transaction->getCurrency());
            $transaction->setFee($billableAmount * static::STANDARD_RATE);
            return;
        }

        $transaction->setFee(0.0);
    }

    private function getThisWeekCashOutHistory(Account $account, Transaction $transaction) : Collection
    {
        $transactionDate = $transaction->getDate();
        return $account->getTransactionHistory()->filter(
            function (Transaction $t) use ($transactionDate) {
                return $t->getTransactionType() === Transaction::TYPE_CASH_OUT
                    && $t->getDate()->format('W') === $transactionDate->format('W')
                    && $transactionDate->diff($t->getDate(), true)->days < 7;
            }
        );
    }


}
