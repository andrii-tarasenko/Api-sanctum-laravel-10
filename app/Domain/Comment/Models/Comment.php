<?php

namespace Domain\Comment\Models;

use Domain\Task\Models\Task;
use Domain\User\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $content
 *
 * @property int $task_id
 * @property int $user_id
 */
class Comment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'content',
        'task_id',
        'user_id',
    ];

    /**
     * Get the user that owns the comment.
     */
    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the task that owns the comment.
     */
    public function tasks(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }
}
