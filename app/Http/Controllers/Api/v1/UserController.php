<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Api\v1\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tag;
use App\Models\TagModel;
use App\Models\User;
use App\Models\UserModel;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // RESTful API standards

  /**
   * UserController constructor.
   */
 public function __construct()
 {
  parent::__construct();
 }

 /**
  * Get User api
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\JsonResponse
  */
 public function show(Request $request)
 {
  // Merge the request with the value from the route parameter and validate the request
  $request->merge(['id' => $request->route('id')]);
  $fields = $request->validate([
   'id' => 'required|integer|in:' . Auth::id(),
  ]);

  // Find the record from database and return success response
  $data = User::find($fields['id']);
  return response()->json($data, 200); // 200 OK
 }

    
        
                /**
                 * Update User api
                 *
                 * @param  \Illuminate\Http\Request  $request
                 * @return \Illuminate\Http\JsonResponse
                 */
                public function update(Request $request)
                {
                    // Merge the request with the value from the route parameter and validate the request
                    $request->merge(['id' => $request->route('id')]);
                    $fields = $request->validate([
                        'id' => 'required|integer|in:' . Auth::id(),
                        'first_name' => 'required|string|max:255',
                        'last_name' => 'required|string|max:255',
                        'email' => [
                            'required',
                            'string',
                            'max:' . len()->users->email,
                            Rule::unique('users', 'email')->ignore(Auth::id()),
                        ],
                        'password' => 'nullable|string|min:8',
                        'phone' => 'nullable|string|max:' . len()->users->phone,
                        'country' => 'required|string|max:5',
                        'company_name' => 'nullable|string|max:' . len()->users->company_name,
                        'facebook_username' => 'nullable|string|alpha_dash|max:' . len()->users->facebook_username,
                    ]);
        
                    // Update data into database
                    $row = User::find($fields['id']);
                    $row->first_name = $fields['first_name'];
                    $row->last_name = $fields['last_name'];
                    $row->name = $fields['first_name'] . ' ' . $fields['last_name'];
                    // $row->email = $fields['email'];
                    $row->phone = $fields['phone'];
                    $row->country = $fields['country'];
                    $row->company_name = $fields['company_name'];
                    $row->facebook_username = $fields['facebook_username'];
        
                    // Check if user not subscribed to Team plan
                    if (empty(Auth::user()->team_user_id)) {
                        $row->email = $fields['email'];
                    }
        
                    // Check if password submitted
                    if (!empty($fields['password'])) {
                        $row->password = Hash::make($fields['password']);
                    }
        
                    $row->save();
                    $row->image = img_url($row->image);
        
                    // Return success response with updated data (200 OK)
                    return response()->json($row, 200);
                }
        /**
         * Get User Preferences api
         *
         * @param  \Illuminate\Http\Request  $request
         * @return \Illuminate\Http\JsonResponse
         */
        public function showPreferences(Request $request)
        {
            // Merge the request with the value from the route parameter and validate the request
            $request->merge(['id' => $request->route('id')]);
            $fields = $request->validate([
                'id' => 'required|integer|in:' . Auth::id(),
            ]);
    
            // Find the record from database and return success response
            $data = UserModel::get_profile();
            return response()->json($data, 200); // 200 OK
        }
    public function updatePreferences(Request $request)
    {
        // Merge the request with the value from the route parameter and validate the request
        $request->merge(['id' => $request->route('id')]);
        $fields = $request->validate([
            'timezone' => 'required|string|max:' . len()->users_profile->timezone,
            'currency' => 'required|string|max:' . len()->users_profile->currency,
            // 'language' => 'nullable|string|max:' . len()->users_profile->language,
            // 'time_period' => 'nullable|integer',
            // 'time_cycle' => 'nullable|integer',
            // 'time' => 'nullable|date_format:H:i',
            // 'monthly_report' => 'nullable|integer|digits_between:0,1',
            // 'monthly_report_time' => 'nullable|date_format:H:i',

            'billing_frequency' => 'required|integer|between:1,40',
            'billing_cycle' => 'required|integer|between:1,9',
            'billing_type' => 'required|integer|in:1,2',
            // 'payment_mode' => 'nullable|string|max:' . len()->users_profile->payment_mode,
            // 'payment_mode_id' => 'required|integer',
        ]);

        $profile_data = [
            'timezone' => $fields['timezone'],
            'currency' => $fields['currency'],

            'billing_frequency' => $fields['billing_frequency'],
            'billing_cycle' => $fields['billing_cycle'],
            'billing_type' => $fields['billing_type'],
        ];

        $status = UserModel::update_profile(Auth::id(), $profile_data);

        // Return success response with updated data
        return response()->json($profile_data, 200);
    }

    public function showBilling(Request $request)
    {
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

        // Round percent
        $billing->subscription_percent = round($billing->subscription_percent, 2);
        $billing->folder_percent = round($billing->folder_percent, 2);
        $billing->tag_percent = round($billing->tag_percent, 2);
        $billing->contact_percent = round($billing->contact_percent, 2);
        $billing->payment_method_percent = round($billing->payment_method_percent, 2);
        $billing->alert_profile_percent = round($billing->alert_profile_percent, 2);
        $billing->webhook_percent = round($billing->webhook_percent, 2);
        $billing->team_percent = round($billing->team_percent, 2);
        $billing->storage_percent = round($billing->storage_percent, 2);

        // Find the record from database and return success response
        return response()->json($billing, 200);
    }
}
