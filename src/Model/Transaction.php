<?php
declare(strict_types=1);

namespace App\Model;

class Transaction
{
    const TYPE_CASH_IN = 'cash_in';
    const TYPE_CASH_OUT = 'cash_out';

    /**
     * @var \DateTimeInterface
     */
    private $date;
    /**
     * @var int
     */
    private $accountId;
    /**
     * @var string
     */
    private $accountType;
    /**
     * @var string
     */
    private $transactionType;
    /**
     * @var float
     */
    private $amount;
    /**
     * @var Currency
     */
    private $currency;
    /**
     * @var float
     */
    private $fee = 0.0;

    public function __construct(
        \DateTimeInterface $date,
        int $accountId,
        string $accountType,
        string $transactionType,
        float $amount,
        Currency $currency
    ) {
        $this->date = $date;
        $this->accountId = $accountId;
        $this->accountType = $accountType;
        $this->transactionType = $transactionType;
        $this->amount = $amount;
        $this->currency = $currency;
    }

    public function getDate() : \DateTimeInterface
    {
        return $this->date;
    }

    public function getAccountId() : int
    {
        return $this->accountId;
    }

    public function getAccountType() : string
    {
        return $this->accountType;
    }

    public function getTransactionType() : string
    {
        return $this->transactionType;
    }

    public function getAmount() : float
    {
        return $this->amount;
    }

    public function getCurrency() : Currency
    {
        return $this->currency;
    }

    public function getFee() : float
    {
        return $this->fee;
    }

    public function setFee(float $feeAmount)
    {
        $this->fee = $feeAmount;
    }
}
