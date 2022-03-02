<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ShiftsController;
use App\Http\Controllers\OvertimesController;
use App\Http\Controllers\AdvancedPaymentController;
use App\Http\Controllers\EmployeesController;
use App\Http\Controllers\VacationController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\AttendanceController;


Auth::routes();

Route::middleware(['auth'])->group(function () {

    Route::controller(DashboardController::class)->group(function() {
      Route::get('/', 'index')->name('dashboard');
      Route::get('dashboard/getData', 'getData');
    });

    Route::controller(SettingsController::class)->prefix('setting')->group(function() {
      Route::get('/', 'index')->name('setting.index');
      Route::PUT('/{id}', 'update')->name('setting.update');
    });

    Route::controller(OvertimesController::class)->prefix('overtime')->group(function() {
      Route::post('/getHourlyPrice', 'getHourlyPrice');
      Route::post('/getRate', 'getRate');
    });

    Route::controller(AttendanceController::class)->group(function() {
      Route::prefix('attendance')->group(function() {
        Route::get('/', 'index')->name('attendance.index');
        Route::post('/check', 'check');
        Route::post('/store', 'store');
        Route::delete('/{date}', 'destroy');
      });
    });

    Route::post('/employee/getData', [EmployeesController::class,'getData']);
    Route::post('/advanced-payment/getData', [AdvancedPaymentController::class,'getData']);
    Route::post('/vacation/getData', [VacationController::class,'getData']);
    Route::get('/salary', [SalaryController::class,'index'])->name('salary.index');

    route::resources([
      'employee'=> EmployeesController::class,  
      'overtime'=> OvertimesController::class,
      'advanced-payment'=> AdvancedPaymentController::class,
      'vacation'=> VacationController::class,
      'shift' => ShiftsController::class,
      ]);  
});

Route::fallback(function () {
  return 'This page does not exist';
});

// Route::get('/', [DashboardController::class,'index']);
// Route::get('/dashboard', [DashboardController::class,'index']);
// Route::get('/dashboard/getData', [DashboardController::class,'getData']);
// Route::get('/setting', [SettingsController::class,'index']);
// Route::get('/setting/{id}', [SettingsController::class,'index']);
// Route::match(['put', 'patch'], '/setting/{id}',[SettingsController::class,'update']);

/*
Route::resource('setting', 'SettingsController', [
    'only' => ['index','show', 'edit' , 'update']
  ]);
*/
// Route::resource('shift', ShiftsController::class);
// Route::resource('employee', EmployeesController::class);
// Route::post('/employee/getData', [EmployeesController::class,'getData']);
// Route::resource('overtime', OvertimesController::class);
// Route::post('/overtime/getHourlyPrice', [OvertimesController::class,'getHourlyPrice']);
// Route::post('/overtime/getRate', [OvertimesController::class,'getRate']);

// Route::resource('advanced-payment', AdvancedPaymentController::class);
// Route::post('/advanced-payment/getData', [AdvancedPaymentController::class,'getData']);


// Route::get('/attendance', [AttendanceController::class,'index'])->name('attendance.index');
// Route::post('/attendance/check', [AttendanceController::class,'check']);
// Route::post('/attendance/store', [AttendanceController::class,'store']);
// Route::delete('/attendance/{date}', [AttendanceController::class,'destroy']);
// Route::get('/salary', [SalaryController::class,'index'])->name('salary.index');

// Route::resource('vacation', VacationController::class);
// Route::post('/vacation/getData', [VacationController::class,'getData']);
