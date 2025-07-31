<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseOrder extends Model
{
    protected $fillable = ['vendor_id', 'date', 'amount', 'status'];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}
