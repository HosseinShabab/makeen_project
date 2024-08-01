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
    Route::get('show/{id?}', [AuthController::class, 'show'])->name('show');
});
// Route Tickets
Route::group(['prefix' => 'tickets', 'as' => 'tickets.', 'middleware' => 'auth:sanctum'], function () {
    Route::get('index/{id?}', [TicketController::class, 'index'])->name('index');
    Route::post('create', [TicketController::class, 'store'])->name('create');
    Route::put('edit/{id}', [TicketController::class, 'update'])->name('edit');
    Route::delete('delete/{id}', [TicketController::class, 'delete'])->name('delete');
});

//Route Messages
Route::group(['prefix' => 'messages', 'as' => 'messages.'], function () {
    Route::get('index/{id?}', [MessageController::class, 'index'])->name('index');
    Route::post('create', [MessageController::class, 'store'])->name('create');
    Route::put('edit/{id}', [MessageController::class, 'update'])->name('edit');
    Route::delete('delete/{id}', [MessageController::class, 'delete'])->name('delete');
});


Route::group(['prefix' => 'loans', 'as' => 'loans.'], function () {

    Route::post('show/guarantors', [LoanController::class, 'showGuarantors'])->name('showGuarantors');
    Route::post('show/admin', [LoanController::class, 'showAdmin'])->name('showAdmin');
    Route::post('accept/admin', [LoanController::class, 'acceptAdmin'])->name('acceptAdmin');
    Route::post('accept/guarantor', [LoanController::class, 'acceptGuarantor'])->name('acceptGuarantor');
    Route::post('show', [LoanController::class, 'show'])->name('show');
    Route::post('store', [LoanController::class, 'store'])->name('create');
    Route::post('update', [LoanController::class, 'update'])->name('update');
});

Route::group(['prefix' => 'installments', 'as' => 'installments.'], function () {

    Route::post('show', [InstallmentController::class, 'show'])->name('show');
    Route::post('pay', [InstallmentController::class, 'pay'])->name('pay');
    Route::post('admin/accept', [InstallmentController::class, 'adminAccept'])->name('adminAccept');
    Route::post('show/admin', [InstallmentController::class, 'showAdmin'])->name('showAdmin');
    Route::post('show/pyament', [InstallmentController::class, 'showPayment'])->name('showPayment');
    Route::post('show/sub', [InstallmentController::class, 'showSubscription'])->name('showSub');
});

//users route
Route::prefix('users/')->as('users.')->group(function () {
    Route::put('index/{id?}', [UserController::class, 'index'])->name('index');
    Route::post('create', [UserController::class, 'store'])->name('create');
    Route::post('delete', [UserController::class, 'delete'])->name('delete');
    Route::post('deactive', [UserController::class, 'ban'])->name('ban');
});

//auth routs
Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {

    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('verification/send', [AuthController::class, 'verificationSend'])->name('verification.send');
    Route::post('verification/check', [AuthController::class, 'verificationCheck'])->name('verification.check');
    Route::post('edit', [AuthController::class, 'updateprofile'])->name('edit');
    Route::post('login/admin', [AuthController::class, 'loginAdmin'])->name('login.admin');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('me', [AuthController::class, 'me'])->name('me');
});
