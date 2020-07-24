<?php


namespace App\Service;

use App\Entity\Wallet;
use App\Service\Kraken\KrakenAPI;
use App\Service\Kraken\KrakenAPIException;
use PHPUnit\Runner\Exception;

class Kraken extends API implements Trading
{
    private $public_key;
    private $secret_key;
    private $api;

    public function historique(array $data){
        $buy = [];

        //Building Array of buy
        foreach($data as $tab)
            if($tab[3]=="b")//b pour buy (Achat), s pour sell (Vente)
                $buy[] = ['y'=>$tab[0], 'x'=>$tab[2]];

            // Sorting ascending date
        usort($buy, function($a, $b) {
            return $a['x'] <=> $b['x'];
        });

        for($i=0;$i<count($buy);$i++)
            $buy[$i]['x']=date('H:i:s',$buy[$i]['x']);

        return $buy;
    }

    /**
     * @param Wallet $wallet
     */
    public function authentication(Wallet $wallet):self
    {
        $this->public_key = $wallet->getClePublic();
        $this->secret_key = $wallet->getClePrive();
        try{
            $this->api = new KrakenAPI($this->public_key, $this->secret_key);
        }catch (Exception $e){
            throw $e;
        }
        return $this;
    }

    /**
     * @param string $base_curr
     * @param string $target_curr
     * @param \DateTime $date
     *
     * @return mixed
     */
    public function getHistory(string $base_curr, string $target_curr, \DateTime $date)
    {
        $result = [];
        try{
            $key = $base_curr."Z".$target_curr;
            $res = $this->api->QueryPublic('Trades', array('pair' => $key));

            if(isset($res["result"])){
                foreach ($res["result"] as $data){
                    $result = $this->historique($data);
                    break;
                }
            }

        }catch (KrakenAPIException $e){
            throw $e;
        }
        return $result;
    }

    /**
     * @param Wallet $wallet
     * @return mixed
     */
    public function getBalances(Wallet $wallet)
    {
        try{
            $res = $this->api->QueryPrivate('Balance');
        }catch (Exception $e){
            throw $e;
        }
        return $res['result'];
    }

    /**
     * @param Wallet $wallet
     * @return int
     */
    public function sell(Wallet $wallet){
        return 0;
    }

}