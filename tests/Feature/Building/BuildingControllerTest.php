<?php

use App\Models\{Building, Comment, Task, User};

use function Pest\Laravel\getJson;

it('should be able to list comments with tasks', function () {
    $user     = User::factory()->create();
    $building = Building::factory()->create();
    $task     = Task::factory()->create(['building_id' => $building->id]);
    $comment  = Comment::factory()->create(['task_id' => $task->id, 'user_id' => $user->id]);

    $response = getJson(route('tasks.index', [$building->id, $task->id]));

    $response->assertJsonStructure([
        'id',
        'name',
        'address',
        'created_at',
        'updated_at',
        'tasks' => [
            '*' => [
                'id',
                'building_id',
                'assigned_to',
                'title',
                'description',
                'status',
                'created_at',
                'updated_at',
                'user' => [
                    'id',
                    'name',
                    'email',
                    'email_verified_at',
                    'created_at',
                    'updated_at',
                ],
                'comments' => [
                    '*' => [
                        'id',
                        'task_id',
                        'user_id',
                        'comment',
                        'created_at',
                        'updated_at',
                    ],
                ],
            ],
        ],
    ]);
});
