<?php
include_once(dirname(__FILE__).'/StaticBlockClass.php');
class csstaticblocks extends Module
{
	protected $error = false;
	private $_html;
	private $myHook = array('displayTop','topleft','topcontent','displayLeftColumn','displayRightColumn','displayFooter','displayHome', 'csslideshow','footertop','footerbottom','copyright');
	
	public function __construct()
	{
	 	$this->name = 'csstaticblocks';
	 	$this->tab = 'MyBlocks';
	 	$this->version = '1.0';
		$this->author = 'Codespot';
	 	parent::__construct();

		$this->displayName = $this->l('Cs Static block');
		$this->description = $this->l('Adds static blocks with free content');
		$this->confirmUninstall = $this->l('Are you sure that you want to delete your static blocks?');
	
	}
	public function init_data()
	{
		$content_block1 = '<div class="free_shipping">Free shipping on orders of $150 + and free returns</div>';
		$content_block1_fr='<div class="free_shipping">Free shipping on orders of $150 + and free returns</div>';
		
		$content_block2 = '<ul class="cs_top_links">\r\n<li><a title="" href="#">Shop</a></li>\r\n<li><a title="" href="#">LookBook</a></li>\r\n<li><a title="" href="#">Blog</a></li>\r\n<li><a title="" href="#">About</a></li>\r\n<li><a title="" href="#">Features</a></li>\r\n</ul>';
		$content_block2_fr='<ul class="cs_top_links">\r\n<li><a title="" href="#">Shop</a></li>\r\n<li><a title="" href="#">LookBook</a></li>\r\n<li><a title="" href="#">Blog</a></li>\r\n<li><a title="" href="#">About</a></li>\r\n<li><a title="" href="#">Features</a></li>\r\n</ul>';
	
		$content_block3 = '<div class="cs_home_banner_block">\r\n<p class="item"><a title="" href="#"><img src="{static_block_url}themes/boutiques/img/cms/bn_1.jpg" alt="" /></a></p>\r\n<p class="item"><a title="" href="#"><img src="{static_block_url}themes/boutiques/img/cms/bn_2.jpg" alt="" /></a></p>\r\n<p class="item"><a title="" href="#"><img src="{static_block_url}themes/boutiques/img/cms/bn_3.jpg" alt="" /></a></p>\r\n<p class="item bn_4"><a title="" href="#"><img src="{static_block_url}themes/boutiques/img/cms/bn_4.png" alt="" /></a></p>\r\n</div>';
		$content_block3_fr='<div class="cs_home_banner_block">\r\n<p class="item"><a title="" href="#"><img src="{static_block_url}themes/boutiques/img/cms/bn_1.jpg" alt="" /></a></p>\r\n<p class="item"><a title="" href="#"><img src="{static_block_url}themes/boutiques/img/cms/bn_2.jpg" alt="" /></a></p>\r\n<p class="item"><a title="" href="#"><img src="{static_block_url}themes/boutiques/img/cms/bn_3.jpg" alt="" /></a></p>\r\n<p class="item bn_4"><a title="" href="#"><img src="{static_block_url}themes/boutiques/img/cms/bn_4.png" alt="" /></a></p>\r\n</div>';
		
		$content_block4 ='<div class="cs_follow_online ">\r\n<h4>Online Boutique for Men and Women</h4>\r\n<p>Any shopaholic would fall in love with this layout, ET Boutiques Responsive Prestashop theme. Super neat design, really modern and high fashion skin for the website.<br /> With variety available colors, probably, your website will be congruent with your store image. <br /> Need to change? You want a very own color combination? Pieces of cake, you can change it to any color you wish with color variation function.</p>\r\n<div class="cs_i_follow"><span class="label">Follow Us:</span>\r\n<p><a title="facebook" href="https://www.facebook.com/emthemes"><img src="{static_block_url}themes/boutiques/img/cms/i_face.png" alt="" width="32" height="32" /></a> <a title="twitter" href="https://twitter.com/eggthemes"><img src="{static_block_url}themes/boutiques/img/cms/i_twitter.png" alt="" width="32" height="32" /></a> <a title="print" href="http://pinterest.com/pinterest"><img src="{static_block_url}themes/boutiques/img/cms/i_p.png" alt="" width="32" height="32" /></a><a title="youtube" href="http://www.youtube.com/user/username"><img src="{static_block_url}themes/boutiques/img/cms/i_youtube.png" alt="" width="32" height="32" /></a><a title="google" href="https://plus.google.com/114285862387604007220/posts"><img src="{static_block_url}themes/boutiques/img/cms/i_g.png" alt="" width="32" height="32" /></a></p>\r\n</div>\r\n</div>';
		$content_block4_fr='<div class="cs_follow_online ">\r\n<h4>Online Boutique for Men and Women</h4>\r\n<p>Any shopaholic would fall in love with this layout, ET Boutiques Responsive Prestashop theme. Super neat design, really modern and high fashion skin for the website.<br /> With variety available colors, probably, your website will be congruent with your store image. <br /> Need to change? You want a very own color combination? Pieces of cake, you can change it to any color you wish with color variation function.</p>\r\n<div class="cs_i_follow"><span class="label">Follow Us:</span>\r\n<p><a title="facebook" href="https://www.facebook.com/emthemes"><img src="{static_block_url}themes/boutiques/img/cms/i_face.png" alt="" width="32" height="32" /></a> <a title="twitter" href="https://twitter.com/eggthemes"><img src="{static_block_url}themes/boutiques/img/cms/i_twitter.png" alt="" width="32" height="32" /></a> <a title="print" href="http://pinterest.com/pinterest"><img src="{static_block_url}themes/boutiques/img/cms/i_p.png" alt="" width="32" height="32" /></a><a title="youtube" href="http://www.youtube.com/user/username"><img src="{static_block_url}themes/boutiques/img/cms/i_youtube.png" alt="" width="32" height="32" /></a><a title="google" href="https://plus.google.com/114285862387604007220/posts"><img src="{static_block_url}themes/boutiques/img/cms/i_g.png" alt="" width="32" height="32" /></a></p>\r\n</div>\r\n</div>';
		
		$content_block5='<div class="grid_4 omega">\r\n<h4 class="title_block">Further Info</h4>\r\n<ul>\r\n<li><a href="#">Free Gift Wrapping</a></li>\r\n<li><a href="#">Security</a></li>\r\n<li><a href="#">Privacy Policy</a></li>\r\n<li><a href="#">Coppyright</a></li>\r\n</ul>\r\n</div>';
		
		$content_block5_fr='<div class="grid_4 omega">\r\n<h4 class="title_block">Further Info</h4>\r\n<ul>\r\n<li><a href="#">Free Gift Wrapping</a></li>\r\n<li><a href="#">Security</a></li>\r\n<li><a href="#">Privacy Policy</a></li>\r\n<li><a href="#">Coppyright</a></li>\r\n</ul>\r\n</div>';
		
		$content_block6='<div class="cs_payment">\r\n<p>We accept the following credit / debit cards:</p>\r\n<p><a href="#"><img src="{static_block_url}themes/boutiques/img/cms/i_paypal.jpg" alt="" width="37" height="21" /></a><a href="#"><img src="{static_block_url}themes/boutiques/img/cms/i_master.jpg" alt="" width="37" height="21" /><img src="{static_block_url}themes/boutiques/img/cms/i_visa.jpg" alt="" width="37" height="21" /></a><a href="#"><img src="{static_block_url}themes/boutiques/img/cms/_american.jpg" alt="" width="37" height="21" /></a></p>\r\n</div>';
		
		$content_block6_fr='<div class="cs_payment">\r\n<p>We accept the following credit / debit cards:</p>\r\n<p><a href="#"><img src="{static_block_url}themes/boutiques/img/cms/i_paypal.jpg" alt="" width="37" height="21" /></a><a href="#"><img src="{static_block_url}themes/boutiques/img/cms/i_master.jpg" alt="" width="37" height="21" /><img src="{static_block_url}themes/boutiques/img/cms/i_visa.jpg" alt="" width="37" height="21" /></a><a href="#"><img src="{static_block_url}themes/boutiques/img/cms/_american.jpg" alt="" width="37" height="21" /></a></p>\r\n</div>';
		
		$content_block7='<ul class="bottom_nav">\r\n<li><a href="{static_block_url}index.php">Home</a></li>\r\n<li><a href="{static_block_url}index.php?id_cms=4&amp;controller=cms"> Privacy</a></li>\r\n<li><a href="{static_block_url}index.php?id_cms=4&amp;controller=cms"> Terms &amp; Conditions</a></li>\r\n<li><a href="{static_block_url}index.php?controller=sitemap"> Sitemap</a></li>\r\n<li><a href="{static_block_url}index.php?id_cms=4&amp;controller=cms"> About</a></li>\r\n</ul>\r\n<p class="copy_right">© 2013 <a href="#">Boutiques Prestashop</a> Store. All rights reserved</p>';
		
		$content_block7_fr='<ul class="bottom_nav">\r\n<li><a href="{static_block_url}index.php">Home</a></li>\r\n<li><a href="{static_block_url}index.php?id_cms=4&amp;controller=cms"> Privacy</a></li>\r\n<li><a href="{static_block_url}index.php?id_cms=4&amp;controller=cms"> Terms &amp; Conditions</a></li>\r\n<li><a href="{static_block_url}index.php?controller=sitemap"> Sitemap</a></li>\r\n<li><a href="{static_block_url}index.php?id_cms=4&amp;controller=cms"> About</a></li>\r\n</ul>\r\n<p class="copy_right">© 2013 <a href="#">Boutiques Prestashop</a> Store. All rights reserved</p>';

		$id_hook_topleft = Hook::getIdByName('topleft');
		$id_hook_topcontent = Hook::getIdByName('topcontent');
		$id_hook_home = Hook::getIdByName('displayhome');
		$id_hook_footertop = Hook::getIdByName('footertop');
		$id_hook_footer = Hook::getIdByName('displayfooter');
		$id_hook_footerbottom = Hook::getIdByName('footerbottom');
		$id_hook_copyright = Hook::getIdByName('copyright');
		
		$id_en = Language::getIdByIso('en');
		$id_fr = Language::getIdByIso('fr');
		$id_shop = Configuration::get('PS_SHOP_DEFAULT');
		
		//install static Block
		if(!Db::getInstance()->Execute('INSERT INTO `'._DB_PREFIX_.'staticblock` (`id_block`, `identifier_block`, `hook`, `is_active`) 
			VALUES (1, "free-shipping","'.$id_hook_topleft.'", 1),
				(2, "top_links","'.$id_hook_topcontent.'", 1),
				(3, "home-banner","'.$id_hook_home.'", 1),
				(4, "follow-us-text","'.$id_hook_footertop.'", 1),
				(5, "further_info","'.$id_hook_footer.'", 1),
				(6, "payment","'.$id_hook_footerbottom.'", 1),
				(7, "allright_links","'.$id_hook_copyright.'", 1);') OR
		// Install Static Block _shop
		!Db::getInstance()->Execute('INSERT INTO `'._DB_PREFIX_.'staticblock_shop` (`id_block`, `id_shop`, `is_active`)
			VALUES 	(1,'.$id_shop.', 1),
					(2,'.$id_shop.', 1),
					(3,'.$id_shop.', 1),
					(4,'.$id_shop.', 1),
					(5,'.$id_shop.', 1),
					(6,'.$id_shop.', 1),
					(7,'.$id_shop.', 1);') OR
		// static block lang
		!Db::getInstance()->Execute('INSERT INTO `'._DB_PREFIX_.'staticblock_lang` (`id_block`, `id_lang`, `id_shop`, `title`, `content`) 
			VALUES 
			( "1", "'.$id_en.'","'.$id_shop.'","Free Shipping", \''.$content_block1.'\'),
			( "1", "'.$id_fr.'","'.$id_shop.'","Free Shipping", \''.$content_block1_fr.'\'),
			( "2", "'.$id_en.'","'.$id_shop.'","Top Links", \''.$content_block2.'\'),
			( "2", "'.$id_fr.'","'.$id_shop.'","Top Links", \''.$content_block2_fr.'\'),
			( "3", "'.$id_en.'","'.$id_shop.'","Home Banner", \''.$content_block3.'\'),
			( "3","'.$id_fr.'","'.$id_shop.'","Home Banner", \''.$content_block3_fr.'\'),
			( "4", "'.$id_en.'","'.$id_shop.'","Follow Us and Text", \''.$content_block4.'\'),
			( "4", "'.$id_fr.'","'.$id_shop.'","Follow Us and Text", \''.$content_block4_fr.'\'),
			( "5", "'.$id_en.'","'.$id_shop.'","Further Info", \''.$content_block5.'\'),
			( "5", "'.$id_fr.'","'.$id_shop.'","Further Info", \''.$content_block5_fr.'\'),
			( "6","'.$id_en.'","'.$id_shop.'","Payment", \''.$content_block6.'\'),
			( "6", "'.$id_fr.'","'.$id_shop.'","Payment", \''.$content_block6_fr.'\'),
			( "7", "'.$id_en.'","'.$id_shop.'","All Right + links", \''.$content_block7.'\'),
			( "7", "'.$id_fr.'","'.$id_shop.'","All Right + links", \''.$content_block7_fr.'\');')
		)
			return false;
		return true;
		
	}
	
	public function install()
	{		
	 	if (parent::install() == false OR !$this->registerHook('header') OR !$this->registerHook('actionShopDataDuplication'))
	 		return false;
		foreach ($this->myHook AS $hook){
			if ( !$this->registerHook($hook))
				return false;
		}
	 	if (!Db::getInstance()->Execute('CREATE TABLE '._DB_PREFIX_.'staticblock (`id_block` int(10) unsigned NOT NULL AUTO_INCREMENT, `identifier_block` varchar(255) NOT NULL DEFAULT \'\', `hook` int(10) unsigned, `is_active` tinyint(1) NOT NULL DEFAULT \'1\', PRIMARY KEY (`id_block`),UNIQUE KEY `identifier_block` (`identifier_block`)) ENGINE=InnoDB default CHARSET=utf8'))
	 		return false;
		if (!Db::getInstance()->Execute('CREATE TABLE '._DB_PREFIX_.'staticblock_shop (`id_block` int(10) unsigned NOT NULL,`id_shop` int(10) unsigned NOT NULL,`is_active` tinyint(1) NOT NULL DEFAULT \'1\',PRIMARY KEY (`id_block`,`id_shop`)) ENGINE=InnoDB default CHARSET=utf8'))
	 		return false;
		if (!Db::getInstance()->Execute('CREATE TABLE '._DB_PREFIX_.'staticblock_lang (`id_block` int(10) unsigned NOT NULL, `id_lang` int(10) unsigned NOT NULL,`id_shop` int(10) unsigned NOT NULL, `title` varchar(255) NOT NULL DEFAULT \'\', `content` mediumtext, PRIMARY KEY (`id_block`,`id_lang`,`id_shop`)) ENGINE=InnoDB default CHARSET=utf8'))
	 		return false;
		$this->init_data();
	 	return true;
	}
	
	public function uninstall()
	{
		
	 	if (parent::uninstall() == false)
	 		return false;
	 	if (!Db::getInstance()->Execute('DROP TABLE '._DB_PREFIX_.'staticblock') OR !Db::getInstance()->Execute('DROP TABLE '._DB_PREFIX_.'staticblock_shop') OR !Db::getInstance()->Execute('DROP TABLE '._DB_PREFIX_.'staticblock_lang'))
	 		return false;
	 	return true;
	}
	
	private function _displayHelp()
	{
		$this->_html .= '
		<br/>
	 	<fieldset>
			<legend><img src="'.$this->_path.'logo.gif" alt="" title="" /> '.$this->l('Static block Helper').'</legend>
			<div>This module customize static contents on the site. Static contents are displayed at the position of the hook : top, left, home,right, footer.</div>
		</fieldset>';
	}
	
	public function getContent()
   	{
		$this->_html = '<h2>'.$this->displayName.'</h2>';
		$this->_postProcess();
		if (Tools::isSubmit('addBlock'))
			$this->_displayAddForm();
		elseif (Tools::isSubmit('editBlock'))
			$this->_displayUpdateForm();
		else
			$this->_displayForm();
		$this->_displayHelp();
		return $this->_html;
	}
	
	private function _postProcess()
	{
		global $currentIndex;
		$errors = array();
		if (Tools::isSubmit('saveBlock'))
		{
			$block = new StaticBlockClass(Tools::getValue('id_block'));
			$block->copyFromPost();
			$errors = $block->validateController();		
			if (sizeof($errors))
			{
				$this->_html .= $this->displayError(implode('<br />', $errors));
			}
			else
			{
				Tools::getValue('id_block') ? $block->update() : $block->add();
				Tools::redirectAdmin($currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'&saveBlockConfirmation');
			}
		}
		elseif (Tools::isSubmit('changeStatusStaticblock') AND Tools::getValue('id_block'))
		{
			$stblock = new StaticBlockClass(Tools::getValue('id_block'));
			$stblock->updateStatus(Tools::getValue('status'));
			Tools::redirectAdmin($currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'));
		}
		elseif (Tools::isSubmit('deleteBlock') AND Tools::getValue('id_block'))
		{
			$block = new StaticBlockClass(Tools::getValue('id_block'));
			$block->delete();
			Tools::redirectAdmin($currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'&deleteBlockConfirmation');
		}
		elseif (Tools::isSubmit('saveBlockConfirmation'))
			$this->_html = $this->displayConfirmation($this->l('Static block has been saved successfully'));
		elseif (Tools::isSubmit('deleteBlockConfirmation'))
			$this->_html = $this->displayConfirmation($this->l('Static block deleted successfully'));
		
	}
	
	private  function clearCacheBlockForHook($hook)
	{
		
		$this->_clearCache('csstaticblocks_'.strtolower($this->getHookName($hook)).'.tpl');
	}
	
	private function getHookName($id_hook,$name=false)
	{
		if (!$result = Db::getInstance()->getRow('
			SELECT `name`,`title`
			FROM `'._DB_PREFIX_.'hook` 
			WHERE `id_hook` = '.(int)($id_hook)))
			return false;
		return $result['name'];
	}

	private function getBlocks()
	{
		$this->context = Context::getContext();
		$id_lang = $this->context->language->id;
		$id_shop = $this->context->shop->id;
	 	if (!$result = Db::getInstance()->ExecuteS(
			'SELECT b.id_block, b.identifier_block, b.hook, bs.is_active, bl.`title`, bl.`content` 
			FROM `'._DB_PREFIX_.'staticblock` b 
			LEFT JOIN `'._DB_PREFIX_.'staticblock_shop` bs ON (b.`id_block` = bs.`id_block` )
			LEFT JOIN `'._DB_PREFIX_.'staticblock_lang` bl ON (b.`id_block` = bl.`id_block`'.( $id_shop ? 'AND bl.`id_shop` = '.$id_shop : ' ' ).') 
			WHERE bl.id_lang = '.(int)$id_lang.
			( $id_shop ? ' AND bs.`id_shop` = '.$id_shop : ' ' )))
	 		return false;
	 	return $result;
	}
	
	private function getHookTitle($id_hook,$name=false)
	{
		if (!$result = Db::getInstance()->getRow('
			SELECT `name`,`title`
			FROM `'._DB_PREFIX_.'hook` 
			WHERE `id_hook` = '.(int)($id_hook)))
			return false;
		return (($result['title'] != "" && $name) ? $result['title'] : $result['name']);
	}
	
	private function _displayForm()
	{
		global $currentIndex, $cookie;
	 	$this->_html .= '
		
	 	<fieldset>
			<legend><img src="'.$this->_path.'logo.gif" alt="" title="" /> '.$this->l('List of static blocks').'</legend>
			<p><a href="'.$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'&addBlock"><img src="'._PS_ADMIN_IMG_.'add.gif" alt="" /> '.$this->l('Add a new block').'</a></p><br/>
			<table width="100%" class="table" cellspacing="0" cellpadding="0">
			<thead>
			<tr class="nodrag nodrop">
				<th>'.$this->l('ID').'</th>
				<th class="center">'.$this->l('Title').'</th>
				<th class="center">'.$this->l('Identifier').'</th>
				<th class="center">'.$this->l('Hook into').'</th>
				<th class="right">'.$this->l('Active').'</th>
			</tr>
			</thead>
			<tbody>';
		$s_blocks = $this->getBlocks();
		if (is_array($s_blocks))
		{
			static $irow;
			foreach ($s_blocks as $block)
			{
				$this->_html .= '
				<tr class="'.($irow++ % 2 ? 'alt_row' : '').'">
					<td class="pointer" onclick="document.location = \''.$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'&editBlock&id_block='.$block['id_block'].'\'">'.$block['id_block'].'</td>
					<td class="pointer center" onclick="document.location = \''.$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'&editBlock&id_block='.$block['id_block'].'\'">'.$block['title'].'</td>
					<td class="pointer center" onclick="document.location = \''.$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'&editBlock&id_block='.$block['id_block'].'\'">'.$block['identifier_block'].'</td>
					<td class="pointer center" onclick="document.location = \''.$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'&editBlock&id_block='.$block['id_block'].'\'">'.(Validate::isInt($block['hook']) ? $this->getHookTitle($block['hook']) : '').'</td>
					<td class="pointer center"> <a href="'.$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'&changeStatusStaticblock&id_block='.$block['id_block'].'&status='.$block['is_active'].'&hook='.$block['hook'].'">'.($block['is_active'] ? '<img src="'._PS_ADMIN_IMG_.'enabled.gif" alt="Enabled" title="Enabled" />' : '<img src="'._PS_ADMIN_IMG_.'disabled.gif" alt="Disabled" title="Disabled" />').'</a> </td>
				</tr>';
			}
		}
		$this->_html .= '
			</tbody>
			</table>
		</fieldset>';
			
		
	}
	
	private function _displayAddForm()
	{
		global $currentIndex, $cookie;
	 	// Language 
	 	$defaultLanguage = (int)(Configuration::get('PS_LANG_DEFAULT'));
		$languages = Language::getLanguages(false);
		$divLangName = 'titlediv¤contentdiv';
		// TinyMCE
		$iso = Language::getIsoById((int)($cookie->id_lang));
		$isoTinyMCE = (file_exists(_PS_ROOT_DIR_.'/js/tiny_mce/langs/'.$iso.'.js') ? $iso : 'en');
		$ad = dirname($_SERVER["PHP_SELF"]);
		$this->_html .=  '
		<script type="text/javascript">	
		var iso = \''.$isoTinyMCE.'\' ;
		var pathCSS = \''._THEME_CSS_DIR_.'\' ;
		var ad = \''.$ad.'\' ;
		</script>
		<script type="text/javascript" src="'.__PS_BASE_URI__.'js/tiny_mce/tiny_mce.js"></script>
		<script type="text/javascript" src="'.__PS_BASE_URI__.'js/tinymce.inc.js"></script>
		<script type="text/javascript">id_language = Number('.$defaultLanguage.');</script>	
		<script type="text/javascript">
		$(document).ready(function(){		
			tinySetup({});});
		</script>
		';
		// Form
		$this->_html .= '
		<fieldset>
			<legend><img src="'.$this->_path.'logo.gif" alt="" title="" /> '.$this->l('New block').'</legend>
			<form method="post" action="'.Tools::safeOutput($_SERVER['REQUEST_URI']).'" enctype="multipart/form-data">
				<label>'.$this->l('Title:').'</label>
				<div class="margin-form">';
					foreach ($languages as $language)
					{
						$this->_html .= '
					<div id="titlediv_'.$language['id_lang'].'" style="display: '.($language['id_lang'] == $defaultLanguage ? 'block' : 'none').'; float: left;">
						<input type="text" name="title_'.$language['id_lang'].'" value="'.Tools::getValue('title_'.$language['id_lang']).'" size="55" /><sup> *</sup>
					</div>';
					}
					$this->_html .= $this->displayFlags($languages, $defaultLanguage, $divLangName, 'titlediv', true);	
					$this->_html .= '
					<div class="clear"></div>
				</div>
				
				<label>'.$this->l('Identifier:').'</label>
				<div class="margin-form">
					<div id="identifierdiv" style="float: left;">
						<input type="text" name="identifier_block" value="'.Tools::getValue('identifier_block').'" size="55" /><sup> *</sup>
					</div>
					<p class="clear">'.$this->l('Identifier must be unique').'. '.$this->l('Match a-zA-Z-_0-9').'</p>
					<div class="clear"></div>
				</div>
				
				<label>'.$this->l('Hook into:').'</label>
				<div class="margin-form">
					<div id="hookdiv" style="float: left;">
						<select name="hook">
							<option value="0">'.$this->l('None').'</option>';

		foreach ($this->myHook AS $hook){
			$id_hook = Hook::getIdByName($hook);
			$this->_html .= '<option value="'.$id_hook.'"'.($id_hook == Tools::getValue('hook') ? 'selected="selected"' : '').'>'.$this->getHookTitle($id_hook).'</option>';
		}
		
		$this->_html .= '
						</select>
					</div>
					<div class="clear"></div>
				</div>
				
				<label>'.$this->l('Active:').'</label>
				<div class="margin-form">
					<div id="activediv" style="float: left;">
						<input type="radio" name="is_active" value="1"'.(Tools::getValue('is_active',1) ? 'checked="checked"' : '').' />
						<label class="t"><img src="'._PS_ADMIN_IMG_.'enabled.gif" alt="Enabled" title="Enabled" /></label>
						<input type="radio" name="is_active" value="0"'.(Tools::getValue('is_active',1) ? '' : 'checked="checked"').' />
						<label class="t"><img src="'._PS_ADMIN_IMG_.'disabled.gif" alt="Disabled" title="Disabled" /></label>
					</div>
					<div class="clear"></div>
				</div>
				
				<label>'.$this->l('Content:').'</label>
				<div class="margin-form">';									
					foreach ($languages as $language)
					{
						$this->_html .= '
					<div id="contentdiv_'.$language['id_lang'].'" style="display: '.($language['id_lang'] == $defaultLanguage ? 'block' : 'none').'; float: left;">
						<textarea class="rte" name="content_'.$language['id_lang'].'" id="contentInput_'.$language['id_lang'].'" cols="100" rows="20">'.Tools::getValue('content_'.$language['id_lang']).'</textarea>
					</div>';
					}
					$this->_html .= $this->displayFlags($languages, $defaultLanguage, $divLangName, 'contentdiv', true);
					$this->_html .= '
					<div class="clear"></div>
				</div>			
				<div class="margin-form">';
					$this->_html .= '<input type="submit" class="button" name="saveBlock" value="'.$this->l('Save Block').'" id="saveBlock" />
									';
					$this->_html .= '					
				</div>
				
			</form>
			<a href="'.$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'"><img src="'._PS_ADMIN_IMG_.'arrow2.gif" alt="" />'.$this->l('Back to list').'</a>
		</fieldset>';
	}
	
	private function _displayUpdateForm()
	{
		global $currentIndex, $cookie;
		if (!Tools::getValue('id_block'))
		{
			$this->_html .= '<a href="'.$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'"><img src="'._PS_ADMIN_IMG_.'arrow2.gif" alt="" />'.$this->l('Back to list').'</a>';
			return;
		}

		$block = new StaticBlockClass((int)Tools::getValue('id_block'));
	 	// Language 
	 	$defaultLanguage = (int)(Configuration::get('PS_LANG_DEFAULT'));
		$languages = Language::getLanguages(false);
		$divLangName = 'titlediv¤contentdiv';
		// TinyMCE
		$iso = Language::getIsoById((int)($cookie->id_lang));
		$isoTinyMCE = (file_exists(_PS_ROOT_DIR_.'/js/tiny_mce/langs/'.$iso.'.js') ? $iso : 'en');
		$ad = dirname($_SERVER["PHP_SELF"]);
		$this->_html .=  '
		<script type="text/javascript">	
		var iso = \''.$isoTinyMCE.'\' ;
		var pathCSS = \''._THEME_CSS_DIR_.'\' ;
		var ad = \''.$ad.'\' ;
		</script>
		<script type="text/javascript" src="'.__PS_BASE_URI__.'js/tiny_mce/tiny_mce.js"></script>
		<script type="text/javascript" src="'.__PS_BASE_URI__.'js/tinymce.inc.js"></script>
		<script type="text/javascript">id_language = Number('.$defaultLanguage.');</script>	
		<script type="text/javascript">
		$(document).ready(function(){		
			tinySetup({});});
		</script>
		';
		// Form
		$this->_html .= '
		<fieldset>
			<legend><img src="'.$this->_path.'logo.gif" alt="" title="" /> '.$this->l('Edit block').' '.$block->identifier_block.'</legend>
			<form method="post" action="'.Tools::safeOutput($_SERVER['REQUEST_URI']).'" enctype="multipart/form-data">
				<input type="hidden" name="id_block" value="'.(int)$block->id_block.'" id="id_block" />
				<div class="margin-form">
					<input type="submit" class="button " name="deleteBlock" value="'.$this->l('Delete Block').'" id="deleteBlock" onclick="if (!confirm(\'Are you sure that you want to delete this static blocks?\')) return false "/>
				</div>
				<label>'.$this->l('Title:').'</label>
				<div class="margin-form">';
					foreach ($languages as $language)
					{
						$this->_html .= '
					<div id="titlediv_'.$language['id_lang'].'" style="display: '.($language['id_lang'] == $defaultLanguage ? 'block' : 'none').'; float: left;">
						<input type="text" name="title_'.$language['id_lang'].'" value="'.(isset($block->title[$language['id_lang']]) ? $block->title[$language['id_lang']] : '').'" size="55" /><sup> *</sup>
					</div>';
					}
					$this->_html .= $this->displayFlags($languages, $defaultLanguage, $divLangName, 'titlediv', true);	
					$this->_html .= '
					<div class="clear"></div>
				</div>
				
				<label>'.$this->l('Identifier:').'</label>
				<div class="margin-form">
					<div id="identifierdiv" style="float: left;">
						<input type="text" name="identifier_block" value="'.$block->identifier_block.'" size="55" /><sup> *</sup>
					</div>
					<p class="clear">'.$this->l('Identifier must be unique').'. '.$this->l('Match a-zA-Z-_0-9').'</p>
					<div class="clear"></div>
				</div>
				
				<label>'.$this->l('Hook into:').'</label>
				<div class="margin-form">
					<div id="hookdiv" style="float: left;">
						<select name="hook">
							<option value="0">'.$this->l('None').'</option>';
		foreach ($this->myHook AS $hook){
			$id_hook = Hook::getIdByName($hook);
			$this->_html .= '<option value="'.$id_hook.'"'.($id_hook == $block->hook ? 'selected="selected"' : '').'>'.$this->getHookTitle($id_hook).'</option>';
		}
		$this->_html .= '
						</select>
					</div>
					<div class="clear"></div>
				</div>
				
				<label>'.$this->l('Status:').'</label>
				<div class="margin-form">
					<div id="activediv" style="float: left;">
						<input type="radio" name="is_active" '.($block->is_active ? 'checked="checked"' : '').' value="1" />
						<label class="t"><img src="'._PS_ADMIN_IMG_.'enabled.gif" alt="Enabled" title="Enabled" /></label>
						<input type="radio" name="is_active" '.($block->is_active ? '' : 'checked="checked"').' value="0" />
						<label class="t"><img src="'._PS_ADMIN_IMG_.'disabled.gif" alt="Disabled" title="Disabled" /></label>
					</div>
					<div class="clear"></div>
				</div>
				
				<label>'.$this->l('Content:').'</label>
				<div class="margin-form">';									
					foreach ($languages as $language)
					{
						$this->_html .= '
					<div id="contentdiv_'.$language['id_lang'].'" style="display: '.($language['id_lang'] == $defaultLanguage ? 'block' : 'none').'; float: left;">
						<textarea class="rte" name="content_'.$language['id_lang'].'" id="contentInput_'.$language['id_lang'].'" cols="100" rows="20">'.(isset($block->content[$language['id_lang']]) ? $block->content[$language['id_lang']] : '').'</textarea>
					</div>';
					}
					$this->_html .= $this->displayFlags($languages, $defaultLanguage, $divLangName, 'contentdiv', true);
					$this->_html .= '
					<div class="clear"></div>
				</div>			
				<div class="margin-form">';
					$this->_html .= '<input type="submit" class="button" name="saveBlock" value="'.$this->l('Save Block').'" id="saveBlock" />';
					$this->_html .= '					
				</div>
				
			</form>
			<a href="'.$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'"><img src="'._PS_ADMIN_IMG_.'arrow2.gif" alt="" />'.$this->l('Back to list').'</a>
		</fieldset>';
	}

	public function contentById($id_block)
	{
		global $cookie;

		$staticblock = new StaticBlockClass($id_block);
		return ($staticblock->is_active ? $staticblock->content[(int)$cookie->id_lang] : '');
	}
	
	public function contentByIdentifier($identifier)
	{
		global $cookie;

		if (!$result = Db::getInstance()->getRow('
			SELECT `id_block`,`identifier_block`
			FROM `'._DB_PREFIX_.'staticblock` 
			WHERE `identifier_block` = \''.$identifier.'\''))
			return false;
		$staticblock = new StaticBlockClass($result['id_block']);
		return ($staticblock->is_active ? $staticblock->content[(int)$cookie->id_lang] : '');
	}
	
	private function getBlockInHook($hook_name)
	{
		$block_list = array();
		$this->context = Context::getContext();
		$id_shop = $this->context->shop->id;
		$id_hook = Hook::getIdByName($hook_name);
		if ($id_hook)
		{
			$results = Db::getInstance()->ExecuteS('SELECT b.`id_block` FROM `'._DB_PREFIX_.'staticblock` b
			LEFT JOIN `'._DB_PREFIX_.'staticblock_shop` bs ON (b.id_block = bs.id_block)
			WHERE bs.is_active = 1 AND (bs.id_shop = '.(int)$id_shop.') AND b.`hook` = '.(int)($id_hook));
			foreach ($results as $row)
			{
				$temp = new StaticBlockClass($row['id_block']);
				$block_list[] = $temp;
			}
		}	
		
		return $block_list;
	}
	
	function hookHeader($params)
	{
		global $smarty;
		$smarty->assign(array(
			'HOOK_TOP_CONTENT' => Hook::Exec('topcontent'),
			'HOOK_FOOTER_BOTTOM' => Hook::Exec('footerbottom'),
			'HOOK_COPYRIGHT' => Hook::Exec('copyright')
		));
	}
	
	
	public function hookDisplayTop()
	{
		global $smarty, $cookie;
		
		$block_list = $this->getBlockInHook('displayTop');
		
		$smarty->assign(array(
			'block_list' => $block_list
		));
		return $this->display(__FILE__, 'csstaticblocks_displaytop.tpl');
	}
	
	public function hookTopLeft()
	{
		global $smarty, $cookie;
		
		$block_list = $this->getBlockInHook('topleft');
		
		$smarty->assign(array(
			'block_list' => $block_list
		));
		return $this->display(__FILE__, 'csstaticblocks_topleft.tpl');
	}
	
	public function hookTopContent()
	{
		global $smarty, $cookie;
		
		$block_list = $this->getBlockInHook('topcontent');
		
		$smarty->assign(array(
			'block_list' => $block_list
		));
		
		return $this->display(__FILE__, 'csstaticblocks_topcontent.tpl');
	}
	
	public function hookDisplayLeftColumn()
	{
		global $smarty, $cookie;
		
		$block_list = $this->getBlockInHook('displayLeftColumn');
		
		$smarty->assign(array(
			'block_list' => $block_list
		));
		
		return $this->display(__FILE__, 'csstaticblocks_displayleftcolumn.tpl');
	}
	
	public function hookDisplayRightColumn()
	{
		global $smarty, $cookie;
		
		$block_list = $this->getBlockInHook('displayRightColumn');
		
		$smarty->assign(array(
			'block_list' => $block_list
		));
		return $this->display(__FILE__, 'csstaticblocks_displayrightcolumn.tpl');
	}
	
	public function hookDisplayFooter()
	{
		global $smarty, $cookie;
		
			$block_list = $this->getBlockInHook('displayFooter');
			$smarty->assign(array(
				'block_list' => $block_list
			));
		
		return $this->display(__FILE__, 'csstaticblocks_displayfooter.tpl');
	}
	
	public function hookDisplayHome()
	{
		global $smarty, $cookie;
		
			$block_list = $this->getBlockInHook('displayHome');
			$smarty->assign(array(
				'block_list' => $block_list
			));
		return $this->display(__FILE__, 'csstaticblocks_displayhome.tpl');
	}
	public function hookFooterTop()
	{
		global $smarty, $cookie;
		
			$block_list = $this->getBlockInHook('footertop');
			$smarty->assign(array(
				'block_list' => $block_list
			));
		
		return $this->display(__FILE__, 'csstaticblocks_footertop.tpl');
	}
	
	public function hookFooterBottom()
	{
		global $smarty, $cookie;
			$block_list = $this->getBlockInHook('footerbottom');
			$smarty->assign(array(
				'block_list' => $block_list
			));
		
		return $this->display(__FILE__, 'csstaticblocks_footerbottom.tpl');
	}
	public function hookCopyRight()
	{
		global $smarty, $cookie;
			$block_list = $this->getBlockInHook('copyright');
			$smarty->assign(array(
				'block_list' => $block_list
			));
		return $this->display(__FILE__, 'csstaticblocks_copyright.tpl');
	}
	
	public function hookActionShopDataDuplication($params)
	{
		//duplicate static block for shop
		Db::getInstance()->execute('
		INSERT IGNORE INTO '._DB_PREFIX_.'staticblock_shop (`id_block`, `id_shop`, `is_active`)
		SELECT id_block, '.(int)$params['new_id_shop'].', is_active 
		FROM '._DB_PREFIX_.'staticblock_shop
		WHERE id_shop = '.(int)$params['old_id_shop']);
		
		//duplicate hometab language for shop
		Db::getInstance()->execute('
		INSERT IGNORE INTO '._DB_PREFIX_.'staticblock_lang (`id_block`, `id_lang`, `id_shop`, `title`, `content`)
		SELECT id_block,id_lang, '.(int)$params['new_id_shop'].', title,content
		FROM '._DB_PREFIX_.'staticblock_lang
		WHERE id_shop = '.(int)$params['old_id_shop']);
	}
	
}
?>
