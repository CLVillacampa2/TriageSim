<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TriageController;

Route::get('/', [TriageController::class, 'index']);
Route::post('/api/sessions', [TriageController::class, 'storeSession']);
