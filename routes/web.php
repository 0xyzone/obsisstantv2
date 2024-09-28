<?php

use Illuminate\Support\Facades\Route;
use Symfony\Component\Process\Process;
use App\Http\Controllers\ObsController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/connectOBS', [ObsController::class, 'connectToObs'])->name('connectOBS');

Route::view('demo', 'demo')->name('demo');