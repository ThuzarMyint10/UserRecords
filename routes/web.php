<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Jobs\FetchAndStoreUserRecordsJob;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

