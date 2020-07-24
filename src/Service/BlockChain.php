<?php


namespace App\Service;


use App\Entity\Wallet;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlockChain extends AbstractController
{
    public function getChain(Wallet $wallet){
        $bchain = null;
        switch ($wallet->getBlockchain()->getNom()){
            case "kraken.com":
                $bchain = $this->container->get('trade.kraken')->authentication($wallet);
                break;
            default:
                $bchain = $this->container->get('trade.kraken')->authentication($wallet);
                break;
        }
        return $bchain;
    }
}