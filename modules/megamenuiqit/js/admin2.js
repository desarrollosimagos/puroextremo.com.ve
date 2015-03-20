$(document).ready(function(){

var exids2 = '';
$('#product_autocomplete_inputm').autocomplete('ajax_products_list.php', {
        minChars: 1,
        autoFill: true,
        max:20,
        matchContains: true,
        mustMatch:true,
        scroll:false,
 				extraParams: {
				excludeIds : exids2
			},
        cacheLength:0,
        formatItem: function(item) {
          return item[1]+' - '+item[0];
        }
      }).result(function(event, data, formatted) {
    if (data == null)
      return false;
    var productId = data[1];
    var productName = data[0];
    $('#right_product_id_curr').html(productName + '(ID: ' + productId + ')');
    $('#right_product_id').val(productId);
    
    
    
    
    
});


var exids = $('#leftproductsitemsInput').val().replaceAll('PRD', '');
$('#leftproduct_autocomplete_input')
			.autocomplete('ajax_products_list.php', {
				minChars: 1,
				autoFill: true,
				max:20,
				matchContains: true,
				mustMatch:true,
				scroll:false,
				cacheLength:0,
				extraParams: {
				excludeIds : exids
			},
				formatItem: function(item) {
					return item[1]+' - '+item[0];
				}
			}).result(function(event, data, formatted) {
		if (data == null)
			return false;
		var productId = data[1];
		var productName = data[0];
		val = "PRD"+productId;
		$("#leftproductsitems").append("<option value=\""+val+"\">"+productName+"</option>");
		$('#leftproduct_autocomplete_input').val('');
		serialize_leftproducts();
});










	function getLeftProductIds()
	{
		if ($('#leftproductsitemsInput').val() === undefined)
			return '';
		var ids = $('#leftproductsitemsInput').val().replaceAll('PRD', '');

		
    
		return ids;
	}



					$("#leftproductsremoveItem").click(remove_leftproducts);
					$("#leftproductsitems").dblclick(remove_leftproducts);
					function add_leftproducts()
					{
						$("#leftproductsavailableItems option:selected").each(function(i){
							var val = $(this).val();
							var text = $(this).text();
							text = text.replace(/(^\s*)|(\s*$)/gi,"");
							$("#leftproductsitems").append("<option value=\""+val+"\">"+text+"</option>");
						});
						serialize_leftproducts();
						return false;
					}
					function remove_leftproducts()
					{
						$("#leftproductsitems option:selected").each(function(i){
							$(this).remove();
						});
						serialize_leftproducts();
						return false;
					}
					function serialize_leftproducts()
					{
						var options = "";
						$("#leftproductsitems option").each(function(i){
							options += $(this).val()+",";
						});
						$("#leftproductsitemsInput").val(options.substr(0, options.length - 1));
									$('#leftproduct_autocomplete_input').setOptions({
			extraParams: {
				excludeIds : getLeftProductIds()
			}
		});
					}



});