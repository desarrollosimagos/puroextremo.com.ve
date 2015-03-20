<?php
/*
* 2007-2012 PrestaShop
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
*  @copyright  2007-2012 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

class MegaMenuTab
{
	public static function gets($id_lang, $id_menu = null, $id_shop)
	{
		$sql = 'SELECT l.id_menu, l.position, l.active, l.new_window, l.label_icon, l.background_color, l.text_color, ll.link, ll.label, ll.label_tag
				FROM '._DB_PREFIX_.'megamenuiqit_menus l
				LEFT JOIN '._DB_PREFIX_.'megamenuiqit_menus_lang ll ON (l.id_menu = ll.id_menu AND ll.id_lang = '.(int)$id_lang.' AND ll.id_shop='.(int)$id_shop.')
				WHERE 1 '.((!is_null($id_menu)) ? ' AND l.id_menu = "'.(int)$id_menu.'"' : '').'
				AND l.id_shop IN (0, '.(int)$id_shop.') ORDER BY l.position ASC';

		return Db::getInstance()->executeS($sql);
	}
	
	
		public static function getsfm($id_lang, $id_shop)
	{
		$sql = 'SELECT l.id_menu, l.position, l.active, l.new_window, l.label_icon, l.background_color, l.text_color , ll.link, ll.label, ll.label_tag, li.left, li.right, li.bottom, li.left_val, li.right_val, li.bottom_val, lli.link_right, lli.link_bottom
				FROM '._DB_PREFIX_.'megamenuiqit_menus l
				LEFT JOIN '._DB_PREFIX_.'megamenuiqit_menus_lang ll ON (l.id_menu = ll.id_menu AND ll.id_lang = '.(int)$id_lang.' AND ll.id_shop='.(int)$id_shop.')
				LEFT JOIN '._DB_PREFIX_.'megamenuiqit_menusin_lang lli ON (l.id_menu = lli.id_menu AND lli.id_lang = '.(int)$id_lang.' AND lli.id_shop='.(int)$id_shop.')
				LEFT JOIN '._DB_PREFIX_.'megamenuiqit_insidem li ON (l.id_menu = li.id_menu)
				WHERE l.id_shop IN (0, '.(int)$id_shop.') AND l.active = 1   ORDER BY l.position ASC';

		return Db::getInstance()->executeS($sql);
	}
	
	

	public static function get($id_menu, $id_lang, $id_shop)
	{
		return self::gets($id_lang, $id_menu, $id_shop);
	}
	
		public static function get_last_position($id_shop)
	{
		$sql = 'SELECT MAX(`position`) as max_position
				FROM '._DB_PREFIX_.'megamenuiqit_menus WHERE id_shop IN (0, '.(int)$id_shop.')';

		return Db::getInstance()->getValue($sql);
	}
	
	
		public static function getinside($id_menu)
	{
		$sql = 'SELECT l.left, l.right, l.bottom, l.left_val, l.right_val, l.bottom_val
				FROM '._DB_PREFIX_.'megamenuiqit_insidem l
				WHERE  id_menu = "'.(int)$id_menu.'"';


$ret =  Db::getInstance()->getRow($sql);
		return $ret;
	}
	
	
	
	
	
	
		public static function moveUp($id_menu, $id_shop)
	{
		$sql = 'SELECT position
				FROM '._DB_PREFIX_.'megamenuiqit_menus WHERE 1 '.((!is_null($id_menu)) ? ' AND id_menu = "'.(int)$id_menu.'"' : '').'
				AND id_shop IN (0, '.(int)$id_shop.')';
		
		$curr_position = Db::getInstance()->getValue($sql);
		
		$sql = 'SELECT MAX(`position`)
				FROM '._DB_PREFIX_.'megamenuiqit_menus  WHERE position < '.(int)$curr_position.' AND id_shop IN (0, '.(int)$id_shop.')';
		$prev_position = Db::getInstance()->getValue($sql);
		
		$sql = 'SELECT id_menu
				FROM '._DB_PREFIX_.'megamenuiqit_menus  WHERE position = '.(int)$prev_position.' AND id_shop IN (0, '.(int)$id_shop.')';
		$prev_menu_id = Db::getInstance()->getValue($sql);
		
		
		if(isset($prev_position) && isset($prev_menu_id)){
		Db::getInstance()->update(
			'megamenuiqit_menus',
			array(
				'position'=>(int)$prev_position,
				'id_shop' => (int)$id_shop
			),
			'id_menu = '.(int)$id_menu
		);
		
		
				Db::getInstance()->update(
			'megamenuiqit_menus',
			array(
				'position'=>(int)$curr_position,
				'id_shop' => (int)$id_shop
			),
			'id_menu = '.(int)$prev_menu_id
		);
		
		}
		
	}
	
		public static function moveDown($id_menu, $id_shop)
	{
		$sql = 'SELECT position
				FROM '._DB_PREFIX_.'megamenuiqit_menus WHERE 1 '.((!is_null($id_menu)) ? ' AND id_menu = "'.(int)$id_menu.'"' : '').'
				AND id_shop IN (0, '.(int)$id_shop.')';
		
		$curr_position = Db::getInstance()->getValue($sql);
		
		$sql = 'SELECT MIN(`position`)
				FROM '._DB_PREFIX_.'megamenuiqit_menus  WHERE position > '.(int)$curr_position.' AND id_shop IN (0, '.(int)$id_shop.')';
		$next_position = Db::getInstance()->getValue($sql);
		
		$sql = 'SELECT id_menu
				FROM '._DB_PREFIX_.'megamenuiqit_menus  WHERE position = '.(int)$next_position.' AND id_shop IN (0, '.(int)$id_shop.')';
		$next_menu_id = Db::getInstance()->getValue($sql);
		
		
		if(isset($next_position) && isset($next_menu_id)){
		Db::getInstance()->update(
			'megamenuiqit_menus',
			array(
				'position'=>(int)$next_position,
				'id_shop' => (int)$id_shop
			),
			'id_menu = '.(int)$id_menu
		);
		
		
				Db::getInstance()->update(
			'megamenuiqit_menus',
			array(
				'position'=>(int)$curr_position,
				'id_shop' => (int)$id_shop
			),
			'id_menu = '.(int)$next_menu_id
		);
		
		}
		
	}
	
	
	
	
	
	
	
	

	public static function getLinkLang($id_menu, $id_shop)
	{
		$ret = Db::getInstance()->executeS('
			SELECT l.id_menu, l.position, l.active, l.new_window, l.label_icon,  l.background_color, l.text_color, ll.link, ll.label, ll.label_tag, ll.id_lang, lli.link_right, lli.link_bottom, lli.id_lang as in_id_lang
			FROM '._DB_PREFIX_.'megamenuiqit_menus l
			LEFT JOIN '._DB_PREFIX_.'megamenuiqit_menus_lang ll ON (l.id_menu = ll.id_menu AND ll.id_shop='.(int)$id_shop.')
			LEFT JOIN '._DB_PREFIX_.'megamenuiqit_menusin_lang lli ON (l.id_menu = lli.id_menu AND lli.id_shop='.(int)$id_shop.')
			WHERE 1
			'.((!is_null($id_menu)) ? ' AND l.id_menu = "'.(int)$id_menu.'"' : '').'
			AND l.id_shop IN (0, '.(int)$id_shop.')
		');

		$link = array();
		$label = array();
		$label_tag = array();
		$link_right = array();
		$link_bottom = array();
		$new_window = false;
		$position = 0;
		$active = 0;
		$new_window = 0;
		$label_icon = NULL;
		$background_color = NULL;
		$text_color = NULL;
		foreach ($ret as $line)
		{
			$link[$line['id_lang']] = Tools::safeOutput($line['link']);
			$label[$line['id_lang']] = Tools::safeOutput($line['label']);
			$label_tag[$line['id_lang']] = Tools::safeOutput($line['label_tag']);
			$link_right[$line['in_id_lang']] = Tools::safeOutput($line['link_right']);
			$link_bottom[$line['in_id_lang']] = Tools::safeOutput($line['link_bottom']);
			$position = $line['position'];
			$active = $line['active'];
			$new_window = $line['new_window'];
			$label_icon = $line['label_icon'];
			$background_color = $line['background_color'];
			$text_color = $line['text_color'];
		}
		return array('link' => $link, 'label' => $label, 'label_tag' => $label_tag, 'position' => $position, 'active' => $active, 'new_window' => $new_window, 'label_icon' => $label_icon, 'background_color' => $background_color, 'text_color' => $text_color, 'link_right' => $link_right, 'link_bottom' => $link_bottom  );
	}

	public static function add($link, $label, $label_tag, $active, $new_window, $label_icon, $background_color, $text_color, $id_shop, $insidecontent)
	{
		if(!is_array($label))
			return false;
		if(!is_array($link))
			return false;
			


		$position= self::get_last_position($id_shop);
		Db::getInstance()->insert(
			'megamenuiqit_menus',
			array(
				'active'=>(int)$active,
				'new_window'=>(int)$new_window,
				'position'=>(int)$position + 1,
				'label_icon'=>$label_icon,
				'background_color'=>$background_color,
				'text_color'=>$text_color,
				'id_shop' => (int)$id_shop
			)
		);
		


		
		$id_menu = Db::getInstance()->Insert_ID();
			
			
			Db::getInstance()->insert(
			'megamenuiqit_insidem',
			array(
				'id_menu'=>(int)$id_menu,
				'left'=> $insidecontent['left_panel'],
				'right'=> $insidecontent['right_panel'],
				'bottom' => $insidecontent['bottom_panel'],
				'left_val'=> $insidecontent['left_panel_val'],
				'right_val'=> $insidecontent['right_panel_val'],
				'bottom_val' => $insidecontent['bottom_panel_val']
			)
			
		);

		foreach ($label as $id_lang=>$label)
		Db::getInstance()->insert(
			'megamenuiqit_menus_lang',
			array(
				'id_menu'=>(int)$id_menu,
				'id_lang'=>(int)$id_lang,
				'id_shop'=>(int)$id_shop,
				'label'=>pSQL($label),
				'link'=>pSQL($link[$id_lang]),
				'label_tag'=>(isset($label_tag[$id_lang]) ? pSQL($label_tag[$id_lang]) : '')           
			)
		);
		
		
			
		foreach ($link as $id_lang=>$link){
			$lright = NULL;
			$lbottom = NULL;
		if(isset($insidecontent['right_link_lang'][$id_lang]))
		$lright = pSQL($insidecontent['right_link_lang'][$id_lang]);
		if(isset($insidecontent['bottom_link_lang'][$id_lang]))
		$lbottom = pSQL($insidecontent['bottom_link_lang'][$id_lang]);
		Db::getInstance()->insert(
			'megamenuiqit_menusin_lang',
			array(
				'id_menu'=>(int)$id_menu,
				'id_lang'=>(int)$id_lang,
				'id_shop'=>(int)$id_shop,
				'link_right'=>$lright,
				'link_bottom'=>$lbottom
			)
		);}
		

		 
	}

	public static function update($link, $labels, $label_tag, $active, $new_window, $label_icon, $background_color, $text_color, $id_shop, $insidecontent, $id_menu)
	{
		
		if(!is_array($labels))
			return false;
		if(!is_array($link))
			return false;
		if(!is_array($insidecontent))
			return false;

		Db::getInstance()->update(
			'megamenuiqit_menus',
			array(
				'active'=>(int)$active,
				'new_window'=>(int)$new_window,
				'label_icon'=>$label_icon,
				'background_color'=>$background_color,
				'text_color'=>$text_color,
				'id_shop' => (int)$id_shop
			),
			'id_menu = '.(int)$id_menu
		);
		
		
		
								Db::getInstance()->update(
			'megamenuiqit_insidem',
			array(
				'left'=> $insidecontent['left_panel'],
				'right'=> $insidecontent['right_panel'],
				'bottom' => $insidecontent['bottom_panel'],
				'left_val'=> $insidecontent['left_panel_val'],
				'right_val'=> $insidecontent['right_panel_val'],
				'bottom_val' => $insidecontent['bottom_panel_val']
			),
			'id_menu = '.(int)$id_menu
		);
		

		foreach ($labels as $id_lang => $label)
			Db::getInstance()->update(
				'megamenuiqit_menus_lang',
				array(
					'id_shop'=>(int)$id_shop,
					'label'=>pSQL($label),
					'link'=>pSQL($link[$id_lang]),
					'label_tag'=>(isset($label_tag[$id_lang]) ? pSQL($label_tag[$id_lang]) : '') 
				),
				'id_menu = '.(int)$id_menu.' AND id_lang = '.(int)$id_lang
			);
			
		if(isset($insidecontent['bottom_link_lang']) || isset($insidecontent['right_link_lang'])  )
{			
		foreach ($link as $id_lang=>$link){
	 Db::getInstance()->update(
			'megamenuiqit_menusin_lang',
			array(
				'id_shop'=>(int)$id_shop,
				'link_right'=> (isset($insidecontent['right_link_lang']) ? pSQL($insidecontent['right_link_lang'][$id_lang]) : ''),
				'link_bottom'=>(isset($insidecontent['bottom_link_lang']) ? pSQL($insidecontent['bottom_link_lang'][$id_lang]) : '')
			),
				'id_menu = '.(int)$id_menu.' AND id_lang = '.(int)$id_lang
		);
	
		}
		
}
			
			
	}


	public static function remove($id_menu, $id_shop)
	{
		Db::getInstance()->delete('megamenuiqit_menus', 'id_menu = '.(int)$id_menu);
		Db::getInstance()->delete('megamenuiqit_menus_lang', 'id_menu = '.(int)$id_menu);
		Db::getInstance()->delete('megamenuiqit_insidem', 'id_menu = '.(int)$id_menu);
		Db::getInstance()->delete('megamenuiqit_menusin_lang', 'id_menu = '.(int)$id_menu);
	}

}

?>
