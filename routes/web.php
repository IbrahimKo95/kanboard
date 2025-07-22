<?php

use App\Http\Controllers\ColumnController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('home');
    } else {
        return redirect()->route('login');
    }
});
Route::get('/{project}/list', [TaskController::class, 'list'])->name('tasks.list');

Route::middleware('auth')->group(function () {
    Route::get('/', [ProjectController::class, 'index'])->name('home');
    Route::post('/store', [ProjectController::class, 'store'])->name('projects.store');
    Route::get('/create', [ProjectController::class, 'creationForm'])->name('projects.create');
    Route::get('/{project}', [ProjectController::class, 'show'])->name('projects.show');
    Route::get('/{project}/kanban', [ProjectController::class, 'kanban'])->name('projects.kanban');
    Route::post('/{project}/{column}/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::patch('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{task}', [TaskController::class, 'delete'])->name('tasks.delete');
});

use App\Http\Controllers\Auth\RegisterController;

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

use App\Http\Controllers\Auth\LoginController;

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

Route::post('/{project}/columns', [ColumnController::class, 'store'])->name('columns.store');
Route::post('/tasks/reorder', [TaskController::class, 'reorder'])->name('tasks.reorder');

Route::get('/{project}/calendar', [TaskController::class, 'calendar'])->name('projects.calendar');

