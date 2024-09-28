<?php

use App\Models\ObsSetting;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Route;
use Symfony\Component\Process\Process;
use App\Http\Controllers\ObsController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/connectOBS', [ObsController::class, 'connectToObs'])->name('connectOBS');

Route::get('demo', function() {
    $userId = auth()->user()->id;
    $setting = ObsSetting::where('user_id', $userId)->first();
    $password = Crypt::decryptString($setting->password);
    return view('demo', compact('setting'));
})->name('demo');