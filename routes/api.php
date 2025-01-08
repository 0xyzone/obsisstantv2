<?php

use App\Http\Controllers\AssetController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MatchController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('{user}')->group(function () {
    Route::get('/match', [MatchController::class, 'index']);
    Route::get('/assets', [AssetController::class, 'index']);
});