<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CSVController;
use App\Http\Controllers\GenerateArrayController;
use App\Http\Controllers\TestController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [CSVController::class, 'index'])->name('index');

// 配列生成
Route::post('/generate', [GenerateArrayController::class, 'generateArr'])->name('generateArr');
// Route::post('/generate', [CSVController::class, 'download'])->name('download');

// CSVダウンロード
Route::post('/download', [CSVController::class, 'download'])->name('download');

// 練習
Route::get('/practice', [TestController::class, 'practice'])->name('practice');
