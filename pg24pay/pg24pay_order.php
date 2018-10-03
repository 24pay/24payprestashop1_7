<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of veintequatropago_order
 *
 * @author durcak
 */
include_once 'country_code_converter.php';
include_once 'pg24pay_sign.php';
include_once 'pg24pay_table.php';

class Pg24payOrder {
    public $mid;
    public $eshopId;
    public $msTxnId;
    public $amount;
    public $currAlphaCode;
    public $language;
    public $clientId;
    public $firstName;
    public $familyName;
    public $email;
    public $country;
    public $nurl;
    public $rurl;
    public $timestamp;
    public $sign;
    
    public $debug = true;
    
    private $signGenerator;
    private $dbTable;
    
    function __construct($cart){
        
        $this->signGenerator = new Pg24paySign();
        $this->dbTable = new Pg24payTable();
        
        $this->mid = $this->signGenerator->mid;
        $this->eshopId = $this->signGenerator->eshopid;
        
        
        $customer = new Customer($cart->id_customer);
        $address = new Address($cart->id_address_invoice);
        $country = new Country($address->id_country);
        $language = new Language($cart->id_lang);
        $currency = new Currency($cart->id_currency);
        
        //$this->msTxnId = $cart->id;
        $this->msTxnId = $this->dbTable->createId($cart->id);
        
        
        // TRUE - TOTAL WITH TAX, FALSE - TOTAL WITHOUT TAX
        //$this->amount = $cart->getOrderTotal(false).".00";
        
        $this->currAlphaCode = $currency->iso_code;
        $this->language = strtoupper($language->iso_code);
                
        $this->timestamp = date("Y-m-d H:i:s");
        $this->clientId = str_pad($cart->id_customer, 3, "0", STR_PAD_LEFT);
        $this->firstName = $customer->firstname;
        $this->familyName = $customer->lastname;
        $this->email = $customer->email;
        $this->country = $this->convertCountryCodeToIsoA3($country->iso_code);
                
        $this->amount = number_format($cart->getOrderTotal(true, Cart::BOTH), 2, '.', '');
        
        $this->rurl = Tools::getHttpHost(true) . __PS_BASE_URI__ . 'module/pg24pay/rurl';
        $this->nurl = Tools::getHttpHost(true) . __PS_BASE_URI__ . 'module/pg24pay/nurl';
    }
    
    private function convertCountryCodeToIsoA3($isoa2code) {
        return convert_country_code_from_isoa2_to_isoa3($isoa2code);
    }
    
    private function plainText(){
        return $this->mid.$this->amount.$this->currAlphaCode.$this->msTxnId.$this->firstName.$this->familyName.$this->timestamp;
    }
    
    public function signRequest(){
        $this->sign = $this->signGenerator->getSign($this->plainText());
    }
    
    public function preview(){
        
        echo "<pre>";
            echo "PlainText: ".$this->plainText()."\n\r";
            echo "Mid: ".$this->mid."\n\r";
            echo "EshopId: ".$this->eshopId."\n\r";
            echo "MsTxnId: ".$this->msTxnId."\n\r";
            echo "Amount: ".$this->amount."\n\r";
            echo "Currency: ".$this->currAlphaCode."\n\r";
            echo "Language: ".$this->language."\n\r";
            echo "ClientId: ".$this->clientId."\n\r";
            echo "FirstName: ".$this->firstName."\n\r";
            echo "LastName: ".$this->familyName."\n\r";
            echo "Email: ".$this->email."\n\r";
            echo "Country: ".$this->country."\n\r";
            echo "NURL: ".$this->nurl."\n\r";
            echo "RURL: ".$this->rurl."\n\r";
            echo "Timestamp: ".$this->timestamp."\n\r";
            echo "Sign: ".$this->sign."\n\r";
        echo "</pre>";
    }
}
