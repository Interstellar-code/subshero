<?php

use App\Http\Controllers\Api\v1\AlertController;
use App\Http\Controllers\Api\v1\AuthController;
use App\Http\Controllers\Api\v1\CalendarController;
use App\Http\Controllers\Api\v1\FolderController;
use App\Http\Controllers\Api\v1\NotificationController;
use App\Http\Controllers\Api\v1\PaymentMethodController;
use App\Http\Controllers\Api\v1\Product\ProductController;
use App\Http\Controllers\Api\v1\Product\CategoryController;
use App\Http\Controllers\Api\v1\ReportController;
use App\Http\Controllers\Api\v1\SettingsController;
use App\Http\Controllers\Api\v1\SubscriptionController;
use App\Http\Controllers\Api\v1\TagController;
use App\Http\Controllers\Api\v1\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


// Application Programming Interface (API) -> Version 1 -> Routes
Route::group(['prefix' => 'v1', 'middleware' => 'throttle:1000,1'], function () {

    // Public routes
    // Authentication routes
    Route::group(['prefix' => 'auth'], function () {
        // Route::post('register', [AuthController::class, 'register'])->name('api/v1/auth/register');
        Route::post('login', [AuthController::class, 'login'])->name('api/v1/auth/login');
        Route::post('register', [AuthController::class, 'register'])->name('api/v1/auth/register');
    });


    // Protected routes
    Route::group(['middleware' => ['auth:sanctum']], function () {

        // Authentication routes
        Route::group(['prefix' => 'auth'], function () {
            Route::get('token', [AuthController::class, 'token'])->name('api/v1/auth/token');
            Route::get('logout', [AuthController::class, 'logout'])->name('api/v1/auth/logout');
        });

        // Product routes
        Route::group(['prefix' => 'products'], function () {
            Route::get('/', [ProductController::class, 'index'])->name('api/v1/products/index');
            Route::get('{id}', [ProductController::class, 'show'])
                ->where('id', '[0-9]+')
                ->name('api/v1/products/show');
            Route::get('search', [ProductController::class, 'search'])->name('api/v1/products/search');

            // Product -> Category routes
            Route::group(['prefix' => 'categories'], function () {
                Route::get('/', [CategoryController::class, 'index'])->name('api/v1/products/categories/index');
                // Route::post('/', [CategoryController::class, 'create'])->name('api/v1/products/categories/create');
                Route::get('{id}', [CategoryController::class, 'show'])->name('api/v1/products/categories/show');
                // Route::match(['put', 'patch'], '{id}', [CategoryController::class, 'update'])->name('api/v1/products/categories/update');
                // Route::delete('{id}', [CategoryController::class, 'delete'])->name('api/v1/products/categories/delete');
            });
        });

        // Subscription routes
        Route::group(['prefix' => 'subscriptions'], function () {
            Route::get('/', [SubscriptionController::class, 'index'])->name('api/v1/subscriptions/index');
            Route::get('search', [SubscriptionController::class, 'search'])->name('api/v1/subscriptions/search');
            Route::get('upcoming', [SubscriptionController::class, 'upcoming'])->name('api/v1/subscriptions/upcoming');
            Route::post('/', [SubscriptionController::class, 'create'])->name('api/v1/subscriptions/create');
            Route::get('{id}', [SubscriptionController::class, 'show'])->name('api/v1/subscriptions/show');
            Route::match(['put', 'patch'], '{id}', [SubscriptionController::class, 'update'])->name('api/v1/subscriptions/update');
            Route::delete('{id}', [SubscriptionController::class, 'delete'])->name('api/v1/subscriptions/delete');
            Route::post('{id}/cancel', [SubscriptionController::class, 'cancel'])->name('api/v1/subscriptions/cancel');
            Route::post('{id}/refund', [SubscriptionController::class, 'refund'])->name('api/v1/subscriptions/refund');
            Route::post('{id}/addon', [SubscriptionController::class, 'addon'])->name('api/v1/subscriptions/addon');
        });

        // Subscription routes
        Route::group(['prefix' => 'reports'], function () {
            Route::get('/', [ReportController::class, 'index'])->name('api/v1/reports/index');
            Route::get('active-subs-ltd', [ReportController::class, 'activeSubsLtd'])->name('api/v1/reports/active-subs-ltd');

            Route::get('active-subscription-total', [ReportController::class, 'activeSubscriptionTotal'])->name('api/v1/reports/active-subscription-total');
            Route::get('active-subscription-mrr', [ReportController::class, 'activeSubscriptionMRR'])->name('api/v1/reports/active-subscription-mrr');
            Route::get('active-subscription-pie', [ReportController::class, 'activeSubscriptionPie'])->name('api/v1/reports/active-subscription-pie');

            Route::get('active-lifetime-mrr', [ReportController::class, 'activeLifetimeMRR'])->name('api/v1/reports/active-lifetime-mrr');
        });

        // Settings routes
        Route::group(['prefix' => 'settings'], function () {
            Route::get('extension', [SettingsController::class, 'index'])->name('api/v1/settings/extension/index');
            Route::match(['put', 'patch'], 'extension', [SettingsController::class, 'update'])->name('api/v1/settings/extension/update');
        });

        // Calendar routes
        Route::group(['prefix' => 'calendar'], function () {
            Route::get('/', [CalendarController::class, 'index'])->name('api/v1/calendar/index');
        });

        // Notification routes
        Route::group(['prefix' => 'notifications'], function () {
            Route::get('/', [NotificationController::class, 'index'])->name('api/v1/notifications/index');
            Route::match(['put', 'patch'], '{id}', [NotificationController::class, 'update'])->name('api/v1/notifications/update');

            // Notification -> Clear all route
            Route::delete('/', [NotificationController::class, 'clear'])->name('api/v1/notifications/clear');

            // Notification -> Clear one route
            Route::delete('{id}', [NotificationController::class, 'delete'])->name('api/v1/notifications/delete');
        });

        // Folder routes
        Route::group(['prefix' => 'folders'], function () {
            Route::get('/', [FolderController::class, 'index'])->name('api/v1/folders/index');
            Route::post('/', [FolderController::class, 'create'])->name('api/v1/folders/create');
            Route::get('{id}', [FolderController::class, 'show'])->name('api/v1/folders/show');
            Route::match(['put', 'patch'], '{id}', [FolderController::class, 'update'])->name('api/v1/folders/update');
            Route::delete('{id}', [FolderController::class, 'delete'])->name('api/v1/folders/delete');
        });

        // Alert routes
        Route::group(['prefix' => 'alerts'], function () {
            Route::get('/', [AlertController::class, 'index'])->name('api/v1/alerts/index');
            Route::post('/', [AlertController::class, 'create'])->name('api/v1/alerts/create');
            Route::get('{id}', [AlertController::class, 'show'])->name('api/v1/alerts/show');
            Route::match(['put', 'patch'], '{id}', [AlertController::class, 'update'])->name('api/v1/alerts/update');
            Route::delete('{id}', [AlertController::class, 'delete'])->name('api/v1/alerts/delete');
        });

        // Alert routes
        Route::group(['prefix' => 'tags'], function () {
            Route::get('/', [TagController::class, 'index'])->name('api/v1/tags/index');
            Route::post('/', [TagController::class, 'create'])->name('api/v1/tags/create');
            Route::get('{id}', [TagController::class, 'show'])->name('api/v1/tags/show');
            Route::match(['put', 'patch'], '{id}', [TagController::class, 'update'])->name('api/v1/tags/update');
            Route::delete('{id}', [TagController::class, 'delete'])->name('api/v1/tags/delete');
        });

        // Alert routes
        Route::group(['prefix' => 'payments/methods'], function () {
            Route::get('/', [PaymentMethodController::class, 'index'])->name('api/v1/payments/methods/index');
            Route::post('/', [PaymentMethodController::class, 'create'])->name('api/v1/payments/methods/create');
            Route::get('{id}', [PaymentMethodController::class, 'show'])->name('api/v1/payments/methods/show');
            Route::match(['put', 'patch'], '{id}', [PaymentMethodController::class, 'update'])->name('api/v1/payments/methods/update');
            Route::delete('{id}', [PaymentMethodController::class, 'delete'])->name('api/v1/payments/methods/delete');
        });

        // Currency routes
        Route::group(['prefix' => 'currencies'], function () {
            Route::get('/', [SubscriptionController::class, 'index'])->name('api/v1/currencies/index');
        });

        // User routes
        Route::group(['prefix' => 'users'], function () {
            // Route::get('/', [UserController::class, 'index'])->name('api/v1/users/index');
            // Route::post('/', [UserController::class, 'create'])->name('api/v1/users/create');
            Route::get('{id}', [UserController::class, 'show'])->name('api/v1/users/show');
            Route::match(['put', 'patch'], '{id}', [UserController::class, 'update'])->name('api/v1/users/update');
            // Route::delete('{id}', [UserController::class, 'delete'])->name('api/v1/users/delete');

            Route::get('{id}/billing', [UserController::class, 'showBilling'])->name('api/v1/users/billing/show');
            Route::get('{id}/preferences', [UserController::class, 'showPreferences'])->name('api/v1/users/preference/show');
            Route::match(['put', 'patch'], '{id}/preferences', [UserController::class, 'updatePreferences'])->name('api/v1/users/preference/update');

            // User -> Preferences routes
            // Route::group(['prefix' => '{id}/preferences'], function () {
            //     Route::get('/', [UserController::class, 'index'])->name('api/v1/users/preferences/index');
            //     Route::post('/', [UserController::class, 'create'])->name('api/v1/users/preferences/create');
            //     // Route::get('{id}', [UserController::class, 'show'])->name('api/v1/users/preferences/show');
            //     Route::match(['put', 'patch'], '{id}', [UserController::class, 'update'])->name('api/v1/users/preferences/update');
            //     Route::delete('{id}', [UserController::class, 'delete'])->name('api/v1/users/preferences/delete');
            // });
        });
    });
});
