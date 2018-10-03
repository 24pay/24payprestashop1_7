<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of payment
 *
 * @author durcak
 */

include_once 'modules/pg24pay/pg24pay_order.php';
include_once 'modules/pg24pay/pg24pay_order_from_order.php';

class Pg24payPaymentModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        parent::initContent();
       
        $order = "";
        
        if (isset($_GET['from_order'])){
			
            $orderId = $_GET['from_order'];
            $order = $this->paymentFromOrder($orderId);
        }
        else{
			
            $order = $this->paymentFromCart();
        }
        
        if (Configuration::get('PAY24_DEBUG')==1){
            $action_url = "https://test.24-pay.eu/pay_gate/paygt";
        }
        else{
            $action_url = "https://admin.24-pay.eu/pay_gate/paygt";
        }
        
        $this->context->smarty->assign(array(
            'PAY24_ACTION' => $action_url,
            'PAY24_MID' => $order->mid,
            'PAY24_ESHOPID' => $order->eshopId,
            'PAY24_MSTXNID' => $order->msTxnId,
            'PAY24_AMOUNT' => $order->amount,
            'PAY24_CURRALPHACODE' => $order->currAlphaCode,
            'PAY24_LANGUAGE' => $order->language,
            'PAY24_CLIENTID' => $order->clientId,
            'PAY24_FIRSTNAME' => $order->firstName,
            'PAY24_FAMILYNAME' => $order->familyName,
            'PAY24_EMAIL' => $order->email,
            'PAY24_COUNTRY' => $order->country,
            'PAY24_NURL' => $order->nurl,
            'PAY24_RURL' => $order->rurl,
            'PAY24_TIMESTAMP' => $order->timestamp,
            'PAY24_SIGN' => $order->sign,
            'PAY24_MODULE_DEBUG' => Configuration::get('PAY24_MODULE_DEBUG'),
        ));
        
        $this->setTemplate('module:pg24pay/views/templates/front/payment.tpl');
    }
    
    private function paymentFromCart(){
		
		$tmp = new Cart(8);
		
        $order = new Pg24payOrder($this->context->cart);
        $order->signRequest();
        return $order;
    }
    
    private function paymentFromOrder($orderId){
        $order = new Pg24payOrderFromOrder($orderId);
        $order->signRequest();
        return $order;
    }

}