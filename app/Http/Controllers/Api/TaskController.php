<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Building;

class TaskController extends Controller
{
    public function index(Building $building)
    {
        return $building->load(['tasks', 'tasks.user', 'tasks.comments']);
    }
}
