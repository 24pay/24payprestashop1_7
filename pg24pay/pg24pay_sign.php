<?php

/**
 * Description of veintequatropago_sign
 *
 * @author 24-pay
 */

class Pg24paySign {
    public $debug;
    public $mid;
    public $eshopid;
    public $key;
    
    
    private $_mode = "CBC";
    private $_cipher;
    private $_paddingType;
    private $iv;
    
    function __construct() {
        $this->debug =  Configuration::get('PAY24_DEBUG');
        $this->mid = Configuration::get('PAY24_MID');
        $this->eshopid =  Configuration::get('PAY24_ESHOP_ID');
        $this->key =  Configuration::get('PAY24_KEY');
        
        /* SIGN GENERATOR SETTING */
        $this->_cipher = "";
        $this->_paddingType = 'PKCS7';
        $this->iv = $this->mid.strrev($this->mid);
    }
    
    public function regenerateIv(){
        $this->iv = $this->mid.strrev($this->mid);
    }
    
    public function getSign($plaintext){
		if ( PHP_VERSION_ID >= 50303 && extension_loaded( 'openssl' ) ) {
			$hash = $this->getData($plaintext);
			$crypted = openssl_encrypt( $hash, 'AES-256-CBC', $this->getHexKey(), 1, $this->iv );
			return strtoupper(bin2hex(substr($crypted, 0, 16)));
			
		}
		else{
			$this->_mode = MCRYPT_MODE_CBC;
			$this->_cipher = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', $this->_mode, '');
			
			if ($this->_paddingType == 'PKCS7'){
				$data = $this->AddPadding($this->getData($plaintext));
			}
	 
			mcrypt_generic_init($this->_cipher, $this->getHexKey(), $this->iv);
			$result = mcrypt_generic($this->_cipher, $data);
			mcrypt_generic_deinit($this->_cipher);
		  
			return strtoupper(substr(bin2hex($result),0,32));
		}
    }
    
    /* SIGN SUPPORT METHODS */
    private function getData($data){
        return sha1($data,true);
    }
    
    private function AddPadding($data){
      $block = mcrypt_get_block_size('des', $this->_mode);
      $pad   = $block - (strlen($data) % $block);
      $data .= str_repeat(chr($pad), $pad);
      return $data;
    }
    
    private function getHexKey(){
        return pack("H*" , $this->key );
    }
    
    
}
