<?php

use App\Http\Controllers\Mobile_Api\AdsController;
use App\Http\Controllers\Api\CarController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\citiyController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\GlobalController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\SplashController;
use App\Http\Controllers\Mobile_Api\UserController;
use App\Http\Controllers\Mobile_Api\MobileFinanceController as Mobile_ApiFinanceController;
use App\Http\Controllers\Mobile_Api\GlobalController as Mobile_ApiGlobalController;
use App\Http\Controllers\Mobile_Api\RequestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\SetLocale;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group(['middleware' => ['json.response']], function () {
    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/register', 'Api\Auth\AuthController@register');
    Route::post('/login', 'Api\Auth\AuthController@login');
    Route::post('/send-otp', 'Api\Auth\ForgetPasswordController@sendOtp');
    Route::post('/resend-otp', 'Api\Auth\ForgetPasswordController@resendOtp');
    Route::post('/reset-password', 'Api\Auth\ForgetPasswordController@resetPassword');
    Route::post('/verify-otp', 'Api\Auth\VerificationController@verifyOtp');
    Route::get('/act_mod', 'Api\UserController@act_mod');
    Route::post('/resend-otp-order', [Mobile_ApiFinanceController::class, 'resendOtp']);

    Route::group(['middleware' => 'auth:sanctum'], function () {
        // Route::post('/resend-otp', 'Api\Auth\VerificationController@resendOtp');
        Route::post('/logout', 'Api\Auth\AuthController@logout');
        Route::post('/add-Your-addss', [AdsController::class, 'store']);
        Route::delete('/addss/delete/{id}', 'Api\UserController@destroy');
        Route::get('/addss/edit/{id}', 'Api\UserController@edit');
        Route::get('/notification', 'Api\UserController@notification');
        Route::get('/changestatuenotificaton', 'Api\UserController@changestatue');

        Route::post('/addss/update/{id}', 'Api\UserController@update');

        Route::get('/profile', 'Api\UserController@profile');
        Route::post('/update-profile', 'Api\UserController@updateProfile');
        Route::post('/change-password', 'Api\UserController@changPassword');
        Route::get('/addss', [UserController::class,'ads']);
        Route::post('/update-status/{id}', 'Api\UserController@updateAdsShowInHomePage');
        Route::get('/filter-addss', 'Api\UserController@filter');
        Route::post('/add-favorite-withauth', 'Api\FavoriteController@store');
        Route::get('/requests_auth', [RequestController::class,'index'])->name('get-requests');
        Route::post('/finance-Order', [Mobile_ApiFinanceController::class, 'financeOrder'])->name('finance.order');
        Route::post('/finance-dash', [Mobile_ApiFinanceController::class, 'financeOrderDashboard'])->name('finance.orde');
        Route::post('/favorite-auth', 'Api\UserController@favorite');
    });
    Route::post('/favorite-withoutauth', 'Api\UserController@favorite');
    Route::post('/add-favorite-withoutauth', 'Api\FavoriteController@store');
    Route::post('/add-array-favorite', 'Api\FavoriteController@storeFavArray');

    // ------------------------- Home ---------------------------------------
    Route::get('/brand', 'Api\HomeController@brand');
    Route::get('/home', 'Api\HomeController@allhome');

    Route::get('/brands', 'Api\HomeController@brands');
    Route::get('/splash_screen', [SplashController::class, 'index']);

    Route::get('/brand/{id}', 'Api\HomeController@carsbrand');
    Route::get('/why-code-car', 'Api\HomeController@why_code_car');
    Route::get('/financing-advantage', 'Api\HomeController@financing_advantage');
    Route::get('/financing-body', 'Api\HomeController@financing_bodies');
    // ----------------------- Settings --------------------------------
    Route::get('/calc_data', 'Api\HomeController@act_mod');

    Route::get('/footer', 'Api\SettingController@footer');
    Route::get('/finance', 'Api\SettingController@finance');
    Route::get('/social', 'Api\SettingController@social');
    Route::get('/cars-news', 'Api\SettingController@cars_news');
    Route::get('/contact-us', 'Api\SettingController@contact_us');
    Route::get('/about', 'Api\SettingController@about');
    Route::get('/filter_count', 'Api\SettingController@filter_count');

    Route::get('/offer', 'Api\SettingController@offer');
    Route::get('/setting', 'Api\SettingController@setting');
    Route::get('/terms-condition-privacy', 'Api\SettingController@termsCondition');
    Route::get('/settings', 'Api\SettingController@setting');
    Route::get('/allsettings', 'Api\SettingController@AllDescription');
    Route::get('/cashing', 'Api\SettingController@cach');


    //  -------------------- Career -----------------------
    Route::group([
        'prefix' => 'career'
    ], function ($router) {
        Route::get('/', 'Api\CareerController@index');
        Route::post('/store/{career_id}', 'Api\CareerController@store');
    });
    // -------------------------- Subscriber ----------------------------
    Route::post('/subscriber/store', 'Api\SubscriberController@store');
    // -------------------------- News --------------------------------
    Route::get('/news/show/{id}', 'Api\NewsController@show');

    Route::post('/filter-cars', [CarController::class, 'filter_cars']);

    // -------------------------------------------------------

    Route::get('/careers', 'Api\CareerController@index');
    Route::get('/cars', [CarController::class, 'carsdetails']);

    Route::get('/car-option', [CarController::class, 'CarOption']);

    Route::get('/car/{id}', [CarController::class, 'cardetails']);

    Route::post('/contact', [ContactController::class, 'contact']);
    Route::get('/car-model', [CarController::class, 'carmodel']);

    // Route::post('/add-Your-Ad', [AdsController::class, 'store']);
    // Route::post('/add-Your-Ad/{step}', [AdsController::class, 'store']);

    // Route::post('/send-otp', [FinanceController::class, 'sendOtp']);
    Route::post('/verify-otp-order', [Mobile_ApiFinanceController::class, 'verifyOtp']);
    // Route::post('/cash-Order', [FinanceController::class, 'sendOtp']);
    Route::post('/cash-Order', [Mobile_ApiFinanceController::class, 'validationcash']);
    // Route::post('/financecar-Order', [Mobile_ApiFinanceController::class, 'validationfinance']);



    Route::get('/cities', [citiyController::class, 'index']);
    Route::get('/citiesData', [Mobile_ApiGlobalController::class, 'cityData']);

    Route::get('/banksData', [Mobile_ApiGlobalController::class, 'bankData']);
    Route::get('/colores', [Mobile_ApiGlobalController::class, 'colorsData']);
    Route::get('/organization_type', [Mobile_ApiGlobalController::class, 'organizationTypes']);
    Route::get('/Organization_active', [Mobile_ApiGlobalController::class, 'Organizationactive']);
    Route::get('/Sectors_data', [Mobile_ApiGlobalController::class, 'SectorsData']);
    Route::get('/Nationality', [Mobile_ApiGlobalController::class, 'Nationality']);
    Route::get('/Nationality', [Mobile_ApiGlobalController::class, 'Nationality']);
    Route::get('/advanced_search', [Mobile_ApiGlobalController::class, 'some_data'] );

    Route::get('/mobile_brands', [Mobile_ApiGlobalController::class, 'brands'] );
    Route::get('/get_model_by_brand/{id}', [Mobile_ApiGlobalController::class, 'get_model_by_brand']);
    Route::post('/cars', [Mobile_ApiGlobalController::class, 'carsdetails_fav']);

    // ----------------------- Offer ----------------------
    Route::get('/offer/show/{id}', 'Api\OfferController@show');
    // --------------------------------
    Route::get('/best-selling-cars', [CarController::class, 'BestSellingCars']);
    Route::get('/cars-biggest-discount', [CarController::class, 'carsWithBiggestDiscount']);
    Route::get('/current-year', [CarController::class, 'currentyear']);
    Route::get('/filter', [CarController::class, 'filter']);
    //  -------------------------------------------
    Route::post('/search', [SearchController::class, 'search']);
    Route::get('/categories', [CategoryController::class, 'categories']);
    Route::get('/car-type', [CarController::class, 'cartype']);


    Route::get('/calculator', 'Home\CalculatorController@index')->name('calculator');
    Route::post('/amount-calculator', 'Home\CalculatorController@calculate')->name('amount-calculator');
    Route::post('/calculate-installments', 'CalculatorController@calculateInstallmentss')->name('calculateInstallments');
    Route::post('/individuals-finance', 'OrderController@individualsFinance')->name('individualsFinance');


    // Route::get('/requests','Api\RequestController@index')->name('get-requests');
    Route::post('/requests-search', [RequestController::class,'search']);
    Route::post('/find', 'Api\financecalc@encry');

    Route::get('/requests', 'mobile_api\RequestController@index')->name('get-requests-without-auth');
    Route::post('/finance-Order', [Mobile_ApiFinanceController::class, 'financeOrder'])->name('finance.order2');
    Route::post('/finance-dash', [Mobile_ApiFinanceController::class, 'financeOrderDashboard'])->middleware(SetLocale::class);

});
