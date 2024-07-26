<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\InstallmentController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\TicketController;
use GuzzleHttp\Middleware;
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

///Route me
    Route::get('show/{id?}',[AuthController::class, 'show'])->name('show');

});
// Route Tickets
Route::group(['prefix' => 'tickets','as' => 'tickets.'], function(){
    Route::get('index/{id?}', [TicketController::class, 'index'])->name('index');
    Route::post('create', [TicketController::class, 'store'])->name('create');
});

//Route Messages
Route::group(['prefix' => 'messages','as' => 'messages.'], function(){
    Route::get('index/{id?}', [MessageController::class, 'index'])->name('index');
    Route::post('create', [MessageController::class, 'store'])->name('create');
});


Route::group(['prefix' => 'loans', 'as' => 'loans.'], function () {

    Route::post('showGuarantors', [LoanController::class, 'showGuarantors'])->name('showGuarantors');
    Route::post('acceptGuarantor', [LoanController::class, 'acceptGuarantor'])->name('acceptGuarantor');
    Route::post('showAdmin', [LoanController::class, 'showAdmin'])->name('showAdmin');
    Route::post('show', [LoanController::class, 'show'])->name('show');
    Route::post('acceptAdmin', [LoanController::class, 'acceptAdmin'])->name('acceptAdmin');
    Route::post('store', [LoanController::class, 'store'])->name('create');
    Route::post('update', [LoanController::class, 'update'])->name('update');

});

Route::group(['prefix' => 'installments', 'as' => 'installments.'], function () {

    Route::post('show', [InstallmentController::class, 'show'])->name('show');
    Route::post('pay', [InstallmentController::class, 'pay'])->name('pay');
    Route::post('adminAccept', [InstallmentController::class, 'adminAccept'])->name('adminAccept');
    Route::post('showAdmin', [InstallmentController::class, 'showAdmin'])->name('showAdmin');
    Route::post('showPyament', [InstallmentController::class, 'showPayment'])->name('showPayment');

});

//users route
    Route::prefix('users/')->as('users.')->group(function () {
    Route::get('index/{id?}', [UserController::class, 'index'])->name('index');
    Route::post('create', [UserController::class, 'store'])->name('create');
    Route::put('edit/{id}', [AuthController::class, 'updateprofile'])->name('edit');
    Route::delete('delete/{id}', [UserController::class, 'delete'])->name('delete');
});

//auth routs
Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {

    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('verification/send', [AuthController::class, 'verificationSend'])->name('verification.send');
    Route::post('verification/check', [AuthController::class, 'verificationCheck'])->name('verification.check');
    Route::post('login/admin', [AuthController::class, 'loginAdmin'])->name('login.admin');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('me', [AuthController::class, 'me'])->name('me');

});
