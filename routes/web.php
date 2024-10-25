<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameOfLifeController;

Route::get('/', [GameOfLifeController::class, 'index']);
Route::post('/load-file', [GameOfLifeController::class, 'loadFile']);
Route::post('/next-generation', [GameOfLifeController::class, 'calculateNextGeneration']);
