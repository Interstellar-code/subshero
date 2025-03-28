<?php

// -------------------------------- Global -> Constants --------------------------------

!defined('SUB_DImg') && define('SUB_DImg', 'system/subscription/default_image.png');
!defined('User_Default_Img') && define('User_Default_Img', 'system/user/default-profile-picture.jpg');
!defined('Favicon_Default_Img') && define('Favicon_Default_Img', 'assets/images/favicon.ico');
defined('APP_TIMEZONE') or define('APP_TIMEZONE', 'Europe/Amsterdam');
defined('APP_CURRENCY') or define('APP_CURRENCY', 'USD');
defined('LARAVEL_TIMEZONE') or define('LARAVEL_TIMEZONE', 'UTC');
defined('APP_TIMESTAMP_FORMAT') or define('APP_TIMESTAMP_FORMAT', 'Y-m-d H:i:s');
defined('PRODUCT_RESERVED_ID') or define('PRODUCT_RESERVED_ID', 10);
defined('CRON_TOKEN') or define('CRON_TOKEN', 'm3Fn5vGjr4');
defined('FREE_PLAN_ID') or define('FREE_PLAN_ID', 1);
defined('PRO_PLAN_ID') or define('PRO_PLAN_ID', 2);
defined('PRO_LIFETIME_PLAN_ID') or define('PRO_LIFETIME_PLAN_ID', 3);
defined('TEAM_PLAN_ALL_ID') or define('TEAM_PLAN_ALL_ID', [4, 7]);
defined('PRO_PLAN_ALL_ID') or define('PRO_PLAN_ALL_ID', [2, 3, 5, 6, 8]);
defined('LTD_PLAN_ALL_ID') or define('LTD_PLAN_ALL_ID', [5, 6, 7]);
defined('LTD_PLAN_ALL_EXCEPT_TEAM_ID') or define('LTD_PLAN_ALL_EXCEPT_TEAM_ID', [5, 6]);
defined('PRO_RECUR_PLAN_ID') or define('PRO_RECUR_PLAN_ID', 2);
defined('PRO_LTD_PLAN_ID') or define('PRO_LTD_PLAN_ID', 5);
defined('TEAM_RECUR_PLAN_ID') or define('TEAM_RECUR_PLAN_ID', 4);
defined('TEAM_LTD_PLAN_ID') or define('TEAM_LTD_PLAN_ID', 7);
defined('ADMIN_ROLE_ID') or define('ADMIN_ROLE_ID', 1);
defined('CLIENT_ROLE_ID') or define('CLIENT_ROLE_ID', 2);
defined('ALERT_SYSTEM_ID_ALL') or define('ALERT_SYSTEM_ID_ALL', [-1, 1]);
defined('DIR_GRAVITEC_PUSH') or define('DIR_GRAVITEC_PUSH', 'app/vendors/gravitec/push-notification/');

// Database -> Table -> Field Length
!defined('len') && define('len', [
    'products' => [
        'id' => 11,
        'category_id' => 11,
        'product_name' => 50,
        'brandname' => 50,
        'product_type' => 127,
        'pricing_type' => 4,
        'description' => 150,
        'url' => 100,
        'url_app' => 100,
        'image' => 100,
        'favicon' => 100,
        'status' => 1,
        'currency_code' => 3,
        'price1_name' => 20,
        'price1_value' => 10,
        'price2_name' => 20,
        'price2_value' => 10,
        'price3_name' => 20,
        'price3_value' => 10,
        'refund_days' => 999,
        'billing_frequency' => 40,
        'billing_cycle' => 4,
        'ltdval_price' => 10,
        'ltdval_frequency' => 40,
        'ltdval_cycle' => 4,
    ],


    'product_categories' => [
        'id' => 11,
        'user_id' => 11,
        'name' => 50,
        'status' => 1,
    ],


    'config' => [
        'id' => 11,
        'smtp_host' => 50,
        'smtp_port' => 5,
        'smtp_encryption' => 10,
        'smtp_username' => 50,
        'smtp_password' => 50,
        'smtp_sender_name' => 50,
        'smtp_sender_email' => 50,
        'webhook_key' => 50,
        'recaptcha_site_key' => 50,
        'recaptcha_secret_key' => 50,
        'cdn_base_url' => 50,
        'xeno_public_key' => 50,
        'gravitec_app_key' => 32,
        'gravitec_app_secret' => 32,
    ],


    'email_templates' => [
        'id' => 11,
        'user_id' => 11,
        'type' => 50,
        'subject' => 100,
        'body' => 16777215,
        'is_default' => 1,
    ],


    'users' => [
        'id' => 11,
        'role_id' => 11,
        'plan_id' => 11,
        'name' => 50,
        'first_name' => 50,
        'last_name' => 50,
        'country' => 5,
        'email' => 50,
        'email_verified_at' => 19,
        'password' => 255,
        'description' => 100,
        'image' => 255,
        'remember_token' => 100,
        'marketplace_status' => 1,
        'marketplace_token' => 100,
        'paypal_api_username' => 255,
        'paypal_api_password' => 255,
        'paypal_api_secret' => 255,
        'company_name' => 50,
        'facebook_username' => 50,
        'phone' => 20,
        'created_at' => 19,
        'created_by' => 11,
        'updated_at' => 19,
        'status' => 1,
    ],


    'users_teams' => [
        'id' => 11,
        'team_user_id' => 11,
        'pro_user_id' => 11,
        'created_at' => 19,
        'updated_at' => 19,
    ],


    'folder' => [
        'id' => 11,
        'user_id' => 11,
        'name' => 20,
        'color' => 10,
        'is_default' => 1,
        'created_at' => 19,
        'created_by' => 11,
    ],


    'subscriptions' => [
        'id' => 11,
        'user_id' => 11,
        'folder_id' => 11,
        'brand_id' => 11,
        'type' => 3,
        'image' => 100,
        'favicon' => 100,
        'product_name' => 255,
        'brandname' => 30,
        'product_type' => 1,
        'description' => 255,
        'price' => 10,
        'price_type' => 20,
        'recurring' => 1,
        'payment_date' => 10,
        'next_payment_date' => 10,
        'payment_date_upd' => 19,
        'expiry_date' => 10,
        'contract_expiry' => 10,
        'homepage' => 255,
        'pay_gateway_id' => 11,
        'note' => 255,
        'company_name' => 50,
        'discount_voucher' => 20,
        'payment_mode' => 20,
        'include_notes' => 1,
        'alert_type' => 1,
        'url' => 255,
        'support_details' => 255,
        'tags' => 255,
        'billing_frequency' => 1,
        'billing_cycle' => 1,
        'billing_type' => 1,
        'ltdval_price' => 10,
        'ltdval_frequency' => 1,
        'ltdval_cycle' => 1,
        'status' => 1,
        'timezone' => 255,
        'pricing_type' => 1,
        'currency_code' => 3,
        'refund_days' => 999,
        'refund_date' => 10,
        'base_value' => 10,
        'base_currency' => 3,
        'created_at' => 19,
        'created_by' => 1,
    ],


    'users_contacts' => [
        'id' => 11,
        'user_id' => 11,
        'name' => 50,
        'email' => 50,
        'status' => 1,
    ],


    'users_payment_methods' => [
        'id' => 11,
        'user_id' => 11,
        'payment_type' => 20,
        'name' => 20,
        'color' => 10,
        'description' => 100,
        'expiry' => 10,
    ],


    'users_profile' => [
        'id' => 11,
        'user_id' => 11,
        'timezone' => 50,
        'currency' => 10,
        'language' => 20,
        'billing_frequency' => 1,
        'billing_cycle' => 1,
        'payment_mode' => 20,
        'payment_mode_id' => 11,
    ],


    'users_alert' => [
        'id' => 11,
        'user_id' => 11,
        'time_period' => 32767,
        'time_cycle' => 1,
        'time' => 8,
        'alert_condition' => 1,
        'alert_contact' => 11,
        'alert_type' => 1,
        'alert_types' => [
            'array_length' => 5,
            'string_length' => 9,
        ],
        'alert_name' => 30,
        'timezone' => 50,
        'alert_subs_type' => 1,
    ],


    'personal_access_tokens' => [
        'id' => 11,
        'tokenable_type' => 11,
        'tokenable_id' => 20,
        'name' => 255,
        'secret_key' => 60,
        'token' => 64,
        'abilities' => 65535,
        'last_used_at' => 19,
        'created_at' => 19,
        'updated_at' => 19,
    ],


    'push_notification_registers' => [
        'id' => 11,
        'user_id' => 11,
        'service_provider' => 30,
        'auth' => 255,
        'browser' => 50,
        'endpoint' => 255,
        'lang' => 5,
        'p256dh' => 255,
        'reg_id' => 255,
        'subscription_spec' => 11,
        'subscription_strategy' => 11,
        'created_at' => 19,
        'updated_at' => 19,
    ],


    'event_browser_notify' => [
        'id' => 11,
        'user_id' => 11,
        'type' => 30,
        'title' => 255,
        'message' => 255,
        'icon' => 255,
        'image' => 255,
        'redirect_url' => 255,
        'buttons' => 65535,
        'status' => 1,
        'scheduled_at' => 19,
        'created_at' => 19,
        'updated_at' => 19,
    ],


    'webhooks' => [
        'id' => 11,
        'user_id' => 11,
        'type' => 1,
        'name' => 50,
        'endpoint_url' => 255,
        'token' => 40,
        'events' => 65535,
        'token' => 40,
        'status' => 1,
        'created_at' => 19,
        'updated_at' => 19,
    ],


    'tags' => [
        'id' => 11,
        'user_id' => 11,
        'name' => 20,
        'created_at' => 19,
        'updated_at' => 19,
    ],


    'subscription_cart' => [
        'id' => 11,
        'user_id' => 11,
        'subscription_id' => 11,
        'product_category_id' => 11,
        'product_platform_id' => 11,
        'product_description' => 65535,
        'product_name' => 50,
        'product_logo' => 255,
        'sale_price' => 10,
        'currency_code' => 3,
        'plan_name' => 50,
        'product_url' => 255,
        'sales_url' => 255,
        'notes' => 255,
        'created_at' => 19,
        'updated_at' => 19,
    ],


    'marketplace_orders' => [
        'id' => 11,
        'seller_user_id' => 11,
        'seller_paypal_api_username' => 255,
        'buyer_user_id' => 11,
        'buyer_first_name' => 50,
        'buyer_last_name' => 50,
        'buyer_email' => 50,
        'buyer_phone' => 30,
        'buyer_company_name' => 50,
        'buyer_country' => 10,
        'payment_method' => 10,
        'subscription_id' => 11,
        'product_id' => 11,
        'product_name' => 50,
        'sale_price' => 10,
        'currency_code' => 3,
        'subtotal' => 10,
        'charges' => 10,
        'total' => 10,
        'status' => 1,
        'created_at' => 19,
        'updated_at' => 19,
    ],


    'plan_coupons' => [
        'id' => 11,
        'user_id' => 11,
        'coupon' => 30,
        'status' => 1,
        'created_at' => 19,
        'updated_at' => 19,
    ],
]);
