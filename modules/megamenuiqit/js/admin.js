$(document).ready(function(){
String.prototype.replaceAll = function( token, newToken, ignoreCase ) {
    var _token;
    var str = this + "";
    var i = -1;

    if ( typeof token === "string" ) {

        if ( ignoreCase ) {

            _token = token.toLowerCase();

            while( (
                i = str.toLowerCase().indexOf(
                    token, i >= 0 ? i + newToken.length : 0
                ) ) !== -1
            ) {
                str = str.substring( 0, i ) +
                    newToken +
                    str.substring( i + token.length );
            }

        } else {
            return this.split( token ).join( newToken );
        }

    }
return str;
};


if($("#image_bottom_panel:checked").val())
$("#bottom_image_upload").slideDown();

$("#image_bottom_panel").on("click", function(event){
  if($("#image_bottom_panel:checked"))
  $("#bottom_image_upload").slideDown();
});

$(".cbottom_panel input[type=radio]").not("#image_bottom_panel").on("click", function(event){
  if($("#image_bottom_panel:checked").not())
  $("#bottom_image_upload").slideUp();
});




if($("#links_bottom_panel:checked").val())
$("#bottom_links").slideDown();

$("#links_bottom_panel").on("click", function(event){
  if($("#links_bottom_panel:checked"))
  $("#bottom_links").slideDown();
});

$(".cbottom_panel input[type=radio]").not("#links_bottom_panel").on("click", function(event){
  if($("#links_bottom_panel:checked").not())
  $("#bottom_links").slideUp();
});



if($("#image_right_panel:checked").val())
$("#right_image_upload").slideDown();

$("#image_right_panel").on("click", function(event){
  if($("#image_right_panel:checked"))
  $("#right_image_upload").slideDown();
});

$(".cright_panel input[type=radio]").not("#image_right_panel").on("click", function(event){
  if($("#image_right_panel:checked").not())
  $("#right_image_upload").slideUp();
});




if($("#links_right_panel:checked").val())
$("#right_links").slideDown();

$("#links_right_panel").on("click", function(event){
  if($("#links_right_panel:checked"))
  $("#right_links").slideDown();
});

$(".cright_panel input[type=radio]").not("#links_right_panel").on("click", function(event){
  if($("#links_right_panel:checked").not())
  $("#right_links").slideUp();
});








if($("#categories_left_panel:checked").val())
$("#leftcat_links").slideDown();

$("#categories_left_panel").on("click", function(event){
  if($("#categories_left_panel:checked"))
  $("#leftcat_links").slideDown();
});

$(".cleft_panel input[type=radio]").not("#categories_left_panel").on("click", function(event){
  if($("#categories_left_panel:checked").not())
  $("#leftcat_links").slideUp();
});



if($("#man_left_panel:checked").val())
$("#leftman_links").slideDown();

$("#man_left_panel").on("click", function(event){
  if($("#man_left_panel:checked"))
  $("#leftman_links").slideDown();
});

$(".cleft_panel input[type=radio]").not("#man_left_panel").on("click", function(event){
  if($("#man_left_panel:checked").not())
  $("#leftman_links").slideUp();
});



if($("#links_left_panel:checked").val())
$("#leftlinks_links").slideDown();

$("#links_left_panel").on("click", function(event){
  if($("#links_left_panel:checked"))
  $("#leftlinks_links").slideDown();
});

$(".cleft_panel input[type=radio]").not("#links_left_panel").on("click", function(event){
  if($("#links_left_panel:checked").not())
  $("#leftlinks_links").slideUp();
});



if($("#cms_left_panel:checked").val())
$("#leftpanel_cms").slideDown();

$("#cms_left_panel").on("click", function(event){
  if($("#cms_left_panel:checked"))
  $("#leftpanel_cms").slideDown();
});

$(".cleft_panel input[type=radio]").not("#cms_left_panel").on("click", function(event){
  if($("#cms_left_panel:checked").not())
  $("#leftpanel_cms").slideUp();
});







if($("#product_left_panel:checked").val())
$("#leftproducts").slideDown();

$("#product_left_panel").on("click", function(event){
  if($("#product_left_panel:checked"))
  $("#leftproducts").slideDown();
});

$(".cleft_panel input[type=radio]").not("#product_left_panel").on("click", function(event){
  if($("#product_left_panel:checked").not())
  $("#leftproducts").slideUp();
});
























});