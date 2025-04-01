<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



// Check if user login is allowed
if (Storage::disk('local')->exists('system/toggle/login.txt')) {
    Route::get('login', fn () => abort(404))->name('login');
    Route::any('register', fn () => abort(404))->name('register');
    Route::get('password/reset', fn () => abort(404))->name('password.request');
    Route::get('password/email', fn () => abort(404))->name('password.email');
    Route::get('password/reset/{token}', fn () => abort(404))->name('password.reset');
    Route::post('password/reset', fn () => abort(404))->name('password.update');
} else {
    // Authentication routes
    Auth::routes();
}










// Client area routes
Route::get('/home', 'App\Http\Controllers\HomeController@index')->name('home');
Route::get('/', 'App\Http\Controllers\HomeController@index')->name('index');
Route::get('/', 'App\Http\Controllers\HomeController@index')->name('/');


Route::get('calendar', 'App\Http\Controllers\Client\CalendarController@index')->name('calendar');

Route::group([
    'prefix' => 'subscription',
    'namespace' => 'App\Http\Controllers',
], function () {
    Route::any('/', 'Client\SubscriptionController@index')->name('app/subscription/index');
    Route::post('create', 'Client\SubscriptionController@create')->name('app/subscription/create');
    Route::post('create_quick', 'Client\SubscriptionController@create_quick')->name('app/subscription/create_quick');
    Route::post('edit/{id}', 'Client\SubscriptionController@edit')->name('app/subscription/edit');
    Route::post('update/{id}', 'Client\SubscriptionController@update')->name('app/subscription/update');
    Route::post('delete/{id}', 'Client\SubscriptionController@delete')->name('app/subscription/delete');
    Route::post('cancel/{id}', 'Client\SubscriptionController@cancel')->name('app/subscription/cancel');
    Route::post('clone/{id}', 'Client\SubscriptionController@clone')->name('app/subscription/clone');
    // Route::post('pause/{id}', 'Client\SubscriptionController@pause')->name('app/subscription/pause');
    Route::post('refund/{id}', 'Client\SubscriptionController@refund')->name('app/subscription/refund');
    Route::post('addon/{id}', 'Client\SubscriptionController@addon')->name('app/subscription/addon');
    Route::post('attachment/{id}', 'Client\SubscriptionController@attachment')->name('app/subscription/attachment');
    Route::post('attachment_upload/{id}', 'Client\SubscriptionController@attachment_upload')->name('app/subscription/attachment_upload');
    Route::post('attachment_delete/{id}', 'Client\SubscriptionController@attachment_delete')->name('app/subscription/attachment_delete');
    Route::post('get', 'Client\SubscriptionController@get')->name('app/subscription/get');
    Route::post('chart', 'Client\SubscriptionController@get_chart')->name('app/subscription/chart');
    Route::post('session/set_datatable_page_length', 'Client\SubscriptionController@set_datatable_page_length')->name('app/subscription/session/set_datatable_page_length');

    Route::group(['prefix' => 'koolreport'], function () {
        Route::post('both_charts', 'Client\SubscriptionController@koolreport_both_charts')->name('app/subscription/koolreport/both_charts');
        Route::post('area_chart', 'Client\SubscriptionController@koolreport_area_chart')->name('app/subscription/koolreport/area_chart');
        Route::post('lifetime_drilldown_chart_all_time', 'Client\SubscriptionController@koolreport_lifetime_drilldown_chart_all_time')->name('app/subscription/koolreport/drilldown_chart');
        Route::post('lifetime_drilldown_chart_inside', 'Client\SubscriptionController@koolreport_lifetime_drilldown_chart_inside')->name('app/subscription/koolreport/drilldown_chart');
    });

    Route::post('history/{id}', 'Client\SubscriptionController@history')->name('app/subscription/history');
    Route::post('history/{id}', 'Client\SubscriptionController@history')->name('app/subscription/history');

    Route::group([
        'prefix' => 'marketplace',
        'namespace' => 'Client\Subscription',
    ], function () {
        Route::get('edit/{id}', 'MarketplaceController@edit')->name('app/subscription/marketplace/edit');
        Route::post('/', 'MarketplaceController@create')->name('app/subscription/marketplace/create');
        Route::match(['put', 'patch'], '{id}', 'MarketplaceController@update')->name('app/subscription/marketplace/update');
    });

    Route::group([
        'prefix' => 'adapt',
        'namespace' => 'Client\Subscription',
    ], function () {
        Route::post('submit/{id}', 'AdaptController@submit')->name('app/subscription/adapt/submit');
        Route::get('edit/{id}', 'AdaptController@edit')->name('app/subscription/adapt/edit');
        Route::post('accept/{id}', 'AdaptController@accept')->name('app/subscription/adapt/accept');
    });

    Route::group([
        'prefix' => 'pdf',
        'namespace' => 'Client\Subscription',
    ], function () {
        Route::post('import', 'PDFImportController@import')->name('app/subscription/pdf/import');
        Route::post('save', 'PDFImportController@save')->name('app/subscription/pdf/save');
    });

    // mass update inline editable subscription list
    Route::post('mass-update/save-all', 'Client\SubscriptionController@massUpdateSaveAll');
    Route::post('mass-update/save', 'Client\SubscriptionController@massUpdateSave');
    Route::get('mass-update/list', 'Client\SubscriptionController@massUpdateList');
    Route::get('mass-update', 'Client\SubscriptionController@massUpdate')->name('app/subscription/mass-update');
});

Route::group([
    'prefix' => 'subscription_history',
    'namespace' => 'App\Http\Controllers\Client',
], function () {
    Route::get('get/{subscription_id}', 'SubscriptionHistoryController@get')->name('app/subscription_history/get');
    Route::post('update', 'SubscriptionHistoryController@update')->name('app/subscription_history/update');
    Route::post('delete', 'SubscriptionHistoryController@delete')->name('app/subscription_history/delete');
    Route::post('update_all', 'SubscriptionHistoryController@update_all')->name('app/subscription_history/update_all');
});


Route::group([
    'prefix' => 'report',
    'namespace' => 'App\Http\Controllers',
], function () {
    Route::any('/', 'Client\ReportController@index')->name('app/report/index');
    Route::post('subscription/all_charts', 'Client\ReportController@get_subscription_all_charts')->name('app/report/subscription/all_charts');

    Route::group(['prefix' => 'koolreport'], function () {
        Route::post('lifetime/drilldown_chart', 'Client\ReportController@get_lifetime_drilldown_chart')->name('app/report/koolreport/lifetime/drilldown_chart');
    });


    Route::group(['prefix' => 'koolreport'], function () {
        Route::post('subscription/mrp_area_chart', 'Client\ReportController@get_subscription_mrp_area_chart')->name('app/report/koolreport/subscription/mrp_area_chart');
        Route::post('subscription/category_pie', 'Client\ReportController@get_subscription_category_pie')->name('app/report/koolreport/subscription/category_pie');
        Route::post('subscription/all_drilldown', 'Client\ReportController@get_subscription_all_drilldown')->name('app/report/koolreport/subscription/all_drilldown');
    });
});

Route::group([
    'prefix' => 'folder',
    'namespace' => 'App\Http\Controllers',
], function () {
    Route::get('/', function () {
        return redirect()->route('app/subscription');
    });
    // Route::get('/{id}', 'Client\SubscriptionController@get_by_folder')->name('app/subscription/get_by_folder');
    Route::post('session/set', 'Client\FolderController@session_set')->name('app/folder/session/set');
    Route::post('session/clear', 'Client\FolderController@session_clear')->name('app/folder/session/clear');
    Route::post('create', 'Client\FolderController@create')->name('app/folder/create');
    Route::post('edit/{id}', 'Client\FolderController@edit')->name('app/folder/edit');
    Route::post('update/{id}', 'Client\FolderController@update')->name('app/folder/update');
    Route::post('delete/{id}', 'Client\FolderController@delete')->name('app/folder/delete');
    Route::post('get', 'Client\FolderController@get')->name('app/folder/get');
    Route::post('refresh', 'Client\FolderController@refresh')->name('app/folder/refresh');
    Route::get('search', 'Client\FolderController@search')->name('app/folder/search');
});

Route::group([
    'prefix' => 'settings',
    'namespace' => 'App\Http\Controllers',
], function () {
    Route::get('/', 'Client\SettingsController@profile')->name('app/settings/profile');

    Route::group(['prefix' => 'profile'], function () {
        Route::get('/', 'Client\SettingsController@profile')->name('app/settings/profile');
        Route::post('upload', 'Client\SettingsController@profile_upload')->name('app/settings/profile/upload');
        Route::post('update/info', 'Client\SettingsController@profile_update_info')->name('app/settings/profile/update/info');
        Route::post('update/password', 'Client\SettingsController@profile_update_password')->name('app/settings/profile/update/password');
        Route::post('reset', 'Client\Settings\ProfileController@reset')->name('app/settings/profile/reset');
        Route::post('reset/delete_account', 'Client\Settings\ProfileController@delete_account')->name('app/settings/profile/reset/delete_account');
    });

    Route::group(['prefix' => 'billing'], function () {
        Route::get('/', 'Client\SettingsController@billing')->name('app/settings/billing');
        Route::post('coupon/apply', 'Client\SettingsController@billing_coupon_apply')->name('app/settings/billing/coupon/apply');
    });

    Route::get('payment', 'Client\SettingsController@payment')->name('app/settings/payment');
    Route::post('payment/update', 'Client\SettingsController@payment_update')->name('app/settings/payment/update');

    Route::group(['prefix' => 'contact'], function () {
        Route::get('/', 'Client\Settings\ContactController@index')->name('app/settings/contact');
        Route::post('create', 'Client\Settings\ContactController@create')->name('app/settings/contact/create');
        Route::post('get/{id}', 'Client\Settings\ContactController@get')->name('app/settings/contact/get');
        Route::post('edit/{id}', 'Client\Settings\ContactController@edit')->name('app/settings/contact/edit');
        Route::post('update', 'Client\Settings\ContactController@update')->name('app/settings/contact/update');
        Route::post('delete/{id}', 'Client\Settings\ContactController@delete')->name('app/settings/contact/delete');
    });

    Route::get('preference', 'Client\SettingsController@preference')->name('app/settings/preference');
    Route::post('preference/update', 'Client\SettingsController@preference_update')->name('app/settings/preference/update');


    Route::group(['prefix' => 'tag'], function () {
        Route::get('/', 'Client\Settings\TagController@index')->name('app/settings/tag');
        Route::get('search', 'Client\Settings\TagController@search')->name('app/settings/tag/search');
        Route::post('create', 'Client\Settings\TagController@create')->name('app/settings/tag/create');
        Route::post('get/{id}', 'Client\Settings\TagController@get')->name('app/settings/tag/get');
        Route::post('update', 'Client\Settings\TagController@update')->name('app/settings/tag/update');
        Route::post('delete/{id}', 'Client\Settings\TagController@delete')->name('app/settings/tag/delete');
    });


    Route::group(['prefix' => 'alert'], function () {
        Route::get('/', 'Client\Settings\AlertController@index')->name('app/settings/alert');
        Route::post('create', 'Client\Settings\AlertController@create')->name('app/settings/alert/create');
        Route::post('edit/{id}', 'Client\Settings\AlertController@edit')->name('app/settings/alert/edit');
        Route::post('update', 'Client\Settings\AlertController@update')->name('app/settings/alert/update');
        Route::post('delete/{id}', 'Client\Settings\AlertController@delete')->name('app/settings/alert/delete');
    });


    Route::group(['prefix' => 'payment'], function () {
        Route::get('/', 'Client\Settings\PaymentController@index')->name('app/settings/payment');
        Route::post('create', 'Client\Settings\PaymentController@create')->name('app/settings/payment/create');
        Route::post('get/{id}', 'Client\Settings\PaymentController@get')->name('app/settings/payment/get');
        Route::post('edit/{id}', 'Client\Settings\PaymentController@edit')->name('app/settings/payment/edit');
        Route::post('update', 'Client\Settings\PaymentController@update')->name('app/settings/payment/update');
        Route::post('delete/{id}', 'Client\Settings\PaymentController@delete')->name('app/settings/payment/delete');
    });

    Route::group(['prefix' => 'import'], function () {
        Route::get('/', 'Client\Settings\DumpController@index')->name('app/settings/import');
        Route::post('validate', 'Client\Settings\DumpController@import_validate')->name('app/settings/import/validate');
        Route::post('insert', 'Client\Settings\DumpController@import_insert')->name('app/settings/import/insert');
        Route::get('export', 'Client\Settings\DumpController@import_export')->name('app/settings/import/export');
    });

    Route::group(['prefix' => 'recovery'], function () {
        Route::get('/', 'Client\Settings\RecoveryController@index')->name('app/settings/recovery');
        Route::post('validate', 'Client\Settings\RecoveryController@validate')->name('app/settings/recovery/validate');
        Route::post('backup', 'Client\Settings\RecoveryController@backup')->name('app/settings/recovery/backup');
        Route::post('backup/delete', 'Client\Settings\RecoveryController@backup_delete')->name('app/settings/recovery/backup/delete');
        Route::post('restore', 'Client\Settings\RecoveryController@restore')->name('app/settings/recovery/restore');
        Route::post('reset', 'Client\Settings\RecoveryController@reset')->name('app/settings/recovery/reset');
    });

    Route::group(['prefix' => 'account'], function () {
        Route::post('reset', 'Client\Settings\AccountController@reset')->name('app/settings/account/reset');
    });


    Route::group(['prefix' => 'api'], function () {
        Route::get('/', 'Client\Settings\ApiController@index')->name('app/settings/api');
        Route::get('token', 'Client\Settings\ApiController@index')->name('app/settings/api/token');
        Route::post('token/create', 'Client\Settings\ApiController@token_create')->name('app/settings/api/token/create');
        Route::post('token/edit/{id}', 'Client\Settings\ApiController@token_edit')->name('app/settings/api/token/edit');
        Route::post('token/update', 'Client\Settings\ApiController@token_update')->name('app/settings/api/token/update');
        Route::post('token/delete/{id}', 'Client\Settings\ApiController@token_delete')->name('app/settings/api/token/delete');
    });

    Route::group(['prefix' => 'webhook'], function () {
        // Route::get('/', 'Client\Settings\WebhookController@index')->name('app/settings/webhook');
        Route::post('create', 'Client\Settings\WebhookController@create')->name('app/settings/webhook/create');
        Route::post('edit/{id}', 'Client\Settings\WebhookController@edit')->name('app/settings/webhook/edit');
        Route::post('update/{id}', 'Client\Settings\WebhookController@update')->name('app/settings/webhook/update');
        Route::post('delete/{id}', 'Client\Settings\WebhookController@delete')->name('app/settings/webhook/delete');
        Route::post('generate', 'Client\Settings\WebhookController@generate')->name('app/settings/webhook/generate');
    });

    Route::group(['prefix' => 'team'], function () {
        Route::get('/', 'Client\Settings\TeamController@index')->name('app/settings/team');
        Route::post('link', 'Client\Settings\TeamController@link')->name('app/settings/team/link');
        Route::post('unlink/{id}', 'Client\Settings\TeamController@unlink')->name('app/settings/team/unlink');
    });

    Route::group(['prefix' => 'marketplace'], function () {
        Route::get('/', 'Client\Settings\MarketplaceController@index')->name('app/settings/marketplace');
        Route::post('update', 'Client\Settings\MarketplaceController@update')->name('app/settings/marketplace/update');
    });

    Route::post('edit/{id}', 'Client\SettingsController@edit')->name('app/settings/edit');
    Route::post('update/{id}', 'Client\SettingsController@update')->name('app/settings/update');
    Route::post('get', 'Client\SettingsController@get')->name('app/settings/get');
});

Route::group([
    'prefix' => 'market',
    'namespace' => 'App\Http\Controllers\Public',
], function () {
    // Route::get('/', 'MarketplaceController@index')->name('app/marketplace/index');
    Route::post('buy', 'MarketplaceController@buy')->name('app/marketplace/buy');
    Route::get('checkout', 'MarketplaceController@checkout_show')->name('app/marketplace/checkout/show');
    Route::post('checkout', 'MarketplaceController@checkout_save')->name('app/marketplace/checkout/save');
    Route::get('{marketplace_token}', 'MarketplaceController@showcase')->name('app/marketplace/showcase');
});

Route::group([
    'prefix' => 'subshero-buzz',
    'namespace' => 'App\Http\Controllers\Public',
], function () {
    Route::get('/', 'BuzzController@index')->name('app/buzz/index');
});



Route::group([
    'prefix' => 'gateway',
    'namespace' => 'App\Http\Controllers\Public\Gateway',
], function () {
    Route::group(['prefix' => 'paypal'], function () {
        Route::get('initiate', 'PayPalController@initiate')->name('app/gateway/paypal/initiate');
        Route::any('notify', 'PayPalController@notify')->name('app/gateway/paypal/notify');
        Route::any('return', 'PayPalController@return')->name('app/gateway/paypal/return');
        Route::any('cancel', 'PayPalController@cancel')->name('app/gateway/paypal/cancel');
    });
});









Route::group([
    'prefix' => 'koolreport',
    'namespace' => 'App\Http\Controllers',
], function () {
    Route::post('subscription/get_drilldown_tooltip', 'Client\KoolReportController@get_drilldown_tooltip')->name('app/koolreport/subscription/get_drilldown_tooltip');
    Route::post('subscription/{chart_name}', 'Client\KoolReportController@subscription')->name('app/koolreport/subscription');
});



Route::group([
    'prefix' => 'notification',
    'namespace' => 'App\Http\Controllers',
], function () {
    Route::post('register', 'Client\NotificationController@register')->name('app/notification/register');
    Route::post('edit/{type}/{id}', 'Client\NotificationController@notification_edit')->name('app/notification/edit');
    Route::post('delete/{type}/{id}', 'Client\NotificationController@notification_delete')->name('app/notification/delete');
});








// Select2 routes
Route::group([
    'prefix' => 'select2',
    'namespace' => 'App\Http\Controllers',
], function () {
    Route::get('folder/search/{term?}', 'Client\Select2Controller@folder_search')->name('app/select2/folder/search');
    Route::get('product/search/{term?}', 'Client\Select2Controller@product_search')->name('app/select2/folder/product');
});


// DataTables routes
Route::group([
    'prefix' => 'datatable',
    'namespace' => 'App\Http\Controllers',
], function () {
    Route::post('subscription/{term?}', 'Client\DataTableController@subscription')->name('app/datatable/subscription');
});










// Admin panel routes
Route::group([
    'prefix' => 'admin',
    'namespace' => 'App\Http\Controllers',
], function () {

    Route::get('/', 'Admin\HomeController@index')->name('admin/index');
    Route::get('customer', 'Admin\CustomerController@index')->name('admin/customer');

    // Product routes
    Route::group(['prefix' => 'product'], function () {
        Route::get('/', 'Admin\ProductController@index')->name('admin/product');
        Route::get('user', 'Admin\ProductController@user_index')->name('admin/product/user');
        Route::post('create', 'Admin\ProductController@create')->name('admin/product/create');
        Route::post('edit/{id}', 'Admin\ProductController@edit')->name('admin/product/edit');
        Route::post('update/{id}', 'Admin\ProductController@update')->name('admin/product/update');
        Route::post('delete/{id}', 'Admin\ProductController@delete')->name('admin/product/delete');
        Route::post('search-logo', 'Admin\ProductController@searchLogo')->name('admin/product/search-logo');
        Route::post('download-logo', 'Admin\ProductController@downloadLogo')->name('admin/product/download-logo');

        Route::get('logos_and_favicons', 'Admin\LogosAndFaviconsImportController@index')->name('admin/product/logos_and_favicons');
        Route::get('check/favicons', 'Admin\LogosAndFaviconsImportController@check_favicons')->name('admin/product/check/favicons');

        Route::group(['prefix' => 'import'], function () {
            Route::get('/', 'Admin\ProductController@import_index')->name('admin/product/import');
            Route::get('export', 'Admin\ProductController@export')->name('admin/product/import/export');
            Route::post('load', 'Admin\ProductController@import_load')->name('admin/product/import/load');
            Route::post('validate', 'Admin\ProductController@import_validate')->name('admin/product/import/validate');
            Route::post('save', 'Admin\ProductController@import_save')->name('admin/product/import/save');
        });

        // DataTables routes
        Route::group(['prefix' => 'datatable'], function () {
            Route::post('index/{term?}', 'Admin\ProductController@datatable_index')->name('admin/product/datatable/index');
        });

        Route::group(['prefix' => 'entity'], function () {
            Route::post('create', 'Admin\ProductRelatedEntityController@create')->name("admin/product/entity/create");
        });

        Route::group(['prefix' => '{productRelatedEntity}', 'where' => ['productRelatedEntity' => 'type|category|platform']], function () {
            Route::get('/', 'Admin\ProductRelatedEntityController@index')->name("admin/product/productRelatedEntity");
            Route::post('edit/{id}', 'Admin\ProductRelatedEntityController@edit')->name("admin/product/productRelatedEntity/edit");
            Route::post('update/{id}', 'Admin\ProductRelatedEntityController@update')->name("admin/product/productRelatedEntity/update");
            Route::post('delete/{id}', 'Admin\ProductRelatedEntityController@delete')->name("admin/product/productRelatedEntity/delete");

            Route::group(['prefix' => 'datatable'], function () {
                Route::post('index', 'Admin\ProductRelatedEntityController@datatable_index')->name("admin/product/productRelatedEntity/datatable/index");
            });
        });

        Route::post('images/import', 'Admin\LogosAndFaviconsImportController@import')->name('admin/product/images/import');


        // Product -> Adapt routes
        Route::group([
            'prefix' => 'adapt',
            'namespace' => 'Admin\Product',
        ], function () {
            Route::get('/', 'AdaptController@index')->name('admin/product/adapt');
            Route::post('edit/{id}', 'AdaptController@edit')->name('admin/product/adapt/edit');
            Route::post('update/{id}', 'AdaptController@update')->name('admin/product/adapt/update');
            // Route::post('delete/{id}', 'AdaptController@delete')->name('admin/product/adapt/delete');

            // Product -> Adapt -> DataTables routes
            Route::group(['prefix' => 'datatable'], function () {
                Route::post('index/{term?}', 'AdaptController@datatable_index')->name('admin/product/adapt/datatable/index');
            });
        });
    });


    // Customer routes
    Route::group(['prefix' => 'customer'], function () {
        Route::get('/', 'Admin\CustomerController@index')->name('admin/customer');
        Route::post('create', 'Admin\CustomerController@create')->name('admin/customer/create');
        Route::post('edit/{id}', 'Admin\CustomerController@edit')->name('admin/customer/edit');
        Route::post('update/{id}', 'Admin\CustomerController@update')->name('admin/customer/update');
        Route::post('delete/{id}', 'Admin\CustomerController@delete')->name('admin/customer/delete');
    });


    // Admin settings routes
    Route::group(['prefix' => 'settings'], function () {
        // Route::get('/', 'Admin\SettingsController@smtp')->name('admin/settings');
        Route::get('/', 'Admin\EmailController@smtp')->name('admin/settings');
        Route::get('script', 'Admin\SettingsController@script')->name('admin/settings/script');
        Route::post('script/update', 'Admin\SettingsController@script_update')->name('admin/settings/script/update');

        Route::get('webhook', 'Admin\SettingsController@webhook')->name('admin/settings/webhook');
        Route::post('webhook/update', 'Admin\SettingsController@webhook_update')->name('admin/settings/webhook/update');
        Route::post('webhook/log/{id}', 'Admin\SettingsController@get_webhook_log')->name('admin/settings/webhook/log');

        Route::group(['prefix' => 'misc'], function () {
            Route::get('/', 'Admin\SettingsController@misc')->name('admin/settings/misc');
            Route::post('recaptcha/update', 'Admin\SettingsController@misc_recaptcha_update')->name('admin/settings/misc/recaptcha/update');
            Route::post('cdn/update', 'Admin\SettingsController@misc_cdn_update')->name('admin/settings/misc/cdn/update');
            Route::post('cron/update', 'Admin\SettingsController@misc_cron_update')->name('admin/settings/misc/cron/update');
            Route::post('gravitec/update', 'Admin\SettingsController@misc_gravitec_update')->name('admin/settings/misc/gravitec/update');
            Route::post('toggle/update', 'Admin\SettingsController@misc_toggle_update')->name('admin/settings/misc/toggle/update');
            Route::post('xeno/update', 'Admin\SettingsController@misc_xeno_update')->name('admin/settings/misc/xeno/update');
        });

        Route::group(['prefix' => 'email'], function () {
            Route::get('smtp', 'Admin\EmailController@smtp')->name('admin/settings/email/smtp');
            Route::post('smtp/update', 'Admin\EmailController@smtp_update')->name('admin/settings/email/smtp/update');
            Route::post('smtp/test', 'Admin\EmailController@smtp_test')->name('admin/settings/email/smtp/test');

            Route::get('template', 'Admin\EmailController@template')->name('admin/settings/email/template');
            Route::get('template/create', 'Admin\EmailController@template_create_show')->name('admin/settings/email/template/create');
            Route::post('template/create', 'Admin\EmailController@template_create')->name('admin/settings/email/template/create');

            Route::get('template/update/{id}', 'Admin\EmailController@template_update_show')->name('admin/settings/email/template/update');
            Route::post('template/update/{id}', 'Admin\EmailController@template_update')->name('admin/settings/email/template/update');
            Route::post('template/delete/{id}', 'Admin\EmailController@template_delete')->name('admin/settings/email/template/delete');

            Route::post('logs/user', 'Admin\EmailController@email_logs_user')->name('admin/settings/email/logs/user');
            Route::post('logs/delete_all', 'Admin\EmailController@email_logs_delete_all')->name('admin/settings/email/logs/delete_all');
            // Route::get('logs/admin', 'Admin\EmailController@email_logs_admin')->name('admin/settings/email/logs/admin');
        });

        Route::group(['prefix' => 'cron'], function () {
            Route::post('log/download', 'Admin\SettingsController@cron_log_download')->name('admin/settings/cron/log/download');
            Route::post('log/delete', 'Admin\SettingsController@cron_log_delete')->name('admin/settings/cron/log/delete');
        });

        Route::group(['prefix' => 'update'], function () {
            Route::get('/', 'Admin\UpdateController@index')->name('admin/settings/update');
            Route::get('check', 'Admin\UpdateController@check')->name('admin/settings/update/check');
            Route::get('download', 'Admin\UpdateController@download')->name('admin/settings/update/download');
            Route::post('handle', 'Admin\UpdateController@handle')->name('admin/settings/update/handle');
        });
    });
});











// Guest routes
// Route::get('logout', 'Auth\LoginController@logout')->name('logout');
Route::get('logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('login');
})->name('logout');
Route::get('maintenance', 'App\Http\Controllers\Controller@maintenance')->name('maintenance');

// User routes (not logged in)
Route::group([
    'prefix' => 'user',
    'namespace' => 'App\Http\Controllers',
], function () {
    Route::get('confirm/{token}', 'Auth\UserController@confirm_show')->name('user/confirm');
    Route::post('confirm/{token}', 'Auth\UserController@confirm_save')->name('user/confirm');

    Route::get('email/verify', 'Auth\UserController@email_verify_show')->name('user/email/verify');
    Route::post('email/verify', 'Auth\UserController@email_verify_send')->name('user/email/verify');
    Route::get('email/verify/{token}', 'Auth\UserController@email_verify_token')->name('user/email/verify/token');

    Route::get('team/invite/accept/{token}', 'Auth\UserController@team_invite_accept')->name('user/team/invite/accept');
});


Route::group([
    'prefix' => 'pdfparser',
    'namespace' => 'App\Http\Controllers',
], function() {
    Route::post('parse', 'Client\PdfParserController@parse_pdf')->name('pdfparser/parse');
});










// Console routes
Route::post('webhook/admin/v1', 'App\Http\Controllers\WebhookController@admin_v1')->name('webhook/admin/v1');
Route::post('webhook/user/data/{token}', 'App\Http\Controllers\Webhook\WebhookController@handle')->name('webhook/user/data');

// CronJob routes
Route::group(['prefix' => 'cron'], function () {
    Route::get('{type}/{token}', 'App\Http\Controllers\CronController@specific')->name('cron/specific');
});











Route::any('vendor/koolreport/{folder_path}/{file_name}', function ($folder_path, $file_name) {
    $content_types = [
        'css' => 'text/css',
        'js' => 'application/javascript',
    ];

    $path = base_path("vendor/koolreport/$folder_path/$file_name");

    if (!file_exists($path)) {
        abort(404);
    }

    $ext = pathinfo($path, PATHINFO_EXTENSION);

    if (isset($content_types[$ext])) {
        return Response::make(File::get($path), 200, [
            'Content-Type' => $content_types[$ext],
            'Content-Length' => File::size($path),
        ]);
    }

    abort(404);
})
    ->where('folder_path', '(.*)')
    ->where('file_name', '([a-zA-Z]+(\.[a-zA-Z]+)+)');



$content_types = [
    'css' => 'text/css',
    'js' => 'application/javascript',
];
foreach ([
    // Node.js modules for Grapecity/wijmo
    'node_modules/@grapecity',
    'node_modules/systemjs',
    'node_modules/systemjs-plugin-babel',
    'node_modules/systemjs-plugin-css',
    'node_modules/bootstrap',
    'node_modules/jszip',
] as $path) {
    Route::any("$path/{file_path}", function ($file_name) use ($path, $content_types) {
        $path = base_path("$path/$file_name");

        if (file_exists($path)) {
            $ext = pathinfo($path, PATHINFO_EXTENSION);

            if (isset($content_types[$ext])) {
                return Response::make(File::get($path), 200, [
                    'Content-Type' => $content_types[$ext],
                    'Content-Length' => File::size($path),
                ]);
            }
        }

        abort(404);
    })->where('file_path', '(.*)');
}


// Gravitec.net push notification files
Route::get('manifest.json', function () {
    $path = storage_path(DIR_GRAVITEC_PUSH . 'manifest.json');

    if (!File::exists($path)) {
        abort(404);
    }

    return Response::make(File::get($path), 200, [
        'Content-Type' => 'application/json',
        'Content-Length' => File::size($path),
    ]);
});
Route::get('push-worker.js', function () {
    $path = storage_path(DIR_GRAVITEC_PUSH . 'push-worker.js');

    if (!File::exists($path)) {
        abort(404);
    }

    return Response::make(File::get($path), 200, [
        'Content-Type' => 'application/javascript',
        'Content-Length' => File::size($path),
    ]);
});







// App function routes

// Route::fallback(function () {
//     return redirect()->to('/');
// });

// File load with exact path using -
Route::get('storage/{file_path}', function ($file_path) {
    $path = storage_path('app/' . base64_decode($file_path));

    if (!File::exists($path)) {
        abort(404);
    }

    $mimeType = '';
    if (File::extension($path) == 'csv') {
        $mimeType = 'text/csv';
    } else {
        $mimeType = File::mimeType($path);
    }

    return Response::make(File::get($path), 200, [
        'Content-Type' => $mimeType,
        'Content-Length' => File::size($path),
        'Content-Disposition' => 'inline; filename=" "',

        // Allow CORS policy
        'Access-Control-Allow-Origin' => '*',
    ]);
});


// CSRF routes
Route::get('csrf/get', function () {
    return csrf_token();
});


// Cache routes
Route::get('clear-cache', function () {
    $exitCode = Artisan::call('cache:clear');
    $exitCode = Artisan::call('view:clear');
    $exitCode = Artisan::call('config:cache');
    return true;
});







// Pre-defined Authentication Routes...
// Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
// Route::post('login', 'Auth\LoginController@login');
// Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// // Registration Routes...
// Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
// Route::post('register', 'Auth\RegisterController@register');

// // Password Reset Routes...
// Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
// Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
// Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
// Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');
