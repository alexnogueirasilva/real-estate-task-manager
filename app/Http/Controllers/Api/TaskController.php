<?php

namespace App\Http\Controllers\Api;

use App\Filters\TaskFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Building\BuildingRequest;
use App\Http\Resources\Api\Building\BuildingResource;
use App\Models\{Building, Comment, Task};
use Illuminate\Database\Eloquent\Collection;

class TaskController extends Controller
{
    /**
     * Display filtered tasks of a building.
     *
     * @param Building $building
     * @param BuildingRequest $request
     * @return BuildingResource
     */
    public function index(Building $building, BuildingRequest $request): BuildingResource
    {
        $filters = $request->validated();

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
