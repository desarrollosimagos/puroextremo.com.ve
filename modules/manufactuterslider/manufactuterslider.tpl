<!-- Block manufacturers  slider module -->
{if $manufacturers}

<section id="manufacturers_slider" class=" ">
 <div id="manufacturers_logo_slider"  class="flexslider_carousel">
  <ul class="slides">
   {foreach from=$manufacturers item=manufacturer name=manufacturer_lists}
   {assign var="myfile" value="img/m/{$manufacturer.id_manufacturer|escape:'htmlall':'UTF-8'}-mf_image.jpg"}
   {if file_exists($myfile)}
   <li>
     <a href="{$link->getmanufacturerLink($manufacturer.id_manufacturer, $manufacturer.link_rewrite)}">

      <img src="{$img_manu_dir}{$manufacturer.id_manufacturer|escape:'htmlall':'UTF-8'}-mf_image.jpg" class="img-responsive logo_manufacturer transition-300" {if isset($manufacturerSize)} width="{$manufacturerSize.width}" height="{$manufacturerSize.height}"{/if} title="{$manufacturer.name}" alt="{$manufacturer.name}" />

    </a>
  </li>
  {/if}
  {/foreach}
</ul>
</div>
</section>
{/if}

<!-- /Block manufacturers slider module -->
