<?php

use App\Http\Controllers\NotesController;
use App\Http\Controllers\TasksController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('/notes', NotesController::class);
Route::apiResource('/tasks', TasksController::class);
