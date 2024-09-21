<?php

use App\Http\Controllers\Api\{CommentController, TaskController};
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return response()->json(
        ['status' => 'Api is working fine!'],
        200
    );
})->name('api.status');

Route::prefix('tasks/{building}')->group(function () {
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
});

Route::prefix('tasks/{task}')->group(function () {
    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
});
