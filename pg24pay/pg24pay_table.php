<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of veintequatropago_sign
 *
 * @author durcak
 */
class Pg24payTable {
   
    public function createId($cart_id){
        
        
        $id = Db::getInstance()->insert('24pay_orderer', array(
        'cart_id' => (int)$cart_id,
        'created'=> pSQL(date("Y-m-d H:i:s")),
        )); 
		
		$dbid = Db::getInstance()->Insert_ID();
		
        return $dbid;
    }
    
    public function createIdFromOrder($order_id){
        
		$id = Db::getInstance()->insert('24pay_orderer', array(
        'order_id' => (int)$order_id,
        'from_order' => (int)1,
        'created'=> pSQL(date("Y-m-d H:i:s")),
        )); 
        /*
        $id = Db::getInstance()->autoExecute(_DB_PREFIX_.'24pay_orderer', array(
        'order_id' => (int)$order_id,
        'from_order' => (int)1,
        'created'=> pSQL(date("Y-m-d H:i:s")),
        ), 'INSERT'); 
		*/
        return Db::getInstance()->Insert_ID();
    }
    
    
    
    public function getOrderId($id_24){
        /* return Db::getInstance()->getValue(
            'SELECT `order_id`
            FROM `24pay_orderer`
            WHERE `id_24` = '.(int) $id_24
        );
		*/
		$result = Db::getInstance()->executeS( (new DbQuery())
		->from('24pay_orderer')
		->select('order_id')
		->where('`id_24` = '.(int) $id_24)
		);
		
		return $result['0']['order_id'];
    }
    
    public function getCartId($id_24){
		$result = Db::getInstance()->executeS( (new DbQuery())
		->from('24pay_orderer')
		->select('cart_id')
		->where('`id_24` = '.(int) $id_24)
		);
		return $result['0']['cart_id'];
    }
    
    public function getStatus($id_24){
        /*return Db::getInstance()->getValue(
            'SELECT `status`
            FROM `24pay_orderer`
            WHERE `id_24` = '.(int) $id_24
        );
		*/
		$result = Db::getInstance()->executeS( (new DbQuery())
		->from('24pay_orderer')
		->select('status')
		->where('`id_24` = '.(int) $id_24)
		);
		return $result['0']['status'];
    }
    
    public function confirmOrder($id_24, $order_id, $status){
        
        Db::getInstance()->update('24pay_orderer', array(
        'order_id' => (int)$order_id,
        'confirmed'=> pSQL(date("Y-m-d H:i:s")),
        'status'=> pSQL($status),
        ), '`id_24`= \''.(int) $id_24.'\''); 
        
        /*
        Db::getInstance()->Execute(
            'UPDATE `24pay_orderer` '
                . 'SET `order_id`=\''.(int) $order_id.'\', `confirmed`=\''.pSQL(date("Y-m-d H:i:s")).'\', `status`=\''.pSQL($status).'\' '
                . 'WHERE `id_24`= \''.(int) $id_24.'\' ');
        */	
    }
    
    public function setOrderNurl($id_24, $nurl){
        
        Db::getInstance()->update('24pay_orderer', array(
        'nurl'=> pSQL($nurl,true),
        ),  '`id_24`= \''.(int) $id_24.'\''); 
    }
    
    public function setStatus($id_24,$newStatus){
        Db::getInstance()->update('24pay_orderer', array(
        'status'=> pSQL($newStatus),
        ), '`id_24`= \''.(int) $id_24.'\''); 
    }
    
}
