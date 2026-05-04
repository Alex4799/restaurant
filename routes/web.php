<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard',[AuthController::class,'dashboard'] )->middleware(['auth', 'verified'])->name('dashboard');


require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
