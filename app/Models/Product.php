<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['image' , 'name' ,'category_id' , 'brand_id'];

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function brand(){
        return $this->belongsTo(Brand::class);
    }

    public function specifications(){
        return $this->hasMany(Specification::class);
    }

    public function purchaseProducts(){
        return $this->hasMany(PurchaseProducts::class);
    }
}
