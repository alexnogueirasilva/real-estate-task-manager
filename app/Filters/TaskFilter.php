<?php

namespace App\Filters;

use App\Models\{Building, Task};
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class TaskFilter
{
    /**
     * Apply filters to the task query.
     *
     * @param Building $building
     * @param array<string, mixed> $filters
     * @return Builder<Task>
     */
    public static function apply(Building $building, array $filters): Builder
    {
        // Modificação: Pegue a query do relacionamento HasMany para trabalhar com o Builder
        /** @var Builder<Task> $query */
        $query = $building->tasks()->getQuery();

        if (isset($filters['task_keyword']) && is_string($filters['task_keyword'])) {
            $query->byKeyword($filters['task_keyword']);
        }

        if (isset($filters['status']) && is_string($filters['status'])) {
            $query->byStatus($filters['status']);
        }

        if (isset($filters['comment_keyword']) && is_string($filters['comment_keyword'])) {
            $query->byCommentKeyword($filters['comment_keyword']);
        }

        if (isset($filters['comment_user']) && is_string($filters['comment_user'])) {
            $commentUser = $filters['comment_user'];

            $query->whereHas('comments.user', function ($q) use ($commentUser) {
                $q->where('name', 'like', "%{$commentUser}%");
            });

            $query->with(['comments' => function ($q) use ($commentUser) {
                $q->whereHas('user', function ($q2) use ($commentUser) {
                    $q2->where('name', 'like', "%{$commentUser}%");
                });
            }]);
        } else {
            $query->with(['comments.user', 'user']);
        }

        if (isset($filters['created_from'], $filters['created_to'])) {
            $startDate = Carbon::parse($filters['created_from'])->startOfDay();
            $endDate   = Carbon::parse($filters['created_to'])->endOfDay();

            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        // Certifique-se de retornar o Builder corretamente
        return $query;
    }
}
