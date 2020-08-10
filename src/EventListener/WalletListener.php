<?php


namespace App\EventListener;

use App\Entity\Wallet;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Process\Process;

class WalletListener
{
    public function postUpdate(Wallet $wallet, LifecycleEventArgs $event){
        if($wallet->getTrading()==1){
            //Activate trading cron

            $cron_src = "/var/spool/cron/crontabs/lionel";

            /*$symfony_cmd = 'php '.__DIR__.'/../../bin/console app:get-spread '.$wallet->getSignature().' X'.$wallet->getCryptomonnaie()->getSymbol().' ZEUR';
            $cmd = "echo '$symfony_cmd' >> $cron_src";

            $p = new Process($cmd);
            $p->run();*/

        }else{
            //Remove trading cron if it exists
        }
    }

}