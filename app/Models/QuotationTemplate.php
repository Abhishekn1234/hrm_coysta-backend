<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuotationTemplate extends Model
{
    //
    protected $fillable = [
        'name',
        'front_pages',
        'back_pages',
        'items',
        'project_name',
        'customer_id',
        'is_default',
        'user_id'
    ];
}
