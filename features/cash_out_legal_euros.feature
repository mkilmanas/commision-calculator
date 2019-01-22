Feature: Calculation of commissions for cash-out for legal customers in base currency
    In order to earn revenue
    As a bank
    I need to calculate cash transaction commission fees


    Scenario: Cash-out is charged at 0.3%
        Given there is a transaction
            | date       | account_id | account_type | transaction_type | amount  | currency |
            | 2019-01-21 | 1          | legal        | cash_out         | 1000.00 | EUR      |
        When I run commission calculation
        Then I should get the result
            | commission_fee |
            | 3.00           |

    Scenario: Transaction fee is always at least 0.50 EUR
        Given there is a transaction
            | date       | account_id | account_type | transaction_type | amount   | currency |
            | 2019-01-21 | 1          | legal        | cash_out         | 100.00   | EUR      |
        When I run commission calculation
        Then I should get the result
            | commission_fee |
            | 0.50           |
