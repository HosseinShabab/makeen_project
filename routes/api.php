<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\TicketController;
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
// Route Tickets
Route::group(['prefix' => 'tickets','as' => 'tickets.'], function(){
    Route::get('index/{id?}', [TicketController::class, 'index'])->name('index');
    Route::post('create', [TicketController::class, 'store'])->name('create');
    Route::put('edit/{id}', [TicketController::class, 'update'])->name('edit');
    Route::delete('delete/{id}', [TicketController::class, 'delete'])->name('delete');
});

//Route Messages
Route::group(['prefix' => 'messages','as' => 'messages.'], function(){
    Route::get('index/{id?}', [MessageController::class, 'index'])->name('index');
    Route::post('create', [MessageController::class, 'store'])->name('create');
    Route::put('edit/{id}', [MessageController::class, 'update'])->name('edit');
    Route::delete('delete/{id}', [MessageController::class, 'delete'])->name('delete');
});


Route::group(['prefix' => 'loans', 'as' => 'loans.'], function () {

    Route::post('index/{id?}', [LoanController::class, 'index'])->name('index');
    Route::put('edit/{id}', [LoanController::class, 'update'])->name('edit');
    Route::post('create', [LoanController::class, 'store'])->name('create');
    Route::delete('delete/{id}', [LoanController::class, 'delete'])->name('delete');

});

Route::group(['prefix' => 'payments', 'as' => 'payments.'], function () {

    Route::post('index/{id?}', [PaymentController::class, 'index'])->name('index');
    Route::put('edit/{id}', [PaymentController::class, 'update'])->name('edit');
    Route::post('create', [PaymentController::class, 'store'])->name('create');
    Route::delete('delete/{id}', [PaymentController::class, 'delete'])->name('delete');
});

//users route
    Route::prefix('users/')->as('users.')->group(function () {
    Route::get('index/{id?}', [UserController::class, 'index'])->name('index');
    Route::post('create', [UserController::class, 'store'])->name('create');
    Route::put('edit/{id}', [UserController::class, 'update'])->name('edit');
    Route::delete('delete/{id}', [UserController::class, 'delete'])->name('delete');
});

//auth routs
Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {

    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('login/admin', [AuthController::class, 'loginAdmin'])->name('login.admin');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('me', [AuthController::class, 'me'])->name('me');

});
