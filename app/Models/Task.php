<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};
use Illuminate\Database\Eloquent\{Builder, Collection, Model};

/**
 * @property mixed $comments
 */
class Task extends Model
{
    /**
     * Class Task
     *
     * @property int $id
     * @property string $title
     * @property string $description
     * @property string $status
     * @property-read Collection|Comment[] $comments
     * @property-read User $user
     *
     * @method static Builder|Task byKeyword(string $keyword)
     * @method static Builder|Task byStatus(string $status)
     * @method static Builder|Task byCommentKeyword(string $keyword)
     * @method static Builder|Task byCommentUser(string $userName)
     */
    public function scopeByKeyword(Builder $query, string $keyword): Builder
    {
        return $query->where(function (Builder $q) use ($keyword) {
            $q->where('title', 'like', "%$keyword%")
                ->orWhere('description', 'like', "%$keyword%");
        });
    }

    /**
     * Escopo para filtrar pelo status da tarefa
     *
     * @param Builder $query
     * @param string $status
     * @return Builder
     */
    public function scopeByStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    /**
     * Escopo para filtrar por palavra-chave nos comentários
     *
     * @param Builder $query
     * @param string $keyword
     * @return Builder
     */
    public function scopeByCommentKeyword(Builder $query, string $keyword): Builder
    {
        return $query->whereHas('comments', function (Builder $q) use ($keyword) {
            $q->where('comment', 'like', "%$keyword%");
        });
    }

    /**
     * Escopo para filtrar por nome do usuário de comentário
     *
     * @param Builder $query
     * @param string $userName
     * @return Builder
     */
    public function scopeByCommentUser(Builder $query, string $userName): Builder
    {
        return $query->whereHas('comments.user', function (Builder $q) use ($userName) {
            $q->where('name', $userName);
        });
    }

    /**
     * Relation with comments
     *
     * @return HasMany<Comment>
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
    /**
     * Relation with the assigned user
     *
     * @return BelongsTo<User, Task>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
