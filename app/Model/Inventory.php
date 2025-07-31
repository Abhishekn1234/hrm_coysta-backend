<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Inventory extends Authenticatable
{
    use Notifiable;
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // protected $guarded = [];

    protected $fillable = [
        'id',
        'base_inventory_id',
        'item_name',
        'hsn_code',
        'stock_category',
        'unit',
        'worth',
        'vendor',
        'description',
        'model_no',
        'gm_code',
        'brand_name',
        'purchase_price',
        'length',
        'height',
        'width',
        'weight',
        'volume',
        'current',
        'power',
        'rental_information',
        'client_id'
    ];

    public function base_inventory()
    {
        return $this->belongsTo(Inventory::class, 'base_inventory_id');
    }
}
