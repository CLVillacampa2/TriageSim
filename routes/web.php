<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TriageController;

/*
|--------------------------------------------------------------------------
| Web Routes Configuration
|--------------------------------------------------------------------------
*/

// Main TriageSim portal route
Route::get('/', [TriageController::class, 'index'])->name('triage.home');

// API endpoint to post simulation results to MySQL
Route::post('/api/sessions', [TriageController::class, 'storeSession'])->name('triage.store');