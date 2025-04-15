<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/projects/{project}/tasks', [TaskController::class, 'store'])->name('tasks.store')->middleware('auth');
Route::delete('/tasks/{task}', [TaskController::class, 'delete'])->name('tasks.delete')->middleware('auth');
Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update')->middleware('auth');
