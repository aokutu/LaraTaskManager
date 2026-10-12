<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
Route::post('/tasks', [TaskController::class, 'newtask'])->name('tasks.newtask');
Route::get('/tasks/delete/{id}', [TaskController::class, 'delete'])->name('tasks.delete');
Route::get('/tasks/edit/{id}', [TaskController::class, 'edit'])->name('tasks.edit');
Route::post('/tasks/update/{id}', [TaskController::class, 'update'])->name('tasks.update');
Route::post('/tasks/reorder', [TaskController::class, 'reorder'])->name('tasks.reorder');

