<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Comment
 *
 * @property int $id
 * @property string $comment
 * @property int $task_id
 * @property int $user_id
 * @property-read User $user
 * @property-read Task $task
 */
class Comment extends Model
{
    use HasFactory;

    /**
     * Relation with the task
     *
     * @return BelongsTo<Task, Comment>
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * Relation with the user who made the comment
     *
     * @return BelongsTo<User, Comment>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
