<?php

namespace App;

use App\Helper\CurrencyRounding;
use App\Model\Account;
use App\Model\AccountRegistry;
use App\Model\Transaction;
use App\Strategy\CashInFeeStrategy;
use App\Strategy\FeeStrategyInterface;
use App\Strategy\LegalCashOutFeeStrategy;
use App\Strategy\NaturalCashOutFeeStrategy;

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

    /**
     * @var CurrencyRounding
     */
    private $rounding;

    public function __construct(
        AccountRegistry $registry,
        CurrencyRounding $rounding,
        Strategy\FeeStrategyInterface ...$strategies
    ) {
        $this->registry = $registry;

        if (empty($strategies)) {
            throw new \InvalidArgumentException("Please provide some Fee Strategies for " . __CLASS__);
        }

        $this->strategies = $strategies;
        $this->rounding = $rounding;
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
                $transaction->setFee(
                    $this->rounding->roundUp(
                        $transaction->getFee(),
                        $transaction->getCurrency()
                    )
                );
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
