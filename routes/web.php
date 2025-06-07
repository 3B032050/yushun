<?php

use App\Http\Controllers\AdminServiceAreaController;
use App\Http\Controllers\Auth\MasterVerificationController;
use App\Http\Controllers\MastersAppointmentTimeController;
use App\Http\Controllers\MasterServiceAreaController;
use App\Http\Controllers\ScheduleRecordController;
use Illuminate\Support\Facades\Auth;
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
Route::get('/index', [App\Http\Controllers\HomeController::class, 'index'])->name('index');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('index');

Route::get('login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

Route::get('register', [App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [App\Http\Controllers\Auth\RegisterController::class, 'register']);

//google第三方登入
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);
Auth::routes(['verify' => true]);
Route::get('auth/masters/login', [\App\Http\Controllers\Auth\MasterLoginController::class, 'showLoginForm'])->name('masters_login');
Route::post('auth/masters/login', [\App\Http\Controllers\Auth\MasterLoginController::class, 'login']);
Route::post('masters/logout', [\App\Http\Controllers\Auth\MasterLoginController::class, 'logout'])->name('masters_logout');
Route::get('masters/register', [\App\Http\Controllers\Auth\MasterRegisterController::class, 'showRegistrationForm'])->name('masters_register');
Route::post('masters/register', [\App\Http\Controllers\Auth\MasterRegisterController::class, 'register']);


Route::get('masters/email/verify/{id}/{hash}', [MasterVerificationController::class, 'verify'])
    ->middleware(['signed', 'web']) // 注意這邊加 web 是確保 session 可用
    ->name('masters.verification.verify');


Route::middleware(['auth:master'])->group(function () {
    Route::get('masters/email/verify', [MasterVerificationController::class, 'notice'])->name('masters.verification.notice');
    Route::post('masters/email/resend', [MasterVerificationController::class, 'resend'])->name('masters.verification.send');
});
Route::group(['middleware' => ['auth:master', 'verified']], function() {
    Route::prefix('masters')->name('masters.')->group(function () {
        Route::get('/index', [App\Http\Controllers\MasterController::class, 'index'])->name('index');
        Route::get('/personal_information/index', [App\Http\Controllers\MasterPersonalInformationController::class, 'index'])->name("personal_information.index");
        Route::get('/personal_information/edit', [App\Http\Controllers\MasterPersonalInformationController::class, 'edit'])->name("personal_information.edit");
        Route::patch('/personal_information/update/{hashedMasterId}', [App\Http\Controllers\MasterPersonalInformationController::class, 'update'])->name('personal_information.update');

        //可服務地區
        Route::get('/service_areas/testSession', [MasterServiceAreaController::class, 'testSession'])->name('service_areas.testSession');
        Route::get('/service_areas/index', [MasterServiceAreaController::class, 'index'])->name('service_areas.index');
        Route::get('/service_areas/create', [MasterServiceAreaController::class, 'create'])->name('service_areas.create');
        Route::post('/service_areas', [MasterServiceAreaController::class, 'store'])->name('service_areas.store');
       // Route::delete('/service_areas/{hashedId}/destroy', [MasterServiceAreaController::class, 'destroy'])->name("service_areas.destroy");

        Route::get('/service_areas/create_item', [MasterServiceAreaController::class, 'create_item'])->name('service_areas.create_item');
        Route::post('/service_areas/storeServiceSelection', [MasterServiceAreaController::class, 'storeServiceSelection'])->name('service_areas.storeServiceSelection');

        //可預約時段
        Route::get('/appointmenttime/index', [\App\Http\Controllers\MastersAppointmentTimeController::class, 'index'])->name('appointmenttime.index');
        Route::get('/appointmenttime/create', [MastersAppointmentTimeController::class, 'create'])->name('appointmenttime.create');
        Route::post('/appointmenttime', [MastersAppointmentTimeController::class, 'store'])->name('appointmenttime.store');
        Route::get('/appointmenttime/edit/{hash_appointmenttime}', [App\Http\Controllers\MastersAppointmentTimeController::class, 'edit'])->name('appointmenttime.edit');
        Route::patch('/appointmenttime/{hash_appointmenttime}/update', [App\Http\Controllers\MastersAppointmentTimeController::class, 'update'])->name('appointmenttime.update');
        Route::delete('/appointmenttime/{hash_appointmenttime}/destroy', [MastersAppointmentTimeController::class, 'destroy'])->name("appointmenttime.destroy");
        Route::post('/appointmenttime/copy', [MastersAppointmentTimeController::class, 'copy'])->name('appointmenttime.copy');

        //租借制服
//        Route::get('/rent_uniforms/index', [\App\Http\Controllers\MasterRentUniformController::class, 'index'])->name('rent_uniforms.index');
//        Route::get('/rent_uniforms/create/{uniform}', [\App\Http\Controllers\MasterRentUniformController::class, 'create'])->name('rent_uniforms.create');
        Route::post('/rent_uniforms/store', [\App\Http\Controllers\MasterRentUniformController::class, 'store'])->name('rent_uniforms.store');
//        Route::get('/rent_uniforms/history', [\App\Http\Controllers\MasterRentUniformController::class, 'history'])->name('rent_uniforms.history');
//        Route::post('/rentals/{rental}/return', [\App\Http\Controllers\MasterRentUniformController::class, 'return'])->name('rent_uniforms.return');

        //借用設備
        Route::get('borrow_equipments/{hash_appointmenttime}/create', [\App\Http\Controllers\BorrowingRecordController::class, 'create'])->name('borrow_equipments.create');
        Route::post('borrow_equipments/{hash_appointmenttime}', [\App\Http\Controllers\BorrowingRecordController::class, 'store'])->name('borrow_equipments.store');

        Route::get('schedule_details/{hash_appointmenttime}/create', [\App\Http\Controllers\ScheduleDetailController::class, 'create'])->name('schedule_details.create');

        Route::post('schedule_details/{hash_appointmenttime}/store', [\App\Http\Controllers\ScheduleDetailController::class, 'store'])->name('schedule_details.store');
    });
    Route::prefix('admins')->name('admins.')->group(function () {
        //設備管理
        Route::get('/equipment/index', [App\Http\Controllers\AdminEquipmentController::class, 'index'])->name('equipment.index');
        Route::get('/equipment/create',[App\Http\Controllers\AdminEquipmentController::class,'create'])->name('equipment.create');
        Route::post('/equipment/store', [App\Http\Controllers\AdminEquipmentController::class, 'store'])->name("equipment.store");
        Route::get('/equipment/edit/{hash_equipment}', [App\Http\Controllers\AdminEquipmentController::class, 'edit'])->name('equipment.edit');
        Route::patch('/equipment/update/{hash_equipment}', [App\Http\Controllers\AdminEquipmentController::class, 'update'])->name('equipment.update');
        Route::delete('/equipment/destroy/{hash_equipment}', [App\Http\Controllers\AdminEquipmentController::class, 'destroy'])->name('equipment.destroy');

        //服務地區
        Route::get('service_areas/index', [AdminServiceAreaController::class, 'index'])->name('service_areas.index');
        Route::get('service_areas/create', [AdminServiceAreaController::class, 'create'])->name('service_areas.create');
        Route::post('service_areas', [AdminServiceAreaController::class, 'store'])->name('service_areas.store');
        Route::get('/service_areas/{hash_service_area}/edit', [AdminServiceAreaController::class, 'edit'])->name("service_areas.edit");
        Route::patch('/service_areas/{hash_service_area}/update',[AdminServiceAreaController::class,'update'])->name('service_areas.update');
        Route::delete('/service_areas/{hash_service_area}/destroy', [AdminServiceAreaController::class, 'destroy'])->name("service_areas.destroy");

        //服務項目
        Route::get('/service_items/index', [App\Http\Controllers\AdminServiceItemController::class, 'index'])->name('service_items.index');
        Route::get('/service_items/create',[App\Http\Controllers\AdminServiceItemController::class,'create'])->name('service_items.create');
        Route::post('/service_items/store', [App\Http\Controllers\AdminServiceItemController::class, 'store'])->name("service_items.store");
        Route::get('/service_items/edit/{hash_service_item}', [App\Http\Controllers\AdminServiceItemController::class, 'edit'])->name('service_items.edit');
        Route::patch('/service_items/update/{hash_service_item}', [App\Http\Controllers\AdminServiceItemController::class, 'update'])->name('service_items.update');
        Route::delete('/service_items/destroy/{hash_service_item}', [App\Http\Controllers\AdminServiceItemController::class, 'destroy'])->name('service_items.destroy');

        //制服管理
        Route::get('/uniforms/index', [App\Http\Controllers\AdminUniformController::class, 'index'])->name('uniforms.index');
        Route::get('/uniforms/create',[App\Http\Controllers\AdminUniformController::class,'create'])->name('uniforms.create');
        Route::post('/uniforms/store', [App\Http\Controllers\AdminUniformController::class, 'store'])->name("uniforms.store");
        Route::get('/uniforms/edit/{hash_uniform}', [App\Http\Controllers\AdminUniformController::class, 'edit'])->name('uniforms.edit');
        Route::patch('/uniforms/update/{hash_uniform}', [App\Http\Controllers\AdminUniformController::class, 'update'])->name('uniforms.update');
        Route::delete('/uniforms/destroy/{hash_uniform}', [App\Http\Controllers\AdminUniformController::class, 'destroy'])->name('uniforms.destroy');

        //師傅管理
        Route::get('/masters/index', [App\Http\Controllers\AdminMasterController::class, 'index'])->name('masters.index');
        Route::get('/masters/create',[App\Http\Controllers\AdminMasterController::class,'create'])->name('masters.create');
        Route::post('/masters/store', [App\Http\Controllers\AdminMasterController::class, 'store'])->name("masters.store");
        Route::get('/masters/edit/{hash_master}', [App\Http\Controllers\AdminMasterController::class, 'edit'])->name('masters.edit');
        Route::patch('/masters/update/{hash_master}', [App\Http\Controllers\AdminMasterController::class, 'update'])->name('masters.update');
        Route::delete('/masters/destroy/{hash_master}', [App\Http\Controllers\AdminMasterController::class, 'destroy'])->name('masters.destroy');

        Route::get('schedules/index', [App\Http\Controllers\AdminScheduleController::class, 'index'])->name('schedules.index');
        Route::get('schedules/getScheduleData', [App\Http\Controllers\AdminScheduleController::class, 'getScheduleData'])->name('schedules.getScheduleData');
    });
});



    Route::group(['middleware' => ['auth', 'verified']], function() {
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('index', [App\Http\Controllers\UserController::class, 'index'])->name('index');
            Route::get('schedule/index', [App\Http\Controllers\ScheduleRecordController::class, 'index'])->name('schedule.index');
            Route::get('schedule/available_masters', [App\Http\Controllers\ScheduleRecordController::class, 'available_masters'])->name('schedule.available_masters');
            Route::get('schedule/available_times', [App\Http\Controllers\ScheduleRecordController::class, 'available_times'])->name('schedule.available_times');
            Route::get('schedule/getServicePrice', [App\Http\Controllers\ScheduleRecordController::class, 'getServicePrice'])->name('schedule.getServicePrice');
            Route::get('schedule/getTotalPrice', [App\Http\Controllers\ScheduleRecordController::class, 'getTotalPrice'])->name('schedule.getTotalPrice');
            Route::post('/schedule/copy', [ScheduleRecordController::class, 'copy'])->name('schedule.copy');

            Route::get('schedule/check', [App\Http\Controllers\ScheduleRecordController::class, 'check'])->name('schedule.check');
            Route::get('schedule/create', [App\Http\Controllers\ScheduleRecordController::class, 'create'])->name('schedule.create');
            Route::post('schedule/store', [App\Http\Controllers\ScheduleRecordController::class, 'store'])->name("schedule.store");
            Route::get('personal_information', [App\Http\Controllers\UserController::class, 'personal_index'])->name('personal_information.personal_index');
            Route::get('personal_information/{hash_user}/edit', [App\Http\Controllers\UserController::class, 'edit'])->name('personal_information.edit');
            Route::patch('personal_information/{hash_user}/update', [App\Http\Controllers\UserController::class, 'update'])->name('personal_information.update');
            Route::post('schedule_details/review', [App\Http\Controllers\ScheduleDetailController::class, 'review'])->name('schedule_details.review');
        });
    });

