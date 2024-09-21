<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Building\StoreCommentRequest;
use App\Http\Resources\Api\Building\CommentResource;
use App\Models\Task;

class CommentController extends Controller
{
    /**
     * Store a newly created comment for a task.
     *
     * @param StoreCommentRequest $request
     * @param Task $task
     * @return CommentResource
     */
    public function store(StoreCommentRequest $request, Task $task): CommentResource
    {
        $comment = $task->comments()->create($request->validated());

        return new CommentResource($comment);
    }
}
