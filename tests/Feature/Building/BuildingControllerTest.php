<?php

use App\Models\{Building, Comment, Task, User};
use Carbon\Carbon;

use function Pest\Laravel\{getJson, postJson};

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

    $task1 = Task::factory()->create([
        'title'       => 'Consertar elevador',
        'status'      => 'done',
        'building_id' => $building->id,
        'created_at'  => Carbon::create(2024, 9, 10),
    ]);
    Comment::factory()->create(['task_id' => $task1->id, 'user_id' => $user->id, 'comment' => 'Elevador consertado']);

    Task::factory()->create([
        'title'       => 'Reparar telhado',
        'status'      => 'in_progress',
        'building_id' => $building->id,
        'created_at'  => Carbon::create(2024, 9, 11),
    ]);

    Task::factory()->create([
        'title'       => 'Pintar o saguão',
        'status'      => 'open',
        'building_id' => $building->id,
        'created_at'  => Carbon::create(2024, 9, 13),
    ]);

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
    [['created_from' => '2024-09-10', 'created_to' => '2024-09-11'], 'Reparar telhado', ['Pintar o saguão']],
    [['created_from' => '2024-09-10', 'created_to' => '2024-09-12'], 'Consertar elevador', ['Pintar o saguão']],
]);

it('creates a new task for a building', function () {
    $building = Building::factory()->create();
    $user     = User::factory()->create();

    $payload = [
        'title'       => 'Consertar elevador',
        'description' => 'O elevador do edifício precisa de conserto',
        'status'      => 'open',
        'assigned_to' => $user->id,
    ];

    $response = postJson(route('tasks.store', $building->id), $payload);

    $response->assertStatus(Response::HTTP_CREATED)
        ->assertJsonFragment([
            'title'  => 'Consertar elevador',
            'status' => 'open',
        ]);
});

it('adds a comment to a task', function () {
    $task = Task::factory()->create();
    $user = User::factory()->create();

    $payload = [
        'comment' => 'O conserto do elevador foi finalizado com sucesso',
        'user_id' => $user->id,
    ];

    // Note como estamos passando tanto o 'building_id' quanto o 'task_id' para a rota
    $response = postJson(route('comments.store', ['task' => $task->id]), $payload);

    $response->assertStatus(Response::HTTP_CREATED)
        ->assertJsonFragment([
            'comment' => 'O conserto do elevador foi finalizado com sucesso',
        ]);
});
