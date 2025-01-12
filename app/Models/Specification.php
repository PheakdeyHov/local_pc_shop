<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Specification extends Model
{
    protected $fillable = ['product_id','product_code','specs_value','status','qty','purchase_price','sale_price'];

    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function purchaseProducts(){
        return $this->hasMany(PurchaseProducts::class);
    }
}
