<?php

use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');

Route::get('/projects/{project}/list', [TaskController::class, 'list'])->name('tasks.list');

Route::middleware('auth')->group(function () {
    Route::post('/projects/{project}/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{task}', [TaskController::class, 'delete'])->name('tasks.delete');
    
    Route::put('/tasks/{task}/assign', [TaskController::class, 'assignUser'])->name('tasks.assign');
    Route::put('/tasks/{task}/unassign', [TaskController::class, 'unassignUser'])->name('tasks.unassign');
});
