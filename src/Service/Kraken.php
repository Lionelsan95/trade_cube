<?php


namespace App\Service;

use App\Entity\Wallet;
use App\Service\Kraken\KrakenAPI;
use App\Service\Kraken\KrakenAPIException;
use PHPUnit\Runner\Exception;

class Kraken extends API implements Trading, Banking
{
    private $public_key;
    private $secret_key;
    private $api;
    private $wallet;

    public function historique(array $data){
        $buy = [];

        //Building Array of buy
        foreach($data['asks'] as $tab)
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
     * @return $this
     */
    public function authentication(Wallet $wallet):self
    {
        $this->public_key = $wallet->getApikey();
        $this->secret_key = $wallet->getSecretkey();
        $this->wallet = $wallet;
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
            $res = $this->api->QueryPublic('Depth', array('pair' => $key));
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
    public function solde()
    {
        try{
            $res = $this->api->QueryPrivate('TradeBalance');
            dump($res);
        }catch (Exception $e){
            throw $e;
        }
        return $res['result']??null;
    }

    /**
     * @param Wallet $wallet
     * @return int
     */
    public function sell(Wallet $wallet){
        return 0;
    }

    public function assets(){
        return $this->api->QueryPublic('Spread',['pair'=>'XXBTZEUR','interval'=>60]);
    }

    public function porteOuverte()
    {
        // TODO: Implement porteOuverte() method.
    }

    public function porteFerme()
    {
        // TODO: Implement porteFerme() method.
    }

    public function histroqueTrading()
    {
        // TODO: Implement histroqueTrading() method.
    }

    public function infosTrading()
    {
        // TODO: Implement infosTrading() method.
    }

    public function comptes()
    {
        // TODO: Implement comptes() method.
    }

    public function infosCompte()
    {
        // TODO: Implement infosCompte() method.
    }

    public function ohlc()
    {
        // TODO: Implement ohlc() method.
    }

    public function changes()
    {
        // TODO: Implement changes() method.
    }

    public function variationChange()
    {
        // TODO: Implement variationChange() method.
    }

    public function dernierTrading()
    {
        // TODO: Implement dernierTrading() method.
    }

    public function virement()
    {
        // TODO: Implement virement() method.
    }

    public function infosVirement()
    {
        // TODO: Implement infosVirement() method.
    }

    public function annulerVirement()
    {
        // TODO: Implement annulerVirement() method.
    }

    public function statutVirement()
    {
        // TODO: Implement statutVirement() method.
    }

    public function transaction()
    {
        // TODO: Implement transaction() method.
    }

    public function annulerTransaction()
    {
        // TODO: Implement annulerTransaction() method.
    }

    public function recupererDepot()
    {
        // TODO: Implement recupererDepot() method.
    }

    public function adresseDepot()
    {
        // TODO: Implement adresseDepot() method.
    }

    public function infosDepots()
    {
        // TODO: Implement infosDepots() method.
    }
}