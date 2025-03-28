<?php

namespace App\Http\Controllers;

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
use App\Library\Email;
use App\Models\SettingsModel;
use Illuminate\Support\Facades\Mail;
use App\Mail\Test;
use App\Library\NotificationEngine;
use App\Models\TemplateModel;
use App\Models\TokenModel;
use App\Models\WebhookModel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class WebhookController extends Controller
{
    private $user = null;
    private $raw_json_data = null;

    public function __construct()
    {
        parent::__construct();
    }

    public function admin_v1(Request $request)
    {
        // // Log::debug('Start');

        $apache_headers = apache_request_headers();
        $request_data = [
            'request' => $_REQUEST,
            'server' => $_SERVER,
            'apache' => $apache_headers,
            'raw' => file_get_contents('php://input'),
            // 'php_event' => json_encode(json_decode(file_get_contents('php://input'), true)),
        ];
        $log_id = WebhookModel::create([
            'request' => json_encode($request_data),
            'created_at' => date(APP_TIMESTAMP_FORMAT),
        ]);

        if (!$this->authenticate_data($request, $request_data)) {
            // Log::debug('Authentication failed');
            // Unauthorized
            // abort(200);
            abort(401);
        }

        // Log::debug('Authorized');


        $raw_str = $request_data['raw'];
        // $raw_str = str_replace('\\', '', $raw_str);

        if (empty($raw_str)) {
            // Bad request
            // abort(401);
            abort(200);
        }

        $raw_json = json_decode($raw_str, true);

        if (empty($raw_json)) {
            // Bad request
            abort(200);
            // abort(401);
        }

        // Log::debug('Request checked');

        $this->raw_json_data = $raw_json;
        $data = $raw_json;
        $type = $this->get_type($request, $data);

        // Log::debug('Type: ' . $type);


        switch ($type) {
            case 'customer.created':
                // Log::debug('customer.created');

                $user_data = $this->user_search($data);
                if (!$user_data['status']) {
                    $user_data = $this->user_create($data);
                }

                if ($user_data['status']) {
                    return Response::json([
                        'status' => true,
                        'message' => null,
                    ]);
                } else {
                    return Response::json($user_data);
                }

                break;

            case 'order.created':
            case 'order.updated':
            case 'subscription.created':
            case 'subscription.updated':
                // Log::debug('subscription.created');

                $user_data = $this->billing_user_search($data);
                if (!$user_data['status']) {
                    $user_data = $this->billing_user_create($data);
                }
                if ($user_data['status']) {
                    // Log::debug('set_plan');
                    $this->set_wp_user_id($user_data['data']);
                    return $this->set_plan($user_data['data']->id, $data);
                } else {
                    return Response::json($user_data);
                }

                break;

            default:
        }


        // Log::debug('End');


        return Response::json([
            'status' => true,
            'message' => 'Success',
        ], 200);
    }

    public function handle(Request $request)
    {
    }

    private function set_wp_user_id($user_data)
    {
        // Set wp_user_id from wordpress customer id
        if (isset($this->raw_json_data['customer_id'])) {
            DB::table('users')
                ->where('id', $user_data->id)
                ->update(['wp_user_id' => $this->raw_json_data['customer_id']]);
            return true;
        }
        return false;
    }

    private function set_plan($user_id, $data)
    {
        $errors = [];
        $status = false;

        if (empty($data['line_items'][0]) || empty($data['status'])) {
            return [
                'status' => $status,
                'message' => null,
            ];
        }

        if (!in_array($data['status'], ['active', 'completed', 'processing', 'cancelled', 'refunded'])) {
            return [
                'status' => $status,
                'message' => null,
            ];
        }

        // cancelled
        if ($data['status'] == 'cancelled' || $data['status'] == 'refunded') {

            UserModel::set_plan($user_id, 1);
            $status = true;

            return [
                'status' => $status,
                'message' => null,
            ];
        }

        // refund check
        if (empty($data['refunds'])) {

            $validator = Validator::make($data['line_items'][0], [
                'product_id' => 'required|integer',
                'variation_id' => 'nullable|integer',
            ]);

            if ($validator->fails()) {
                $errors = $validator->messages();
                return [
                    'status' => $status,
                    'message' => $errors,
                ];
            } else {
                $product_id = $data['line_items'][0]['product_id'];
                $variation_id = $data['line_items'][0]['variation_id'] ?? null;
                $plan = PlanModel::get_by_product_id($product_id, $variation_id);

                // Check if not a Team plan user
                if (!empty($plan) && empty($this->user->team_user_id)) {
                    UserModel::set_plan($user_id, $plan->id);
                    $status = true;
                }

                return [
                    'status' => $status,
                    'message' => null,
                ];
            }
        }

        // Refund order
        else {
            UserModel::set_plan($user_id, 1);
            $status = true;

            return [
                'status' => $status,
                'message' => null,
            ];
        }
    }

    private function authenticate_header($headers)
    {
        if (empty($headers['Authorization'])) {
            return false;
        } else {

            // Webhook authentication with Bearer token
            $authorization = $headers['Authorization'];
            $authorization_arr = explode(' ', $authorization);

            if (!count($authorization_arr) == 2) {
                return false;
            }

            $config = SettingsModel::get();

            if (empty($config->webhook_key)) {
                return false;
            }

            if ($authorization_arr[1] != $config->webhook_key) {
                return false;
            }
        }

        return true;
    }

    private function authenticate_data(Request $request, $request_data)
    {
        if ($request->header('CONTENT_TYPE') != 'application/json') {
            abort(200);
        }


        if ($request->hasHeader('HTTP_X_WC_WEBHOOK_SIGNATURE')) {
            $request_signature = $request->header('HTTP_X_WC_WEBHOOK_SIGNATURE');
        } else if ($request->hasHeader('X_WC_WEBHOOK_SIGNATURE')) {
            $request_signature = $request->header('X_WC_WEBHOOK_SIGNATURE');
        } else if ($request->hasHeader('X-WC-Webhook-Signature')) {
            $request_signature = $request->header('X-WC-Webhook-Signature');
        }

        // Log::debug('$request_signature: ' . $request_signature);


        if (empty($request_signature)) {
            return false;
        } else {


            $config = SettingsModel::get();

            if (empty($config->webhook_key)) {
                return false;
            }

            $calculate_signature = base64_encode(hash_hmac('sha256', $request_data['raw'], $config->webhook_key, true));

            // Log::debug('$calculate_signature: ' . $calculate_signature);
            // Log::debug('$request_data[raw]: ' . $request_data['raw']);

            if ($request_signature == $calculate_signature) {
                return true;
            }
        }

        return false;
    }

    private function get_type(Request $request, $data = null)
    {
        $type = null;
        $event = null;

        // Fetch topic
        if ($request->hasHeader('HTTP_X_WC_WEBHOOK_TOPIC')) {
            $type = $request->header('HTTP_X_WC_WEBHOOK_TOPIC');
        } else if ($request->hasHeader('X_WC_WEBHOOK_TOPIC')) {
            $type = $request->header('X_WC_WEBHOOK_TOPIC');
        } else if ($request->hasHeader('X-WC-Webhook-Topic')) {
            $type = $request->header('X-WC-Webhook-Topic');
        }


        // Check for subscription and order type
        if ($type == 'order.created') {

            if ($request->hasHeader('X_WC_WEBHOOK_EVENT')) {
                $event = $request->header('X_WC_WEBHOOK_EVENT');
            }

            // if (isset($data['line_items'][0]['product_id'])) {
            //     $product_id = $data['line_items'][0]['product_id'];

            //     // Order
            //     if (in_array($product_id, $this->order_product_id_all)) {
            //         if (empty($event)) {
            //             $type = 'order';
            //         } else {
            //             $type = 'order.' . $event;
            //         }
            //     }

            //     // Subscription
            //     else if (in_array($product_id, $this->subscription_product_id_all)) {
            //         if (empty($event)) {
            //             $type = 'subscription';
            //         } else {
            //             $type = 'subscription.' . $event;
            //         }
            //     }
            // }
        }

        return $type;
    }

    private function billing_user_create($data)
    {
        if (empty($data['billing'])) {
            return [
                'status' => false,
                'message' => null,
            ];
        }

        // Set wp_user_id from wordpress customer id
        if (isset($this->raw_json_data['customer_id'])) {
            $data['billing']['id'] = $this->raw_json_data['customer_id'];
        }

        return self::user_create($data['billing']);
    }

    private function billing_user_search($data)
    {
        if (empty($data['billing'])) {
            return [
                'status' => false,
                'message' => null,
            ];
        }

        $validator = Validator::make($data['billing'], [
            'email' => 'required|string|max:' . len()->users->email,
        ]);

        if ($validator->fails()) {
            $errors = $validator->messages();
            return [
                'status' => false,
                'message' => $errors,
            ];
        } else {

            // Create an account
            $user = UserModel::get_by_email($data['billing']['email']);
            $this->user = $user;

            return [
                'status' => !empty($user),
                'message' => null,
                'data' => $user,
            ];
        }
    }

    private function user_create($data)
    {
        $errors = [];
        $status = false;

        $validator = Validator::make($data, [
            'first_name' => 'required|string|max:' . len()->users->first_name,
            'last_name' => 'required|string|max:' . len()->users->last_name,
            'email' => 'required|string|max:' . len()->users->email,
        ]);

        if ($validator->fails()) {
            $errors = $validator->messages();
            return [
                'status' => $status,
                'message' => $errors,
            ];
        } else {

            // Check for wordpress customer id
            if (!isset($data['id'])) {
                $data['id'] = null;
            }

            // Create an account
            $user_id = UserModel::create([
                'wp_user_id' => $data['id'],
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'name' => $data['first_name'] . ' ' . $data['last_name'],
                'email' => $data['email'],
                'password' => '',
                'status' => 0,
            ]);

            $user = UserModel::get($user_id);

            if (!empty($user)) {
                $this->user = $user;

                $status = true;
                UserModel::create_profile_default($user->id, 'user_webhook');

                $token = Str::lower(Str::random(64));
                TokenModel::create([
                    'user_id' => $user->id,
                    'table_name' => 'users',
                    'table_row_id' => $user->id,
                    'type' => 'webhook_user_confirm',
                    'email' => $user->email,
                    'token' => Hash::make($token),
                    'expire_at' => Carbon::now()->addDays(1)->format(APP_TIMESTAMP_FORMAT),
                ]);

                // Generate mail
                $template = NotificationEngine::staticModel('email')::prepare_message_template([
                    '{user_first_name}' => [
                        'user' => $user,
                    ],
                    '{new_password_url}' => [
                        'token' => $token,
                        'user' => $user,
                    ],
                    'type' => 'webhook_user_create',
                ]);

                // Send email and log this
                NotificationEngine::staticModel('email')::send_message([
                    'user' => $user,
                    'template' => $template,
                ]);

                // Create event logs
                NotificationEngine::staticModel('email')::do_create([
                    'user_id' => $user->id,
                    'event_type' => 'email',
                    'event_type_status' => 'create',
                    'event_status' => 1,
                    'table_name' => 'users',
                    'table_row_id' => $user->id,
                    'event_type_function' => __CLASS__ . '::' . __FUNCTION__ . '()',
                ]);

                // Create event logs
                NotificationEngine::staticModel('webhook')::create([
                    'user_id' => $user->id,
                    'event_type' => 'webhook',
                    'event_type_status' => 'create',
                    'event_status' => 1,
                    'table_name' => 'users',
                    'table_row_id' => $user->id,
                    'event_type_function' => __CLASS__ . '::' . __FUNCTION__ . '()',
                ]);
            }

            return [
                'status' => $status,
                'message' => null,
                'data' => $user,
            ];
        }
    }

    private function user_search($data)
    {
        $validator = Validator::make($data, [
            'email' => 'required|string|max:' . len()->users->email,
        ]);

        if ($validator->fails()) {
            $errors = $validator->messages();
            return [
                'status' => false,
                'message' => $errors,
            ];
        } else {

            // Create an account
            $user = UserModel::get_by_email($data['email']);
            $this->user = $user;

            return [
                'status' => !empty($user),
                'message' => null,
                'data' => $user,
            ];
        }
    }
}
