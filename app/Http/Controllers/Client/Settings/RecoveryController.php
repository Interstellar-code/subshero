<?php

namespace App\Http\Controllers\Client\Settings;

use App\Http\Controllers\Controller;
use App\Library\Application as lib;
use App\Models\ApiToken;
use App\Models\ApiTokenModel;
use App\Models\Contact;
use App\Library\NotificationEngine;
use App\Models\EventBrowser;
use App\Models\EventChrome;
use App\Models\EventEmail;
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

class RecoveryController extends Controller
{
    private $backup_dir_path = null;
    private $token = null;
    private $event_id;
    private $backup_commands;
    private $restore_commands;
    private $reset_commands;
    private $recovery_dir_name;
    private $recovery_dir_path;
    private $current_event;
    private $old_backup_dir_name;
    private $old_backup_dir_path;
    private $user_id;
    private $backup_dir_name;
    private $backup_timestamp;
    private $backup_end_time;
    private $restore_dir_name;
    private $restore_dir_path;
    private $restore_timestamp;

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
    // Backup
    //
    //
    //
    //
    //
    //
     */

    public function backup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required|string|size:32',
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
        $this->token = $request->input('token');
        $this->event_id = $request->input('event_id');
        $command = $request->input('command');

        // Check if another recovery is in progress
        if ($this->is_recovery_in_progress()) {
            return Response::json([
                'status' => false,
                'message' => $this->get_in_progress_msg(),
            ]);
        }

        // Get backup directory
        if (isset($_SESSION['account_recovery']['backup'][$this->token]['backup_dir_name'])) {
            $this->backup_dir_name = $_SESSION['account_recovery']['backup'][$this->token]['backup_dir_name'];
            $this->backup_dir_path = $_SESSION['account_recovery']['backup'][$this->token]['backup_dir_path'];
            $this->backup_timestamp = intval(basename($this->backup_dir_name));
        }

        switch ($command) {
            case 'check_existence':
                return $this->cmd_backup_check_existence($request, $command);
                break;

            case 'delete_backup':
                return $this->cmd_backup_delete_backup($request, $command);
                break;

            case 'get_account_info':
                return $this->cmd_backup_get_account_info($request, $command);
                break;

            case 'backup_subscription':
                return $this->cmd_backup_subscription($request, $command);
                break;

            case 'backup_folder':
                return $this->cmd_backup_folder($request, $command);
                break;

            case 'backup_tag':
                return $this->cmd_backup_tag($request, $command);
                break;

            case 'backup_payment_method':
                return $this->cmd_backup_payment_method($request, $command);
                break;

            case 'backup_contact':
                return $this->cmd_backup_contact($request, $command);
                break;

            case 'backup_alert_profile':
                return $this->cmd_backup_alert_profile($request, $command);
                break;

            case 'backup_api_token':
                return $this->cmd_backup_api_token($request, $command);
                break;

            case 'backup_webhook':
                return $this->cmd_backup_webhook($request, $command);
                break;

                // case 'backup_team_account':
                //     return $this->cmd_backup_team_account($request, $command);
                //     break;

            case 'backup_settings':
                return $this->cmd_backup_settings($request, $command);
                break;

            case 'backup_file_all':
                return $this->cmd_backup_file_all($request, $command);
                break;

            case 'backup_success':
                return $this->cmd_backup_success($request, $command);
                break;

            default:
                return $this->backup_response([
                    'status' => false,
                    'message' => 'Command not found',
                ]);
        }
    }

    private function cmd_backup_check_existence(Request $request, $command)
    {
        $output = [
            'command' => $command,
        ];

        // Create event logs
        $output['event_id'] = $this->add_event([
            'event_type' => 'account_backup',
            'event_status' => 0,
        ]);

        // Reset data
        $_SESSION['account_recovery']['backup'] = [];
        $_SESSION['account_recovery']['backup'][$this->token] = [];

        // Set backup directory
        $this->backup_timestamp = time();
        $this->backup_dir_name = $this->recovery_dir_name . $this->backup_timestamp;
        $_SESSION['account_recovery']['backup'][$this->token]['backup_dir_name'] = $this->backup_dir_name;
        $_SESSION['account_recovery']['backup'][$this->token]['backup_dir_path'] = storage_path($this->backup_dir_name);

        // Check if backup files found
        if (empty($this->old_backup_dir_name)) {
            $output['status'] = true;
            $output['message'] = $this->backup_commands[$command]['message']['not_found'];
            $output['next_command'] = 'get_account_info';
        } else {
            $this->set_backup_data('old_directory_path', $this->old_backup_dir_name);

            $output['status'] = true;
            $output['message'] = $this->backup_commands[$command]['message']['found'];
            $output['next_command'] = $this->backup_commands[$command]['next_command'];
        }

        return $this->backup_response($output);
    }

    private function cmd_backup_delete_backup(Request $request, $command)
    {
        $output = [
            'command' => $command,
        ];

        $old_directory_path = $this->get_backup_data('old_directory_path');

        // Check if backup files not found
        if (empty($old_directory_path)) {
            $output['status'] = true;
            $output['message'] = $this->backup_commands[$command]['message']['not_found'];
            $output['next_command'] = $this->backup_commands[$command]['next_command'];
        }

        // Delete backup files if found
        else {
            $status = Storage::disk('local')->deleteDirectory($old_directory_path);

            if ($status) {
                $this->set_backup_data('old_directory_path', null);

                $output['status'] = true;
                $output['message'] = $this->backup_commands[$command]['message']['success'];
                $output['next_command'] = $this->backup_commands[$command]['next_command'];
            } else {
                $output['status'] = false;
                $output['message'] = $this->backup_commands[$command]['message']['failure'];
            }
        }

        return $this->backup_response($output);
    }

    private function cmd_backup_get_account_info(Request $request, $command)
    {
        $output = [
            'command' => $command,
        ];

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

        // $output['data']['team_account_count'] = DB::table('users_teams')
        //     ->where('team_user_id', $this->user_id)
        //     ->get()
        //     ->count();

        if (empty($output['data'])) {
            $output['status'] = false;
            $output['message'] = $this->backup_commands[$command]['message']['failure'];
        } else {

            $output['status'] = true;
            $output['message'] = $this->backup_commands[$command]['message']['success'];
            $output['next_command'] = $this->backup_commands[$command]['next_command'];
        }

        return $this->backup_response($output);
    }

    private function cmd_backup_subscription(Request $request, $command)
    {
        $output = [
            'command' => $command,
        ];

        $data_id_all = [];

        // Get stored data id
        if (isset($_SESSION['account_recovery']['backup'][$this->token]['subscription_id_all'])) {
            $data_id_all = $_SESSION['account_recovery']['backup'][$this->token]['subscription_id_all'];
        }

        // Get subscription
        $subscription = Subscription::where('user_id', $this->user_id)
            ->whereNotIn('id', $data_id_all)
            ->first();

        if (empty($subscription->id)) {

            $output['status'] = true;
            $output['message'] = $this->backup_commands[$command]['message']['complete'];
            $output['next_command'] = $this->backup_commands[$command]['next_command'];

            // Store data in JSON file
            $this->store_backup_data('subscription');
            $this->store_backup_data('subscription_attachment');
            $this->store_backup_data('subscription_marketplace');
        } else {
            $subscription_array = $subscription->getRawOriginal();

            $image_relative_path = $subscription->getRawOriginal('image');
            if (!empty($image_relative_path)) {
                $match_status = preg_match(lib()->regex->subscription_image_path, $image_relative_path);

                if ($match_status) {

                    // Disabled for copying the entire folder
                    // Check if image exists
                    if (Storage::exists($image_relative_path)) {
                        $image_extension = pathinfo($image_relative_path, PATHINFO_EXTENSION);

                        // Copy image to backup folder
                        $image_backup_path = $this->backup_dir_name . '/media/subscription/image/' . $subscription->id . '.' . $image_extension;

                        if (!Storage::exists($image_backup_path)) {
                            Storage::copy($image_relative_path, $image_backup_path);
                            $subscription_array['_image_path'] = $image_backup_path;
                            $subscription_array['_image_filename'] = $subscription->id . '.' . $image_extension;
                        }
                    }
                }
            }

            // Disabled for copying the entire folder
            // Save attachments
            $attachments = SubscriptionAttachment::where('subscription_id', $subscription->id)->get();
            if (!empty($attachments)) {
                $attachment_source_path = 'storage/app/client/1/subscription/' . $subscription->id . '/attachments';
                $attachment_backup_path = 'storage/app/' . $this->backup_dir_name . '/media/subscription/' . $subscription->id . '/attachments';

                if (File::exists($attachment_source_path)) {
                    File::copyDirectory($attachment_source_path, $attachment_backup_path);
                }
                $_SESSION['account_recovery']['backup'][$this->token]['subscription_attachment'][$subscription->id] = $attachments;
            }

            // Save marketplace
            $subscription_marketplace = Marketplace::where('subscription_id', $subscription->id)->first();
            if (!empty($subscription_marketplace->id)) {
                $_SESSION['account_recovery']['backup'][$this->token]['subscription_marketplace'][$subscription->id] = $subscription_marketplace->getRawOriginal();
            }

            // Store data in session
            $_SESSION['account_recovery']['backup'][$this->token]['subscription'][] = $subscription_array;
            $_SESSION['account_recovery']['backup'][$this->token]['subscription_id_all'][] = $subscription->id;

            $output['status'] = true;
            $output['message'] = $this->backup_commands[$command]['message']['success'] . $subscription->product_name;
            $output['next_command'] = $command;
        }

        return $this->backup_response($output);
    }

    private function cmd_backup_folder(Request $request, $command)
    {
        $output = [
            'command' => $command,
        ];

        $data_id_all = [];

        // Get stored data id
        if (isset($_SESSION['account_recovery']['backup'][$this->token]['folder_id_all'])) {
            $data_id_all = $_SESSION['account_recovery']['backup'][$this->token]['folder_id_all'];
        }

        // Get subscription
        $folder = Folder::where('user_id', $this->user_id)
            ->whereNotIn('id', $data_id_all)
            ->first();

        if (empty($folder->id)) {

            $output['status'] = true;
            $output['message'] = $this->backup_commands[$command]['message']['complete'];
            $output['next_command'] = $this->backup_commands[$command]['next_command'];

            // Store data in JSON file
            $this->store_backup_data('folder');
        } else {

            // Store data in session
            $_SESSION['account_recovery']['backup'][$this->token]['folder'][] = $folder->getRawOriginal();
            $_SESSION['account_recovery']['backup'][$this->token]['folder_id_all'][] = $folder->id;

            $output['status'] = true;
            $output['message'] = $this->backup_commands[$command]['message']['success'] . $folder->name;
            $output['next_command'] = $command;
        }

        return $this->backup_response($output);
    }

    private function cmd_backup_tag(Request $request, $command)
    {
        $output = [
            'command' => $command,
        ];

        $data_id_all = [];

        // Get stored data id
        if (isset($_SESSION['account_recovery']['backup'][$this->token]['tag_id_all'])) {
            $data_id_all = $_SESSION['account_recovery']['backup'][$this->token]['tag_id_all'];
        }

        // Get tag
        $tag = Tag::where('user_id', $this->user_id)
            ->whereNotIn('id', $data_id_all)
            ->first();

        if (empty($tag->id)) {

            $output['status'] = true;
            $output['message'] = $this->backup_commands[$command]['message']['complete'];
            $output['next_command'] = $this->backup_commands[$command]['next_command'];

            // Store data in JSON file
            $this->store_backup_data('tag');
        } else {

            // Store data in session
            $_SESSION['account_recovery']['backup'][$this->token]['tag'][] = $tag->getRawOriginal();
            $_SESSION['account_recovery']['backup'][$this->token]['tag_id_all'][] = $tag->id;

            $output['status'] = true;
            $output['message'] = $this->backup_commands[$command]['message']['success'] . $tag->name;
            $output['next_command'] = $command;
        }

        return $this->backup_response($output);
    }

    private function cmd_backup_payment_method(Request $request, $command)
    {
        $output = [
            'command' => $command,
        ];

        $data_id_all = [];

        // Get stored data id
        if (isset($_SESSION['account_recovery']['backup'][$this->token]['payment_method_id_all'])) {
            $data_id_all = $_SESSION['account_recovery']['backup'][$this->token]['payment_method_id_all'];
        }

        // Get payment method
        $payment_method = PaymentMethod::where('user_id', $this->user_id)
            ->whereNotIn('id', $data_id_all)
            ->first();

        if (empty($payment_method->id)) {

            $output['status'] = true;
            $output['message'] = $this->backup_commands[$command]['message']['complete'];
            $output['next_command'] = $this->backup_commands[$command]['next_command'];

            // Store data in JSON file
            $this->store_backup_data('payment_method');
        } else {

            // Store data in session
            $_SESSION['account_recovery']['backup'][$this->token]['payment_method'][] = $payment_method->getRawOriginal();
            $_SESSION['account_recovery']['backup'][$this->token]['payment_method_id_all'][] = $payment_method->id;

            $output['status'] = true;
            $output['message'] = $this->backup_commands[$command]['message']['success'] . $payment_method->name;
            $output['next_command'] = $command;
        }

        return $this->backup_response($output);
    }

    private function cmd_backup_contact(Request $request, $command)
    {
        $output = [
            'command' => $command,
        ];

        $data_id_all = [];

        // Get stored data id
        if (isset($_SESSION['account_recovery']['backup'][$this->token]['contact_id_all'])) {
            $data_id_all = $_SESSION['account_recovery']['backup'][$this->token]['contact_id_all'];
        }

        // Get contact
        $contact = Contact::where('user_id', $this->user_id)
            ->whereNotIn('id', $data_id_all)
            ->first();

        if (empty($contact->id)) {

            $output['status'] = true;
            $output['message'] = $this->backup_commands[$command]['message']['complete'];
            $output['next_command'] = $this->backup_commands[$command]['next_command'];

            // Store data in JSON file
            $this->store_backup_data('contact');
        } else {

            // Store data in session
            $_SESSION['account_recovery']['backup'][$this->token]['contact'][] = $contact->getRawOriginal();
            $_SESSION['account_recovery']['backup'][$this->token]['contact_id_all'][] = $contact->id;

            $output['status'] = true;
            $output['message'] = $this->backup_commands[$command]['message']['success'] . $contact->name;
            $output['next_command'] = $command;
        }

        return $this->backup_response($output);
    }

    private function cmd_backup_alert_profile(Request $request, $command)
    {
        $output = [
            'command' => $command,
        ];

        $data_id_all = [];

        // Get stored data id
        if (isset($_SESSION['account_recovery']['backup'][$this->token]['alert_profile_id_all'])) {
            $data_id_all = $_SESSION['account_recovery']['backup'][$this->token]['alert_profile_id_all'];
        }

        // Get alert
        $alert_profile = UserAlert::whereUserId($this->user_id)
            ->whereNotIn('id', $data_id_all)
            ->first();

        if (empty($alert_profile->id)) {

            $output['status'] = true;
            $output['message'] = $this->backup_commands[$command]['message']['complete'];
            $output['next_command'] = $this->backup_commands[$command]['next_command'];

            // Store data in JSON file
            $this->store_backup_data('alert_profile');
        } else {

            // Store data in session
            $_SESSION['account_recovery']['backup'][$this->token]['alert_profile'][] = $alert_profile->getRawOriginal();
            $_SESSION['account_recovery']['backup'][$this->token]['alert_profile_id_all'][] = $alert_profile->id;

            $output['status'] = true;
            $output['message'] = $this->backup_commands[$command]['message']['success'] . $alert_profile->alert_name;
            $output['next_command'] = $command;
        }

        return $this->backup_response($output);
    }

    private function cmd_backup_api_token(Request $request, $command)
    {
        $output = [
            'command' => $command,
        ];

        $data_id_all = [];

        // Get stored data id
        if (isset($_SESSION['account_recovery']['backup'][$this->token]['api_token_id_all'])) {
            $data_id_all = $_SESSION['account_recovery']['backup'][$this->token]['api_token_id_all'];
        }

        // Get API token
        $api_token = ApiToken::where('tokenable_type', 'App\Models\Api\User')
            ->where('tokenable_id', $this->user_id)
            ->whereNotIn('id', $data_id_all)
            ->first();

        if (empty($api_token->id)) {

            $output['status'] = true;
            $output['message'] = $this->backup_commands[$command]['message']['complete'];
            $output['next_command'] = $this->backup_commands[$command]['next_command'];

            // Store data in JSON file
            $this->store_backup_data('api_token');
        } else {

            // Store data in session
            $_SESSION['account_recovery']['backup'][$this->token]['api_token'][] = $api_token->getRawOriginal();
            $_SESSION['account_recovery']['backup'][$this->token]['api_token_id_all'][] = $api_token->id;

            $output['status'] = true;
            $output['message'] = $this->backup_commands[$command]['message']['success'] . $api_token->name;
            $output['next_command'] = $command;
        }

        return $this->backup_response($output);
    }

    private function cmd_backup_webhook(Request $request, $command)
    {
        $output = [
            'command' => $command,
        ];

        $data_id_all = [];

        // Get stored data id
        if (isset($_SESSION['account_recovery']['backup'][$this->token]['webhook_id_all'])) {
            $data_id_all = $_SESSION['account_recovery']['backup'][$this->token]['webhook_id_all'];
        }

        // Get webhook
        $webhook = Webhook::where('user_id', $this->user_id)
            ->whereNotIn('id', $data_id_all)
            ->first();

        if (empty($webhook->id)) {

            $output['status'] = true;
            $output['message'] = $this->backup_commands[$command]['message']['complete'];
            $output['next_command'] = $this->backup_commands[$command]['next_command'];

            // Store data in JSON file
            $this->store_backup_data('webhook');
        } else {

            // Store data in session
            $_SESSION['account_recovery']['backup'][$this->token]['webhook'][] = $webhook->getRawOriginal();
            $_SESSION['account_recovery']['backup'][$this->token]['webhook_id_all'][] = $webhook->id;

            $output['status'] = true;
            $output['message'] = $this->backup_commands[$command]['message']['success'] . $webhook->name;
            $output['next_command'] = $command;
        }

        return $this->backup_response($output);
    }

    private function cmd_backup_file_all(Request $request, $command)
    {
        $output = [
            'command' => $command,
        ];

        $_SESSION['account_recovery']['backup'][$this->token]['dump']['events'] = NotificationEngine::staticModel('event')::whereUserId($this->user_id)
            ->whereNotIn('event_type', ['account_backup', 'account_restore', 'account_reset'])
            ->get();

        $_SESSION['account_recovery']['backup'][$this->token]['dump']['extension_settings'] = DB::table('extension_settings')
            ->where('user_id', $this->user_id)
            ->get();

        // $_SESSION['account_recovery']['backup'][$this->token]['dump']['files'] = DB::table('files')
        //     ->where('user_id', $this->user_id)
        //     ->get();

        $_SESSION['account_recovery']['backup'][$this->token]['dump']['subscriptions_history'] = DB::table('subscriptions_history')
            ->where('user_id', $this->user_id)
            ->get();

        $_SESSION['account_recovery']['backup'][$this->token]['dump']['notifications_etx'] = NotificationEngine::staticModel('extension')::whereUserId($this->user_id)->get();
        $_SESSION['account_recovery']['backup'][$this->token]['dump']['event_chrome'] = EventChrome::whereUserId($this->user_id)->get();

        $_SESSION['account_recovery']['backup'][$this->token]['dump']['push_notification_queue'] = NotificationEngine::staticModel('browser')::whereUserId($this->user_id)->get();
        $_SESSION['account_recovery']['backup'][$this->token]['dump']['event_browser'] = EventBrowser::whereUserId($this->user_id)->get();
        $_SESSION['account_recovery']['backup'][$this->token]['dump']['event_emails'] = EventEmail::whereUserId($this->user_id)->get();

        $_SESSION['account_recovery']['backup'][$this->token]['dump']['push_notification_registers'] = DB::table('push_notification_registers')
            ->where('user_id', $this->user_id)
            ->get();

        $_SESSION['account_recovery']['backup'][$this->token]['dump']['tokens'] = DB::table('tokens')
            ->where('user_id', $this->user_id)
            ->get();

        $_SESSION['account_recovery']['backup'][$this->token]['dump']['users_tour_status'] = DB::table('users_tour_status')
            ->where('user_id', $this->user_id)
            ->get();

        $_SESSION['account_recovery']['backup'][$this->token]['dump']['webhook_logs'] = DB::table('webhook_logs')
            ->where('user_id', $this->user_id)
            ->get();

        // Store data in JSON file
        $this->store_backup_data('dump');



        // Backup all other files
        $data_source_path = 'storage/app/client/1/user/' . Auth::id();
        $data_backup_path = 'storage/app/' . $this->backup_dir_name . '/media';

        // Create backup directory if it doesn't exist
        if (!file_exists($data_backup_path)) {
            mkdir($data_backup_path, 0777, true);
        }


        // Backup media files
        $all_files = Storage::disk('local')->files('client/1/user/' . Auth::id());
        if (!empty($all_files)) {
            foreach ($all_files as $file) {
                $file_name = basename($file);
                $file_path = $data_source_path . '/' . $file_name;
                $file_backup_path = $data_backup_path . '/' . $file_name;

                if (File::exists($file_path)) {
                    File::copy($file_path, $file_backup_path);
                }
            }
        }

        // Backup all folders
        $all_folders = Storage::disk('local')->directories('client/1/user/' . Auth::id());
        if (!empty($all_folders)) {
            foreach ($all_folders as $folder) {
                $folder_name = basename($folder);

                // Skip backup folder
                if (in_array($folder_name, ['recovery'])) {
                    continue;
                }

                $folder_path = $data_source_path . '/' . $folder_name;
                $folder_backup_path = $data_backup_path . '/' . $folder_name;

                if (File::exists($folder_path)) {
                    File::copyDirectory($folder_path, $folder_backup_path);
                }
            }
        }


        $output['status'] = true;
        $output['message'] = $this->backup_commands[$command]['message']['complete'];
        $output['next_command'] = $this->backup_commands[$command]['next_command'];

        return $this->backup_response($output);
    }

    private function cmd_backup_settings(Request $request, $command)
    {
        $output = [
            'command' => $command,
        ];

        $users = DB::table('users')
            ->where('id', $this->user_id)
            ->first();

        $users_profile = DB::table('users_profile')
            ->where('user_id', $this->user_id)
            ->first();

        // Backup user's data
        if (!empty($users->id)) {

            // Disabled for copying the entire folder
            // Backup user's avatar
            // $image_relative_path = $users->image;
            // if (!empty($image_relative_path)) {

            //     // Check if image exists
            //     if (Storage::exists($image_relative_path)) {
            //         $image_extension = pathinfo($image_relative_path, PATHINFO_EXTENSION);

            //         // Copy image to backup folder
            //         $image_backup_path = $this->backup_dir_name . '/media/avatar.' . $image_extension;

            //         if (!Storage::exists($image_backup_path)) {
            //             Storage::copy($image_relative_path, $image_backup_path);
            //             $users->_avatar_path = $this->backup_dir_name . '/media/avatar.' . $image_extension;
            //             $users->_avatar_filename = 'avatar.' . $image_extension;
            //         }
            //     }
            // }

            // Store data in session
            $_SESSION['account_recovery']['backup'][$this->token]['settings']['users'] = $users;
        }

        // Backup user's profile data
        if (!empty($users_profile->id)) {

            // Store data in session
            $_SESSION['account_recovery']['backup'][$this->token]['settings']['users_profile'] = $users_profile;
        }

        // Default data
        $_SESSION['account_recovery']['backup'][$this->token]['settings']['backup']['item']['subscription']['count'] = 0;
        $_SESSION['account_recovery']['backup'][$this->token]['settings']['backup']['item']['folder']['count'] = 0;
        $_SESSION['account_recovery']['backup'][$this->token]['settings']['backup']['item']['tag']['count'] = 0;
        $_SESSION['account_recovery']['backup'][$this->token]['settings']['backup']['item']['payment_method']['count'] = 0;
        $_SESSION['account_recovery']['backup'][$this->token]['settings']['backup']['item']['contact']['count'] = 0;
        $_SESSION['account_recovery']['backup'][$this->token]['settings']['backup']['item']['alert_profile']['count'] = 0;
        $_SESSION['account_recovery']['backup'][$this->token]['settings']['backup']['item']['api_token']['count'] = 0;
        $_SESSION['account_recovery']['backup'][$this->token]['settings']['backup']['item']['webhook']['count'] = 0;
        $_SESSION['account_recovery']['backup'][$this->token]['settings']['backup']['item']['settings']['count'] = 0;

        // Count backup items
        if (isset($_SESSION['account_recovery']['backup'][$this->token]['subscription']) && is_array($_SESSION['account_recovery']['backup'][$this->token]['subscription'])) {
            $_SESSION['account_recovery']['backup'][$this->token]['settings']['backup']['item']['subscription']['count'] = count($_SESSION['account_recovery']['backup'][$this->token]['subscription']);
            $_SESSION['account_recovery']['backup'][$this->token]['settings']['backup']['item']['subscription']['filename'] = 'subscription.json';
            $_SESSION['account_recovery']['backup'][$this->token]['settings']['backup']['item']['subscription']['path'] = $this->backup_dir_name . '/subscription.json';
        }

        if (isset($_SESSION['account_recovery']['backup'][$this->token]['subscription_attachment']) && is_array($_SESSION['account_recovery']['backup'][$this->token]['subscription_attachment'])) {
            $_SESSION['account_recovery']['backup'][$this->token]['settings']['backup']['item']['subscription_attachment']['count'] = count($_SESSION['account_recovery']['backup'][$this->token]['subscription_attachment']);
            $_SESSION['account_recovery']['backup'][$this->token]['settings']['backup']['item']['subscription_attachment']['filename'] = 'subscription_attachment.json';
            $_SESSION['account_recovery']['backup'][$this->token]['settings']['backup']['item']['subscription_attachment']['path'] = $this->backup_dir_name . '/subscription_attachment.json';
        }

        if (isset($_SESSION['account_recovery']['backup'][$this->token]['subscription_marketplace']) && is_array($_SESSION['account_recovery']['backup'][$this->token]['subscription_marketplace'])) {
            $_SESSION['account_recovery']['backup'][$this->token]['settings']['backup']['item']['subscription_marketplace']['count'] = count($_SESSION['account_recovery']['backup'][$this->token]['subscription_marketplace']);
            $_SESSION['account_recovery']['backup'][$this->token]['settings']['backup']['item']['subscription_marketplace']['filename'] = 'subscription_marketplace.json';
            $_SESSION['account_recovery']['backup'][$this->token]['settings']['backup']['item']['subscription_marketplace']['path'] = $this->backup_dir_name . '/subscription_marketplace.json';
        }

        if (isset($_SESSION['account_recovery']['backup'][$this->token]['folder']) && is_array($_SESSION['account_recovery']['backup'][$this->token]['folder'])) {
            $_SESSION['account_recovery']['backup'][$this->token]['settings']['backup']['item']['folder']['count'] = count($_SESSION['account_recovery']['backup'][$this->token]['folder']);
            $_SESSION['account_recovery']['backup'][$this->token]['settings']['backup']['item']['folder']['filename'] = 'folder.json';
            $_SESSION['account_recovery']['backup'][$this->token]['settings']['backup']['item']['folder']['path'] = $this->backup_dir_name . '/folder.json';
        }

        if (isset($_SESSION['account_recovery']['backup'][$this->token]['tag']) && is_array($_SESSION['account_recovery']['backup'][$this->token]['tag'])) {
            $_SESSION['account_recovery']['backup'][$this->token]['settings']['backup']['item']['tag']['count'] = count($_SESSION['account_recovery']['backup'][$this->token]['tag']);
            $_SESSION['account_recovery']['backup'][$this->token]['settings']['backup']['item']['tag']['filename'] = 'tag.json';
            $_SESSION['account_recovery']['backup'][$this->token]['settings']['backup']['item']['tag']['path'] = $this->backup_dir_name . '/tag.json';
        }

        if (isset($_SESSION['account_recovery']['backup'][$this->token]['payment_method']) && is_array($_SESSION['account_recovery']['backup'][$this->token]['payment_method'])) {
            $_SESSION['account_recovery']['backup'][$this->token]['settings']['backup']['item']['payment_method']['count'] = count($_SESSION['account_recovery']['backup'][$this->token]['payment_method']);
            $_SESSION['account_recovery']['backup'][$this->token]['settings']['backup']['item']['payment_method']['filename'] = 'payment_method.json';
            $_SESSION['account_recovery']['backup'][$this->token]['settings']['backup']['item']['payment_method']['path'] = $this->backup_dir_name . '/payment_method.json';
        }

        if (isset($_SESSION['account_recovery']['backup'][$this->token]['contact']) && is_array($_SESSION['account_recovery']['backup'][$this->token]['contact'])) {
            $_SESSION['account_recovery']['backup'][$this->token]['settings']['backup']['item']['contact']['count'] = count($_SESSION['account_recovery']['backup'][$this->token]['contact']);
            $_SESSION['account_recovery']['backup'][$this->token]['settings']['backup']['item']['contact']['filename'] = 'contact.json';
            $_SESSION['account_recovery']['backup'][$this->token]['settings']['backup']['item']['contact']['path'] = $this->backup_dir_name . '/contact.json';
        }

        if (isset($_SESSION['account_recovery']['backup'][$this->token]['alert_profile']) && is_array($_SESSION['account_recovery']['backup'][$this->token]['alert_profile'])) {
            $_SESSION['account_recovery']['backup'][$this->token]['settings']['backup']['item']['alert_profile']['count'] = count($_SESSION['account_recovery']['backup'][$this->token]['alert_profile']);
            $_SESSION['account_recovery']['backup'][$this->token]['settings']['backup']['item']['alert_profile']['filename'] = 'alert_profile.json';
            $_SESSION['account_recovery']['backup'][$this->token]['settings']['backup']['item']['alert_profile']['path'] = $this->backup_dir_name . '/alert_profile.json';
        }

        if (isset($_SESSION['account_recovery']['backup'][$this->token]['api_token']) && is_array($_SESSION['account_recovery']['backup'][$this->token]['api_token'])) {
            $_SESSION['account_recovery']['backup'][$this->token]['settings']['backup']['item']['api_token']['count'] = count($_SESSION['account_recovery']['backup'][$this->token]['api_token']);
            $_SESSION['account_recovery']['backup'][$this->token]['settings']['backup']['item']['api_token']['filename'] = 'api_token.json';
            $_SESSION['account_recovery']['backup'][$this->token]['settings']['backup']['item']['api_token']['path'] = $this->backup_dir_name . '/api_token.json';
        }

        if (isset($_SESSION['account_recovery']['backup'][$this->token]['webhook']) && is_array($_SESSION['account_recovery']['backup'][$this->token]['webhook'])) {
            $_SESSION['account_recovery']['backup'][$this->token]['settings']['backup']['item']['webhook']['count'] = count($_SESSION['account_recovery']['backup'][$this->token]['webhook']);
            $_SESSION['account_recovery']['backup'][$this->token]['settings']['backup']['item']['webhook']['filename'] = 'webhook.json';
            $_SESSION['account_recovery']['backup'][$this->token]['settings']['backup']['item']['webhook']['path'] = $this->backup_dir_name . '/webhook.json';
        }

        if (isset($_SESSION['account_recovery']['backup'][$this->token]['settings']) && is_array($_SESSION['account_recovery']['backup'][$this->token]['settings'])) {
            $_SESSION['account_recovery']['backup'][$this->token]['settings']['backup']['item']['settings']['count'] = count($_SESSION['account_recovery']['backup'][$this->token]['settings']);
            $_SESSION['account_recovery']['backup'][$this->token]['settings']['backup']['item']['settings']['filename'] = 'settings.json';
            $_SESSION['account_recovery']['backup'][$this->token]['settings']['backup']['item']['settings']['path'] = $this->backup_dir_name . '/settings.json';
        }

        // Store backup stats
        $this->backup_end_time = time();
        $_SESSION['account_recovery']['backup'][$this->token]['settings']['backup']['user_id'] = $this->user_id;
        $_SESSION['account_recovery']['backup'][$this->token]['settings']['backup']['token'] = $this->token;
        $_SESSION['account_recovery']['backup'][$this->token]['settings']['backup']['timezone'] = LARAVEL_TIMEZONE;
        $_SESSION['account_recovery']['backup'][$this->token]['settings']['backup']['timestamp'] = $this->backup_timestamp;
        $_SESSION['account_recovery']['backup'][$this->token]['settings']['backup']['start_time'] = date('Y-m-d H:i:s', $this->backup_timestamp);
        $_SESSION['account_recovery']['backup'][$this->token]['settings']['backup']['end_time'] = date('Y-m-d H:i:s', $this->backup_end_time);
        $_SESSION['account_recovery']['backup'][$this->token]['settings']['backup']['duration'] = $this->backup_end_time - $this->backup_timestamp;
        $_SESSION['account_recovery']['backup'][$this->token]['settings']['backup']['status'] = true;

        // Store data in JSON file
        $this->store_backup_data('settings');

        // Return status
        $output['status'] = true;
        $output['message'] = $this->backup_commands[$command]['message']['complete'];
        $output['next_command'] = $this->backup_commands[$command]['next_command'];

        return $this->backup_response($output);
    }

    private function cmd_backup_success(Request $request, $command)
    {
        $output = [
            'command' => $command,
        ];

        // Update event logs
        NotificationEngine::staticModel('event')::do_update($this->event_id, [
            'event_status' => 1,
        ]);

        // Get backup duration
        $duration = 0;
        if (isset($_SESSION['account_recovery']['backup'][$this->token]['settings']['backup']['duration'])) {
            $duration = $_SESSION['account_recovery']['backup'][$this->token]['settings']['backup']['duration'];
            $output['message'] = $this->backup_commands[$command]['message']['complete'] . $duration . ' seconds.';
        } else {
            $output['message'] = $this->backup_commands[$command]['message']['success'];
        }

        // Reset data
        unset($_SESSION['account_recovery']['backup'][$this->token]);

        $output['status'] = true;
        $output['next_command'] = $this->backup_commands[$command]['next_command'];

        return $this->backup_response($output);
    }

    public function backup_delete(Request $request)
    {
        // Check if another recovery is in progress
        if ($this->is_recovery_in_progress()) {
            return Response::json([
                'status' => false,
                'message' => $this->get_in_progress_msg(),
            ]);
        }

        if (Storage::disk('local')->exists($this->old_backup_dir_name)) {
            Storage::disk('local')->deleteDirectory($this->old_backup_dir_name);
        }

        return Response::json([
            'status' => true,
            'message' => 'Success',
        ], 200);
    }

    /*
    //
    //
    //
    //
    // Restore
    //
    //
    //
    //
    //
    //
     */

    public function restore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required|string|size:32',
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
        $this->token = $request->input('token');
        $this->event_id = $request->input('event_id');
        $command = $request->input('command');

        // Check if another recovery is in progress
        if ($this->is_recovery_in_progress()) {
            return Response::json([
                'status' => false,
                'message' => $this->get_in_progress_msg(),
            ]);
        }

        // Get restore directory
        if (isset($_SESSION['account_recovery']['restore'][$this->token]['restore_dir_name'])) {
            $this->restore_dir_name = $_SESSION['account_recovery']['restore'][$this->token]['restore_dir_name'];
            $this->restore_dir_path = $_SESSION['account_recovery']['restore'][$this->token]['restore_dir_path'];
            $this->restore_timestamp = intval(basename($this->restore_dir_name));
        }

        switch ($command) {

                // Reset commands
            case 'check_existence':
                return $this->cmd_restore_check_existence($request, $command);
                break;

            case 'restore_validate':
                return $this->cmd_restore_validate($request, $command);
                break;

            case 'get_account_info':
                return $this->cmd_restore_get_account_info($request, $command);
                break;

            case 'delete_subscription':
                return $this->cmd_restore_delete_subscription($request, $command);
                break;

            case 'delete_folder':
                return $this->cmd_restore_delete_folder($request, $command);
                break;

            case 'delete_tag':
                return $this->cmd_restore_delete_tag($request, $command);
                break;

            case 'delete_payment_method':
                return $this->cmd_restore_delete_payment_method($request, $command);
                break;

            case 'delete_contact':
                return $this->cmd_restore_delete_contact($request, $command);
                break;

            case 'delete_alert_profile':
                return $this->cmd_restore_delete_alert_profile($request, $command);
                break;

            case 'delete_api_token':
                return $this->cmd_restore_delete_api_token($request, $command);
                break;

            case 'delete_webhook':
                return $this->cmd_restore_delete_webhook($request, $command);
                break;

                // case 'delete_team_account':
                //     return $this->cmd_restore_delete_team_account($request, $command);
                //     break;

                // case 'delete_file_all':
                //     return $this->cmd_restore_delete_file_all($request, $command);
                //     break;

            case 'reset_success':
                return $this->cmd_restore_reset_success($request, $command);
                break;

                // Restore commands
            case 'get_backup_info':
                return $this->cmd_restore_get_backup_info($request, $command);
                break;
            case 'restore_subscription':
                return $this->cmd_restore_subscription($request, $command);
                break;

            case 'restore_folder':
                return $this->cmd_restore_folder($request, $command);
                break;

            case 'restore_tag':
                return $this->cmd_restore_tag($request, $command);
                break;

            case 'restore_payment_method':
                return $this->cmd_restore_payment_method($request, $command);
                break;

            case 'restore_contact':
                return $this->cmd_restore_contact($request, $command);
                break;

            case 'restore_alert_profile':
                return $this->cmd_restore_alert_profile($request, $command);
                break;

            case 'restore_api_token':
                return $this->cmd_restore_api_token($request, $command);
                break;

            case 'restore_webhook':
                return $this->cmd_restore_webhook($request, $command);
                break;

            case 'restore_settings':
                return $this->cmd_restore_settings($request, $command);
                break;

                // case 'restore_team_account':
                //     return $this->cmd_restore_team_account($request, $command);
                //     break;

            case 'restore_file_all':
                return $this->cmd_restore_file_all($request, $command);
                break;

            case 'restore_success':
                return $this->cmd_restore_success($request, $command);
                break;

            default:
                return $this->restore_response([
                    'status' => false,
                    'message' => 'Command not found',
                ]);
        }
    }

    private function cmd_restore_check_existence(Request $request, $command)
    {
        $output = [
            'command' => $command,
        ];
        $backup_status = 'not_found';

        $output['event_id'] = $this->add_event([
            'event_type' => 'account_restore',
            'event_status' => 0,
        ]);

        // Reset data
        $_SESSION['account_recovery']['restore'] = [];
        $_SESSION['account_recovery']['restore'][$this->token] = [];
        $_SESSION['account_recovery']['restore'][$this->token]['_metadata']['timestamp'] = time();

        // Check if backup files found
        if (!empty($this->old_backup_dir_name)) {

            // Set restore directory
            $_SESSION['account_recovery']['restore'][$this->token]['restore_dir_name'] = $this->old_backup_dir_name;
            $_SESSION['account_recovery']['restore'][$this->token]['restore_dir_path'] = storage_path($this->old_backup_dir_name);

            $_SESSION['account_recovery']['restore'][$this->token]['subscription'] = [];
            $_SESSION['account_recovery']['restore'][$this->token]['subscription_attachment'] = [];
            $_SESSION['account_recovery']['restore'][$this->token]['subscription_marketplace'] = [];
            $_SESSION['account_recovery']['restore'][$this->token]['folder'] = [];
            $_SESSION['account_recovery']['restore'][$this->token]['tag'] = [];
            $_SESSION['account_recovery']['restore'][$this->token]['payment_method'] = [];
            $_SESSION['account_recovery']['restore'][$this->token]['contact'] = [];
            $_SESSION['account_recovery']['restore'][$this->token]['alert_profile'] = [];
            $_SESSION['account_recovery']['restore'][$this->token]['api_token'] = [];
            $_SESSION['account_recovery']['restore'][$this->token]['webhook'] = [];
            $_SESSION['account_recovery']['restore'][$this->token]['dump'] = [];
            $_SESSION['account_recovery']['restore'][$this->token]['settings'] = [];

            // Get all files in restore directory and store data in session
            $subscription_data = $this->get_json_file_data('subscription');
            if (!empty($subscription_data) && is_array($subscription_data)) {
                $_SESSION['account_recovery']['restore'][$this->token]['subscription'] = $subscription_data;
            }

            $subscription_attachment_data = $this->get_json_file_data('subscription_attachment');
            if (!empty($subscription_attachment_data) && is_array($subscription_attachment_data)) {
                $_SESSION['account_recovery']['restore'][$this->token]['subscription_attachment'] = $subscription_attachment_data;
            }

            $subscription_marketplace_data = $this->get_json_file_data('subscription_marketplace');
            if (!empty($subscription_marketplace_data) && is_array($subscription_marketplace_data)) {
                $_SESSION['account_recovery']['restore'][$this->token]['subscription_marketplace'] = $subscription_marketplace_data;
            }

            $folder_data = $this->get_json_file_data('folder');
            if (!empty($folder_data) && is_array($folder_data)) {
                $_SESSION['account_recovery']['restore'][$this->token]['folder'] = $folder_data;
            }

            $tag_data = $this->get_json_file_data('tag');
            if (!empty($tag_data) && is_array($tag_data)) {
                $_SESSION['account_recovery']['restore'][$this->token]['tag'] = $tag_data;
            }

            $payment_method_data = $this->get_json_file_data('payment_method');
            if (!empty($payment_method_data) && is_array($payment_method_data)) {
                $_SESSION['account_recovery']['restore'][$this->token]['payment_method'] = $payment_method_data;
            }

            $contact_data = $this->get_json_file_data('contact');
            if (!empty($contact_data) && is_array($contact_data)) {
                $_SESSION['account_recovery']['restore'][$this->token]['contact'] = $contact_data;
            }

            $alert_profile_data = $this->get_json_file_data('alert_profile');
            if (!empty($alert_profile_data) && is_array($alert_profile_data)) {
                $_SESSION['account_recovery']['restore'][$this->token]['alert_profile'] = $alert_profile_data;
            }

            $api_token_data = $this->get_json_file_data('api_token');
            if (!empty($api_token_data) && is_array($api_token_data)) {
                $_SESSION['account_recovery']['restore'][$this->token]['api_token'] = $api_token_data;
            }

            $webhook_data = $this->get_json_file_data('webhook');
            if (!empty($webhook_data) && is_array($webhook_data)) {
                $_SESSION['account_recovery']['restore'][$this->token]['webhook'] = $webhook_data;
            }

            $dump_data = $this->get_json_file_data('dump');
            if (!empty($dump_data) && is_array($dump_data)) {
                $_SESSION['account_recovery']['restore'][$this->token]['dump'] = $dump_data;
            }

            $settings_data = $this->get_json_file_data('settings');
            if (!empty($settings_data) && is_array($settings_data)) {
                $_SESSION['account_recovery']['restore'][$this->token]['settings'] = $settings_data;
            } else {
                $backup_status = 'corrupted';
            }

            if (isset($settings_data['backup']['status']) && $settings_data['backup']['status'] == true) {
                $backup_status = 'okay';
            } else {
                $backup_status = 'corrupted';
            }
        }

        // Check if backup files found and backup is okay
        if ($backup_status == 'okay') {
            $output['status'] = true;
            $output['message'] = $this->restore_commands[$command]['message']['success'];
            $output['next_command'] = $this->restore_commands[$command]['next_command'];
        }

        // Check if backup files are corrupted
        elseif ($backup_status == 'corrupted') {
            $output['status'] = false;
            $output['message'] = $this->restore_commands[$command]['message']['corrupted'];
            $output['next_command'] = '';
        }

        // Check if backup files not found
        else {
            $output['status'] = false;
            $output['message'] = $this->restore_commands[$command]['message']['not_found'];
            $output['next_command'] = '';
        }

        // Return status
        return $this->restore_response($output);
    }

    private function cmd_restore_validate(Request $request, $command)
    {
        $output = [
            'command' => $command,
        ];

        $validation = true;
        dd($command);
        $settings_data = [];
        if ($validation && isset($_SESSION['account_recovery']['restore'][$this->token]['settings'])) {
            $settings_data = $_SESSION['account_recovery']['restore'][$this->token]['settings'];

            if (empty($settings_data) || !is_array($settings_data)) {
                $validation = false;
            }

            if ($validation && (!isset($settings_data['backup']['status']) || $settings_data['backup']['status'] != true)) {
                $validation = false;
            }

            $validator = Validator::make($settings_data['backup'], [
                'status' => 'required|boolean|accepted',
                'user_id' => 'required|integer|in:' . Auth::user()->id,
                'timezone' => 'required|string|max:255',
                'timestamp' => 'required|integer',
                'start_time' => 'required|date_format:Y-m-d H:i:s',
                'end_time' => 'required|date_format:Y-m-d H:i:s|after_or_equal:start_time',
                'duration' => 'required|integer',
            ]);

            if ($validator->fails()) {
                $validation = false;
            }
        } else {
            $validation = false;
        }

        if ($validation && isset($settings_data['backup']['item']['subscription']['count'])) {
        } else {
            $validation = false;
        }

        if ($validation) {

            // Get all files in restore directory
            $subscription_data = $this->get_json_file_data('subscription');
            $folder_data = $this->get_json_file_data('folder');
            $tag_data = $this->get_json_file_data('tag');
            $payment_method_data = $this->get_json_file_data('payment_method');
            $contact_data = $this->get_json_file_data('contact');
            $alert_profile_data = $this->get_json_file_data('alert_profile');
            $api_token_data = $this->get_json_file_data('api_token');
            $webhook_data = $this->get_json_file_data('webhook');

            // Check if all files are found
            if (!is_array($subscription_data) || !isset($settings_data['backup']['item']['subscription']['count']) || $settings_data['backup']['item']['subscription']['count'] != count($subscription_data)) {
                $validation = false;
            }

            if (!is_array($folder_data) || !isset($settings_data['backup']['item']['folder']['count']) || $settings_data['backup']['item']['folder']['count'] != count($folder_data)) {
                $validation = false;
            }

            if (!is_array($tag_data) || !isset($settings_data['backup']['item']['tag']['count']) || $settings_data['backup']['item']['tag']['count'] != count($tag_data)) {
                $validation = false;
            }

            if (!is_array($payment_method_data) || !isset($settings_data['backup']['item']['payment_method']['count']) || $settings_data['backup']['item']['payment_method']['count'] != count($payment_method_data)) {
                $validation = false;
            }

            if (!is_array($contact_data) || !isset($settings_data['backup']['item']['contact']['count']) || $settings_data['backup']['item']['contact']['count'] != count($contact_data)) {
                $validation = false;
            }

            if (!is_array($alert_profile_data) || !isset($settings_data['backup']['item']['alert_profile']['count']) || $settings_data['backup']['item']['alert_profile']['count'] != count($alert_profile_data)) {
                $validation = false;
            }

            if (!is_array($api_token_data) || !isset($settings_data['backup']['item']['api_token']['count']) || $settings_data['backup']['item']['api_token']['count'] != count($api_token_data)) {
                $validation = false;
            }

            if (!is_array($webhook_data) || !isset($settings_data['backup']['item']['webhook']['count']) || $settings_data['backup']['item']['webhook']['count'] != count($webhook_data)) {
                $validation = false;
            }
        }

        // Return status
        if ($validation) {
            $output['status'] = true;
            $output['message'] = $this->restore_commands[$command]['message']['success'];
            $output['next_command'] = $this->restore_commands[$command]['next_command'];
        } else {
            $output['status'] = false;
            $output['message'] = $this->restore_commands[$command]['message']['failure'];
            $output['next_command'] = '';
        }

        return $this->restore_response($output);
    }

    private function cmd_restore_get_account_info(Request $request, $command)
    {
        $output = [
            'command' => $command,
        ];

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

        // $output['data']['team_account_count'] = DB::table('users_teams')
        //     ->where('team_user_id', $this->user_id)
        //     ->get()
        //     ->count();

        if (empty($output['data'])) {
            $output['status'] = false;
            $output['message'] = $this->restore_commands[$command]['message']['failure'];
        } else {

            $output['status'] = true;
            $output['message'] = $this->restore_commands[$command]['message']['success'];
            $output['next_command'] = $this->restore_commands[$command]['next_command'];
        }

        return $this->restore_response($output);
    }

    private function cmd_restore_delete_subscription(Request $request, $command)
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
            $output['message'] = $this->restore_commands[$command]['message']['complete'];
            $output['next_command'] = $this->restore_commands[$command]['next_command'];
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

            $output['status'] = true;
            $output['message'] = $this->restore_commands[$command]['message']['success'] . $subscription->product_name;
            $output['next_command'] = $command;
        }

        return $this->restore_response($output);
    }

    private function cmd_restore_delete_folder(Request $request, $command)
    {
        $output = [
            'command' => $command,
        ];

        // Get folder
        $folder = DB::table('folder')
            ->where('user_id', $this->user_id)
            ->get()
            ->first();

        if (empty($folder->id)) {

            $output['status'] = true;
            $output['message'] = $this->restore_commands[$command]['message']['complete'];
            $output['next_command'] = $this->restore_commands[$command]['next_command'];
        } else {

            // Delete folder
            FolderModel::del($folder->id);

            $output['status'] = true;
            $output['message'] = $this->restore_commands[$command]['message']['success'] . $folder->name;
            $output['next_command'] = $command;
        }

        return $this->restore_response($output);
    }

    private function cmd_restore_delete_tag(Request $request, $command)
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
            $output['message'] = $this->restore_commands[$command]['message']['complete'];
            $output['next_command'] = $this->restore_commands[$command]['next_command'];
        } else {

            // Delete tag
            TagModel::del($tag->id);

            $output['status'] = true;
            $output['message'] = $this->restore_commands[$command]['message']['success'] . $tag->name;
            $output['next_command'] = $command;
        }

        return $this->restore_response($output);
    }

    private function cmd_restore_delete_payment_method(Request $request, $command)
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
            $output['message'] = $this->restore_commands[$command]['message']['complete'];
            $output['next_command'] = $this->restore_commands[$command]['next_command'];
        } else {

            // Delete payment method
            PaymentMethodModel::del($payment_method->id);

            $output['status'] = true;
            $output['message'] = $this->restore_commands[$command]['message']['success'] . $payment_method->name;
            $output['next_command'] = $command;
        }

        return $this->restore_response($output);
    }

    private function cmd_restore_delete_contact(Request $request, $command)
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
            $output['message'] = $this->restore_commands[$command]['message']['complete'];
            $output['next_command'] = $this->restore_commands[$command]['next_command'];
        } else {

            // Delete contact
            UserModel::contact_delete($contact->id);

            $output['status'] = true;
            $output['message'] = $this->restore_commands[$command]['message']['success'] . $contact->name;
            $output['next_command'] = $command;
        }

        return $this->restore_response($output);
    }

    private function cmd_restore_delete_alert_profile(Request $request, $command)
    {
        $output = [
            'command' => $command,
        ];

        // Get alert
        $alert = DB::table('users_alert')
            ->where('user_id', $this->user_id)
            ->get()
            ->first();

        if (empty($alert->id)) {

            $output['status'] = true;
            $output['message'] = $this->restore_commands[$command]['message']['complete'];
            $output['next_command'] = $this->restore_commands[$command]['next_command'];
        } else {

            // Delete alert profile
            UserAlert::find($alert->id)->delete();

            $output['status'] = true;
            $output['message'] = $this->restore_commands[$command]['message']['success'] . $alert->alert_name;
            $output['next_command'] = $command;
        }

        return $this->restore_response($output);
    }

    private function cmd_restore_delete_api_token(Request $request, $command)
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
            $output['message'] = $this->restore_commands[$command]['message']['complete'];
            $output['next_command'] = $this->restore_commands[$command]['next_command'];
        } else {

            // Delete API token
            ApiTokenModel::del($api_token->id);

            $output['status'] = true;
            $output['message'] = $this->restore_commands[$command]['message']['success'] . $api_token->name;
            $output['next_command'] = $command;
        }

        return $this->restore_response($output);
    }

    private function cmd_restore_delete_webhook(Request $request, $command)
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
            $output['message'] = $this->restore_commands[$command]['message']['complete'];
            $output['next_command'] = $this->restore_commands[$command]['next_command'];
        } else {

            // Delete webhook
            Webhook::where('id', $webhook->id)->delete();

            $output['status'] = true;
            $output['message'] = $this->restore_commands[$command]['message']['success'] . $webhook->name;
            $output['next_command'] = $command;
        }

        return $this->restore_response($output);
    }

    private function cmd_restore_reset_success(Request $request, $command)
    {
        $output = [
            'command' => $command,
        ];

        // Delete data from all tables
        $this->clean_tables();
        $this->clean_files();

        $output['status'] = true;
        $output['message'] = $this->restore_commands[$command]['message']['complete'];
        $output['next_command'] = $this->restore_commands[$command]['next_command'];

        return $this->restore_response($output);
    }

    private function cmd_restore_get_backup_info(Request $request, $command)
    {
        $output = [
            'command' => $command,
        ];

        // Check if backup files found
        if (empty($this->old_backup_dir_name)) {
            $output['status'] = true;
            $output['message'] = $this->restore_commands[$command]['message']['failure'];
            $output['next_command'] = '';
        } else {

            // Load data from session
            $settings_data = $_SESSION['account_recovery']['restore'][$this->token]['settings'];

            // Get restore timestamp
            $restore_date = Carbon::createFromTimestamp($settings_data['backup']['timestamp']);
            $restore_date->setTimezone(lib()->user->default->timezone_value);

            $output['data']['backup']['date'] = $restore_date->format('d F, Y \a\t h:i:s A');
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
            $output['data']['dump']['count'] = 0;

            if (isset($_SESSION['account_recovery']['restore'][$this->token]['subscription']) && is_array($_SESSION['account_recovery']['restore'][$this->token]['subscription'])) {
                $output['data']['subscription']['count'] = count($_SESSION['account_recovery']['restore'][$this->token]['subscription']);
            }

            if (isset($_SESSION['account_recovery']['restore'][$this->token]['subscription_attachment']) && is_array($_SESSION['account_recovery']['restore'][$this->token]['subscription_attachment'])) {
                $output['data']['subscription_attachment']['count'] = count($_SESSION['account_recovery']['restore'][$this->token]['subscription_attachment']);
            }

            if (isset($_SESSION['account_recovery']['restore'][$this->token]['subscription_marketplace']) && is_array($_SESSION['account_recovery']['restore'][$this->token]['subscription_marketplace'])) {
                $output['data']['subscription_marketplace']['count'] = count($_SESSION['account_recovery']['restore'][$this->token]['subscription_marketplace']);
            }

            if (isset($_SESSION['account_recovery']['restore'][$this->token]['folder']) && is_array($_SESSION['account_recovery']['restore'][$this->token]['folder'])) {
                $output['data']['folder']['count'] = count($_SESSION['account_recovery']['restore'][$this->token]['folder']);
            }

            if (isset($_SESSION['account_recovery']['restore'][$this->token]['tag']) && is_array($_SESSION['account_recovery']['restore'][$this->token]['tag'])) {
                $output['data']['tag']['count'] = count($_SESSION['account_recovery']['restore'][$this->token]['tag']);
            }

            if (isset($_SESSION['account_recovery']['restore'][$this->token]['payment_method']) && is_array($_SESSION['account_recovery']['restore'][$this->token]['payment_method'])) {
                $output['data']['payment_method']['count'] = count($_SESSION['account_recovery']['restore'][$this->token]['payment_method']);
            }

            if (isset($_SESSION['account_recovery']['restore'][$this->token]['contact']) && is_array($_SESSION['account_recovery']['restore'][$this->token]['contact'])) {
                $output['data']['contact']['count'] = count($_SESSION['account_recovery']['restore'][$this->token]['contact']);
            }

            if (isset($_SESSION['account_recovery']['restore'][$this->token]['alert_profile']) && is_array($_SESSION['account_recovery']['restore'][$this->token]['alert_profile'])) {
                $output['data']['alert_profile']['count'] = count($_SESSION['account_recovery']['restore'][$this->token]['alert_profile']);
            }

            if (isset($_SESSION['account_recovery']['restore'][$this->token]['api_token']) && is_array($_SESSION['account_recovery']['restore'][$this->token]['api_token'])) {
                $output['data']['api_token']['count'] = count($_SESSION['account_recovery']['restore'][$this->token]['api_token']);
            }

            if (isset($_SESSION['account_recovery']['restore'][$this->token]['webhook']) && is_array($_SESSION['account_recovery']['restore'][$this->token]['webhook'])) {
                $output['data']['webhook']['count'] = count($_SESSION['account_recovery']['restore'][$this->token]['webhook']);
            }

            if (isset($_SESSION['account_recovery']['restore'][$this->token]['settings']) && is_array($_SESSION['account_recovery']['restore'][$this->token]['settings'])) {
                $output['data']['settings']['count'] = count($_SESSION['account_recovery']['restore'][$this->token]['settings']);
            }

            if (isset($_SESSION['account_recovery']['restore'][$this->token]['dump']) && is_array($_SESSION['account_recovery']['restore'][$this->token]['dump'])) {
                $output['data']['dump']['count'] = count($_SESSION['account_recovery']['restore'][$this->token]['dump']);
            }

            $output['status'] = true;
            $output['message'] = $this->restore_commands[$command]['message']['success'];
            $output['next_command'] = $this->restore_commands[$command]['next_command'];
        }

        return $this->restore_response($output);
    }

    private function cmd_restore_file_all(Request $request, $command)
    {
        $output = [
            'command' => $command,
        ];

        if (isset($_SESSION['account_recovery']['restore'][$this->token]['dump']['events'])) {
            $events = $_SESSION['account_recovery']['restore'][$this->token]['dump']['events'];
            if (!empty($events) && is_array($events)) {
                NotificationEngine::staticModel('event')::insert($events);
            }
        }

        if (isset($_SESSION['account_recovery']['restore'][$this->token]['dump']['extension_settings'])) {
            $extension_settings = $_SESSION['account_recovery']['restore'][$this->token]['dump']['extension_settings'];
            if (!empty($extension_settings) && is_array($extension_settings)) {
                DB::table('extension_settings')->insert($extension_settings);
            }
        }

        // if (isset($_SESSION['account_recovery']['restore'][$this->token]['dump']['files'])) {
        //     $files = $_SESSION['account_recovery']['restore'][$this->token]['dump']['files'];
        //     if (!empty($files) && is_array($files)) {
        //         DB::table('files')->insert($files);
        //     }
        // }

        if (isset($_SESSION['account_recovery']['restore'][$this->token]['dump']['subscriptions_history'])) {
            $subscriptions_history = $_SESSION['account_recovery']['restore'][$this->token]['dump']['subscriptions_history'];
            if (!empty($subscriptions_history) && is_array($subscriptions_history)) {
                DB::table('subscriptions_history')->insert($subscriptions_history);
            }
        }

        if (isset($_SESSION['account_recovery']['restore'][$this->token]['dump']['notifications_etx'])) {
            $notifications_etx = $_SESSION['account_recovery']['restore'][$this->token]['dump']['notifications_etx'];
            if (!empty($notifications_etx) && is_array($notifications_etx)) {
                NotificationEngine::staticModel('extension')::insert($notifications_etx);
            }
        }
        if (isset($_SESSION['account_recovery']['restore'][$this->token]['dump']['event_chrome'])) {
            $event_chrome = $_SESSION['account_recovery']['restore'][$this->token]['dump']['event_chrome'];
            if (!empty($event_chrome) && is_array($event_chrome)) {
                EventChrome::insert($event_chrome);
            }
        }

        if (isset($_SESSION['account_recovery']['restore'][$this->token]['dump']['push_notification_queue'])) {
            $push_notification_queue = $_SESSION['account_recovery']['restore'][$this->token]['dump']['push_notification_queue'];
            if (!empty($push_notification_queue) && is_array($push_notification_queue)) {
                NotificationEngine::staticModel('browser')::insert($push_notification_queue);
            }
        }
        if (isset($_SESSION['account_recovery']['restore'][$this->token]['dump']['event_browser'])) {
            $event_browser = $_SESSION['account_recovery']['restore'][$this->token]['dump']['event_browser'];
            if (!empty($event_browser) && is_array($event_browser)) {
                EventBrowser::insert($event_browser);
            }
        }

        if (isset($_SESSION['account_recovery']['restore'][$this->token]['dump']['event_emails'])) {
            $event_emails = $_SESSION['account_recovery']['restore'][$this->token]['dump']['event_emails'];
            if (!empty($event_emails) && is_array($event_emails)) {
                EventEmail::insert($event_emails);
            }
        }

        if (isset($_SESSION['account_recovery']['restore'][$this->token]['dump']['push_notification_registers'])) {
            $push_notification_registers = $_SESSION['account_recovery']['restore'][$this->token]['dump']['push_notification_registers'];
            if (!empty($push_notification_registers) && is_array($push_notification_registers)) {
                DB::table('push_notification_registers')->insert($push_notification_registers);
            }
        }

        if (isset($_SESSION['account_recovery']['restore'][$this->token]['dump']['tokens'])) {
            $tokens = $_SESSION['account_recovery']['restore'][$this->token]['dump']['tokens'];
            if (!empty($tokens) && is_array($tokens)) {
                DB::table('tokens')->insert($tokens);
            }
        }

        if (isset($_SESSION['account_recovery']['restore'][$this->token]['dump']['users_tour_status'])) {
            $users_tour_status = $_SESSION['account_recovery']['restore'][$this->token]['dump']['users_tour_status'];
            if (!empty($users_tour_status) && is_array($users_tour_status)) {
                DB::table('users_tour_status')->insert($users_tour_status);
            }
        }

        if (isset($_SESSION['account_recovery']['restore'][$this->token]['dump']['webhook_logs'])) {
            $webhook_logs = $_SESSION['account_recovery']['restore'][$this->token]['dump']['webhook_logs'];
            if (!empty($webhook_logs) && is_array($webhook_logs)) {
                DB::table('webhook_logs')->insert($webhook_logs);
            }
        }


        // Restore all files
        $data_backup_path = 'storage/app/' . $this->old_backup_dir_name . '/media';
        $data_destination_path = 'storage/app/client/1/user/' . Auth::id();

        // Restore media files
        $all_files = Storage::disk('local')->files($this->old_backup_dir_name . '/media');
        if (!empty($all_files)) {
            foreach ($all_files as $file) {
                $file_name = basename($file);
                $file_path = $data_backup_path . '/' . $file_name;
                $file_restore_path = $data_destination_path . '/' . $file_name;

                if (File::exists($file_path)) {
                    File::copy($file_path, $file_restore_path);
                }
            }
        }

        // Restore all folders
        $all_folders = Storage::disk('local')->directories($this->old_backup_dir_name . '/media');
        if (!empty($all_folders)) {
            foreach ($all_folders as $folder) {
                $folder_name = basename($folder);

                // Skip backup folder
                if (in_array($folder_name, ['recovery'])) {
                    continue;
                }

                $folder_path = $data_backup_path . '/' . $folder_name;
                $folder_restore_path = $data_destination_path . '/' . $folder_name;

                if (File::exists($folder_path)) {
                    File::copyDirectory($folder_path, $folder_restore_path);
                }
            }
        }


        $output['status'] = true;
        $output['message'] = $this->restore_commands[$command]['message']['complete'];
        $output['next_command'] = $this->restore_commands[$command]['next_command'];

        return $this->restore_response($output);
    }

    private function cmd_restore_subscription(Request $request, $command)
    {
        $output = [
            'command' => $command,
        ];

        if (empty($_SESSION['account_recovery']['restore'][$this->token]['subscription'])) {

            $output['status'] = true;
            $output['message'] = $this->restore_commands[$command]['message']['complete'];
            $output['next_command'] = $this->restore_commands[$command]['next_command'];
        } else {

            $subscription_data = [];
            $subscription_key = null;
            foreach ($_SESSION['account_recovery']['restore'][$this->token]['subscription'] as $subscription_key => $subscription_data) {
                break;
            }

            if (!empty($subscription_data) && is_array($subscription_data)) {

                // Disabled for copying the entire folder
                // Search and replace the image
                // if (!empty($subscription_data['_image_filename'])) {

                //     $image_source_path = $this->old_backup_dir_name . '/media/subscription/image/' . $subscription_data['_image_filename'];
                //     $image_destination_path = $subscription_data['image'];

                //     if (Storage::exists($image_source_path)) {
                //         Storage::copy($image_source_path, $image_destination_path);
                //     }
                // }

                // Insert subscription data
                unset($subscription_data['_image_path'], $subscription_data['_image_filename']);
                $product = null;
                if ($subscription_data['brand_id']) {
                    $product = ProductModel::where('status', 1)->find($subscription_data['brand_id']);
                } elseif ($subscription_data['product_name']) {
                    $product = ProductModel::where('status', 1)->where('product_name', $subscription_data['product_name'])->first();
                }
                if (!$product) {
                    $product = ProductModel::find(1);
                }
                if ($product) {
                    if (!isset($subscription_data['brand_id']) || !$subscription_data['brand_id']) {
                        $subscription_data['brand_id'] = $product->id;
                    }
                    if (!isset($subscription_data['product_name']) || !$subscription_data['product_name']) {
                        $subscription_data['product_name'] = $product->product_name;
                    }
                    if (!isset($subscription_data['brandname']) || !$subscription_data['brandname']) {
                        $subscription_data['brandname'] = $product->brandname;
                    }
                    if (!isset($subscription_data['product_type']) || !$subscription_data['product_type']) {
                        $subscription_data['product_type'] = $product->product_type;
                    }
                    if (!isset($subscription_data['currency_code']) || !$subscription_data['currency_code']) {
                        $subscription_data['currency_code'] = $product->currency_code;
                    }
                    if (!isset($subscription_data['refund_days'])) {
                        $subscription_data['refund_days'] = $product->refund_days;
                    }
                    if (!isset($subscription_data['type']) || !$subscription_data['type']) {
                        $subscription_data['type'] = $product->pricing_type ?: 1;
                    }
                    if (!isset($subscription_data['status'])) {
                        $subscription_data['status'] = 0;
                    }
                    if (!isset($subscription_data['billing_frequency']) || !$subscription_data['billing_frequency']) {
                        $subscription_data['billing_frequency'] = $product->billing_frequency;
                    }
                    if (!isset($subscription_data['billing_cycle']) || !$subscription_data['billing_cycle']) {
                        $subscription_data['billing_cycle'] = $product->billing_cycle ?: 3;
                    }
                    if (!isset($subscription_data['billing_type']) || !$subscription_data['billing_type']) {
                        $subscription_data['billing_type'] = $product->billing_type ?: 1;
                    }
                    if (!isset($subscription_data['pricing_type']) || !$subscription_data['pricing_type']) {
                        $subscription_data['pricing_type'] = $product->pricing_type ?: 1;
                    }
                    if (!isset($subscription_data['price_type']) || !$subscription_data['price_type']) {
                        $subscription_data['price_type'] = $product->currency_code ?: "USD";
                    }
                    if (!isset($subscription_data['recurring'])) {
                        if (!empty($subscription_data['billing_frequency']) && !empty($subscription_data['billing_cycle']) && $subscription_data['type'] != 3) {
                            $subscription_data['recurring'] = 1;
                        } else {
                            $subscription_data['recurring'] = 0;
                        }
                    }
                }
                $subscription_data['url'] = $product->url;
                $subscription_data['description'] = $product->description;
                $subscription_data['category_id'] = $product->category_id;
                $subscription_data['ltdval_price'] = $product->ltdval_price;
                $subscription_data['ltdval_frequency'] = $product->ltdval_frequency;
                $subscription_data['ltdval_cycle'] = $product->ltdval_cycle;
                $subscription_data['image'] = $product->image;
                $subscription_data['favicon'] = $product->favicon;
                DB::table('subscriptions')->insert($subscription_data);


                // Restore attachments
                if (!empty($_SESSION['account_recovery']['restore'][$this->token]['subscription_attachment'][$subscription_data['id']])) {
                    $attachments = $_SESSION['account_recovery']['restore'][$this->token]['subscription_attachment'][$subscription_data['id']];
                    if (!empty($attachments) && is_array($attachments)) {

                        // Disabled for copying the entire folder
                        $attachment_destination_path = 'storage/app/client/1/subscription/' . $subscription_data['id'] . '/attachments';
                        $attachment_backup_path = 'storage/app/' . $this->old_backup_dir_name . '/media/subscription/' . $subscription_data['id'] . '/attachments';

                        if (File::exists($attachment_backup_path)) {
                            File::copyDirectory($attachment_backup_path, $attachment_destination_path);
                        }

                        DB::table('subscriptions_attachments')->insert($attachments);
                        unset($_SESSION['account_recovery']['restore'][$this->token]['subscription_attachment'][$subscription_data['id']]);
                    }
                }

                // Restore marketplace
                if (!empty($_SESSION['account_recovery']['restore'][$this->token]['subscription_marketplace'][$subscription_data['id']])) {
                    $attachments = $_SESSION['account_recovery']['restore'][$this->token]['subscription_marketplace'][$subscription_data['id']];
                    if (!empty($attachments) && is_array($attachments)) {
                        DB::table('subscription_cart')->insert($attachments);
                        unset($_SESSION['account_recovery']['restore'][$this->token]['subscription_marketplace'][$subscription_data['id']]);
                    }
                }
            }

            if ($subscription_key !== null) {
                unset($_SESSION['account_recovery']['restore'][$this->token]['subscription'][$subscription_key]);
            }

            $output['status'] = true;
            $output['message'] = $this->restore_commands[$command]['message']['success'] . $subscription_data['product_name'];
            $output['next_command'] = $command;
        }

        return $this->restore_response($output);
    }

    private function cmd_restore_folder(Request $request, $command)
    {
        $output = [
            'command' => $command,
        ];

        if (empty($_SESSION['account_recovery']['restore'][$this->token]['folder'])) {

            $output['status'] = true;
            $output['message'] = $this->restore_commands[$command]['message']['complete'];
            $output['next_command'] = $this->restore_commands[$command]['next_command'];
        } else {

            $folder_data = [];
            $folder_key = null;
            foreach ($_SESSION['account_recovery']['restore'][$this->token]['folder'] as $folder_key => $folder_data) {
                break;
            }

            if (!empty($folder_data) && is_array($folder_data)) {
                DB::table('folder')->insert($folder_data);
            }

            if ($folder_key !== null) {
                unset($_SESSION['account_recovery']['restore'][$this->token]['folder'][$folder_key]);
            }

            $output['status'] = true;
            $output['message'] = $this->restore_commands[$command]['message']['success'] . $folder_data['name'];
            $output['next_command'] = $command;
        }

        return $this->restore_response($output);
    }

    private function cmd_restore_tag(Request $request, $command)
    {
        $output = [
            'command' => $command,
        ];

        if (empty($_SESSION['account_recovery']['restore'][$this->token]['tag'])) {

            $output['status'] = true;
            $output['message'] = $this->restore_commands[$command]['message']['complete'];
            $output['next_command'] = $this->restore_commands[$command]['next_command'];
        } else {

            $tag_data = [];
            $tag_key = null;
            foreach ($_SESSION['account_recovery']['restore'][$this->token]['tag'] as $tag_key => $tag_data) {
                break;
            }

            if (!empty($tag_data) && is_array($tag_data)) {
                DB::table('tags')->insert($tag_data);
            }

            if ($tag_key !== null) {
                unset($_SESSION['account_recovery']['restore'][$this->token]['tag'][$tag_key]);
            }

            $output['status'] = true;
            $output['message'] = $this->restore_commands[$command]['message']['success'] . $tag_data['name'];
            $output['next_command'] = $command;
        }

        return $this->restore_response($output);
    }

    private function cmd_restore_payment_method(Request $request, $command)
    {
        $output = [
            'command' => $command,
        ];

        if (empty($_SESSION['account_recovery']['restore'][$this->token]['payment_method'])) {

            $output['status'] = true;
            $output['message'] = $this->restore_commands[$command]['message']['complete'];
            $output['next_command'] = $this->restore_commands[$command]['next_command'];
        } else {

            $payment_method_data = [];
            $payment_method_key = null;
            foreach ($_SESSION['account_recovery']['restore'][$this->token]['payment_method'] as $payment_method_key => $payment_method_data) {
                break;
            }

            if (!empty($payment_method_data) && is_array($payment_method_data)) {
                DB::table('users_payment_methods')->insert($payment_method_data);
            }

            if ($payment_method_key !== null) {
                unset($_SESSION['account_recovery']['restore'][$this->token]['payment_method'][$payment_method_key]);
            }

            $output['status'] = true;
            $output['message'] = $this->restore_commands[$command]['message']['success'] . $payment_method_data['name'];
            $output['next_command'] = $command;
        }

        return $this->restore_response($output);
    }

    private function cmd_restore_contact(Request $request, $command)
    {
        $output = [
            'command' => $command,
        ];

        if (empty($_SESSION['account_recovery']['restore'][$this->token]['contact'])) {

            $output['status'] = true;
            $output['message'] = $this->restore_commands[$command]['message']['complete'];
            $output['next_command'] = $this->restore_commands[$command]['next_command'];
        } else {

            $contact_data = [];
            $contact_key = null;
            foreach ($_SESSION['account_recovery']['restore'][$this->token]['contact'] as $contact_key => $contact_data) {
                break;
            }

            if (!empty($contact_data) && is_array($contact_data)) {
                DB::table('users_contacts')->insert($contact_data);
            }

            if ($contact_key !== null) {
                unset($_SESSION['account_recovery']['restore'][$this->token]['contact'][$contact_key]);
            }

            $output['status'] = true;
            $output['message'] = $this->restore_commands[$command]['message']['success'] . $contact_data['name'];
            $output['next_command'] = $command;
        }

        return $this->restore_response($output);
    }

    private function cmd_restore_alert_profile(Request $request, $command)
    {
        $output = [
            'command' => $command,
        ];

        if (empty($_SESSION['account_recovery']['restore'][$this->token]['alert_profile'])) {

            $output['status'] = true;
            $output['message'] = $this->restore_commands[$command]['message']['complete'];
            $output['next_command'] = $this->restore_commands[$command]['next_command'];
        } else {

            $alert_profile_data = [];
            $alert_profile_key = null;
            foreach ($_SESSION['account_recovery']['restore'][$this->token]['alert_profile'] as $alert_profile_key => $alert_profile_data) {
                break;
            }

            if (!empty($alert_profile_data) && is_array($alert_profile_data)) {
                DB::table('users_alert')->insert($alert_profile_data);
            }

            if ($alert_profile_key !== null) {
                unset($_SESSION['account_recovery']['restore'][$this->token]['alert_profile'][$alert_profile_key]);
            }

            $output['status'] = true;
            $output['message'] = $this->restore_commands[$command]['message']['success'] . $alert_profile_data['alert_name'];
            $output['next_command'] = $command;
        }

        return $this->restore_response($output);
    }

    private function cmd_restore_api_token(Request $request, $command)
    {
        $output = [
            'command' => $command,
        ];

        if (empty($_SESSION['account_recovery']['restore'][$this->token]['api_token'])) {

            $output['status'] = true;
            $output['message'] = $this->restore_commands[$command]['message']['complete'];
            $output['next_command'] = $this->restore_commands[$command]['next_command'];
        } else {

            $api_token_data = [];
            $api_token_key = null;
            foreach ($_SESSION['account_recovery']['restore'][$this->token]['api_token'] as $api_token_key => $api_token_data) {
                break;
            }

            if (!empty($api_token_data) && is_array($api_token_data)) {
                DB::table('personal_access_tokens')->insert($api_token_data);
            }

            if ($api_token_key !== null) {
                unset($_SESSION['account_recovery']['restore'][$this->token]['api_token'][$api_token_key]);
            }

            $output['status'] = true;
            $output['message'] = $this->restore_commands[$command]['message']['success'] . $api_token_data['name'];
            $output['next_command'] = $command;
        }

        return $this->restore_response($output);
    }

    private function cmd_restore_webhook(Request $request, $command)
    {
        $output = [
            'command' => $command,
        ];

        if (empty($_SESSION['account_recovery']['restore'][$this->token]['webhook'])) {

            $output['status'] = true;
            $output['message'] = $this->restore_commands[$command]['message']['complete'];
            $output['next_command'] = $this->restore_commands[$command]['next_command'];
        } else {

            $webhook_data = [];
            $webhook_key = null;
            foreach ($_SESSION['account_recovery']['restore'][$this->token]['webhook'] as $webhook_key => $webhook_data) {
                break;
            }

            if (!empty($webhook_data) && is_array($webhook_data)) {
                DB::table('webhooks')->insert($webhook_data);
            }

            if ($webhook_key !== null) {
                unset($_SESSION['account_recovery']['restore'][$this->token]['webhook'][$webhook_key]);
            }

            $output['status'] = true;
            $output['message'] = $this->restore_commands[$command]['message']['success'] . $webhook_data['name'];
            $output['next_command'] = $command;
        }

        return $this->restore_response($output);
    }

    private function cmd_restore_settings(Request $request, $command)
    {
        $output = [
            'command' => $command,
        ];

        $settings_data = [];
        if (isset($_SESSION['account_recovery']['restore'][$this->token]['settings'])) {
            $settings_data = $_SESSION['account_recovery']['restore'][$this->token]['settings'];
        }

        if (isset($settings_data['users_profile'])) {
            $users_profile = $settings_data['users_profile'];

            if (!empty($users_profile) && is_array($users_profile)) {
                DB::table('users_profile')->updateOrInsert(['user_id' => Auth::id()], $users_profile);
            }
        }

        // Search and replace the image
        if (!empty($settings_data['users']['image']) && !empty($settings_data['users']['_avatar_filename'])) {
            $image_source_path = $this->old_backup_dir_name . '/media/' . $settings_data['users']['_avatar_filename'];
            $image_destination_path = Auth::user()->image;

            if (Storage::exists($image_source_path)) {
                Storage::copy($image_source_path, $image_destination_path);
            }
        }

        // Return status
        $output['status'] = true;
        $output['message'] = $this->restore_commands[$command]['message']['complete'];
        $output['next_command'] = $this->restore_commands[$command]['next_command'];

        return $this->restore_response($output);
    }

    private function cmd_restore_success(Request $request, $command)
    {
        $output = [
            'command' => $command,
        ];

        $duration = 0;
        if (isset($_SESSION['account_recovery']['restore'][$this->token]['_metadata']['timestamp'])) {
            $duration = time() - $_SESSION['account_recovery']['restore'][$this->token]['_metadata']['timestamp'];
        }

        $this->add_event([
            'event_type' => 'account_restore',
            'event_status' => 1,
        ]);

        // Reset data
        unset($_SESSION['account_recovery']['restore'][$this->token]);

        // Logout user and redirect to login page
        // Auth::logout();
        // $request->session()->invalidate();
        // $request->session()->regenerateToken();

        $output['status'] = true;
        $output['message'] = $this->restore_commands[$command]['message']['complete'] . $duration . ' seconds';
        $output['next_command'] = $this->restore_commands[$command]['next_command'];

        return $this->restore_response($output);
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
        $output['event_id'] = $this->add_event([
            'event_type' => 'account_reset',
            'event_status' => 0,
        ]);

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
        $alert = DB::table('users_alert')
            ->where('user_id', $this->user_id)
            ->get()
            ->first();

        if (empty($alert->id)) {

            $output['status'] = true;
            $output['message'] = $this->reset_commands[$command]['message']['complete'];
            $output['next_command'] = $this->reset_commands[$command]['next_command'];
        } else {

            // Delete alert profile
            UserAlert::find($alert->id)->delete();

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


        // Create event logs for reset
        $this->add_event([
            'event_type' => 'account_reset',
            'event_status' => 1,
        ]);

        // Logout user and redirect to login page
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $output['status'] = true;
        $output['message'] = $this->reset_commands[$command]['message']['complete'];
        $output['next_command'] = $this->reset_commands[$command]['next_command'];

        return response()->json($output);
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
            $this->event_id = NotificationEngine::staticModel('event')::create([
                'user_id' => Auth::id(),
                'event_type' => $data['event_type'],
                'event_type_status' => 'create',
                'event_status' => $data['event_status'],
                'table_name' => 'users',
                'table_row_id' => Auth::id(),
                'event_type_function' => __CLASS__ . '::' . __FUNCTION__ . '()',
                'event_cron' => 0,
                'event_type_schedule' => 0,
            ])->id;
        } else {
            // Update event logs
            NotificationEngine::staticModel('event')::do_update($this->event_id, [
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
        $event = NotificationEngine::staticModel('event')::where('user_id', Auth::user()->id)
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
        if (empty($this->current_event)) return $msg;
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
            NotificationEngine::staticModel('event')::whereUserId(Auth::user()->id)->delete();
        } else {
            NotificationEngine::staticModel('event')::where([
                ['user_id', Auth::user()->id],
                ['id', '!=', $this->event_id],
            ])->delete();
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
