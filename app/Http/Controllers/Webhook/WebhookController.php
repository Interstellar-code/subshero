<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Webhook\Controller;
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
use App\Models\TemplateModel;
use App\Models\TokenModel;
use App\Models\WebhookLog;
use App\Models\WebhookModel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Http\Controllers\Webhook\SubscriptionController;
use App\Models\Webhook;

class WebhookController extends Controller
{
    private $user = null;
    private $webhook = null;
    private $event = null;

    /*
        200 = OK
        400 = Bad request
        401 = Unauthorized
        403 = Forbidden
        406 = Not Acceptable
        422 = Unprocessable Entity but request was well-formed
    */

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(Request $request, $token)
    {
        // // Log::debug('Start');
        $status_code = 200;

        $apache_headers = apache_request_headers();
        $request_data = [
            'token' => $token,
            'request' => $_REQUEST,
            'server' => $_SERVER,
            'apache' => $apache_headers,
            'raw' => file_get_contents('php://input'),
            // 'php_event' => json_encode(json_decode(file_get_contents('php://input'), true)),
        ];


        // Create log
        $webhook_log = new WebhookLog();
        // $webhook_log->webhook_id = $webhook->id;
        // $webhook_log->user_id = $user_id;
        $webhook_log->type = 1;
        // $webhook_log->event = $event;
        $webhook_log->request = json_encode($request_data);
        $webhook_log->created_at = date(APP_TIMESTAMP_FORMAT);
        $webhook_log->save();

        // die;

        $status_code = $this->authenticate_data($request, $request_data);
        // Log::debug('Authentication failed');

        // Check HTTP status code from authenticate function
        if ($status_code == 200) {
            Auth::loginUsingId($this->webhook->user_id);
        }

        // Unauthorized
        else {
            return response(null, $status_code);
        }

        // dd($request_data);

        // Log::debug('Authorized');

        $webhook_log->webhook_id = $this->webhook->id;
        $webhook_log->user_id = $this->webhook->user_id;
        // $webhook_log->event = $this->webhook->event;
        $webhook_log->save();


        $raw_str = $request_data['raw'];
        // $raw_str = str_replace('\\', '', $raw_str);

        if (empty($raw_str)) {
            // Bad request
            // abort(401);
            return response(null, 400);
        }

        $raw_json = json_decode($raw_str, true);
        // dd($raw_json);
        if (empty($raw_json)) {
            // Bad request
            // abort(401);
            return response(null, 400);
        }

        // dd($raw_json);

        // Log::debug('Request checked');

        $data = $raw_json;
        $status_code = $this->check_event($request, $data);

        // Check HTTP status code from type function
        if ($status_code != 200) {
            return response(null, $status_code);
        }

        // Update the event after header validation
        $webhook_log->event = $this->event;
        $webhook_log->save();

        // Log::debug('Type: ' . $this->type);

        // dd($data);


        switch ($this->event) {
            case 'subscription.created':
                $subscription_controller = new SubscriptionController($this->webhook->user_id);
                $status_code = $subscription_controller->create($request, $data);
                break;

            case 'subscription.updated':
                $subscription_controller = new SubscriptionController($this->webhook->user_id);
                $status_code = $subscription_controller->update($request, $data);
                break;

            case 'subscription.deleted':
                $subscription_controller = new SubscriptionController($this->webhook->user_id);
                $status_code = $subscription_controller->delete($request, $data);
                break;

            case 'subscription.refunded':
                $subscription_controller = new SubscriptionController($this->webhook->user_id);
                $status_code = $subscription_controller->refund($request, $data);
                break;

            case 'subscription.canceled':
                $subscription_controller = new SubscriptionController($this->webhook->user_id);
                $status_code = $subscription_controller->cancel($request, $data);
                break;

            default:
        }

        // Log::debug('End');

        if (is_int($status_code) && $status_code >= 200) {
            return response(null, $status_code);
        }

        return response(null, 406);
    }

    private function authenticate_data(Request $request, $request_data)
    {
        // dd($request_data);
        if ($request->header('CONTENT_TYPE') != 'application/json') {
            return 400;
        }

        // Webhook authentication with token
        if (empty($request_data['token'])) {
            return 400;
        }

        $webhook = Webhook::where('token', $request_data['token'])->first();

        if (empty($webhook->id)) {
            return 401;
        }

        $this->webhook = $webhook;

        // Log::debug('$request_signature: ' . $request_signature);

        return 200;
    }

    private function check_event(Request $request, $data = null)
    {
        // Fetch topic
        if ($request->hasHeader('HTTP_X_SUBSHERO_WEBHOOK_TOPIC')) {
            $this->event = $request->header('HTTP_X_SUBSHERO_WEBHOOK_TOPIC');
        } else if ($request->hasHeader('X_SUBSHERO_WEBHOOK_TOPIC')) {
            $this->event = $request->header('X_SUBSHERO_WEBHOOK_TOPIC');
        } else if ($request->hasHeader('X-Subshero-Webhook-Topic')) {
            $this->event = $request->header('X-Subshero-Webhook-Topic');
        } else {
            return 403;
        }

        // Validate Webhook event
        if (!in_array($this->event, $this->webhook->events)) {
            return 403;
        }

        return 200;
    }
}
