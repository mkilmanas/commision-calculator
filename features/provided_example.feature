Feature: Calculation of commissions according to all the rules
    In order to consider calculator correct
    As a developer
    I need to make sure it passes the provided test case

    Scenario: Test with provided data
        Given there are transactions
            | date       | account_id | account_type | transaction_type | amount     | currency |
            | 2014-12-31 | 4          | natural      | cash_out         | 1200.00    | EUR      |
            | 2015-01-01 | 4          | natural      | cash_out         | 1000.00    | EUR      |
            | 2016-01-05 | 4          | natural      | cash_out         | 1000.00    | EUR      |
            | 2016-01-05 | 1          | natural      | cash_in          | 200.00     | EUR      |
            | 2016-01-06 | 2          | legal        | cash_out         | 300.00     | EUR      |
            | 2016-01-06 | 1          | natural      | cash_out         | 30000      | JPY      |
            | 2016-01-07 | 1          | natural      | cash_out         | 1000.00    | EUR      |
            | 2016-01-07 | 1          | natural      | cash_out         | 100.00     | USD      |
            | 2016-01-10 | 1          | natural      | cash_out         | 100.00     | EUR      |
            | 2016-01-10 | 2          | legal        | cash_in          | 1000000.00 | EUR      |
            | 2016-01-10 | 3          | natural      | cash_out         | 1000.00    | EUR      |
            | 2016-02-15 | 1          | natural      | cash_out         | 300.00     | EUR      |
            | 2016-02-19 | 2          | natural      | cash_out         | 3000000    | JPY      |
        When I run commission calculation
        Then I should get the results
            | commission_fee |
            | 0.60           |
            | 3.00           |
            | 0.00           |
            | 0.06           |
            | 0.90           |
            | 0              |
            | 0.70           |
            | 0.30           |
            | 0.30           |
            | 5.00           |
            | 0.00           |
            | 0.00           |
            | 8612           |
