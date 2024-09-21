<?php

use App\Models\{Building, Comment, Task, User};

use function Pest\Laravel\getJson;

use Symfony\Component\HttpFoundation\Response;

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

it('filtra tarefas com diferentes filtros', function ($filters, $expectedTitle, $unexpectedTitles) {
    $building = Building::factory()->create();

    $user = User::factory()->create(['name' => 'John Doe']);

    $task1 = Task::factory()->create(['title' => 'Consertar elevador', 'status' => 'done', 'building_id' => $building->id]);
    Comment::factory()->create(['task_id' => $task1->id, 'user_id' => $user->id, 'comment' => 'Elevador consertado']);

    Task::factory()->create(['title' => 'Reparar telhado', 'status' => 'in_progress', 'building_id' => $building->id]);
    Task::factory()->create(['title' => 'Pintar o saguão', 'status' => 'open', 'building_id' => $building->id]);

    $response = getJson(route('tasks.index', array_merge(['building' => $building->id], $filters)));

    $response->assertStatus(Response::HTTP_OK)
        ->assertJsonFragment(['title' => $expectedTitle]);

    foreach ($unexpectedTitles as $title) {
        $response->assertJsonMissing(['title' => $title]);
    }
})->with([
    [['task_keyword' => 'Consertar'], 'Consertar elevador', ['Reparar telhado', 'Pintar o saguão']],
    [['status' => 'done'], 'Consertar elevador', ['Reparar telhado', 'Pintar o saguão']],
    [['comment_keyword' => 'Elevador'], 'Consertar elevador', ['Reparar telhado', 'Pintar o saguão']],
    [['comment_user' => 'John Doe'], 'Consertar elevador', ['Reparar telhado', 'Pintar o saguão']],
    [['task_keyword' => 'Consertar', 'status' => 'done'], 'Consertar elevador', ['Reparar telhado', 'Pintar o saguão']],
    [['task_keyword' => 'Consertar', 'status' => 'done', 'comment_user' => 'John Doe'], 'Consertar elevador', ['Reparar telhado', 'Pintar o saguão']],
]);
