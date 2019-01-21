<?php

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
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
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
        throw new PendingException();
    }

    /**
     * @Then I should get the result
     * @Then I should get the results
     */
    public function iShouldGetTheResult(TableNode $table)
    {
        throw new PendingException();
    }
}
