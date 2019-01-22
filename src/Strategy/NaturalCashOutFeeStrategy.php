<?php

namespace App\Strategy;

use App\Model\Account;
use App\Model\Transaction;
use Doctrine\Common\Collections\Collection;

class NaturalCashOutFeeStrategy implements FeeStrategyInterface
{
    const STANDARD_RATE = 0.003;
    const FREE_TIER = 1000;

    function isApplicable(Account $account, Transaction $transaction)
    {
        return $transaction->getAccountType() === Account::TYPE_NATURAL
            && $transaction->getTransactionType() === Transaction::TYPE_CASH_OUT;
    }

    function calculateFee(Account $account, Transaction $transaction)
    {
        $thisWeeksHistory = $this->getThisWeekCashOutHistory($account, $transaction);

        if ($thisWeeksHistory->count() >= 3) {
            $transaction->setFee($transaction->getAmount() * static::STANDARD_RATE);
            return;
        }

        $thisWeekAmount = 0;
        foreach ($thisWeeksHistory as $t) {
            $thisWeekAmount += $t->getAmount();
        }

        if ($thisWeekAmount >= static::FREE_TIER) {
            $transaction->setFee($transaction->getAmount() * static::STANDARD_RATE);
            return;
        }
        $thisWeekAmount += $transaction->getAmount();

        if ($thisWeekAmount >= static::FREE_TIER) {
            $transaction->setFee(($thisWeekAmount - static::FREE_TIER) * static::STANDARD_RATE);
            return;
        }

        $transaction->setFee(0.0);
    }

    private function getThisWeekCashOutHistory(Account $account, Transaction $transaction) : Collection
    {
        $weekCode = $transaction->getDate()->format('Y-W');
        return $account->getTransactionHistory()->filter(
            function (Transaction $t) use ($weekCode) {
                return $t->getTransactionType() === Transaction::TYPE_CASH_OUT
                    && $t->getDate()->format('Y-W') === $weekCode;
            }
        );
    }


}
