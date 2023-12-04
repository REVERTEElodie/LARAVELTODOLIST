<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TaskController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\CategoryController;

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

// **************
// ==== TASK ===
// **************

// Route GET pour la liste des taches
Route::get('/tasks', [TaskController::class, 'list']);
// Route GET pour une tache
// [0-9]+ => 1 caractère ou plus. Chaque caractère est compris entre 0 et 9
Route::get('/tasks/{id}', [TaskController::class, 'show'])->where(['id', '[0-9]+']);

// Route pour supprimer un tache
Route::delete('/tasks/{id}', [TaskController::class, 'delete'])->where(['id', '[0-9]+']);

// Route pour ajouter une tache
Route::post('/tasks', [TaskController::class, 'create']);

// Route pour modifier une tache
Route::put('/tasks/{id}', [TaskController::class, 'update'])->where(['id', '[0-9]+']);



// **************
// = CATEGORIES =
// **************

// Read
Route::get('/categories', [CategoryController::class, 'list']);
Route::get('/categories/{id}', [CategoryController::class, 'show'])->where('id', '[0-9]+');
// Delete
Route::delete('/categories/{id}', [CategoryController::class, 'delete'])->where('id', '[0-9]+');
// CREATE
Route::post('/categories', [CategoryController::class, 'create']);
// Update
Route::put('/categories/{id}', [CategoryController::class, 'update'])->where('id', '[0-9]+');


// **************
// ==== TAGS ====
// **************

// Read
Route::get('/tags', [TagController::class, 'list']);
Route::get('/tags/{id}', [TagController::class, 'show'])->where('id', '[0-9]+');
// Delete
Route::delete('/tags/{id}', [TagController::class, 'delete'])->where('id', '[0-9]+');
// CREATE
Route::post('/tags', [TagController::class, 'create']);
// Update
Route::put('/tags/{id}', [TagController::class, 'update'])->where('id', '[0-9]+');

