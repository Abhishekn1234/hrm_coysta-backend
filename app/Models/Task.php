<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;

    protected $table = 'task';

    protected $fillable = [
        'user_id',
        'task_name',
        'task_description',
        'deadline',
        'assigned_by',
        'project_name',
        'project_id',
        'customer',
        'project_value',
        'project_status',
        'duration'
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function assigner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(TaskLog::class);
    }
}



class TaskLog extends Model
{
    use HasFactory;

    protected $table = 'task_logs';

     protected $fillable = [
    'task_id',
    'user_id',
    'resumed_at',
    'paused_at',
    'ended_at',
    'duration',
    'log_date'  // <-- added
];

protected $casts = [
    'resumed_at' => 'datetime',
    'paused_at' => 'datetime',
    'ended_at' => 'datetime',
    'log_date' => 'date', // <-- optional, but recommended
];
}
class TaskTracking extends Model
{
    use HasFactory;

    protected $table = 'task_trackings';

    protected $fillable = [
        'task_id',
        'user_id',
        'time_taken',
        'date',
        'time'
    ];

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

