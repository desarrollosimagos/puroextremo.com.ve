<?php

/**
 * Envio de Newsletter pelo Admin Prestashop 1.2
 * @category admin
 *
 * @author Odlanier de Souza <master_odlanier@hotmail.com>
 * @contributor Leighton Whiting http://blog.leightonwhiting.com 
 * @copyright prestashopbr.com
 * @license http://www.opensource.org/licenses/osl-3.0.php Open-source licence 3.0
 * @version 1.4
 *
 */

session_start();

function recursive_in_array($needle, $haystack) {
    foreach ($haystack as $stalk) {
        if ($needle === $stalk || (is_array($stalk) && recursive_in_array($needle, $stalk))) {
            return true;
        }
    }
    return false;
}

include_once (PS_ADMIN_DIR . '/../classes/AdminTab.php');

class AdminNewsletter extends AdminTab
{

    public function display()
    {
        global $currentIndex, $cookie;

        if (Tools::getValue('send') != 1)
        {
            $_POST                      =   isset($_SESSION['newsletter']['POST']) ? $_SESSION['newsletter']['POST'] : null;

            $iso = Language::getIsoById(intval($cookie->id_lang));

            echo $this->html = '
		<h2>' . $this->l('Newsletter') 	. '</h2><p>'  . 
				$this->l('Variables:') 	. '   '	.
				$this->l('%FIRSTNAME%') . ' - ' . 
				$this->l('%LASTNAME%') 	. ' - ' . 
				$this->l('%MAIL%') 		. '</p>
		
		<form action="' . $currentIndex . '&token=' . $this->token .
                '&send=1" method="post" >
		<fieldset>
		<legend>'.$this->l('Subject').':</legend><br/>
		<input type="text" name="subject_email" style="width: 100%" value="'.Tools::getValue('subject_email').'" />
		</fieldset>
		
		<br/>
		
		<fieldset>
		<legend>'.$this->l('Message').':</legend>
		
		<br/>
						
		<script type="text/javascript" src="../js/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
                <script type="text/javascript">
            	tinyMCE.init({
            		// General options
            		mode : "textareas",
            		theme : "advanced",
                        languages : "pt",
            		plugins : "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount",
            
            		// Theme options
            		theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
            		theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
            		theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
            		theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",
            		theme_advanced_toolbar_location : "top",
            		theme_advanced_toolbar_align : "left",
            		theme_advanced_statusbar_location : "bottom",
            		theme_advanced_resizing : true,
            
            		// Example content CSS (should be your site CSS)
            		content_css : "css/content.css",
            
                        save_enablewhendirty : true,
                        save_onsavecallback : "mysave",
            
            		// Drop lists for link/image/media/template dialogs
            		template_external_list_url : "../js/tinymce/jscripts/tiny_mce/lists/template_list.php",
            		external_link_list_url : "lists/link_list.js",
            		external_image_list_url : "lists/image_list.js",
            		media_external_list_url : "lists/media_list.js",
            
            		// Replace values for the template plugin
            		template_replace_values : {
            			username : "Some User",
            			staffid : "991234"
            		}
            	});
                </script>
		
		<textarea name="body_email" style="width:100%; height: 300px;">
                '.Tools::getValue('body_email').'
		</textarea>

        <br />
        
       <div align="left">

            <h2> Configuration: </h2>
            <div style="margin-left: 15px">
                Emails per minute:
                <input type="text" name="wait_time" size="2" value="2" maxlength="2" /></label> <br /><br />
            </div>

            <h2> Test </h2>
            <div style="margin-left: 15px">
                <input type="checkbox" name="sTeste" value="1" /> Mailer Test <br /><br /> &nbsp;&nbsp;&nbsp;
                Email: <input type="text" name="sMailTest" size="30" /></label> <br /><br />
            </div>

            <h2> Block Subscribers </h2>
            <div style="margin-left: 15px">
                <input type="checkbox" name="$sNewsletter" value="1" /> Newsletter Block Subscribers <br /><br /> &nbsp;&nbsp;&nbsp;
                Name: <input type="text" name="name_Subscribers" size="30" /> <br /><br /> &nbsp;&nbsp;&nbsp;
                Lastname: <input type="text" name="lname_Subscribers" size="30" /> <br /><br />
            </div>

            <h2> Customers </h2>
            <div style="margin-left: 15px">
                <input type="radio" name="sCustomers" value="0" checked="checked"  /> None <br /><br />
                <input type="radio" name="sCustomers" value="1"  /> All Customers <br /><br />
                <input type="radio" name="sCustomers" value="2"  /> Customers who signed up for Newsletter <br /><br />
                <input type="radio" name="sCustomers" value="3"  /> Customers birthday <br /><br /> &nbsp;&nbsp;&nbsp;
                    <input type="text" size="6" maxlength="5" value="'.date("d-m").'" name="dateBrithday" /> Format: d-m
            </div>
        
        </div>
        
        <br />        
                
        <div align="right">                
		<input type="submit" onclick="return confirm(\''.$this->l('Want to send emails ?').'\');" name="send_newsletter" value="'.$this->l('Send Mail').'" class="button" />
		</div>
                
		</fieldset>
		</form>
		';

            unset( $_SESSION['newsletter'] );
            $_SESSION['newsletter']['POST']           =   $_POST;
        }
    }

    public function postProcess()
    {
        global $currentIndex, $cookie;

        if (Tools::getValue('send') == 1)
        {
            if( !isset($_SESSION['newsletter']) OR empty($_SESSION['newsletter']['finalList']) ){

                $sCustomers 		= Tools::getValue('sCustomers');
                $sNewsletter 		= Tools::getValue('$sNewsletter');
                $sTeste                 = Tools::getValue('sTeste');
                $sendList               = Array();

                /** BEGIN Mailer test **/
                if( $sTeste AND Validate::isEmail(Tools::getValue('sMailTest')) )
                {
                    $array[]            =
                    array( 'email' => Tools::getValue('sMailTest'), 'firstname' => $this->l('Mailer'), 'lastname' => $this->l('TestMan') );
                    $sendList           = array_merge($sendList,  $array );
                }
                /** END Mailer test **/

                /** BEGIN Customers **/
                if ($sCustomers == '1' )
                {
                    $customers 		= Customer::getCustomers();
                    $sendList           = array_merge($sendList, $customers);
                }
                else if ( $sCustomers == '2' )
                {
                    $customers          = Customer::getNewsletteremails();
                    $sendList           = array_merge($sendList, $customers);
                }
                else if( $sCustomers == '3' )
                {
                    $customers          = $this->getBrithdayCustomers( Tools::getValue('dateBrithday') );
                    $sendList           = array_merge($sendList, $customers);
                }
                /** END Customers **/

                /** BEGIN Block Subscribers **/
                if ($sNewsletter)
                {
                    $blockSubscribers   = $this->getBlockSubscribers();
                    $sendList           = array_merge($sendList, $blockSubscribers);
                }
                /** END Block Subscribers **/

                $finalList = Array();
                foreach ($sendList as $item){
                    if (!recursive_in_array($item['email'],$finalList))
                        {$finalList[] = $item;}
                }

                $Result['total']            =   0;
                $Result['failed']           =   0;
                $Result['sucess']           =   0;
                $ArrayFailed                =   array();
                $key                        =   0;
                $output                     =   '';
                $check_division             =   60 / ( intval($_POST['wait_time']) > 0 ? intval($_POST['wait_time']) : 2 ) ;
                $wait                       =   intval( $check_division ) > 0 ? $check_division : 30;

                $_SESSION['newsletter']['check']          =   TRUE;
                $_SESSION['newsletter']['finalList']      =   $finalList;
                $_SESSION['newsletter']['total']          =   $Result['total'];
                $_SESSION['newsletter']['failed']         =   $Result['failed'];
                $_SESSION['newsletter']['sucess']         =   $Result['sucess'];
                $_SESSION['newsletter']['ArrayFailed']    =   $ArrayFailed;
                $_SESSION['newsletter']['POST']           =   $_POST;
                $_SESSION['newsletter']['GET']            =   $_GET;
                $_SESSION['newsletter']['key']            =   $key;
                $_SESSION['newsletter']['output']         =   $output;
            }else{
                $finalList                  =   $_SESSION['newsletter']['finalList'];
                $Result['total']            =   $_SESSION['newsletter']['total'];
                $Result['failed']           =   $_SESSION['newsletter']['failed'];
                $Result['sucess']           =   $_SESSION['newsletter']['sucess'];
                $ArrayFailed                =   $_SESSION['newsletter']['ArrayFailed'];
                $_POST                      =   $_SESSION['newsletter']['POST'];
                $_GET                       =   $_SESSION['newsletter']['GET'];
                $key                        =   $_SESSION['newsletter']['key']+1;
                $output                     =   $_SESSION['newsletter']['output'];
                
                $check_division             =   60 / ( intval($_POST['wait_time']) > 0 ? intval($_POST['wait_time']) : 2 ) ;
                $wait                       =   intval( $check_division ) > 0 ? $check_division : 30;
            }

            /** GLOBAL **/
            $email_from                     = Configuration::get('PS_SHOP_EMAIL');
            $name                           = Configuration::get('PS_SHOP_NAME');
            /** GLOBAL **/

            if( empty($finalList) ){
                echo '<div class="alert">'.$this->l('No recipients, with selected criteria!').'</div>';
                $_POST['send']  =   0;
                return $this->display();
            }

            $value      = $finalList[$key];

            //%EMAIL% - %NOME% - %SOBRENOME%
            $html 	= Tools::getValue('body_email');
            $assunto 	= Tools::getValue('subject_email');

            $firstname 	= isset($value['firstname']) ? $value['firstname'] : Tools::getValue('name_Subscribers');
            $lastname 	= isset($value['lastname']) ? $value['lastname'] : Tools::getValue('lname_Subscribers');
            $email 	= $value['email'];

            $html 	= str_replace($this->l('%FIRSTNAME%'), $firstname, $html);
            $html 	= str_replace($this->l('%LASTNAME%'),  $lastname, $html);
            $html 	= str_replace($this->l('%MAIL%'),      $email, $html);

            $assunto 	= str_replace($this->l('%FIRSTNAME%'), $firstname, $assunto);
            $assunto 	= str_replace($this->l('%LASTNAME%'),  $lastname, $assunto);
            $assunto 	= str_replace($this->l('%MAIL%'),      $email, $assunto);

            unset($headers);
            $headers        = "MIME-Version: 1.0\r\n";
            $headers 	   .= "Content-type: text/html; charset=iso-8859-1\r\n";
            $headers 	   .= "X-Priority: 1\r\n";
            $headers 	   .= "X-MSMail-Priority: High\r\n";
            $headers 	   .= "Disposition-Notification-To: $email_from\r\n" . $headers .=
                              "Reply-To: $email_from\r\n";
            $headers 	   .= "From: $name <$email_from>\r\n";
            $headers 	   .= "Organization: $name\r\n";

            $mensagem 		= nl2br($html);

            $Mail_Send          = new Mail();
            $send               = false;

            $send 		= Mail::Send(intval($cookie->id_lang), 'newsletter', $assunto, array('{email}' => $email_from, '{message}' => stripslashes($mensagem)), $email);

            $output             .=
            '<tr>
            <td>&nbsp;' . $firstname . '</td>
            <td>&nbsp;' . $lastname . '</td>
            <td>&nbsp;' . $email . '</td>
            <td align="center">';

            if ($send)
            {
                $Result['sucess']++;
                $output .= "<font color=\"GREEN\"> ".$this->l('SUCESS!')." </font> <br>";
            } else
            {
                $ArrayFailed[]  =   $value;
                $Result['failed']++;
                $output .=  "<font color=\"RED\"> ".$this->l('FAILED')." </font> <br>";
            }

            $output .=  ' </td></tr>';

            $Result['total']++;

            $_SESSION['newsletter']['key']            =   $key;
            $_SESSION['newsletter']['output']         =   $output;
            $_SESSION['newsletter']['total']          =   $Result['total'];
            $_SESSION['newsletter']['failed']         =   $Result['failed'];
            $_SESSION['newsletter']['sucess']         =   $Result['sucess'];

            $ouput_foot     =    '<tr><td colspan="4">&nbsp;</td></tr>';
            
            $ouput_foot     .=    '<tr><td colspan="2"  ></td><td><b>&nbsp; '.$this->l('Total sent').'</b></td><td align="center">'
            .$Result['total'].'</td><tr>';
            
            $ouput_foot     .=    '<tr><td colspan="2"  ></td><td><b>&nbsp; '.$this->l('Total successfully').'</b></td><td align="center">
            <font color="GREEN">'.$Result['sucess'].'</font></td><tr>';
            
            $ouput_foot     .=    '<tr><td colspan="2"  ></td><td><b>&nbsp; '.$this->l('Total failure').'</b></td><td align="center">
            <font color="RED">'.$Result['failed'].'</font></td><tr>';

            if( isset($finalList[$key+1]) ){
                echo "<meta http-equiv=\"refresh\" content=\"$wait\" />";
                echo '<div class="alert">'.$this->l('Wait...').'</div>';
                $button      =   '  ';
            }
            else{
                session_destroy();
                $_SESSION['newsletter']['POST']        =   $_POST;
                echo '<div class="conf">'.$this->l('Success!').'</div>';
                $button      =   ' <input type="submit" name="voltar" value="'.$this->l('Voltar').'" class="button" /> ';
            }

            $ouput_header                   = '<fieldset>
            <legend>Report</legend><br>
            <table border="1" width="100%" style="border-collapse: collapse;"
            cellpadding="2" bordercolor="#e0d0b1" >
            <tr>
                    <td align="center"><b>'.$this->l('FIRSTNAME').'</b></td>
                    <td align="center"><b>'.$this->l('LASTNAME').'</b></td>
                    <td align="center"><b>'.$this->l('E-MAIL').'</b></td>
                    <td align="center"><b>'.$this->l('STATUS').'</b></td>
            </tr>
            ';

            /** BOTÃO VOLTAR **/
            $ouput_foot     .=    '</table>
            </fieldset>
            <form action="' . $currentIndex . '&token=' . $this->token .
            '&send=0" method="post" >
            <div align="right"><br />
                '.$button.'
		</div>
		</form>';

            echo $ouput_header;
            echo $output;
            echo $ouput_foot;

        }
    }

    private function getBrithdayCustomers( $date = null )
    {
        $date               = is_null($date) ? date("d-m") : $date;
        $return             = Db::getInstance()->ExecuteS("
        SELECT
            ps_customer.email,
            ps_customer.lastname,
            ps_customer.firstname
        FROM
            ps_customer
        WHERE
            DATE_FORMAT(ps_customer.birthday, \"%d-%m\") = '$date' ");
        return $return;
    }

    private function getBlockSubscribers()
    {
        $return             = Db::getInstance()->ExecuteS('
        SELECT *
        FROM `'._DB_PREFIX_.'newsletter`; ');
        $bNewsletters = ( is_array($return) ? $return : array() );
        return $return;
    }
}

?>