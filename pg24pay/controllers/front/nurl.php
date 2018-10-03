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



class Pg24payNurlModuleFrontController extends ModuleFrontController
{
    
    public function initContent()
    {
        
        parent::initContent();
      
        if (isset($_POST['params'])){
            
            if (Configuration::get('PAY24_LOG')==1){
                $fileName = "modules/pg24pay/logs/LOG".date("Y-m-d_H.i.s").".log";
                $myfile = fopen("$fileName", "w");
                $income = "PAYMENT FROM CART \n\r".print_r($_POST,true);
                fwrite($myfile, $income);
                fclose($myfile);
            }
            
            include_once 'modules/pg24pay/pg24pay_nurl.php';
            $nurl = new Pg24payNurl($_POST['params']);
            $id_24 = $nurl->get24Id();
            
            include_once 'modules/pg24pay/pg24pay_table.php';            
            $dbTable = new Pg24payTable();
            $cartId = $dbTable->getCartId($id_24);
            $orderId = $dbTable->getOrderId($id_24);
            $orderStatus = $dbTable->getStatus($id_24);
            
            $dbTable->setOrderNurl($id_24, $_POST['params']);
            
            if ($nurl->validateSign()){
				
				if (($orderId!=0)&&($orderStatus=="PENDING")){
					$fileName = "modules/pg24pay/logs/PENDING-PROC-LOG".date("Y-m-d_H.i.s").".log";
					$myfile = fopen("$fileName", "w");
					$income = "PAYMENT FROM CART ".$cartId."\n\r".print_r($_POST,true);
					$income .= "\n\r\n\r\n\r INFORMATIONS ORDER ID: ".$orderId." STATUS: ".$orderStatus;
					fwrite($myfile, $income);
					fclose($myfile);
					
					
					if ($nurl->result=="OK"){
						
						$dbTable->confirmOrder($id_24, $orderId, "OK");
						
						$orderObj = new Order($orderId);
						$orderObj->setInvoice(true);
						
						$history = new OrderHistory();
						$history->id_order = (int)$orderId;
						$history->changeIdOrderState(Configuration::get('PAY24_OK'), (int)($orderId));
						$history->addWithemail(true);						
					}
					else if($nurl->result=="FAIL"){
						$dbTable->confirmOrder($id_24, $orderId, "FAIL");
						
						$history = new OrderHistory();
						$history->id_order = (int)$orderId;
						$history->changeIdOrderState(Configuration::get('PAY24_FAIL'), $orderId);
					}
				}
				else if(($orderId!=0)&&($orderStatus!="PENDING")){
					header('HTTP/1.1 500 ORDER ALREDY CONFIRMED');
					exit();
				}
				else{
					$cart = new Cart($cartId);
										
					if ($nurl->result=="OK"){
							
							$order_id = $this->confirmOrder($cartId,$nurl->result);
							$dbTable->confirmOrder($id_24, $order_id, "OK");
							
							echo "Result: ".$nurl->result;
							echo "ORDER ID: ".$order_id;
							echo "24-pay ID: ".$id_24;
							
							die("WHY?");
					}
					else if ($nurl->result=="PENDING"){
							$order_id = $this->confirmOrder($cartId,$nurl->result);
							$dbTable->confirmOrder($id_24, $order_id, "PENDING");
					}
					else if ($nurl->result=="FAIL"){
						
						if (Configuration::get('PAY24_REPAY')==1){
							$order_id = $this->confirmOrder($cartId,$nurl->result);
							$dbTable->confirmOrder($id_24, $order_id, "FAIL");
						}
						else{
							$dbTable->confirmOrder($id_24, -1, "FAIL");
							
						}
					}
				}
            }
            else{
                echo "BAD NURL SIGN!";
            }
        }
        else{
            echo "NO POST!";
        }
		
		$this->setTemplate('module:pg24pay/views/templates/front/empty.tpl');
    }
	
	private function confirmOrder($cartId, $result){
		
		
		$cart = new Cart($cartId);
		if ($cart->id_customer == 0 || $cart->id_address_delivery == 0 || $cart->id_address_invoice == 0 || !$this->module->active)
			Tools::redirect('index.php?controller=order&step=1');

		// Check that this payment option is still available in case the customer changed his address just before the end of the checkout process
		$authorized = false;
		foreach (Module::getPaymentModules() as $module)
			if ($module['name'] == 'pg24pay')
			{
				$authorized = true;
				break;
			}
			
		/*
		if (!$authorized)
			die($this->module->getTranslator()->trans('This payment method is not available.', array(), 'Modules.Wirepayment.Shop'));
		*/
		$customer = new Customer($cart->id_customer);
		if (!Validate::isLoadedObject($customer))
			Tools::redirect('index.php?controller=order&step=1');

		$currency = $this->context->currency;
		$total = (float)$cart->getOrderTotal(true, Cart::BOTH);
		$mailVars = array(
			'{bankwire_owner}' => Configuration::get('BANK_WIRE_OWNER'),
			'{bankwire_details}' => nl2br(Configuration::get('BANK_WIRE_DETAILS')),
			'{bankwire_address}' => nl2br(Configuration::get('BANK_WIRE_ADDRESS'))
		);
		
		/*
		$cart = new Cart($cartId);
		  		// Check that this payment option is still available in case the customer changed his address just before the end of the checkout process
		$authorized = false;
		foreach (Module::getPaymentModules() as $module)
                if ($module['name'] == 'pg24pay')
                {
                        $authorized = true;
                        break;
                }
		
                
		
		
		$customer = new Customer($cart->id_customer);
		if (!Validate::isLoadedObject($customer))
                    Tools::redirect('index.php?controller=order&step=1');
                
                
		
		$currency = new Currency($cart->id_currency);
		$total = (float)$cart->getOrderTotal(true, Cart::BOTH);
		$mailVars = array(
                    '{bankwire_owner}' => Configuration::get('BANK_WIRE_OWNER'),
                    '{bankwire_details}' => nl2br(Configuration::get('BANK_WIRE_DETAILS')),
                    '{bankwire_address}' => nl2br(Configuration::get('BANK_WIRE_ADDRESS')),
		);
           */     
                

                if ($result == "OK")
                    $this->module->validateOrder($cart->id, Configuration::get('PAY24_OK'), $total, $this->module->displayName, NULL, NULL, (int)$currency->id, false, $customer->secure_key);
                else if ($result == "FAIL"){
                    $this->module->validateOrder($cart->id, Configuration::get('PAY24_FAIL'), $total, $this->module->displayName, NULL, $mailVars, (int)$currency->id, false, $customer->secure_key);
                }
                else if ($result == "PENDING")
                    $this->module->validateOrder($cart->id, Configuration::get('PAY24_PENDING'), $total, $this->module->displayName, NULL, $mailVars, (int)$currency->id, false, $customer->secure_key);
                
                return $this->module->currentOrder;
	}
	
}