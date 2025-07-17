<?php

use App\Http\Controllers\ColumnController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
// Route::get('/projects/{project}/list', [TaskController::class, 'list'])->name('tasks.list');
use App\Http\Controllers\InvitationController;
use App\Mail\ProjectInvitationMail;
use Illuminate\Support\Facades\Mail;
use App\Models\Invitation;


Route::middleware('auth')->group(function () {
    Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::post('/projects/store', [ProjectController::class, 'store'])->name('projects.store');
    Route::get('/projects/create', [ProjectController::class, 'creationForm'])->name('projects.create');
    Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');
    Route::post('/projects/{project}/{column}/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::post('/projects/{project}/tasks', [TaskController::class, 'storeFromList'])->name('tasks.storeFromList');
    Route::post('/projects/{project}/invitations', [InvitationController::class, 'send'])->name('invitations.send');
    Route::patch('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{task}', [TaskController::class, 'delete'])->name('tasks.delete');
});


Route::get('/invitations/accept/{token}', [InvitationController::class, 'accept'])->name('invitations.accept');

use App\Http\Controllers\Auth\RegisterController;

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

use App\Http\Controllers\Auth\LoginController;

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::post('/projects/{project}/columns', [ColumnController::class, 'store'])->name('columns.store');
Route::post('/tasks/reorder', [TaskController::class, 'reorder'])->name('tasks.reorder');


