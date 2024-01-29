<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Category;

class Item extends Model
{
    protected $fillable = [
        'name',
        'unit',
        'price',
      ];
    public function category() {
        return $this->belongsTo(Category::class,'cat_id'); // don't forget to add your full namespace
    }  

    public function getItemByCategoryID($catID){
        $itemList = $this->where("cat_id",$catID)->get()->toArray();
        return $itemList;
    }

    public function getItemDetail($id){
        $itemDetail = $this->find($id)->toArray();
        return $itemDetail;
    }
}
