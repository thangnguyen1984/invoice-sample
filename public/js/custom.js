
jQuery(function(){
    actionCat();

   
});

function actionCat(){
    $('select').change(function(e){

        var dataValue = $(this).attr("data-value");
        if(dataValue == "cat")
        {
            var indexCat = $(this).attr("dataset");
            var catID = $(this).find(":selected").val();
            $('.unit_'+indexCat).html("");
            $('.price_'+indexCat).html("");
            $('.amount_'+indexCat).html("");
            
            $.ajax({
                type: 'get',
                url: '/admin/invoices/getItemByCategory/'+catID,
               
                success:function(data){
                    var listItem = jQuery.parseJSON(data);
                    //console.log(listItem);
                    $('#item_'+indexCat).find('option').remove().end().append('<option value="none">--Select an item--</option>');
                    if(listItem.length > 0){

                        jQuery.each(listItem, function (i) {
                            var itemName = listItem[i]['name'];
                            var itemID = listItem[i]['id'];
                            var itemUnit = listItem[i]['unit'];
                            var itemPrice = listItem[i]['price'];

                            $('#item_'+indexCat).append('<option value="'+itemID+'">'+itemName+'</option>');
                            //$('.unit_'+indexCat).html(itemUnit);
                            //$('.price_'+indexCat).html(itemUnit);
                        });
                    }
                }
            });
            
        }
        

        if(dataValue == "item")
        {
            var indexCat = $(this).attr("dataset");
            var itemID = $(this).find(":selected").val();
  
            $.ajax({
                type: 'get',
                url: "/admin/invoices/getItemDetail/"+itemID,
                
                success:function(dataItem){
                    var itemDetail = jQuery.parseJSON(dataItem);
                    var itemUnit = itemDetail['unit'];
                    var itemPrice = itemDetail['price'];

                       

                        //$('#item_'+indexCat).append('<option value="'+itemID+'">'+itemName+'</option>');
                        $('.unit_'+indexCat).html(itemUnit);
                        $('.price_'+indexCat).html(itemPrice);
                        
                   
                }
            });
            
        }
        calTotal();
    });

    jQuery('input').change(function(e){
        var inputIndex = $(this).attr("dataset");
        if(inputIndex > 0)
        {
            calAmount(inputIndex); 
            calTotal();
        }
    });

    jQuery('input').keyup(function(e){
        var inputIndex = $(this).attr("dataset");
        if(inputIndex > 0)
        {
            calAmount(inputIndex); 
            calTotal();
        }
        
    });


    
}

function calAmount(inputIndex)
{
    
        var quantity = $("#quantity_"+inputIndex).val();
        if($.isNumeric(quantity)){
            var price = $(".price_"+inputIndex).html();
            if( price != ""){
                var amount = quantity * price;
                if($.isNumeric(amount) > 0){
                    $(".amount_"+inputIndex).html(amount);
                }
                else{
                    $(".amount_"+inputIndex).html("");
                }

                

            }
            else{
                $(".amount_"+inputIndex).html("");
            }
        }

        calTotal();
    
}

function calTotal(){
    var rowInvoiceCount = $('.tableInvoice tr').length;
        
    var totalText = 0;
            for(var i=1;i<rowInvoiceCount;i++){
                var checkAmount = $(".amount_"+i).html();
                if(checkAmount=="")
                {
                    checkAmount = 0;
                }
                var checkAmount = parseInt(checkAmount,10);
                
                totalText += checkAmount;
                //console.log(totalText);
                $('#txtTotal').val(totalText);
            }
}
