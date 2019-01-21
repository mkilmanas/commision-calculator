<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\TableNode;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
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
        throw new PendingException();
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
