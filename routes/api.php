<?php

use App\Http\Controllers\API\ApplicationStatusApi;
use App\Http\Controllers\API\Authentication;
use App\Http\Controllers\API\ChangePasswordApi;
use App\Http\Controllers\API\ContactApi;
use App\Http\Controllers\API\CountriesApi;
use App\Http\Controllers\API\CustomerRequestApi;
use App\Http\Controllers\API\DelegateControllerApi;
use App\Http\Controllers\API\FavoritesApi;
use App\Http\Controllers\API\InvoicesApi;
use App\Http\Controllers\API\OffersApi;
use App\Http\Controllers\API\OnBoardingScreenApi;
use App\Http\Controllers\API\PackagesApi;
use App\Http\Controllers\API\SectionsApi;
use App\Http\Controllers\API\StoresApi;
use App\Http\Controllers\API\UserApi;
use App\Http\Controllers\API\UserSubscription;
use App\Http\Controllers\API\VerifyTokenApi;
use App\Http\Controllers\API\VerifyTokenChangePasswordApi;
use App\Http\Controllers\API\VerifyTokenRegisterApi;
use App\Http\Controllers\StoreController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// public routes

//auth
Route::post('/register', [Authentication::class, 'register']);
Route::post('/verify/token', [VerifyTokenRegisterApi::class, 'verifyToken']);

Route::post('/login', [Authentication::class, 'login']);

//packages
Route::get('/all/packages', [PackagesApi::class, 'packages']);

//sections
Route::get('/all/sections', [SectionsApi::class, 'sections']);

//stores
Route::get('/all/stores', [StoresApi::class, 'stores']);
Route::get('/all/stores/with/offers', [StoresApi::class, 'storesWithOffers']);
Route::get('/all/stores/with/offers', [StoresApi::class, 'storesWithOffers']);
Route::post('/specific/store', [StoresApi::class, 'store']);
Route::get('/most/popular/stores', [StoresApi::class, 'mostPopularStores']);
Route::post('/filter/stores', [StoresApi::class, 'filterStores']);


//offers
Route::get('/all/offers', [OffersApi::class, 'offers']);
Route::post('/specific/offer', [OffersApi::class, 'offer']);
Route::post('/products/of/offer', [OffersApi::class, 'offerProducts']);


//contact
Route::post('/contact', [ContactApi::class, 'userMessage']);

//countries
Route::get('/countries', [CountriesApi::class, 'countries']);

//app status
Route::get('/application/status', [ApplicationStatusApi::class, 'appStatus']);

//onBoarding screen
Route::get('/onBoaring/screen', [OnBoardingScreenApi::class, 'onBoardings']);





Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// private routes
Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::post('/logout', [Authentication::class, 'logout']);
    Route::post('/change/password', [Authentication::class, 'changePassword']);
    Route::post('/verify/change/password/token', [VerifyTokenChangePasswordApi::class, 'verifyToken']);
    Route::post('/change/password/api', [ChangePasswordApi::class, 'changePassword']);

    // subscriptions
    Route::post('/user/package/subscription', [UserSubscription::class, 'packageUserSupscriptionMonthly']);
    Route::post('/user/free/subscription', [UserSubscription::class, 'freeSubscription']);
    Route::get('/user/subscription/details', [UserSubscription::class, 'currentSubscriptionDetails']);

    //profile
    Route::get('/user/profile', [UserApi::class, 'profile']);

    //offers
    Route::post('/update/offer/discount', [OffersApi::class, 'updateOfferDiscount']);

    //invoices
    Route::get('/user/purchases', [InvoicesApi::class, 'myPurchases']);

    //favorites
    Route::get('/user/favorite/offers', [FavoritesApi::class, 'userFavoriteOffers']);
    Route::get('/user/favorite/stores', [FavoritesApi::class, 'userFavoriteStores']);


    //product requests
    Route::post('/request/product', [CustomerRequestApi::class, 'requestProduct']);
    Route::get('/unconfirmed/product/requests', [CustomerRequestApi::class, 'unconfirmedPurchaseRequests']);

    Route::get('store/discount/{store}', [StoreController::class, 'showDiscount'])->name('store.discount');

    Route::prefix('/delegate')->middleware(['auth', 'is_delegate'])->group(function () {
        /**
         * API route for creating different types of sellers.
         **/
        Route::post('/seller/store', [DelegateControllerApi::class, 'store']);
        Route::get('/seller/{sellerId}/edit', [DelegateControllerApi::class, 'edit']);
        Route::delete('/seller/{sellerId}', [DelegateControllerApi::class, 'destroy']);
        Route::put('/seller/update', [DelegateControllerApi::class, 'update']);
        Route::get('/sellers', [DelegateControllerApi::class, 'getRelatedSellers']);
    });
});
