<?php


namespace App\Tests\AppBundle\Entity;
use App\Entity\Wallet;
use PHPUnit\Framework\TestCase;

class WalletTest extends TestCase
{
    public function testToString()
    {
        $wallet = new Wallet();
        $wallet->setName('Wallet 1');

        $this->assertEquals('Wallet 1', $wallet->__toString());
    }
}