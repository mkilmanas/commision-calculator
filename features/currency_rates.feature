Feature: Conversion of limits to different currencies
    In order to calculate the fees fairly
    As a bank
    I need to respect imposed limits regardless of transaction currency


    Scenario: 5.00 EUR cash-in cap is respected in USD (EUR:USD - 1:1.1497)
        Given there is a transaction
            | date       | account_id | account_type | transaction_type | amount   | currency |
            | 2019-01-21 | 1          | natural      | cash_in          | 50000.00 | USD      |
        When I run commission calculation
        Then I should get the result
            | commission_fee |
            | 5.75           |

    Scenario: 5.00 EUR cash-in cap is respected in JPY (EUR:JPY - 1:129.53)
        Given there is a transaction
            | date       | account_id | account_type | transaction_type | amount     | currency |
            | 2019-01-21 | 1          | natural      | cash_in          | 5000000.00 | JPY      |
        When I run commission calculation
        Then I should get the result
            | commission_fee |
            | 648            |

    Scenario: First 1000 EUR free cash-out per week for natural account is respected in USD (EUR:USD - 1:1.1497)
        Given there are transactions
            | date       | account_id | account_type | transaction_type | amount | currency |
            | 2019-01-15 | 1          | natural      | cash_out         | 1000   | USD      |
            | 2019-01-16 | 1          | natural      | cash_out         | 1000   | USD      |
        When I run commission calculation
        Then I should get the results
            | commission_fee |
            | 0.00           |
            | 2.56           |

    Scenario: First 1000 EUR free cash-out per week for natural account is respected in JPY (EUR:JPY - 1:129.53)
        Given there are transactions
            | date       | account_id | account_type | transaction_type | amount  | currency |
            | 2019-01-15 | 1          | natural      | cash_out         | 100000  | JPY      |
            | 2019-01-16 | 1          | natural      | cash_out         | 100000  | JPY      |
        When I run commission calculation
        Then I should get the results
            | commission_fee |
            | 0              |
            | 212            |


    Scenario: 0.50 EUR minimum cash-out fee for legal accounts is respected in USD (EUR:USD - 1:1.1497)
        Given there is a transaction
            | date       | account_id | account_type | transaction_type | amount | currency |
            | 2019-01-21 | 1          | legal        | cash_out         | 10.00  | USD      |
        When I run commission calculation
        Then I should get the result
            | commission_fee |
            | 0.58           |

    Scenario: 0.50 EUR minimum cash-out fee for legal accounts is respected in JPY (EUR:JPY - 1:129.53)
        Given there is a transaction
            | date       | account_id | account_type | transaction_type | amount | currency |
            | 2019-01-21 | 1          | legal        | cash_out         | 100    | JPY      |
        When I run commission calculation
        Then I should get the result
            | commission_fee |
            | 65             |

