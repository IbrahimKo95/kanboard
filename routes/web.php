<?php

use App\Http\Controllers\CalendarExportController;
use App\Http\Controllers\ColumnController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
use App\Http\Controllers\InvitationController;


Route::middleware('auth')->group(function () {
    Route::get('/projects/{project}/list', [TaskController::class, 'list'])->name('tasks.list');
    Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::post('/projects/store', [ProjectController::class, 'store'])->name('projects.store');
    Route::get('/projects/create', [ProjectController::class, 'creationForm'])->name('projects.create');
    Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');
    Route::patch('/projects/{project}/update', [ProjectController::class, 'update'])->name('projects.update');
    Route::get('/projects/{project}/kanban', [ProjectController::class, 'kanban'])->name('projects.kanban');
    Route::post('/projects/{project}/{column}/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::post('/projects/{project}/tasks', [TaskController::class, 'storeFromList'])->name('tasks.storeFromList');
    Route::post('/projects/{project}/invitations', [InvitationController::class, 'send'])->name('invitations.send');
    Route::get('/projects/{project}/users', [ProjectController::class, 'showUsers'])->name('projects.users');
    Route::patch('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{task}', [TaskController::class, 'delete'])->name('tasks.delete');
    Route::post('/projects/{project}/columns', [ColumnController::class, 'store'])->name('columns.store');
    Route::post('/tasks/reorder', [TaskController::class, 'reorder'])->name('tasks.reorder');
    Route::get('/projects/{project}/calendar', [TaskController::class, 'calendar'])->name('projects.calendar');
    Route::get('/calendar/{project}.ics', [CalendarExportController::class, 'export'])->name('calendar.export');
});


Route::get('/invitations/accept/{token}', [InvitationController::class, 'accept'])->name('invitations.accept');

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

// Routes publiques
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


