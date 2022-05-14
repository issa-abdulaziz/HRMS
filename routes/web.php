<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
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
      Route::PUT('/{setting}', 'update')->name('setting.update');
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

    Route::get('employee/{employee}/getData', [EmployeesController::class,'getData'])->name('employee.getData');
    Route::get('advanced-payment/{employee}/getData', [AdvancedPaymentController::class, 'getData'])->name('advanced-payment.getData');
    Route::post('vacation/getData', [VacationController::class,'getData']);
    Route::get('salary', [SalaryController::class,'index'])->name('salary.index');

    route::resources([
        'employee' => EmployeesController::class,
      'overtime'=> OvertimesController::class,
      'advanced-payment'=> AdvancedPaymentController::class,
      'vacation'=> VacationController::class,
      'shift' => ShiftsController::class,
    ]);
});

Route::fallback(function () {
  return 'This page does not exist';
});
