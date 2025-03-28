<?php

namespace App\Library;

// use Illuminate\Database\Eloquent\Model;

use App\Models\ApiTokenModel;
use App\Models\BrandModel;
use App\Models\CurrencyModel;
use App\Models\FolderModel;
use App\Models\CustomerModel;
use App\Models\EmailModel;
use App\Models\EmailType;
use App\Models\KoolReportModel;
use App\Models\PlanModel;
use App\Models\ProductCategoryModel;
use App\Models\TemplateModel;
use App\Models\ProductModel;
use App\Models\ProductPlatformModel;
use App\Models\ProductTypeModel;
use App\Models\SettingsModel;
use App\Models\SubscriptionModel;
use App\Models\TagModel;
use App\Models\TeamModel;
use App\Models\UserAlert;
use App\Models\UserModel;
use App\Models\Webhook;
use App\Models\WebhookLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use stdClass;

class Application # extends Model
{
    // public static $table = Application_Table::class;
    // public static $config = Application_Config::class;

    /**
     * Create a new class instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->config = new Application_Config;
        $this->table = new Application_Table;
        $this->folder = new FolderModel();
        // $this->subscription = new SubscriptionModel();
        $this->subscription = new SubscriptionModel();
        $this->tag = new TagModel();
        $this->brand = new BrandModel();
        $this->user = new Application_User();
        // $this->table = new Application_Table;
        $this->prefer = new Application_Preference;
        $this->cron = new Application_CronJob;
        $this->do = new Application_Doable;
        $this->lang = new Application_Language;
        $this->cache = new Application_Cache;
        $this->regex = new Application_RegEx;
        $this->is = new Application_Is;
        $this->search = new Application_Search;
        $this->get = new Application_Get;


        $this->product = new ProductModel();
        $this->product->type = new ProductTypeModel();
        $this->product->category = new ProductCategoryModel();
        $this->product->platform = new ProductPlatformModel();
        $this->customer = new CustomerModel();
        $this->plan = new PlanModel();
        $this->settings = new SettingsModel();
        $this->settings->account = new Application_Settings_Account();
        $this->settings->email = new EmailModel();
        $this->settings->recovery = new Application_Settings_Recovery();
        // $this->email->template = new EmailTemplate();
        $this->email = new \stdClass();
        $this->email->type = new EmailType();
        $this->kr = new KoolReportModel();
        $this->api = new stdClass();
        $this->api->token = new ApiTokenModel();
        $this->team = new TeamModel();
        $this->webhook = new Webhook();
    }
}

class Application_Config
{
    private const path = [
        'currency' => 'json/currency.json',
        'country' => 'json/country.json',
        'state' => 'json/state.json',
        'timezone' => 'json/timezone.json',
        'language' => 'json/language.json',
    ];
    // public $currency = [];

    // Cron log files path
    public $cron_log_path_all = [
        'system/cron/schedule.log',
        'system/cron/mail.log',
        'system/cron/plan.log',
        'system/cron/report.log',
        'system/cron/misc.log',
        'system/cron/notification.log',
    ];

    public
        $currency = [],
        $currency_code = [],
        $currency_symbol = [],
        $country = [],
        $state = [],
        $timezone = [],
        $language = [],

        $smtp_port,
        $smtp_host,
        $smtp_encryption,
        $smtp_username,
        $smtp_password,
        $smtp_sender_name,
        $smtp_sender_email,
        $webhook_key,
        $recaptcha_status,
        $recaptcha_site_key,
        $recaptcha_secret_key,
        $cdn_base_url,
        $cron_misc_days,
        $paypal_environment;

    public function __construct()
    {
        $this->cron_log_path_all = array_flip($this->cron_log_path_all);


        // Load all json files
        foreach ($this::path as $key => $val) {
            $path = storage_path($val);
            if (file_exists($path)) {
                $this->{$key} = json_decode(file_get_contents($path), true);
            }
        }

        // Sort timezone list
        uasort($this->timezone, function ($a, $b) {
            return strcmp($a['display'], $b['display']);
        });

        // Sort currency list
        uasort($this->currency, function ($a, $b) {
            return strcmp($a['name'], $b['name']);
        });

        // Load all config data
        $config = SettingsModel::get();
        if (!empty($config)) {
            foreach ($config as $key => $val) {
                $this->{$key} = $val;
            }
        }

        // Separate all currency code
        if (!empty($this->currency)) {
            foreach ($this->currency as $val) {
                $this->currency_code[] = $val['code'];
                $this->currency_symbol[$val['code']] = $val['symbol'];
            }
        }
    }
}

class Application_Table
{
    public $subscription = [
        'type' => [
            1 => 'Subscription',
            2 => 'Trial',
            3 => 'Lifetime',
            4 => 'Revenue',
        ],
        'cycle' => [
            1 => 'Daily',
            2 => 'Weekly',
            3 => 'Monthly',
            4 => 'Yearly',
        ],
        'recurring' => [
            'No',
            'Yes',
        ],
    ];


    public function __construct()
    {
        $this->subscription = [
            'type' => [
                // __('Unknown'),
                __('Subscription'),
                __('Trial'),
                __('Lifetime'),
                __('Revenue'),
            ],
            'cycle' => [
                // __('Unknown'),
                __('Daily'),
                __('Weekly'),
                __('Monthly'),
                __('Yearly'),
            ],
            'recurring' => [
                __('No'),
                __('Yes'),
            ],
            'price' => [
                'type' => [
                    'USD' => 'USD',
                    'EUR' => 'EUR',
                    'INR' => 'INR',
                ],
            ],
        ];
    }
}

class Application_Folder
{
    public $currency = [];

    public function __construct()
    {
        $path = storage_path('json/currency.json');
        if (file_exists($path)) {
            $this->currency = json_decode(file_get_contents($path), true);
        }
    }
}

class Application_User extends UserModel
{
    public $me, $profile, $plan, $alert_preference, $payments, $contacts, $tags, $timezone, $tour_status, $default;

    public function __construct()
    {
        $this->me = UserModel::me();
        $this->alert_preference = UserModel::get_alert_preference();
        $this->alert = new UserAlert();
        $this->profile = UserModel::get_profile();
        $this->plan = UserModel::get_plan();
        $this->payment_methods = UserModel::get_payments();
        $this->contacts = UserModel::get_contacts();
        $this->tags = UserModel::get_tags();
        $this->tour_status = UserModel::get_tour_status();

        $this->init();
    }

    public function init()
    {
        $this->default = new \stdClass();
        $this->default->folder_id = 0;
        $this->default->payment_mode = null;

        if (empty($this->me->id)) {
            return false;
        }

        // Set default values
        if (!empty($this->profile->id)) {
            $this->default->timezone_value = $this->profile->timezone;
            $this->default->currency_code = $this->profile->currency;
            $this->default->language_name = $this->profile->language;
            $this->default->billing_frequency = $this->profile->billing_frequency;
            $this->default->billing_cycle = $this->profile->billing_cycle;
            $this->default->payment_mode = $this->profile->payment_mode;
            $this->default->payment_mode_id = $this->profile->payment_mode_id;
        }


        $this->default->folder_id = FolderModel::get_default_folder_id($this->me->id);
    }

    public function get_timezone()
    {
        if (empty($this->timezone)) {
            $user_profile = UserModel::get_profile();
            $this->timezone = 'UTC';

            if (!empty($user_profile)) {
                $this->timezone = $user_profile->timezone;
                // date_default_timezone_set($user_profile->timezone);
            }
        }

        return $this->timezone;
    }
}

class Application_Preference
{
    public $currency = 'USD';
    public $timezone = 'UTC';
    public $language = 'English';
    public $billing_type = 1;

    public function __construct()
    {
        // Load user preferences
        $user_id = Auth::id();
        $user = UserModel::get($user_id);
        $users_profile = UserModel::get_profile();

        if (!empty($users_profile)) {
            $this->currency = $users_profile->currency;
            $this->timezone = $users_profile->timezone;
            $this->language = $users_profile->language;
            $this->billing_type = $users_profile->billing_type;
        }
    }
}

class Application_CronJob
{

    public function __construct()
    {
    }

    public function get_all_log_files()
    {
        $all_files = Storage::disk('local')->files('system/cron');
        $data = [];

        // Check path
        foreach ($all_files as $val) {
            if (isset(lib()->config->cron_log_path_all[$val])) {
                $data[] = $val;
            }
        }

        return $data;
    }
}


class Application_Settings_Account
{
    public $all_command = [];

    public function __construct()
    {

        // Command definition
        $this->all_command = [
            'get_account_info' => [
                'message' => [
                    'request' => __('Searching your account information...'),
                    'success' => __('Found your account information.'),
                    'failure' => __('Failed to load your account information.'),
                ],
                'next_command' => 'delete_subscription',
            ],
            'delete_subscription' => [
                'message' => [
                    'request' => null,
                    'success' => __('Deleting subscription: '),
                    'complete' => __('Successfully deleted your subscriptions.'),
                    'failure' => __('Failed to delete your subscription.'),
                ],
                'next_command' => 'delete_folder',
            ],
            'delete_folder' => [
                'message' => [
                    'request' => null,
                    'success' => __('Deleting folder: '),
                    'complete' => __('Successfully deleted your folders.'),
                    'failure' => __('Failed to delete your folder.'),
                ],
                'next_command' => 'delete_tag',
            ],
            'delete_tag' => [
                'message' => [
                    'request' => null,
                    'success' => __('Deleting tag: '),
                    'complete' => __('Successfully deleted your tags.'),
                    'failure' => __('Failed to delete your tag.'),
                ],
                'next_command' => 'delete_payment_method',
            ],
            'delete_payment_method' => [
                'message' => [
                    'request' => null,
                    'success' => __('Deleting payment method: '),
                    'complete' => __('Successfully deleted your payment methods.'),
                    'failure' => __('Failed to delete your payment method.'),
                ],
                'next_command' => 'delete_contact',
            ],
            'delete_contact' => [
                'message' => [
                    'request' => null,
                    'success' => __('Deleting contact: '),
                    'complete' => __('Successfully deleted your contacts.'),
                    'failure' => __('Failed to delete your contact.'),
                ],
                'next_command' => 'delete_alert_profile',
            ],
            'delete_alert_profile' => [
                'message' => [
                    'request' => null,
                    'success' => __('Deleting alert profile: '),
                    'complete' => __('Successfully deleted your profiles.'),
                    'failure' => __('Failed to delete your alert profile.'),
                ],
                'next_command' => 'delete_api_token',
            ],
            'delete_api_token' => [
                'message' => [
                    'request' => null,
                    'success' => __('Deleting API token: '),
                    'complete' => __('Successfully deleted your API tokens.'),
                    'failure' => __('Failed to delete your API token.'),
                ],
                'next_command' => 'delete_webhook',
            ],
            'delete_webhook' => [
                'message' => [
                    'request' => null,
                    'success' => __('Deleting webhook: '),
                    'complete' => __('Successfully deleted your webhooks.'),
                    'failure' => __('Failed to delete your webhook.'),
                ],
                'next_command' => 'delete_team_account',
            ],
            'delete_team_account' => [
                'message' => [
                    'request' => null,
                    'success' => __('Deleting team account: '),
                    'complete' => __('Successfully deleted your team accounts.'),
                    'failure' => __('Failed to delete your team account.'),
                ],
                'next_command' => 'reset_success',
            ],
            'delete_file_all' => [
                'message' => [
                    'request' => __('Deleting all of your files.'),
                    'success' => __('Successfully deleted all of your files.'),
                    'complete' => __('Successfully deleted all of your files.'),
                    'failure' => __('Failed to delete your files.'),
                ],
                'next_command' => 'reset_success',
            ],
            'reset_success' => [
                'message' => [
                    'request' => __(''),
                    'success' => __('Account reset completed successfully.'),
                    'complete' => __('Account reset completed successfully.'),
                    'failure' => __('Failed to reset your account.'),
                ],
                'next_command' => '',
            ],
        ];
    }
}


class Application_Settings_Recovery
{
    public $backup_commands = [];
    public $restore_commands = [];

    public function __construct()
    {
        // Command definition
        $this->backup_commands = [
            'check_existence' => [
                'message' => [
                    'request' => __('Searching for existing backup files...'),
                    'success' => __('We found your backup files.'),
                    'failure' => __('Failed to your search backup files.'),
                    'found' => __('We found your old backup files.'),
                    'not_found' => __('No backup files found.'),
                    'backup_in_progress' => __('Another backup in progress, please try again in a few minutes.'),
                    'restore_in_progress' => __('Restore in progress, please try again in a few minutes.'),
                ],
                'color' => [
                    'default' => '#343a40',
                ],
                'next_command' => 'delete_backup',
            ],
            'delete_backup' => [
                'message' => [
                    'request' => __('Deleting old backup files...'),
                    'success' => __('Successfully deleted your old backup files.'),
                    'failure' => __('Failed to delete your backup files.'),
                    'not_found' => __('No backup files found.'),
                ],
                'color' => [
                    'default' => '#343a40',
                ],
                'next_command' => 'get_account_info',
            ],
            'get_account_info' => [
                'message' => [
                    'request' => __('Searching for your account information...'),
                    'success' => __('Found your account information.'),
                    'failure' => __('Failed to load your account information.'),
                ],
                'color' => [
                    'default' => '#343a40',
                ],
                'next_command' => 'backup_subscription',
            ],
            'backup_subscription' => [
                'message' => [
                    'request' => __('Taking backup of your subscriptions...'),
                    'success' => __('Subscription backed up: '),
                    'complete' => __('Successfully backed up your subscriptions.'),
                    'failure' => __('Failed to backup your subscription.'),
                ],
                'color' => [
                    'default' => '#fdd835',
                ],
                'next_command' => 'backup_folder',
            ],
            'backup_folder' => [
                'message' => [
                    'request' => __('Taking backup of your folders...'),
                    'success' => __('Folder backed up: '),
                    'complete' => __('Successfully backed up your folders.'),
                    'failure' => __('Failed to backup your folder.'),
                ],
                'color' => [
                    'default' => '#00bcd4',
                ],
                'next_command' => 'backup_tag',
            ],
            'backup_tag' => [
                'message' => [
                    'request' => __('Taking backup of your tags...'),
                    'success' => __('Tag backed up: '),
                    'complete' => __('Successfully backed up your tags.'),
                    'failure' => __('Failed to backup your tag.'),
                ],
                'color' => [
                    'default' => '#4527a0',
                ],
                'next_command' => 'backup_payment_method',
            ],
            'backup_payment_method' => [
                'message' => [
                    'request' => __('Taking backup of your payment methods...'),
                    'success' => __('Payment method backed up: '),
                    'complete' => __('Successfully backed up your payment methods.'),
                    'failure' => __('Failed to backup your payment method.'),
                ],
                'color' => [
                    'default' => '#ff5722',
                ],
                'next_command' => 'backup_contact',
            ],
            'backup_contact' => [
                'message' => [
                    'request' => __('Taking backup of your contacts...'),
                    'success' => __('Contact backed up: '),
                    'complete' => __('Successfully backed up your contacts.'),
                    'failure' => __('Failed to backup your contact.'),
                ],
                'color' => [
                    'default' => '#2196f3',
                ],
                'next_command' => 'backup_alert_profile',
            ],
            'backup_alert_profile' => [
                'message' => [
                    'request' => __('Taking backup of your alert profiles...'),
                    'success' => __('Alert profile backed up: '),
                    'complete' => __('Successfully backed up your alert profiles.'),
                    'failure' => __('Failed to backup your alert profile.'),
                ],
                'color' => [
                    'default' => '#3e2723',
                ],
                'next_command' => 'backup_api_token',
            ],
            'backup_api_token' => [
                'message' => [
                    'request' => __('Taking backup of your API tokens...'),
                    'success' => __('API token backed up: '),
                    'complete' => __('Successfully backed up your API tokens.'),
                    'failure' => __('Failed to backup your API token.'),
                ],
                'color' => [
                    'default' => 'yellow',
                ],
                'next_command' => 'backup_webhook',
            ],
            'backup_webhook' => [
                'message' => [
                    'request' => __('Taking backup of your webhooks...'),
                    'success' => __('Webhook backed up: '),
                    'complete' => __('Successfully backed up your webhooks.'),
                    'failure' => __('Failed to backup your webhook.'),
                ],
                'color' => [
                    'default' => 'green',
                ],
                'next_command' => 'backup_file_all',
            ],
            // 'backup_team_account' => [
            //     'message' => [
            //         'request' => __('Taking backup of your team accounts...'),
            //         'success' => __('Team account backed up: '),
            //         'complete' => __('Successfully backed up your team accounts.'),
            //         'failure' => __('Failed to backup your team account.'),
            //     ],
            //     'color' => [
            //         'default' => 'blue',
            //     ],
            //     'next_command' => 'backup_file_all',
            // ],
            'backup_file_all' => [
                'message' => [
                    'request' => __('Taking backup of your files...'),
                    'success' => __('All of your files backed up.'),
                    'complete' => __('Successfully backed up your files.'),
                    'failure' => __('Failed to backup your files.'),
                ],
                'color' => [
                    'default' => '#343a40',
                ],
                'next_command' => 'backup_settings',
            ],
            'backup_settings' => [
                'message' => [
                    'request' => __('Taking backup of your settings...'),
                    'success' => __('Settings backed up'),
                    'complete' => __('Successfully backed up your settings.'),
                    'failure' => __('Failed to backup your settings.'),
                ],
                'color' => [
                    'default' => 'green',
                ],
                'next_command' => 'backup_success',
            ],
            'backup_success' => [
                'message' => [
                    'request' => __(''),
                    'success' => __('Backup completed successfully.'),
                    'complete' => __('Your backup was completed in '),
                    'failure' => __('Failed to backup your data.'),
                ],
                'color' => [
                    'default' => '#343a40',
                ],
                'next_command' => '',
            ],
        ];


        $this->restore_commands = [
            // Validate commands
            'check_existence' => [
                'message' => [
                    'request' => __('Searching for existing backup files...'),
                    'success' => __('We found your backup files.'),
                    'failure' => __('Failed to your search backup files.'),
                    'found' => __('We found your old backup files.'),
                    'not_found' => __('No backup files found.'),
                    'corrupted' => __('Your existing backup files are corrupted, please take a new backup.'),
                ],
                'color' => [
                    'default' => '#343a40',
                ],
                'next_command' => 'get_account_info',
            ],
            'restore_validate' => [
                'message' => [
                    'request' => __('Validating your existing backup files...'),
                    'success' => __('Backup files validation successful.'),
                    'complete' => __('Successfully validated your backup files.'),
                    'failure' => __('Failed to validate your backup files.'),
                ],
                'color' => [
                    'default' => '#343a40',
                ],
                'next_command' => 'get_account_info',
            ],

            // Reset commands
            'get_account_info' => [
                'message' => [
                    'request' => __('Searching your account information...'),
                    'success' => __('Found your account information.'),
                    'failure' => __('Failed to load your account information.'),
                ],
                'color' => [
                    'default' => '#343a40',
                ],
                'next_command' => 'delete_subscription',
            ],
            'delete_subscription' => [
                'message' => [
                    'request' => __('Deleting your subscriptions...'),
                    'success' => __('Deleted your subscription: '),
                    'complete' => __('Successfully deleted your subscriptions.'),
                    'failure' => __('Failed to delete your subscription.'),
                ],
                'color' => [
                    'default' => '#fdd835',
                ],
                'next_command' => 'delete_folder',
            ],
            'delete_folder' => [
                'message' => [
                    'request' => __('Deleting your folders...'),
                    'success' => __('Deleted your folder: '),
                    'complete' => __('Successfully deleted your folders.'),
                    'failure' => __('Failed to delete your folder.'),
                ],
                'color' => [
                    'default' => '#00bcd4',
                ],
                'next_command' => 'delete_tag',
            ],
            'delete_tag' => [
                'message' => [
                    'request' => __('Deleting your tags...'),
                    'success' => __('Deleted your tag: '),
                    'complete' => __('Successfully deleted your tags.'),
                    'failure' => __('Failed to delete your tag.'),
                ],
                'color' => [
                    'default' => '#4527a0',
                ],
                'next_command' => 'delete_payment_method',
            ],
            'delete_payment_method' => [
                'message' => [
                    'request' => __('Deleting your payment methods...'),
                    'success' => __('Deleted your payment method: '),
                    'complete' => __('Successfully deleted your payment methods.'),
                    'failure' => __('Failed to delete your payment method.'),
                ],
                'color' => [
                    'default' => '#ff5722',
                ],
                'next_command' => 'delete_contact',
            ],
            'delete_contact' => [
                'message' => [
                    'request' => __('Deleting your contacts...'),
                    'success' => __('Deleted your contact: '),
                    'complete' => __('Successfully deleted your contacts.'),
                    'failure' => __('Failed to delete your contact.'),
                ],
                'color' => [
                    'default' => '#2196f3',
                ],
                'next_command' => 'delete_alert_profile',
            ],
            'delete_alert_profile' => [
                'message' => [
                    'request' => __('Deleting your alert profiles...'),
                    'success' => __('Deleted your alert profile: '),
                    'complete' => __('Successfully deleted your profiles.'),
                    'failure' => __('Failed to delete your alert profile.'),
                ],
                'color' => [
                    'default' => '#3e2723',
                ],
                'next_command' => 'delete_api_token',
            ],
            'delete_api_token' => [
                'message' => [
                    'request' => __('Deleting your API tokens...'),
                    'success' => __('Deleted your API token: '),
                    'complete' => __('Successfully deleted your API tokens.'),
                    'failure' => __('Failed to delete your API token.'),
                ],
                'color' => [
                    'default' => 'yellow',
                ],
                'next_command' => 'delete_webhook',
            ],
            'delete_webhook' => [
                'message' => [
                    'request' => __('Deleting your webhooks...'),
                    'success' => __('Deleted your webhook: '),
                    'complete' => __('Successfully deleted your webhooks.'),
                    'failure' => __('Failed to delete your webhook.'),
                ],
                'color' => [
                    'default' => 'green',
                ],
                'next_command' => 'reset_success',
            ],
            'delete_file_all' => [
                'message' => [
                    'request' => __('Deleting all of your files.'),
                    'success' => __('All of your files deleted.'),
                    'complete' => __('Successfully deleted all of your files.'),
                    'failure' => __('Failed to delete your files.'),
                ],
                'color' => [
                    'default' => '#343a40',
                ],
                'next_command' => 'reset_success',
            ],
            'reset_success' => [
                'message' => [
                    'request' => __(''),
                    'success' => __('Account reset completed successfully.'),
                    'complete' => __('Account reset completed successfully.'),
                    'failure' => __('Failed to reset your account.'),
                ],
                'color' => [
                    'default' => '#343a40',
                ],
                'next_command' => 'get_backup_info',
            ],


            // Restore commands
            'get_backup_info' => [
                'message' => [
                    'request' => __('Loading your backup files...'),
                    'success' => __('Your backup files are loaded into memory.'),
                    'failure' => __('No backup files found.'),
                ],
                'color' => [
                    'default' => '#343a40',
                ],
                'next_command' => 'restore_file_all',
            ],
            'restore_file_all' => [
                'message' => [
                    'request' => __('Restoring all of your files.'),
                    'success' => __('All of your files restored.'),
                    'complete' => __('Successfully restored all of your files.'),
                    'failure' => __('Failed to restore your files.'),
                ],
                'color' => [
                    'default' => 'green',
                ],
                'next_command' => 'restore_subscription',
            ],
            'restore_subscription' => [
                'message' => [
                    'request' => __('Restoring your subscriptions...'),
                    'success' => __('Subscription restored: '),
                    'complete' => __('Successfully restored your subscriptions.'),
                    'failure' => __('Failed to restore your subscription.'),
                ],
                'color' => [
                    'default' => '#fdd835',
                ],
                'next_command' => 'restore_folder',
            ],
            'restore_folder' => [
                'message' => [
                    'request' => __('Restoring your folders...'),
                    'success' => __('Folder restored: '),
                    'complete' => __('Successfully restored your folders.'),
                    'failure' => __('Failed to restore your folder.'),
                ],
                'color' => [
                    'default' => '#00bcd4',
                ],
                'next_command' => 'restore_tag',
            ],
            'restore_tag' => [
                'message' => [
                    'request' => __('Restoring your tags...'),
                    'success' => __('Tag restored: '),
                    'complete' => __('Successfully restored your tags.'),
                    'failure' => __('Failed to restore your tag.'),
                ],
                'color' => [
                    'default' => '#4527a0',
                ],
                'next_command' => 'restore_payment_method',
            ],
            'restore_payment_method' => [
                'message' => [
                    'request' => __('Restoring your payment methods...'),
                    'success' => __('Payment method restored: '),
                    'complete' => __('Successfully restored your payment methods.'),
                    'failure' => __('Failed to restore your payment method.'),
                ],
                'color' => [
                    'default' => '#ff5722',
                ],
                'next_command' => 'restore_contact',
            ],
            'restore_contact' => [
                'message' => [
                    'request' => __('Restoring your contacts...'),
                    'success' => __('Contact restored: '),
                    'complete' => __('Successfully restored your contacts.'),
                    'failure' => __('Failed to restore your contact.'),
                ],
                'color' => [
                    'default' => '#2196f3',
                ],
                'next_command' => 'restore_alert_profile',
            ],
            'restore_alert_profile' => [
                'message' => [
                    'request' => __('Restoring your alert profiles...'),
                    'success' => __('Alert profile restored: '),
                    'complete' => __('Successfully restored your profiles.'),
                    'failure' => __('Failed to restore your alert profile.'),
                ],
                'color' => [
                    'default' => '#3e2723',
                ],
                'next_command' => 'restore_api_token',
            ],
            'restore_api_token' => [
                'message' => [
                    'request' => __('Restoring your API tokens...'),
                    'success' => __('API token restored: '),
                    'complete' => __('Successfully restored your API tokens.'),
                    'failure' => __('Failed to restore your API token.'),
                ],
                'color' => [
                    'default' => 'yellow',
                ],
                'next_command' => 'restore_webhook',
            ],
            'restore_webhook' => [
                'message' => [
                    'request' => __('Restoring your webhooks...'),
                    'success' => __('Webhook restored: '),
                    'complete' => __('Successfully restored your webhooks.'),
                    'failure' => __('Failed to restore your webhook.'),
                ],
                'color' => [
                    'default' => 'green',
                ],
                'next_command' => 'restore_settings',
            ],
            'restore_settings' => [
                'message' => [
                    'request' => __('Restoring your settings...'),
                    'success' => __('Settings restored'),
                    'complete' => __('Successfully restored your settings.'),
                    'failure' => __('Failed to restore your settings.'),
                ],
                'color' => [
                    'default' => 'green',
                ],
                'next_command' => 'restore_success',
            ],
            'restore_success' => [
                'message' => [
                    'request' => __(''),
                    'success' => __('Restore completed successfully.'),
                    'complete' => __('Your restore was completed in '),
                    'failure' => __('Failed to restore your data.'),
                ],
                'color' => [
                    'default' => 'green',
                ],
                'next_command' => '',
            ],
        ];


        $this->reset_commands = [
            'get_account_info' => [
                'message' => [
                    'request' => __('Searching your account information...'),
                    'success' => __('Found your account information.'),
                    'failure' => __('Failed to load your account information.'),
                ],
                'color' => [
                    'default' => '#343a40',
                ],
                'next_command' => 'delete_subscription',
            ],
            'delete_subscription' => [
                'message' => [
                    'request' => null,
                    'success' => __('Deleting subscription: '),
                    'complete' => __('Successfully deleted your subscriptions.'),
                    'failure' => __('Failed to delete your subscription.'),
                ],
                'color' => [
                    'default' => '#fdd835',
                ],
                'next_command' => 'delete_folder',
            ],
            'delete_folder' => [
                'message' => [
                    'request' => null,
                    'success' => __('Deleting folder: '),
                    'complete' => __('Successfully deleted your folders.'),
                    'failure' => __('Failed to delete your folder.'),
                ],
                'color' => [
                    'default' => '#00bcd4',
                ],
                'next_command' => 'delete_tag',
            ],
            'delete_tag' => [
                'message' => [
                    'request' => null,
                    'success' => __('Deleting tag: '),
                    'complete' => __('Successfully deleted your tags.'),
                    'failure' => __('Failed to delete your tag.'),
                ],
                'color' => [
                    'default' => '#4527a0',
                ],
                'next_command' => 'delete_payment_method',
            ],
            'delete_payment_method' => [
                'message' => [
                    'request' => null,
                    'success' => __('Deleting payment method: '),
                    'complete' => __('Successfully deleted your payment methods.'),
                    'failure' => __('Failed to delete your payment method.'),
                ],
                'color' => [
                    'default' => '#ff5722',
                ],
                'next_command' => 'delete_contact',
            ],
            'delete_contact' => [
                'message' => [
                    'request' => null,
                    'success' => __('Deleting contact: '),
                    'complete' => __('Successfully deleted your contacts.'),
                    'failure' => __('Failed to delete your contact.'),
                ],
                'color' => [
                    'default' => '#2196f3',
                ],
                'next_command' => 'delete_alert_profile',
            ],
            'delete_alert_profile' => [
                'message' => [
                    'request' => null,
                    'success' => __('Deleting alert profile: '),
                    'complete' => __('Successfully deleted your profiles.'),
                    'failure' => __('Failed to delete your alert profile.'),
                ],
                'color' => [
                    'default' => '#3e2723',
                ],
                'next_command' => 'delete_api_token',
            ],
            'delete_api_token' => [
                'message' => [
                    'request' => null,
                    'success' => __('Deleting API token: '),
                    'complete' => __('Successfully deleted your API tokens.'),
                    'failure' => __('Failed to delete your API token.'),
                ],
                'color' => [
                    'default' => 'yellow',
                ],
                'next_command' => 'delete_webhook',
            ],
            'delete_webhook' => [
                'message' => [
                    'request' => null,
                    'success' => __('Deleting webhook: '),
                    'complete' => __('Successfully deleted your webhooks.'),
                    'failure' => __('Failed to delete your webhook.'),
                ],
                'color' => [
                    'default' => 'green',
                ],
                'next_command' => 'delete_file_all',
            ],
            'delete_file_all' => [
                'message' => [
                    'request' => __('Deleting all of your files.'),
                    'success' => __('Successfully deleted all of your files.'),
                    'complete' => __('Successfully deleted all of your files.'),
                    'failure' => __('Failed to delete your files.'),
                ],
                'color' => [
                    'default' => '#343a40',
                ],
                'next_command' => 'reset_success',
            ],
            'reset_success' => [
                'message' => [
                    'request' => __(''),
                    'success' => __('Account reset completed successfully.'),
                    'complete' => __('Account reset completed successfully.'),
                    'failure' => __('Failed to reset your account.'),
                ],
                'color' => [
                    'default' => '#343a40',
                ],
                'next_command' => '',
            ],
        ];
    }
}

class Application_Doable
{

    public function __construct()
    {
    }

    public function timezone_convert($data = [])
    {
        // $to_timezone = null, $date_time = null, $from_timezone = 'UTC', $return_format = 'Y-m-d H:i:s'


        // Set current date time
        if (empty($data['date_time'])) {
            $data['date_time'] = date('Y-m-d H:i:s');
        }

        if (empty($data['to_timezone'])) {
            $data['to_timezone'] = lib()->user->get_timezone();
        }

        if (empty($data['from_timezone'])) {
            $data['from_timezone'] = date_default_timezone_get();
        }

        if (empty($data['return_format'])) {
            $data['return_format'] = 'Y-m-d H:i:s';
        }

        $date = new \DateTime($data['date_time'], new \DateTimeZone($data['from_timezone']));
        $date->setTimezone(new \DateTimeZone($data['to_timezone']));
        return $date->format($data['return_format']);
    }

    public function get_script_header()
    {
        if (Storage::disk('local')->exists('system/script/header')) {
            return Storage::disk('local')->get('system/script/header');
        }
        return null;
    }

    public function get_script_footer()
    {
        if (Storage::disk('local')->exists('system/script/footer')) {
            return Storage::disk('local')->get('system/script/footer');
        }
        return null;
    }

    public function get_script_css()
    {
        if (Storage::disk('local')->exists('system/script/css')) {
            return Storage::disk('local')->get('system/script/css');
        }
        return null;
    }

    public function get_limit_msg(Request $request, $type)
    {
        return Response::json([
            'status' => false,
            'message' => __('Your limit has been reached. Please upgrade your plan.'),
        ], 200);
    }

    public function get_limit_msg_api(Request $request, $type)
    {
        return Response::json([
            'message' => 'Your limit has been reached. Please upgrade your plan.',
        ], 400);
    }

    public function recaptcha_rules(array $rules)
    {
        if (!isset($rules['g-recaptcha-response']) && lib()->config->recaptcha_status) {
            $rules['g-recaptcha-response'] = 'required|recaptcha';
        }

        return $rules;
    }

    public function currency_convert($value, $from_currency_code)
    {
        // Invalid value check
        if ($value <= 0) {
            return (float)$value;
        }

        // Check same currency
        else if ($from_currency_code == APP_CURRENCY) {
            return (float)$value;
        }

        $currency_conversion = CurrencyModel::get_by_code($from_currency_code);

        if (empty($currency_conversion)) {
            return (float)$value;
        } else {
            return (float)$value * $currency_conversion->conversion_rate;
        }
    }

    public function get_currency_symbol($currency_code)
    {
        if (isset(lib()->config->currency_symbol[$currency_code])) {
            return lib()->config->currency_symbol[$currency_code];
        }

        return $currency_code;
    }

    public function json_response(bool $status, string $message = null, int $status_code = 410)
    {
        if (empty($message)) {
            $message = $status ? 'Success' : 'Failure';
        }

        // Send response
        if (request()->ajax()) {
            return Response::json([
                'status' => $status,
                'message' => $message,
            ], $status_code);
        } else {
            return back();
        }
    }

    public function cycle_convert($number_of_days)
    {
        $cycle = 1;

        // Calculate
        if ($number_of_days > 0) {

            // Year
            if (is_int($number_of_days / 365)) {
                $cycle = 4;
                $number_of_days = $number_of_days / 365;
            }

            // Month
            else if (is_int($number_of_days / 30)) {
                $cycle = 3;
                $number_of_days = $number_of_days / 30;
            }

            // Week
            else if (is_int($number_of_days / 7)) {
                $cycle = 2;
                $number_of_days = $number_of_days / 7;
            }

            // Days
            else {
                $cycle = 1;
            }
        }

        return [
            'cycle' => $cycle,
            'frequency' => $number_of_days,
        ];
    }

    public function get_cycle_frequency($cycle, $frequency)
    {
        // 1=Daily, 2=Weekly, 3=Monthly, 4=Yearly
        switch ($cycle) {

                // Week
            case 2:
                $frequency = $frequency * 7;
                break;

                // Month
            case 3:
                $frequency = $frequency * 30;
                break;

                // Year
            case 4:
                $frequency = $frequency * 365;
                break;
        }

        return $frequency;
    }

    public function get_alert_profile_system_default()
    {
        return DB::table('users_alert')
            ->where('id', 1)
            ->where('user_id', 0)
            ->get()
            ->first();
    }

    public function get_alert_profile_system_default_ltd()
    {
        return DB::table('users_alert')
            ->where('id', -1)
            ->where('user_id', 0)
            ->get()
            ->first();
    }

    public function send_push_notification($data)
    {
        // Check if push notification is enabled
        if (!lib()->config->gravitec_status) {
            return false;
        }

        if (empty($data['icon'])) {
            $data['icon'] = asset_ver('assets/images/favicon.ico');
        } else {
            $data['icon'] = img_url($data['icon']);
        }

        try {
            // Send push notification
            $endpoint = 'https://uapi.gravitec.net/api/v3/push';
            $client = new \GuzzleHttp\Client();


            $options = [];
            $options['auth'] = [
                lib()->config->gravitec_app_key,
                lib()->config->gravitec_app_secret,
            ];
            $options['headers'] = [
                'Content-Type' => 'application/json',
            ];

            $options_body = [
                'ttl' => 1,
                'display_time' => 1,
                'payload' => [
                    'message' => $data['message'],
                    'title' => $data['title'],
                    'icon' => $data['icon'],
                    'redirect_url' => 'https://dev.subshero.com/subscription',
                ],
            ];

            if (!empty($data['reg_id'])) {
                $options['query'] = [
                    'reg_id' => $data['reg_id'],
                ];

                $options_body['audience']['tokens'] = [
                    $data['reg_id'],
                ];
            }

            if (!empty($data['reg_id_all'])) {

                // Take one random reg_id
                if (empty($data['reg_id'])) {
                    $options['query'] = [
                        'reg_id' => $data['reg_id_all'][array_rand($data['reg_id_all'])],
                    ];
                }

                $options_body['audience']['tokens'] = $data['reg_id_all'];
            }

            $options['body'] = json_encode($options_body);

            $response = $client->request('POST', $endpoint, $options);



            // $response = $client->request('POST', $endpoint, [
            //     'auth' => [
            //         lib()->config->gravitec_app_key,
            //         lib()->config->gravitec_app_secret,
            //     ],
            //     'headers' => [
            //         'Content-Type' => 'application/json',
            //     ],
            //     'query' => [
            //         'regId' => $data['reg_id'],
            //     ],
            //     'body' => json_encode([
            //         // 'send_date' => '',
            //         'ttl' => 1,
            //         // 'push_tag' => '',
            //         'display_time' => 1,
            //         // 'is_transactional' => '',
            //         // 'segments' => [''],
            //         'payload' => [
            //             'message' => $data['message'],
            //             'title' => $data['title'],
            //             'icon' => $data['icon'],
            //             // 'icon' => '',
            //             // 'image' => '',
            //             'redirect_url' => 'https://dev.subshero.com/subscription',
            //             // 'buttons' => [
            //             //     'title' => '',
            //             //     'url' => ''
            //             // ],
            //         ],
            //         'audience' => [
            //             'tokens' => [
            //                 $data['reg_id'],
            //             ],
            //             //     'aliases' => [''],
            //             //     'tags' => [''],
            //             //     'tags_segment' => ['']
            //         ],
            //     ]),
            // ]);


            $statusCode = $response->getStatusCode();
            $content = $response->getBody();
            // dd($statusCode, $content);

            if ($statusCode == 200) {
                return true;
            }
        }

        //catch exception
        catch (\GuzzleHttp\Exception\ClientException $e) {

            return false;
        }

        return false;
    }

    public function send_webhook(string $event, $user_id, $data)
    {
        $webhook_list = Webhook::where('user_id', $user_id)
            ->where('status', 1)
            ->where('type', 2)
            ->where('events', 'like', '%' . $event . '%')
            ->get();

        foreach ($webhook_list as $webhook) {
            try {
                $client = new \GuzzleHttp\Client();

                $headers = [
                    'user-agent' => 'Subshero/' . SettingsModel::get_arr(1)->versions_name,
                    'Content-Type' => 'application/json',
                    'x-subshero-webhook-source' => url('/'),
                    'x-subshero-webhook-event' => $event,
                    'x-subshero-webhook-id' => $webhook->id,
                ];
                $body = json_encode($data);

                // Create log
                $webhook_log = new WebhookLog();
                $webhook_log->webhook_id = $webhook->id;
                $webhook_log->user_id = $user_id;
                $webhook_log->type = $webhook->type;
                $webhook_log->event = $event;
                $webhook_log->request = json_encode([
                    'method' => 'POST',
                    'url' => $webhook->endpoint_url,
                    'event' => $event,
                    'headers' => $headers,
                    'body' => $body,
                ]);
                $webhook_log->created_at = date(APP_TIMESTAMP_FORMAT);
                $webhook_log->save();

                // Send request
                $client->request('POST', $webhook->endpoint_url, [
                    'verify' => false,
                    'timeout' => 1, // Response timeout
                    'connect_timeout' => 5, // Connection timeout
                    'headers' => $headers,
                    'body' => $body,
                ]);
            } catch (\Exception $e) {}
        }
    }

    public function get_filesize($size, $precision = 2)
    {
        for ($i = 0; ($size / 1024) > 0.9; $i++, $size /= 1024) {
        }
        return round($size, $precision) . ' ' . ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'][$i];
    }

    public function parse_sort(string $sort_str): array
    {
        // Format: column_name:asc,column_name:desc
        $sort_items = explode(',', $sort_str);
        $sort_items_parsed = [];

        foreach ($sort_items as $sort_item) {
            $sort_item_arr = explode(':', $sort_item);
            if (count($sort_item_arr) == 1) {
                $sort_items_parsed[] = [
                    'column' => $sort_item_arr[0],
                    'direction' => 'asc',
                ];
            } else if (count($sort_item_arr) == 2) {
                $sort_items_parsed[] = [
                    'column' => $sort_item_arr[0],
                    'direction' => $sort_item_arr[1],
                ];
            }
        }

        return $sort_items_parsed;
    }

    public function filter_unicode(string|null $str): string
    {
        $str = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $str);
        $str = preg_replace('/[\x00-\x1F\x7F]/', '', $str);
        $str = preg_replace('/[\x00-\x1F\x7F]/u', '', $str);
        return $str;
    }

    public function env_set($key, $value)
    {
        if (empty($key)) {
            return;
        }

        $pattern = '/([^\=]*)\=[^\n]*/';

        $envFile = base_path() . '/.env';
        $lines = file($envFile);
        $newLines = [];
        foreach ($lines as $line) {
            preg_match($pattern, $line, $matches);

            if (!count($matches)) {
                $newLines[] = $line;
                continue;
            }

            if (trim($matches[1]) == $key) {
                $line = trim($matches[1]) . "={$value}\n";
                $newLines[] = $line;
            } else {
                $newLines[] = $line;
            }
        }

        $newContent = implode('', $newLines);
        file_put_contents($envFile, $newContent);
    }
}

class Application_Language
{

    public function __construct()
    {
    }

    public function get_billing_type($value = null)
    {
        if (empty($value) || $value == 1) {
            return __('Calculate by days');
        }

        return __('Calculate by date');
    }
}

class Application_Cache
{
    private $data = [];

    public function __construct()
    {
    }

    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }

        return null;
    }

    public function __isset($name)
    {
        return isset($this->data[$name]);
    }

    public function __unset($name)
    {
        unset($this->data[$name]);
    }
}

class Application_RegEx
{
    public $subscription_image_path = '/^client\/1\/user\/[0-9]+\/subscription\/.*$/i';
    public $db_order_by_field_dir = '/^([0-9a-z]+(?:_[a-z0-9]+)*)+[,]+\b(asc|desc)\b$/gs';
    public $rgb_color = '/^#(?:[0-9a-fA-F]{3}){1,2}$/';

    public function __construct()
    {
    }
}

class Application_Is
{
    public function __construct()
    {
    }

    public function json($string)
    {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }
}

class Application_Search
{
    public function __construct()
    {
        // $this->subscription = new Application_Search_Subscription;
    }

    public function filter($searchValue, $items)
    {
        $searchValue = strtolower($searchValue);
        $all_search_term = explode(' ', $searchValue);

        if (count($all_search_term) > 2) {
            $all_search_term = array_slice($all_search_term, 2);
        }

        $type = null;

        // Search the needle
        foreach ($all_search_term as $val) {
            foreach ($items as $type_id => $all_terms) {
                foreach ($all_terms as $term) {
                    if (strpos($term, $val) !== false) {
                        $type = $type_id;
                        break 3;
                    }
                }
            }
        }

        return $type;
    }

    public function clean(string $searchValue)
    {
        // Remove unicode characters
        $searchValue = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $searchValue);
        $searchValue = preg_replace('/[\x00-\x1F\x7F]/', '', $searchValue);
        $searchValue = preg_replace('/[\x00-\x1F\x7F]/u', '', $searchValue);

        return $searchValue;
    }
}

class Application_Get
{
    public function __construct()
    {
    }

    public function cdn_url(string $path = null, $secure = null)
    {
        $path = $path ? '?v=' . SettingsModel::get_arr(1)->versions_name : '';
        if (empty(lib()->config->cdn_base_url)) {
            return app('url')->asset($path, $secure);
        } else {
            return lib()->config->cdn_base_url . $path;
        }
    }

    public function currency_symbol($currency_code)
    {
        if (isset(lib()->config->currency_symbol[$currency_code])) {
            return lib()->config->currency_symbol[$currency_code];
        }
        return null;
    }

    public function country($iso_code)
    {
        foreach (lib()->config->country as $country) {
            if ($country['isocode'] == $iso_code) {
                return (object)$country;
            }
        }

        return null;
    }
}
