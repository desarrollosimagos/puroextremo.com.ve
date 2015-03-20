<?php
/*
* 2007-2013 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2013 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

/**
 * @since 1.5.0
 * @version 1.2 (2012-03-14)
 */

if (!defined('_PS_VERSION_'))
	exit;

include_once(_PS_MODULE_DIR_.'advancedslider/AdvancedSlide.php');

class AdvancedSlider extends Module
{
	private $_html = '';

	public function __construct()
	{
		$this->name = 'advancedslider';
		$this->tab = 'front_office_features';
		$this->version = '1.0';
		$this->author = 'IQIT-COMMERCE.COM';
		$this->need_instance = 0;
		$this->secure_key = Tools::encrypt($this->name);

		parent::__construct();

		$this->displayName = $this->l('Advanced Image slider for your homepage.');
		$this->description = $this->l('Adds an advanced image slider to your homepage.');
	}

	/**
	 * @see Module::install()
	 */
	public function install()
	{
		/* Adds Module */
		if (parent::install() && $this->registerHook('displayHome') &&  $this->registerHook('maxSlideshow')  && $this->registerHook('actionShopDataDuplication') && $this->registerHook('displayHeader'))
		{
			/* Sets up configuration */
			$res = Configuration::updateValue('advancedslideR_HEIGHT', '500');
			$res &= Configuration::updateValue('advancedslideR_width', '0');
			$res &= Configuration::updateValue('advancedslideR_SPEED', '500');
			$res &= Configuration::updateValue('advancedslideR_shadow', '0');
			$res &= Configuration::updateValue('advancedslideR_PAUSE', '3000');
			/* Creates tables */
			$res &= $this->createTables();

			/* Adds samples */
			if ($res)
				$this->installSamples();

			return $res;
		}
		return false;
	}

	/**
	 * Adds samples
	 */
	private function installSamples()
	{
		$languages = Language::getLanguages(false);
		for ($i = 1; $i <= 2; ++$i)
		{
			$slide = new AdvancedSlide();
			$slide->position = $i;
			$slide->active = 1;
			$slide->image = 'sample-'.$i.'.jpg';
			
			$slide->text_zindex = 2;
			$slide->text_delay = 0;
			
			$slide->b_text_left = -20;
			$slide->b_text_top = -20;
			$slide->b_text_rotation = 45;
			$slide->b_text_duration = 1000;
	
			$slide->text_left = 8;
			$slide->text_top = 35;
			$slide->text_rotation = 0;
			$slide->text_duration = 1000;
	
			$slide->a_text_left = -20;
			$slide->a_text_top = 20 ;
			$slide->a_text_rotation = 45 ;
			$slide->a_text_duration = 1000;
			
			foreach ($languages as $language)
			{
				$slide->title[$language['id_lang']] = 'SAMPLE SLIDE INFORMATION '.$i;
				$slide->description[$language['id_lang']] = 'This is a sample picture, This is a sample picture for you purposes you can not use it';
				$slide->legend[$language['id_lang']] = 'SPECIAL INFORMATION HERE '.$i;
				$slide->url[$language['id_lang']] = 'http://www.prestashop.com';
			}
			$slide->add();
			
			Db::getInstance()->execute("INSERT INTO `"._DB_PREFIX_."advancedslider_slides_images` (`id_advancedslider_slides`, `id_image`, `image`, `zindex`, `delay`, `b_left`, `b_top`, `b_rotation`, `b_duration`, `left`, `top`, `rotation`, `duration`, `a_left`, `a_top`, `a_rotation`, `a_duration`) VALUES
(".$i.", '', 'Untitled-1.png', 1, 0, 60, -60, 0, 1000, 60, 18, 0, 1000, 60, 110, 0, 1000),
(".$i.", '', 'Untitled-2.png', 1, 1000, 83, 110, 0, 1000, 83, 65, 0, 1000, 83, -60, 0, 1000),
(".$i.", '', 'Untitled-3.png', 1, 2000, 50, 110, 0, 1000, 50, 45, 0, 1000, 50, -60, 0, 1000);
		");
			
			
		}
		$this->generateCSS();
	}

	/**
	 * @see Module::uninstall()
	 */
	public function uninstall()
	{
		/* Deletes Module */
		if (parent::uninstall())
		{
			/* Deletes tables */
			$res = $this->deleteTables();
			/* Unsets configuration */
			$res &= Configuration::deleteByName('advancedslideR_HEIGHT');
			$res &= Configuration::deleteByName('advancedslideR_width');
			$res &= Configuration::deleteByName('advancedslideR_SPEED');
			$res &= Configuration::deleteByName('advancedslideR_shadow');
			$res &= Configuration::deleteByName('advancedslideR_PAUSE');
			return $res;
		}
		return false;
	}

	/**
	 * Creates tables
	 */
	protected function createTables()
	{
		/* Slides */
		$res = (bool)Db::getInstance()->execute('
			CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'advancedslider` (
				`id_advancedslider_slides` int(10) unsigned NOT NULL AUTO_INCREMENT,
				`id_shop` int(10) unsigned NOT NULL,
				PRIMARY KEY (`id_advancedslider_slides`, `id_shop`)
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
		');

		/* Slides configuration */
		$res &= Db::getInstance()->execute('
			CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'advancedslider_slides` (
			  `id_advancedslider_slides` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `position` int(10) unsigned NOT NULL DEFAULT \'0\',
			  `active` tinyint(1) unsigned NOT NULL DEFAULT \'0\',
			  `text_zindex` int(10)  NOT NULL DEFAULT \'0\',
			  `text_delay` int(10)  NOT NULL DEFAULT \'0\',
			  `b_text_left` int(10)  NOT NULL DEFAULT \'0\',
			  `b_text_top` int(10)  NOT NULL DEFAULT \'0\',
			  `b_text_rotation` int(10)  NOT NULL DEFAULT \'0\',
			  `b_text_duration` int(10)  NOT NULL DEFAULT \'0\',
			  `text_left` int(10)  NOT NULL DEFAULT \'0\',
			  `text_top` int(10) NOT NULL DEFAULT \'0\',
			  `text_rotation` int(10)  NOT NULL DEFAULT \'0\',
			  `text_duration` int(10)  NOT NULL DEFAULT \'0\',
			  `a_text_left` int(10)  NOT NULL DEFAULT \'0\',
			  `a_text_top` int(10)  NOT NULL DEFAULT \'0\',
			  `a_text_rotation` int(10)  NOT NULL DEFAULT \'0\',
			  `a_text_duration` int(10)  NOT NULL DEFAULT \'0\',	
			  `image` varchar(255) NULL,  
			  `h_color` varchar(255) NULL,
			  `h_bg` varchar(255) NULL,
			  `d_color` varchar(255) NULL,
			  `d_bg` varchar(255) NULL,
			  PRIMARY KEY (`id_advancedslider_slides`)
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
		');
		
		$res &= Db::getInstance()->execute('
			CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'advancedslider_slides_images` (
			  `id_advancedslider_slides` int(10) unsigned NOT NULL,
			  `id_image` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `image` varchar(255) NOT NULL,
			  `zindex` int(10)  NOT NULL DEFAULT \'0\',
			  `delay` int(10)  NOT NULL DEFAULT \'0\',
			  `b_left` int(10)  NOT NULL DEFAULT \'0\',
			  `b_top` int(10)  NOT NULL DEFAULT \'0\',
			  `b_rotation` int(10)  NOT NULL DEFAULT \'0\',
			  `b_duration` int(10)  NOT NULL DEFAULT \'0\',
			  `left` int(10)  NOT NULL DEFAULT \'0\',
			  `top` int(10)  NOT NULL DEFAULT \'0\',
			  `rotation` int(10)  NOT NULL DEFAULT \'0\',
			  `duration` int(10)  NOT NULL DEFAULT \'0\',
			  `a_left` int(10)  NOT NULL DEFAULT \'0\',
			  `a_top` int(10)  NOT NULL DEFAULT \'0\',
			  `a_rotation` int(10)  NOT NULL DEFAULT \'0\',
			  `a_duration` int(10)  NOT NULL DEFAULT \'0\',	  
			   PRIMARY KEY (`id_image`)
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
		');

		/* Slides lang configuration */
		$res &= Db::getInstance()->execute('
			CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'advancedslider_slides_lang` (
			  `id_advancedslider_slides` int(10) unsigned NOT NULL,
			  `id_lang` int(10) unsigned NOT NULL,
			  `title` varchar(255) NOT NULL,
			  `description` text NOT NULL,
			  `legend` varchar(255) NOT NULL,
			  `url` varchar(255) NOT NULL,
			  PRIMARY KEY (`id_advancedslider_slides`,`id_lang`)
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
		');

		return $res;
	}



	/**
	 * deletes tables
	 */
	protected function deleteTables()
	{
		$slides = $this->getSlides();
		foreach ($slides as $slide)
		{
			$to_del = new AdvancedSlide($slide['id_slide']);
			$to_del->delete();
		}
		return Db::getInstance()->execute('
			DROP TABLE IF EXISTS `'._DB_PREFIX_.'advancedslider`, `'._DB_PREFIX_.'advancedslider_slides_images`, `'._DB_PREFIX_.'advancedslider_slides`, `'._DB_PREFIX_.'advancedslider_slides_lang`;
		');
	}

	public function getContent()
	{
		$this->_html .= $this->headerHTML();
		$this->_html .= '<h2>'.$this->displayName.'.</h2>';

		/* Validate & process */
		if (Tools::isSubmit('submitSlide') || Tools::isSubmit('delete_id_slide') ||
			Tools::isSubmit('submitSlider') ||
			Tools::isSubmit('changeStatus'))
		{
			if ($this->_postValidation())
				$this->_postProcess();
			$this->_displayForm();
		}
		elseif (Tools::isSubmit('addSlide') || (Tools::isSubmit('id_slide') && $this->slideExists((int)Tools::getValue('id_slide'))))
			$this->_displayAddForm();
		else
			$this->_displayForm();

		return $this->_html;
	}

	private function _displayForm()
	{
		/* Gets Slides */
		$slides = $this->getSlides();

		/* Begin fieldset slider */
		$this->_html .= '
		<fieldset>
			<legend><img src="'._PS_BASE_URL_.__PS_BASE_URI__.'modules/'.$this->name.'/logo.gif" alt="" /> '.$this->l('Slider configuration').'</legend>';
		/* Begin form */
		$this->_html .= '<form action="'.Tools::safeOutput($_SERVER['REQUEST_URI']).'" method="post">';
		/* Height field */
		$this->_html .= '
			<label>'.$this->l('Height:').'</label>
			<div class="margin-form">
				<input type="text" name="advancedslideR_HEIGHT" id="height" size="3" value="'.Tools::safeOutput(Configuration::get('advancedslideR_HEIGHT')).'" /> px
			</div>';
				/* width field */
			$this->_html .= '	<label>' . $this->l('Slideshow width:') . '</label>
				<div class="margin-form">
				<input type="radio" name="advancedslideR_width" id="advancedslideR_width_1" value="1" ' . ((Configuration::get('advancedslideR_width') == 1) ? 'checked="checked" ' : '') . '/>
					<label class="t" for="advancedslideR_width_1"> <img src="../img/admin/enabled.gif" alt="' . $this->l('Full width') . '" title="' . $this->l('Full width') . '" />' . $this->l('Full width') . '</label>
					<input type="radio" name="advancedslideR_width" id="advancedslideR_width_0" value="0" ' . ((Configuration::get('advancedslideR_width') == 0) ? 'checked="checked" ' : '') . '/>
					<label class="t" for="advancedslideR_width_0"> <img src="../img/admin/disabled.gif" alt="' . $this->l('Content width') . '" title="' . $this->l('Content width') . '" />' . $this->l('Content width') . '</label>
				</div>';
				
							/* shadow field */
			$this->_html .= '	<label>' . $this->l('Slideshow inner shadow:') . '</label>
				<div class="margin-form">
				<input type="radio" name="advancedslideR_shadow" id="advancedslideR_shadow_1" value="1" ' . ((Configuration::get('advancedslideR_shadow') == 1) ? 'checked="checked" ' : '') . '/>
					<label class="t" for="advancedslideR_shadow_1"> <img src="../img/admin/enabled.gif" alt="' . $this->l('Full width') . '" title="' . $this->l('Enable') . '" />' . $this->l('Enable') . '</label>
					<input type="radio" name="advancedslideR_shadow" id="advancedslideR_shadow_0" value="0" ' . ((Configuration::get('advancedslideR_shadow') == 0) ? 'checked="checked" ' : '') . '/>
					<label class="t" for="advancedslideR_shadow_0"> <img src="../img/admin/disabled.gif" alt="' . $this->l('Content width') . '" title="' . $this->l('Disable') . '" />' . $this->l('Disable') . '</label>
				</div>';
			
		/* Save */
		$this->_html .= '
		<div class="margin-form">
			<input type="submit" class="button" name="submitSlider" value="'.$this->l('Save').'" />
		</div>';
		/* End form */
		$this->_html .= '</form>';
		/* End fieldset slider */
		$this->_html .= '</fieldset>';

		$this->_html .= '<br /><br />';

		/* Begin fieldset slides */
		$this->_html .= '
		<fieldset>
			<legend><img src="'._PS_BASE_URL_.__PS_BASE_URI__.'modules/'.$this->name.'/logo.gif" alt="" /> '.$this->l('Slides configuration').'</legend>
			<strong>
				<a href="'.AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'&addSlide">
					<img src="'._PS_ADMIN_IMG_.'add.gif" alt="" /> '.$this->l('Add Slide').'
				</a>
			</strong>';

		/* Display notice if there are no slides yet */
		if (!$slides)
			$this->_html .= '<p style="margin-left: 40px;">'.$this->l('You have not yet added any slides.').'</p>';
		else /* Display slides */
		{
			$this->_html .= '
			<div id="slidesContent" style="width: 400px; margin-top: 30px;">
				<ul id="slides">';

			foreach ($slides as $slide)
			{
				$this->_html .= '
					<li id="slides_'.$slide['id_slide'].'">
						<strong>#'.$slide['id_slide'].'</strong> '.$slide['title'].'
						<p style="float: right">'.
							$this->displayStatus($slide['id_slide'], $slide['active']).'
							<a href="'.AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'&id_slide='.(int)($slide['id_slide']).'" title="'.$this->l('Edit').'"><img src="'._PS_ADMIN_IMG_.'edit.gif" alt="" /></a>
							<a href="'.AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'&delete_id_slide='.(int)($slide['id_slide']).'" title="'.$this->l('Delete').'"><img src="'._PS_ADMIN_IMG_.'delete.gif" alt="" /></a>
						</p>
					</li>';
			}
			$this->_html .= '</ul></div>';
		}
		// End fieldset
		$this->_html .= '</fieldset>';
	}

	private function _displayAddForm()
	{
		$this -> context -> controller -> addJS(_PS_JS_DIR_ . 'jquery/plugins/jquery.colorpicker.js');
		$this -> context -> controller -> addJS(($this -> _path) . 'admin.js');
		
		$this->context->controller->addJqueryUI('ui.draggable');
		$this->context->controller->addJqueryUI('ui.tabs');

		$this -> context -> controller -> addJS(($this -> _path) . 'fileuploader.js');
		/* Sets Slide : depends if edited or added */
		$slide = null;
		if (Tools::isSubmit('id_slide') && $this->slideExists((int)Tools::getValue('id_slide')))
			$slide = new AdvancedSlide((int)Tools::getValue('id_slide'));
		/* Checks if directory is writable */
		if (!is_writable('.'))
			$this->adminDisplayWarning(sprintf($this->l('Modules %s must be writable (CHMOD 755 / 777)'), $this->name));

		/* Gets languages and sets which div requires translations */
		$id_lang_default = (int)Configuration::get('PS_LANG_DEFAULT');
		$languages = Language::getLanguages(false);
		$divLangName = 'image¤title¤url¤legend¤description';
		$this->_html .= '<script type="text/javascript">id_language = Number('.$id_lang_default.');</script>';
		$imgwidth ="999";
		if(Configuration::get('THED_BIG_RESPONSIVE'))
		$imgwidth = "1240";

		/* Form */
		$this->_html .= '
		<link href="'._MODULE_DIR_.'advancedslider/fileuploader.css" rel="stylesheet" type="text/css" media="all" />
<link href="'._MODULE_DIR_.'advancedslider/admin.css" rel="stylesheet" type="text/css" media="all" />
		<form action="'.Tools::safeOutput($_SERVER['REQUEST_URI']).'" method="post" enctype="multipart/form-data">';

		/* Fieldset Upload */
		$this->_html .= '
		<fieldset class="width4">
			<br />
			<legend><img src="'._PS_ADMIN_IMG_.'add.gif" alt="" />1 - '.$this->l('Slide config').'</legend>';
		/* Image */
		$this->_html .= '<label>'.$this->l('Slide background image:').' * </label><div class="margin-form">
		Size: width - '.(Configuration::get('advancedslideR_width') ? '1920' : $imgwidth).'px; height - '.Configuration::get('advancedslideR_HEIGHT').'px
		';
		
	
			$this->_html .= '<input type="file" name="image_bg" id="image_bg" size="30" value="'.(isset($slide->image) ? $slide->image : '').'"/>';
			/* Sets image as hidden in case it does not change */
			if ($slide && $slide->image)
				$this->_html .= '<input type="hidden" name="image_old_bg" value="'.($slide->image).'" id="image_old_bg" />';
			/* Display image */
			if ($slide && $slide->image)
				$this->_html .= '<input type="hidden" name="has_picture" value="1" /><br class="clear"/><br /><img src="'.__PS_BASE_URI__.'modules/'.$this->name.'/images/'.$slide->image.'" width="100%" alt=""/>';
		
		
				/* Active */
		$this->_html .= '
		</div><br class="clear"/><br /><label for="active_on">'.$this->l('Active:').'</label>
		<div class="margin-form">
			<img src="../img/admin/enabled.gif" alt="Yes" title="Yes" />
			<input type="radio" name="active_slide" id="active_on" '.(($slide && (isset($slide->active) && (int)$slide->active == 0)) ? '' : 'checked="checked" ').' value="1" />
			<label class="t" for="active_on">'.$this->l('Yes').'</label>
			<img src="../img/admin/disabled.gif" alt="No" title="No" style="margin-left: 10px;" />
			<input type="radio" name="active_slide" id="active_off" '.(($slide && (isset($slide->active) && (int)$slide->active == 0)) ? 'checked="checked" ' : '').' value="0" />
			<label class="t" for="active_off">'.$this->l('No').'</label>
		</div>';

		
		/* End Fieldset Upload */
		$this->_html .= '</fieldset><br /><br />';

		/* Fieldset edit/add */
		$this->_html .= '<fieldset class="width4">';
		$this->_html .= '<legend><img src="'._PS_BASE_URL_.__PS_BASE_URI__.'modules/'.$this->name.'/logo.gif" alt="" /> 2 - '.$this->l('Slide text').'</legend>';
		/* Sets id slide as hidden */
		if ($slide && Tools::getValue('id_slide'))
			$this->_html .= '<input type="hidden" name="id_slide" value="'.$slide->id.'" id="id_slide" />';
		/* Sets position as hidden */
		$this->_html .= '<input type="hidden" name="position" value="'.(($slide != null) ? ($slide->position) : ($this->getNextPosition())).'" id="position" />';

		/* Form content */
		/* Title */
		$this->_html .= '<br /><label>'.$this->l('Title:').'</label><div class="margin-form">';
		foreach ($languages as $language)
		{
			$this->_html .= '
					<div class="titleInputWrap" id="title_'.$language['id_lang'].'" style="display: '.($language['id_lang'] == $id_lang_default ? 'block' : 'none').';float: left;">
						<input type="text" class="titleInput" name="title_'.$language['id_lang'].'" id="title_'.$language['id_lang'].'" size="30" value="'.(isset($slide->title[$language['id_lang']]) ? $slide->title[$language['id_lang']] : '').'"/>
					</div>';
		}
		$this->_html .= $this->displayFlags($languages, $id_lang_default, $divLangName, 'title', true);
		$this->_html .= '</div><br /><br />';

		/* URL */
		$this->_html .= '<label>'.$this->l('URL:').'</label><div class="margin-form">';
		foreach ($languages as $language)
		{
			$this->_html .= '
					<div id="url_'.$language['id_lang'].'" style="display: '.($language['id_lang'] == $id_lang_default ? 'block' : 'none').';float: left;">
						<input type="text" name="url_'.$language['id_lang'].'" id="url_'.$language['id_lang'].'" size="30" value="'.(isset($slide->url[$language['id_lang']]) ? $slide->url[$language['id_lang']] : '').'"/>
					</div>';
		}
		$this->_html .= $this->displayFlags($languages, $id_lang_default, $divLangName, 'url', true);
		$this->_html .= '</div><br /><br />';

		/* Legend */
		$this->_html .= '<label>'.$this->l('Subtitle:').'</label><div class="margin-form">';
		foreach ($languages as $language)
		{
			$this->_html .= '
					<div  class="subtitleInputWrap" id="legend_'.$language['id_lang'].'" style="display: '.($language['id_lang'] == $id_lang_default ? 'block' : 'none').';float: left;">
						<input type="text" class="subtitleInput" name="legend_'.$language['id_lang'].'" id="legend_'.$language['id_lang'].'" size="30" value="'.(isset($slide->legend[$language['id_lang']]) ? $slide->legend[$language['id_lang']] : '').'"/>
					</div>';
		}
		$this->_html .= $this->displayFlags($languages, $id_lang_default, $divLangName, 'legend', true);
		$this->_html .= '</div><br /><br />';

		/* Description */
		$this->_html .= '
		<label>'.$this->l('Description:').' </label>
		<div class="margin-form">';
		foreach ($languages as $language)
		{
			$this->_html .= '<div class="descInputWrap" id="description_'.$language['id_lang'].'" style="display: '.($language['id_lang'] == $id_lang_default ? 'block' : 'none').';float: left;">
				<textarea name="description_'.$language['id_lang'].'" class="descInput" rows="10" cols="29">'.(isset($slide->description[$language['id_lang']]) ? $slide->description[$language['id_lang']] : '').'</textarea>
			</div>';
		}
		$this->_html .= $this->displayFlags($languages, $id_lang_default, $divLangName, 'description', true);
		$this->_html .= '</div><div class="clear"></div><br />';
		
		
				/* Colors */
		
		$this->_html .= '<label style="clear: both;">' . $this->l('Custom headings color') . '</label>
			<div class="margin-form">
				<input type="color" name="h_color" onchange="changeSliderColor();" id="h_color" class="colorpicker" data-hex="true" value="' . (isset($slide->h_color) ? $slide->h_color : '') . '" />
					<p class="clear">' . $this->l('Leave empty if you want to keep default.') . '</p>
				</div>
				
					<label style="clear: both;">' . $this->l('Custom headings background') . '</label>
			<div class="margin-form">
				<input type="color" name="h_bg"   onchange="changeSliderColor();" id="h_bg" class="colorpicker" data-hex="true" value="' . (isset($slide->h_bg) ? $slide->h_bg : '') . '" />
					<p class="clear">' . $this->l('Leave empty if you want to keep default(transparent)') . '</p>
				</div>
				
				<label style="clear: both;">' . $this->l('Custom description color') . '</label>
			<div class="margin-form">
				<input type="color" name="d_color" onchange="changeSliderColor();" id="d_color" class="colorpicker" data-hex="true" value="' . (isset($slide->d_color) ? $slide->d_color : '') . '" />
					<p class="clear">' . $this->l('Leave empty if you want to keep default') . '</p>
				</div>
				
					<label style="clear: both;">' . $this->l('Custom description background') . '</label>
			<div class="margin-form">
				<input type="color" name="d_bg" onchange="changeSliderColor();"  id="d_bg" class="colorpicker" data-hex="true" value="' . (isset($slide->d_bg) ? $slide->d_bg : '') . '" />
					<p class="clear">' . $this->l('Leave empty if you want to keep default(transparent)') . '</p>
				</div>
				
				';
		/* Conf */
		$this->_html .= '
		<label>'.$this->l('Config:').' </label>
		<div class="margin-form">
		'.$this->l('Z-index(layer)').'
		<input type="text" name="text_zindex" id="text_zindex" size="3" value="'.(($slide && (isset($slide->text_zindex))) ? $slide->text_zindex : '').'"/>
		
		'.$this->l('Delay').'
		<input type="text" name="text_delay" id="text_delay" size="3" value="'.(($slide && (isset($slide->text_delay))) ? $slide->text_delay : '').'"/>ms</div>';
		
		
		/* Before animation */
		$this->_html .= '
		<label>'.$this->l('Position before animation:').' </label>
		<div class="margin-form">
		'.$this->l('Left').'
		<input type="text" name="b_text_left" id="b_text_left" size="3" value="'.(($slide && (isset($slide->b_text_left))) ? $slide->b_text_left : '').'"/>%
		
		'.$this->l('Top').'
		<input type="text" name="b_text_top" id="b_text_top" size="3" value="'.(($slide && (isset($slide->b_text_top))) ? $slide->b_text_top : '').'"/>%
		
		'.$this->l('Rotation').'
		<input type="text" name="b_text_rotation" id="b_text_rotation" size="3" value="'.(($slide && (isset($slide->b_text_rotation))) ? $slide->b_text_rotation : '').'"/>&deg;
		
		'.$this->l('Duration').'
		<input type="text" name="b_text_duration" id="b_text_duration" size="3" value="'.(($slide && (isset($slide->b_text_duration))) ? $slide->b_text_duration : '').'"/>ms
		
		
		</div>';
		
		
		/* Shown position */
		$this->_html .= '
		<label>'.$this->l('Position of showed text:').' </label>
		<div class="margin-form">
		'.$this->l('Left').'
		<input type="text" name="text_left" id="text_left" size="3" value="'.(($slide && (isset($slide->text_left))) ? $slide->text_left : '').'"/>%
		
		'.$this->l('Top').'
		<input type="text" name="text_top" id="text_top" size="3" value="'.(($slide && (isset($slide->text_top))) ? $slide->text_top : '').'"/>%
		
		'.$this->l('Rotation').'
		<input type="text" name="text_rotation" id="text_rotation" size="3" value="'.(($slide && (isset($slide->text_rotation))) ? $slide->text_rotation : '').'"/>&deg;
		
		'.$this->l('Duration').'
		<input type="text" name="text_duration" id="text_duration" size="3" value="'.(($slide && (isset($slide->text_duration))) ? $slide->text_duration : '').'"/>ms
		
		
		</div>';
		
		/* After animation */
		$this->_html .= '
		<label>'.$this->l('Position after animation:').' </label>
		<div class="margin-form">
		'.$this->l('Left').'
		<input type="text" name="a_text_left" id="a_text_left" size="3" value="'.(($slide && (isset($slide->a_text_left))) ? $slide->a_text_left : '').'"/>%
		
		'.$this->l('Top').'
		<input type="text" name="a_text_top" id="a_text_top" size="3" value="'.(($slide && (isset($slide->a_text_top))) ? $slide->a_text_top : '').'"/>%
		
		'.$this->l('Rotation').'
		<input type="text" name="a_text_rotation" id="a_text_rotation" size="3" value="'.(($slide && (isset($slide->a_text_rotation))) ? $slide->a_text_rotation : '').'"/>&deg;
		
		'.$this->l('Duration').'
		<input type="text" name="a_text_duration" id="a_text_duration" size="3" value="'.(($slide && (isset($slide->a_text_duration))) ? $slide->a_text_duration : '').'"/>ms
		
		
		</div>';
		
		
		
		
		

		/* End of fieldset & form */
		$this->_html .= '
			<p>*'.$this->l('Required fields').'</p>
			</fieldset>';
			
		/* Slide images*/
if (Tools::isSubmit('id_slide') && $this->slideExists((int)Tools::getValue('id_slide')))
{
			
			$this->_html .= '

		    <script type="text/javascript">  
			var upbutton = "Upload";
	  
			$(document).ready(function(){   
              
            var uploader = new qq.FileUploader({
                element: document.getElementById("file-uploader-demo1"),
                action: "'._MODULE_DIR_.'advancedslider/ajax_advancedsliderUpload.php",
				allowedExtensions: [\'jpg\', \'jpeg\', \'png\', \'gif\'],    
       messages: {
            typeError: "{file} has invalid extension. Only {extensions} are allowed.",
            sizeError: "{file} is too large, maximum file size is {sizeLimit}.",
            minSizeError: "{file} is too small, minimum file size is {minSizeLimit}.",
            emptyError: "{file} is empty, please select files again without it.",
            allowedExtensionsError : "{file} is not allowed.",
            onLeave: "The files are being uploaded, if you leave now the upload will be cancelled."
        },
        showMessage: function (message) {
            alert(message);
        },
                debug: true,
					params: {
					action : "submitUploadImage",	
					id_slide: '.$slide->id.',
				},
				onComplete: function(id, fileName, responseJSON){console.log(responseJSON);
								var id_image = responseJSON[\'id_image\'];
								var image_code = responseJSON[\'filename\'];
								
	$("#imagesContainer").append(\' <div id="slideImage_\' + id_image + \'" class="slideImage" data-imageid="\' + id_image + \'"> <div class="margin-form"><img src="'.$this -> _path.'uploads/\'+ image_code + \'" class="pimage" /></div> <input type="hidden" name="slideImageIds[]" value="\' + id_image + \'"><label>'.htmlspecialchars($this->l('Config:'), ENT_QUOTES).'</label><div class=margin-form>'.htmlspecialchars($this->l('Z-index(layer)'), ENT_QUOTES).'<input name="image_\' + id_image + \'_zindex" id="image_\' + id_image + \'_zindex" size=3 value=1> '.htmlspecialchars($this->l('Delay'), ENT_QUOTES).'<input name="image_\' + id_image + \'_delay" id="image_\' + id_image + \'_delay" size=3 value=0>ms</div><label>'.htmlspecialchars($this->l('Position before animation:'), ENT_QUOTES).' </label><div class="margin-form"> '.htmlspecialchars($this->l('Left'), ENT_QUOTES).' <input type="text" name="b_image_\' + id_image + \'_left" id="b_image_\' + id_image + \'_left" size="3" value="0"/>% '.htmlspecialchars($this->l('Top'), ENT_QUOTES).' <input type="text" name="b_image_\' + id_image + \'_top" id="b_image_\' + id_image + \'_top" size="3" value="0"/>% '.htmlspecialchars($this->l('Rotation'), ENT_QUOTES).' <input type="text" name="b_image_\' + id_image + \'_rotation" id="b_image_\' + id_image + \'_rotation" size="3" value="0"/>&deg; '.htmlspecialchars($this->l('Duration'), ENT_QUOTES).' <input type="text" name="b_image_\' + id_image + \'_duration" id="b_image_\' + id_image + \'_duration" size="3" value="1000"/>ms</div><label>'.htmlspecialchars($this->l('Position of showed image'), ENT_QUOTES).' </label><div class="margin-form"> '.htmlspecialchars($this->l('Left'), ENT_QUOTES).' <input type="text" name="image_\' + id_image + \'_left" id="image_\' + id_image + \'_left" size="3" value="0"/>% '.htmlspecialchars($this->l('Top'), ENT_QUOTES).' <input type="text" name="image_\' + id_image + \'_top" id="image_\' + id_image + \'_top" size="3" value="0"/>% '.htmlspecialchars($this->l('Rotation'), ENT_QUOTES).' <input type="text" name="image_\' + id_image + \'_rotation" id="image_\' + id_image + \'_rotation" size="3" value="0"/>&deg; '.htmlspecialchars($this->l('Duration'), ENT_QUOTES).' <input type="text" name="image_\' + id_image + \'_duration" id="image_\' + id_image + \'_duration" size="3" value="1000"/>ms</div><label>'.htmlspecialchars($this->l('Position after animation:'), ENT_QUOTES).' </label><div class="margin-form"> '.htmlspecialchars($this->l('Left'), ENT_QUOTES).' <input type="text" name="a_image_\' + id_image + \'_left" id="a_image_\' + id_image + \'_left" size="3" value="0"/>% '.htmlspecialchars($this->l('Top'), ENT_QUOTES).' <input type="text" name="a_image_\' + id_image + \'_top" id="a_image_\' + id_image + \'_top" size="3" value="0"/>% '.htmlspecialchars($this->l('Rotation'), ENT_QUOTES).' <input type="text" name="a_image_\' + id_image + \'_rotation" id="a_image_\' + id_image + \'_rotation" size="3" value="0"/>&deg; '.htmlspecialchars($this->l('Duration'), ENT_QUOTES).' <input type="text" name="a_image_\' + id_image + \'_duration" id="a_image_\' + id_image + \'_duration" size="3" value="1000"/>ms<br class="clear"/><br/><a class="deleteImage" href="'._MODULE_DIR_.'advancedslider/ajax_advancedsliderUpload.php?&action=deleteImage&id=\' + id_image + \'"><img src="../img/admin/delete.gif" alt="" title="" style="cursor: pointer"/>Delete image </a></div></div> \');

	
	$("#live_beforeWrapper").append(\' <div class="dragElement" id="before_image_\' + id_image + \'" data-imageid="\' + id_image + \'" data-atype="before"><div class="dragHandle"><img src="'.$this -> _path.'uploads/\'+ image_code + \'" class="dragElement_inside" /></div><div class="rotateButton"><img src="'.$this -> _path.'images/rotate.png" /></div></div> \');
	$("#live_showedWrapper").append(\' <div class="dragElement" id="showed_image_\' + id_image + \'" data-imageid="\' + id_image + \'" data-atype="showed"><div class="dragHandle"><img src="'.$this -> _path.'uploads/\'+ image_code + \'" class="dragElement_inside" /></div><div class="rotateButton"><img src="'.$this -> _path.'images/rotate.png" /></div></div> \');
	
	$("#live_afterWrapper").append(\' <div class="dragElement" id="after_image_\' + id_image + \'" data-imageid="\' + id_image + \'" data-atype="after"><div class="dragHandle"><img src="'.$this -> _path.'uploads/\'+ image_code + \'" class="dragElement_inside" /></div><div class="rotateButton"><img src="'.$this -> _path.'images/rotate.png" /></div></div> \');	
	 $(\'body\').enableDragging();		
	 initImages();
				
				
				},
				onSubmit: function(id, fileName){$(".qq-upload-list").fadeIn();},
            });           
       });      
 
    </script>  
				<br />
		<fieldset class="width4">
			<br />
			<legend><img src="'._PS_ADMIN_IMG_.'add.gif" alt="" />1 - '.$this->l('Slide images').'</legend>
				<label for="video_embed_code">'.$this->l('Image file').'</label>
				<div class="margin-form">
					<div id="file-uploader-demo1">		
		<noscript>			
			<p>Please enable JavaScript to use file uploader.</p>
			<!-- or put a simple form for upload here -->
		</noscript>         
	</div>
				</div>
									<script type="text/javascript">
						

			$(".deleteImage").live("click", function(event){
			event.preventDefault();

      			 var url = this.href;
               	 $.ajax({
                        url: url,
                        dataType: "json",
                        success: function(response) {
                                $("#slideImage_"+response).remove();
								$("#before_image_"+response).remove();
								$("#showed_image_"+response).remove();
								$("#after_image_"+response).remove();
								
								
                        }
                });
				});
		</script>
			
				<div id="imagesContainer">';
				
				$slideImages = $slide->getImages();				
				foreach ($slideImages as $slideImage)
		{
			
			
			
			$this->_html .= '<div id="slideImage_'.$slideImage['id_image'].'" class="slideImage" data-imageid="'.$slideImage['id_image'].'">
				<div class="margin-form"><img src="'.$this -> _path.'uploads/'.$slideImage['image'].'" class="pimage" /></div>';
			$this->_html .= '<input type="hidden" name="slideImageIds[]" value="'.$slideImage['id_image']. '">';
		
		/* Conf */
		$this->_html .= '
		<label>'.$this->l('Config:').' </label>
		<div class="margin-form">
		'.$this->l('Z-index(layer)').'
		<input type="text" name="image_'.$slideImage['id_image'].'_zindex" id="image_'.$slideImage['id_image'].'_zindex" size="3" value="'.$slideImage['zindex'].'"/>
		
		'.$this->l('Delay').'
		<input type="text" name="image_'.$slideImage['id_image'].'_delay" id="image_'.$slideImage['id_image'].'_delay" size="3" value="'.$slideImage['delay'].'"/>ms</div>';
		
		
		/* Before animation */

		$this->_html .= '
		<label>'.$this->l('Position before animation:').' </label>
		<div class="margin-form">
		'.$this->l('Left').'
		<input type="text" name="b_image_'.$slideImage['id_image'].'_left" id="b_image_'.$slideImage['id_image'].'_left" size="3" value="'.$slideImage['b_left'].'"/>%
		
		'.$this->l('Top').'
		<input type="text" name="b_image_'.$slideImage['id_image'].'_top" id="b_image_'.$slideImage['id_image'].'_top" size="3" value="'.$slideImage['b_top'].'"/>%
		
		'.$this->l('Rotation').'
		<input type="text" name="b_image_'.$slideImage['id_image'].'_rotation" id="b_image_'.$slideImage['id_image'].'_rotation" size="3" value="'.$slideImage['b_rotation'].'"/>&deg;
		
		'.$this->l('Duration').'
		<input type="text" name="b_image_'.$slideImage['id_image'].'_duration" id="b_image_'.$slideImage['id_image'].'_duration" size="3" value="'.$slideImage['b_duration'].'"/>ms
		
		
		</div>';
		
		
		/* Show position */
		
		$this->_html .= '
		<label>'.$this->l('Position of showed image:').' </label>
		<div class="margin-form">
		'.$this->l('Left').'
		<input type="text" name="image_'.$slideImage['id_image'].'_left" id="image_'.$slideImage['id_image'].'_left" size="3" value="'.$slideImage['left'].'"/>%
		
		'.$this->l('Top').'
		<input type="text" name="image_'.$slideImage['id_image'].'_top" id="image_'.$slideImage['id_image'].'_top" size="3" value="'.$slideImage['top'].'"/>%
		
		'.$this->l('Rotation').'
		<input type="text" name="image_'.$slideImage['id_image'].'_rotation" id="image_'.$slideImage['id_image'].'_rotation" size="3" value="'.$slideImage['rotation'].'"/>&deg;
		
		'.$this->l('Duration').'
		<input type="text" name="image_'.$slideImage['id_image'].'_duration" id="image_'.$slideImage['id_image'].'_duration" size="3" value="'.$slideImage['duration'].'"/>ms
		
		
		</div>';
		
		/* After animation */
		$this->_html .= '
		<label>'.$this->l('Position after animation:').' </label>
		<div class="margin-form">
		'.$this->l('Left').'
		<input type="text" name="a_image_'.$slideImage['id_image'].'_left" id="a_image_'.$slideImage['id_image'].'_left" size="3" value="'.$slideImage['a_left'].'"/>%
		'.$this->l('Top').'
		<input type="text" name="a_image_'.$slideImage['id_image'].'_top" id="a_image_'.$slideImage['id_image'].'_top" size="3" value="'.$slideImage['a_top'].'"/>%
		
		'.$this->l('Rotation').'
		<input type="text" name="a_image_'.$slideImage['id_image'].'_rotation" id="a_image_'.$slideImage['id_image'].'_rotation" size="3" value="'.$slideImage['a_rotation'].'"/>&deg;
		
		'.$this->l('Duration').'
		<input type="text" name="a_image_'.$slideImage['id_image'].'_duration" id="a_image_'.$slideImage['id_image'].'_duration" size="3" value="'.$slideImage['a_duration'].'"/>ms
			
			<br class="clear" /><br /><a class="deleteImage" href="'._MODULE_DIR_.'advancedslider/ajax_advancedsliderUpload.php?&action=deleteImage&id='.$slideImage['id_image'].'"><img src="../img/admin/delete.gif" alt="" title="" style="cursor: pointer" />Delete image </a>
			
	</div></div>';
		}
				
				$this->_html .= '</div>	
			</fieldset>';
		
	
	
	
}
else{
	
	$this->_html .= '
	<br />
		<fieldset class="width4">
			<br />
			<legend><img src="'._PS_ADMIN_IMG_.'add.gif" alt="" />3 - '.$this->l('Slide images').'</legend>
	<div class="margin-form">
	'.$this->l('To add images save slide first and then edit').'
		</div>	
			</fieldset>';
	
	
	}
	$slider_bg = "";
	if (Tools::isSubmit('id_slide') && $this->slideExists((int)Tools::getValue('id_slide')))
	$slider_bg = __PS_BASE_URI__.'modules/'.$this->name.'/images/'.$slide->image;
	
	$this->_html .= '<br /> <div id="tabs_animation">
	  <ul>
    <li><a href="#tabs-1">'.$this->l('Liveedit - before animation').'</a></li>
    <li><a href="#tabs-2">'.$this->l('Liveedit - showed elements').'</a></li>
    <li><a href="#tabs-3">'.$this->l('Liveedit - after animation').'</a></li>
  </ul>
	
	';		/* Liveedit - before */
	$this->_html .= '
	<div id="tabs-1">
	
		<fieldset class="width">
	<div class="liveedit_wrapper">
	<div class="liveedit_fwwrapper" style="height: '.Configuration::get('advancedslideR_HEIGHT').'px; '.(Configuration::get('advancedslideR_width') ? 'background-image: url('.$slider_bg .'); background-repeat: no-repeat; background-size: cover; border-top: 1px solid #cecece; border-bottom: 1px solid #cecece;' : '').'">
	<div id="live_beforeWrapper" class="content_liveedit_wrapper" style="width:'.$imgwidth.'px; height: '.Configuration::get('advancedslideR_HEIGHT').'px; '.(Configuration::get('advancedslideR_width') ? 'border-top: none; border-bottom: none;' : 'background-image: url('.$slider_bg .'); background-repeat: no-repeat;').'">
	<div class="textWrapper dragTextElement" id="before_text" data-atype="before">
	<div class="dragHandle clearfix">
	
		<h2>aaaaa</h2>
                            <h3>aaaaaaaaa</h3>
							<p class="desc">aaaaaaaaa</p>
	</div>
	<div class="rotateButton2"><img src="'.$this -> _path.'images/rotate.png"  /></div>
	</div>
	';
	$slideImages =array();
	if (Tools::isSubmit('id_slide') && $this->slideExists((int)Tools::getValue('id_slide')))
	$slideImages = $slide->getImages();				
				foreach ($slideImages as $slideImage)
		{
			
	$this->_html .= '
	<div class="dragElement" id="before_image_'.$slideImage['id_image'].'"  data-imageid="'.$slideImage['id_image'].'" data-atype="before">
	<div class="dragHandle">
	<img src="'.$this -> _path.'uploads/'.$slideImage['image'].'" class="dragElement_inside" />
	</div>
	<div class="rotateButton"><img src="'.$this -> _path.'images/rotate.png"  /></div>
	</div>';	
			
	
		}
	
	$this->_html .= '
	</div>
	</div></div>
	</fieldset></div>
	';
	/* Liveedit - showed */
	
		$this->_html .= '<div id="tabs-2">
		<fieldset class="width">
	<div class="liveedit_wrapper">
	<div  class="liveedit_fwwrapper" style="height: '.Configuration::get('advancedslideR_HEIGHT').'px; '.(Configuration::get('advancedslideR_width') ? 'background-image: url('.$slider_bg .'); background-repeat: no-repeat; background-size: cover; border-top: 1px solid #cecece; border-bottom: 1px solid #cecece;' : '').'">
	<div id="live_showedWrapper" class="content_liveedit_wrapper" style="width:'.$imgwidth.'px; height: '.Configuration::get('advancedslideR_HEIGHT').'px; '.(Configuration::get('advancedslideR_width') ? 'border-top: none; border-bottom: none;' : 'background-image: url('.$slider_bg .'); background-repeat: no-repeat;').'">
	
		<div class="textWrapper dragTextElement" id="showed_text" data-atype="showed">
	<div class="dragHandle clearfix">
	
		<h2></h2>
                            <h3></h3>
							<p class="desc"></p>
	</div>
	<div class="rotateButton2"><img src="'.$this -> _path.'images/rotate.png"  /></div>
	</div>
	
	
	';
			
				foreach ($slideImages as $slideImage)
		{
			
	$this->_html .= '
	<div class="dragElement" id="showed_image_'.$slideImage['id_image'].'"  data-imageid="'.$slideImage['id_image'].'" data-atype="showed">
	<div class="dragHandle">
	<img src="'.$this -> _path.'uploads/'.$slideImage['image'].'" class="dragElement_inside" />
	</div>
	<div class="rotateButton"><img src="'.$this -> _path.'images/rotate.png"  /></div>
	</div>';	
			
	
		}
	
	$this->_html .= '
	</div>
	</div></div>
	</fieldset></div>
	';
	
	
	
	
	/* Liveedit - after */


	$this->_html .= '<div id="tabs-3">

		<fieldset class="width">

	<div class="liveedit_wrapper">
	<div class="liveedit_fwwrapper" style="height: '.Configuration::get('advancedslideR_HEIGHT').'px; '.(Configuration::get('advancedslideR_width') ? 'background-image: url('.$slider_bg .'); background-repeat: no-repeat; background-size: cover; border-top: 1px solid #cecece; border-bottom: 1px solid #cecece;' : '').'">
	<div id="live_afterWrapper" class="content_liveedit_wrapper" style="width:'.$imgwidth.'px; height: '.Configuration::get('advancedslideR_HEIGHT').'px; '.(Configuration::get('advancedslideR_width') ? 'border-top: none; border-bottom: none;' : 'background-image: url('.$slider_bg .'); background-repeat: no-repeat;').'">
		<div class="textWrapper dragTextElement" id="after_text" data-atype="after">
	<div class="dragHandle clearfix">
	
		<h2></h2>
                            <h3></h3>
							<p class="desc"></p>
	</div>
	<div class="rotateButton2"><img src="'.$this -> _path.'images/rotate.png"  /></div>
	</div>
	
	';
	
			
				foreach ($slideImages as $slideImage)
		{
			
	$this->_html .= '
	<div class="dragElement" id="after_image_'.$slideImage['id_image'].'"  data-imageid="'.$slideImage['id_image'].'" data-atype="after">
	<div class="dragHandle">
	<img src="'.$this -> _path.'uploads/'.$slideImage['image'].'" class="dragElement_inside" />
	</div>
	<div class="rotateButton"><img src="'.$this -> _path.'images/rotate.png"  /></div>
	</div>';	
			
	
		}
	
	$this->_html .= '
	</div>
	</div></div>
	</fieldset></div></div>	<br />
	';
	
	
			
				/* Save */
		$this->_html .= '
		<fieldset class="width4">
			<br />
			<legend><img src="'._PS_ADMIN_IMG_.'add.gif" alt="" />1 - '.$this->l('Save').'</legend>';
		$this->_html .= '
		<p class="center">
			<input style="min-height:26px" type="submit" class="button" name="submitSlide" value="'.$this->l('Save').'" />
			<a class="button" style="position:relative; padding:4px 3px;" href="'.AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'">'.$this->l('Cancel').'</a>
		</p></fieldset>';
		$this->_html .= '	</form>';
	}

	private function _postValidation()
	{
		$errors = array();

		/* Validation for Slider configuration */
		if (Tools::isSubmit('submitSlider'))
		{

			if (!Validate::isInt(Tools::getValue('advancedslideR_SPEED')) || !Validate::isInt(Tools::getValue('advancedslideR_PAUSE')) ||
				 !Validate::isInt(Tools::getValue('advancedslideR_HEIGHT')))
					$errors[] = $this->l('Invalid values');
		} /* Validation for status */
		elseif (Tools::isSubmit('changeStatus'))
		{
			if (!Validate::isInt(Tools::getValue('id_slide')))
				$errors[] = $this->l('Invalid slide');
		}
		/* Validation for Slide */
		elseif (Tools::isSubmit('submitSlide'))
		{
			/* Checks state (active) */
			if (!Validate::isInt(Tools::getValue('active_slide')) || (Tools::getValue('active_slide') != 0 && Tools::getValue('active_slide') != 1))
				$errors[] = $this->l('Invalid slide state');
			/* Checks position */
			if (!Validate::isInt(Tools::getValue('position')) || (Tools::getValue('position') < 0))
				$errors[] = $this->l('Invalid slide position');
			/* If edit : checks id_slide */
			if (Tools::isSubmit('id_slide'))
			{
				if (!Validate::isInt(Tools::getValue('id_slide')) && !$this->slideExists(Tools::getValue('id_slide')))
					$errors[] = $this->l('Invalid id_slide');
			}
			/* Checks title/url/legend/description/image */
			$languages = Language::getLanguages(false);
			foreach ($languages as $language)
			{
				if (Tools::strlen(Tools::getValue('title_'.$language['id_lang'])) > 255)
					$errors[] = $this->l('The title is too long.');
				if (Tools::strlen(Tools::getValue('legend_'.$language['id_lang'])) > 255)
					$errors[] = $this->l('The legend is too long.');
				if (Tools::strlen(Tools::getValue('url_'.$language['id_lang'])) > 255)
					$errors[] = $this->l('The URL is too long.');
				if (Tools::strlen(Tools::getValue('description_'.$language['id_lang'])) > 4000)
					$errors[] = $this->l('The description is too long.');
				if (Tools::strlen(Tools::getValue('url_'.$language['id_lang'])) > 0 && !Validate::isUrl(Tools::getValue('url_'.$language['id_lang'])))
					$errors[] = $this->l('The URL format is not correct.');
				if (Tools::getValue('image_'.$language['id_lang']) != null && !Validate::isFileName(Tools::getValue('image_'.$language['id_lang'])))
					$errors[] = $this->l('Invalid filename');
				if (Tools::getValue('image_old_'.$language['id_lang']) != null && !Validate::isFileName(Tools::getValue('image_old_'.$language['id_lang'])))
					$errors[] = $this->l('Invalid filename');
			}

			/* Checks title/url/legend/description for default lang */
			$id_lang_default = (int)Configuration::get('PS_LANG_DEFAULT');
			if (!Tools::isSubmit('has_picture') && (!isset($_FILES['image_bg']) || empty($_FILES['image_bg']['tmp_name'])))
				$errors[] = $this->l('The image is not set.');
			if (Tools::getValue('image_old_bg') && !Validate::isFileName(Tools::getValue('image_old_bg')))
				$errors[] = $this->l('The image is not set.');
		} /* Validation for deletion */
		elseif (Tools::isSubmit('delete_id_slide') && (!Validate::isInt(Tools::getValue('delete_id_slide')) || !$this->slideExists((int)Tools::getValue('delete_id_slide'))))
			$errors[] = $this->l('Invalid id_slide');

		/* Display errors if needed */
		if (count($errors))
		{
			$this->_html .= $this->displayError(implode('<br />', $errors));
			return false;
		}

		/* Returns if validation is ok */
		return true;
	}

	private function _postProcess()
	{
		$errors = array();

		/* Processes Slider */
		if (Tools::isSubmit('submitSlider'))
		{

			$res = Configuration::updateValue('advancedslideR_HEIGHT', (int)Tools::getValue('advancedslideR_HEIGHT'));
			$res &= Configuration::updateValue('advancedslideR_width', (int)Tools::getValue('advancedslideR_width'));
			$res &= Configuration::updateValue('advancedslideR_SPEED', (int)Tools::getValue('advancedslideR_SPEED'));
			$res &= Configuration::updateValue('advancedslideR_shadow', (int)Tools::getValue('advancedslideR_shadow'));
			$res &= Configuration::updateValue('advancedslideR_PAUSE', (int)Tools::getValue('advancedslideR_PAUSE'));
			$this->clearCache();			
			if (!$res)
				$errors[] = $this->displayError($this->l('The configuration could not be updated.'));
			$this->_html .= $this->displayConfirmation($this->l('Configuration updated'));
			$this->generateCSS();
		} /* Process Slide status */
		elseif (Tools::isSubmit('changeStatus') && Tools::isSubmit('id_slide'))
		{
			$slide = new AdvancedSlide((int)Tools::getValue('id_slide'));
			if ($slide->active == 0)
				$slide->active = 1;
			else
				$slide->active = 0;
			$res = $slide->update();
			$this->clearCache();
			$this->_html .= ($res ? $this->displayConfirmation($this->l('Configuration updated')) : $this->displayError($this->l('The configuration could not be updated.')));
		}
		/* Processes Slide */
		elseif (Tools::isSubmit('submitSlide'))
		{
			/* Sets ID if needed */
			if (Tools::getValue('id_slide'))
			{
				$slide = new AdvancedSlide((int)Tools::getValue('id_slide'));
				if (!Validate::isLoadedObject($slide))
				{
					$this->_html .= $this->displayError($this->l('Invalid id_slide'));
					return;
				}
			}
			else
				$slide = new AdvancedSlide();
			/* Sets position */
			$slide->position = (int)Tools::getValue('position');
			/* Sets active */
			$slide->active = (int)Tools::getValue('active_slide');
			
			/* Set text config */
			$slide->text_zindex = (int)Tools::getValue('text_zindex');
			$slide->text_delay = (int)Tools::getValue('text_delay');
			
			/* Set text before */
			$slide->b_text_left = (int)Tools::getValue('b_text_left');
			$slide->b_text_top = (int)Tools::getValue('b_text_top');
			$slide->b_text_rotation = (int)Tools::getValue('b_text_rotation');
			$slide->b_text_duration = (int)Tools::getValue('b_text_duration');
			
			/* Set text show */
			$slide->text_left = (int)Tools::getValue('text_left');
			$slide->text_top = (int)Tools::getValue('text_top');
			$slide->text_rotation = (int)Tools::getValue('text_rotation');
			$slide->text_duration = (int)Tools::getValue('text_duration');
			
			/* Set text after */
			$slide->a_text_left = (int)Tools::getValue('a_text_left');
			$slide->a_text_top = (int)Tools::getValue('a_text_top');
			$slide->a_text_rotation = (int)Tools::getValue('a_text_rotation');
			$slide->a_text_duration = (int)Tools::getValue('a_text_duration');
			
			$slide->h_color = Tools::getValue('h_color');
			$slide->h_bg = Tools::getValue('h_bg');
			$slide->d_color = Tools::getValue('d_color');
			$slide->d_bg =  Tools::getValue('d_bg');
			
						/* Uploads image and sets slide */
				$type = strtolower(substr(strrchr($_FILES['image_bg']['name'], '.'), 1));
				$imagesize = array();
				$imagesize = @getimagesize($_FILES['image_bg']['tmp_name']);
				if (isset($_FILES['image_bg']) &&
					isset($_FILES['image_bg']['tmp_name']) &&
					!empty($_FILES['image_bg']['tmp_name']) &&
					!empty($imagesize) &&
					in_array(strtolower(substr(strrchr($imagesize['mime'], '/'), 1)), array('jpg', 'gif', 'jpeg', 'png')) &&
					in_array($type, array('jpg', 'gif', 'jpeg', 'png')))
				{
					$temp_name = tempnam(_PS_TMP_IMG_DIR_, 'PS');
					$salt = sha1(microtime());
					if ($error = ImageManager::validateUpload($_FILES['image_bg']))
						$errors[] = $error;
					elseif (!$temp_name || !move_uploaded_file($_FILES['image_bg']['tmp_name'], $temp_name))
						return false;
					elseif (!ImageManager::resize($temp_name, dirname(__FILE__).'/images/'.Tools::encrypt($_FILES['image_bg']['name'].$salt).'.'.$type, null, null, $type))
						$errors[] = $this->displayError($this->l('An error occurred during the image upload process.'));
					if (isset($temp_name))
						@unlink($temp_name);
					$slide->image = Tools::encrypt($_FILES['image_bg']['name'].$salt).'.'.$type;
				}
				elseif (Tools::getValue('image_old_bg') != '')
					$slide->image = Tools::getValue('image_old_bg');
					
					
			
			$imagesArray = array();
			$imagesArray = Tools::getValue('slideImageIds');
			
			if(!empty($imagesArray)){
			foreach($imagesArray as $slideimage)
			{		
				
				$zindex	= Tools::getValue('image_'.$slideimage.'_zindex');
				$delay	= Tools::getValue('image_'.$slideimage.'_delay');
				
				$b_left	= Tools::getValue('b_image_'.$slideimage.'_left');
				$b_top	= Tools::getValue('b_image_'.$slideimage.'_top');
				$b_rotation = Tools::getValue('b_image_'.$slideimage.'_rotation');
				$b_duration	= Tools::getValue('b_image_'.$slideimage.'_duration');
				
				
				$left	= Tools::getValue('image_'.$slideimage.'_left');
				$top	= Tools::getValue('image_'.$slideimage.'_top');
				$rotation = Tools::getValue('image_'.$slideimage.'_rotation');
				$duration	= Tools::getValue('image_'.$slideimage.'_duration');
				
				
				$a_left	= Tools::getValue('a_image_'.$slideimage.'_left');
				$a_top	= Tools::getValue('a_image_'.$slideimage.'_top');
				$a_rotation = Tools::getValue('a_image_'.$slideimage.'_rotation');
				$a_duration = Tools::getValue('a_image_'.$slideimage.'_duration');
				
				$slide->updateSlideImage($slideimage, $zindex,  $delay,  $b_left, $b_top, $b_rotation, $b_duration, $left, $top, $rotation, $duration, $a_left, $a_top, $a_rotation, $a_duration);
			
			}
			
			}
			/* Sets each langue fields */
			$languages = Language::getLanguages(false);
			foreach ($languages as $language)
			{
				$slide->title[$language['id_lang']] = Tools::getValue('title_'.$language['id_lang']);
				$slide->url[$language['id_lang']] = Tools::getValue('url_'.$language['id_lang']);
				$slide->legend[$language['id_lang']] = Tools::getValue('legend_'.$language['id_lang']);
				$slide->description[$language['id_lang']] = Tools::getValue('description_'.$language['id_lang']);
			}

			/* Processes if no errors  */
			if (!$errors)
			{
				/* Adds */
				if (!Tools::getValue('id_slide'))
				{
					if (!$slide->add())
						$errors[] = $this->displayError($this->l('The slide could not be added.'));
				}
				/* Update */
				elseif (!$slide->update())
					$errors[] = $this->displayError($this->l('The slide could not be updated.'));
				$this->clearCache();
			}
		} /* Deletes */
		elseif (Tools::isSubmit('delete_id_slide'))
		{
			$slide = new AdvancedSlide((int)Tools::getValue('delete_id_slide'));
			$res = $slide->delete();
			$this->clearCache();
			if (!$res)
				$this->_html .= $this->displayError('Could not delete');
			else
				$this->_html .= $this->displayConfirmation($this->l('Slide deleted'));
		}

		/* Display errors if needed */
		if (count($errors))
			$this->_html .= $this->displayError(implode('<br />', $errors));
		elseif (Tools::isSubmit('submitSlide') && Tools::getValue('id_slide'))
		{	
			$this->generateCSS();
			$this->_html .= $this->displayConfirmation($this->l('Slide updated'));
		}
		elseif (Tools::isSubmit('submitSlide'))
		{
			$this->generateCSS();
			$this->_html .= $this->displayConfirmation($this->l('Slide added'));
		}
	}

	private function _prepareHook()
	{
		if (!$this->isCached('advancedslider.tpl', $this->getCacheId()))
		{

			$slides = $this->getSlideswImages(true);
			if (!$slides)
				return false;

			$this->smarty->assign('advancedslider_slides', $slides);
			
		}

		return true;
	}


	private function _prepareFwHook()
	{
		if (!$this->isCached('advancedslider-fw.tpl', $this->getCacheId()))
		{

			$slides = $this->getSlideswImages(true);
			if (!$slides)
				return false;

			$this->smarty->assign('advancedslider_slides', $slides);
			
		}

		return true;
	}
	
	
	public function hookDisplayHome()
	{
		$sswidth  = Configuration::get('advancedslideR_width');
		
		if($sswidth==0){
		if(!$this->_prepareHook())
			return;


		return $this->display(__FILE__, 'advancedslider.tpl', $this->getCacheId());
		}
	}


	public function hookmaxSlideshow($params)
{
	$sswidth  = Configuration::get('advancedslideR_width');
		
		if($sswidth==1){
if(!$this->_prepareFwHook())
			return;
			
			return $this->display(__FILE__, 'advancedslider-fw.tpl', $this->getCacheId());
		}
}


			public function hookDisplayHeader($params)
	{
		if (!isset($this->context->controller->php_self) || $this->context->controller->php_self != 'index')
			return;
		$this->context->controller->addCSS($this->_path.'advancedslider.css');
		
		
		if (Shop::getContext() == Shop::CONTEXT_GROUP)
		$this -> context -> controller -> addCSS(($this -> _path) . 'css/advancedslider_slides_g_'.(int)$this->context->shop->getContextShopGroupID().'.css', 'all');
		elseif (Shop::getContext() == Shop::CONTEXT_SHOP)
		$this -> context -> controller -> addCSS(($this -> _path) . 'css/advancedslider_slides_s_'.(int)$this->context->shop->getContextShopID().'.css', 'all');
		$this->context->controller->addJS($this->_path.'js/advancedslider.js');
	}
	
	public function clearCache()
	{
		$this->_clearCache('advancedslider.tpl');
		$this->_clearCache('advancedslider-fw.tpl');
	}

	public function hookActionShopDataDuplication($params)
	{
		Db::getInstance()->execute('
		INSERT IGNORE INTO '._DB_PREFIX_.'advancedslider (id_advancedslider_slides, id_shop)
		SELECT id_advancedslider_slides, '.(int)$params['new_id_shop'].'
		FROM '._DB_PREFIX_.'advancedslider
		WHERE id_shop = '.(int)$params['old_id_shop']);
		$this->clearCache();
	}

	public function headerHTML()
	{
		if (Tools::getValue('controller') != 'AdminModules' && Tools::getValue('configure') != $this->name)
			return;

		$this->context->controller->addJqueryUI('ui.sortable');
		/* Style & js for fieldset 'slides configuration' */
		$html = '
		<style>
		#slides li {
			list-style: none;
			margin: 0 0 4px 0;
			padding: 10px;
			background-color: #F4E6C9;
			border: #CCCCCC solid 1px;
			color:#000;
		}
		</style>
		
		<script type="text/javascript">
			$(function() {
				var $mySlides = $("#slides");
				$mySlides.sortable({
					opacity: 0.6,
					cursor: "move",
					update: function() {
						var order = $(this).sortable("serialize") + "&action=updateSlidesPosition";
						$.post("'._PS_BASE_URL_.__PS_BASE_URI__.'modules/'.$this->name.'/ajax_'.$this->name.'.php?secure_key='.$this->secure_key.'", order);
						}
					});
				$mySlides.hover(function() {
					$(this).css("cursor","move");
					},
					function() {
					$(this).css("cursor","auto");
				});
			});
		</script>';

		return $html;
	}

	public function getNextPosition()
	{
		$row = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('
				SELECT MAX(hss.`position`) AS `next_position`
				FROM `'._DB_PREFIX_.'advancedslider_slides` hss, `'._DB_PREFIX_.'advancedslider` hs
				WHERE hss.`id_advancedslider_slides` = hs.`id_advancedslider_slides` AND hs.`id_shop` = '.(int)$this->context->shop->id
		);

		return (++$row['next_position']);
	}



	public function generateCSS()
	{	
		$css ='';
		$slides = $this->getSlidesforCSS();
		$height = Configuration::get('advancedslideR_HEIGHT');
		if(Configuration::get('advancedslideR_shadow'))
		{
			$css .= '#sequence-theme li{
-webkit-box-shadow: inset 2px -1px 19px 2px rgba(0,0,0,0.15);
-moz-box-shadow: inset 2px -1px 19px 2px rgba(0,0,0,0.15);
box-shadow: inset 2px -1px 19px 2px rgba(0,0,0,0.15);
}';
			
		}

if(Configuration::get('THED_BIG_RESPONSIVE'))

{
		$css .= '
			#sequence-theme, #sequence{
				height: '.$height.'px;
				}
			@media only screen and (max-width: 480px) {
	
		#sequence-theme, #sequence{
height: '.(0.22*$height).'px;
}	
}
@media only screen and (min-width: 480px) and (max-width: 767px) {
		#sequence-theme, #sequence{
height: '.(0.35*$height).'px;
}
}

@media only screen and (min-width: 767px) and (max-width: 1000px) {
		#sequence-theme, #sequence{
height:'.(0.58*$height).'px;
}
}

@media only screen and (min-width: 1001px) and (max-width: 1319px) {
		#sequence-theme, #sequence{
height: '.(0.79*$height).'px;
}
}	
';	
	
}
else{
	
			$css .= '
			#sequence-theme, #sequence{
				height: '.$height.'px;
				}
			@media only screen and (max-width: 480px) {
	
		#sequence-theme, #sequence{
height: '.(0.22*$height).'px;
}	
}
@media only screen and (min-width: 480px) and (max-width: 767px) {
		#sequence-theme, #sequence{
height: '.(0.35*$height).'px;
}
}

@media only screen and (min-width: 767px) and (max-width: 1000px) {
		#sequence-theme, #sequence{
height:'.(0.58*$height).'px;
}
}

@media only screen and (min-width: 1001px) and (max-width: 1319px) {
		#sequence-theme, #sequence{
height: '.(0.79*$height).'px;
}
}	
';
	
	
}
	
		foreach($slides as $slide)
		{
		if(!empty($slide['h_color']))
		{
			
$css .= '#sequence #slide_li_'.$slide['id_advancedslider_slides'].' h2, #sequence #slide_li_'.$slide['id_advancedslider_slides'].' h3  {
color:  '.$slide['h_color'].' !important;
	}	';
		}
	
	if(!empty($slide['d_color']))
		{
$css .='#sequence #slide_li_'.$slide['id_advancedslider_slides'].' .desc  {
color:  '.$slide['d_color'].' !important;
	}';
	}
	if(!empty($slide['h_bg'])){
	$css .= '#sequence #slide_li_'.$slide['id_advancedslider_slides'].' h2, #sequence #slide_li_'.$slide['id_advancedslider_slides'].' h3  {
background-color: '.$slide['h_bg'].' !important;
	}	';
	}
	if(!empty($slide['d_bg'])){
$css .='#sequence #slide_li_'.$slide['id_advancedslider_slides'].' .desc  {
background-color: '.$slide['d_bg'].' !important;
	}';
	}
	
	
	
	
		$css .= '		
			
#sequence #slide_li_'.$slide['id_advancedslider_slides'].' {
background-image:url(../images/'.$slide['image'].');
background-repeat:no-repeat;
	}

#sequence  .animate-in .slide_'.$slide['id_advancedslider_slides'].' h2 {
transition-delay: '.$slide['text_delay'].'ms;
-moz-transition-delay: '.$slide['text_delay'].'ms;
-webkit-transition-delay: '.$slide['text_delay'].'ms;
-o-transition-delay: '.$slide['text_delay'].'ms;
}
#sequence .animate-in .slide_'.$slide['id_advancedslider_slides'].' h3 {
transition-delay: '.($slide['text_delay']+200).'ms;
-moz-transition-delay: '.($slide['text_delay']+200).'ms;
-webkit-transition-delay: '.($slide['text_delay']+200).'ms;
-o-transition-delay: '.($slide['text_delay']+200).'ms;
}
#sequence  .animate-in .slide_'.$slide['id_advancedslider_slides'].' .desc{
transition-delay: '.($slide['text_delay']+400).'ms;
-moz-transition-delay: '.($slide['text_delay']+400).'ms;
-webkit-transition-delay: '.($slide['text_delay']+400).'ms;
-o-transition-delay: '.($slide['text_delay']+400).'ms;	
	}
			
#sequence .slide_'.$slide['id_advancedslider_slides'].' {
  position: absolute;
  left: '.$slide['b_text_left'].'%;
  top: '.$slide['b_text_top'].'%;
  width: 450px;
  opacity: 0;
  -webkit-transform: rotate('.$slide['b_text_rotation'].'deg);
  -moz-transform: rotate('.$slide['b_text_rotation'].'deg);
  -ms-transform: rotate('.$slide['b_text_rotation'].'deg);
  -o-transform: rotate('.$slide['b_text_rotation'].'deg);
  transform: rotate('.$slide['b_text_rotation'].'deg);
  -webkit-transition-duration: '.$slide['b_text_duration'].'ms;
  -moz-transition-duration: '.$slide['b_text_duration'].'ms;
  -ms-transition-duration: '.$slide['b_text_duration'].'ms;
  -o-transition-duration: '.$slide['b_text_duration'].'ms;
  transition-duration: '.$slide['b_text_duration'].'ms;
    z-index:  '.$slide['text_zindex'].';
}
#sequence .animate-in .slide_'.$slide['id_advancedslider_slides'].' {
  left: '.$slide['text_left'].'%;
  top: '.$slide['text_top'].'%;
  opacity: 1;
  -webkit-transform: rotate('.$slide['text_rotation'].'deg);
  -moz-transform: rotate('.$slide['text_rotation'].'deg);
  -ms-transform: rotate('.$slide['text_rotation'].'deg);
  -o-transform: rotate('.$slide['text_rotation'].'deg);
  transform: rotate('.$slide['text_rotation'].'deg);
  -webkit-transition-duration: '.$slide['text_duration'].'ms;
  -moz-transition-duration: '.$slide['text_duration'].'ms;
  -ms-transition-duration: '.$slide['text_duration'].'ms;
  -o-transition-duration: '.$slide['text_duration'].'ms;
  transition-duration: '.$slide['text_duration'].'ms;
  -webkit-transition-delay: '.$slide['text_delay'].'ms;
  -moz-transition-delay: '.$slide['text_delay'].'ms;
  -ms-transition-delay: '.$slide['text_delay'].'ms;
  -o-transition-delay: '.$slide['text_delay'].'ms;
  transition-delay: '.$slide['text_delay'].'ms;
}
#sequence .animate-out .slide_'.$slide['id_advancedslider_slides'].' {
  left: '.$slide['a_text_left'].'%;
  opacity: 0;
  top: '.$slide['a_text_top'].'%;
  -webkit-transform: rotate('.$slide['a_text_rotation'].'deg);
  -moz-transform: rotate('.$slide['a_text_rotation'].'deg);
  -ms-transform: rotate('.$slide['a_text_rotation'].'deg);
  -o-transform: rotate('.$slide['a_text_rotation'].'deg);
  transform: rotate('.$slide['a_text_rotation'].'deg);
  -webkit-transition-duration: '.$slide['a_text_duration'].'ms;
  -moz-transition-duration: '.$slide['a_text_duration'].'ms;
  -ms-transition-duration: '.$slide['a_text_duration'].'ms;
  -o-transition-duration: '.$slide['a_text_duration'].'ms;
  transition-duration: '.$slide['a_text_duration'].'ms;
}


@media only screen and (max-width: 480px) {
	
#sequence .slide_'.$slide['id_advancedslider_slides'].'{
transform: scale(0.22) rotate('.$slide['b_text_rotation'].'deg) !important;
-moz-transform: scale(0.22) rotate('.$slide['b_text_rotation'].'deg) !important;
-webkit-transform: scale(0.22) rotate('.$slide['b_text_rotation'].'deg) !important;
-o-transform: scale(0.22) rotate('.$slide['b_text_rotation'].'deg) !important;
}
}

@media only screen and (min-width: 480px) and (max-width: 767px) {
#sequence .slide_'.$slide['id_advancedslider_slides'].'{
transform: scale(0.35) rotate('.$slide['b_text_rotation'].'deg) !important;
-moz-transform: scale(0.35) rotate('.$slide['b_text_rotation'].'deg) !important;
-webkit-transform: scale(0.35) rotate('.$slide['b_text_rotation'].'deg) !important;
-o-transform: scale(0.35) rotate('.$slide['b_text_rotation'].'deg) !important;
}	

}

@media only screen and (min-width: 767px) and (max-width: 1000px) {
#sequence .slide_'.$slide['id_advancedslider_slides'].'{
transform: scale(0.58) rotate('.$slide['b_text_rotation'].'deg) !important;
-moz-transform: scale(0.58) rotate('.$slide['b_text_rotation'].'deg) !important;
-webkit-transform: scale(0.58) rotate('.$slide['b_text_rotation'].'deg) !important;
-o-transform: scale(0.58) rotate('.$slide['b_text_rotation'].'deg) !important;
}
}

@media only screen and (min-width: 1001px) and (max-width: 1319px) {
#sequence .slide_'.$slide['id_advancedslider_slides'].'{
transform: scale(0.79) rotate('.$slide['b_text_rotation'].'deg) !important;
-moz-transform: scale(0.79) rotate('.$slide['b_text_rotation'].'deg) !important;
-webkit-transform: scale(0.79) rotate('.$slide['b_text_rotation'].'deg) !important;
-o-transform: scale(0.79) rotate('.$slide['b_text_rotation'].'deg) !important;
}
}

/* showed */
@media only screen and (max-width: 480px) {
	
#sequence .animate-in .slide_'.$slide['id_advancedslider_slides'].' {
transform: scale(0.22) rotate('.$slide['text_rotation'].'deg) !important;
-moz-transform: scale(0.22) rotate('.$slide['text_rotation'].'deg) !important;
-webkit-transform: scale(0.22) rotate('.$slide['text_rotation'].'deg) !important;
-o-transform: scale(0.22) rotate('.$slide['text_rotation'].'deg) !important;
}
}

@media only screen and (min-width: 480px) and (max-width: 767px) {
#sequence .animate-in .slide_'.$slide['id_advancedslider_slides'].' {
transform: scale(0.35) rotate('.$slide['text_rotation'].'deg) !important;
-moz-transform: scale(0.35) rotate('.$slide['text_rotation'].'deg) !important;
-webkit-transform: scale(0.35) rotate('.$slide['text_rotation'].'deg) !important;
-o-transform: scale(0.35) rotate('.$slide['text_rotation'].'deg) !important;
}	

}

@media only screen and (min-width: 767px) and (max-width: 1000px) {
#sequence .animate-in .slide_'.$slide['id_advancedslider_slides'].' {
transform: scale(0.58) rotate('.$slide['text_rotation'].'deg) !important;
-moz-transform: scale(0.58) rotate('.$slide['text_rotation'].'deg) !important;
-webkit-transform: scale(0.58) rotate('.$slide['text_rotation'].'deg) !important;
-o-transform: scale(0.58) rotate('.$slide['text_rotation'].'deg) !important;
}
}

@media only screen and (min-width: 1001px) and (max-width: 1319px) {
#sequence .animate-in .slide_'.$slide['id_advancedslider_slides'].' {
transform: scale(0.79) rotate('.$slide['text_rotation'].'deg) !important;
-moz-transform: scale(0.79) rotate('.$slide['text_rotation'].'deg) !important;
-webkit-transform: scale(0.79) rotate('.$slide['text_rotation'].'deg) !important;
-o-transform: scale(0.79) rotate('.$slide['text_rotation'].'deg) !important;
}
}

/* after */

@media only screen and (max-width: 480px) {
	
#sequence .animate-out .slide_'.$slide['id_advancedslider_slides'].' {
transform: scale(0.22) rotate('.$slide['a_text_rotation'].'deg) !important;
-moz-transform: scale(0.22) rotate('.$slide['a_text_rotation'].'deg) !important;
-webkit-transform: scale(0.22) rotate('.$slide['a_text_rotation'].'deg) !important;
-o-transform: scale(0.22) rotate('.$slide['a_text_rotation'].'deg) !important;
}
}

@media only screen and (min-width: 480px) and (max-width: 767px) {
#sequence .animate-out .slide_'.$slide['id_advancedslider_slides'].' {
transform: scale(0.35) rotate('.$slide['a_text_rotation'].'deg) !important;
-moz-transform: scale(0.35) rotate('.$slide['a_text_rotation'].'deg) !important;
-webkit-transform: scale(0.35) rotate('.$slide['a_text_rotation'].'deg) !important;
-o-transform: scale(0.35) rotate('.$slide['a_text_rotation'].'deg) !important;
}	

}

@media only screen and (min-width: 767px) and (max-width: 1000px) {
#sequence .animate-out .slide_'.$slide['id_advancedslider_slides'].' {
transform: scale(0.58) rotate('.$slide['a_text_rotation'].'deg) !important;
-moz-transform: scale(0.58) rotate('.$slide['a_text_rotation'].'deg) !important;
-webkit-transform: scale(0.58) rotate('.$slide['a_text_rotation'].'deg) !important;
-o-transform: scale(0.58) rotate('.$slide['a_text_rotation'].'deg) !important;
}
}

@media only screen and (min-width: 1001px) and (max-width: 1319px) {
#sequence .animate-out .slide_'.$slide['id_advancedslider_slides'].' {
transform: scale(0.79) rotate('.$slide['a_text_rotation'].'deg) !important;
-moz-transform: scale(0.79) rotate('.$slide['a_text_rotation'].'deg) !important;
-webkit-transform: scale(0.79) rotate('.$slide['a_text_rotation'].'deg) !important;
-o-transform: scale(0.79) rotate('.$slide['a_text_rotation'].'deg) !important;
}
}



';
			
	foreach($slide['s_images'] as $s_image)
		{
					$css .= '
#sequence .slide_'.$slide['id_advancedslider_slides'].'_image_'.$s_image['id_image'].' {
  position: absolute;
  left: '.$s_image['b_left'].'%;
  top: '.$s_image['b_top'].'%;
  -webkit-transform: rotate('.$s_image['b_rotation'].'deg);
  -moz-transform: rotate('.$s_image['b_rotation'].'deg);
  -ms-transform: rotate('.$s_image['b_rotation'].'deg);
  -o-transform: rotate('.$s_image['b_rotation'].'deg);
  transform: rotate('.$s_image['b_rotation'].'deg);
  -webkit-transition-duration: '.$s_image['b_duration'].'ms;
  -moz-transition-duration: '.$s_image['b_duration'].'ms;
  -ms-transition-duration: '.$s_image['b_duration'].'ms;
  -o-transition-duration: '.$s_image['b_duration'].'ms;
  transition-duration: '.$s_image['b_duration'].'ms;
  z-index:  '.$s_image['zindex'].';
  opacity: 0;
}
#sequence .animate-in .slide_'.$slide['id_advancedslider_slides'].'_image_'.$s_image['id_image'].' {
  left: '.$s_image['left'].'%;
  top: '.$s_image['top'].'%;
  -webkit-transform: rotate('.$s_image['rotation'].'deg);
  -moz-transform: rotate('.$s_image['rotation'].'deg);
  -ms-transform: rotate('.$s_image['rotation'].'deg);
  -o-transform: rotate('.$s_image['rotation'].'deg);
  transform: rotate('.$s_image['rotation'].'deg);
  -webkit-transition-duration: '.$s_image['duration'].'ms;
  -moz-transition-duration: '.$s_image['duration'].'ms;
  -ms-transition-duration: '.$s_image['duration'].'ms;
  -o-transition-duration: '.$s_image['duration'].'ms;
  transition-duration: '.$s_image['duration'].'ms;
    -webkit-transition-delay: '.$s_image['delay'].'ms;
  -moz-transition-delay: '.$s_image['delay'].'ms;
  -ms-transition-delay: '.$s_image['delay'].'ms;
  -o-transition-delay: '.$s_image['delay'].'ms;
  transition-delay: '.$s_image['delay'].'ms;
  opacity: 1;
}
#sequence .animate-out .slide_'.$slide['id_advancedslider_slides'].'_image_'.$s_image['id_image'].' {
  left: '.$s_image['a_left'].'%;
  opacity: 0;
  top: '.$s_image['a_top'].'%;
  -webkit-transform: rotate('.$s_image['a_rotation'].'deg);
  -moz-transform: rotate('.$s_image['a_rotation'].'deg);
  -ms-transform: rotate('.$s_image['a_rotation'].'deg);
  -o-transform: rotate('.$s_image['a_rotation'].'deg);
  transform: rotate('.$s_image['a_rotation'].'deg);
  -webkit-transition-duration: '.$s_image['a_duration'].'ms;
  -moz-transition-duration: '.$s_image['a_duration'].'ms;
  -ms-transition-duration: '.$s_image['a_duration'].'ms;
  -o-transition-duration: '.$s_image['a_duration'].'ms;
  transition-duration: '.$s_image['a_duration'].'ms;
}






@media only screen and (max-width: 480px) {
	
#sequence .slide_'.$slide['id_advancedslider_slides'].'_image_'.$s_image['id_image'].' {
transform: scale(0.22) rotate('.$s_image['b_rotation'].'deg) !important;
-moz-transform: scale(0.22) rotate('.$s_image['b_rotation'].'deg) !important;
-webkit-transform: scale(0.22) rotate('.$s_image['b_rotation'].'deg) !important;
-o-transform: scale(0.22) rotate('.$s_image['b_rotation'].'deg) !important;
}
}

@media only screen and (min-width: 480px) and (max-width: 767px) {
#sequence .slide_'.$slide['id_advancedslider_slides'].'_image_'.$s_image['id_image'].' {
transform: scale(0.35) rotate('.$s_image['b_rotation'].'deg) !important;
-moz-transform: scale(0.35) rotate('.$s_image['b_rotation'].'deg) !important;
-webkit-transform: scale(0.35) rotate('.$s_image['b_rotation'].'deg) !important;
-o-transform: scale(0.35) rotate('.$s_image['b_rotation'].'deg) !important;
}	

}

@media only screen and (min-width: 767px) and (max-width: 1000px) {
#sequence .slide_'.$slide['id_advancedslider_slides'].'_image_'.$s_image['id_image'].' {
transform: scale(0.58) rotate('.$s_image['b_rotation'].'deg) !important;
-moz-transform: scale(0.58) rotate('.$s_image['b_rotation'].'deg) !important;
-webkit-transform: scale(0.58) rotate('.$s_image['b_rotation'].'deg) !important;
-o-transform: scale(0.58) rotate('.$s_image['b_rotation'].'deg) !important;
}
}

@media only screen and (min-width: 1001px) and (max-width: 1319px) {
#sequence .slide_'.$slide['id_advancedslider_slides'].'{
transform: scale(0.79) rotate('.$s_image['b_rotation'].'deg) !important;
-moz-transform: scale(0.79) rotate('.$s_image['b_rotation'].'deg) !important;
-webkit-transform: scale(0.79) rotate('.$s_image['b_rotation'].'deg) !important;
-o-transform: scale(0.79) rotate('.$s_image['b_rotation'].'deg) !important;
}
}


/* showed */


@media only screen and (max-width: 480px) {
	
#sequence .animate-in .slide_'.$slide['id_advancedslider_slides'].'_image_'.$s_image['id_image'].' {
transform: scale(0.22) rotate('.$s_image['rotation'].'deg) !important;
-moz-transform: scale(0.22) rotate('.$s_image['rotation'].'deg) !important;
-webkit-transform: scale(0.22) rotate('.$s_image['rotation'].'deg) !important;
-o-transform: scale(0.22) rotate('.$s_image['rotation'].'deg) !important;
}
}

@media only screen and (min-width: 480px) and (max-width: 767px) {
#sequence .animate-in .slide_'.$slide['id_advancedslider_slides'].'_image_'.$s_image['id_image'].' {
transform: scale(0.35) rotate('.$s_image['rotation'].'deg) !important;
-moz-transform: scale(0.35) rotate('.$s_image['rotation'].'deg) !important;
-webkit-transform: scale(0.35) rotate('.$s_image['rotation'].'deg) !important;
-o-transform: scale(0.35) rotate('.$s_image['rotation'].'deg) !important;
}	

}

@media only screen and (min-width: 767px) and (max-width: 1000px) {
#sequence .animate-in .slide_'.$slide['id_advancedslider_slides'].'_image_'.$s_image['id_image'].' {
transform: scale(0.58) rotate('.$s_image['rotation'].'deg) !important;
-moz-transform: scale(0.58) rotate('.$s_image['rotation'].'deg) !important;
-webkit-transform: scale(0.58) rotate('.$s_image['rotation'].'deg) !important;
-o-transform: scale(0.58) rotate('.$s_image['rotation'].'deg) !important;
}
}

@media only screen and (min-width: 1001px) and (max-width: 1319px) {
#sequence .animate-in .slide_'.$slide['id_advancedslider_slides'].'_image_'.$s_image['id_image'].' {
transform: scale(0.79) rotate('.$s_image['rotation'].'deg) !important;
-moz-transform: scale(0.79) rotate('.$s_image['rotation'].'deg) !important;
-webkit-transform: scale(0.79) rotate('.$s_image['rotation'].'deg) !important;
-o-transform: scale(0.79) rotate('.$s_image['rotation'].'deg) !important;
}
}

/* after */


@media only screen and (max-width: 480px) {
	
#sequence .animate-out .slide_'.$slide['id_advancedslider_slides'].'_image_'.$s_image['id_image'].' {
transform: scale(0.22) rotate('.$s_image['a_rotation'].'deg) !important;
-moz-transform: scale(0.22) rotate('.$s_image['a_rotation'].'deg) !important;
-webkit-transform: scale(0.22) rotate('.$s_image['a_rotation'].'deg) !important;
-o-transform: scale(0.22) rotate('.$s_image['a_rotation'].'deg) !important;
}
}

@media only screen and (min-width: 480px) and (max-width: 767px) {
#sequence .animate-out .slide_'.$slide['id_advancedslider_slides'].'_image_'.$s_image['id_image'].' {
transform: scale(0.35) rotate('.$s_image['a_rotation'].'deg) !important;
-moz-transform: scale(0.35) rotate('.$s_image['a_rotation'].'deg) !important;
-webkit-transform: scale(0.35) rotate('.$s_image['a_rotation'].'deg) !important;
-o-transform: scale(0.35) rotate('.$s_image['a_rotation'].'deg) !important;
}	

}

@media only screen and (min-width: 767px) and (max-width: 1000px) {
#sequence .animate-out .slide_'.$slide['id_advancedslider_slides'].'_image_'.$s_image['id_image'].' {
transform: scale(0.58) rotate('.$s_image['a_rotation'].'deg) !important;
-moz-transform: scale(0.58) rotate('.$s_image['a_rotation'].'deg) !important;
-webkit-transform: scale(0.58) rotate('.$s_image['a_rotation'].'deg) !important;
-o-transform: scale(0.58) rotate('.$s_image['a_rotation'].'deg) !important;
}
}

@media only screen and (min-width: 1001px) and (max-width: 1319px) {
#sequence .animate-out .slide_'.$slide['id_advancedslider_slides'].'_image_'.$s_image['id_image'].' {
transform: scale(0.79) rotate('.$s_image['a_rotation'].'deg) !important;
-moz-transform: scale(0.79) rotate('.$s_image['a_rotation'].'deg) !important;
-webkit-transform: scale(0.79) rotate('.$s_image['a_rotation'].'deg) !important;
-o-transform: scale(0.79) rotate('.$s_image['a_rotation'].'deg) !important;
}
}

';
			
		}
			}
		
		
		
		
		
	
		
		if (Shop::getContext() == Shop::CONTEXT_GROUP)
		$myFile = $this->local_path . "css/advancedslider_slides_g_".(int)$this->context->shop->getContextShopGroupID().".css";
		elseif (Shop::getContext() == Shop::CONTEXT_SHOP)
		$this -> context -> controller -> addCSS(($this -> _path) . 'css/advancedslider_slides_s_'.(int)$this->context->shop->getContextShopID().'.css', 'all');
		$myFile = $this->local_path . "css/advancedslider_slides_s_".(int)$this->context->shop->getContextShopID().".css";
		
	
		
		
		
		$fh = fopen($myFile, 'w') or die("can't open file");
		fwrite($fh, $css);
		fclose($fh);
		
	}
	public function getSlides($active = null)
	{
		$this->context = Context::getContext();
		$id_shop = $this->context->shop->id;
		$id_lang = $this->context->language->id;

		return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
			SELECT hs.`id_advancedslider_slides` as id_slide,
					   hss.`image`,
					   hss.`position`,
					   hss.`active`,
					   hssl.`title`,
					   hssl.`url`,
					   hssl.`legend`,
					   hssl.`description`
			FROM '._DB_PREFIX_.'advancedslider hs
			LEFT JOIN '._DB_PREFIX_.'advancedslider_slides hss ON (hs.id_advancedslider_slides = hss.id_advancedslider_slides)
			LEFT JOIN '._DB_PREFIX_.'advancedslider_slides_lang hssl ON (hss.id_advancedslider_slides = hssl.id_advancedslider_slides)
			WHERE (id_shop = '.(int)$id_shop.')
			AND hssl.id_lang = '.(int)$id_lang.
			($active ? ' AND hss.`active` = 1' : ' ').'
			ORDER BY hss.position');
	}
	
		public function getSlideswImages($active = null)
	{
		$this->context = Context::getContext();
		$id_shop = $this->context->shop->id;
		$id_lang = $this->context->language->id;

		$rows =  Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
			SELECT hs.`id_advancedslider_slides` as id_slide,
					   hss.`position`,
					   hss.`active`,
					   hssl.`title`,
					   hssl.`url`,
					   hssl.`legend`,
					   hssl.`description`
			FROM '._DB_PREFIX_.'advancedslider hs
			LEFT JOIN '._DB_PREFIX_.'advancedslider_slides hss ON (hs.id_advancedslider_slides = hss.id_advancedslider_slides)
			LEFT JOIN '._DB_PREFIX_.'advancedslider_slides_lang hssl ON (hss.id_advancedslider_slides = hssl.id_advancedslider_slides)
			WHERE (id_shop = '.(int)$id_shop.')
			AND hssl.id_lang = '.(int)$id_lang.
			($active ? ' AND hss.`active` = 1' : ' ').'
			ORDER BY hss.position');
			
						
			foreach($rows  as  $k => $row)
			{
			$images = array();
			$images =  Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
			SELECT hs.`image` as s_image, id_image
			FROM '._DB_PREFIX_.'advancedslider_slides_images hs WHERE hs.id_advancedslider_slides = '.$row['id_slide'].'');
			$rows[$k]['s_images'] = $images;
			}
			
			return $rows;
	}
	
	
	public function getSlidesforCSS()
	{
		

		$rows =  Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
			SELECT  *
			FROM '._DB_PREFIX_.'advancedslider_slides
			');
			
						
			foreach($rows  as  $k => $row)
			{
			$images = array();
			$images =  Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
			SELECT *
					FROM '._DB_PREFIX_.'advancedslider_slides_images hs WHERE hs.id_advancedslider_slides = '.$row['id_advancedslider_slides'].'');
			$rows[$k]['s_images'] = $images;
			}
			return $rows;
	}

	public function displayStatus($id_slide, $active)
	{
		$title = ((int)$active == 0 ? $this->l('Disabled') : $this->l('Enabled'));
		$img = ((int)$active == 0 ? 'disabled.gif' : 'enabled.gif');
		$html = '<a href="'.AdminController::$currentIndex.
				'&configure='.$this->name.'
				&token='.Tools::getAdminTokenLite('AdminModules').'
				&changeStatus&id_slide='.(int)$id_slide.'" title="'.$title.'"><img src="'._PS_ADMIN_IMG_.''.$img.'" alt="" /></a>';
		return $html;
	}

	public function slideExists($id_slide)
	{
		$req = 'SELECT hs.`id_advancedslider_slides` as id_slide
				FROM `'._DB_PREFIX_.'advancedslider` hs
				WHERE hs.`id_advancedslider_slides` = '.(int)$id_slide;
		$row = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow($req);
		return ($row);
	}
}
