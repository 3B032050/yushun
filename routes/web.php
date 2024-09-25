<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('index');
});
//Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('index');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home.index');
Auth::routes();

Route::group(['middleware' => 'master'], function() {
    Route::prefix('masters')->name('masters.')->group(function () {

        Route::get('/login', [\App\Http\Controllers\Auth\MasterLoginController::class, 'showLoginForm'])->name('masters_login');
        Route::post('/login', [\App\Http\Controllers\Auth\MasterLoginController::class, 'login']);


        Route::get('/register', [\App\Http\Controllers\Auth\MasterRegisterController::class, 'showRegistrationForm'])->name('masters_register');
        Route::post('/register', [\App\Http\Controllers\Auth\MasterRegisterController::class, 'register']);
    });
});


Route::group(['middleware' => 'auth'], function() {
    Route::get('/', [App\Http\Controllers\UserController::class, 'index'])->name('index');
    Route::get('/edit', [App\Http\Controllers\UserController::class, 'edit'])->name("users.edit");
    Route::patch('{user}/update', [App\Http\Controllers\UserController::class, 'update'])->name('users.update');

});
