<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceDetail extends Model
{
    protected $fillable = [
        'cat_id',
        'item_id',
        'unit',
        'price',
        'quantity',
        'amount',
      ];
}
