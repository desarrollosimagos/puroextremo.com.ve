{if $status == 'ok'}
    <center>
    <center>{$imgBanner}</center>
    </center>
    <br />
    <h3>{l s='¡Enhorabuena! Su pedido ha sido generado correctamente.' mod='mercadopago'}</h3>
    <p>{l s='El importe de tu compra es:' mod='mercadopago'} <span class="price">{$totalApagar}</span></p>
    <p>{l s='Para pagar con el botón de abajo' mod='mercadopago'}</p>
    <p>{l s='Si tiene alguna pregunta por favor, utilice el' mod='mercadopago'}	<a href="{$base_dir}contact-form.php">{l s='formulario de contacto' mod='cheque'}</a>.</p>
    <br />
    {$formmercadopago}
{else}
    <p class="warning">
        {l s='Hubo alguna falla en la presentación de su solicitud. Por favor, póngase en contacto con nuestro soporte' mod='mercadopago'} 
        <a href="{$base_dir}contact-form.php">{l s='atención al cliente' mod='mercadopago'}</a>.
    </p>
{/if}
