<?php
if (!defined('_PS_VERSION_'))
    exit;

class SendReviewRequest extends Module
{
    public function __construct()
    {
        $this->name = 'sendreviewrequest';
        $this->tab = 'emailing';
        $this->version = '0.3';
        $this->author = 'www.kik-off.com';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.5', 'max' => '1.6');
        $this->dependencies = array('productcomments');

        parent::__construct();

        $this->displayName = $this->l('Send Review Request');
        $this->description = $this->l('Send a review request after order state is delivered.');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
    }

    public function install()
    {
        if (parent::install() == false
			OR !$this->registerHook('header')
			OR !$this->registerHook('actionOrderStatusPostUpdate')
			OR !Configuration::updateValue('SENDREVIEWREQUEST', 5)
			)
            return false;
        return true;
    }

	public function uninstall()
    {
        if (!parent::uninstall() ||
		!Configuration::deleteByName('SENDREVIEWREQUEST') ||
        !$this->unregisterHook('header') ||
		!$this->unregisterHook('actionOrderStatusPostUpdate')
		)
            return false;
        return true;
    }

	public function getContent()
	{
	    $tab = Tab::getTab($this->context->language->id, Tab::getIdFromClassName(Tools::getValue('controller')));
		$tab_name = $tab['name'];
		$token = Tools::getAdminTokenLite('AdminModules');
		$this->_html = '
		<style type="text/css">
		    #imprint{width: 100%; text-align: right;}
            #imprint-logo {float: left;}
		</style>
		<div class="toolbar-placeholder">
		    <div class="toolbarBox toolbarHead">
			    <div class="pageTitle">
				    <h3><span style="font-weight: normal;" id="current_obj"><span class="breadcrumb item-0">'.$tab_name.'</span> : <span class="breadcrumb item-1">'.$this->displayName.'</span></span></h3>
			    </div>
		    </div>
	    </div>';

		if(Tools::isSubmit('sendReviewRequestFormSubmit'))
		{
		    $order_state = Tools::getValue('order_state');
			Configuration::updateValue('SENDREVIEWREQUEST', $order_state);
		}

		$this->displayForm();

		$this->_html .= '
		<br/>
		<div id="imprint">
		    <fieldset>
			<span id="imprint-logo"><a href="http://www.kik-off.com" target="_blank" title="http://www.kik-off.com"><img src="'.$this->_path.'logo_kik_off.gif" alt="" /></a> '.$this->displayName.' v'.$this->version.'</span>
			<span id="imprint-text">'.$this->l('Module by').': <a href="http://www.kik-off.com" target="_blank" title="http://www.kik-off.com">www.kik-off.com</a>, <a href="mailto:info@kik-off.com">info@kik-off.com</a></span>
			</fieldset>
		</div>';
		return $this->_html;
	}

	public function displayForm()
	{
		$id_lang = $this->context->language->id;
		$order_states = OrderState::getOrderStates($id_lang);
		
		$this->_html .= '
		<fieldset>
		    <form action="'.Tools::safeOutput($_SERVER['REQUEST_URI']).'" method="post"><br/>
			<label>'.$this->l('Order state').'</label>
			<div class="margin-form">
				<select name="order_state">';

			    foreach($order_states as $i => $value)
				{
				    $this->_html .= '<option value="'.$value['id_order_state'].'"'.((int)Configuration::get('SENDREVIEWREQUEST') == $value['id_order_state'] ? ' selected="selected" ' : '').'/>'.$value['name'].'</option>';
				}

			$this->_html .= '
			    </select>
				<p class="preference_description clear">'.$this->l('Select the order status you want to send email. By default(Delivered)').'.</p>
			</div>
			<div class="margin-form">
				<input class="button" type="submit" name="sendReviewRequestFormSubmit" value="'.$this->l('Save').'" />
			</div>
			</form>
		</fieldset>';

		return $this->_html;
	}

	protected function getProducts($order)
	{
		$products = $order->getProducts();

		return $products;
	}

	public function hookActionOrderStatusPostUpdate($params)
    {
		$id_order_state = (int)Tools::getValue('id_order_state');
		$order_state = (int)Configuration::get('SENDREVIEWREQUEST');

		if($id_order_state == $order_state)
		{
		    if ($context == null)
			    $context = Context::getContext();

			$id_order = Tools::getValue('id_order');
			$order = new Order((int)$id_order);
			$id_lang = $order->id_lang;
			$products_list = '';
			$file_attachment = array();
			
			foreach($this->getProducts($order) as $review_product)
			{
				$product = new Product((int)$review_product['id_product'], true, (int)$id_lang);
				$image = Image::getCover((int)$review_product['id_product']);
				$product_link = Context::getContext()->link-> getProductLink((int)$review_product['id_product'], $product->link_rewrite, $product->category, $product->ean13, $id_lang, (int)$order->id_shop, 0, true);
				$image_url =  Context::getContext()->link->getImageLink($product->link_rewrite, $image['id_image'], 'small_default');
				$file_attachment .= array('content' => $image_url, 'name' => $product->name, 'mime' => 'image/jpg');
				
				$products_list .=
					'<tr style="background-color: '.($key % 2 ? '#DDE2E6' : '#EBECEE').';">
						<td style="padding: 0.6em 0.4em;width: 25%;text-align: center;"><img src="'.$image_url.'" title="'.$product->name.'" alt="'.$product->name.'" /></td>
						<td style="padding: 0.6em 0.4em;width: 75%;text-align: left;"><strong><a href="'.$product_link.'#post_review" title="'.$this->l('Click to go to product page').'">'.$product->name.'</a></strong></td>
					</tr>';
			}

			$data = array(
			    '{firstname}' => $this->context->customer->firstname,
			    '{lastname}' => $this->context->customer->lastname,
			    '{products}' => $this->formatProductForEmail($products_list)
		    );

		    if (Validate::isEmail($this->context->customer->email))
		        Mail::Send(
		            (int)$id_lang, 'post_review',
			        Mail::l('Send your reviews', (int)$id_lang),
			        $data, $this->context->customer->email,
			        $this->context->customer->firstname.' '.$this->context->customer->lastname,
			        $file_attachment, null, null, null, dirname(__FILE__).'/mails/', false, (int)$order->id_shop
		        );
		}
    }

	public function formatProductForEmail($content)
	{
		return $content;
	}
	
	public function hookHeader($params)
	{
		if(Tools::getValue('id_product'))
		{
		    $html = '
			<script type="text/javascript">
			    $(document).ready(function(){
				    var hash = window.location.hash;
                    if(hash == \'#post_review\'){
				        $(\'#product_comments_block_extra\').find(\'.open-comment-form\').click();
					}
				});
			</script>';
		
		    return $html;
		}
	}
}
?>