Feature: Calculation of commissions for cash-in in base currency
    In order to earn revenue
    As a bank
    I need to calculate cash transaction commission fees


    Scenario: Charge 0.03% for cash-in transaction
        Given there is a transaction
            | date       | account_id | account_type | transaction_type | amount | currency |
            | 2019-01-21 | 1          | natural      | cash_in          | 100.00 | EUR      |
        When I run commission calculation
        Then I should get the result
            | commission_fee |
            | 0.03           |

    Scenario: Cash-in fee is capped at 5.00 EUR
      Given there is a transaction
        | date       | account_id | account_type | transaction_type | amount   | currency |
        | 2019-01-21 | 1          | natural      | cash_in          | 20000.00 | EUR      |
      When I run commission calculation
      Then I should get the result
        | commission_fee |
        | 5.00           |
