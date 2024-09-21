<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Building\BuildingResource;
use App\Models\Building;
use Illuminate\Support\Facades\Request;

class TaskController extends Controller
{
    public function index(Building $building, Request $request): BuildingResource
    {
        $building->load(['tasks.user', 'tasks.comments.user']);

        return new BuildingResource($building);
    }
}
