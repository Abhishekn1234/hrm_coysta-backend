<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Lead extends Model
{
    use Notifiable;
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    protected $fillable = [
    'lead_name',
    'lead_email',
    'lead_phone',
    'lead_status',
    // any other fields...
];
}