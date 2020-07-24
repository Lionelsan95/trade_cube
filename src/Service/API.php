<?php

namespace App\Service;

use App\Entity\Wallet;

abstract class API
{
    protected $key;
    protected $link;

    public abstract function authentication(Wallet $wallet);

    public abstract function getHistory(string $base_curr, string $target_curr, \DateTime $date);

    public abstract function getBalances(Wallet $wallet);

    public abstract function sell(Wallet $wallet);
}