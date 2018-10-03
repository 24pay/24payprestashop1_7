<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of veintequatropago
 *
 * @author durcak
 */

class Pg24payInstall{
    /**
     * Create PayPal tables
     */
    private function createTables()
    {
        /* Set database */
        $query = '
		CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'24pay_orderer` (
			`id_24` int(11) unsigned NOT NULL AUTO_INCREMENT,
                        `cart_id` int(11) unsigned NOT NULL,
                        `order_id` int(10) unsigned NOT NULL,
			`nurl` text DEFAULT NULL,
                        `from_order` int(1) unsigned NOT NULL DEFAULT \'0\',
			`created` datetime DEFAULT NULL,
                        `confirmed` datetime DEFAULT NULL,
                        `status` varchar(255) DEFAULT NULL,
			PRIMARY KEY (`id_24`)
		) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8';
        
        if (!Db::getInstance()->Execute($query))
            return false;
        else
            return true;
    }
    
    private function dropTables(){
        if (!Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'24pay_orderer`')) {
            return false;
        }
    }
    
    
    
    private function insertParams(){
        Configuration::updateValue('PAY24_DEBUG', '1');
        Configuration::updateValue('PAY24_MID', 'demoOMED');
        Configuration::updateValue('PAY24_KEY', '1234567812345678123456781234567812345678123456781234567812345678');
        Configuration::updateValue('PAY24_ESHOP_ID', '11111111');
        Configuration::updateValue('PAY24_MODULE_DEBUG', '0');
        Configuration::updateValue('PAY24_REPAY', '1');
        Configuration::updateValue('PAY24_LOG', '1');
    }
    
    private function deleteParams(){
        Configuration::deleteByName('PAY24_DEBUG');
        Configuration::deleteByName('PAY24_MID');
        Configuration::deleteByName('PAY24_KEY');
        Configuration::deleteByName('PAY24_ESHOP_ID');
        Configuration::deleteByName('PAY24_MODULE_DEBUG');
        Configuration::deleteByName('PAY24_REPAY');
        Configuration::deleteByName('PAY24_LOG');
    }
    
    public function install(){
        if (!$this->createTables()){
            die('Could not install table');
        }
        $this->insertParams();
        $this->insertOrderState();
    }
    
    public function uninstall(){
        $this->dropTables();
        $this->deleteParams();
        $this->deleteOrderState();
    }
    
    public function deleteOrderState(){
        $ok_id = Configuration::get('PAY24_OK');
        $fail_id = Configuration::get('PAY24_FAIL');
        $pending_id = Configuration::get('PAY24_PENDING');
        
        $state_ok = new OrderState($ok_id);
        $state_ok->delete();
        
        $state_fail = new OrderState($fail_id);
        $state_fail->delete();
        
        $state_pending = new OrderState($pending_id);
        $state_pending->delete();
        
        Configuration::deleteByName('PAY24_OK');
        Configuration::deleteByName('PAY24_FAIL');
        Configuration::deleteByName('PAY24_PENDING');
    }
    
    public function insertOrderState(){
        // OK
        if (!Configuration::get('PAY24_OK')) {
            $OK_state = new OrderState();
            $OK_state->name = array();

            foreach (Language::getLanguages() as $language) {
                if (Tools::strtolower($language['iso_code']) == 'sk') {
                    $OK_state->name[$language['id_lang']] = '24Pay - Platba OK';
                } else {
                    $OK_state->name[$language['id_lang']] = '24Pay - Payment OK';
                }
            }
            $OK_state->module_name = "pg24pay";
            $OK_state->send_email = true;
            $OK_state->color = '#00CE52';
            $OK_state->hidden = false;
            $OK_state->delivery = false;
            $OK_state->logable = true;
            $OK_state->invoice = true;

            if ($OK_state->add()) {
                // ICON
                $twentyfourpayIcon = dirname( __FILE__ ) . '/logo.png';
                $newStateIcon = dirname( __FILE__ ) . '/../../img/os/' . (int) $OK_state->id . '.gif';
                copy( $twentyfourpayIcon, $newStateIcon );

                Configuration::updateValue('PAY24_OK',$OK_state->id);
            }
        }



        // FAIL
        if (!Configuration::get('PAY24_FAIL')) {
            $FAIL_state = new OrderState();
            $FAIL_state->name = array();

            foreach (Language::getLanguages() as $language) {
                if (Tools::strtolower($language['iso_code']) == 'sk') {
                    $FAIL_state->name[$language['id_lang']] = '24Pay - Platba NEÚSPEŠNÁ';
                } else {
                    $FAIL_state->name[$language['id_lang']] = '24Pay - Payment FAIL';
                }
            }
            $FAIL_state->module_name = "pg24pay";
            $FAIL_state->send_email = false;
            $FAIL_state->color = '#FF0000';
            $FAIL_state->hidden = false;
            $FAIL_state->delivery = false;
            $FAIL_state->logable = true;
            $FAIL_state->invoice = false;

            if ($FAIL_state->add()) {
                // ICON
                $twentyfourpayIcon = dirname( __FILE__ ) . '/logo.png';
                $newStateIcon = dirname( __FILE__ ) . '/../../img/os/' . (int) $FAIL_state->id . '.gif';
                copy( $twentyfourpayIcon, $newStateIcon );

                Configuration::updateValue('PAY24_FAIL',$FAIL_state->id);
            }
        }



        // PENDING
        if (!Configuration::get('PAY24_PENDING')) {
            $PENDING_state = new OrderState();
            $PENDING_state->name = array();

            foreach (Language::getLanguages() as $language) {
                if (Tools::strtolower($language['iso_code']) == 'sk') {
                    $PENDING_state->name[$language['id_lang']] = '24Pay - Platba ČAKAJÚCA';
                } else {
                    $PENDING_state->name[$language['id_lang']] = '24Pay - Payment PENDING';
                }
            }
            $PENDING_state->module_name = "pg24pay";
            $PENDING_state->send_email = false;
            $PENDING_state->color = '#FCC79F';
            $PENDING_state->hidden = false;
            $PENDING_state->delivery = false;
            $PENDING_state->logable = true;
            $PENDING_state->invoice = false;

            if ($PENDING_state->add()) {
                // ICON
                $twentyfourpayIcon = dirname( __FILE__ ) . '/logo.png';
                $newStateIcon = dirname( __FILE__ ) . '/../../img/os/' . (int) $PENDING_state->id . '.gif';
                copy( $twentyfourpayIcon, $newStateIcon );

                Configuration::updateValue('PAY24_PENDING',$PENDING_state->id);
            }
        }
    }
}