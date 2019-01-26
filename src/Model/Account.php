<?php
declare(strict_types=1);

namespace App\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Account
{
    const TYPE_NATURAL = 'natural';
    const TYPE_LEGAL = 'legal';

    const VALID_TYPES = [self::TYPE_NATURAL, self::TYPE_LEGAL];

    /**
     * @var int
     */
    private $id;
    /**
     * @var string
     */
    private $type;

    /**
     * @var ArrayCollection
     */
    private $transactionHistory;

    public function __construct(int $id, string $type)
    {
        if (!in_array($type, self::VALID_TYPES)) {
            throw new \InvalidArgumentException("Unknown account type '{$type}'");
        }

        $this->id = $id;
        $this->type = $type;
        $this->transactionHistory = new ArrayCollection();
    }

    public function getId() : int
    {
        return $this->id;
    }

    public function getType() : string
    {
        return $this->type;
    }

    public function getTransactionHistory() : Collection
    {
        return $this->transactionHistory;
    }

    public function addTransaction(Transaction $transaction)
    {
        $this->transactionHistory->set(
            $transaction->getDate()->format('c') . spl_object_hash($transaction),
            $transaction
        );
        $transactions = $this->transactionHistory->toArray();
        ksort($transactions);
        $this->transactionHistory = new ArrayCollection($transactions);
    }
}
