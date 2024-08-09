<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FactorController;
use App\Http\Controllers\InstallmentController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\SettingController;
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
});


//Route Messages
Route::group(['prefix' => 'messages', 'as' => 'messages.','middleware'=> 'auth:sanctum'], function () {
    Route::get('index', [MessageController::class, 'index'])->middleware("permission:message.index")->name('index');
    Route::post('create', [MessageController::class, 'store'])->middleware("permission:message.create")->name('create');
    Route::post('create/admin', [MessageController::class, 'storeAdmin'])->middleware("permission:message.create")->name('create.admin');
    Route::get('show/{type}', [MessageController::class, 'show'])->name('show');
    Route::get('unreadmessage', [MessageController::class, 'unreadmessage'])->name('unreadmessage');

});


Route::group(['prefix' => 'loans', 'as' => 'loans.', 'middleware' => 'auth:sanctum'], function () {

    Route::post('show/guarantors', [LoanController::class, 'showGuarantors'])->name('showGuarantors');
    Route::post('show/admin', [LoanController::class, 'showAdmin'])->name('showAdmin');
    Route::post('accept/admin', [LoanController::class, 'acceptAdmin'])->name('acceptAdmin');
    Route::post('accept/guarantor', [LoanController::class, 'acceptGuarantor'])->name('acceptGuarantor');
    Route::post('show', [LoanController::class, 'show'])->name('show');
    Route::post('store', [LoanController::class, 'store'])->name('create');
    Route::post('update', [LoanController::class, 'updateGuarantor'])->name('update');
});

Route::group(['prefix' => 'installments', 'as' => 'installments.', 'middleware' => 'auth:sanctum'], function () {

    Route::post('show', [InstallmentController::class, 'show'])->name('show');
    Route::put('show/admin/{id?}', [InstallmentController::class, 'showAdmin'])->name('showAdmin');
});

//users route
Route::prefix('users')->as('users.')->middleware('auth:sanctum')->group(function () {
    Route::get('memberCnt', [UserController::class, 'MemberCnt'])->name('MemberCnt');
    Route::put('index/{id?}', [UserController::class, 'index'])->middleware("permission:user.index")->name('index');
    Route::post('create', [UserController::class, 'store'])->middleware("permission:user.create")->name('create');
    Route::post('edit', [UserController::class, 'update'])->middleware("permission:user.update")->name('edit');
    Route::post('delete', [UserController::class, 'delete'])->middleware('permission:user.delete')->name('delete');
    Route::post('deactive', [UserController::class, 'deactive'])->middleware('permission:user.deactive')->name('ban');
});

//auth routs
Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
    Route::post('login/admin', [AuthController::class, 'loginAdmin'])->name('login.admin');
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('edit', [AuthController::class, 'updateprofile'])->middleware(['auth:sanctum', 'permission:update.profile'])->name('edit');
    Route::post('me', [AuthController::class, 'me'])->middleware('auth:sanctum')->name('me');
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum')->name('logout');
});

// factor controller
Route::group(['prefix' => 'factors', 'as' => 'factors.', 'middleware' => 'auth:sanctum'], function () {
    Route::get('factorCnt', [FactorController::class, 'factorCnt'])->name('factorCnt');
    Route::put('index/{id?}', [FactorController::class, 'index'])->name('index');
    Route::post('store', [FactorController::class, 'store'])->name('create');
    Route::post('accept', [FactorController::class, 'accept'])->name('accept');
    Route::post('update', [FactorController::class, 'update'])->name('edit');
});

//media controller
Route::group(['prefix' => 'media', 'as' => 'media.', 'middleware' => 'auth:sanctum'], function () {
    Route::post('show', [MediaController::class, 'show'])->middleware('auth:sanctum')->name('index');
    Route::post('create', [MediaController::class, 'store'])->middleware('auth:sanctum')->name('create');
    Route::post('delete', [MediaController::class, 'delete'])->middleware("auth:sanctum")->name('delete');
});

Route::prefix('settings/')->as('settings.')->middleware('auth:sanctum')->group(function () {
    Route::post('create', [SettingController::class, 'store'])->middleware('permission:setting.create')->name('create');
    Route::get('index', [SettingController::class, 'index'])->middleware('permission:setting.index')->name('index');
    Route::post('addmedia', [SettingController::class, 'addmedia'])->middleware('permission:addmedia')->name('addmedia');
    Route::post('addmedia', [SettingController::class, 'addmedia'])->middleware('permission:addmedia')->name('addmedia');
    Route::get('removemedia', [SettingController::class, 'removemedia'])->middleware('permission:removemedia')->name('removemedia');
    Route::post('edit', [SettingController::class, 'update'])->middleware('permission:setting.update')->name('edit');
});
