<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Api\v1\Controller;
use App\Models\ExtensionSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\Api\User as ApiUser;

class SettingsController extends Controller
{
    /**
     * SettingsController constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get Settings api
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $output = [
            'extension' => [
                'auto_detect_subscriptions' => 0,
                'browser_notifications' => 0,
                'notify_before_days' => 0,
            ],
            'user' => ApiUser::where('users.id', Auth::user()->id)
                ->join('users_plans', 'users.id', '=', 'users_plans.user_id')
                ->leftJoin('plans', 'plans.id', '=', 'users_plans.plan_id')
                ->select('users.*', 'plans.name as plan_name')
                ->first(),
            // 'user' => Auth::user(),
        ];

        $data = ExtensionSettings::where('extension_settings.user_id', Auth::id())
            ->select('extension_settings.*')
            ->first();

        if (!empty($data->id)) {
            $output['extension'] = [
                'auto_detect_subscriptions' => $data->auto_detect_subscriptions,
                'browser_notifications' => $data->browser_notifications,
                'notify_before_days' => $data->notify_before_days,
            ];
        }

        return $output;
        // return response()->json(lib()->user->default);
    }

    /**
     * Update Settings api
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $fields = $request->validate([
            'auto_detect_subscriptions' => 'nullable|in:0,1',
            'browser_notifications' => 'nullable|in:0,1',
            'notify_before_days' => 'nullable|integer|min:1',
        ]);

        $data = ExtensionSettings::where('extension_settings.user_id', Auth::id())->first();

        if (empty($data->id)) {
            $data = new ExtensionSettings();
            $data->user_id = Auth::id();
            $data->auto_detect_subscriptions = $request->input('auto_detect_subscriptions');
            $data->browser_notifications = $request->input('browser_notifications');
            $data->notify_before_days = $request->input('notify_before_days');
            $data->save();
        } else {
            ExtensionSettings::where('extension_settings.user_id', Auth::id())
                ->update([
                    'auto_detect_subscriptions' => $request->input('auto_detect_subscriptions') ? 1 : 0,
                    'browser_notifications' => $request->input('browser_notifications') ? 1 : 0,
                    'notify_before_days' => $request->input('notify_before_days'),
                ]);
        }

        return response([
            'status' => true,
            'message' => 'Success',
        ], 200);
    }
}
