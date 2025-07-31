<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
   protected $fillable = [
        'name', 'description', 'price', 'image', 'previewImage',
        'category', 'stock', 'sku', 'weight', 'dimensions',
        'hsn_code', 'barcode', 'unit', 'status', 'condition',
        'cost_price', 'base_price', 'tax_rate', 'pricing_levels',
        'attributes', 'brand', 'productType','images'
    ];

    protected $casts = [
        'pricing_levels' => 'array',
        'attributes' => 'array',
        'tax_rate' => 'float',
        'cost_price' => 'float',
        'base_price' => 'float',
        'images' => 'array'
    ];
}
