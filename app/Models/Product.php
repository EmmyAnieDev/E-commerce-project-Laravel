<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'price', 'short_description', 'qty', 'sku', 'description', 'image'];

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }


    public function colors()
    {
        return $this->hasMany(ProductColor::class);
    }
}
