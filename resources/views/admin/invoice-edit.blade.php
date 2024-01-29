
@push('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush
<script src="{{asset('js/custom.js?v=1.0')}}"></script>

<div class="row"><div class="col-md-12">
<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">Edit</h3>

        <div class="box-tools">
            <div class="btn-group pull-right" style="margin-right: 5px">
                <a href="{{  url('/admin/invoices') }}" class="btn btn-sm btn-default" title="List"><i class="fa fa-list"></i><span class="hidden-xs">&nbsp;List</span></a>
            </div>
        </div>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    
    <form action="" method="post" class="form-horizontal model-form-65b3bdf686268" accept-charset="UTF-8" pjax-container="">

        <div class="box-body">
            <div class="fields-group">
                <div class="col-md-12">



                <div class="form-group  ">

                        <label for="cat_id" class="col-sm-2 control-label">Customer</label>

                            <div class="col-sm-8">
                           
                                <input type="text" id="txtCustomer" name="txtCustomer" value="{{$invoice['customer']}}" />

                               
                            </div>
                </div>
                <div class="form-group  ">
                <div class="col-sm-2"></div>
                <div class="col-sm-8">
                    <div class="card">
                        <div class="card-header">
                        <h3 class="card-title">Invoice detail</h3>
                        </div>

                        <div class="card-body">
                        <table class="table table-bordered tableInvoice">
                        <thead>
                        <tr>
                        <th style="width: 10px">No</th>
                        <th>Category</th>
                        <th>Fruit</th>
                        <th>Unit</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Amount</th>
                        
                        </tr>
                        </thead>
                        <tbody>
                        @php
                            $countItem = 1
                        @endphp

                        @foreach($invoiceDetail as $detail)
                        <tr class="row_{{$countItem}}" dataset="{{$countItem}}">
                        <td>
                          
                            {{$countItem}}
                        </td>

                        <td>
                            <select name="cat_{{$countItem}}" id="cat_{{$countItem}}" dataset="{{$countItem}}" data-value="cat">
                                <option value="none">
                                    --Select a category--
                                </option>
                         
                                @foreach($catList as $cat)
                                <option value="{{$cat->id}}" @if($cat->id == $detail['cat_id']) selected @endif>
                                   {{$cat->name}}
                                </option>
                                @endforeach
                            </select>
                        </td>

                        <td>
                            <select name="item_{{$countItem}}" id="item_{{$countItem}}" dataset="1" data-value="item">
                                <option value="none">
                                    --Select a item--
                                </option>
                                @foreach($detail['itemList'] as $itemList)
                                <option value="{{$itemList['id']}}" @if($itemList['id'] == $detail['item_id']) selected @endif>
                                   {{$itemList['name']}}
                                </option>
                                @endforeach
                            </select>
                        </td>     

                        <td class="unit_{{$countItem}}">
                            {{$detail['unit']}}
                        </td>
                        <td class="price_{{$countItem}}">
                            {{$detail['price']}}
                        </td>
                        <td>
                            <input class="txtQuantity_{{$countItem}}" value="{{$detail['quantity']}}" type="number" id="quantity_{{$countItem}}" name="quantity_{{$countItem}}" dataset="{{$countItem}}"  />
                        </td>   

                        <td class="amount_{{$countItem}}">
                            {{$detail['amount']}}
                        </td>

                        @php
                            $countItem++;
                        @endphp
                        </tr> 
                        @endforeach

                           
                        </tbody>
                        </table>
                        </div>


                    </div>

                    <div>

                   

                    <div class="form-group  ">
                        <div class="col-sm-2">
                            <a class="btnMore" style="cursor:pointer">Add more row</a>
                        </div>
                        <div class="col-sm-10 text-right">
                            <div class="col-sm-10">
                                <b>Total</b>
                            </div>  
                            <div class="col-sm-2 ">
                                <input name="txtTotal" id="txtTotal" type="text" readonly value="{{$invoice['total']}}" style="width:90px;text-align:center" />
                                
                            </div>
                        </div>
                    </div>


                    <div class="form-group  ">
                        
                        <div class="col-sm-10 text-center">
                            <a class="btn btn-danger" href="{{  url('/admin/invoices') }}">Cancel</a>
                            <button type="button" class="btn btn-primary btnCreate">Edit Invoice</button>
                        </div>
                    </div>
                    {{ csrf_field() }}
               

                </div>
            </div>
        </div>               
    
<!-- /.box-footer -->
    </form>
</div>

<input type="hidden" name="hiddenCat" id="hiddenCat" value="{{$catList}}" />


</div></div>



<script>
jQuery(function(){
    

    jQuery.ajaxSetup({ headers: { 'csrftoken' : '{{ csrf_token() }}' } });

    var catList = JSON.parse($("#hiddenCat").val());


    $(".btnMore").click(function(){

        var rowCount = $('.tableInvoice tr').length;


        var rowAdd = '<tr class="row_'+rowCount+'" dataset="'+rowCount+'">';
            rowAdd += '<td>'+rowCount+'</td>';
            rowAdd += '<td>';
            rowAdd += '<select name="cat_'+rowCount+'" id="cat_'+rowCount+'" dataset="'+rowCount+'" data-value="cat">';
            rowAdd += '<option value="none" selected>--Select a category--</option>';

            $.each(catList, function( index, value ) {
                //console.log( catList[index]["id"] );

                rowAdd += '<option value="'+catList[index]["id"]+'">';
                rowAdd += catList[index]["name"];
                rowAdd +=  '</option>';
            });
           
            
            rowAdd += '</select>';
            rowAdd += '</td>';
            rowAdd += '<td>';
            rowAdd += '<select name="item_'+rowCount+'" id="item_'+rowCount+'" dataset="'+rowCount+'" data-value="item">';
            rowAdd += '<option value="none" selected>';
            rowAdd += '--Select a item--';
            rowAdd += '</option>';
            rowAdd += '</select>';
            rowAdd += '</td>';     
            rowAdd += '<td class="unit_'+rowCount+'"></td>';
            rowAdd += '<td class="price_'+rowCount+'"></td>';
            rowAdd += '<td>';
            rowAdd += '<input class="txtQuantity_'+rowCount+'" type="number" id="quantity_'+rowCount+'" name="quantity_'+rowCount+'" dataset="'+rowCount+'"  />';
            rowAdd += '</td>';   
            rowAdd += '<td class="amount_'+rowCount+'"></td>';
            rowAdd += '</tr>';


        $(".tableInvoice tbody")
        .append(rowAdd);

        actionCat();


   

    });

    $(".btnCreate").click(function(){
        if($.trim($("#txtCustomer").val()) == ""){
            alert("Please input Customer");return;

        }
        if($("#txtTotal").val() == "0"){
            alert("Cannot create invoice. Please check your item list in Invoice Detail table");return;
        }
       

            var rowInvoiceCount = $('.tableInvoice tr').length;
            var invoiceDetail = [];
            for(var i=1;i<rowInvoiceCount;i++){
                if($("#amount_"+i).html()!=""){
                    var invoiceItem = new Object();
                    invoiceItem.cat_id = $("#cat_"+i).val();
                    invoiceItem.item_id = $("#item_"+i).val();
                    invoiceItem.unit = $(".unit_"+i).html();
                    invoiceItem.price = $(".price_"+i).html();
                    invoiceItem.quantity = $("#quantity_"+i).val();
                    invoiceItem.amount = $(".amount_"+i).html();
                    invoiceDetail.push(invoiceItem);
                    
                }
            }
            
            $.ajax({
                type: 'post',
                url: "/admin/invoices/{{$invoice['id']}}/saveInvoice",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "customer" : $("#txtCustomer").val(),
                    "total" : $("#txtTotal").val(),
                    "invoiceData" : invoiceDetail

                },
                success:function(dataInvoice){
                    
                    var result = $.parseJSON(dataInvoice);
                    if(result == "success"){
                        window.location.href = "{{  url('/admin/invoices') }}";
                    }   
                    //}
                }
            });
        
    });
    

});

</script>