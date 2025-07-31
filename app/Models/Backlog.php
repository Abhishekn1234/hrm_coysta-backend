<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Backlog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'backlog_assigned_user_id',
        'project_id',
        'backlog_taken_user_id',
        'assigned_task_id',
        'sprint_name',
        'backlog_name',
        'backlog_description',
        'estimated_time',
        'ceo_approval',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'backlog_assigned_user_id');
    }

    public function takenUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'backlog_taken_user_id');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'assigned_task_id');
    }
}
