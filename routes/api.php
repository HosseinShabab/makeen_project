<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FactorController;
use App\Http\Controllers\InstallmentController;
use App\Http\Controllers\InventoryController;
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
    Route::get('index/{id?}', [MessageController::class, 'index'])->middleware("permission:message.index|active")->name('index');
    Route::post('create', [MessageController::class, 'store'])->middleware("permission:message.create|active")->name('create');
    Route::post('create/admin', [MessageController::class, 'storeAdmin'])->middleware("permission:message.createAdmin|active")->name('create.admin');
    Route::get('show/{type}', [MessageController::class, 'show'])->middleware('permission:message.show|active')->name('show');
    Route::get('unreadmessage', [MessageController::class, 'unreadmessage'])->middleware('permission:message.unread|active')->name('unreadmessage');

});


Route::group(['prefix' => 'loans', 'as' => 'loans.', 'middleware' => 'auth:sanctum'], function () {

    Route::post('show/guarantors', [LoanController::class, 'showGuarantors'])->middleware("permission:loan.showGuarantors|active")->name('showGuarantors');
    Route::get('requestCnt', [LoanController::class, 'requestCnt'])->name('requestCnt');
    Route::post('show/admin', [LoanController::class, 'showAdmin'])->middleware("permission:loan.showAdmin|active")->name('showAdmin');
    Route::post('accept/admin', [LoanController::class, 'acceptAdmin'])->middleware("permission:loan.acceptAdmin|active")->name('acceptAdmin');
    Route::post('accept/guarantor', [LoanController::class, 'acceptGuarantor'])->middleware("permission:loan.acceptGuarantor|active")->name('acceptGuarantor');
    Route::post('show', [LoanController::class, 'show'])->middleware("permission:loan.show")->name('show');
    Route::post('store', [LoanController::class, 'store'])->middleware("permission:loan.create|active")->name('create');
    Route::post('update', [LoanController::class, 'updateGuarantor'])->middleware("permission:loan.updateGuarantor|active")->name('update');
    Route::get('loanDetails',[LoanController::class,'loanDetails'])->name('loand.details');
});

Route::group(['prefix' => 'installments', 'as' => 'installments.', 'middleware' => 'auth:sanctum'], function () {
    Route::get('last', [InstallmentController::class, 'last'])->name('last');
    Route::get('show', [InstallmentController::class, 'show'])->name('show');
    Route::post('sum', [InstallmentController::class,'sum'])->name('sum');
    Route::get('show/admin/{id?}', [InstallmentController::class, 'showAdmin'])->name('showAdmin');
});

//users routee
Route::prefix('users')->as('users.')->middleware('auth:sanctum')->group(function () {
    Route::get('memberCnt', [UserController::class, 'MemberCnt'])->name('MemberCnt');
    Route::put('index/{id?}', [UserController::class, 'index'])->middleware("permission:user.index")->name('index');
    Route::post('create', [UserController::class, 'store'])->middleware("permission:user.create")->name('create');
    Route::post('edit', [UserController::class, 'update'])->middleware("permission:user.update|active")->name('edit');
    Route::get('delete/{id}', [UserController::class, 'delete'])->middleware('permission:user.delete')->name('delete');
    Route::get('active/{id}', [UserController::class, 'active'])->middleware('permission:user.delete')->name('active');
    Route::get('deactiveReq', [UserController::class, 'deactiveReq'])->name('deactiveReq');
    Route::get('deactiveShow', [UserController::class, 'deactiveShow'])->middleware('permission:user.deactive')->name('deactiveShow');
    Route::post('deactive', [UserController::class, 'deactive'])->middleware('permission:user.deactive')->name('deactive');
});

//auth routs
Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
    Route::post('login/admin', [AuthController::class, 'loginAdmin'])->name('login.admin');
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('forgetPass', [AuthController::class, 'forgetPassword'])->name('forgetPass');
    Route::post('edit', [AuthController::class, 'updateprofile'])->middleware(['auth:sanctum','permission:update.profile'])->name('edit');
    Route::post('me', [AuthController::class, 'me'])->middleware('auth:sanctum')->name('me');
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum')->name('logout');
});

// factor controller
Route::group(['prefix' => 'factors', 'as' => 'factors.', 'middleware' => 'auth:sanctum'], function () {
    Route::get('factorCnt', [FactorController::class, 'factorCnt'])->name('factorCnt');
    Route::get('index/{id?}', [FactorController::class, 'index'])->middleware('permission:factor.index')->name('index');
    Route::post('store', [FactorController::class, 'store'])->middleware('permission:factor.create')->name('create');
    Route::post('accept', [FactorController::class, 'accept'])->middleware('permission:factor.accept')->name('accept');
    Route::post('update', [FactorController::class, 'update'])->middleware('permission:factor.update')->name('edit');
});

//media controller
Route::group(['prefix' => 'media', 'as' => 'media.', 'middleware' => 'auth:sanctum'], function () {
    Route::post('show', [MediaController::class, 'show'])->middleware('permission:media.index')->name('index');
    Route::post('create', [MediaController::class, 'store'])->middleware('permission:media.create')->name('create');
    Route::post('delete', [MediaController::class, 'delete'])->middleware('permission:media.delete')->name('delete');
});

Route::prefix('settings/')->as('settings.')->middleware('auth:sanctum')->group(function () {
    Route::post('create', [SettingController::class, 'store'])->middleware('permission:setting.create')->name('create');
    Route::get('index', [SettingController::class, 'index'])->middleware('permission:setting.index')->name('index');
    Route::post('addmedia', [SettingController::class, 'addmedia'])->name('addmedia');
    Route::post('removemedia', [SettingController::class, 'removemedia'])->middleware('permission:removemedia')->name('removemedia');
    Route::post('edit', [SettingController::class, 'update'])->middleware('permission:setting.update')->name('edit');
});

Route::group(['prefix'=> 'inventory','as'=> 'inventory.','middleware'=> 'auth:sanctum'], function () {
    Route::get('index',[InventoryController::class,'index'])->middleware('permission:inventory.index')->name('index');
});
