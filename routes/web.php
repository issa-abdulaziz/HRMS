<?php

use App\Http\Controllers\AdvancedPaymentController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegistrationController;
use App\Http\Controllers\Auth\ResetPassController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeesController;
use App\Http\Controllers\OvertimesController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ShiftsController;
use App\Http\Controllers\VacationController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
return to_route('login');
});


Route::get('/ForgetPassword',[ResetPassController::class,'index'])->name('auth.forget-pass');
Route::post('/ForgetPassword-action',[ResetPassController::class,'forgetPass'])->name('auth.forget-pass.action');

Route::get('/Reset-Password/{token}',[ResetPassController::class,'resetPassView'])->name('auth.reset-password');
Route::post('/Reset-Password-action',[ResetPassController::class,'resetPass'])->name('auth.reset-password.action');

Route::get('/Register',[RegistrationController::class,'index'])->name('auth.register');
Route::get('/Verify-Email/{token}',[RegistrationController::class,'verify'])->name('auth.register.verify');

Route::get('/Login',[LoginController::class,'index'])->name('login');

Route::post('/Register-action',[RegistrationController::class,'storeUser'])->name('auth.register.action');
Route::post('/login-action',[LoginController::class,'SignIn'])->name('auth.login.action');


Route::get('logout', function () {
   Auth::logout();

   return redirect()->route('login');
})->name('logout');

Route::middleware(['auth'])->group(function () {



    Route::controller(DashboardController::class)->group(function() {
        Route::get('/dashboard', 'index')->name('dashboard');
        Route::get('dashboard/getData', 'getData');
      });

      Route::controller(SettingsController::class)->prefix('setting')->group(function() {
        Route::get('/', 'index')->name('setting.index');
        Route::PUT('/{setting}', 'update')->name('setting.update');
      });

      Route::group(['controller' => OvertimesController::class, 'prefix' => 'overtime', 'as' => 'overtime.'], function() {
          Route::get('{employee}/getHourlyPrice', 'getHourlyPrice')->name('getHourlyPrice');
          Route::get('{date}/getRate', 'getRate')->name('getRate');
      });

      Route::group(['controller' => AttendanceController::class, 'prefix' => 'attendance', 'as' => 'attendance.'], function () {
          Route::get('/', 'index')->name('index');
          Route::get('check/{date}/{shift}', 'check')->name('check');
          Route::post('store', 'store')->name('store');
          Route::delete('/{date}', 'destroy')->name('destroy');
      });

      Route::get('employee/{employee}/getData', [EmployeesController::class,'getData'])->name('employee.getData');
      Route::get('advanced-payment/{employee}/getData', [AdvancedPaymentController::class, 'getData'])->name('advanced-payment.getData');
      Route::get('vacation/{employee}/getData', [VacationController::class,'getData'])->name('vacation.getData');
      Route::get('salary', [SalaryController::class,'index'])->name('salary.index');

      route::resources([
          'employee' => EmployeesController::class,
          'overtime'=> OvertimesController::class,
          'advanced-payment'=> AdvancedPaymentController::class,
          'vacation'=> VacationController::class,
          'shift' => ShiftsController::class,
      ]);
});


// ISSA

Route::fallback(function () {
  return 'This page does not exist';
});


