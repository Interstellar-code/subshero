<?php

namespace App\Http\Controllers\Client\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Application as lib;
use Illuminate\Support\Carbon;
use App\Models\FolderModel;
use App\Models\PlanModel;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\File;
use App\Models\UserModel;
use Illuminate\Support\Facades\Hash;
use App\Models\SettingsModel;
use Illuminate\Support\Facades\Mail;
use App\Mail\Test;
use App\Models\ApiTokenModel;
use App\Models\EmailModel;
use App\Models\EmailTemplate;
use App\Library\NotificationEngine;
use App\Models\PaymentMethodModel;
use App\Models\SubscriptionModel;
use App\Models\TagModel;
use App\Models\TeamModel;
use App\Models\UserAlert;
use App\Models\Webhook;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use ZanySoft\Zip\Zip;
use ZanySoft\Zip\ZipFacade;
use PhpMyAdmin\SqlParser\Parser;
use Illuminate\Support\Facades\Artisan;

class AccountController extends Controller
{
    private $user_id;
    private $all_command;

    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');


        // Command definition
        $this->all_command = lib()->settings->account->all_command;
    }


    public function reset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'command' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
        }

        $this->user_id = Auth::id();
        $command = $request->input('command');

        switch ($command) {
            case 'get_account_info':
                return $this->cmd_get_account_info($request, $command);
                break;

            case 'delete_subscription':
                return $this->cmd_delete_subscription($request, $command);
                break;

            case 'delete_folder':
                return $this->cmd_delete_folder($request, $command);
                break;

            case 'delete_tag':
                return $this->cmd_delete_tag($request, $command);
                break;

            case 'delete_payment_method':
                return $this->cmd_delete_payment_method($request, $command);
                break;

            case 'delete_contact':
                return $this->cmd_delete_contact($request, $command);
                break;

            case 'delete_alert_profile':
                return $this->cmd_delete_alert_profile($request, $command);
                break;

            case 'delete_api_token':
                return $this->cmd_delete_api_token($request, $command);
                break;

            case 'delete_webhook':
                return $this->cmd_delete_webhook($request, $command);
                break;

            case 'delete_team_account':
                return $this->cmd_delete_team_account($request, $command);
                break;

            case 'delete_file_all':
                return $this->cmd_delete_file_all($request, $command);
                break;

            case 'reset_success':
                return $this->cmd_reset_success($request, $command);
                break;

            default:
                return response()->json([
                    'status' => false,
                    'message' => 'Command not found',
                ]);
        }
    }

    private function cmd_get_account_info(Request $request, $command)
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

        $output['data']['team_account_count'] = DB::table('users_teams')
            ->where('team_user_id', $this->user_id)
            ->get()
            ->count();


        if (empty($output['data'])) {
            $output['message'] = $this->all_command[$command]['message']['failure'];
            $output['status'] = false;
        } else {

            $output['status'] = true;
            $output['message'] = $this->all_command[$command]['message']['success'];
            $output['next_command'] = $this->all_command[$command]['next_command'];

            // $request->session()->put('new_version_number', $list_array[0]['version_number']);
        }

        return response()->json($output);
    }

    private function cmd_delete_subscription(Request $request, $command)
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
            $output['message'] = $this->all_command[$command]['message']['complete'];
            $output['next_command'] = $this->all_command[$command]['next_command'];
        } else {

            // Add event logs
            $this->add_event([
                'table_row_id' => $subscription->id,
                'event_type_status' => 'delete',
                'event_product_id' => $subscription->brand_id,
                'event_type_schedule' => $subscription->recurring,
            ]);

            // Update event logs
            NotificationEngine::set_del_status([
                'event_types' => ['email', 'browser'],
                'subscription_id' => $subscription->id,
            ]);

            // Delete subscription
            SubscriptionModel::del($subscription->id);

            if (!empty($subscription->image)) {
                $file_path = 'client/1/subscription/' . $subscription->id . '/' . basename($subscription->image);

                if (Storage::disk('local')->exists($file_path)) {
                    Storage::disk('local')->delete($file_path);
                }
            }

            $output['status'] = true;
            $output['message'] = $this->all_command[$command]['message']['success'] . $subscription->product_name;
            $output['next_command'] = $command;
        }

        return response()->json($output);
    }

    private function cmd_delete_folder(Request $request, $command)
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
            $output['message'] = $this->all_command[$command]['message']['complete'];
            $output['next_command'] = $this->all_command[$command]['next_command'];
        } else {

            // Delete folder
            FolderModel::del($folder->id);

            $output['status'] = true;
            $output['message'] = $this->all_command[$command]['message']['success'] . $folder->name;
            $output['next_command'] = $command;
        }

        return response()->json($output);
    }

    private function cmd_delete_tag(Request $request, $command)
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
            $output['message'] = $this->all_command[$command]['message']['complete'];
            $output['next_command'] = $this->all_command[$command]['next_command'];
        } else {

            // Delete tag
            TagModel::del($tag->id);

            $output['status'] = true;
            $output['message'] = $this->all_command[$command]['message']['success'] . $tag->name;
            $output['next_command'] = $command;
        }

        return response()->json($output);
    }

    private function cmd_delete_payment_method(Request $request, $command)
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
            $output['message'] = $this->all_command[$command]['message']['complete'];
            $output['next_command'] = $this->all_command[$command]['next_command'];
        } else {

            // Delete payment method
            PaymentMethodModel::del($payment_method->id);

            $output['status'] = true;
            $output['message'] = $this->all_command[$command]['message']['success'] . $payment_method->name;
            $output['next_command'] = $command;
        }

        return response()->json($output);
    }

    private function cmd_delete_contact(Request $request, $command)
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
            $output['message'] = $this->all_command[$command]['message']['complete'];
            $output['next_command'] = $this->all_command[$command]['next_command'];
        } else {

            // Delete contact
            UserModel::contact_delete($contact->id);

            $output['status'] = true;
            $output['message'] = $this->all_command[$command]['message']['success'] . $contact->name;
            $output['next_command'] = $command;
        }

        return response()->json($output);
    }

    private function cmd_delete_alert_profile(Request $request, $command)
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
            $output['message'] = $this->all_command[$command]['message']['complete'];
            $output['next_command'] = $this->all_command[$command]['next_command'];
        } else {

            // Delete alert profile
            UserAlert::find($alert->id)->delete();

            $output['status'] = true;
            $output['message'] = $this->all_command[$command]['message']['success'] . $alert->alert_name;
            $output['next_command'] = $command;
        }

        return response()->json($output);
    }

    private function cmd_delete_api_token(Request $request, $command)
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
            $output['message'] = $this->all_command[$command]['message']['complete'];
            $output['next_command'] = $this->all_command[$command]['next_command'];
        } else {

            // Delete API token
            ApiTokenModel::del($api_token->id);

            $output['status'] = true;
            $output['message'] = $this->all_command[$command]['message']['success'] . $api_token->name;
            $output['next_command'] = $command;
        }

        return response()->json($output);
    }

    private function cmd_delete_webhook(Request $request, $command)
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
            $output['message'] = $this->all_command[$command]['message']['complete'];
            $output['next_command'] = $this->all_command[$command]['next_command'];
        } else {

            // Delete webhook
            Webhook::where('id', $webhook->id)->delete();

            $output['status'] = true;
            $output['message'] = $this->all_command[$command]['message']['success'] . $webhook->name;
            $output['next_command'] = $command;
        }

        return response()->json($output);
    }

    private function cmd_delete_team_account(Request $request, $command)
    {
        $output = [
            'command' => $command,
        ];

        // Get team_account
        $team_account = DB::table('users_teams')
            ->leftJoin('users', 'users.id', '=', 'users_teams.team_user_id')
            ->where('users_teams.team_user_id', $this->user_id)
            ->select('users_teams.*', 'users.name as user_name')
            ->get()
            ->first();


        if (empty($team_account->id)) {

            $output['status'] = true;
            $output['message'] = $this->all_command[$command]['message']['complete'];
            $output['next_command'] = $this->all_command[$command]['next_command'];
        } else {

            // Delete team account
            TeamModel::del($team_account->id);

            $output['status'] = true;
            $output['message'] = $this->all_command[$command]['message']['success'] . $team_account->user_name;
            $output['next_command'] = $command;
        }

        return response()->json($output);
    }

    private function cmd_delete_file_all(Request $request, $command)
    {
        $output = [
            'command' => $command,
        ];

        $output['status'] = true;
        $output['message'] = $this->all_command[$command]['message']['complete'];
        $output['next_command'] = $this->all_command[$command]['next_command'];

        return response()->json($output);
    }

    private function cmd_reset_success(Request $request, $command)
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

        // Delete data from events table where event_type in 'subscription', 'email'
        NotificationEngine::staticModel('event')::where([
            ['user_id', $this->user_id],
            ['event_type', 'subscription'],
            ['event_type_status', 'delete'],
        ])->delete();
        NotificationEngine::staticModel('email')::where([
            ['user_id', $this->user_id],
            ['event_type', 'email'],
            ['event_type_status', 'delete'],
        ])->delete();

        // Delete subscriptions data
        DB::table('subscriptions_history')
            ->where('user_id', $this->user_id)
            ->delete();

        // Set reset time
        DB::table('users')
            ->where('id', $this->user_id)
            ->update([
                'reset_at' => date('Y-m-d H:i:s'),
            ]);

        // Create event logs for reset
        UserModel::reset($this->user_id);

        // Logout user and redirect to login page
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $output['status'] = true;
        $output['message'] = $this->all_command[$command]['message']['complete'];
        $output['next_command'] = $this->all_command[$command]['next_command'];

        return response()->json($output);
    }

    private function add_event($data)
    {
        if (in_array($data['event_type_status'], ['create', 'create_quick', 'update', 'delete'])) {
            $old_event_id = NotificationEngine::staticModel('event')::get_event_id_by_table_row_id($data['table_row_id']);

            if (!$old_event_id) {

                // Create event logs
                NotificationEngine::staticModel('event')::create([
                    'user_id' => Auth::id(),
                    'event_type' => 'subscription',
                    'event_type_status' => $data['event_type_status'],
                    'event_status' => 1,
                    'table_name' => 'subscriptions',
                    'table_row_id' => $data['table_row_id'],
                    'event_type_function' => __CLASS__ . '::' . __FUNCTION__ . '()',
                    'event_cron' => 0,
                    'event_product_id' => $data['event_product_id'],
                    'event_type_schedule' => $data['event_type_schedule'],
                ]);
            } else {

                // Update event logs
                NotificationEngine::staticModel('event')::do_update($old_event_id, [
                    'user_id' => Auth::id(),
                    'event_type' => 'subscription',
                    'event_type_status' => $data['event_type_status'],
                    'event_status' => 1,
                    'table_name' => 'subscriptions',
                    'table_row_id' => $data['table_row_id'],
                    'event_type_function' => __CLASS__ . '::' . __FUNCTION__ . '()',
                    'event_cron' => 0,
                    'event_product_id' => $data['event_product_id'],
                    'event_type_schedule' => $data['event_type_schedule'],

                    'event_url' => url()->current(),
                    'event_timezone' => APP_TIMEZONE,
                    'event_datetime' => lib()->do->timezone_convert([
                        'to_timezone' => APP_TIMEZONE,
                    ]),
                ]);
            }
        }
    }
}
