<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of pg24pay
 *
 * @author durcak
 */

use PrestaShop\PrestaShop\Core\Payment\PaymentOption;

class Pg24pay extends PaymentModule{
    protected $_html = '';
    protected $_postErrors = array();


    public function __construct()
    {
        $this->name = 'pg24pay';
        $this->tab = 'payments_gateways';
        $this->version = '1.7.4';
        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
        $this->author = 'Lukius24';
        //$this->controllers = array('validation');
        //$this->is_eu_compatible = 1;

        $this->currencies = true;
        $this->currencies_mode = 'checkbox';

        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->l('24 Pay');
        $this->description = $this->l('24-pay official payment module');
        $this->confirmUninstall = $this->l('Are you sure you want to delete your details?');

        /*if (!count(Currency::checkPaymentCurrencies($this->id))) {
            $this->warning = $this->l('No currency has been set for this module.');
        }*/
    }

    public function install(){
        if (!parent::install() || !$this->registerHook('payment') || !$this->registerHook('paymentReturn') || !$this->registerHook('paymentOptions')
            || !$this->registerHook('displayRightColumn') || !$this->registerHook('displayLeftColumn') || !$this->registerHook('displayFooter')) {
            return false;
        }

        include_once _PS_MODULE_DIR_.$this->name.'/pg24pay_install.php';
        $pay24_install = new Pg24payInstall();
        $pay24_install->install();
        return true;
    }

    public function hookRightColumn($params){
        return $this->fetchTemplate("right.tpl");
    }

    public function hookLeftColumn($params){
        return $this->fetchTemplate("left.tpl");
    }

    public function hookFooter($params){
        return $this->fetchTemplate("foot.tpl");
    }

	public function hookPaymentOptions()
	{
		    if (!$this->active) {
            return;
        }

        $newOption = new PaymentOption();
        $newOption->setModuleName($this->name)
                ->setCallToActionText($this->l('Pay by 24-pay'))
                ->setAction($this->context->link->getModuleLink($this->name, 'payment', array(), true))
                ->setAdditionalInformation($this->l('Pay by card or internet banking'));
        $payment_options = [
            $newOption,
        ];

        return $payment_options;
	}

    public function hookPayment($params) {

            if (!$this->active)
                return;

            $this->smarty->assign(array(
                    'this_path' => $this->_path,
                    'this_path_cheque' => $this->_path,
                    'this_path_ssl' => Tools::getShopDomainSsl(true, true).__PS_BASE_URI__.'modules/'.$this->name.'/'
            ));

            return $this->fetchTemplate('module:pg24pay/views/templates/front/payment.tpl');
    }



    public function hookPaymentReturn($params) {

    }

    public function uninstall(){
        include_once _PS_MODULE_DIR_.$this->name.'/pg24pay_install.php';
        $pay24_install = new Pg24payInstall();
        $pay24_install->uninstall();
        return parent::uninstall();
    }

    public function getContent(){
		if (isset($_GET['action'])){
			if ($_GET['action']=='test'){
				$this->runTest();
			}
		}
		else{
			$this->context->smarty->assign(array(
				'PAY24_TEST' => '0',
			));
		}

        $this->baseConfig();

        //$output = $this->fetchTemplate('/views/templates/admin/back_office.tpl');
	$output = $this->context->smarty->fetch('module:pg24pay/views/templates/admin/back_office.tpl');

        if ($this->active == false) {
            return $output;
        }

        return $output;
    }

    private function baseConfig(){
        if (isset($_POST['submitPaypal'])){
            if ($_POST['submitPaypal']=="pg24pay_configuration"){
                Configuration::updateValue('PAY24_DEBUG', $_POST['PAY24_DEBUG']);
                Configuration::updateValue('PAY24_MID', $_POST['PAY24_MID']);
                Configuration::updateValue('PAY24_ESHOP_ID', $_POST['PAY24_ESHOP_ID']);
                Configuration::updateValue('PAY24_KEY', $_POST['PAY24_KEY']);
                Configuration::updateValue('PAY24_MODULE_DEBUG', $_POST['PAY24_MODULE_DEBUG']);
                Configuration::updateValue('PAY24_REPAY', $_POST['PAY24_REPAY']);
                Configuration::updateValue('PAY24_LOG', $_POST['PAY24_LOG']);
            }
        }

        $url  = 'index.php?controller=AdminModules&configure=pg24pay&tab_module=payments_gateways&module_name=pg24pay';
        $url .= '&token='.Tools::getAdminTokenLite('AdminModules');

        $this->context->smarty->assign(array(
            'PAY24_DEBUG' => Configuration::get('PAY24_DEBUG'),
            'PAY24_MID' => Configuration::get('PAY24_MID'),
            'PAY24_ESHOP_ID' => Configuration::get('PAY24_ESHOP_ID'),
            'PAY24_KEY' => Configuration::get('PAY24_KEY'),
            'PAY24_MODULE_DEBUG' => Configuration::get('PAY24_MODULE_DEBUG'),
            'PAY24_REPAY' => Configuration::get('PAY24_REPAY'),
            'PAY24_LOG' => Configuration::get('PAY24_LOG'),

            'PAY24_CONFIG_LINK' => $url,
        ));
    }

    private function runTest(){
        include_once _PS_MODULE_DIR_.$this->name.'/pg24pay_request.php';
            $requester = new Pg24payRequest();
            $signResult = $requester->checkSignGeneration();
            $gatesResult = $requester->checkAvailableGateways();
            $this->context->smarty->assign(array(
                'PAY24_TEST'=>'1',
                'PAY24_SIGN_RESULT'=>$signResult,
                'PAY24_GATES_RESULT'=>$gatesResult,
            ));
    }

    public function fetchTemplate($name)
    {
        $views = 'views/templates/';
        if (@filemtime(dirname(__FILE__).'/'.$name)) {
            return $this->display(__FILE__, $name);
        } elseif (@filemtime(dirname(__FILE__).'/'.$views.'hook/'.$name)) {
            return $this->display(__FILE__, $views.'hook/'.$name);
        } elseif (@filemtime(dirname(__FILE__).'/'.$views.'front/'.$name)) {
            return $this->display(__FILE__, $views.'front/'.$name);
        } elseif (@filemtime(dirname(__FILE__).'/'.$views.'admin/'.$name)) {
            return $this->display(__FILE__, $views.'admin/'.$name);
        }
        else
            return $this->display(__FILE__, $name);
    }

}
