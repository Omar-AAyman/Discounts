<?php

use App\Http\Controllers\API\ApplicationStatusApi;
use App\Http\Controllers\API\Authentication;
use App\Http\Controllers\API\CitiesApi;
use App\Http\Controllers\API\ForgetPasswordApi;
use App\Http\Controllers\API\ContactApi;
use App\Http\Controllers\API\CountriesApi;
use App\Http\Controllers\API\CustomerRequestApi;
use App\Http\Controllers\API\Delegate\DelegateControllerApi;
use App\Http\Controllers\API\FavoritesApi;
use App\Http\Controllers\API\InvoicesApi;
use App\Http\Controllers\API\NewsApi;
use App\Http\Controllers\API\OffersApi;
use App\Http\Controllers\API\OnBoardingScreenApi;
use App\Http\Controllers\API\PackagesApi;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\PushNotificationController;
use App\Http\Controllers\API\SectionsApi;
use App\Http\Controllers\API\Sellers\DiscountRequestController;
use App\Http\Controllers\API\Sellers\SellerController;
use App\Http\Controllers\API\StoresApi;
use App\Http\Controllers\API\SubscriptionController;
use App\Http\Controllers\API\UserApi;
use App\Http\Controllers\API\UserSettingsController;
use App\Http\Controllers\API\UserSubscription;
use App\Http\Controllers\API\VerifyTokenApi;
use App\Http\Controllers\API\VerifyTokenForgetPasswordApi;
use App\Http\Controllers\API\VerifyTokenRegisterApi;
use App\Http\Controllers\API\DiscountController;
use App\Http\Controllers\API\Sellers\DiscountController as SellerDiscountController;
use App\Http\Controllers\StoreController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Sellers\OfferController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TransactionController;
use App\Models\City;

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


Route::post('/testNotification', [InvoicesApi::class, 'TestSendPushNotification']);

//auth
Route::post('/register', [Authentication::class, 'register']);
Route::post('/verify/token', [VerifyTokenRegisterApi::class, 'verifyToken']);

Route::post('/login', [Authentication::class, 'login']);

// Password management routes
Route::post('/forget/password', [Authentication::class, 'forgetPassword']); // Request to forget password
Route::post('/verify/forget/password/token', [VerifyTokenForgetPasswordApi::class, 'verifyToken']); // Verify token for password reset
Route::post('/forget/password/api', [ForgetPasswordApi::class, 'forgetPassword']); // API call for forgetting password

//packages
Route::get('/all/packages', [UserSubscription::class, 'getAllPackages']);

//sections
Route::get('/all/sections', [SectionsApi::class, 'sections']);





Route::post('/products/of/offer', [OffersApi::class, 'offerProducts']);


//Contact
Route::post('/contact', [ContactApi::class, 'userMessage']);


//countries
Route::get('/cities', [CountriesApi::class, 'cities']);


Route::get('/all/news', [NewsApi::class, 'getAllNews']);

//Cities
Route::get('/areas', [CitiesApi::class, 'areas']);

//app status
Route::get('/application/status', [ApplicationStatusApi::class, 'appStatus']);

//onBoarding screen
Route::get('/onBoaring/screen', [OnBoardingScreenApi::class, 'onBoardings']);

Route::get('/check/subscription/status', [SubscriptionController::class, 'checkUserSubscription']);


Route::get('/cities/{countryId}', function ($countryId) {
    return City::where('country_id', $countryId)->get(['id', 'name']);
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/stores/discount/{uuid}', [StoresApi::class, 'showDiscount'])
    ->name('store.discount');

Route::get('/payment/callback', [SubscriptionController::class, 'callback'])->name('payment.callback');

// private routes
Route::group(['middleware' => ['auth:sanctum', 'track_activity']], function () {

    Route::post('/logout', [Authentication::class, 'logout']);

    // Route::get('/all/offers', [OffersApi::class, 'offers']);

    //offers
    Route::post('/update/offer/discount', [OffersApi::class, 'updateOfferDiscount']);

    //product requests
    Route::post('/request/product', [CustomerRequestApi::class, 'requestProduct']);
    Route::get('/unconfirmed/product/requests', [CustomerRequestApi::class, 'unconfirmedPurchaseRequests']);


    //offers
    Route::get('/all/offers', [OffersApi::class, 'offers']);
    Route::post('/specific/offer', [OffersApi::class, 'offer']);

    //products
    Route::get('/all/products', [ProductController::class, 'getAllProducts']);
    Route::get('/products/{id}', [ProductController::class, 'getProductDetails']);

    Route::get('/all/user/sections', [StoresApi::class, 'getUserSubscribedSections']);
    //stores
    Route::get('/all/stores', [StoresApi::class, 'getSellersWithStores']);
    Route::get('/all/stores/with/offers', [StoresApi::class, 'storesWithOffers']);
    Route::get('/all/stores/with/products', [StoresApi::class, 'storesWithProducts']);
    Route::post('/specific/store', [StoresApi::class, 'store']);
    Route::get('/most/popular/stores', [StoresApi::class, 'mostPopularStores']);
    Route::post('/filter/stores', [StoresApi::class, 'filterStores']);

    // Lahza Payment
    Route::post('/subscribe', [SubscriptionController::class, 'initiate']);
    // Route::post('/payment/callback', [SubscriptionController::class, 'callback'])->name('payment.callback');
    Route::prefix('/user')->group(function () {

        // subscriptions
        Route::post('/package/subscription', [UserSubscription::class, 'packageUserSubscription']);
        Route::post('/free/subscription', [UserSubscription::class, 'freeSubscription']);
        Route::get('/subscription/details', [UserSubscription::class, 'currentSubscriptionDetails']);

        //profile
        Route::get('/profile', [UserApi::class, 'profile']);

        //invoices
        Route::get('/purchases', [InvoicesApi::class, 'clientPurchases']);

        //Client Discount Requests
        Route::post('/discountRequests/requestDiscount', [DiscountController::class, 'requestDiscount']);
        Route::get('/discountRequests/getUserDiscountRequests', [DiscountController::class, 'getUserDiscountRequests']);

        // Get & Update User Language Routes
        Route::get('/lang', [UserApi::class, 'getUserLang']);
        Route::post('/lang', [UserApi::class, 'updateUserLang']);

        //favorites
        Route::prefix('/favorite')->group(function () {
            Route::get('/offers', [FavoritesApi::class, 'userFavoriteOffers']); // Get user's favorite offers
            Route::post('/offers', [FavoritesApi::class, 'toggleFavoriteOffer']); // Add an offer to user's favorites

            Route::get('/stores', [FavoritesApi::class, 'userFavoriteStores']); // Get user's favorite stores
            Route::post('/stores', [FavoritesApi::class, 'toggleFavoriteStore']); // Add a store to user's favorites
        });

        Route::prefix('/settings')->group(function () {
            // Update the user's password.
            Route::post('/update-password', [UserSettingsController::class, 'updatePassword']);

            // Toggle the push notification preference for the user.
            Route::post('/toggle-push-notifications', [UserSettingsController::class, 'togglePushNotifications']);
        });

        Route::prefix('/notifications')->group(function () {
            // Send a notification to specified users.
            Route::post('/send', [PushNotificationController::class, 'sendNotification']);

            // Retrieve all notifications for the authenticated user.
            Route::get('/', [PushNotificationController::class, 'getUserNotifications']);

            // Mark a specific notification as viewed by the user.
            Route::post('/{notificationId}/view', [PushNotificationController::class, 'markAsViewed']);
        });

        Route::get('/tickets', [ContactApi::class, 'getUserTickets']);
        Route::get('/tickets/{id}', [ContactApi::class, 'showUserTicket']);
    });

    // delegate routes
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

    // delegate routes
    Route::prefix('/sellers')->middleware(['auth', 'is_seller'])->group(function () {
        // Request a discount change
        Route::get('/discount', [DiscountRequestController::class, 'getCurrentStoreDiscount']);
        Route::post('/discount/request', [DiscountRequestController::class, 'requestStoreDiscountUpdate']);

        // Get the authenticated seller's data
        Route::get('/data', [SellerController::class, 'getSellerData']);

        // Update the authenticated seller's data
        Route::put('/update', [SellerController::class, 'update']);

        // Get the seller's products
        Route::get('/products', [SellerController::class, 'getSellerProducts']);

        // Update seller's product
        Route::put('/products/{productId}', [SellerController::class, 'updateProduct']); // Update product

        // Delete Seller's Product
        Route::delete('/products/{productId}', [SellerController::class, 'deleteProduct']);

        // Get all excluded products
        Route::get('/excluded/products', [SellerController::class, 'getExcludedProducts']);

        // Update the list of excluded products (add/remove)
        Route::put('/excluded/products', [SellerController::class, 'updateExcludedProducts']); // Update excluded products list

        // Offer routes
        Route::prefix('/offers')->group(function () {
            Route::post('/', [OfferController::class, 'addOffer']); // Add a new offer
            Route::get('/', [OfferController::class, 'getUserOffers']); // Get user offers with products
            Route::put('/{offerId}', [OfferController::class, 'updateOffer']); // Update an existing offer
            Route::patch('/{offerId}/offline', [OfferController::class, 'markOfferOffline']); // Mark an offer as offline
        });

        // Endpoint to get the sales data for the authenticated seller
        Route::get('/seller-sales', [InvoicesApi::class, 'sellerSales']);

        // Endpoint to get details of a specific invoice
        Route::get('/invoices/{invoiceId}', [InvoicesApi::class, 'getInvoiceDetails']);

        // Endpoint to mark a specific invoice as paid
        Route::post('/invoices/{invoiceId}/mark-paid', [InvoicesApi::class, 'markInvoiceAsPaid']);


        Route::get('/discountRequests/getDiscountRequests', [SellerDiscountController::class, 'getDiscountRequests']);
        Route::post('/discountRequests/markAsPaid/{invoiceId}', [SellerDiscountController::class, 'markAsPaid']);
    });
});
