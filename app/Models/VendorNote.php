<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorNote extends Model
{
    protected $fillable = ['vendor_id', 'note'];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}
