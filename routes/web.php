<?php

use App\Http\Controllers\Dashboard\ChatController;
use App\Http\Controllers\Dashboard\SettingController;
use Illuminate\Support\Facades\Route;

Route::get('/language/{lang}', [SettingController::class, 'changeLanguage'])->name('change-language');
Route::get('/', function () {
    return redirect('/dashboard');
})->name('index');
Route::group(['namespace' => 'Auth' , 'middleware' => 'set_locale'] , function () {
    Route::get('/', function () {
        return redirect('/dashboard');
    })->name('index');
    // employee login routes
    Route::get('employee/login','EmployeeAuthController@showLoginForm')->name('employee.login-form');
    Route::post('employee/login','EmployeeAuthController@login')->name('employee.login');
    Route::post('employee/logout','EmployeeAuthController@logout')->name('employee.logout');

    // user login routes
    Route::get('employee/login','EmployeeAuthController@showLoginForm')->name('employee.login-form');
});

Route::group(['namespace' => 'Home' , 'middleware' => 'set_locale', 'as' => 'home.'] , function () {
    Route::get('/calculator','CalculatorController@index')->name('calculator');
    Route::post('/amount-calculator','CalculatorController@calculate')->name('amount-calculator');
    Route::post('/calculate-installments','CalculatorController@calculateInstallmentss')->name('calculateInstallments');
    Route::post('/individuals-finance','OrderController@individualsFinance')->name('individualsFinance');
});
