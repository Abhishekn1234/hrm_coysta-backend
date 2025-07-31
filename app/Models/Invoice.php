<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'customer_id', 'invoice_number', 'issue_date', 'due_date', 'amount', 'status'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}