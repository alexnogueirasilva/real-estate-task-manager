<?php

use App\Models\{Task, User};

use function Pest\Laravel\postJson;

use Symfony\Component\HttpFoundation\Response as ResponseAlias;

it('adds a comment to a task', function () {
    $task = Task::factory()->create();
    $user = User::factory()->create();

    $payload = [
        'comment' => 'O conserto do elevador foi finalizado com sucesso',
        'user_id' => $user->id,
    ];

    $response = postJson(route('comments.store', ['task' => $task->id]), $payload);

    $response->assertStatus(ResponseAlias::HTTP_CREATED)
        ->assertJsonFragment([
            'comment' => 'O conserto do elevador foi finalizado com sucesso',
        ]);
});

test('validation rules for comment creation', function ($f) {

    $task = Task::factory()->create();

    $payload = [
        $f->field => $f->value,
    ];

    if (property_exists($f, 'aValue')) {
        $payload[$f->aField] = $f->aValue;
    }

    $response = postJson(route('comments.store', $task->id), $payload);

    // Verificar apenas a presença do erro de validação para o campo
    $response->assertStatus(422)
        ->assertJsonValidationErrors([$f->field]);

})->with([
    'comment::required' => (object)['field' => 'comment', 'value' => '', 'rule' => 'required'],
    'user_id::required' => (object)['field' => 'user_id', 'value' => '', 'rule' => 'required'],
    'user_id::exists'   => (object)['field' => 'user_id', 'value' => 999, 'rule' => 'exists'], // ID inexistente
    'comment::string'   => (object)['field' => 'comment', 'value' => 12345, 'rule' => 'string'], // comentário inválido
]);
