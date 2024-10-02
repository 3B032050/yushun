<?php

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

Route::prefix('masters')->name('masters.')->middleware('master')->group(function () {
    Route::prefix('masters')->name('masters.')->group(function () {
        Route::get('/index', [App\Http\Controllers\MasterController::class, 'index'])->name('index');
        Route::get('/personal_information/edit', [App\Http\Controllers\MasterController::class, 'edit'])->name("personal_information.edit");
        Route::patch('/personal_information/{user}/update', [App\Http\Controllers\MasterController::class, 'update'])->name('personal_information.update');
    });

    Route::prefix('masters')->name('admins.')->group(function () {
        Route::get('/equipment/index', [App\Http\Controllers\AdminEquipmentController::class, 'index'])->name('equipment.index');
    });

});


Route::group(['middleware' => 'auth'], function() {
    Route::get('users/index', [App\Http\Controllers\UserController::class, 'index'])->name('users.index');
    Route::get('/personal_information/edit', [App\Http\Controllers\UserController::class, 'edit'])->name("users.personal_information.edit");
    Route::patch('/personal_information/{user}/update', [App\Http\Controllers\UserController::class, 'update'])->name('users.personal_information.update');

});
