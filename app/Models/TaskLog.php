<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}