<div id="opc_new_account" class="opc-main-block">
	<div id="opc_new_account-overlay" class="opc-overlay" style="display: none;"></div>
	<h2><span>1</span> {l s='Cuenta'}</h2>
	<form action="{$link->getPageLink('authentication', true, NULL, "back=order-opc")|escape:'html'}" method="post" id="login_form" class="std">
		<fieldset>
			<h3>{l s='Ya se ha registrado?'}</h3>
			<p><a href="#" id="openLoginFormBlock">&raquo; {l s='Click aqui'}</a></p>
			<div id="login_form_content" style="display:none;">
				<!-- Error return block -->
				<div id="opc_login_errors" class="error" style="display:none;"></div>
				<!-- END Error return block -->
				<div style="margin-bottom:5px;float:left;width:40%;" class="col_new_acc">
					<label for="login_email">{l s='Dirección de correo electrónico'}</label>
					<span><input type="text" id="login_email" name="email" /></span>
				</div>
				<div style="margin-bottom:5px;float:left;width:40%;" class="col_new_acc">
					<label for="login_passwd">{l s='Contraseña'}</label>
					<span><input type="password" id="login_passwd" name="login_passwd" /></span>
					<a href="{$link->getPageLink('password', true)|escape:'html'}" class="lost_password">{l s='¿Olvidaste tu contraseña?'}</a>
				</div>
				<p class="submit">
					{if isset($back)}<input type="hidden" class="hidden" name="back" value="{$back|escape:'htmlall':'UTF-8'}" />{/if}
					<input type="submit" id="SubmitLogin" name="SubmitLogin" class="button" value="{l s='Iniciar Sesión'}" />
				</p>
			</div>
		</fieldset>
	</form>
	<form action="javascript:;" method="post" id="new_account_form" class="std" autocomplete="on" autofill="on">
		<fieldset>
			<h3 id="new_account_title">{l s='Nuevo Cliente'}</h3>
			<div id="opc_account_choice">
				<div class="opc_float">
					<p class="title_block">{l s='Pedido instantánea'}</p>
					<p>
						<input type="button" class="exclusive_large" id="opc_guestCheckout" value="{l s='Pagar como invitado'}" />
					</p>
				</div>

				<div class="opc_float">
					<p class="title_block">{l s='Crea tu cuenta hoy y disfrute de:'}</p>
					<ul class="bullet">
						<li>{l s='Acceso personalizado y seguro'}</li>
						<li>{l s='Facil y rapido proceso de compra.'}</li>
						<li>{l s='Direcciones de facturación y de envío separadas'}</li>
					</ul>
					<p>
						<input type="button" class="button_large" id="opc_createAccount" value="{l s='Crear una cuenta'}" />
					</p>
				</div>
				<div class="clear"></div>
			</div>
			<div id="opc_account_form">
				{$HOOK_CREATE_ACCOUNT_TOP}
				<script type="text/javascript">
				// <![CDATA[
				idSelectedCountry = {if isset($guestInformations) && $guestInformations.id_state}{$guestInformations.id_state|intval}{else}false{/if};
				{if isset($countries)}
					{foreach from=$countries item='country'}
						{if isset($country.states) && $country.contains_states}
							countries[{$country.id_country|intval}] = new Array();
							{foreach from=$country.states item='state' name='states'}
								countries[{$country.id_country|intval}].push({ldelim}'id' : '{$state.id_state}', 'name' : '{$state.name|escape:'htmlall':'UTF-8'}'{rdelim});
							{/foreach}
						{/if}
						{if $country.need_identification_number}
							countriesNeedIDNumber.push({$country.id_country|intval});
						{/if}	
						{if isset($country.need_zip_code)}
							countriesNeedZipCode[{$country.id_country|intval}] = {$country.need_zip_code};
						{/if}
					{/foreach}
				{/if}
				//]]>
				{literal}
				function vat_number()
				{
					if (($('#company').length) && ($('#company').val() != ''))
						$('#vat_number_block').show();
					else
						$('#vat_number_block').hide();
				}
				function vat_number_invoice()
				{
					if (($('#company_invoice').length) && ($('#company_invoice').val() != ''))
						$('#vat_number_block_invoice').show();
					else
						$('#vat_number_block_invoice').hide();
				}
				$(document).ready(function() {
					$('#company').on('input',function(){
						vat_number();
					});
					$('#company_invoice').on('input',function(){
						vat_number_invoice();
					});
					vat_number();
					vat_number_invoice();
					{/literal}
					$('.id_state option[value={if isset($guestInformations.id_state)}{$guestInformations.id_state|intval}{/if}]').prop('selected', true);
					$('.id_state_invoice option[value={if isset($guestInformations.id_state_invoice)}{$guestInformations.id_state_invoice|intval}{/if}]').prop('selected', true);
					{literal}
				});
				{/literal}
				</script>
				<!-- Error return block -->
				<div id="opc_account_errors" class="error" style="display:none;"></div>
				<!-- END Error return block -->
				<!-- Account -->
				<input type="hidden" id="is_new_customer" name="is_new_customer" value="0" />
				<input type="hidden" id="opc_id_customer" name="opc_id_customer" value="{if isset($guestInformations) && $guestInformations.id_customer}{$guestInformations.id_customer}{else}0{/if}" />
				<input type="hidden" id="opc_id_address_delivery" name="opc_id_address_delivery" value="{if isset($guestInformations) && $guestInformations.id_address_delivery}{$guestInformations.id_address_delivery}{else}0{/if}" />
				<input type="hidden" id="opc_id_address_invoice" name="opc_id_address_invoice" value="{if isset($guestInformations) && $guestInformations.id_address_delivery}{$guestInformations.id_address_delivery}{else}0{/if}" />
				<p class="required text">
					<label for="email">{l s='Email'} <sup>*</sup></label>
					<input type="text" class="text" id="email" name="email" value="{if isset($guestInformations) && $guestInformations.email}{$guestInformations.email}{/if}" />
				</p>
				<p class="required password is_customer_param">
					<label for="passwd">{l s='Contraseña'} <sup>*</sup></label>
					<input type="password" class="text" name="passwd" id="passwd" />
					<span class="form_info">{l s='(cinco caracteres min.)'}</span>
				</p>
				<p class="radio required">
					<span>{l s='Título'}</span>
					{foreach from=$genders key=k item=gender}
						<input type="radio" name="id_gender" id="id_gender{$gender->id_gender}" value="{$gender->id_gender}" {if isset($smarty.post.id_gender) && $smarty.post.id_gender == $gender->id_gender}checked="checked"{/if} />
						<label for="id_gender{$gender->id_gender}" class="top">{$gender->name}</label>
					{/foreach}
				</p>
				<p class="required text">
					<label for="firstname">{l s='Nombres'} <sup>*</sup></label>
					<input type="text" class="text" id="customer_firstname" name="customer_firstname" onblur="$('#firstname').val($(this).val());" value="{if isset($guestInformations) && $guestInformations.customer_firstname}{$guestInformations.customer_firstname}{/if}" />
				</p>
				<p class="required text">
					<label for="lastname">{l s='Apellidos'} <sup>*</sup></label>
					<input type="text" class="text" id="customer_lastname" name="customer_lastname" onblur="$('#lastname').val($(this).val());" value="{if isset($guestInformations) && $guestInformations.customer_lastname}{$guestInformations.customer_lastname}{/if}" />
				</p>
				<p class="select">
					<span>{l s='Fecha de nacimiento'}</span>
					<select id="days" name="days">
						<option value="">-</option>
						{foreach from=$days item=day}
							<option value="{$day|escape:'htmlall':'UTF-8'}" {if isset($guestInformations) && ($guestInformations.sl_day == $day)} selected="selected"{/if}>{$day|escape:'htmlall':'UTF-8'}&nbsp;&nbsp;</option>
						{/foreach}
					</select>
					{*
						{l s='Enero'}
						{l s='Febrero'}
						{l s='Marzo'}
						{l s='Abril'}
						{l s='Mayo'}
						{l s='Junio'}
						{l s='Julio'}
						{l s='Agosto'}
						{l s='Septiembre'}
						{l s='Octubre'}
						{l s='Noviembre'}
						{l s='Diciembre'}
					*}
					<select id="months" name="months">
						<option value="">-</option>
						{foreach from=$months key=k item=month}
							<option value="{$k|escape:'htmlall':'UTF-8'}" {if isset($guestInformations) && ($guestInformations.sl_month == $k)} selected="selected"{/if}>{l s=$month}&nbsp;</option>
						{/foreach}
					</select>
					<select id="years" name="years">
						<option value="">-</option>
						{foreach from=$years item=year}
							<option value="{$year|escape:'htmlall':'UTF-8'}" {if isset($guestInformations) && ($guestInformations.sl_year == $year)} selected="selected"{/if}>{$year|escape:'htmlall':'UTF-8'}&nbsp;&nbsp;</option>
						{/foreach}
					</select>
				</p>
				{if isset($newsletter) && $newsletter}
				<p class="checkbox">
					<input type="checkbox" name="newsletter" id="newsletter" value="1" {if isset($guestInformations) && $guestInformations.newsletter}checked="checked"{/if} autocomplete="off"/>
					<label for="newsletter">{l s='Suscríbase a nuestro boletín de noticias!'}</label>
				</p>
				<p class="checkbox" >
					<input type="checkbox"name="optin" id="optin" value="1" {if isset($guestInformations) && $guestInformations.optin}checked="checked"{/if} autocomplete="off"/>
					<label for="optin">{l s='Recibir ofertas especiales de nuestros socios!'}</label>
				</p>
				{/if}
				<h3>{l s='Dirección de entrega'}</h3>
				{$stateExist = false}
				{$postCodeExist = false}
				{$dniExist = false}
				{foreach from=$dlv_all_fields item=field_name}
				{if $field_name eq "company" && $b2b_enable}
				<p class="text">
					<label for="company">{l s='Empresa'}</label>
					<input type="text" class="text" id="company" name="company" value="{if isset($guestInformations) && $guestInformations.company}{$guestInformations.company}{/if}" />
				</p>
				{elseif $field_name eq "vat_number"}	
				<div id="vat_number_block" style="display:none;">
					<p class="text">
						<label for="vat_number">{l s='RIF'}</label>
						<input type="text" class="text" name="vat_number" id="vat_number" value="{if isset($guestInformations) && $guestInformations.vat_number}{$guestInformations.vat_number}{/if}" />
					</p>
				</div>
				{elseif $field_name eq "dni"}
				{assign var='dniExist' value=true}
				<p class="text">
					<label for="dni">{l s='Número de identificación'}</label>
					<input type="text" class="text" name="dni" id="dni" value="{if isset($guestInformations) && $guestInformations.dni}{$guestInformations.dni}{/if}" />
					<span class="form_info">{l s='DNI / NIF / NIE'}</span>
				</p>
				{elseif $field_name eq "firstname"}
				<p class="required text">
					<label for="firstname">{l s='Nombres'} <sup>*</sup></label>
					<input type="text" class="text" id="firstname" name="firstname" value="{if isset($guestInformations) && $guestInformations.firstname}{$guestInformations.firstname}{/if}" />
				</p>
				{elseif $field_name eq "lastname"}
				<p class="required text">
					<label for="lastname">{l s='Apellidos'} <sup>*</sup></label>
					<input type="text" class="text" id="lastname" name="lastname" value="{if isset($guestInformations) && $guestInformations.lastname}{$guestInformations.lastname}{/if}" />
				</p>
				{elseif $field_name eq "address1"}
				<p class="required text">
					<label for="address1">{l s='Dirección'} <sup>*</sup></label>
					<input type="text" class="text" name="address1" id="address1" value="{if isset($guestInformations) && $guestInformations.address1}{$guestInformations.address1}{/if}" />
				</p>
				{elseif $field_name eq "address2"}
				<p class="text is_customer_param">
					<label for="address2">{l s='Dirección (Linea 2)'}</label>
					<input type="text" class="text" name="address2" id="address2" value="{if isset($guestInformations) && $guestInformations.address2}{$guestInformations.address2}{/if}" />
				</p>
				{elseif $field_name eq "postcode"}
				{$postCodeExist = true}
				<p class="required postcode text">
					<label for="postcode">{l s='Zip / Código Postal'} <sup>*</sup></label>
					<input type="text" class="text" name="postcode" id="postcode" value="{if isset($guestInformations) && $guestInformations.postcode}{$guestInformations.postcode}{/if}" onkeyup="$('#postcode').val($('#postcode').val().toUpperCase());" />
				</p>
				{elseif $field_name eq "city"}
				<p class="required text">
					<label for="city">{l s='Ciudad'} <sup>*</sup></label>
					<input type="text" class="text" name="city" id="city" value="{if isset($guestInformations) && $guestInformations.city}{$guestInformations.city}{/if}" />
				</p>
				{elseif $field_name eq "country" || $field_name eq "Country:name"}
				<p class="required select">
					<label for="id_country">{l s='País'} <sup>*</sup></label>
					<select name="id_country" id="id_country">
						{foreach from=$countries item=v}
						<option value="{$v.id_country}"{if (isset($guestInformations) AND $guestInformations.id_country == $v.id_country) OR (!isset($guestInformations) && $sl_country == $v.id_country)} selected="selected"{/if}>{$v.name|escape:'htmlall':'UTF-8'}</option>
						{/foreach}
					</select>
				</p>
				{elseif $field_name eq "state" || $field_name eq 'State:name'}
				{$stateExist = true}
				<p class="required id_state select" style="display:none;">
					<label for="id_state">{l s='Estado'} <sup>*</sup></label>
					<select name="id_state" id="id_state">
						<option value="">-</option>
					</select>
				</p>
				{/if}
				{/foreach}
				{if !$postCodeExist}
				<p class="required postcode text hidden">
					<label for="postcode">{l s='Zip / Código Postal'} <sup>*</sup></label>
					<input type="text" class="text" name="postcode" id="postcode" value="{if isset($guestInformations) && $guestInformations.postcode}{$guestInformations.postcode}{/if}" onkeyup="$('#postcode').val($('#postcode').val().toUpperCase());" />
				</p>
				{/if}				
				{if !$stateExist}
				<p class="required id_state select hidden">
					<label for="id_state">{l s='Estado'} <sup>*</sup></label>
					<select name="id_state" id="id_state">
						<option value="">-</option>
					</select>
				</p>
				{/if}
				{if !$dniExist}
				<p class="required text dni">
					<label for="dni">{l s='Número de identificación'} <sup>*</sup></label>
					<input type="text" class="text" name="dni" id="dni" value="{if isset($guestInformations) && $guestInformations.dni}{$guestInformations.dni}{/if}" />
					<span class="form_info">{l s='DNI / NIF / NIE'}</span>
				</p>
				{/if}
				<p class="textarea is_customer_param">
					<label for="other">{l s='Información adicional'}</label>
					<textarea name="other" id="other" cols="26" rows="3"></textarea>
				</p>
				{if isset($one_phone_at_least) && $one_phone_at_least}
					<p class="inline-infos required is_customer_param">{l s='Usted debe registrar al menos un número de teléfono.'}</p>
				{/if}								
				<p class="text is_customer_param">
					<label for="phone">{l s='Teléfono de la casa'}</label>
					<input type="text" class="text" name="phone" id="phone" value="{if isset($guestInformations) && $guestInformations.phone}{$guestInformations.phone}{/if}" />
				</p>
				<p class="{if isset($one_phone_at_least) && $one_phone_at_least}required {/if}text">
					<label for="phone_mobile">{l s='Teléfono móvil'}{if isset($one_phone_at_least) && $one_phone_at_least} <sup>*</sup>{/if}</label>
					<input type="text" class="text" name="phone_mobile" id="phone_mobile" value="{if isset($guestInformations) && $guestInformations.phone_mobile}{$guestInformations.phone_mobile}{/if}" />
				</p>
				<input type="hidden" name="alias" id="alias" value="{l s='Mi Dirección'}"/>

				<p class="checkbox">
					<input type="checkbox" name="invoice_address" id="invoice_address"{if (isset($smarty.post.invoice_address) && $smarty.post.invoice_address) || (isset($guestInformations) && $guestInformations.invoice_address)} checked="checked"{/if} autocomplete="off"/>
					<label for="invoice_address"><b>{l s='Utilice otra dirección para la factura'}</b></label>
				</p>

				<div id="opc_invoice_address" class="is_customer_param">
					{assign var=stateExist value=false}
					{assign var=postCodeExist value=false}
					{assign var=dniExist value=false}
					<h3>{l s='Dirección de facturación'}</h3>
					{foreach from=$inv_all_fields item=field_name}
					{if $field_name eq "company" &&  $b2b_enable}
					<p class="text">
						<label for="company_invoice">{l s='Empresa'}</label>
						<input type="text" class="text" id="company_invoice" name="company_invoice" value="" />
					</p>
					{elseif $field_name eq "vat_number"}
					<div id="vat_number_block_invoice" class="is_customer_param" style="display:none;">
						<p class="text">
							<label for="vat_number_invoice">{l s='RIF'}</label>
							<input type="text" class="text" id="vat_number_invoice" name="vat_number_invoice" value="" />
						</p>
					</div>
					{elseif $field_name eq "dni"}
					{assign var='dniExist' value=true}
					<p class="text">
						<label for="dni_invoice">{l s='Número de identificación'}</label>
						<input type="text" class="text" name="dni_invoice" id="dni_invoice" value="{if isset($guestInformations) && $guestInformations.dni_invoice}{$guestInformations.dni_invoice}{/if}" />
						<span class="form_info">{l s='DNI / NIF / NIE'}</span>
					</p>
					{elseif $field_name eq "firstname"}
					<p class="required text">
						<label for="firstname_invoice">{l s='Nombres'} <sup>*</sup></label>
						<input type="text" class="text" id="firstname_invoice" name="firstname_invoice" value="{if isset($guestInformations) && $guestInformations.firstname_invoice}{$guestInformations.firstname_invoice}{/if}" />
					</p>
					{elseif $field_name eq "lastname"}
					<p class="required text">
						<label for="lastname_invoice">{l s='Apellidos'} <sup>*</sup></label>
						<input type="text" class="text" id="lastname_invoice" name="lastname_invoice" value="{if isset($guestInformations) && $guestInformations.lastname_invoice}{$guestInformations.lastname_invoice}{/if}" />
					</p>
					{elseif $field_name eq "address1"}
					<p class="required text">
						<label for="address1_invoice">{l s='Dirección'} <sup>*</sup></label>
						<input type="text" class="text" name="address1_invoice" id="address1_invoice" value="{if isset($guestInformations) && $guestInformations.address1_invoice}{$guestInformations.address1_invoice}{/if}" />
					</p>
					{elseif $field_name eq "address2"}
					<p class="text is_customer_param">
						<label for="address2_invoice">{l s='Dirección (Linea 2)'}</label>
						<input type="text" class="text" name="address2_invoice" id="address2_invoice" value="{if isset($guestInformations) && $guestInformations.address2_invoice}{$guestInformations.address2_invoice}{/if}" />
					</p>
					{elseif $field_name eq "postcode"}
					{$postCodeExist = true}
					<p class="required postcode_invoice text">
						<label for="postcode_invoice">{l s='Zip / Código Postal'} <sup>*</sup></label>
						<input type="text" class="text" name="postcode_invoice" id="postcode_invoice" value="{if isset($guestInformations) && $guestInformations.postcode_invoice}{$guestInformations.postcode_invoice}{/if}" onkeyup="$('#postcode_invoice').val($('#postcode_invoice').val().toUpperCase());" />
					</p>
					{elseif $field_name eq "city"}
					<p class="required text">
						<label for="city_invoice">{l s='Ciudad'} <sup>*</sup></label>
						<input type="text" class="text" name="city_invoice" id="city_invoice" value="{if isset($guestInformations) && $guestInformations.city_invoice}{$guestInformations.city_invoice}{/if}" />
					</p>
					{elseif $field_name eq "country" || $field_name eq "Country:name"}
					<p class="required select">
						<label for="id_country_invoice">{l s='País'} <sup>*</sup></label>
						<select name="id_country_invoice" id="id_country_invoice">
							<option value="">-</option>
							{foreach from=$countries item=v}
							<option value="{$v.id_country}"{if (isset($guestInformations) AND $guestInformations.id_country_invoice == $v.id_country) OR (!isset($guestInformations) && $sl_country == $v.id_country)} selected="selected"{/if}>{$v.name|escape:'htmlall':'UTF-8'}</option>
							{/foreach}
						</select>
					</p>
					{elseif $field_name eq "state" || $field_name eq 'State:name'}
					{$stateExist = true}
					<p class="required id_state_invoice select" style="display:none;">
						<label for="id_state_invoice">{l s='Estado'} <sup>*</sup></label>
						<select name="id_state_invoice" id="id_state_invoice">
							<option value="">-</option>
						</select>
					</p>
					{/if}
					{/foreach}
					{if !$postCodeExist}
					<p class="required postcode_invoice text hidden">
						<label for="postcode_invoice">{l s='Zip / Código Postal'} <sup>*</sup></label>
						<input type="text" class="text" name="postcode_invoice" id="postcode_invoice" value="" onkeyup="$('#postcode').val($('#postcode').val().toUpperCase());" />
					</p>
					{/if}					
					{if !$stateExist}
					<p class="required id_state_invoice select hidden">
						<label for="id_state_invoice">{l s='Estado'} <sup>*</sup></label>
						<select name="id_state_invoice" id="id_state_invoice">
							<option value="">-</option>
						</select>
					</p>
					{/if}
					{if !$dniExist}
					<p class="required text dni_invoice">
						<label for="dni_invoice">{l s='Número de identificación'} <sup>*</sup></label>
						<input type="text" class="text" name="dni_invoice" id="dni_invoice" value="{if isset($guestInformations) && $guestInformations.dni_invoice}{$guestInformations.dni_invoice}{/if}" />
						<span class="form_info">{l s='DNI / NIF / NIE'}</span>
					</p>
					{/if}
					<p class="textarea is_customer_param">
						<label for="other_invoice">{l s='Información adicional'}</label>
						<textarea name="other_invoice" id="other_invoice" cols="26" rows="3"></textarea>
					</p>
					{if isset($one_phone_at_least) && $one_phone_at_least}
						<p class="inline-infos required is_customer_param">{l s='Usted debe registrar al menos un número de teléfono.'}</p>
					{/if}					
					<p class="text is_customer_param">
						<label for="phone_invoice">{l s='Teléfono de la casa'}</label>
						<input type="text" class="text" name="phone_invoice" id="phone_invoice" value="{if isset($guestInformations) && $guestInformations.phone_invoice}{$guestInformations.phone_invoice}{/if}" />
					</p>
					<p class="{if isset($one_phone_at_least) && $one_phone_at_least}required {/if}text">
						<label for="phone_mobile_invoice">{l s='Teléfono móvil'}{if isset($one_phone_at_least) && $one_phone_at_least} <sup>*</sup>{/if}</label>
						<input type="text" class="text" name="phone_mobile_invoice" id="phone_mobile_invoice" value="{if isset($guestInformations) && $guestInformations.phone_mobile_invoice}{$guestInformations.phone_mobile_invoice}{/if}" />
					</p>
					<input type="hidden" name="alias_invoice" id="alias_invoice" value="{l s='Mi Dirección de facturación'}" />
				</div>
				{$HOOK_CREATE_ACCOUNT_FORM}
				<p class="submit">
					<input type="submit" class="exclusive button" name="submitAccount" id="submitAccount" value="{l s='Guardar'}" />
				</p>
				<p style="display: none;" id="opc_account_saved">
					{l s='La información de cuenta guardado correctamente'}
				</p>
				<p class="required opc-required" style="clear: both;">
					<sup>*</sup>{l s='campos obligatorios'}
				</p>
				<!-- END Account -->
			</div>
		</fieldset>
	</form>
	<div class="clear"></div>
</div>