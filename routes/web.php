<?php

use App\Http\Controllers\ScreenController;
use App\Livewire\StartingSoon;
use App\Models\ObsSetting;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Route;
use Symfony\Component\Process\Process;
use App\Http\Controllers\ObsController;

Route::get('/', function () {
    return redirect()->route('filament.dashboard.pages.dashboard');
})->name('home');

Route::get('phpinfo', function () {
    return phpinfo();
})->name('phpinfo');

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

Route::group(['prefix'=> 'screen'], function () {
    Route::get('/{tournament_id}/{match_id}', function () {
        return view('screen.assets.versus');
    });
});

Route::group(['prefix'=> '/{id}'], function () {
    Route::get('/vs', [ScreenController::class,'versus'])->name('screen.versus');
    Route::get('/teama', [ScreenController::class,'teama'])->name('screen.teama');
    Route::get('/teamb', [ScreenController::class,'teamb'])->name('screen.teamb');
    Route::get('/matchstats', [ScreenController::class,'matchstats'])->name('screen.matchstats');
    Route::get('/winner', [ScreenController::class,'winner'])->name('screen.winner');
    Route::get('/mvp', [ScreenController::class,'mvp'])->name('screen.mvp');
    Route::get('/mvph2h', [ScreenController::class,'mvph2h'])->name('screen.mvph2h');
    Route::get('/solocaster', [ScreenController::class,'soloCaster'])->name('screen.caster1');
});


Route::get('/{user}/start', StartingSoon::class)->name('starting');