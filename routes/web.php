<?php

use App\Http\Controllers\AdminServiceAreaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\GoogleController;
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

//Route::get('/', function () {
//    return view('index');
//});
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('index');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home.index');
Auth::routes();

//google第三方登入
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

Route::get('masters/login', [\App\Http\Controllers\Auth\MasterLoginController::class, 'showLoginForm'])->name('masters_login');
Route::post('masters/login', [\App\Http\Controllers\Auth\MasterLoginController::class, 'login']);
Route::post('masters/logout', [\App\Http\Controllers\Auth\MasterLoginController::class, 'logout'])->name('masters_logout');
Route::get('masters/register', [\App\Http\Controllers\Auth\MasterRegisterController::class, 'showRegistrationForm'])->name('masters_register');
Route::post('masters/register', [\App\Http\Controllers\Auth\MasterRegisterController::class, 'register']);

Route::group(['middleware' => 'master'], function() {
    Route::prefix('masters')->name('masters.')->group(function () {
        Route::get('/index', [App\Http\Controllers\MasterController::class, 'index'])->name('index');
        Route::get('/personal_information/edit', [App\Http\Controllers\MasterController::class, 'edit'])->name("personal_information.edit");
        Route::patch('/personal_information/{user}/update', [App\Http\Controllers\MasterController::class, 'update'])->name('personal_information.update');
    });

    Route::prefix('admins')->name('admins.')->group(function () {
        //設備管理
        Route::get('/equipment/index', [App\Http\Controllers\AdminEquipmentController::class, 'index'])->name('equipment.index');
        Route::get('/equipment/create',[App\Http\Controllers\AdminEquipmentController::class,'create'])->name('equipment.create');
        Route::post('/equipment/store', [App\Http\Controllers\AdminEquipmentController::class, 'store'])->name("equipment.store");
        Route::get('/equipment/edit/{equipment}', [App\Http\Controllers\AdminEquipmentController::class, 'edit'])->name('equipment.edit');
        Route::patch('/equipment/update/{equipment}', [App\Http\Controllers\AdminEquipmentController::class, 'update'])->name('equipment.update');
        Route::delete('/equipment/destroy/{equipment}', [App\Http\Controllers\AdminEquipmentController::class, 'destroy'])->name('equipment.destroy');

        //服務地區
        Route::get('service_areas/index', [AdminServiceAreaController::class, 'index'])->name('service_areas.index');
        Route::get('service_areas/create', [AdminServiceAreaController::class, 'create'])->name('service_areas.create');
        Route::post('service_areas', [AdminServiceAreaController::class, 'store'])->name('service_areas.store');
        Route::get('/service_areas/{service_area}/edit', [AdminServiceAreaController::class, 'edit'])->name("service_areas.edit");
        Route::patch('/service_areas/{service_area}/update',[AdminServiceAreaController::class,'update'])->name('service_areas.update');
        Route::patch('/service_areas/{service_areas}/destroy', [AdminServiceAreaController::class, 'destroy'])->name("service_areas.destroy");
    });
});


Route::group(['middleware' => 'auth'], function() {
    Route::get('users/index', [App\Http\Controllers\UserController::class, 'index'])->name('users.index');
    Route::get('/personal_information/edit', [App\Http\Controllers\UserController::class, 'edit'])->name("users.personal_information.edit");
    Route::patch('/personal_information/{user}/update', [App\Http\Controllers\UserController::class, 'update'])->name('users.personal_information.update');

});
