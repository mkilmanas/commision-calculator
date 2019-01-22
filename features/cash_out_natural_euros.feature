Feature: Calculation of commissions for cash-out for natural customers in base currency
    In order to earn revenue
    As a bank
    I need to calculate cash transaction commission fees


    Scenario: Cash-out of first 1000 EUR per calendar week is free
        Given there is a transaction
            | date       | account_id | account_type | transaction_type | amount | currency |
            | 2019-01-21 | 1          | natural      | cash_out         | 100.00 | EUR      |
        When I run commission calculation
        Then I should get the result
            | commission_fee |
            | 0.00           |

    Scenario: Cash-out over 1000 EUR per calendar week is charged at 0.3% of excess amount
        Given there is a transaction
            | date       | account_id | account_type | transaction_type | amount   | currency |
            | 2019-01-21 | 1          | natural      | cash_out         | 3000.00  | EUR      |
        When I run commission calculation
        Then I should get the result
            | commission_fee |
            | 6.00           |

    Scenario: Cash-out of first 1000 EUR per calendar week for free applies for up to 3 transactions
        Given there are transactions
            | date       | account_id | account_type | transaction_type | amount | currency |
            | 2019-01-15 | 1          | natural      | cash_out         | 100.00 | EUR      |
            | 2019-01-16 | 1          | natural      | cash_out         | 100.00 | EUR      |
            | 2019-01-17 | 1          | natural      | cash_out         | 100.00 | EUR      |
        When I run commission calculation
        Then I should get the results
            | commission_fee |
            | 0.00           |
            | 0.00           |
            | 0.00           |

    Scenario: Cash-out of 4th and subsequent transactions in a week are charged 0.3% of full amount
        Given there are transactions
            | date       | account_id | account_type | transaction_type | amount | currency |
            | 2019-01-15 | 1          | natural      | cash_out         | 100.00 | EUR      |
            | 2019-01-16 | 1          | natural      | cash_out         | 100.00 | EUR      |
            | 2019-01-17 | 1          | natural      | cash_out         | 100.00 | EUR      |
            | 2019-01-18 | 1          | natural      | cash_out         | 100.00 | EUR      |
            | 2019-01-19 | 1          | natural      | cash_out         | 100.00 | EUR      |
        When I run commission calculation
        Then I should get the results
            | commission_fee |
            | 0.00           |
            | 0.00           |
            | 0.00           |
            | 0.30           |
            | 0.30           |


    Scenario: Cash-out transactions are counted for each account separately
        Given there are transactions
            | date       | account_id | account_type | transaction_type | amount | currency |
            | 2019-01-15 | 1          | natural      | cash_out         | 100.00 | EUR      |
            | 2019-01-15 | 2          | natural      | cash_out         | 200.00 | EUR      |
            | 2019-01-16 | 1          | natural      | cash_out         | 100.00 | EUR      |
            | 2019-01-16 | 2          | natural      | cash_out         | 200.00 | EUR      |
            | 2019-01-17 | 1          | natural      | cash_out         | 100.00 | EUR      |
            | 2019-01-17 | 2          | natural      | cash_out         | 200.00 | EUR      |
            | 2019-01-18 | 1          | natural      | cash_out         | 100.00 | EUR      |
            | 2019-01-18 | 2          | natural      | cash_out         | 200.00 | EUR      |

        When I run commission calculation
        Then I should get the results
            | commission_fee |
            | 0.00           |
            | 0.00           |
            | 0.00           |
            | 0.00           |
            | 0.00           |
            | 0.00           |
            | 0.30           |
            | 0.60           |


    Scenario: 1000 EUR limit is calculated for each calendar week separately
        Given there are transactions
            | date       | account_id | account_type | transaction_type | amount | currency |
            | 2019-01-15 | 1          | natural      | cash_out         | 350.00 | EUR      |
            | 2019-01-16 | 1          | natural      | cash_out         | 350.00 | EUR      |
            | 2019-01-17 | 1          | natural      | cash_out         | 350.00 | EUR      |
            | 2019-01-18 | 1          | natural      | cash_out         | 300.00 | EUR      |
            | 2019-01-21 | 1          | natural      | cash_out         | 300.00 | EUR      |
        When I run commission calculation
        Then I should get the results
            | commission_fee |
            | 0.00           |
            | 0.00           |
            | 0.15           |
            | 0.90           |
            | 0.00           |

    Scenario: 3 transaction limit is calculated for each calendar week separately
        Given there are transactions
            | date       | account_id | account_type | transaction_type | amount | currency |
            | 2019-01-15 | 1          | natural      | cash_out         | 100.00 | EUR      |
            | 2019-01-16 | 1          | natural      | cash_out         | 100.00 | EUR      |
            | 2019-01-17 | 1          | natural      | cash_out         | 100.00 | EUR      |
            | 2019-01-18 | 1          | natural      | cash_out         | 100.00 | EUR      |
            | 2019-01-21 | 1          | natural      | cash_out         | 100.00 | EUR      |
        When I run commission calculation
        Then I should get the results
            | commission_fee |
            | 0.00           |
            | 0.00           |
            | 0.00           |
            | 0.30           |
            | 0.00           |
