<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = ['purchase_code','supplier_id','shipping_company','shipping_price','shipping_status','paid_price','notpaid_price','total_price'];

    public function supplier(){
        return $this->belongsTo(Supplier::class);
    }

    public function purchaseProducts(){
        return $this->hasMany(PurchaseProducts::class);
    }
}
