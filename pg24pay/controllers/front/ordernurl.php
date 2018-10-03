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


include_once 'modules/pg24pay/pg24pay_nurl.php';
include_once 'modules/pg24pay/pg24pay_table.php';   

class Pg24payOrdernurlModuleFrontController extends ModuleFrontController
{
    
    public function initContent()
    {
        parent::initContent();
      
        if (isset($_POST['params'])){
            
            if (Configuration::get('PAY24_LOG')==1){
                $fileName = "modules/pg24pay/logs/LOG".date("Y-m-d_H.i.s").".log";
                $myfile = fopen("$fileName", "w");
                $income = "PAYMENT BY ORDER \n\r".print_r($_POST,true);
                fwrite($myfile, $income);
                fclose($myfile);
            }
            
            
            $nurl = new Pg24payNurl($_POST['params']);
            $id_24 = $nurl->get24Id();
            
                     
            $dbTable = new Pg24payTable();
            $orderId = $dbTable->getOrderId($id_24);
            
            $dbTable->setOrderNurl($id_24, $_POST['params']);
            
            if ($nurl->validateSign()){
                if ($nurl->result=="OK"){
					
					$orderObj = new Order($orderId);
					$orderObj->setInvoice(true);
					
                    $history = new OrderHistory();
                    $history->id_order = (int)$orderId;
                    $history->changeIdOrderState(Configuration::get('PAY24_OK'), (int)($orderId));
                    $history->addWithemail(true);
                    
                    $dbTable->setStatus($id_24, "OK");
                }
                else if ($nurl->result=="PENDING"){
                    $history = new OrderHistory();
                    $history->id_order = (int)$orderId;
                    $history->changeIdOrderState(Configuration::get('PAY24_PENDING'), (int)($orderId));
                    $history->add();
                    
                    $dbTable->setStatus($id_24, "PENDING");
                }
                else if ($nurl->result=="FAIL"){
                    $history = new OrderHistory();
                    $history->id_order = (int)$orderId;
                    $history->changeIdOrderState(Configuration::get('PAY24_FAIL'), (int)($orderId));
                    $history->add();
                    
                    $dbTable->setStatus($id_24, "FAIL");
                }
            }
            else{
                echo "BAD NURL SIGN!";
            }
        }
        else{
            echo "NO POST!";
        }
    }

}