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

{if $comparator_max_item}
{if !isset($paginationId) || $paginationId == ''}
<script type="text/javascript">
// <![CDATA[
	var min_item = '{l s='Por favor, seleccione al menos un producto' js=1}';
	var max_item = "{l s='No se puede agregar más de %d producto (s) para la comparación de productos' sprintf=$comparator_max_item js=1}";
//]]>
</script>
{/if}
	<form method="post" action="{$link->getPageLink('products-comparison')|escape:'html'}" onsubmit="true" class="compare">
		<p>
		<input type="submit" id="bt_compare" class="button" value="{l s='Comparar'}" />
		<input type="hidden" name="compare_product_list" class="compare_product_list" value="" />
		</p>
	</form>
{/if}

