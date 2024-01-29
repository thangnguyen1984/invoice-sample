<?php

namespace App\Admin\Controllers;

use App\Item;
use App\Category;
use App\Invoice;
use App\InvoiceDetail;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Layout\Content;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Widgets\Table;
use Illuminate\Http\Request;
use App\Admin\Extensions\CheckRow;
use Encore\Admin\Admin;

class InvoiceController extends AdminController
{
   /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Invoice';

   
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Invoice());
echo "<script type=\"text/javascript\">
function PrintDiv(invoiceID) {    
 
    $.ajax({
        type: 'get',
        url: '/admin/invoices/'+invoiceID+'/printInvoice',
        success:function(dataInvoice){
                    
            var result = $.parseJSON(dataInvoice);

            
            $('.lblID').html(result.id);
            $('.lblCustomer').html(result.customer);
            $('.lblCreatedTime').html(result.created_at);

            var detail = result.detail;
            var countInvoice = 1;
            var detailTable = '';
            $.each(detail, function( index, value ) {
                
                
                detailTable += '<tr>';
                detailTable += '<td>'+countInvoice+'</td>';
                detailTable += '<td>'+detail[index]['cat_name']+'</td>';
                detailTable += '<td>'+detail[index]['item_name']+'</td>';
                detailTable += '<td>'+detail[index]['unit']+'</td>';
                detailTable += '<td style=\'text-align:center\'>'+detail[index]['price']+'</td>';
                detailTable += '<td style=\'text-align:center\'>'+detail[index]['quantity']+'</td>';
                detailTable += '<td style=\'text-align:center\'>'+detail[index]['amount']+'</td>';
                detailTable += '</tr>';

                
                countInvoice++;
            });


            detailTable += '<tr>';
            detailTable += '<td colspan=\'6\' style=\'text-align:right;padding-right:20px;border-top: 1px solid\'>Total</td>';
            detailTable += '<td style=\'text-align:center;border-top: 1px solid\'>'+result.total+'</td>';
            detailTable += '</tr>';
            $('.printTable tbody').append(detailTable);
            var divToPrint = document.getElementById('divToPrint');
            var popupWin = window.open('', '_blank', 'width=1000,height=1000');
            popupWin.document.open();
            popupWin.document.write(divToPrint.innerHTML + '<script>window.print();<\/script>');
            popupWin.document.close();
            $('.printTable tbody').html('');
        }
        
    });
    
   
} 

</script>";       
//popupWin.document.write(\"<html><body onload='window.print()'>\"+divToPrint.innerHTML+\"</html>\");
echo "
<div id='divToPrint' style='display:none;'>
<div style='width:700px;height:700px;padding-left:200px'>
    <div style='padding-top:50px'> INVOICE <b class='lblID'></b></div>
    <div> Created time <b class='lblCreatedTime'></b></div>   
    <div> Customer <b class='lblCustomer'></b></div> 
    <div> Detail</div>
    <table class='printTable' cellspacing='0' cellpadding='0' style='border: 1px solid'>
        <thead>
        <tr>
            <td style='width:50px;'>No.</td>
            <td style='width:100px;'>Category</td>
            <td style='width:150px;'>Item</td>
            <td style='width:50px;'>Unit</td>
            <td style='width:70px;text-align:center'>Price</td>
            <td style='width:70px;text-align:center'>Quantity</td>
            <td style='width:70px;text-align:center'>Amount</td>

        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>    
</div>
</div>
";


        echo '<style>'; 
        echo '.column-__actions__{display:none}'; 
        echo '</style>';  

        echo '<script type="text/javascript">'; 
        echo 'function warningBeforeRedirect(url){
         var checkbox = confirm("Are you sure you want to delete this Invoice?");
         if (checkbox == true) {
                window.location.href=url;
            } else {
               
            }
            
            }';
        echo '</script>';

        $grid->column('id', __('Id'));
        $grid->column('customer', __('Customer'));
        $grid->column('total', __('Total Amount'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));
        $grid->column('Action')->display(function ($title, $column) {
            $deleteLink = route('admin.invoices.delete', ['id' => $this->id]);
            $editLink = route('admin.invoices.edit', ['id' => $this->id]);
            return '<a href="'.$editLink.'">Edit</a>&nbsp;&nbsp;&nbsp;
            <a href="" onclick="warningBeforeRedirect(\''.$deleteLink.'\')">Delete</a>&nbsp;&nbsp;&nbsp; 
            <a href="" onclick="PrintDiv('.$this->id.')">Print</a>';
        });
        

        $grid->disableExport();

        return $grid;
    }

    /**
     * Retrieve data to print.
     *
     * 
     */
    public function printInvoice($id){
        $invoiceObj = new Invoice();
        $invoice = $invoiceObj->getInvoiceData($id);
        echo json_encode($invoice);
      
    }



    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Invoice());

        $form->text('customer', __('Customer'))->required();
        $form->button('Add item details');

        return $form;
       
    }

    /**
     * Retrieve data for create invoice screen.
     *
     * @return Form
     */

    public function create(Content $content){
        $categoryObj = new Category();
        $catList = $categoryObj->get();
        
        return $content
        ->title($this->title())
        ->body(view('admin.invoice-create', [
            'catList' => $catList,
        ]));

    }

    /**
     * Retrieve data for edit invoice screen.
     *
     * @return Form
     */
    public function edit($id,Content $content){
        $categoryObj = new Category();
        $catList = $categoryObj->get();
        
        $invoiceObj = new Invoice();
        $invoice = $invoiceObj->find($id)->toArray();

        $invoiceDetailObj = new InvoiceDetail();
        $invoiceDetail = $invoiceDetailObj->where("invoice_id",$id)->get()->toArray();

        $itemObj = new Item();

        foreach($invoiceDetail as $key=>$item){
            
            $itemList = $itemObj->where("cat_id",$item["cat_id"])->get()->toArray();
            $invoiceDetail[$key]['itemList'] = $itemList;
            
        }
        return $content
        ->title($this->title())
        ->body(view('admin.invoice-edit', [
            'catList' => $catList,
            'invoice' => $invoice,
            'invoiceDetail' => $invoiceDetail
        ]));

    }

    public function delete($id){
        $invoiceObj = new Invoice();
        $invoiceObj->where('id', $id)->delete();
        $invoiceDetailModel = new InvoiceDetail();
        $invoiceDetailModel->where('invoice_id', $id)->delete();
        return redirect()->route('admin.invoices.index')->withSuccessMessage('Import Data successfully');
    }

    public function getItemByCategory($catID){
        $itemObj = new Item();
        $listItem = $itemObj->getItemByCategoryID($catID);
        return json_encode($listItem);
    }

    public function getItemDetail($id){
        $itemObj = new Item();
        $item = $itemObj->getItemDetail($id);
        return json_encode($item);
    }

    public function saveInvoice($id,Request $request){
        $customer = $request->post("customer");
        $total = $request->post("total");
        $invoiceData = $request->post('invoiceData'); 
        
        
        if($id==0)
        {
            $invoiceModel = new Invoice();
            $invoiceModel->customer = $customer;
            $invoiceModel->total = $total;

            $invoiceModel->save();

            $idInvoice = $invoiceModel->id;
            foreach($invoiceData as $key=>$item)
            {
                $invoiceDetailModel = new InvoiceDetail();
                $invoiceDetailModel->invoice_id = $idInvoice;
                $invoiceDetailModel->cat_id = $item['cat_id'];
                $invoiceDetailModel->item_id = $item['item_id'];
                $invoiceDetailModel->unit = $item['unit'];
                $invoiceDetailModel->price = $item['price'];
                $invoiceDetailModel->quantity = $item['quantity'];
                $invoiceDetailModel->amount = $item['amount'];
                $invoiceDetailModel->save();
            }

            if($idInvoice > 0)
            {
                echo json_encode(array("success"));
            }

        }
        else{
            $invoiceDetailModel = new InvoiceDetail();
            $itemDelete = $invoiceDetailModel->where('invoice_id', $id)->delete();
            $invoiceUpdate = array();
            $invoiceUpdate['customer'] = $customer;
            $invoiceUpdate['total'] = $total;
            $invoiceModel = new Invoice();
            $invoiceModel->where('id', $id)->update($invoiceUpdate);
            

            foreach($invoiceData as $key=>$item)
            {
                $invoiceDetailModel = new InvoiceDetail();
                $invoiceDetailModel->invoice_id = $id;
                $invoiceDetailModel->cat_id = $item['cat_id'];
                $invoiceDetailModel->item_id = $item['item_id'];
                $invoiceDetailModel->unit = $item['unit'];
                $invoiceDetailModel->price = $item['price'];
                $invoiceDetailModel->quantity = $item['quantity'];
                $invoiceDetailModel->amount = $item['amount'];
                $invoiceDetailModel->save();
            }

            echo json_encode(array("success"));
        }
       
    }
}
