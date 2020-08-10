<?php


namespace App\Service;


interface Banking
{
    public function solde();
    public function virement();
    public function infosVirement();
    public function annulerVirement();
    public function statutVirement();
    public function transaction();
    public function annulerTransaction();
    public function recupererDepot();
    public function adresseDepot();
    public function infosDepots();
}