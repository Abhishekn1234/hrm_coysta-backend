<?php

namespace App\Models;
use App\Models\Bill;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
   protected $fillable = [
    'salutation',
    'name',
    'phone',
    'email',
    'gst_no',
    'type',
    'material',
    'address',
    'login_enabled',
    'organization',
    'username',
    'password',
];

public function contactPersons()
{
    return $this->hasMany(VendorContactPerson::class, 'vendor_id');
}
public function purchaseOrders()
{
    return $this->hasMany(PurchaseOrder::class);
}

public function bills()
{
    return $this->hasMany(Bill::class);
}
public function notes(){
    return $this->hasMany(VendorNote::class);
}
}