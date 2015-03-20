<div class="col-xs-12 megamenuiqit-container {if  isset($mega_menu_width) && ($mega_menu_width==1)} megamenuiqit-container-full {/if}">
<nav id="topmenuContener" class="megamenuiqit megamenu_styleb{$mega_menu_style} {if  isset($mega_menu_style) && ($mega_menu_style==1)} megamenu_style2{/if}{if  isset($mega_menu_width) && ($mega_menu_width==1)} mmfullwidth {else} mmfixed{/if} mega-menu-border-{$mega_menu_border}" role="navigation">
    <div class="container">
        <div class="row">
       <ul id="megamenuiqit" class="clearfix">
        <li class="megamenu_home"><a class="main_menu_link megamenu_home_lnk" title="{l s='Home' mod='megamenuiqit'}" href="{$base_dir}" style="background-color: {$mega_home_bg};
color: {$mega_txt_color}"><i class="icon-home"></i></a></li>
        {$mega_menu}
    </ul>

    
    <div id="responsiveMenu">
        <div id="responsiveMenuShower" class="clearfix">
            <div class="responsiveInykator2"><i class="icon-reorder"></i></div>
            <span>{l s='Menu' mod='megamenuiqit'}</span>
        </div>
        <ul id="responsiveAccordion"> 
         <li><a title="{l s='Home' mod='megamenuiqit'}" href="{$base_dir}">{l s='Home' mod='megamenuiqit'}</a></li>
         {$mega_responsivemenu}
     </ul>
 </div> </div>
</div>
</nav>
</div>


