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

			{if !$content_only}
					</div><!-- /Center -->
			{if $page_name != 'index'}
				{if isset($settings)}
					{if ($settings->column == '2_column_right' || $settings->column == '3_column')}
					<!-- Right -->
							<div id="right_column" class="{if isset($settings)} {if $page_name == 'index'}{$settings->right_class_home} {else}{$settings->right_class}{/if} {else}grid_4{/if} omega">
								{if isset($HOOK_RIGHT_COLUMN) && $HOOK_RIGHT_COLUMN}{$HOOK_RIGHT_COLUMN}{/if}
							</div>
					{/if}
				{/if}
			{/if}
				</div><!--/columns-->
			</div><!--/container_24-->
			</div>
				<div class="mode_footer_top">
					<div class="container_24">
						<div id="footer_top">
						{if isset($HOOK_FOOTER_TOP) && $HOOK_FOOTER_TOP}{$HOOK_FOOTER_TOP}{/if}
						</div>
					</div>
				</div>
			</div>
<!-- Footer -->
			<div class="mode_footer">				
				<div class="mode_footer_main">
					<div class="container_24">
						<div id="footer">
							{$HOOK_FOOTER}
						</div>
					</div>
					<div class="container_24">
						<div id="footer_bottom">
							{if isset($HOOK_FOOTER_BOTTOM) && $HOOK_FOOTER_BOTTOM}{$HOOK_FOOTER_BOTTOM}{/if}
							
						</div>
					</div>
					<div class="container_24">
						<div id="footer_copyright">
							{if isset($HOOK_COPYRIGHT) && $HOOK_COPYRIGHT}{$HOOK_COPYRIGHT}{/if}
						</div>
					</div>
				</div>
			</div>			
		</div><!--/page-->
	{/if}
	</body>
</html>
