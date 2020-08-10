<?php


namespace App\Service;

use App\Entity\Wallet;
use Symfony\Component\Process\Process;

class CronTab
{
    public function create(Wallet $wallet)
    {
        $cron_src = "/var/spool/cron/crontabs/lionel";

        $symfony_cmd = '*/2 * * * * php '.__DIR__.'/../../bin/console app:get-spread '.$wallet->getId().' X'.$wallet->getCurrency()->getSymbol().' ZEUR';
        $cmd = "echo '$symfony_cmd' >> $cron_src";

        $p = new Process($cmd);
        $p->run();
    }

    public function remove(Wallet $wallet)
    {
        $cron_src = "/var/spool/cron/crontabs/lionel";
        $cmd = "sed '/".$wallet->getId()."/d' $cron_src >> $cron_src";

        $p = new Process($cmd);
        $p->run();
    }
}