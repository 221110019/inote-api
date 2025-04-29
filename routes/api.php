<?php

use App\Http\Controllers\NotesController;
use App\Http\Controllers\TasksController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('user/register', [UserController::class, 'register'])->name('user.register');
Route::post('user/login', [UserController::class, 'login'])->name('user.login');

Route::get('/notes', [NotesController::class, 'index'])->name('notes.index');
Route::get('/tasks', [TasksController::class, 'index'])->name('tasks.index');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('user/logout', [UserController::class, 'logout'])->name('user.logout');

    Route::post('/notes', [NotesController::class, 'store'])->name('notes.store');
    Route::put('/notes/{note}', [NotesController::class, 'update'])->name('notes.update');
    Route::delete('/notes/{note}', [NotesController::class, 'destroy'])->name('notes.destroy');

    Route::post('/tasks', [TasksController::class, 'store'])->name('tasks.store');
    Route::put('/tasks/{task}', [TasksController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{task}', [TasksController::class, 'destroy'])->name('tasks.destroy');
});

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// Route::middleware('auth:sanctum')->prefix('user')->group(function () {
//     Route::post('logout', [UserController::class, 'logout'])->name('user.logout');
// });

// Route::post('user/register', [UserController::class, 'register'])->name('user.register');
// Route::post('user/login', [UserController::class, 'login'])->name('user.login');

// Route::apiResource('/notes', NotesController::class);
// Route::apiResource('/tasks', TasksController::class);