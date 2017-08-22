<?php
/**
 * 2017 Cwplus.be
 *
 * @author    Cwplus <contact@cwplus.be>
 * @copyright 2017 Cwplus Agence web
 * @license   For any request send an e-mail.
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

class Cwplus_paymentpermonth extends Module
{
    public function __construct()
    {
        $this->name = 'cwplus_paymentpermonth';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'Khalid EL HAKYM <www.cwplus.be>';
        $this->need_instance = 0;

        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Cwplus payment per month');
        $this->description = $this->l('It\'s using for payment per month');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall \"Cwplus payment per month\" module ?');

        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        include(dirname(__FILE__).'/sql/install.php');
//$this->registerHook('displayAdminProductsExtra') &&
	    // $this->registerHook('actionProductSave') &&

	    //This hook is displayed after a product is created
	    //This hook is called when a product is deleted
	    //This hook is displayed after a product has been updated

	    //$this->registerHook('actionProductAdd') &&
	    //$this->registerHook('actionProductUpdate') &&
	    if (parent::install() &&
            $this->registerHook('actionProductSave') &&
			$this->registerHook('actionProductDelete') &&
            $this->registerHook('displayAdminProductsPriceStepBottom') &&
			$this->registerHook('header') &&
			$this->registerHook('backOfficeHeader')) return true;
        return false;
    }

    public function uninstall()
    {
        include(dirname(__FILE__).'/sql/uninstall.php');

        return parent::uninstall();
    }

	public function hookActionProductDelete($params)
	{
		return $this->deleteData($params['id_product']);
	}

	private function getDefaultData()
	{
		return array(
			'advance' => '',
			'per_month' => '',
			'nbr_month' => ''
		);
	}

	public function hookActionProductSave($params)
	{
		$data = Tools::getValue('paymentpermonth',$this->getDefaultData());
		$data['id_product'] = $params['id_product'];
		//Tools::displayError('An error occurred while creating an object.');
		//error_log('test ok');
		return $this->saveData($data);
		//return true;
	}

	public function hookDisplayAdminProductsPriceStepBottom($params)
	{
		return $this->renderPaymentPerMonthForm($params);
	}

	public function renderPaymentPerMonthForm($params)
	{
		$this->context->smarty->assign(array(
			'sign' => $this->context->currency->sign,
			'paymentpermonth' => $this->getData($params['id_product'])
		));

		return $this->display(__FILE__, 'cwplus-paymentpermonth.tpl');
	}

	public function deleteData($id_product)
	{
		Db::getInstance()->delete('cwplus_paymentpermonth',' `id_product` = '.$id_product);
	}

	public function getData($id_product)
	{
		$sql = 'SELECT * FROM '._DB_PREFIX_.'cwplus_paymentpermonth WHERE 1 AND id_product = '.$id_product.' ORDER BY id_paymentpermonth DESC';
		if (($row = Db::getInstance()->getRow($sql,0)) && !is_null($row)) return $row;
		return $this->getDefaultData();
	}

	public function saveData($data)
	{
		$db = Db::getInstance();
		$id_product = $data['id_product'];
		if(empty($data['per_month']) || empty($data['nbr_month'])) return true;
		if( $this->checkDataProductExist($id_product)){
			$data_up = array(
				'advance' => $data['advance'],
				'per_month' => $data['per_month'],
				'nbr_month' => $data['nbr_month']
			);
			return $db->update('cwplus_paymentpermonth',$data_up,'id_product = '.$id_product);
		}
		return $db->insert('cwplus_paymentpermonth',$data);
	}

	public function checkDataProductExist($id_product)
	{
		$sql = 'SELECT COUNT(*) FROM '._DB_PREFIX_.'cwplus_paymentpermonth WHERE 1 AND id_product = '.$id_product.' ORDER BY id_paymentpermonth DESC';
		$count = Db::getInstance()->getValue($sql,0);
		if($count) return true;
		return false;
	}

    /**
    * Add the CSS & JavaScript files you want to be loaded in the BO.
    */
    public function hookBackOfficeHeader()
    {
        if (Tools::getValue('module_name') == $this->name) {
            $this->context->controller->addJS($this->_path.'views/js/cwplus-paymentpermonth-back.js');
            $this->context->controller->addCSS($this->_path.'views/css/cwplus-paymentpermonth-back.css');
        }
    }

    /**
     * Add the CSS & JavaScript files you want to be added on the FO.
     */
    public function hookHeader()
    {
        $this->context->controller->addJS($this->_path.'/views/js/cwplus-paymentpermonth-front.js');
        $this->context->controller->addCSS($this->_path.'/views/css/cwplus-paymentpermonth-front.css');
    }
}
