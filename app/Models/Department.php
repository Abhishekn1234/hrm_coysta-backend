<?php

// app/Models/Department.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = ['department_name', 'description', 'department_head'];
}
