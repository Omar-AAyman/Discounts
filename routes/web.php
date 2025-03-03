<?php

use App\Http\Controllers\CityController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\DelegateController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\OfferNotificationController;
use App\Http\Controllers\OnBoardingController;
use App\Http\Controllers\OptionController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\PaymentStatusController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\StoreAndSellerController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use App\Models\OfferNotification;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


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

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('/');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/stores/discount/{uuid}', [StoreController::class, 'showDiscount'])
    ->name('store.discount');

Route::get("/payments-summary", [PaymentStatusController::class, "show"])->name("payments-summary");

Route::middleware(['auth', 'is_admin'])->group(function () {
    // ADMIN PANEL ROUTES
    Route::get('/admin', function () {
        return view('admin');
    });



    // packages routes
    Route::get('/admin/packages', [PackageController::class, 'index'])->name('packages.index');
    // Route::get('/admin/packages/create', [PackageController::class, 'create'])->name('packages.create');
    Route::get('/admin/packages/edit/{uuid}', [PackageController::class, 'edit'])->name('packages.edit');
    // Route::post('/admin/packages/store', [PackageController::class, 'store'])->name('packages.store');
    Route::put('/admin/packages/update/{uuid}', [PackageController::class, 'update'])->name('packages.update');
    Route::get('/admin/packages/sections/{uuid}', [PackageController::class, 'showSections'])->name('packages.showSections');

    // stores routes
    // Route::get('/admin/stores', [StoreController::class, 'index'])->name('stores.index');
    // Route::get('/admin/stores/create', [StoreController::class, 'create'])->name('stores.create');
    // Route::get('/admin/stores/edit/{uuid}', [StoreController::class, 'edit'])->name('stores.edit');
    // Route::post('/admin/stores/store', [StoreController::class, 'store'])->name('stores.store');
    // Route::put('/admin/stores/update/{uuid}', [StoreController::class, 'update'])->name('stores.update');
    Route::get('/admin/store/show/requests', [StoreController::class, 'showSellersRequests'])->name('stores.showSellersRequests');
    Route::get('/admin/stores/approve/seller', [StoreController::class, 'approveSeller'])->name('stores.approveSeller');
    Route::post('/admin/stores/add/seller', [StoreController::class, 'addSeller'])->name('stores.addSeller');
    // Store Request to Change Discount
    Route::get('/admin/stores/discount/pending-requests', [StoreController::class, 'showChangeDiscountRequests'])->name('stores.showChangeDiscountRequests');
    Route::post('/admin/stores/discount/reject-request/{id}', [StoreController::class, 'rejectChangeDiscountRequest'])->name('stores.discount.reject');
    Route::post('/admin/stores/discount/accept-request/{id}', [StoreController::class, 'acceptChangeDiscountRequest'])->name('stores.discount.accept');
    //Store Delete Requests
    Route::get('/admin/stores/delete-requests', [StoreController::class, 'deleteRequests'])->name('stores.deleteRequests');
    Route::post('/admin/stores/delete-request/{store}', [StoreController::class, 'processDeleteRequest'])->name('stores.processDeleteRequest');

    // Store & Seller Part (New)
    Route::get('/admin/stores-sellers', [StoreAndSellerController::class, 'index'])->name('store-and-seller.index');
    Route::get('/admin/stores-sellers/create', [StoreAndSellerController::class, 'create'])->name('store-and-seller.create');
    Route::post('/admin/stores-sellers/store', [StoreAndSellerController::class, 'store'])->name('store-and-seller.store');
    Route::get('/admin/stores-sellers/{seller}/edit', [StoreAndSellerController::class, 'edit'])->name('store-and-seller.edit');
    Route::put('/admin/stores-sellers/{seller}/update', [StoreAndSellerController::class, 'update'])->name('store-and-seller.update');
    Route::delete('/admin/stores-sellers/{seller}', [StoreAndSellerController::class, 'destroy'])->name('store-and-seller.destroy');



    // sections routes
    Route::get('/admin/sections', [SectionController::class, 'index'])->name('sections.index');
    Route::get('/admin/sections/create', [SectionController::class, 'create'])->name('sections.create');
    Route::get('/admin/sections/edit/{uuid}', [SectionController::class, 'edit'])->name('sections.edit');
    Route::post('/admin/sections/store', [SectionController::class, 'store'])->name('sections.store');
    Route::put('/admin/sections/update/{uuid}', [SectionController::class, 'update'])->name('sections.update');
    Route::delete('/admin/sections/{uuid}', [SectionController::class, 'destroy'])->name('sections.destroy');
    Route::get('/admin/sections/show/stores/{uuid}', [SectionController::class, 'showStores'])->name('sections.showStores');
    Route::get('/admin/sections/show/attach/stores/{uuid}', [SectionController::class, 'showAttachStore'])->name('sections.showAttachStore');
    Route::patch('/admin/sections/attach/store/{uuid}', [SectionController::class, 'attachStore'])->name('sections.attachStore');

    // options routes
    Route::get('/admin/options', [OptionController::class, 'index'])->name('options.index');
    Route::get('/admin/options/create', [OptionController::class, 'create'])->name('options.create');
    Route::get('/admin/options/edit/{id}', [OptionController::class, 'edit'])->name('options.edit');
    Route::post('/admin/options/store', [OptionController::class, 'store'])->name('options.store');
    Route::put('/admin/options/update/{id}', [OptionController::class, 'update'])->name('options.update');

    Route::get('/admin/options/show/images', [OptionController::class, 'showImages'])->name('options.showImages');
    Route::get('/admin/options/img/edit/{id}', [OptionController::class, 'editImageOption'])->name('options.editImageOption');
    Route::put('/admin/options/img/update/{id}', [OptionController::class, 'updateImageOption'])->name('options.updateImageOption');


    // subscriptions routes
    Route::get('/admin/guestSubscriptions', [SubscriptionController::class, 'displayGuestsSubscribtions'])->name('subscriptions.guestSubscriptions');
    Route::get('/admin/userSubscriptions', [SubscriptionController::class, 'displayUsersSubscribtions'])->name('subscriptions.userSubscriptions');
    Route::get('/admin/subscriptions/show/subscribe/guest', [SubscriptionController::class, 'showSubscribeGuest'])->name('subscriptions.showSubscribeGuest');
    Route::get('/admin/subscriptions/show/subscribe/user', [SubscriptionController::class, 'showSubscribeUser'])->name('subscriptions.showSubscribeUser');



    Route::post('/admin/subscriptions/subscribe/guest', [SubscriptionController::class, 'guestSubscription'])->name('subscriptions.guestSubscription');
    Route::post('/admin/subscriptions/subscribe/user', [SubscriptionController::class, 'userSubscription'])->name('subscriptions.userSubscription');

    Route::put('/admin/subscriptions/unsubscribe', [SubscriptionController::class, 'unsubscribe'])->name('subscriptions.unsubscribe');


    // news routes
    Route::get('/admin/news', [NewsController::class, 'index'])->name('news.index');
    Route::get('/admin/news/create', [NewsController::class, 'create'])->name('news.create');
    Route::post('/admin/news/store', [NewsController::class, 'store'])->name('news.store');
    Route::get('/admin/news/edit/{uuid}', [NewsController::class, 'edit'])->name('news.edit');
    Route::put('/admin/news/update/{uuid}', [NewsController::class, 'update'])->name('news.update');

    // Countries & Cities
    Route::prefix('admin')->group(function () {
        Route::resource('cities', CountryController::class);
        Route::resource('areas', CityController::class);
    });

    // users routes
    Route::post('/admin/make/seller/sponsor', [UserController::class, 'makeSponser'])->name('users.makeSponser');
    Route::get('/admin/users/sellers', [UserController::class, 'sellers'])->name('users.sellers');
    Route::get('/admin/users/customer-support', [UserController::class, 'customerSupport'])->name('users.customer_supports');
    Route::get('/admin/users/clients', [UserController::class, 'clients'])->name('users.clients');
    Route::get('/admin/users/delegates', [UserController::class, 'delegates'])->name('users.delegates');
    Route::get('/admin/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/admin/users/store', [UserController::class, 'store'])->name('users.store');
    Route::get('/admin/users/edit/{uuid}', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/admin/users/update/{uuid}', [UserController::class, 'update'])->name('users.update');


    // offers routes
    Route::get('/admin/offers', [OfferController::class, 'index'])->name('offers.index');
    Route::get('/admin/offers/create', [OfferController::class, 'create'])->name('offers.create');
    Route::post('/admin/offers/store', [OfferController::class, 'store'])->name('offers.store');
    Route::get('/admin/offers/edit/{id}', [OfferController::class, 'edit'])->name('offers.edit');
    Route::put('/admin/offers/update/{id}', [OfferController::class, 'update'])->name('offers.update');


    // offer notifications routes
    Route::get('/admin/offersNotification/pending-requests', [OfferNotificationController::class, 'showChangeDiscountRequests'])->name('offerNotifications.showChangeDiscountRequests');
    Route::post('/admin/offersNotification/reject-request/{id}', [OfferNotificationController::class, 'rejectChangeDiscountRequest'])->name('offerNotifications.reject');
    Route::post('/admin/offersNotification/accept-request/{id}', [OfferNotificationController::class, 'acceptChangeDiscountRequest'])->name('offerNotifications.accept');


    // on boardings routes
    Route::get('/admin/on-boarding', [OnBoardingController::class, 'index'])->name('onboardings.index');
    Route::get('/admin/on-boarding/edit/{id}', [OnBoardingController::class, 'edit'])->name('onboardings.edit');
    Route::put('/admin/on-boarding/update/{id}', [OnBoardingController::class, 'update'])->name('onboardings.update');
});

Route::middleware(['auth', 'is_delegate'])->group(function () {
    // delegat routes
    Route::get('/main/view', [DelegateController::class, 'mainView'])->name('delegates.mainView');
    Route::get('/create/seller', [DelegateController::class, 'createSeller'])->name('delegates.createSeller');
    Route::get('/get/related/sellers', [DelegateController::class, 'getRelatedSellers'])->name('delegates.relatedSellers');
    Route::post('/add/seller', [DelegateController::class, 'addSeller'])->name('delegates.addSeller');
    Route::post('/store/{store}/request-delete', [DelegateController::class, 'requestStoreDeletion'])->name('delegates.requestDelete');
    Route::get('/delegates/sellers/{seller}/edit', [DelegateController::class, 'editSeller'])->name('delegates.editSeller');
    Route::put('/delegates/sellers/{seller}', [DelegateController::class, 'updateSeller'])->name('delegates.updateSeller');
});
Route::middleware(['auth', 'admin_or_cs'])->group(function () {

    // tickets routes
    Route::get('tickets', [TicketController::class, 'index'])->name('tickets.index');
    Route::get('tickets/show/{id}', [TicketController::class, 'show'])->name('tickets.show');
    Route::post('tickets/{parentTicketId}/reply', [TicketController::class, 'storeReply'])->name('tickets.storeReply');
    Route::patch('tickets/{id}/update-status', [TicketController::class, 'updateStatus'])->name('tickets.updateStatus');
});
