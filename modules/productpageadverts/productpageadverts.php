<?php

class ProductPageAdverts extends Module {

	private $_postErrors = array();

	function __construct() {
		$this->name = 'productpageadverts';
		$this->tab = 'front_office_features';
		$this->version = 1.6;
		$this->author = 'IQIT-COMMERCE.COM';
		$this->bootstrap = true;

		parent::__construct();
		$this->displayName = $this->l('Product page adverts');
		$this->description = $this->l('Display image adverts on product page');
	}

	public function install() {
		$this->_clearCache('productpageadverts.tpl');
		if (!parent::install() OR !$this->registerHook('productPageRight') OR !$this->registerHook('header'))
			return false;

		$images = $this->_parseImageDir();
		if (!$images)
			return false;

		$langs = '';
		$curr = '';
		for ($i = 0; $i < count($images); $i++) {
			$curr .= 'all;';
			$langs .= 'all;';
		}

		if (!Configuration::updateValue($this->name . '_links', '') OR !Configuration::updateValue($this->name . '_langs', $langs) OR !Configuration::updateValue($this->name . '_curr', $curr) OR !Configuration::updateValue($this->name . '_images', implode(";", $images)))
			return false;

		return true;
	}

	private function _postProcess() {
		$html ='';
		Configuration::updateValue($this->name . '_images', Tools::getValue('image_data'));
		Configuration::updateValue($this->name . '_links', Tools::getValue('link_data'));
		Configuration::updateValue($this->name . '_langs', Tools::getValue('lang_data'));
		Configuration::updateValue($this->name . '_curr', Tools::getValue('curr_data'));

		$html .= $this->displayConfirmation($this->l('Configuration updated'));
	}

	public function getContent() {

		$html = '';
		if (Tools::isSubmit('submit')) {

			if (!sizeof($this->_postErrors))
				$html .= $this->_postProcess();
			else {
				foreach ($this->_postErrors as $err) {
					$html .= '<div class="alert error">' . $err . '</div>';
				}
			}
			$this->_clearCache('productpageadverts.tpl');
		}

		$dirImages = $this->_parseImageDir();
		$confImages = $this->_getImageArray();
		$nbDirImages = count($dirImages);
		$nbConfImages = count($confImages);

		$html .= '
			<script type="text/javascript" src="../js/jquery/plugins/jquery.tablednd.js"></script>
			<script type="text/javascript" src="../modules/' . $this->name . '/ajaxupload.js"></script>
			<script type="text/javascript" src="../modules/' . $this->name . '/productpageadverts.js"></script>
			
			<table id="hidden-row" style="display:none">' . $this->_getTableRowHTML(0, 2, '') . '</table>
		
			<form action="'.$_SERVER['REQUEST_URI'].'" method="post">
		<div class="panel">
		<div class="panel-heading">'.$this->l('Settings').'</div>
					
					<input type="hidden" id="hidden_image_data" name="image_data" value="' . Configuration::get($this->name . '_images') . '"/>
					<input type="hidden" id="hidden_link_data" name="link_data" value="' . Configuration::get($this->name . '_links') . '"/>
					<input type="hidden" id="hidden_lang_data" name="lang_data" value="' . Configuration::get($this->name . '_langs') . '"/>
					<input type="hidden" id="hidden_curr_data" name="curr_data" value="' . Configuration::get($this->name . '_curr') . '"/>
					
					
					<table cellpadding="0" cellspacing="0" class="table space' . ($nbDirImages >= 2 ? ' tableDnD' : '') . '" id="table_images" style="margin-left: 30px; width: 825px;">
					<caption style="font-weight: bold; margin-bottom: 1em;">' . $this->l('Images') . '</caption>
					<tr class="nodrag nodrop">
						<th width="60" colspan="2">' . $this->l('Position') . '</th>
						
						<th style="padding-left: 10px; display: none">' . $this->l('Image') . ' </th>
						<th width="270">' . $this->l('Link') . ' </th>
						<th width="80">' . $this->l('Language') . ' </th>
						<th width="80">' . $this->l('Currency') . ' </th>
						<th width="40">' . $this->l('Enabled') . ' </th>
						<th width="40">' . $this->l('Delete') . ' </th>
						<th>' . $this->l('Image') . ' </th>
					</tr>';

		if ($nbDirImages) {
			$i = 1;

			foreach ($confImages AS $confImage) {
				if (in_array($confImage['name'], $dirImages)) {
					$html .= $this->_getTableRowHTML($i, $nbDirImages, $confImage['name'], $confImage['link'], true);
					$i++;
				}
			}

			if ($nbDirImages > $nbConfImages) {
				foreach ($dirImages AS $dirImage) {
					if (!$this->_isImageInArray($dirImage, $confImages)) {
						$html .= $this->_getTableRowHTML($i, $nbDirImages, $dirImage);
						$i++;
					}
				}
			}
		} else {
			$html .= '<tr><td colspan="4">' . $this->l('No image in module directory') . '</td></tr>';
		}

		$html .= '	</table>
			
			        <br />
			        	<p style="color: red; margin-left: 30px;">' . $this->l('Max width of image: 240px') . ' </p>
			        <a href="#" id="uploadImage" style="margin-left:30px">
			             <img src="../img/admin/add.gif" alt="upload image" />' . $this->l('Add an image') . '
                    </a>
                    <img id="loading_gif" src="' . _MODULE_DIR_ . $this->name . '/ajax-loader.gif" alt="uploading..." style="position:relative; top:2px; display:none;"/>
			
		<br /><br />
		<input type="submit" name="submit" value="'.$this->l('Update').'" class="btn btn-default pull-right" >
		<br /><br />

		</div>
		</form>';
		return $html;
	}

	private function _displayForm() {

	}

	public function hookproductPageRight($params) {

		if (!$this->isCached('productpageadverts.tpl', $this->getCacheId())) {
			global $smarty;

			$width = Configuration::get($this->name . '_width');
			$width = ($width == '' || $width == 'auto') ? '100%' : $width . 'px';

			$smarty->assign(array('climages' => $this->_getImageArray(true, true)));
		}
		return $this->display(__FILE__, 'productpageadverts.tpl', $this->getCacheId());

	}

	private function _getImageArray($lang_filter = false, $curr_filter = false) {
		global $cookie;

		$images = explode(";", Configuration::get($this->name . '_images'));
		$links = explode(";", Configuration::get($this->name . '_links'));
		$langs = $lang_filter ? explode(";", Configuration::get($this->name . '_langs')) : false;
		$curr = $curr_filter ? explode(";", Configuration::get($this->name . '_curr')) : false;

		$tab_images = array();

		for ($i = 0, $length = sizeof($images); $i < $length; $i++) {
			if ($images[$i] != "") {
				if ($lang_filter && isset($langs[$i]) && $langs[$i] != 'all' && $langs[$i] != $cookie->id_lang)
					continue;
				if (($curr_filter && isset($curr[$i]) && $curr[$i] != 'all' && $curr[$i] != $cookie->id_currency))
					continue;

				$tab_images[] = array('name' => $images[$i], 'link' => isset($links[$i]) ? $links[$i] : '');
			}
		}

		return $tab_images;
	}

	private function _isImageInArray($name, $array) {
		if (!is_array($array))
			return false;

		foreach ($array as $image) {
			if (isset($image['name'])) {
				if ($image['name'] == $name)
					return true;
			}
		}

		return false;
	}

	private function _parseImageDir() {
		$dir = _PS_MODULE_DIR_ . $this->name . '/slides/';
		$imgs = array();
		$imgmarkup = '';

		if ($dh = opendir($dir)) {
			while (($file = readdir($dh)) !== false) {
				if (!is_dir($file) && preg_match("/^[^.].*?\.(jpe?g|gif|png)$/i", $file)) {
					array_push($imgs, $file);
				}
			}
			closedir($dh);
		} else {
			echo 'can\'t open slide directory';
			return false;
		}

		return $imgs;
	}

	private function _getTableRowHTML($i, $nbImages, $imagename, $imagelink = '', $checked = false) {
		return '<tr id="tr_image_' . $i . '"' . ($i % 2 ? ' class="alt_row"' : '') . ' style="height: 142px;">
				<td class="positions" width="30" style="padding-left: 20px;">' . $i . '</td>
				<td' . ($nbImages >= 2 ? ' class="dragHandle"' : '') . ' id="td_image_' . $i . '" width="30">
					<a' . ($i == 1 ? ' style="display: none;"' : '') . ' href="#" class="move-up"><img src="../img/admin/up.gif" alt="' . $this->l('Up') . '" title="' . $this->l('Up') . '" /></a><br />
					<a ' . ($i == $nbImages ? ' style="display: none;"' : '') . 'href="#" class="move-down"><img src="../img/admin/down.gif" alt="' . $this->l('Down') . '" title="' . $this->l('Down') . '" /></a>
				</td>
				
				<td class="imagename" style="padding-left: 10px; display: none;">' . $imagename . '</td>
				<td class="imagelink">
					<input type="text" style="width: 250px" name="image_link_' . $i . '" value="' . $imagelink . '" />
				</td>
				<td class="imagelang">' . $this->_getLanguageSelectHTML($i) . '</td>
				<td class="imagecurr">' . $this->_getCurrencySelectHTML($i) . '</td>
				<td class="checkbox_image_enabled" style="padding-left: 25px;" width="40">
					<input type="checkbox" name="image_enable_' . $i . '"' . ($checked ? ' checked="checked"' : '') . ' /> 
				</td>
				<td class="delete_image" style="padding-left: 25px;" width="40">
					<img src="../img/admin/delete.gif" alt="' . $this->l('Delete') . '" title="' . $this->l('Delete') . '" style="cursor:pointer;" /> 
				</td>
				<td class="image_prev" style="padding-left: 10px;"><img src="' . _MODULE_DIR_ . $this->name . '/slides/' . $imagename . '" alt="' . $imagename . '"></td>
			</tr>';
	}

	private function _getLanguageSelectHTML($i) {
		$languages = Language::getLanguages();
		$langs = explode(";", Configuration::get($this->name . '_langs'));

		$html = '<select name="language_' . $i . '" style="width:55px">';
		$html .= '<option value="all">ALL</option>';

		foreach ($languages as $language) {
			$html .= '<option value="' . $language['id_lang'] . '"' . (isset($langs[$i - 1]) && $langs[$i - 1] == $language['id_lang'] ? 'selected="selected"' : '') . '>' . strtoupper($language['iso_code']) . '</option>';
		}

		return $html .= '</select>';
	}

	private function _getCurrencySelectHTML($i) {
		$currencies = Currency::getCurrencies();
		$curr = explode(";", Configuration::get($this->name . '_curr'));

		$html = '<select name="currency_' . $i . '" style="width:55px">';
		$html .= '<option value="all">ALL</option>';

		foreach ($currencies as $currency) {
			$html .= '<option value="' . $currency['id_currency'] . '"' . (isset($curr[$i - 1]) && $curr[$i - 1] == $currency['id_currency'] ? 'selected="selected"' : '') . '>' . strtoupper($currency['iso_code']) . '</option>';
		}

		return $html .= '</select>';
	}

	public function hookDisplayHeader() {
		
		if (!isset($this->context->controller->php_self) || $this->context->controller->php_self != 'product')
			return;
		$this->context->controller->addCSS(($this->_path) . 'productpageadverts.css', 'all');
		$this->context->controller->addJS(($this->_path).'productpageadvertsfront.js');
	}

}
?>