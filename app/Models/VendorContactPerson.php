<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorContactPerson extends Model
{
    protected $table = 'vendor_contact_persons';

    protected $fillable = [
        'vendor_id',
        'name',
        'designation',
        'work_email',
        'work_phone'
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}
