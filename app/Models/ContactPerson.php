<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ContactPerson extends Model
{
    use HasFactory;
    protected $table='contact_persons';

    protected $fillable = [
        'customer_id',
        'contact_name',
        'designation',
        'work_email',
        'work_phone',
        'personal_email',
        'personal_phone',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
