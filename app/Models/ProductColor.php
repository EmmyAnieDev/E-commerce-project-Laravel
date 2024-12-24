<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductColor extends Model
{

    protected $fillable = ['name', 'product_id'];

    public  function product(){
        return $this->belongsTo(Product::class);
    }
}
