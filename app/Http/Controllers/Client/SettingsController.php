<?php

namespace App\Http\Controllers\Client;

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
use App\Models\PaymentMethodModel;
use App\Models\PlanCoupon;
use App\Models\ProductModel;
use App\Models\SubscriptionModel;
use App\Models\TagModel;
use App\Models\UserModel;
use App\Models\UsersPlan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class SettingsController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
    }

    public function index()
    {
        $data = [
            // 'slug' => 'settings/profile',
        ];

        return view('client/settings/profile', $data);
    }

    public function profile()
    {
        $data = [
            'slug' => 'profile',
        ];
        return view('client/settings/profile', $data);
    }

    public function billing()
    {
        UsersPlan::update_total_all();
        $user_plan = UserModel::get_plan();

        // Default data
        $billing = new \stdClass();
        $billing->subscription_total = 0;
        $billing->subscription_limit = 0;
        $billing->subscription_percent = 0;

        $billing->folder_total = 0;
        $billing->folder_limit = 0;
        $billing->folder_percent = 0;

        $billing->tag_total = 0;
        $billing->tag_limit = 0;
        $billing->tag_percent = 0;

        $billing->contact_total = 0;
        $billing->contact_limit = 0;
        $billing->contact_percent = 0;

        $billing->payment_method_total = 0;
        $billing->payment_method_limit = 0;
        $billing->payment_method_percent = 0;

        $billing->alert_profile_total = 0;
        $billing->alert_profile_limit = 0;
        $billing->alert_profile_percent = 0;

        $billing->webhook_total = 0;
        $billing->webhook_limit = 0;
        $billing->webhook_percent = 0;

        $billing->team_total = 0;
        $billing->team_limit = 0;
        $billing->team_percent = 0;

        $billing->storage_total = 0;
        $billing->storage_limit = 0;
        $billing->storage_percent = 0;


        if (!empty($user_plan)) {

            // Calculate subscription
            if (isset($user_plan->limit_subs) && isset($user_plan->total_subs)) {
                $user_plan->limit_subs = (int)$user_plan->limit_subs;
                $user_plan->total_subs = (int)$user_plan->total_subs;

                $billing->subscription_total = $user_plan->total_subs;
                $billing->subscription_limit = $user_plan->limit_subs;

                if ($user_plan->limit_subs > 0 && $user_plan->total_subs > 0) {
                    $billing->subscription_percent = 100 - ((($user_plan->limit_subs - $user_plan->total_subs) / $user_plan->limit_subs) * 100);
                }
            }

            // Calculate folder
            if (isset($user_plan->limit_folders) && isset($user_plan->total_folders)) {
                $user_plan->limit_folders = (int)$user_plan->limit_folders;
                $user_plan->total_folders = (int)$user_plan->total_folders;

                $billing->folder_total = $user_plan->total_folders;
                $billing->folder_limit = $user_plan->limit_folders;

                if ($user_plan->limit_folders > 0 && $user_plan->total_folders > 0) {
                    $billing->folder_percent = 100 - ((($user_plan->limit_folders - $user_plan->total_folders) / $user_plan->limit_folders) * 100);
                }
            }

            // Calculate tag
            if (isset($user_plan->limit_tags) && isset($user_plan->total_tags)) {
                $user_plan->limit_tags = (int)$user_plan->limit_tags;
                $user_plan->total_tags = (int)$user_plan->total_tags;

                $billing->tag_total = $user_plan->total_tags;
                $billing->tag_limit = $user_plan->limit_tags;

                if ($user_plan->limit_tags > 0 && $user_plan->total_tags > 0) {
                    $billing->tag_percent = 100 - ((($user_plan->limit_tags - $user_plan->total_tags) / $user_plan->limit_tags) * 100);
                }
            }

            // Calculate contact
            if (isset($user_plan->limit_contacts) && isset($user_plan->total_contacts)) {
                $user_plan->limit_contacts = (int)$user_plan->limit_contacts;
                $user_plan->total_contacts = (int)$user_plan->total_contacts;

                $billing->contact_total = $user_plan->total_contacts;
                $billing->contact_limit = $user_plan->limit_contacts;

                if ($user_plan->limit_contacts > 0 && $user_plan->total_contacts > 0) {
                    $billing->contact_percent = 100 - ((($user_plan->limit_contacts - $user_plan->total_contacts) / $user_plan->limit_contacts) * 100);
                }
            }

            // Calculate payment method
            if (isset($user_plan->limit_pmethods) && isset($user_plan->total_pmethods)) {
                $user_plan->limit_pmethods = (int)$user_plan->limit_pmethods;
                $user_plan->total_pmethods = (int)$user_plan->total_pmethods;

                $billing->payment_method_total = $user_plan->total_pmethods;
                $billing->payment_method_limit = $user_plan->limit_pmethods;

                if ($user_plan->limit_pmethods > 0 && $user_plan->total_pmethods > 0) {
                    $billing->payment_method_percent = 100 - ((($user_plan->limit_pmethods - $user_plan->total_pmethods) / $user_plan->limit_pmethods) * 100);
                }
            }

            // Calculate alert profile
            if (isset($user_plan->limit_alert_profiles) && isset($user_plan->total_alert_profiles)) {
                $user_plan->limit_alert_profiles = (int)$user_plan->limit_alert_profiles;
                $user_plan->total_alert_profiles = (int)$user_plan->total_alert_profiles;

                $billing->alert_profile_total = $user_plan->total_alert_profiles;
                $billing->alert_profile_limit = $user_plan->limit_alert_profiles;

                if ($user_plan->limit_alert_profiles > 0 && $user_plan->total_alert_profiles > 0) {
                    $billing->alert_profile_percent = 100 - ((($user_plan->limit_alert_profiles - $user_plan->total_alert_profiles) / $user_plan->limit_alert_profiles) * 100);
                }
            }

            // Calculate webhook
            if (isset($user_plan->limit_webhooks) && isset($user_plan->total_webhooks)) {
                $user_plan->limit_webhooks = (int)$user_plan->limit_webhooks;
                $user_plan->total_webhooks = (int)$user_plan->total_webhooks;

                $billing->webhook_total = $user_plan->total_webhooks;
                $billing->webhook_limit = $user_plan->limit_webhooks;

                if ($user_plan->limit_webhooks > 0 && $user_plan->total_webhooks > 0) {
                    $billing->webhook_percent = 100 - ((($user_plan->limit_webhooks - $user_plan->total_webhooks) / $user_plan->limit_webhooks) * 100);
                }
            }

            // Calculate team
            if (isset($user_plan->limit_teams) && isset($user_plan->total_teams)) {
                $user_plan->limit_teams = (int)$user_plan->limit_teams;
                $user_plan->total_teams = (int)$user_plan->total_teams;

                $billing->team_total = $user_plan->total_teams;
                $billing->team_limit = $user_plan->limit_teams;

                if ($user_plan->limit_teams > 0 && $user_plan->total_teams > 0) {
                    $billing->team_percent = 100 - ((($user_plan->limit_teams - $user_plan->total_teams) / $user_plan->limit_teams) * 100);
                }
            }

            // Calculate storage
            if (isset($user_plan->limit_storage) && isset($user_plan->total_storage)) {
                $user_plan->limit_storage = (int)$user_plan->limit_storage;
                $user_plan->total_storage = (int)$user_plan->total_storage;

                $billing->storage_total = $user_plan->total_storage;
                $billing->storage_limit = $user_plan->limit_storage;

                if ($user_plan->limit_storage > 0 && $user_plan->total_storage > 0) {
                    $billing->storage_percent = 100 - ((($user_plan->limit_storage - $user_plan->total_storage) / $user_plan->limit_storage) * 100);
                }
            }
        }


        $data = [
            'slug' => 'billing',
            'data' => $user_plan,
            'billing' => $billing,
        ];

        return view('client/settings/billing', $data);
    }

    public function billing_coupon_apply(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'coupon' => [
                'required',
                'string',
                'max:' . len()->plan_coupons->coupon,
                Rule::exists('plan_coupons', 'coupon')->where(function ($query) {
                    $query->where('status', 1);
                }),
            ],
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
        }

        $coupon = PlanCoupon::where('coupon', $request->coupon)->first();

        // Search for the next plan
        foreach (LTD_PLAN_ALL_ID as $plan_id) {
            if ($plan_id > Auth::user()->users_plan->plan_id) {
                UserModel::set_plan(Auth::user()->id, $plan_id);

                // Make coupon claimed
                $coupon->user_id = Auth::id();
                $coupon->status = 2;
                $coupon->save();
                break;
            }
        }

        if ($request->ajax()) {
            return Response::json([
                'status' => true,
                'message' => 'Success',
            ], 200);
        } else {
            return back();
        }
    }

    public function preference()
    {
        $data = [
            'slug' => 'preference',
        ];
        return view('client/settings/preference', $data);
    }

    public function profile_upload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'picture' => 'image|dimensions:min_width=100,min_height=200',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
            // abort(419);
        }

        // Upload image
        $image_path = File::add_get_path($request->file('picture'), 'avatar', Auth::id());
        UserModel::do_update(Auth::id(), [
            'image' => $image_path,
        ]);
    }

    public function profile_update_info(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'max:' . len()->users->email,
                Rule::unique('users', 'email')->ignore(Auth::id()),
            ],
            'phone' => 'nullable|string|max:' . len()->users->phone,
            'country' => 'required|string|max:5',
            'company_name' => 'nullable|string|max:' . len()->users->company_name,
            'facebook_username' => 'nullable|string|alpha_dash|max:' . len()->users->facebook_username,
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
            // abort(419);
        }

        $data = [
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'name' => $request->input('first_name') . ' ' . $request->input('last_name'),
            // 'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'country' => $request->input('country'),
            'company_name' => $request->input('company_name'),
            'facebook_username' => $request->input('facebook_username'),
        ];

        // Check if user not subscribed to Team plan
        if (empty(Auth::user()->team_user_id)) {
            $data['email'] = $request->input('email');
        }


        $status = UserModel::do_update(Auth::id(), $data);

        if ($request->ajax()) {
            return Response::json([
                'status' => true,
                'message' => 'Success',
            ], 200);
        } else {
            return back();
        }
    }

    public function profile_update_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|confirmed|min:8|max:255',
            // 'confirm_password' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
            // abort(419);
        }

        $data = [
            'password' => Hash::make($request->input('password')),
        ];

        $status = UserModel::do_update(Auth::id(), $data);

        if ($request->ajax()) {
            return Response::json([
                'status' => true,
                'message' => 'Success',
            ], 200);
        } else {
            return back();
        }
    }

    public function preference_update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'timezone' => 'nullable|string|max:50',
            'currency' => 'nullable|string|max:10',
            'language' => 'nullable|string|max:20',
            'time_period' => 'nullable|integer',
            'time_cycle' => 'nullable|integer',
            'time' => 'nullable|date_format:H:i',
            'monthly_report' => 'nullable|integer|digits_between:0,1',
            'monthly_report_time' => 'nullable|date_format:H:i',

            'billing_frequency' => 'nullable|integer|between:1,40',
            'billing_cycle' => 'nullable|integer|between:1,9',
            'billing_type' => 'nullable|integer|in:2',
            // 'payment_mode' => 'nullable|string|max:' . len()->users_profile->payment_mode,
            'payment_mode_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
            // abort(419);
        }

        $profile_data = [
            'timezone' => $request->input('timezone'),
            'currency' => $request->input('currency'),
            'language' => $request->input('language'),

            'billing_frequency' => $request->input('billing_frequency'),
            'billing_cycle' => $request->input('billing_cycle'),
            'billing_type' => $request->input('billing_type') ?? 1,
            // 'payment_mode' => $request->input('payment_mode'),
            'payment_mode_id' => $request->input('payment_mode_id'),
        ];

        $alert_preference_data = [
            'time_period' => $request->input('time_period'),
            'time_cycle' => $request->input('time_cycle'),
            'time' => $request->input('time'),
            'monthly_report' => $request->input('monthly_report'),
            'monthly_report_time' => $request->input('monthly_report_time'),
        ];

        $status = UserModel::update_profile(Auth::id(), $profile_data);
        $status = UserModel::update_alert_preference(Auth::id(), $alert_preference_data);

        if ($request->ajax()) {
            return Response::json([
                'status' => true,
                'message' => 'Success',
            ], 200);
        } else {
            return back();
        }
    }
}
