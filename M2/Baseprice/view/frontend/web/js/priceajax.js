define([ "jquery", "jquery/ui", "mage/mage" ], function($) {
	
	$.widget('modulename.customname', {
        _create: function() {
            var data = this.options;
            /*this.options contaion all variables which you pass in it for
             use in your javascript code */
            //console.log(this.options); 
            // here you can write your javascript code as your requirement
			$( ".product-custom-option-select-length" ).change(function() {
						$('.product-custom-option-select-thickness').addClass('test');

				$(".product-custom-option-select-thickness option").each(function()
							{
								// Add $(this).val() to your list
			
			
					if($(this).text() == '-- Please Select --'){
			
						$(this).attr( "selected",true );
						$(this).prop( "selected",true );
						$(this).attr( "selected", "selected", );
			
					}
				});
			});
	$( ".product-custom-option-select-length" ).change(function() {
		

				$(".product-custom-option-select-thickness option").each(function()
							{
								// Add $(this).val() to your list
			
			
					if($(this).text() == '-- Please Select --'){
			
						$(this).attr( "selected",true );
						$(this).prop( "selected",true );
						$(this).attr( "selected", "selected", );
			
					}else{
									 			$(this).removeAttr("selected");
									
					}
				});
			});
			$( ".product-custom-option-select-width" ).change(function() {
		

				$(".product-custom-option-select-thickness option").each(function()
							{
								// Add $(this).val() to your list
			
			
					if($(this).text() == '-- Please Select --'){
			
						$(this).attr( "selected",true );
						$(this).prop( "selected",true );
						$(this).attr( "selected", "selected", );
			
					}else{
									 			$(this).removeAttr("selected");
									
					}
				});
			});$( ".product-custom-option-select-states" ).change(function() {
		

				$(".product-custom-option-select-thickness option").each(function()
							{
								// Add $(this).val() to your list
			
			
					if($(this).text() == '-- Please Select --'){
			
						$(this).attr( "selected",true );
						$(this).prop( "selected",true );
						$(this).attr( "selected", "selected", );
			
				}else{
									 			$(this).removeAttr("selected");
									
					}
				});
			});		
			$( ".product-custom-option-select-color" ).change(function() {
		

				$(".product-custom-option-select-thickness option").each(function()
							{
								// Add $(this).val() to your list
			
			
					if($(this).text() == '-- Please Select --'){
			
						$(this).attr( "selected",true );
						$(this).prop( "selected",true );
						$(this).attr( "selected", "selected" );
			
					}else{
									 			$(this).removeAttr("selected");
									
					}
				});
			});		
			$( ".product-custom-option-select-thickness" ).change(function() {

            
         
        		var dataForm = $('#product_addtocart_form');
        		dataForm.mage('validation', {});
        		var AjaxUrl = "https://www.freshupmattresses.com/freshup_oms/api/fetch_price_by_dimemsion_api"
        		
        		var lengthid=$(".product-custom-option-select-length option:selected").text().toLowerCase();
        		var widthid=$(".product-custom-option-select-width option:selected").text().toLowerCase();
        		var heightid=$(".product-custom-option-select-thickness option:selected").text().toLowerCase();
        		var zone=$(".product-custom-option-select-states option:selected").text();
			
        		var param = {
        				'proId' : data.sku,	
        				'lengthid' : lengthid,
        				'widthid' : widthid,
        				'heightid' : heightid,
        				'zoneid' : zone
        		};	

 
        		if(dataForm.validation('isValid') === false){
        		    return false;
        		}
        			
        		$.ajax({
        			showLoader : true,
        			url : data.url,
        			data : param,
        			type : "POST"
        		}).done(function(response) {
        			var response = JSON.parse(response);
        			if(response.price > 0) {
        				$('.price-final_price .price').html("&#x20B9;" + response.discount );
						$('#price-discount .price-discount-per').html( "Off" + "<br>" + response.discount_percent +"%");
						 $('.price-final_price .old-price .price').html("&#x20B9;" + response.price);
        			} else {
        				alert("Unable to fetch the updated price");
        			}
        			
        			return true;
        		}).fail(function(response) {
        			alert('We could not processed the request');
        		});

        	});
            
        }
    });
    return $.modulename.customname;
	
	
});