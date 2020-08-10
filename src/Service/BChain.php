<?php


namespace App\Service;


use App\Entity\Wallet;
use Blockchain\Blockchain;

class BChain
{
    private $kraken;

    public function __construct(Kraken $kraken)
    {
        $this->kraken = $kraken;
    }

    /**
     * @param Wallet $wallet
     * @return mixed
     * @throws \Exception
     */
    public function getChain(Wallet $wallet){
        $bchain = null;
        switch ($wallet->getBlockchain()->getName()){
            case "kraken.com":
                try{
                    $bchain = $this->kraken->authentication($wallet);
                }catch(\Exception $e){
                    throw $e;
                }
                break;
            case 'blockchain.com':
                try{
                    $url="";
                    $new = new Blockchain($wallet->getBlockchain()->getCleApi());
                    $new->setServiceUrl($url);
                }catch (\Exception $e){
                    throw $e;
                }
            default:
                $bchain = null;//$this->kraken->authentication($wallet);
                break;
        }
        return $bchain;
    }
}