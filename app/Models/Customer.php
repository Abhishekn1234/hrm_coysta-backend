<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;

   protected $fillable = [
    'profile_logo',
    'customer_type',
    'company_name',
    'display_name',
    'owner_name',
    'primary_contact_name',
    'primary_contact_phone',
    'email',
    'pan_no',
    'organization',
    'login_enabled', // ✅ Add here
];

public function projects()
{
    return $this->hasMany(Project::class); // Update class name if different
}

public function invoices()
{
    return $this->hasMany(Invoice::class); // Update class name if different
}
// app/Models/Customer.php

public function estimates()
{
    return $this->hasMany(Estimate::class);
}

    // Relationships

    public function gstDetails()
    {
        return $this->hasMany(GstDetail::class);
    }

    public function shippingAddresses()
    {
        return $this->hasMany(ShippingAddress::class);
    }
public function tasks()
{
    return $this->hasMany(Task::class, 'customer_id');
}

    public function contactPersons()
    {
        return $this->hasMany(ContactPerson::class);
    }
}