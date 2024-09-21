<?php

namespace App\Http\Controllers\Api;

use App\Filters\TaskFilter;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Building\BuildingResource;
use App\Models\{Building, Comment, Task};
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display filtered tasks of a building.
     *
     * @param  Building  $building
     * @param  Request   $request
     * @return BuildingResource
     */
    public function index(Building $building, Request $request): BuildingResource
    {
        $filters = $request->only(['task_keyword', 'status', 'comment_keyword', 'comment_user']);

        $tasksQuery = TaskFilter::apply($building, $filters);

        /** @var Collection<int, Task> $tasks */
        $tasks = $tasksQuery->get();

        if (isset($filters['comment_user']) && is_string($filters['comment_user'])) {
            $tasks = $tasks->filter(function (Task $task): bool {
                $comments = $task->comments;
                assert($comments instanceof Collection && $comments->first() instanceof Comment);

                return $comments->isNotEmpty();
            })->values();
        }

        $building->setRelation('tasks', $tasks);

        return new BuildingResource($building);
    }
}
