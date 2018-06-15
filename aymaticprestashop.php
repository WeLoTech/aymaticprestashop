<?php
/**
 * This source file is subject to the Open Software License (OSL 3.0)
 *  @author    Markus Peitl <mp@welovetech.at>
 *  @copyright 2018 WeLo Tech GmbH
 *  @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

if (!defined('_PS_VERSION_')) {
	exit;
}

class AymaticPrestaShop extends Module {
	public function __construct() {
		$this->name = 'aymaticprestashop';
		$this->displayName = 'aymatic Videos';
		$this->tab = 'administration';
		$this->version = '0.1.0';
		$this->author = 'WeLoTech';
		$this->description = $this->l('Product video using API.');
		$this->ps_versions_compliancy = array('min' => '1.5', 'max' => _PS_VERSION_);
		// $this->module_key = '53ef2ea35243b9b84e5cebcf067cf963';
		$this->bootstrap = true;
		$this->_psv = Tools::substr(_PS_VERSION_, 0, 3) * 10;
		parent::__construct();

	}

	public function install() {
		return (parent::install() && $this->registerHook('displayHeader') && 
		$this->registerHook('actionProductSave') && 
		$this->registerHook('actionProductUpdate') && 
		$this->registerHook('displayAdminProductsExtra') && 
		$this->createTable() && 
		$this->registerHook('actionProductDelete') && 
		$this->registerHook('displayFooterProduct'));
	}
	
	public function uninstall() {
		return (parent::uninstall() && 
		Configuration::deleteByName('API_KEY') && 
		//Configuration::deleteByName('active_module') && 
		Configuration::deleteByName('videos_visibility') && 
		Configuration::deleteByName('dev_mode') && 
		$this->deleteTable());
	}

	public function createTable() {
		$sql = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'productvideos` (
                  `id_product` INT(10) UNSIGNED NOT NULL,
  				  `is_enable` INT(2) DEFAULT NULL,
  				  `video_url` TEXT,
				  `thubmnail_url` TEXT,
				  `product_url` TEXT,
  				   PRIMARY KEY (`id_product`)
                ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8';
		$this->runSql($sql);
		return true;
	}
	private function runSql($query) {
		return Db::getInstance()->execute($query);
	}

	public function deleteTable() {
		$sql = 'DROP TABLE `' . _DB_PREFIX_ . 'productvideos`';
		$this->runSql($sql);
		return true;
	}

	public function getContent() {
		$output = null;
		//$Blowfish = new Blowfish(_COOKIE_KEY_, _COOKIE_IV_);
		if (Tools::isSubmit('submit' . $this->name)) {
			$api_key = strval(Tools::getValue('API_KEY'));
			//$active_module = Tools::getValue('active_module');
			$videos_visibility = Tools::getValue('videos_visibility');
			$dev_mode = Tools::getValue('dev_mode');
			/*if (!$api_key || empty($active_module)) {
				$output .= $this->displayError($this->l('Invalid Configuration value'));
			} else {*/
				Configuration::updateValue('API_KEY', $api_key);
				//Configuration::updateValue('active_module', $active_module);
				Configuration::updateValue('videos_visibility', $videos_visibility);
				Configuration::updateValue('dev_mode', $dev_mode);
				$output .= $this->displayConfirmation($this->l('Settings updated'));
			//}
		}
		return $output . $this->displayForm();
	}

	public function displayForm() {
		// Get default language
		$default_lang = (int) Configuration::get('PS_LANG_DEFAULT');

		// Init Fields form array
		$fields_form[0]['form'] = array(
			'legend' => array(
				'title' => $this->l('Settings'),
			),
			'input' => array(
				array(
					'type' => 'switch',
					'label' => $this->l('Videos Visibility'),
					'name' => 'videos_visibility',
					'is_bool' => true,
					'values' => array(
						array(
							'id' => 'active_on',
							'value' => 1,
							'label' => $this->l('On'),
						),
						array(
							'id' => 'active_off',
							'value' => 0,
							'label' => $this->l('Off'),
						),
					),
				),
				array(
					'type' => 'switch',
					'label' => $this->l('Developer Mode'),
					'name' => 'dev_mode',
					'is_bool' => true,
					'values' => array(
						array(
							'id' => 'active_on',
							'value' => 1,
							'label' => $this->l('On'),
						),
						array(
							'id' => 'active_off',
							'value' => 0,
							'label' => $this->l('Off'),
						),
					),
				),
				/*array(
					'type' => 'switch',
					'label' => $this->l('Enabled'),
					'name' => 'active_module',
					'is_bool' => true,
					'values' => array(
						array(
							'id' => 'active_on',
							'value' => 1,
							'label' => $this->l('Yes'),
						),
						array(
							'id' => 'active_off',
							'value' => 0,
							'label' => $this->l('No'),
						),
					),
				),*/
				array(
					'type' => 'text',
					'label' => $this->l('API KEY:'),
					'name' => 'API_KEY',
					'size' => 20,
					'required' => true,
				),

			),
			'submit' => array(
				'title' => $this->l('Save'),
				'class' => 'btn btn-default pull-right',
			),
		);

		$helper = new HelperForm();

		// Module, token and currentIndex
		$helper->module = $this;
		$helper->name_controller = $this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;

		// Language
		$helper->default_form_language = $default_lang;
		$helper->allow_employee_form_lang = $default_lang;

		// Title and toolbar
		$helper->title = $this->displayName;
		$helper->show_toolbar = true; // false -> remove toolbar
		$helper->toolbar_scroll = true; // yes - > Toolbar is always visible on the top of the screen.
		$helper->submit_action = 'submit' . $this->name;
		$helper->toolbar_btn = array(
			'save' => array(
				'desc' => $this->l('Save'),
				'href' => AdminController::$currentIndex . '&configure=' . $this->name . '&save' . $this->name .
				'&token=' . Tools::getAdminTokenLite('AdminModules'),
			),
			'back' => array(
				'href' => AdminController::$currentIndex . '&token=' . Tools::getAdminTokenLite('AdminModules'),
				'desc' => $this->l('Back to list'),
			),
		);

		// Load current value
		$helper->fields_value['API_KEY'] = Configuration::get('API_KEY');
		//$helper->fields_value['active_module'] = Configuration::get('active_module');
		$helper->fields_value['dev_mode'] = Configuration::get('dev_mode');
		$helper->fields_value['videos_visibility'] = Configuration::get('videos_visibility');
		return $helper->generateForm($fields_form);
	}

	public function hookdisplayHeader($params) {
		$this->context->controller->addCSS($this->_path . 'css/productvideo.css');
/*
if ($this->_psv > 16) {
$this->context->controller->addJS($this->_path . 'js/jquery.min.js');
$this->context->controller->addCSS($this->_path . 'css/jquery.fancybox.min.css');

$this->context->controller->addJS($this->_path . 'js/jquery.fancybox.min.js');
}*/

	}

	public function hookdisplayFooterProduct($params) {
		if((bool)Configuration::get('videos_visibility')){
			global $smarty;
			$id_product = $this->_psv > 16 ? $params['product']['id'] : $params['product']->id;
			$data_video = null;

			$statusvideoenabled = false;
			$status = $this->_getproductVideoStatus($id_product);

			if(!(bool)Configuration::get('dev_mode')){
				$data_video = $this->_getDataCall($id_product);
				$statusvideoenabled = (bool)$status['is_enable'];
			}
			else{
				$data_video = $this->_getDefaultData();
				$statusvideoenabled = true;
			}
			//$images = null;
			//$images = Image::getImages($this->context->language->id, $id_product);
			//$product = new Product($id_product);
			//$link = new Link;

			$smarty->assign(array(
				'is_module_enable' => Configuration::get('videos_visibility'),
				'status_video' => $statusvideoenabled,
				'video_url' => $data_video['video_url'],
				'video_thumbnialurl' => $data_video['video_thumbnialurl'],
				'product_url' => $data_video['product_url'],
				'module_path' => $this->_path//,
				//'images' => $images,
				//'product' => $product,
				//'link' => $this->context->link,
			));

			if ($this->_psv > 16) {
				return ($this->display(__FILE__, 'views/templates/hooks/videos.tpl'));

			} else {
				return ($this->display(__FILE__, 'views/templates/hooks/videos16.tpl'));
			}
		}
	}

	public function hookactionProductSave($params) {
		$id_product = $params['id_product'];
		$status = Tools::getValue('active_video');
		$this->sendUpdateToServer();
		//$productvideo = Tools::getValue('submitted_tabs');
		if ($status != '') {
			//$this->_sendApiCall($id_product);
			$this->_updateStatus($id_product, $status, '');

		}
		//$this->_addnewStatus($params['id_product'], $status);
	}

	public function hookactionProductUpdate($params) {
		$id_product = $params['id_product'];
		$status = Tools::getValue('active_video');
		$this->sendUpdateToServer($id_product);
		//$productvideo = Tools::getValue('submitted_tabs');
		if ($status != '') {
			//$this->_sendApiCall($id_product);
			$this->_updateStatus($id_product, $status, '');

		}
	}

	public function sendUpdateToServer($id_product) {
		$ch = curl_init();
		//$url = "http://localhost:8080/postchanged"; //LOCAL
		$url = "https://app.aymatic.com/postchanged"; //LIVE
		$api_key = Tools::getValue('API_KEY');

		$product = new Product($id_product, false, Context::getContext()->language->id);
		$link = new Link;
		$p_url = $link->getProductLink($product);

		$messagearray =  array('productPageUrl' => $p_url);
		$messagejson = json_encode($messagearray);
		//$encryptedmessage = $this->encryptEncode($messagejson,$api_key);
		//$json = array('message' => $encryptedmessage, 'domain' => $_SERVER['HTTP_HOST']);
		$json = array('message' => $messagejson, 'domain' => $_SERVER['HTTP_HOST']);
		$payload = json_encode($json);

		curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
		curl_setopt($ch,CURLOPT_URL,$url); // such as http://example.com/example.xml
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,2);
		curl_setopt($ch,CURLOPT_TIMEOUT,10);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		curl_exec($ch);
		curl_close($ch);
	}
	private function encryptEncode($plainText,$password){
		$encryptedString = $this->encrypt($plainText,$password);
		$encodedString = base64_encode($encryptedString);
		return $encodedString;
	}
	private function encrypt($plaintext, $password) {
		$method = "aes-256-cbc";
		$key = hash('md5', $password, false);
		$iv = openssl_random_pseudo_bytes(16);
		$ciphertext = openssl_encrypt($plaintext, $method, $key, OPENSSL_RAW_DATA, $iv);
		$hash = hash_hmac('sha256', $ciphertext, $key, true);
		return $iv . $hash . $ciphertext;
	}

	public function _sendApiCall($id_product = NULL) {
		//send  your api call to server
		$api_key = Tools::getValue('API_KEY');
		$product = new Product($id_product, false, Context::getContext()->language->id);
		$image = Image::getCover($id_product);
		$link = new Link; //because getImageLInk is not static function
		//$imagePath = $link->getImageLink($product->link_rewrite, $image['id_image'], 'home_default');
		$p_url = $link->getProductLink($product);
		$json = array('productPageUrl' => $p_url, 'domain' => $_SERVER['HTTP_HOST'], 'api_key' => $api_key);
		$url = 'https://app.aymatic.com/postchanged';
		$ch = curl_init($url);
		# Setup request to send json via POST.
		$payload = json_encode($json);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		# Return response instead of printing.
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		# Send request.
		$result = curl_exec($ch);
		curl_close($ch);
		# Print response.
		//echo "<pre>$result</pre>";exit;
		//print_r(json_encode($json));exit;
		return;
	}

	public function _getDataCall($id_product) {
		
		$ch = curl_init();
		$url = "https://app.aymatic.com/requestvideo";
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$video_links = curl_exec($ch);
		$resposeCode = curl_getinfo($ch);
		if ($resposeCode['http_code'] != 200) {
			//return false;
		}
		if (isset($video_links) && !is_null($video_links)) {
			$video_links_data = json_decode($video_links, true);
		}
		curl_close($ch);
		//print_r($video_links_data);exit;
		$videos_link_db = $this->_getproductVideoStatus($id_product);
		$array_video_data = '';
		if ($videos_link_db['product_url'] != '' && $videos_link_db['product_url'] == $video_links_data['productPageUrl']) {
			$this->_updateStatus($videos_link_db['id_product'], '', $video_links_data);
		}
		if ($videos_link_db['product_url'] != '' && $videos_link_db['video_url'] != '' && $videos_link_db['thubmnail_url']) {
			//if video in database then get from db
			$array_video_data = array('product_url' => $videos_link_db['product_url'], 'video_url' => $videos_link_db['video_url'], 'video_thumbnialurl' => $videos_link_db['thubmnail_url']);
		} else {
			//if video not in database then get from API
			$array_video_data = array('product_url' => $video_links_data['productPageUrl'], 'video_url' => $video_links_data['videoEmbedUrl'], 'video_thumbnialurl' => $video_links_data['thumbNailUrl']);
		}
		
		//get api data and send to product page in image section
		return $array_video_data;
	}
	
	public function _getDefaultData() {
		$array_video_data = array('product_url' => 'http://127.0.0.1/prestashop_1.7.3.3/home-accessories/19-customizable-mug.html', 
		'video_url' => 'https://app.aymatic.com/#/video-embed?uid=01737112d13e9b65a41c23e96b4e0f40154c6794400aab71ed4fc833495b9f39&temp=DeveloperMode-1',
		'video_thumbnialurl' => 'https://app.aymatic.com/dist/videos/01737112d13e9b65a41c23e96b4e0f40154c6794400aab71ed4fc833495b9f39/ec3cb20c29f71c22d2524518b7444095e075e1dc04b014439df8c97aa4edb1_640.jpg');
		//get api data and send to product page in image section
		return $array_video_data;
	}

	public function hookDisplayAdminProductsExtra($params) {
		//print_r($params);exit;
		//$id_product = Tools::getValue('id_product');
		$id_product = $params['id_product'] != '' ? $params['id_product'] : Tools::getValue('id_product');
		$status = $this->_getproductVideoStatus($id_product);
		//print_r($status);exit;
		global $smarty;

		$videoEnabled = true;
		$dbEntryExists = true;
		if(!empty($status) && isset($status) && isset($status['video_url'])){
			$videoEnabled = $status['is_enable'];
		}
		else{
			$dbEntryExists = false;
		}

		//if (!empty($status) && isset($status)) {
			$smarty->assign(array(
				'ps_version' => $this->_psv,
				'link' => $this->context->link,
				'status' => $status['is_enable'],
				'entryexists' =>$dbEntryExists));
		//}

		return $this->display(__FILE__, 'views/templates/hooks/admin/tab.tpl');
	}
	public function checkVideoDataEntryExists($product_id){
		$sql = 'SELECT * FROM '._DB_PREFIX_.$tableName.' WHERE id_product = ' . (int)$extractedid;
	}

	public function _getproductVideoStatus($id_product) {
		$sql = 'SELECT * FROM `' . _DB_PREFIX_ . 'productvideos` WHERE `id_product`=' . (int) $id_product;
		if ($row = Db::getInstance()->getRow($sql)) {
			return $row;
		}
	}

	public function _updateStatus($id_product = NULL, $status = NULL, $api_data = NULL) {
		if ($api_data != NULL && isset($api_data)) {
			Db::getInstance()->update('productvideos', array(
				'video_url' => $api_data['videoEmbedUrl'],
				'thubmnail_url' => $api_data['thumbNailUrl'],
			), 'id_product=' . (int) $id_product);
		} else {
			$status_check['is_enable'] = $this->_getproductVideoStatus($id_product);
			$product = new Product($id_product, false, Context::getContext()->language->id);
			$link = new Link;
			$p_url = $link->getProductLink($product);
			if ($status_check['is_enable'] != '') {
				//echo "update";exit;
				Db::getInstance()->update('productvideos', array(
					'is_enable' => (int) $status,
					'product_url' => $p_url,
				), 'id_product=' . (int) $id_product);
			} else {
				//echo "insert";exit;
				$this->_addnewStatus($id_product, $status);
			}
		}
	}

	public function _addnewStatus($id_product, $status) {
		$product = new Product($id_product, false, Context::getContext()->language->id);
		$link = new Link;
		$p_url = $link->getProductLink($product);
		$sql = 'INSERT INTO `' . _DB_PREFIX_ . 'productvideos` (id_product,is_enable,product_url) VALUES (' . (int)$id_product . ',' . (int)$status . ',"' . $p_url . '")';
		if (!Db::getInstance()->execute($sql)) {
			die('error insert!');
		}
	}

	public function hookactionProductDelete($params) {
		$id_product = $params['id_product'];
		$this->_deleteStatus($id_product);

	}
	public function _deleteStatus($id_product) {
		$sql = 'DELETE FROM ' . _DB_PREFIX_ . 'productvideos WHERE id_product = ' . (int)$id_product;
		if (!Db::getInstance()->execute($sql)) {
			die('Error delete!.');
		}
	}
}
