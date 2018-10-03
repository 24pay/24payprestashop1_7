<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of veintequatropago_nurl
 *
 * @author durcak
 */
class Pg24payNurl {
    private $parsed;
    
    private $sign;
    public $msTxnId;
    private $pspTxnId;
    private $amount;
    private $currency;
    private $email;
    private $phone;
    private $street;
    private $zip;
    private $city;
    private $country;
    private $given;
    private $family;
    private $timestamp;
    public $result;
    private $pspCategory;
    private $creditCard;
    
    private $mid;
    private $signGenerator;
    
    function __construct($params) {        
        $transaction = new SimpleXMLElement($params); 
    
        $this->sign =  $transaction['sign'];
        $this->msTxnId =  $transaction->Transaction->Identification->MsTxnId;
        $this->pspTxnId =  $transaction->Transaction->Identification->PspTxnId;

        $this->amount =  $transaction->Transaction->Presentation->Amount;
        $this->currency =  $transaction->Transaction->Presentation->Currency;

        $this->email =  $transaction->Transaction->Customer->Contact->Email;
        $this->phone =  $transaction->Transaction->Customer->Contact->Phone;

        $this->street =  $transaction->Transaction->Customer->Address->Street;
        $this->zip =  $transaction->Transaction->Customer->Address->Zip;
        $this->city =  $transaction->Transaction->Customer->Address->City;
        $this->country =  $transaction->Transaction->Customer->Address->Country;

        $this->given =  $transaction->Transaction->Customer->Name->Given;
        $this->family =  $transaction->Transaction->Customer->Name->Family;

        $this->timestamp =  $transaction->Transaction->Processing->Timestamp;
        $this->result =  $transaction->Transaction->Processing->Result;
        $this->pspCategory =  $transaction->Transaction->Processing->PSPCategory;
        $this->creditCard =  $transaction->Transaction->Processing->CreditCard;
    }
    
    public function validateSign(){
        include_once 'pg24pay_sign.php';
        $this->signGenerator = new Pg24paySign();
        $this->mid = $this->signGenerator->mid;
        $signCandidat = $this->signGenerator->getSign($this->plainText());
        if ($signCandidat==$this->sign)
            return true;
        else
            return false;
    }
    
    private function plainText(){
        return $this->mid.$this->amount.$this->currency.$this->pspTxnId.$this->msTxnId.$this->timestamp.$this->result;
    }
    
    public function get24Id(){
        return $this->msTxnId;
    }
	
    public function getResult(){
        return $this->result;
    }
}
