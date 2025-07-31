<?php

// app/Models/LeaveRequest.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    protected $fillable = [
        'employee_id', 'leave_type', 'from_date', 'to_date', 'reason', 'is_emergency', 'status'
    ];

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }
}
