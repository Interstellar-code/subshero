<?php

namespace App\Http\Controllers\Client\Settings;

use App\Http\Controllers\Controller;
use App\Library\Application as lib;
use App\Models\AlertProfile;
use App\Models\ApiToken;
use App\Models\ApiTokenModel;
use App\Models\Contact;
use App\Models\Event;
use App\Models\EventModel;
use App\Models\Folder;
use App\Models\FolderModel;
use App\Models\Marketplace;
use App\Models\PaymentMethod;
use App\Models\PaymentMethodModel;
use App\Models\Subscription;
use App\Models\SubscriptionModel;
use App\Models\SubscriptionAttachment;
use App\Models\Tag;
use App\Models\TagModel;
use App\Models\UserAlert;
use App\Models\UserModel;
use App\Models\Webhook;
use App\Models\ProductModel;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    private $backup_dir_path = null;
    private $token = null;

    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');

        $this->backup_commands = lib()->settings->recovery->backup_commands;
        $this->restore_commands = lib()->settings->recovery->restore_commands;
        $this->reset_commands = lib()->settings->recovery->reset_commands;

        // $this->recovery_dir_name = 'app/client/1/user/' . Auth::id() .  '/recovery';
        $this->recovery_dir_name = 'client/1/user/' . Auth::id() . '/recovery/';
        $this->recovery_dir_path = storage_path($this->recovery_dir_name);

        $this->current_event = null;
        $this->event_id = 0;

        $this->get_backup_dir_path();
    }

    public function index()
    {
        $output['slug'] = 'import';
        $output['data']['recovery_in_progress'] = false;
        $output['data']['backup']['status'] = 'not_found';

        // Check if another recovery is in progress
        if ($this->is_recovery_in_progress()) {
            $output['data']['recovery_in_progress'] = true;
            $output['data']['recovery_progress_message'] = $this->get_in_progress_msg();
        } else {
            // Check if backup directory exists
            if (!empty($this->old_backup_dir_name)) {

                // Get backup timestamp
                $backup_timestamp = intval(basename($this->old_backup_dir_name));
                $backup_date = Carbon::createFromTimestamp(intval(basename($this->old_backup_dir_name)));
                $backup_date->setTimezone(lib()->user->default->timezone_value);

                $output['data']['backup']['date'] = $backup_date->format('l, d F, Y \a\t h:i:s A');
                $output['data']['subscription']['count'] = 0;
                $output['data']['subscription_attachment']['count'] = 0;
                $output['data']['subscription_marketplace']['count'] = 0;
                $output['data']['folder']['count'] = 0;
                $output['data']['tag']['count'] = 0;
                $output['data']['payment_method']['count'] = 0;
                $output['data']['contact']['count'] = 0;
                $output['data']['alert_profile']['count'] = 0;
                $output['data']['api_token']['count'] = 0;
                $output['data']['webhook']['count'] = 0;
                $output['data']['settings']['count'] = 0;
                $output['data']['avatar']['path'] = null;

                $settings_data = $this->get_json_file_data('settings');
                if (!empty($settings_data) && is_array($settings_data)) {
                    $output['data']['settings']['count'] = count($settings_data);
                } else {
                    $output['data']['backup']['status'] = 'corrupted';
                }

                $output['data']['avatar']['path'] = $this->get_avatar_path();

                // Get all data count
                if (isset($settings_data['backup']['item']['subscription']['count'])) {
                    $output['data']['subscription']['count'] = intval($settings_data['backup']['item']['subscription']['count']);
                }

                if (isset($settings_data['backup']['item']['subscription_attachment']['count'])) {
                    $output['data']['subscription_attachment']['count'] = $this->get_subscription_attachment_count();
                }

                if (isset($settings_data['backup']['item']['subscription_marketplace']['count'])) {
                    $output['data']['subscription_marketplace']['count'] = intval($settings_data['backup']['item']['subscription_marketplace']['count']);
                }

                if (isset($settings_data['backup']['item']['folder']['count'])) {
                    $output['data']['folder']['count'] = intval($settings_data['backup']['item']['folder']['count']);
                }

                if (isset($settings_data['backup']['item']['tag']['count'])) {
                    $output['data']['tag']['count'] = intval($settings_data['backup']['item']['tag']['count']);
                }

                if (isset($settings_data['backup']['item']['payment_method']['count'])) {
                    $output['data']['payment_method']['count'] = intval($settings_data['backup']['item']['payment_method']['count']);
                }

                if (isset($settings_data['backup']['item']['contact']['count'])) {
                    $output['data']['contact']['count'] = intval($settings_data['backup']['item']['contact']['count']);
                }

                if (isset($settings_data['backup']['item']['alert_profile']['count'])) {
                    $output['data']['alert_profile']['count'] = intval($settings_data['backup']['item']['alert_profile']['count']);
                }

                if (isset($settings_data['backup']['item']['api_token']['count'])) {
                    $output['data']['api_token']['count'] = intval($settings_data['backup']['item']['api_token']['count']);
                }

                if (isset($settings_data['backup']['item']['webhook']['count'])) {
                    $output['data']['webhook']['count'] = intval($settings_data['backup']['item']['webhook']['count']);
                }

                // Check if backup is corrupted
                if (isset($settings_data['backup']['status']) && $settings_data['backup']['status'] == true) {
                    $output['data']['backup']['status'] = 'okay';
                } else {
                    $output['data']['backup']['status'] = 'corrupted';
                }
            }
        }

        return view('client/settings/recovery/index', $output);
    }

    /*
    //
    //
    //
    //
    // Reset
    //
    //
    //
    //
    //
    //
     */

    public function reset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'command' => 'required|string|max:50',
            'event_id' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
        }

        $this->user_id = Auth::id();
        $this->event_id = $request->input('event_id');
        $command = $request->input('command');

        // Check if another recovery is in progress
        if ($this->is_recovery_in_progress()) {
            return Response::json([
                'status' => false,
                'message' => $this->get_in_progress_msg(),
            ]);
        }

        switch ($command) {
            case 'get_account_info':
                return $this->cmd_reset_get_account_info($request, $command);
                break;

            case 'delete_subscription':
                return $this->cmd_reset_delete_subscription($request, $command);
                break;

            case 'delete_folder':
                return $this->cmd_reset_delete_folder($request, $command);
                break;

            case 'delete_tag':
                return $this->cmd_reset_delete_tag($request, $command);
                break;

            case 'delete_payment_method':
                return $this->cmd_reset_delete_payment_method($request, $command);
                break;

            case 'delete_contact':
                return $this->cmd_reset_delete_contact($request, $command);
                break;

            case 'delete_alert_profile':
                return $this->cmd_reset_delete_alert_profile($request, $command);
                break;

            case 'delete_api_token':
                return $this->cmd_reset_delete_api_token($request, $command);
                break;

            case 'delete_webhook':
                return $this->cmd_reset_delete_webhook($request, $command);
                break;

            case 'delete_file_all':
                return $this->cmd_reset_delete_file_all($request, $command);
                break;

            case 'reset_success':
                return $this->cmd_reset_reset_success($request, $command);
                break;

            default:
                return response()->json([
                    'status' => false,
                    'message' => 'Command not found',
                ]);
        }
    }

    private function cmd_reset_get_account_info(Request $request, $command)
    {
        $output = [
            'command' => $command,
        ];

        // Create event logs
        // $output['event_id'] = $this->add_event([
        //     'event_type' => 'account_reset',
        //     'event_status' => 0,
        // ]);
        $output['event_id'] = 0;

        $output['data']['subscription_count'] = DB::table('subscriptions')
            ->where('user_id', $this->user_id)
            ->get()
            ->count();

        $output['data']['folder_count'] = DB::table('folder')
            ->where('user_id', $this->user_id)
            ->get()
            ->count();

        $output['data']['tag_count'] = DB::table('tags')
            ->where('user_id', $this->user_id)
            ->get()
            ->count();

        $output['data']['payment_method_count'] = DB::table('users_payment_methods')
            ->where('user_id', $this->user_id)
            ->get()
            ->count();

        $output['data']['contact_count'] = DB::table('users_contacts')
            ->where('user_id', $this->user_id)
            ->get()
            ->count();

        $output['data']['alert_profile_count'] = DB::table('users_alert')
            ->where('user_id', $this->user_id)
            ->get()
            ->count();

        $output['data']['api_token_count'] = DB::table('personal_access_tokens')
            ->where('tokenable_type', 'App\Models\Api\User')
            ->where('tokenable_id', $this->user_id)
            ->get()
            ->count();

        $output['data']['webhook_count'] = DB::table('webhooks')
            ->where('user_id', $this->user_id)
            ->get()
            ->count();

        $output['data']['team_account_count'] = DB::table('users_teams')
            ->where('team_user_id', $this->user_id)
            ->get()
            ->count();

        if (empty($output['data'])) {
            $output['message'] = $this->reset_commands[$command]['message']['failure'];
            $output['status'] = false;
        } else {

            $output['status'] = true;
            $output['message'] = $this->reset_commands[$command]['message']['success'];
            $output['next_command'] = $this->reset_commands[$command]['next_command'];

            // $request->session()->put('new_version_number', $list_array[0]['version_number']);
        }

        return response()->json($output);
    }

    private function cmd_reset_delete_subscription(Request $request, $command)
    {
        $output = [
            'command' => $command,
        ];

        // Get subscription
        $subscription = DB::table('subscriptions')
            ->where('user_id', $this->user_id)
            ->get()
            ->first();

        if (empty($subscription->id)) {

            $output['status'] = true;
            $output['message'] = $this->reset_commands[$command]['message']['complete'];
            $output['next_command'] = $this->reset_commands[$command]['next_command'];
        } else {

            // Delete subscription
            SubscriptionModel::del($subscription->id);

            if (!empty($subscription->image)) {
                // Disabled for copying the entire folder
                // $file_path = 'client/1/subscription/' . $subscription->id . '/' . basename($subscription->image);


                // Check for custom image
                $image_relative_path = $subscription->image;
                $match_status = preg_match(lib()->regex->subscription_image_path, $image_relative_path);

                if ($match_status && Storage::disk('local')->exists($image_relative_path)) {
                    Storage::disk('local')->delete($image_relative_path);
                }
            }

            // Delete all attachments and its directory
            SubscriptionAttachment::deleteBySubscription($subscription->id);

            // Delete subscription marketplace
            Marketplace::where('subscription_id', $subscription->id)->delete();

            $output['status'] = true;
            $output['message'] = $this->reset_commands[$command]['message']['success'] . $subscription->product_name;
            $output['next_command'] = $command;
        }

        return response()->json($output);
    }

    private function cmd_reset_delete_folder(Request $request, $command)
    {
        $output = [
            'command' => $command,
        ];

        // Get subscription
        $folder = DB::table('folder')
            ->where('user_id', $this->user_id)
            ->get()
            ->first();

        if (empty($folder->id)) {

            $output['status'] = true;
            $output['message'] = $this->reset_commands[$command]['message']['complete'];
            $output['next_command'] = $this->reset_commands[$command]['next_command'];
        } else {

            // Delete folder
            FolderModel::del($folder->id);

            $output['status'] = true;
            $output['message'] = $this->reset_commands[$command]['message']['success'] . $folder->name;
            $output['next_command'] = $command;
        }

        return response()->json($output);
    }

    private function cmd_reset_delete_tag(Request $request, $command)
    {
        $output = [
            'command' => $command,
        ];

        // Get tag
        $tag = DB::table('tags')
            ->where('user_id', $this->user_id)
            ->get()
            ->first();

        if (empty($tag->id)) {

            $output['status'] = true;
            $output['message'] = $this->reset_commands[$command]['message']['complete'];
            $output['next_command'] = $this->reset_commands[$command]['next_command'];
        } else {

            // Delete tag
            TagModel::del($tag->id);

            $output['status'] = true;
            $output['message'] = $this->reset_commands[$command]['message']['success'] . $tag->name;
            $output['next_command'] = $command;
        }

        return response()->json($output);
    }

    private function cmd_reset_delete_payment_method(Request $request, $command)
    {
        $output = [
            'command' => $command,
        ];

        // Get payment method
        $payment_method = DB::table('users_payment_methods')
            ->where('user_id', $this->user_id)
            ->get()
            ->first();

        if (empty($payment_method->id)) {

            $output['status'] = true;
            $output['message'] = $this->reset_commands[$command]['message']['complete'];
            $output['next_command'] = $this->reset_commands[$command]['next_command'];
        } else {

            // Delete payment method
            PaymentMethodModel::del($payment_method->id);

            $output['status'] = true;
            $output['message'] = $this->reset_commands[$command]['message']['success'] . $payment_method->name;
            $output['next_command'] = $command;
        }

        return response()->json($output);
    }

    private function cmd_reset_delete_contact(Request $request, $command)
    {
        $output = [
            'command' => $command,
        ];

        // Get contact
        $contact = DB::table('users_contacts')
            ->where('user_id', $this->user_id)
            ->get()
            ->first();

        if (empty($contact->id)) {

            $output['status'] = true;
            $output['message'] = $this->reset_commands[$command]['message']['complete'];
            $output['next_command'] = $this->reset_commands[$command]['next_command'];
        } else {

            // Delete contact
            UserModel::contact_delete($contact->id);

            $output['status'] = true;
            $output['message'] = $this->reset_commands[$command]['message']['success'] . $contact->name;
            $output['next_command'] = $command;
        }

        return response()->json($output);
    }

    private function cmd_reset_delete_alert_profile(Request $request, $command)
    {
        $output = [
            'command' => $command,
        ];

        // Get alert
        $alert = UserAlert::where('user_id', $this->user_id)->first();

        if (empty($alert->id)) {

            $output['status'] = true;
            $output['message'] = $this->reset_commands[$command]['message']['complete'];
            $output['next_command'] = $this->reset_commands[$command]['next_command'];
        } else {

            // Delete alert profile
            $alert->delete();

            $output['status'] = true;
            $output['message'] = $this->reset_commands[$command]['message']['success'] . $alert->alert_name;
            $output['next_command'] = $command;
        }

        return response()->json($output);
    }

    private function cmd_reset_delete_api_token(Request $request, $command)
    {
        $output = [
            'command' => $command,
        ];

        // Get API token
        $api_token = DB::table('personal_access_tokens')
            ->where('tokenable_type', 'App\Models\Api\User')
            ->where('tokenable_id', $this->user_id)
            ->get()
            ->first();

        if (empty($api_token->id)) {

            $output['status'] = true;
            $output['message'] = $this->reset_commands[$command]['message']['complete'];
            $output['next_command'] = $this->reset_commands[$command]['next_command'];
        } else {

            // Delete API token
            ApiTokenModel::del($api_token->id);

            $output['status'] = true;
            $output['message'] = $this->reset_commands[$command]['message']['success'] . $api_token->name;
            $output['next_command'] = $command;
        }

        return response()->json($output);
    }

    private function cmd_reset_delete_webhook(Request $request, $command)
    {
        $output = [
            'command' => $command,
        ];

        // Get webhook
        $webhook = DB::table('webhooks')
            ->where('user_id', $this->user_id)
            ->get()
            ->first();

        if (empty($webhook->id)) {

            $output['status'] = true;
            $output['message'] = $this->reset_commands[$command]['message']['complete'];
            $output['next_command'] = $this->reset_commands[$command]['next_command'];
        } else {

            // Delete webhook
            Webhook::where('id', $webhook->id)->delete();

            $output['status'] = true;
            $output['message'] = $this->reset_commands[$command]['message']['success'] . $webhook->name;
            $output['next_command'] = $command;
        }

        return response()->json($output);
    }

    private function cmd_reset_delete_file_all(Request $request, $command)
    {
        $output = [
            'command' => $command,
        ];

        // Delete data from all tables
        $this->clean_tables();
        $this->clean_files();

        $output['status'] = true;
        $output['message'] = $this->reset_commands[$command]['message']['complete'];
        $output['next_command'] = $this->reset_commands[$command]['next_command'];

        return response()->json($output);
    }

    private function cmd_reset_reset_success(Request $request, $command)
    {
        $output = [
            'command' => $command,
        ];

        // Add default payment method
        $payment_method_id = DB::table('users_payment_methods')->insertGetId([
            'user_id' => $this->user_id,
            'payment_type' => 'PayPal',
            'name' => 'PayPal',
        ]);

        // Set default payment method
        DB::table('users_profile')
            ->where('user_id', $this->user_id)
            ->update([
                'payment_mode_id' => $payment_method_id,
            ]);

        // Add other default payment methods
        DB::table('users_payment_methods')->insert([
            [
                'user_id' => $this->user_id,
                'payment_type' => 'Credit Card',
                'name' => 'Credit Card',
            ],
            [
                'user_id' => $this->user_id,
                'payment_type' => 'Others',
                'name' => 'Others',
            ],
        ]);

        // Set reset time
        DB::table('users')
            ->where('id', $this->user_id)
            ->update([
                'reset_at' => date('Y-m-d H:i:s'),
            ]);


        // // Create event logs for reset
        // $this->add_event([
        //     'event_type' => 'account_reset',
        //     'event_status' => 1,
        // ]);

        // // Logout user and redirect to login page
        // Auth::logout();
        // $request->session()->invalidate();
        // $request->session()->regenerateToken();

        $output['status'] = true;
        $output['message'] = $this->reset_commands[$command]['message']['complete'];
        $output['next_command'] = $this->reset_commands[$command]['next_command'];

        return response()->json($output);
    }

    public function delete_account(Request $request)
    {
        // Delete user account
        $user_id = Auth::id();
        DB::table('users_alert')->where('user_id', $user_id)->delete();
        DB::table('users_alert_preferences')->where('user_id', $user_id)->delete();
        DB::table('users_plans')->where('user_id', $user_id)->delete();
        DB::table('users_profile')->where('user_id', $user_id)->delete();
        DB::table('users_teams')->where('pro_user_id', $user_id)->delete();
        DB::table('users_tour_status')->where('user_id', $user_id)->delete();
        DB::table('users')->where('id', $user_id)->delete();

        // Logout user and redirect to login page
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'status' => true,
            'message' => 'Success',
        ], 200);
    }

    /*
    //
    //
    //
    //
    // Functions
    //
    //
    //
    //
    //
    //
     */

    private function add_event($data)
    {
        // Check if event already exists
        if (empty($this->event_id)) {

            // Create event logs
            $this->event_id = EventModel::create([
                'user_id' => Auth::id(),
                'event_type' => $data['event_type'],
                'event_type_status' => 'create',
                'event_status' => $data['event_status'],
                'table_name' => 'users',
                'table_row_id' => Auth::id(),
                'event_type_function' => __CLASS__ . '::' . __FUNCTION__ . '()',
                'event_cron' => 0,
                'event_type_schedule' => 0,
            ]);
        } else {
            // Update event logs
            EventModel::do_update($this->event_id, [
                'event_status' => $data['event_status'],
                'event_type_function' => __CLASS__ . '::' . __FUNCTION__ . '()',

                'event_url' => url()->current(),
                'event_timezone' => APP_TIMEZONE,
                'event_datetime' => lib()->do->timezone_convert([
                    'to_timezone' => APP_TIMEZONE,
                ]),
            ]);
        }

        return $this->event_id;
    }

    private function backup_response(array $data)
    {
        if (!isset($data['last_command'])) {
            $data['last_command'] = '';

            // Get last command
            if (isset($_SESSION['account_recovery']['backup'][$this->token]['last_command'])) {
                $data['last_command'] = $_SESSION['account_recovery']['backup'][$this->token]['last_command'];
            }

            // Set last command
            if (isset($data['command'])) {
                $_SESSION['account_recovery']['backup'][$this->token]['last_command'] = $data['command'];
            }
        }

        return response()->json($data);
    }

    private function restore_response(array $data)
    {
        if (!isset($data['last_command'])) {
            $data['last_command'] = '';

            // Get last command
            if (isset($_SESSION['account_recovery']['restore'][$this->token]['last_command'])) {
                $data['last_command'] = $_SESSION['account_recovery']['restore'][$this->token]['last_command'];
            }

            // Set last command
            if (isset($data['command'])) {
                $_SESSION['account_recovery']['restore'][$this->token]['last_command'] = $data['command'];
            }
        }

        return response()->json($data);
    }

    private function store_backup_data(string $key)
    {
        if (isset($_SESSION['account_recovery']['backup'][$this->token][$key])) {
            $data = $_SESSION['account_recovery']['backup'][$this->token][$key];
            Storage::disk('local')->put($this->backup_dir_name . '/' . $key . '.json', json_encode($data));
        }
    }

    private function get_backup_dir_path()
    {
        // Get all folders in backup directory
        $directories = Storage::disk('local')->directories($this->recovery_dir_name);
        $directory_path = null;

        if (count($directories) > 0) {
            foreach ($directories as $dir) {
                $basename = basename($dir);

                // Search for a valid directory name
                if (is_numeric($basename) && intval($basename) == $basename && strlen($basename) >= 10) {
                    $directory_path = $dir;
                    break;
                }
            }
        }

        if (!empty($directory_path)) {
            $this->old_backup_dir_name = $directory_path . '/';
            $this->old_backup_dir_path = storage_path($directory_path);
        }

        return $directory_path;
    }

    private function get_json_file_data($file_name)
    {
        $file_path = $this->old_backup_dir_name . $file_name . '.json';
        $file_data = null;

        if (Storage::disk('local')->exists($file_path)) {
            $file_data = Storage::disk('local')->get($file_path);
            if (!empty($file_data)) {
                $file_data = json_decode($file_data, true);
            }
        }

        return $file_data;
    }

    private function get_subscription_attachment_count()
    {
        $folder_path = $this->old_backup_dir_name . 'media/attachment';
        $file_count = 0;

        if (Storage::disk('local')->exists($folder_path)) {
            $files = Storage::disk('local')->files($folder_path);
            $file_count = count($files);
        }

        return $file_count;
    }

    private function get_avatar_path()
    {
        $avatar_path = null;

        // Get all files starting with avatar in backup directory
        $files = Storage::disk('local')->files($this->old_backup_dir_name . 'media');
        if (!empty($files) && is_array($files)) {
            foreach ($files as $file) {
                if (strpos($file, 'avatar.') !== false) {
                    $avatar_path = $file;
                    break;
                }
            }
        }

        return $avatar_path;
    }

    private function set_backup_data($key, $data)
    {
        $_SESSION['account_recovery']['backup'][$this->token][$key] = $data;
    }

    private function get_backup_data($key)
    {
        if (isset($_SESSION['account_recovery']['backup'][$this->token][$key])) {
            return $_SESSION['account_recovery']['backup'][$this->token][$key];
        }
    }

    private function is_recovery_in_progress()
    {
        // Get current datetime in app timezone
        $now = lib()->do->timezone_convert([
            'to_timezone' => APP_TIMEZONE,
        ]);

        $now_carbon = Carbon::createFromFormat('Y-m-d H:i:s', $now, APP_TIMEZONE);
        $before_10_minutes = $now_carbon->copy()->subMinutes(10)->format('Y-m-d H:i:s');

        // Get in progress event
        $event = Event::where('user_id', Auth::user()->id)
            ->where('id', '!=', $this->event_id)
            ->whereIn('event_type', ['account_backup', 'account_restore', 'account_reset'])
            ->where('event_status', 0)
            ->where('event_datetime', '>=', $before_10_minutes)
            ->first();

        if (empty($event->id)) {
            $this->current_event = null;
            return false;
        } else {
            $this->current_event = $event;
            return true;
        }
    }

    private function get_in_progress_msg()
    {
        $msg = '';
        if ($this->current_event->event_type == 'account_backup') {
            $msg = __('Another backup is in progress, please try again in a few minutes.');
        } elseif ($this->current_event->event_type == 'account_restore') {
            $msg = __('Another restore is in progress, please try again in a few minutes.');
        } elseif ($this->current_event->event_type == 'account_reset') {
            $msg = __('Another reset is in progress, please try again in a few minutes.');
        }
        return $msg;
    }

    private function clean_tables()
    {
        $common_tables = [
            // 'events',
            'extension_settings',
            'folder',
            'event_chrome_extn',
            // 'personal_access_tokens',
            'event_browser_notify',
            'push_notification_registers',
            'subscriptions',
            'subscriptions_attachments',
            'subscription_cart',
            'subscriptions_history',
            'subscriptions_tags',
            'tags',
            'tokens',
            'users_alert',
            'users_contacts',
            'users_payment_methods',
            'users_tour_status',
            'webhooks',
            'webhook_logs',
            'event_chrome',
            'event_browser',
            'event_emails',
        ];

        foreach ($common_tables as $table) {
            DB::table($table)->where('user_id', Auth::user()->id)->delete();
        }

        // $this->event_id

        // Clean other tables
        if (empty($this->event_id)) {
            DB::table('events')
                ->where('user_id', Auth::user()->id)
                ->delete();
        } else {
            DB::table('events')
                ->where('user_id', Auth::user()->id)
                ->where('id', '!=', $this->event_id)
                ->delete();
        }

        DB::table('personal_access_tokens')
            ->where('tokenable_type', 'App\Models\Api\User')
            ->where('tokenable_id', Auth::user()->id)
            ->delete();
    }

    private function clean_files()
    {
        // Delete all folders
        $data_storage_path = 'client/1/user/' . Auth::id();
        $data_full_path = 'storage/app/' . $data_storage_path;

        // Delete media files
        $all_files = Storage::disk('local')->files($data_storage_path);
        if (!empty($all_files)) {
            foreach ($all_files as $file) {
                $file_name = basename($file);

                // Skip some files
                if (in_array($file_name, ['avatar.jpg'])) {
                    continue;
                }

                $file_path = $data_full_path . '/' . $file_name;

                if (File::exists($file_path)) {
                    File::delete($file_path);
                }
            }
        }


        // Delete media folders
        $all_folders = Storage::disk('local')->directories($data_storage_path);
        if (!empty($all_folders)) {
            foreach ($all_folders as $folder) {
                $folder_name = basename($folder);

                // Skip backup folder
                if (in_array($folder_name, ['recovery'])) {
                    continue;
                }

                $folder_path = $data_full_path . '/' . $folder_name;

                if (File::exists($folder_path)) {
                    File::deleteDirectory($folder_path);
                }
            }
        }
    }
}
