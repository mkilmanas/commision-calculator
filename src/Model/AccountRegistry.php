<?php

namespace App\Model;

class AccountRegistry
{
    private $registry = [];

    public function find(int $id) : ?Account
    {
        return $this->registry[$id] ?? null;
    }

    public function add(Account $account)
    {
        $this->registry[$account->getId()] = $account;
    }
}
