<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadAttachment extends Model
{
    protected $fillable = [
        'lead_id',
        'original_name',
        'path',
        'mime_type',
        'size'
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }
}
