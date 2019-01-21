Feature: Rounding of commission fees
    In order to earn revenue
    As a bank
    I need to round the commission fees up to the nearest currency unit


    Scenario: Commission fee is always rounded up
        Given there is a transaction
            | date       | account_id | account_type | transaction_type | amount | currency |
            | 2019-01-21 | 1          | natural      | cash_in          | 250.00 | EUR      |
        When I run commission calculation
        Then I should get the result
            | commission_fee |
            | 0.08           |

    Scenario: Amounts in JPY are rounded up to a whole number
        Given there is a transaction
            | date       | account_id | account_type | transaction_type | amount | currency |
            | 2019-01-21 | 1          | natural      | cash_in          | 7500   | JPY      |
        When I run commission calculation
        Then I should get the result
            | commission_fee |
            | 3              |
