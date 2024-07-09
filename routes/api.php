<?php

use App\Http\Controllers\LoanController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'loans', 'as' => 'loans.'], function () {

    Route::get('index/{id?}', [LoanController::class, 'index'])->name('index');
    Route::put('edit/{id}', [LoanController::class, 'update'])->name('edit');
    Route::post('create', [LoanController::class, 'store'])->name('store');
    Route::delete('delete/{id}', [LoanController::class, 'destroy'])->name('destroy');

});
