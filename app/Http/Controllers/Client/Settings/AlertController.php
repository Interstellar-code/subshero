<?php

namespace App\Http\Controllers\Client\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Application as lib;
use Illuminate\Support\Carbon;
use App\Models\FolderModel;
use App\Library\NotificationEngine;
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
use App\Models\UserAlert;
use App\Models\UsersPlan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AlertController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
    }

    public function index()
    {
        $data = [
            'slug' => 'alert',
        ];
        return view('client/settings/alert/index', $data);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'is_default' => 'nullable|integer|in:0,1',
            'alert_name' => [
                'required',
                'string',
                'max:' . len()->users_alert->alert_name,
                Rule::unique('users_alert', 'alert_name')
                    ->where(function ($query) {
                        return $query->where('user_id', Auth::id());
                    }),
            ],
            'time_period' => 'required|integer|min:0|max:' . len()->users_alert->time_period,
            // 'time_cycle' => 'required|integer',
            'time' => 'required|date_format:H:i',
            'alert_condition' => 'required|integer',
            'alert_contact' => 'nullable|integer',
            'alert_types' => 'required|array|max:' . len()->users_alert->alert_types->array_length,
            'alert_types.*' => 'string|max:' . len()->users_alert->alert_types->string_length,
            'timezone' => 'required|string|max:' . len()->users_alert->timezone,
            'alert_subs_type' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
            // abort(419);
        }
        
        $is_default = $request->input('is_default') ? 1 : 0;

        if ($is_default) {
            UserAlert::clear_default($request->input('alert_subs_type'));
        }

        // Check limit
        if (UserModel::limit_reached('alert')) {
            return lib()->do->get_limit_msg($request, 'alert');
        }

        $data = [
            'user_id' => Auth::id(),
            'is_default' => $is_default,
            'alert_name' => $request->input('alert_name'),
            'time_period' => lib()->do->get_cycle_frequency($request->input('time_period_cycle'), $request->input('time_period')),
            // 'time_cycle' => $request->input('time_cycle'),
            'time' => $request->input('time'),
            'alert_condition' => $request->input('alert_condition'),
            'alert_contact' => $request->input('alert_contact'),
            'alert_types' => $request->input('alert_types'),
            'timezone' => $request->input('timezone'),
            'alert_subs_type' => $request->input('alert_subs_type'),
        ];

        $alert_id = UserAlert::create($data);
        $this->add_event($alert_id, 'create');

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
            'id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
            // abort(419);
        }

        $data = UserAlert::find($request->input('id'));
        if (empty($data)) {
            return lib()->do->json_response(false, __('Alert profile not found.'), 403);
        }

        $time_period_cycle_arr = lib()->do->cycle_convert($data->time_period);
        $data->time_period_cycle = $time_period_cycle_arr['cycle'];
        $data->time_period = $time_period_cycle_arr['frequency'];


        $output = [
            'slug' => 'settings/alert',
            'data' => $data,
        ];

        return view('client/settings/alert/edit', $output);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
            'is_default' => 'nullable|integer|in:0,1',
            'alert_name' => [
                'required',
                'string',
                'max:' . len()->users_alert->alert_name,
                Rule::unique('users_alert', 'alert_name')
                    ->ignore($request->input('id'))
                    ->where(function ($query) {
                        return $query->where('user_id', Auth::id());
                    }),
            ],
            'time_period' => 'required|integer|min:0|max:' . len()->users_alert->time_period,
            // 'time_cycle' => 'required|integer',
            'time' => 'required|date_format:H:i',
            'alert_condition' => 'required|integer',
            'alert_contact' => 'nullable|integer',
            'alert_types' => 'required|array|max:' . len()->users_alert->alert_types->array_length,
            'alert_types.*' => 'string|max:' . len()->users_alert->alert_types->string_length,
            'timezone' => 'required|string|max:' . len()->users_alert->timezone,
            'alert_subs_type' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
            // abort(419);
        }

        $is_default = $request->input('is_default') ? 1 : 0;

        if ($is_default) {
            UserAlert::clear_default($request->input('alert_subs_type'));
        }

        $data = [
            'is_default' => $is_default,
            'alert_name' => $request->input('alert_name'),
            'time_period' => lib()->do->get_cycle_frequency($request->input('time_period_cycle'), $request->input('time_period')),
            // 'time_cycle' => $request->input('time_cycle'),
            'time' => $request->input('time'),
            'alert_condition' => $request->input('alert_condition'),
            'alert_contact' => $request->input('alert_contact'),
            'alert_types' => $request->input('alert_types'),
            'timezone' => $request->input('timezone'),
            'alert_subs_type' => $request->input('alert_subs_type'),
        ];

        $status = UserAlert::do_update($request->input('id'), $data);

        $this->add_event($request->input('id'), 'update');

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
            'id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
            // abort(419);
        }

        // Check if in use
        $is_using = UserAlert::is_using($request->input('id'));
        if ($is_using) {
            return Response::json([
                'status' => false,
                'message' => __('This alert profile is currently being used.'),
            ]);
        }

        // Delete data
        else {
            $this->add_event($request->input('id'), 'delete');

            return Response::json([
                'status' => UserAlert::del($request->input('id')),
                'message' => 'Success',
            ], 200);
        }
    }

    private function add_event($id, $event_type_status)
    {
        // Create event logs
        NotificationEngine::staticModel('event')::create([
            'user_id' => Auth::id(),
            'event_type' => 'user_settings',
            'event_type_status' => $event_type_status,
            'event_status' => 1,
            'table_name' => 'users_alert',
            'table_row_id' => $id,
            'event_type_function' => __CLASS__ . '::' . __FUNCTION__ . '()',
        ]);
    }
}
