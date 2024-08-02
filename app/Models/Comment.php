<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    public function users()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the task that owns the comment.
     */
    public function tasks()
    {
        return $this->belongsTo(Task::class);
    }
}
