{*
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
*}

<!-- Block permanent links module HEADER -->
<ul id="header_links">
	<li class="first"><a href="{$link->getPageLink('index', true)}" title="{l s='home' mod='csblockpermanentlinks'}" {if $page_name=='index'}class="active"{/if}>{l s='inicio' mod='csblockpermanentlinks'}</a></li>
	<li><a href="{$link->getPageLink('cms', true)}&amp;id_cms=4" title="{l s='about us' mod='csblockpermanentlinks'}" {if $page_name=='cms'}class="active"{/if}>{l s='nosotros' mod='csblockpermanentlinks'}</a></li>
		
	<li id="header_link_sitemap"><a href="{$link->getPageLink('sitemap')}" title="{l s='sitemap' mod='csblockpermanentlinks'}" {if $page_name=='sitemap'}class="active"{/if}>{l s='Mapa del Sitio' mod='csblockpermanentlinks'}</a></li>
	<li id="header_link_contact" class="last"><a href="{$link->getPageLink('contact', true)}" title="{l s='contact' mod='csblockpermanentlinks'}" {if $page_name=='contact'}class="active"{/if}>{l s='contactos' mod='csblockpermanentlinks'}</a></li>
</ul>
<!-- /Block permanent links module HEADER -->
