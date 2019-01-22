<?php

namespace App;

use App\Model\Account;
use App\Model\AccountRegistry;
use App\Model\Transaction;
use App\Strategy\CashInFeeStrategy;
use App\Strategy\FeeStrategyInterface;
use App\Strategy\LegalCashOutFeeStrategy;

class CommissionCalculator
{
    /**
     * @var AccountRegistry
     */
    private $registry;

    /**
     * @var array
     */
    private $strategies;

    public function __construct(AccountRegistry $registry, array $strategies = [])
    {
        $this->registry = $registry;

        if (empty($strategies)) {
            $this->strategies = [
                new CashInFeeStrategy(),
                new LegalCashOutFeeStrategy(),
            ];
        } else {
            foreach ($strategies as $strategy) {
                if (!$strategy instanceof FeeStrategyInterface) {
                    throw new \InvalidArgumentException("Excpected an instance of FeeStrategyInterface, but got " . get_class($strategy));
                }
            }
            $this->strategies = $strategies;
        }
    }

    public function calculateFee(Transaction $transaction)
    {
        $account = $this->registry->find($transaction->getAccountId());
        if (!$account) {
            $account = new Account($transaction->getAccountId(), $transaction->getAccountType());
            $this->registry->add($account);
        }

        foreach ($this->strategies as $strategy) {
            /** @var FeeStrategyInterface $strategy */
            if ($strategy->isApplicable($account, $transaction)) {
                $strategy->calculateFee($account, $transaction);
                break;
            }
        }

        $account->addTransaction($transaction);
    }

    public function calculateAllFees(array $transactions)
    {
        foreach ($transactions as $transaction) {
            $this->calculateFee($transaction);
        }
    }
}
