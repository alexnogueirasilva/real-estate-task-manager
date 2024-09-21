<?php

namespace App\Http\Controllers\Api;

use App\Filters\TaskFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Building\{BuildingRequest, StoreTaskRequest};
use App\Http\Resources\Api\Building\{BuildingResource, TaskResource};
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

    /**
     * Store a newly created task in storage.
     *
     * @param StoreTaskRequest $request
     * @param Building $building
     * @return TaskResource
     */
    public function store(StoreTaskRequest $request, Building $building): TaskResource
    {
        $task = $building->tasks()->create($request->validated());

        return new TaskResource($task);
    }
}
