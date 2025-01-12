<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PurchaseProducts extends Model
{
    protected $fillable = ['purchase_id','product_id','product_name','specification_id','specs_value','qty','status','purchase_price','sale_price'];

    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function specification(){
        return $this->belongsTo(Specification::class);
    }

    public function purchase(){
        return $this->belongsTo(Purchase::class);
    }
}
