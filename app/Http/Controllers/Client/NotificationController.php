<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Library\Cron;
use App\Library\NotificationEngine;
use App\Models\BrandModel;
use App\Models\FolderModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\SubscriptionModel;
use App\Models\File;
use App\Models\KoolReportModel;
use App\Models\ProductModel;
use App\Models\PushNotificationRegister;
use App\Models\TagModel;
use App\Models\UserModel;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class NotificationController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'auth' => 'required|string|max:' . len()->push_notification_registers->auth,
            'browser' => 'required|string|max:' . len()->push_notification_registers->browser,
            'endpoint' => 'required|string|max:' . len()->push_notification_registers->endpoint,
            'lang' => 'required|string|max:' . len()->push_notification_registers->lang,
            'p256dh' => 'required|string|max:' . len()->push_notification_registers->p256dh,
            'reg_id' => 'required|string|unique:push_notification_registers,reg_id|max:' . len()->push_notification_registers->reg_id,
            'subscription_spec' => 'required|integer',
            'subscription_strategy' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
        }

        // Register for push notification
        $push_notification_register = new PushNotificationRegister();
        $push_notification_register->service_provider = 'Gravitec.net';
        $push_notification_register->user_id = Auth::user()->id;
        $push_notification_register->auth = $request->input('auth');
        $push_notification_register->browser = $request->input('browser');
        $push_notification_register->endpoint = $request->input('endpoint');
        $push_notification_register->lang = $request->input('lang');
        $push_notification_register->p256dh = $request->input('p256dh');
        $push_notification_register->reg_id = $request->input('reg_id');
        $push_notification_register->subscription_spec = $request->input('subscription_spec');
        $push_notification_register->subscription_strategy = $request->input('subscription_strategy');
        $push_notification_register->save();


        return Response::json([
            'status' => true,
            'message' => 'Success',
        ], 200);
    }

    public function notification_edit(Request $request) {
        $params = $request->all();
        $params['type'] = $request->route('type');
        $notificatin_types = join(',', NotificationEngine::getNotificationTypes());
        $validator = Validator::make($params, [
            'id' => 'required|integer',
            'type' => "required|string|in:$notificatin_types",
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
        }
        $notification = NotificationEngine::staticModel($params['type'])::getNotification(['event_id' => $params['id']]);
        if (!$notification) {
            return Response::json([
                'status' => false,
                'message' => 'Notification not found',
            ]);
        }
        $notification->language_type = NotificationEngine::getLangNotificationType($params['type']);
        $notification->type = $params['type'];
        $notification->id = $params['id'];
        $data = [
            'data' => $notification,
        ];

        return view('client/notification/edit', $data);
    }

    public function notification_delete(Request $request) {
        $params = $request->all();
        $params['type'] = $request->route('type');
        $notificatin_types = join(',', NotificationEngine::getNotificationTypes());
        $table = NotificationEngine::events_table($params['type']);
        $validator = Validator::make($params, [
            'id' => [
                'required',
                'integer',
                Rule::exists($table, 'id')->where(function ($query) {
                    $query->where('user_id', Auth::id());
                }),
            ],
            'type' => "required|string|in:$notificatin_types",
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
        }
        $notification = NotificationEngine::staticModel($params['type'])::find_event($params['id']);
        if (!$notification) {
            return Response::json([
                'status' => false,
                'message' => 'Notification not found',
            ]);
        }
        $notification->delete();

        return Response::json([
            'status' => true,
            'message' => 'Success',
        ]);
    }
}
