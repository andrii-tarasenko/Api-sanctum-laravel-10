<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'status',
        'user_id',
        'team_id',
    ];

    /**
     * @return BelongsTo
     */
    public function users()
    {
        return $this->belongsTo(User::class);
    }

    public function teams()
    {
        return $this->belongsTo(Team::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
