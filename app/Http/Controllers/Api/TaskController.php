<?php

namespace App\Http\Controllers\Api;

use App\Filters\TaskFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Building\{BuildingRequest, StoreTaskRequest};
use App\Http\Resources\Api\Building\{BuildingResource, TaskResource};
use App\Models\{Building};

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

        $tasksQuery->with(['comments' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }]);

        $tasks = $tasksQuery->paginate(10);

        return new BuildingResource($building->setRelation('tasks', $tasks));
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
