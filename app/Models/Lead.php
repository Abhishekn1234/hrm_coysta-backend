<?php

// app/Models/Lead.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'contactPerson',
        'email',
        'phone',
        'type',
        'location'
    ];

    public function inventory()
    {
        return $this->hasMany(Inventory::class);
    }
    // app/Models/Lead.php
    public function attachments()
    {
        return $this->hasMany(LeadAttachment::class);
    }
}

