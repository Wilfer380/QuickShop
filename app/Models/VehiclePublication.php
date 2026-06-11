<?php

namespace App\Models;

class VehiclePublication extends Product
{
    protected $table = 'products';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function productImages()
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }

}
