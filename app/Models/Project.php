<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory;

    protected $table = 'projects';

   protected $fillable = [
    'project_name',
    'project_description',
    'project_starting_date',
    'expected_release_date',
    'deadline',
    'product_owner_id',
    'staff_ids',
    'customer_id',
    'status'
];


    protected $casts = [
        'staff_ids' => 'array',
        'project_starting_date' => 'date',
        'expected_release_date' => 'date',
        'deadline' => 'date',
    ];

    public function productOwner()
    {
        return $this->belongsTo(User::class, 'product_owner_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function getStaffListAttribute()
    {
        return User::whereIn('id', $this->staff_ids ?? [])->get();
    }

    public function getDurationAttribute()
    {
        return $this->project_starting_date && $this->expected_release_date
            ? $this->project_starting_date->diffInDays($this->expected_release_date)
            : null;
    }
}