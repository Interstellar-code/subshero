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
use App\Models\ProductModel;
use App\Models\SubscriptionModel;
use App\Models\TagModel;
use App\Models\UserModel;
use App\Models\PaymentMethodModel;
use App\Models\Webhook;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class WebhookController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
    }

    public function index()
    {
        $data = [
            'slug' => 'webhook',
        ];
        return view('client/settings/webhook/index', $data);
    }

    public function get(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => [
                'required',
                'integer',
                Rule::exists('webhooks', 'id')
                    ->where(function ($query) {
                        return $query->where('user_id', Auth::id());
                    }),
            ],
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
            // abort(419);
        }

        return Response::json(UserModel::webhook_get($request->input('id')));
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|integer|in:1,2',
            'name' => 'required|string|max:' . len()->webhooks->name,
            'endpoint_url' => 'required|string|max:' . len()->webhooks->endpoint_url,
            'events.*' => 'required|string|max:' . len()->webhooks->events,
            'token' => 'nullable|string|max:' . len()->webhooks->token,
            'status' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
            // abort(419);
        }

        // Check limit
        if (UserModel::limit_reached('webhook')) {
            return lib()->do->get_limit_msg($request, 'webhook');
        }

        $webhook = new Webhook();
        $webhook->user_id = Auth::id();
        $webhook->type = $request->input('type');
        $webhook->name = $request->input('name');
        $webhook->endpoint_url = $request->input('endpoint_url');
        $webhook->events = $request->input('events');
        $webhook->token = $request->input('token');
        // $webhook->events = implode(',', $request->input('events'));
        $webhook->status = $request->input('status') ? 1 : 0;
        $webhook->save();


        if ($request->ajax()) {
            return Response::json([
                'status' => true,
                'message' => 'Success',
            ], 200);
        } else {
            return back();
        }
    }

    public function edit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => [
                'required',
                'integer',
                Rule::exists('webhooks', 'id')
                    ->where(function ($query) {
                        return $query->where('user_id', Auth::id());
                    }),
            ],
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
            // abort(419);
        }

        $data = Webhook::find($request->input('id'));
        // $data->event_all = explode(',', $data->events);

        $output = [
            'slug' => 'settings/webhook',
            'data' => $data,
        ];

        return view('client/settings/webhook/edit', $output);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => [
                'required',
                'integer',
                Rule::exists('webhooks', 'id')
                    ->where(function ($query) {
                        return $query->where('user_id', Auth::id());
                    }),
            ],
            'type' => 'required|integer|in:1,2',
            'name' => 'required|string|max:' . len()->webhooks->name,
            'endpoint_url' => 'required|string|max:' . len()->webhooks->endpoint_url,
            'events.*' => 'required|string|max:' . len()->webhooks->events,
            'token' => 'nullable|string|max:' . len()->webhooks->token,
            'status' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
            // abort(419);
        }
        // dd($request->input('events'));

        $webhook = Webhook::find($request->input('id'));
        $webhook->type = $request->input('type');
        $webhook->name = $request->input('name');
        $webhook->endpoint_url = $request->input('endpoint_url');
        $webhook->events = $request->input('events');
        $webhook->token = $request->input('token');
        // $webhook->events = implode(',', $request->input('events'));
        $webhook->status = $request->input('status') ? 1 : 0;
        $webhook->save();


        if ($request->ajax()) {
            return Response::json([
                'status' => true,
                'message' => 'Success',
            ], 200);
        } else {
            return back();
        }
    }

    public function delete(Request $request)
    {
        $validator = Validator::make(request()->all(), [
            'id' => [
                'required',
                'integer',
                Rule::exists('webhooks', 'id')
                    ->where(function ($query) {
                        return $query->where('user_id', Auth::id());
                    }),
            ],
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
            // abort(419);
        }

        Webhook::find($request->input('id'))->delete();

        return Response::json([
            'status' => true,
            'message' => 'Success',
        ], 200);
    }

    public function generate()
    {
        // Generate random 32 character string which is not in use
        $random_string = '';
        do {
            $random_string = Str::random(32);
        } while (Webhook::where('endpoint_url', 'like', '%' . $random_string . '%')->exists());

        return response()->json([
            'token' => $random_string,
            'endpoint_url' => route('webhook/user/data', $random_string),
        ]);
    }
}
