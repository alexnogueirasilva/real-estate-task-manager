<?php

use App\Models\{Building, Comment, Task, User};

use function Pest\Laravel\getJson;

it('should be able to list tasks with comments', function () {
    $user     = User::factory()->create();
    $building = Building::factory()->create();
    $task     = Task::factory()->create(['building_id' => $building->id, 'assigned_to' => $user->id]);
    $comment  = Comment::factory()->create(['task_id' => $task->id, 'user_id' => $user->id]);

    $response = getJson(route('tasks.index', $building->id));

    $response->assertJsonStructure([
        'data' => [
            'id',
            'name',
            'address',
            'tasks' => [
                '*' => [
                    'id',
                    'title',
                    'description',
                    'status',
                    'assigned_to',
                    'comments' => [
                        '*' => [
                            'comment',
                            'user',
                        ],
                    ],
                ],
            ],
        ],
    ]);

    $response->assertJsonFragment([
        'title'   => $task->title,
        'comment' => $comment->comment,
    ]);

});
