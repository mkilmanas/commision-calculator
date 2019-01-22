<?php

use App\CommissionCalculator;
use App\Model\Currency;
use App\Model\Transaction;
use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\TableNode;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    private $transactions = [];
    /**
     * @var CommissionCalculator
     */
    private $calculator;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct(CommissionCalculator $calculator)
    {
        $this->calculator = $calculator;
    }

    /**
     * @Given there is a transaction
     * @Given there are transactions
     */
    public function thereIsATransaction(TableNode $table)
    {
        foreach ($table->getColumnsHash() as $row) {
            $this->transactions[] = new Transaction(
                new \DateTimeImmutable($row['date']),
                (int)$row['account_id'],
                $row['account_type'],
                $row['transaction_type'],
                (float)$row['amount'],
                call_user_func([Currency::class, $row['currency']])
            );
        }
    }

    /**
     * @When I run commission calculation
     */
    public function iRunCommissionCalculation()
    {
        $this->calculator->calculateAllFees($this->transactions);
    }

    /**
     * @Then I should get the result
     * @Then I should get the results
     */
    public function iShouldGetTheResult(TableNode $table)
    {
        if (count($table->getColumnsHash()) !== count($this->transactions)) {
            throw new \RuntimeException("The transaction count doesn't match the expected result count");
        }

        foreach ($table->getColumnsHash() as $i => $row)
        {
            $expected = floatval($row['commission_fee']);
            $actual = $this->transactions[$i]->getFee();
            if (abs($expected - $actual) > 0.0001) {
                throw new \Exception("Result mismatch: expected '{$row['commission_fee']}' but found {$actual}");
            }
        }
    }
}
