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

class AdvancedSlide extends ObjectModel
{
	public $title;
	public $description;
	public $url;
	public $legend;
	public $image;
	public $active;
	public $position;
	
	
	public $text_zindex;
	public $text_delay;
	
	public $b_text_left;
	public $b_text_top;
	public $b_text_rotation;
	public $b_text_duration;
	
	public $text_left;
	public $text_top;
	public $text_rotation;
	public $text_duration;
	
	public $a_text_left;
	public $a_text_top;
	public $a_text_rotation;
	public $a_text_duration;
	
	public $h_color;
	public $h_bg;
	public $d_color;
	public $d_bg;

	

	/**
	 * @see ObjectModel::$definition
	 */
	public static $definition = array(
		'table' => 'advancedslider_slides',
		'primary' => 'id_advancedslider_slides',
		'multilang' => true,
		'fields' => array(
			'active' =>			array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'required' => true),
			'position' =>		array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => true),
			
			
			'text_zindex' =>		array('type' => self::TYPE_INT, 'validate' => 'isInt'),
			'text_delay' =>			array('type' => self::TYPE_INT, 'validate' => 'isInt'),
			
			'b_text_left' =>		array('type' => self::TYPE_INT, 'validate' => 'isInt'),
			'b_text_top' =>			array('type' => self::TYPE_INT, 'validate' => 'isInt'),
			'b_text_rotation' =>	array('type' => self::TYPE_INT, 'validate' => 'isInt'),
			'b_text_duration' =>	array('type' => self::TYPE_INT, 'validate' => 'isInt'),
			
			'text_left' =>		array('type' => self::TYPE_INT, 'validate' => 'isInt'),
			'text_top' =>			array('type' => self::TYPE_INT, 'validate' => 'isInt'),
			'text_rotation' =>	array('type' => self::TYPE_INT, 'validate' => 'isInt'),
			'text_duration' =>	array('type' => self::TYPE_INT, 'validate' => 'isInt'),
			
			'a_text_left' =>		array('type' => self::TYPE_INT, 'validate' => 'isInt'),
			'a_text_top' =>			array('type' => self::TYPE_INT, 'validate' => 'isInt'),
			'a_text_rotation' =>	array('type' => self::TYPE_INT, 'validate' => 'isInt'),
			'a_text_duration' =>	array('type' => self::TYPE_INT, 'validate' => 'isInt'),
			'image' =>			array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml', 'size' => 255),
			
			'h_color' =>			array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml', 'size' => 255),
			'd_color' =>			array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml', 'size' => 255),
			'h_bg' =>			array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml', 'size' => 255),
			'd_bg' =>			array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml', 'size' => 255),
			
			

			

			// Lang fields
			'description' =>	array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCleanHtml', 'size' => 4000),
			'title' =>			array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCleanHtml', 'size' => 255),
			'legend' =>			array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCleanHtml', 'size' => 255),
			'url' =>			array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isUrl', 'size' => 255),
			
		)
	);

	public	function __construct($id_slide = null, $id_lang = null, $id_shop = null, Context $context = null)
	{
		parent::__construct($id_slide, $id_lang, $id_shop);
	}

	public function add($autodate = true, $null_values = false)
	{
		$context = Context::getContext();
		$id_shop = $context->shop->id;

		$res = parent::add($autodate, $null_values);
		$res &= Db::getInstance()->execute('
			INSERT INTO `'._DB_PREFIX_.'advancedslider` (`id_shop`, `id_advancedslider_slides`)
			VALUES('.(int)$id_shop.', '.(int)$this->id.')'
		);
		return $res;
	}

	public function delete()
	{
		$res = true;

		$image = $this->image;
	
			if (preg_match('/sample/', $image) === 0)
				if ($image && file_exists(dirname(__FILE__).'/images/'.$image))
					$res &= @unlink(dirname(__FILE__).'/images/'.$image);
		

		$res &= $this->reOrderPositions();

		$res &= Db::getInstance()->execute('
			DELETE FROM `'._DB_PREFIX_.'advancedslider`
			WHERE `id_advancedslider_slides` = '.(int)$this->id
		);

		$res &= parent::delete();
		return $res;
	}
	
		public function getImages()
	{
			$id_slide = $this->id;
			
			return $rows = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
			SELECT *
			FROM `'._DB_PREFIX_.'advancedslider_slides_images`
			WHERE `id_advancedslider_slides` = '.(int)$id_slide	
			);
	}
	
		public function updateSlideImage($id_image, $zindex,  $delay, $b_left, $b_top, $b_rotation, $b_duration, $left, $top, $rotation, $duration, $a_left, $a_top, $a_rotation, $a_duration)
	{
		
	
			
			Db::getInstance()->execute('UPDATE '._DB_PREFIX_.'advancedslider_slides_images SET 
			zindex = '.$zindex.', delay = '.$delay.', b_left = '.$b_left.', b_top = '.$b_top.', b_rotation = '.$b_rotation.', b_duration = '.$b_duration.',
			`left` = '.$left.', `top` = '.$top.', rotation = '.$rotation.', duration = '.$duration.',
			a_left = '.$a_left.', a_top = '.$a_top.', a_rotation = '.$a_rotation.', a_duration = '.$a_duration.' 
			WHERE id_image = '.$id_image);
	}

	public function reOrderPositions()
	{
		$id_slide = $this->id;
		$context = Context::getContext();
		$id_shop = $context->shop->id;

		$max = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
			SELECT MAX(hss.`position`) as position
			FROM `'._DB_PREFIX_.'advancedslider_slides` hss, `'._DB_PREFIX_.'advancedslider` hs
			WHERE hss.`id_advancedslider_slides` = hs.`id_advancedslider_slides` AND hs.`id_shop` = '.(int)$id_shop
		);

		if ((int)$max == (int)$id_slide)
			return true;

		$rows = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
			SELECT hss.`position` as position, hss.`id_advancedslider_slides` as id_slide
			FROM `'._DB_PREFIX_.'advancedslider_slides` hss
			LEFT JOIN `'._DB_PREFIX_.'advancedslider` hs ON (hss.`id_advancedslider_slides` = hs.`id_advancedslider_slides`)
			WHERE hs.`id_shop` = '.(int)$id_shop.' AND hss.`position` > '.(int)$this->position
		);

		foreach ($rows as $row)
		{
			$current_slide = new AdvancedSlide($row['id_slide']);
			--$current_slide->position;
			$current_slide->update();
			unset($current_slide);
		}

		return true;
	}

}
