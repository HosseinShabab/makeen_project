<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\InstallmentController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\TicketController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RolePermissionController;

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


Route::group(['prefix' => 'loans', 'as' => 'loans.' , 'middleware'=> 'auth:sanctum'], function () {

    Route::post('show/guarantors', [LoanController::class, 'showGuarantors'])->name('showGuarantors');
    Route::post('show/admin', [LoanController::class, 'showAdmin'])->name('showAdmin');
    Route::post('accept/admin', [LoanController::class, 'acceptAdmin'])->name('acceptAdmin');
    Route::post('accept/guarantor', [LoanController::class, 'acceptGuarantor'])->name('acceptGuarantor');
    Route::post('show', [LoanController::class, 'show'])->name('show');
    Route::post('store', [LoanController::class, 'store'])->name('create');
    Route::post('update', [LoanController::class, 'updateGuarantor'])->name('update');
});

Route::group(['prefix' => 'installments', 'as' => 'installments.','middleware'=>'auth:sanctum'], function () {

    Route::post('show', [InstallmentController::class, 'show'])->name('show');
    Route::post('pay', [InstallmentController::class, 'pay'])->name('pay');
    Route::post('admin/accept', [InstallmentController::class, 'adminAccept'])->name('adminAccept');
    Route::post('show/admin', [InstallmentController::class, 'showAdmin'])->name('showAdmin');
    Route::post('show/pyament', [InstallmentController::class, 'showPayment'])->name('showPayment');
    Route::post('show/sub', [InstallmentController::class, 'showSubscription'])->name('showSub');
});

//users route
Route::prefix('users/')->as('users.')->middleware('auth:sanctum')->group(function () {
    Route::put('index/{id?}', [UserController::class, 'index'])->middleware("permission:user.index")->name('index');
    Route::post('create', [UserController::class, 'store'])->middleware("permission:user.create")->name('create');
    Route::post('edit', [UserController::class, 'update'])->middleware("permission:user.update")->name('edit');
    Route::post('delete', [UserController::class, 'delete'])->middleware('permission:user.delete')->name('delete');
    Route::post('deactive', [UserController::class, 'ban'])->middleware('permission:user.deactive')->name('ban');
});

//auth routs
Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
    Route::post('login/admin', [AuthController::class, 'loginAdmin'])->name('login.admin');
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('edit', [AuthController::class, 'updateprofile'])->middleware(['auth:sanctum','permission:update.profile'])->name('edit');
    Route::post('me', [AuthController::class, 'me'])->middleware('auth:sanctum')->name('me');
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum')->name('logout');
});

//media controller
Route::group(['prefix' => 'media', 'as' => 'media.', 'middleware' => 'auth:sanctum'], function () {
    Route::post('show', [MediaController::class, 'index'])->middleware('auth:sanctum')->name('index');
    Route::post('create', [MediaController::class, 'store'])->middleware('auth:sanctum')->name('create');
    Route::post('delete', [MediaController::class, 'delete'])->middleware("auth:sanctum")->name('delete');
});
