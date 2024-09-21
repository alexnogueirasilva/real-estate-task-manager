<?php

use App\Models\{Building, Comment, Task, User};

use function Pest\Laravel\getJson;

it('should be able to list comments with tasks', function () {
    $user     = User::factory()->create();
    $building = Building::factory()->create();
    $task     = Task::factory()->create(['building_id' => $building->id]);
    $comment  = Comment::factory()->create(['task_id' => $task->id, 'user_id' => $user->id]);

    $response = getJson(route('buildings.tasks.comments.index', [$building->id, $task->id]));

    $response->assertStatus(200);
    $response->assertJson([
        'data' => [
            [
                'id'      => $comment->id,
                'task_id' => $task->id,
                'comment' => $comment->comment,
            ],
        ],
    ]);
});
