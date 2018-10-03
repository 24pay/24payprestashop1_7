<?php
include_once _PS_MODULE_DIR_.$this->name.'/pg24pay_sign.php';
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of veintequatropago_request
 *
 * @author durcak
 */
class Pg24payRequest {
    private $signGenerator;
    function __construct() {
        $this->signGenerator = new Pg24paySign();
    }
    
    public function getServiceDomain(){
        if ($this->signGenerator->debug==1)
            return "https://test.24-pay.eu";
        else
            return "https://admin.24-pay.eu";
    }
    
    public function getCheckUrl() {
        return $this->getServiceDomain() . '/pay_gate/check';
    }
    
    public function getInstallUrl() {
        return $this->getServiceDomain() . '/pay_gate/install';
    }
        
    public function checkSignGeneration() {
        $checksum = $this->signGenerator->getSign('Check sign text for MID ' . $this->signGenerator->mid);
        $status = $this->makePostRequest($this->getCheckUrl(), array(
                'CHECKSUM' => $checksum ,
                'MID' => $this->signGenerator->mid
        ));
        return $status;
    }
    
    public function checkAvailableGateways() {
        $availableGateways = $this->makePostRequest($this->getInstallUrl(), array(
                'ESHOP_ID' => $this->signGenerator->eshopid,
                'MID' => $this->signGenerator->mid
        ));
        return $availableGateways;
    }
    
    public function makePostRequest($url, $data) {
        $options = array(
                'http' => array(
                        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                        'method'  => 'POST',
                        'content' => http_build_query($data)
                )
        );
        // staging gateway does not have verified certificate
        if ($this->signGenerator->debug==1) {
                $options['ssl'] = array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                );
        }
        $context  = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        return $result;
    }
}
