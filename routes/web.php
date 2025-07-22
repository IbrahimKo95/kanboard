<?php

use App\Http\Controllers\ColumnController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
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

// Routes protégées par auth
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/', [ProjectController::class, 'index'])->name('home');
    Route::post('/store', [ProjectController::class, 'store'])->name('projects.store');
    Route::get('/create', [ProjectController::class, 'creationForm'])->name('projects.create');
    Route::get('/profil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profil', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/{project}/list', [TaskController::class, 'list'])->name('tasks.list');
    Route::get('/{project}', [ProjectController::class, 'show'])->name('projects.show');
    Route::get('/{project}/kanban', [ProjectController::class, 'kanban'])->name('projects.kanban');
    Route::post('/{project}/{column}/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::patch('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{task}', [TaskController::class, 'delete'])->name('tasks.delete');
    Route::post('/{project}/columns', [ColumnController::class, 'store'])->name('columns.store');
    Route::post('/tasks/reorder', [TaskController::class, 'reorder'])->name('tasks.reorder');
    Route::get('/{project}/calendar', [TaskController::class, 'calendar'])->name('projects.calendar');
    Route::post('/{project}/invite', [ProjectController::class, 'invite'])->name('projects.invite');
    Route::post('/invitations/{invitation}/accept', [\App\Http\Controllers\InvitationController::class, 'accept'])->name('invitations.accept');
    Route::post('/invitations/{invitation}/refuse', [\App\Http\Controllers\InvitationController::class, 'refuse'])->name('invitations.refuse');
});

