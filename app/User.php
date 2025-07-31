<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    protected $fillable = [
        'user_type', 
        'name', 
        'email', 
        'phone', 
        'place', 
        'address', 
        'gender', 
        'designation', 
        'role', 
        'image', 
        'resume', 
        'email_verified_at', 
        'password', 
        'remember_token', 
        'status'
    ];

    protected $hidden = [
        'password', 
        'remember_token',
    ];
}