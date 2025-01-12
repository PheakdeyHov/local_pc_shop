<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $fillable = ['name','logo','status'];

    public function product(){
        return $this->hasMany(Product::class);
    }
}
