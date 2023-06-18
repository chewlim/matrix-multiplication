<?php

use App\Http\Controllers\MultiplyMatricesController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return to_route('multiply.show');
});

Route::get('/multiply', [MultiplyMatricesController::class, 'show'])->name('multiply.show');

Route::post('/multiply', [MultiplyMatricesController::class, 'store'])->name('multiply.store');
