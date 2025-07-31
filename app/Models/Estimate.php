<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estimate extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'estimate_number',
        'date',
        'amount',
        'status',
    ];

    // Relationship: each estimate belongs to a customer
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
