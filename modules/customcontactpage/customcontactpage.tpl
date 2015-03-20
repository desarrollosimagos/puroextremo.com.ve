{*
* 2007-2014 PrestaShop
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
*  @copyright  2007-2014 PrestaShop SA

*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<!-- MODULE Block contact infos -->
<section id="customcontactpage">
	<div class="row">
	<div class="col-xs-12 col-sm-8">
	<div id="mapcontact"></div>
	</div>	
	<div class="text_info col-xs-12 col-sm-4">
        <h4 class="page-subheading">{l s='Store Information' mod='customcontactpage'}</h4>
        <ul>
            <li>
                <strong>{$customcontactpage_company|escape:'html':'UTF-8'}</strong>
            </li>
            {if $customcontactpage_address != ''}
            	<li>
            		<i class="icon-map-marker"></i> {$customcontactpage_address|escape:'html':'UTF-8'}
            	</li>
            {/if}
            {if $customcontactpage_phone != ''}
            	<li>
            		<i class="icon-phone"></i> {l s='Call us now:' mod='customcontactpage'} 
            		<span>{$customcontactpage_phone|escape:'html':'UTF-8'}</span>
            	</li>
            {/if}
            {if $customcontactpage_email != ''}
            	<li>
            		<i class="icon-envelope-alt"></i> {l s='Email:' mod='customcontactpage'} 
            		<span>{mailto address=$customcontactpage_email|escape:'html':'UTF-8' encode="hex"}</span>
            	</li>
            {/if}
            {if $customcontactpage_text != ''}
            	<li class="customcontactpage_text">
            	{$customcontactpage_text}
            	</li>
            {/if}
        </ul>
    </div></div>
</section>
<!-- /MODULE Block contact infos -->
