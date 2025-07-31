<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadDetail extends Model
{
    use HasFactory;

    protected $table = 'lead_details';

    protected $fillable = [
        'lead_id',
        'task_title',
        'due_date',
        'type',
        'notes',
        'action'
    ];

    public $timestamps = false;
}
