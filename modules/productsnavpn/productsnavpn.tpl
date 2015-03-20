{if $prevLink != NULL OR $nextLink != NULL}
<div id="productsnavpn" class="pull-right"> 
{if $prevLink != NULL}<a href="{$prevLink}" class="p_prev_link transition-300" title="{l s='Previous product' mod='productsnavpn'} - {$prevName}"><i class="icon-angle-left"></i></a>{/if} 
{if $nextLink != NULL}<a href="{$nextLink}" class="p_next_link transition-300" title="{l s='Next product' mod='productsnavpn'} - {$nextName}"><i class="icon-angle-right"></i></a>{/if}
</div>
{/if}