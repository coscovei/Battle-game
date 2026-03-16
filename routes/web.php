<?php

use App\Http\Controllers\BattleController;
use Illuminate\Support\Facades\Route;

Route::get('/', [BattleController::class, 'index'])->name('battles.index');
Route::get('/battles', [BattleController::class, 'history'])->name('battles.history');

Route::post('/battle/start', [BattleController::class, 'start'])->name('battles.start');
Route::post('/battle/{battle}/next-turn', [BattleController::class, 'nextTurn'])->name('battles.next-turn');
Route::post('/battle/{battle}/run-to-end', [BattleController::class, 'runToEnd'])->name('battles.run-to-end');

Route::get('/battle/{battle}', [BattleController::class, 'show'])->name('battles.show');
