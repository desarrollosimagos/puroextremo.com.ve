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
*  @version  Release: $Revision$
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<!-- Module advancedslider -->
{if isset($advancedslider_slides) && $advancedslider_slides|@count >= 1}
<section id="sequence-theme">
<div id="sequence">
<ul class="sequence-direction-nav">
<li><span class="sequence-next"></span></li>
<li><span class="sequence-prev"></span></li>
</ul>
	<ul class="sequence-canvas">
{foreach from=$advancedslider_slides item=slide name=advancedslider_slides}
	{if $slide.active}
    		<li id="slide_li_{$slide.id_slide}" {if $smarty.foreach.advancedslider_slides.iteration == 1} class="animate-in" {/if}>
            <div class="container sequence_frame_wrapper seqwrap">
					{if (isset($slide.title) && $slide.title!='') || (isset($slide.legend) && $slide.legend!='') || (isset($slide.description) && $slide.description!='')}<div class="slide_{$slide.id_slide} seqslideText">
							{if isset($slide.title) && $slide.title!=''}<h2>{$slide.title}</h2>{/if}
                            {if isset($slide.legend) && $slide.legend!=''}<h3>{$slide.legend}</h3>{/if}
							{if isset($slide.description) && $slide.description!=''}<p class="desc">{$slide.description}</p>{/if}
						</div>{/if}
                        {foreach from=$slide.s_images item=s_image}
                    
                    {assign var="imgLink" value="{$module_template_dir}uploads/{$s_image.s_image}"}
                        <img class="slide_{$slide.id_slide}_image_{$s_image.id_image}" src="{$link->getMediaLink($imgLink)|escape:'html'}"  alt="{$s_image.s_image}" />
                        {/foreach}
                        </div>
						{if isset($slide.url) && $slide.url!=''}<a href="{$slide.url}" class="slidelink">{$slide.url}</a>{/if}
					</li>
    	{/if}
{/foreach}
		</ul>
        	<ul class="sequence-pagination">
        {foreach from=$advancedslider_slides item=slide name=advancedslider_slides}
	{if $slide.active}

<li>x</li>
    	{/if}
{/foreach}
	</ul>                    
		
			
</div>
			</section>
{/if}
<!-- /Module advancedslider -->
