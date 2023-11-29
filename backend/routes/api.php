<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// route GET pour la listes taches
Route::get('/tasks', [TaskController::class, 'list']);

// route GET pour une tache
// [0-9]->un caractere ou plus,  compris entre 0 et 9
Route::get('/tasks/{id}', [TaskController::class, 'show'])->where('id', '[0-9]+');

Route::get('/categories', [CategoryController::class, 'list']);
Route::get('/categories/{id}', [CategoryController::class, 'show'])->where('id', '[0-9]+');

// creér un POST
Route::post('/tasks', [TaskController::class, 'create']);
Route::post('/categories', [CategoryController::class, 'create']);
Route::post('/tags', [TagController::class, 'create']);

//update 1 PUT UPDATE
Route::put('/tasks/{id}', [TaskController::class, 'update'])->where('id', '[0-9]+');
Route::put('/categories/{id}', [CategoryController::class, 'update'])->where('id', '[0-9]+');
Route::put('/tags/{id}', [TagController::class, 'update'])->where('id', '[0-9]+');

//delete 1 PUT DELETE
Route::put('/tasks/{id}', [TaskController::class, 'delete'])->where('id', '[0-9]+');
Route::put('/categories/{id}', [CategoryController::class, 'delete'])->where('id', '[0-9]+');
Route::put('/tags/{id}', [TagController::class, 'delete'])->where('id', '[0-9]+');
