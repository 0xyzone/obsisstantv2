<?php

use App\Models\ObsSetting;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Route;
use Symfony\Component\Process\Process;
use App\Http\Controllers\ObsController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Route::get('/connectOBS', [ObsController::class, 'connectToObs'])->name('connectOBS');
Route::get('/connectOBS', function () {
    // Here you might validate user permissions or check settings
    return response()->json(['status' => 'success']);
})->name('connectOBS');

Route::get('demo', function() {
    if(auth()->user()){
        $tenant = filament()->getTenant();
    } else {
        $tenant = "no tenant available";
    }
    return view('demo', compact('tenant'));
})->name('demo');

Route::get('send-test-email', function () {
    $details = [
        'subject' => 'Test Email',
        'body' => 'This is a test email.'
    ];

    \Mail::to('sumnsth@gmail.com')->send(new \App\Mail\TestMail($details));

    return 'Test email sent!';
});