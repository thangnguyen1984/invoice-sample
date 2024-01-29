<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\InvoiceDetail;
use App\Category;
use App\Item;

class Invoice extends Model
{
    protected $fillable = [
        'customer',
        'total'
      ];

    public function getInvoiceData($id)
    {
        $invoice = $this->find($id)->toArray();
        $categoryObj = new Category();
        $itemObj = new Item();

        $invoiceDetailObj = new InvoiceDetail();

        $invoiceDetail = $invoiceDetailObj->where("invoice_id",$id)->get()->toArray();

        foreach($invoiceDetail as $key=>$item){
           $catData = $categoryObj->find($item['cat_id'])->toArray();
           $invoiceDetail[$key]['cat_name'] = $catData['name'];
           $itemData = $itemObj->find($item['item_id'])->toArray();
           $invoiceDetail[$key]['item_name'] = $itemData['name'];
          

        }
        $invoice['detail'] = $invoiceDetail;
        return $invoice;
    }   
}
