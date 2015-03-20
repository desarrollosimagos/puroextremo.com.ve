{*
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
*  @version  Release: $Revision: 6844 $
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<!-- Block header links module -->
<ul id="header_links" class="clearfix">

    	{foreach from=$headerlinks_links item=headerlinks_link}
		{if isset($headerlinks_link.$lang)} 
			<li><a href="{$headerlinks_link.$url|escape}"{if $headerlinks_link.newWindow} onclick="window.open(this.href);return false;"{/if}>{$headerlinks_link.$lang|escape}</a></li>
		{/if}
	{/foreach}
    
    	{if isset($showcontactlink) && $showcontactlink==1}<li id="header_link_contact"><a href="{$link->getPageLink('contact', true)|escape:'html'}" title="{l s='Contact' mod='headerlinks'}">{l s='Contact' mod='headerlinks'}</a></li>{/if}
	{if isset($showsitemaplink) && $showsitemaplink==1}<li id="header_link_sitemap"><a href="{$link->getPageLink('sitemap')|escape:'html'}" title="{l s='Sitemap' mod='headerlinks'}">{l s='Sitemap' mod='headerlinks'}</a></li>{/if}
    {if isset($title) && $title!=''} 
    {if (isset($showcontactlink) && $showcontactlink==1) || (isset($showsitemaplink) && $showsitemaplink==1)}<li class="separator">|</li>{/if}
    
    <li>{$title}</li>{/if}
</ul>
<!-- /Block header links module -->
