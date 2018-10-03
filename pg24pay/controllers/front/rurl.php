<?php

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


class Pg24payRurlModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        parent::initContent();
        
        include_once 'modules/pg24pay/pg24pay_table.php';            
        $dbTable = new Pg24payTable();
        $orderId = null;
        $orderRef = null;
        
        if (isset($_GET['MsTxnId'])){
            $orderId = $dbTable->getOrderId($_GET['MsTxnId']);
            $cartId = $dbTable->getCartId($_GET['MsTxnId']);
			
            if ($orderId!=null){
                $order = new Order($orderId);
                $orderRef = $order->reference;
            }   
        }
        
        $this->context->smarty->assign(array(
            'PAY24_TEMP' => $_GET,
            'PAY24_REPAY' => Configuration::get('PAY24_REPAY'),
            'PAY24_ORDER' => $orderId,
            'PAY24_ORDER_REF' => $orderRef,

        ));
        
        $this->setTemplate('module:pg24pay/views/templates/front/rurl.tpl');
	}	
	

}