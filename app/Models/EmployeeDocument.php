<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeDocument extends Model
{
    use HasFactory;

   protected $table = 'documents';

    protected $fillable = [
        'user_id',
        'title',
        'file_path',
        'file_type',
        'body', 'image'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
