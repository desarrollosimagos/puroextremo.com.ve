
<div id="ModuleAdditionalproductstabs" class="panel product-tab">
	<input type="hidden" name="submitted_tabs[]" value="ModuleAdditionalproductstabs" />
	<h3>{l s='Add or modify customizable properties' mod='additionalproductstabs'}</h3>

	{if isset($display_common_field) && $display_common_field}
		<div class="alert alert-info">{l s='Warning, if you change the value of fields with an orange bullet %s, the value will be changed for all other shops for this product'  mod='additionalproductstabs' sprintf=$bullet_common_field}</div>
	{/if}
<div class="form-group">		
		<label class="control-label col-lg-3">
			{include file="controllers/products/multishop/checkbox.tpl" field="active" type="radio" onclick=""}
			{l s='Enabled' mod='additionalproductstabs'}
		</label>
		<div class="col-lg-9">
			<span class="switch prestashop-switch fixed-width-lg">
				<input  type="radio" name="activeAdditionalTab" id="activeAdditionalTab_on" value="1" {if $activeAdditionalTab}checked="checked" {/if} />
				<label for="activeAdditionalTab_on" class="radioCheck">
					{l s='Yes' mod='additionalproductstabs'}
				</label>
				<input type="radio" name="activeAdditionalTab" id="activeAdditionalTab_off" value="0" {if !$activeAdditionalTab}checked="checked"{/if} />
				<label for="activeAdditionalTab_off" class="radioCheck">
					{l s='No' mod='additionalproductstabs'}
				</label>
				<a class="slide-button btn"></a>
			</span>
		</div>
	</div>

	<div class="form-group">
			
		<label class="control-label col-lg-3" for="titleAdditionalTab_{$id_lang}">
			<span class="label-tooltip" data-toggle="tooltip"
				title="{l s='The public name for this product.' mod='additionalproductstabs'} {l s='Invalid characters:' mod='additionalproductstabs'} &lt;&gt;;=#{}">
				{l s='Name'}
			</span>
		</label>
		<div class="col-lg-5">
			{include file="controllers/products/input_text_lang.tpl"
				languages=$languages
				input_value=$titleAdditionalTab
				input_name="titleAdditionalTab"
			}
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-lg-3" for="contentAdditionalTab_{$id_lang}">
			{include file="controllers/products/multishop/checkbox.tpl" field="contentAdditionalTab" type="tinymce" multilang="true"}
			<span class="label-tooltip" data-toggle="tooltip"
				title="{l s='Appears on product tab' mod='additionalproductstabs'}">
				{l s='Tab content' mod='additionalproductstabs'}
			</span>
		</label>
		<div class="col-lg-9">
			{include
				file="controllers/products/textarea_lang.tpl"
				languages=$languages
				input_name="contentAdditionalTab"
				class="rte"
				input_value=$contentAdditionalTab}
		</div>
	</div>

<script language="javascript" type="text/javascript">
	$(function() {
		tinySetup();
	});
</script>
	<div class="panel-footer">
		<a href="{$link->getAdminLink('AdminProducts')}" class="btn btn-default"><i class="process-icon-cancel"></i> {l s='Cancel'}</a>
		<button type="submit" name="submitAddproduct" class="btn btn-default pull-right"><i class="process-icon-save"></i> {l s='Save'}</button>
		<button type="submit" name="submitAddproductAndStay" class="btn btn-default pull-right"><i class="process-icon-save"></i> {l s='Save and stay'}</button>
	</div>
</div>
