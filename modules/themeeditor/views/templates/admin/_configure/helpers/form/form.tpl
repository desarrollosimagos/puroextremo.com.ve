{extends file="helpers/form/form.tpl"}

{block name="script"}


$(document).ready(function() {
		$(".fancybox").fancybox();

		 $('.iframe-upload').fancybox({	
			'width'		: 900,
			'height'	: 600,
			'type'		: 'iframe',
      		'autoScale' : false,
      		'autoDimensions': false,
      		 'fitToView' : false,
  			 'autoSize' : false,
  			 onUpdate : function(){ $('.fancybox-iframe').contents().find('a.link').data('field_id', $(this.element).data("input-name"));
			 	 $('.fancybox-iframe').contents().find('a.link').attr('data-field_id', $(this.element).data("input-name"));},
  			 afterShow: function(){
			 	 $('.fancybox-iframe').contents().find('a.link').data('field_id', $(this.element).data("input-name"));
			 	 $('.fancybox-iframe').contents().find('a.link').attr('data-field_id', $(this.element).data("input-name"));
			}
  		  });


		$('#options_tab a').click(function (e) {
		e.preventDefault()
		$(this).tab('show')
	});

var control = $("#font_headings_type");
		
if (control.val() == 1) 
{
$("#heading_google_wrapper").removeClass('hidden');
$("#heading_default_wrapper").addClass('hidden');
}

if (control.val() == 0) {
$("#heading_google_wrapper").addClass('hidden');
$("#heading_default_wrapper").removeClass('hidden');
}




$("#font_headings_type").change(function() {
	var control = $(this);
		
if (control.val() == 1) 
{
$("#heading_google_wrapper").removeClass('hidden');
$("#heading_default_wrapper").addClass('hidden');
}

if (control.val() == 0) {
$("#heading_google_wrapper").addClass('hidden');
$("#heading_default_wrapper").removeClass('hidden');
}

});


var control = $("#font_txt_type");
		
if (control.val() == 1) 
{
$("#txt_google_wrapper").removeClass('hidden');
$("#txt_default_wrapper").addClass('hidden');
}

if (control.val() == 0) {
$("#txt_google_wrapper").addClass('hidden');
$("#txt_default_wrapper").removeClass('hidden');
}

$("#font_txt_type").change(function() {
	var control = $(this);

if (control.val() == 2) 
{
$("#txt_google_wrapper").addClass('hidden');
$("#txt_default_wrapper").addClass('hidden');
}
		
if (control.val() == 1) 
{
$("#txt_google_wrapper").removeClass('hidden');
$("#txt_default_wrapper").addClass('hidden');
}

if (control.val() == 0) {
$("#txt_google_wrapper").addClass('hidden');
$("#txt_default_wrapper").removeClass('hidden');
}

});

});

{/block}

{block name="after"}
{$smarty.block.parent}

<div class="bootstrap panel">
	<h3><i class="icon-cogs"></i> {l s='Latest news from iqit-commerce.com' mod='themeeditor'} </h3>
<iframe width="100%" height="250px" src="http://iqit-commerce.com/iframe/iframe.html" style="border: none; overflow: hidden;"></iframe>
			</div>
{/block}

{block name="defaultForm"}
<div class="row">
<div class="col-lg-2">
<div id="options_tab" class="">
	<ul class="list-group">
{foreach $fields as $tab}
{if $tab.form.tab_name != 'save_tab'}<li {if $tab.form.tab_name == 'main_tab'}class="active"{/if}><a class="list-group-item" href="#{$tab.form.tab_name}">{$tab.form.legend.title}</a></li>{/if}
{/foreach}
<ul>
</div>
</div>
<div class="col-lg-10 tab-content">
{$smarty.block.parent}
</div>
</div>
{/block}

{block name="fieldset"}
{if $fieldset.form.tab_name != 'save_tab'}<div class="tab-pane {if $fieldset.form.tab_name == 'main_tab'}active{/if}" id="{$fieldset.form.tab_name}">{/if}
{$smarty.block.parent}
{if $fieldset.form.tab_name != 'save_tab'}</div>{/if}
{/block}


{block name="input_row"}
{if isset($input.preffix_wrapper)}<div id="{$input.preffix_wrapper}" {if isset($input.wrapper_hidden) && $input.wrapper_hidden} class="hidden"{/if}>{/if}
{if isset($input.upper_separator) && $input.upper_separator}<hr>{/if}
{if isset($input.row_title)}
<div class="col-lg-9 col-lg-offset-3 row-title">{$input.row_title}</div>
{/if}
{$smarty.block.parent}
{if isset($input.separator) && $input.separator}<hr>{/if}
{if isset($input.suffix_wrapper) && $input.suffix_wrapper}</div>{/if}
{/block}

{block name="input"}

    {if $input.type == 'link_url'}
	  <a href="{$input.url}"  data-fancybox-type="iframe" class="btn btn-default fancybox">{$input.label} <i class="icon-angle-right"></i></a>
	{elseif $input.type == 'background_image'}


	<script type="text/javascript"> bgToggle('{$input.selector}'); </script>


	<p> <input id="{$input.name}" type="text" name="{$input.name}" value="{$fields_value[$input.name]|escape:'html':'UTF-8'}"> </p>
	 <a href="filemanager/dialog.php?type=1&field_id={$input.name}" class="btn btn-default iframe-upload"  data-input-name="{$input.name}" type="button">{l s='Background image selector' mod='themeeditor'} <i class="icon-angle-right"></i></a>
	{elseif $input.type == 'background_pattern'}
				 <label class="pattern pattern01" for="{$input.name}_1">
				 <input type="radio" name="{$input.name}"   value="pattern01" id="{$input.name}_1"  {if $fields_value[$input.name] == 'pattern01'}checked="checked"{/if} />
				 </label>
				 
				 <label class="pattern pattern02" for="{$input.name}_2">
				 <input type="radio" name="{$input.name}"   value="pattern02" id="{$input.name}_2" {if $fields_value[$input.name] == 'pattern02'}checked="checked"{/if} />
				 </label>
				 
				 
				 <label class="pattern pattern03" for="{$input.name}_3">
				 <input type="radio" name="{$input.name}"   value="pattern03" id="{$input.name}_3" {if $fields_value[$input.name] == 'pattern03'}checked="checked"{/if} />
				 </label>
				 
				 <label class="pattern pattern04" for="{$input.name}_4">
				 <input type="radio" name="{$input.name}"   value="pattern04" id="{$input.name}_4"  {if $fields_value[$input.name] == 'pattern04'}checked="checked"{/if} />
				 </label>
				 
				 <label class="pattern pattern05" for="{$input.name}_5">
				 <input type="radio" name="{$input.name}"   value="pattern05" id="{$input.name}_5"  {if $fields_value[$input.name] == 'pattern05'}checked="checked"{/if} />
				 </label>
				 
				 <label class="pattern pattern06" for="{$input.name}_6">
				 <input type="radio" name="{$input.name}"   value="pattern06" id="{$input.name}_6"  {if $fields_value[$input.name] == 'pattern06'}checked="checked"{/if} />
				 </label>
				 
				 <label class="pattern pattern07" for="{$input.name}_7">
				 <input type="radio" name="{$input.name}"   value="pattern07" id="{$input.name}_7"   {if $fields_value[$input.name] == 'pattern07'}checked="checked"{/if}  />
				 </label>
				 
				 <label class="pattern pattern08" for="{$input.name}_8">
				 <input type="radio" name="{$input.name}"   value="pattern08" id="{$input.name}_8"   {if $fields_value[$input.name] == 'pattern08'}checked="checked"{/if}  />
				 </label>
				 
				 <label class="pattern pattern09" for="{$input.name}_9">
				 <input type="radio" name="{$input.name}"   value="pattern09" id="{$input.name}_9"   {if $fields_value[$input.name] == 'pattern09'}checked="checked"{/if} />
				 </label>
				 
				 <label class="pattern pattern10" for="{$input.name}_10">
				 <input type="radio" name="{$input.name}"   value="pattern10" id="{$input.name}_10"  {if $fields_value[$input.name] == 'pattern10'}checked="checked"{/if}  />
				 </label>
				 				<label class="pattern pattern11" for="{$input.name}_11">
				 <input type="radio" name="{$input.name}"   value="pattern11" id="{$input.name}_11"  {if $fields_value[$input.name] == 'pattern11'}checked="checked"{/if} />
				 </label>
				 
				 <label class="pattern pattern12" for="{$input.name}_12">
				 <input type="radio" name="{$input.name}"   value="pattern12" id="{$input.name}_12" {if $fields_value[$input.name] == 'pattern12'}checked="checked"{/if} />
				 </label>
				 
				 
				 <label class="pattern pattern13" for="{$input.name}_13">
				 <input type="radio" name="{$input.name}"   value="pattern13" id="{$input.name}_13" {if $fields_value[$input.name] == 'pattern13'}checked="checked"{/if} />
				 </label>
				 
				 <label class="pattern pattern14" for="{$input.name}_14">
				 <input type="radio" name="{$input.name}"   value="pattern14" id="{$input.name}_14"  {if $fields_value[$input.name] == 'pattern14'}checked="checked"{/if} />
				 </label>
				 
				 <label class="pattern pattern15" for="{$input.name}_15">
				 <input type="radio" name="{$input.name}"   value="pattern15" id="{$input.name}_15"  {if $fields_value[$input.name] == 'pattern15'}checked="checked"{/if} />
				 </label>
				 
				 <label class="pattern pattern16" for="{$input.name}_16">
				 <input type="radio" name="{$input.name}"   value="pattern16" id="{$input.name}_16"  {if $fields_value[$input.name] == 'pattern16'}checked="checked"{/if} />
				 </label>
				 
				 <label class="pattern pattern17" for="{$input.name}_17">
				 <input type="radio" name="{$input.name}"   value="pattern17" id="{$input.name}_17"   {if $fields_value[$input.name] == 'pattern17'}checked="checked"{/if}  />
				 </label>
				 
				 <label class="pattern pattern18" for="{$input.name}_18">
				 <input type="radio" name="{$input.name}"   value="pattern18" id="{$input.name}_18"   {if $fields_value[$input.name] == 'pattern18'}checked="checked"{/if}  />
				 </label>
				 
				 <label class="pattern pattern19" for="{$input.name}_19">
				 <input type="radio" name="{$input.name}"   value="pattern19" id="{$input.name}_19"   {if $fields_value[$input.name] == 'pattern19'}checked="checked"{/if} />
				 </label>
				 
				 <label class="pattern pattern20" for="{$input.name}_20">
				 <input type="radio" name="{$input.name}"   value="pattern20" id="{$input.name}_20"  {if $fields_value[$input.name] == 'pattern20'}checked="checked"{/if}  />
				 </label>

				 				<label class="pattern pattern21" for="{$input.name}_21">
				 <input type="radio" name="{$input.name}"   value="pattern21" id="{$input.name}_21"  {if $fields_value[$input.name] == 'pattern21'}checked="checked"{/if} />
				 </label>
				 
				 <label class="pattern pattern22" for="{$input.name}_22">
				 <input type="radio" name="{$input.name}"   value="pattern22" id="{$input.name}_22" {if $fields_value[$input.name] == 'pattern22'}checked="checked"{/if} />
				 </label>
				 
				 
				 <label class="pattern pattern23" for="{$input.name}_23">
				 <input type="radio" name="{$input.name}"   value="pattern23" id="{$input.name}_23" {if $fields_value[$input.name] == 'pattern23'}checked="checked"{/if} />
				 </label>
				 
				 <label class="pattern pattern24" for="{$input.name}_24">
				 <input type="radio" name="{$input.name}"   value="pattern24" id="{$input.name}_24"  {if $fields_value[$input.name] == 'pattern24'}checked="checked"{/if} />
				 </label>
				 
				 <label class="pattern pattern25" for="{$input.name}_25">
				 <input type="radio" name="{$input.name}"   value="pattern25" id="{$input.name}_25"  {if $fields_value[$input.name] == 'pattern25'}checked="checked"{/if} />
				 </label>
				 
				 <label class="pattern pattern26" for="{$input.name}_26">
				 <input type="radio" name="{$input.name}"   value="pattern26" id="{$input.name}_26"  {if $fields_value[$input.name] == 'pattern26'}checked="checked"{/if} />
				 </label>
				 
				 <label class="pattern pattern27" for="{$input.name}_27">
				 <input type="radio" name="{$input.name}"   value="pattern27" id="{$input.name}_27"   {if $fields_value[$input.name] == 'pattern27'}checked="checked"{/if}  />
				 </label>
				 
				 <label class="pattern pattern28" for="{$input.name}_28">
				 <input type="radio" name="{$input.name}"   value="pattern28" id="{$input.name}_28"   {if $fields_value[$input.name] == 'pattern28'}checked="checked"{/if}  />
				 </label>
				 
				 <label class="pattern pattern29" for="{$input.name}_29">
				 <input type="radio" name="{$input.name}"   value="pattern29" id="{$input.name}_29"   {if $fields_value[$input.name] == 'pattern29'}checked="checked"{/if} />
				 </label>
				 
				 <label class="pattern pattern30" for="{$input.name}_30">
				 <input type="radio" name="{$input.name}"   value="pattern30" id="{$input.name}_30"  {if $fields_value[$input.name] == 'pattern30'}checked="checked"{/if}  />
				 </label>

				 				<label class="pattern pattern31" for="{$input.name}_31">
				 <input type="radio" name="{$input.name}"   value="pattern31" id="{$input.name}_31"  {if $fields_value[$input.name] == 'pattern31'}checked="checked"{/if} />
				 </label>
				 
				 <label class="pattern pattern32" for="{$input.name}_32">
				 <input type="radio" name="{$input.name}"   value="pattern32" id="{$input.name}_32" {if $fields_value[$input.name] == 'pattern32'}checked="checked"{/if} />
				 </label>
				 
				 
				 <label class="pattern pattern33" for="{$input.name}_33">
				 <input type="radio" name="{$input.name}"   value="pattern33" id="{$input.name}_33" {if $fields_value[$input.name] == 'pattern33'}checked="checked"{/if} />
				 </label>
				 
				 <label class="pattern pattern34" for="{$input.name}_34">
				 <input type="radio" name="{$input.name}"   value="pattern34" id="{$input.name}_34"  {if $fields_value[$input.name] == 'pattern34'}checked="checked"{/if} />
				 </label>
				 
				 <label class="pattern pattern35" for="{$input.name}_35">
				 <input type="radio" name="{$input.name}"   value="pattern35" id="{$input.name}_35"  {if $fields_value[$input.name] == 'pattern35'}checked="checked"{/if} />
				 </label>
				 
				 <label class="pattern pattern36" for="{$input.name}_36">
				 <input type="radio" name="{$input.name}"   value="pattern36" id="{$input.name}_36"  {if $fields_value[$input.name] == 'pattern36'}checked="checked"{/if} />
				 </label>
				 
				 <label class="pattern pattern37" for="{$input.name}_37">
				 <input type="radio" name="{$input.name}"   value="pattern37" id="{$input.name}_37"   {if $fields_value[$input.name] == 'pattern37'}checked="checked"{/if}  />
				 </label>
				 
				 <label class="pattern pattern38" for="{$input.name}_38">
				 <input type="radio" name="{$input.name}"   value="pattern38" id="{$input.name}_38"   {if $fields_value[$input.name] == 'pattern38'}checked="checked"{/if}  />
				 </label>
				 
				 <label class="pattern pattern39" for="{$input.name}_39">
				 <input type="radio" name="{$input.name}"   value="pattern39" id="{$input.name}_39"   {if $fields_value[$input.name] == 'pattern39'}checked="checked"{/if} />
				 </label>
				 
				 <label class="pattern pattern40" for="{$input.name}_40">
				 <input type="radio" name="{$input.name}"   value="pattern40" id="{$input.name}_40"  {if $fields_value[$input.name] == 'pattern40'}checked="checked"{/if}  />
				 </label>
	 <p class="clear">{l s='Pattern are transparent, so you can use them with background color. Patterns will be apilled only with' mod='themeeditor'}</p>
	<p class="clear">{l s='Pattern credits: www.mfcreative.co.uk, freebiesbug.com, opendent.net and subtlepatterns.com' mod='themeeditor'}</p>
				 						 
	{else}
		{$smarty.block.parent}
    {/if}
{/block}



