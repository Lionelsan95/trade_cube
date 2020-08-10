<?php


namespace App\Service;

Interface Trading
{
    public function historique(array $data);
    public function porteOuverte();
    public function porteFerme();
    public function histroqueTrading();
    public function infosTrading();
    public function comptes();
    public function infosCompte();
    public function ohlc();
    public function changes();
    public function variationChange();
    public function dernierTrading();
}