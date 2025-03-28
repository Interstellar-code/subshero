<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Library\NotificationEngine;
use App\Models\File;
use App\Models\FolderModel;
use App\Models\Folder;
use App\Models\Marketplace;
use App\Models\ProductModel;
use App\Models\Subscription;
use App\Models\SubscriptionHistoryModel;
use App\Models\SubscriptionModel;
use App\Models\SubscriptionAttachment;
use App\Models\TagModel;
use App\Models\UserModel;
use App\Models\Webhook;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Product;

class SubscriptionController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');

        if (!Session::has('subscription_days')) {
            Session::put('subscription_days', 365);
        }
        if (!Session::has('lifetime_days')) {
            Session::put('lifetime_days', 365);
        }

        // KoolReport variable convert
        if (isset($_POST['saleDrillDown']['currentLevel'][1]['month']) && !ctype_digit($_POST['saleDrillDown']['currentLevel'][1]['month'])) {
            $_POST['saleDrillDown']['currentLevel'][1]['month'] = date('m', strtotime($_POST['saleDrillDown']['currentLevel'][1]['month']));
        }
    }

    public function index(Request $request)
    {
        $user_id = Auth::id();

        // UserModel::set_default_picture($user_id);

        $this->set_tour_status($request);

        // lib()->cache->subscription_area_chart = SubscriptionModel::get_subscription_area_chart();
        // SubscriptionModel::get_lifetime_drilldown();

        // Get Lifetime data
        $data = [
            'slug' => 'subscription',
        ];

        return view('client/subscription/index', $data);
    }

    public function index_1(Request $request)
    {
        $user_id = Auth::id();

        // UserModel::set_default_picture($user_id);

        $this->set_tour_status($request);

        lib()->cache->subscription_area_chart = lib()->kr->get_subscription_area_chart(local('subscription_days', 0));

        // // Get Lifetime data
        // if (empty(local('subscription_folder_id'))) {

        //     // Search for selected days
        //     if (local('lifetime_days', 0) <= 0) {
        //         $lifetime_data = DB::table('subscriptions')
        //             ->where('user_id', Auth::user()->id)
        //             ->where('status', 1)
        //             ->where('type', 3)
        //             ->get();
        //     } else {
        //         $lifetime_data = DB::table('subscriptions')
        //             ->where('user_id', Auth::user()->id)
        //             ->where('status', 1)
        //             ->where('type', 3)
        //             ->whereBetween('payment_date', [date('Y-m-d', strtotime('-' . local('lifetime_days', 30) . ' days')), date('Y-m-d', strtotime('+1 days'))])
        //             ->get();
        //     }
        // } else {

        //     // Search for selected days
        //     if (local('lifetime_days', 0) <= 0) {
        //         $lifetime_data = DB::table('subscriptions')
        //             ->where('user_id', Auth::user()->id)
        //             ->where('folder_id', local('subscription_folder_id'))
        //             ->where('status', 1)
        //             ->where('type', 3)
        //             ->get();
        //     } else {
        //         $lifetime_data = DB::table('subscriptions')
        //             ->where('user_id', Auth::user()->id)
        //             ->where('folder_id', local('subscription_folder_id'))
        //             ->where('status', 1)
        //             ->where('type', 3)
        //             ->whereBetween('payment_date', [date('Y-m-d', strtotime('-' . local('lifetime_days', 30) . ' days')), date('Y-m-d', strtotime('+1 days'))])
        //             ->get();
        //     }
        // }

        // Get Lifetime data
        if (empty(local('subscription_folder_id'))) {
            $lifetime_data = DB::table('subscriptions')
                ->where('user_id', Auth::user()->id)
                ->where('status', 1)
                ->where('type', 3)
                ->get();
        } else {
            $lifetime_data = DB::table('subscriptions')
                ->where('user_id', Auth::user()->id)
                ->where('folder_id', local('subscription_folder_id'))
                ->where('status', 1)
                ->where('type', 3)
                ->get();

            $folder_data = FolderModel::get(local('subscription_folder_id'));
            if (!empty($folder_data->id)) {

                // Get folder name to display in the charts
                lib()->cache->subscription_folder_name = $folder_data->name;
            }
        }

        $lifetime_total_price = 0;
        $lifetime_total_count = 0;
        $lifetime_this_year_price = 0;
        $lifetime_this_year_count = 0;
        $lifetime_active_count = 0;
        $lifetime_active_price = 0;
        $lifetime_monthly_price = 0;

        // Count total price in $lifetime_data
        foreach ($lifetime_data as $val) {

            // Count item in $lifetime_data
            $lifetime_total_count++;
            $lifetime_total_price += $val->price;

            // Count total this year price in $lifetime_data
            if (date('Y', strtotime($val->payment_date)) == date('Y')) {
                $lifetime_this_year_count++;
                $lifetime_this_year_price += $val->price;
            }

            // Count total active price in $lifetime_data
            if ($val->status == 1) {
                $lifetime_active_count++;
                $lifetime_active_price += $val->price;
            }
        }

        if (count($lifetime_data) > 0) {

            $to = Carbon::createFromFormat('Y-m-d', $lifetime_data[0]->payment_date);
            $from = Carbon::createFromFormat('Y-m-d', $lifetime_data[count($lifetime_data) - 1]->payment_date);
            $diff_in_days = $to->diffInDays($from);
            // $diff_in_months = $to->diffInMonths($from);
            $diff_in_months = $diff_in_days / 30;
            // print_r($diff_in_months); // Output: 1
            // dd($diff_in_months);

            // dd($lifetime_data[0]->payment_date, $lifetime_data[count($lifetime_data) - 1]->payment_date);
            // dd($diff_in_days, $diff_in_months, $subscription_total_value, number_format((float)($subscription_total_value / $diff_in_months), 2, '.', ''));
            if ($diff_in_months < 1) {
                $diff_in_months = 1;
            }

            // $diff_in_months = round($diff_in_months);

            if ($lifetime_total_price > 0 && $diff_in_months > 0) {
                $lifetime_monthly_price = number_format((float) ($lifetime_total_price / $diff_in_months), 2, '.', '');
            } else if ($diff_in_months == 0) {
                $lifetime_monthly_price = $lifetime_total_price;
            }

            // dd($diff_in_months, $lifetime_total_price, $lifetime_monthly_price);

            if ($lifetime_monthly_price < 0) {
                $lifetime_monthly_price = 0;
            }
        }

        // Store in session
        $_SESSION['lifetime_total_price'] = $lifetime_total_price;
        $_SESSION['lifetime_total_count'] = $lifetime_total_count;
        $_SESSION['lifetime_this_year_price'] = $lifetime_this_year_price;
        $_SESSION['lifetime_this_year_count'] = $lifetime_this_year_count;
        $_SESSION['lifetime_active_count'] = $lifetime_active_count;
        $_SESSION['lifetime_active_price'] = $lifetime_active_price;
        $_SESSION['lifetime_monthly_price'] = $lifetime_monthly_price;

        // $subscription_data = DB::table('subscriptions')
        //     ->where('user_id', Auth::user()->id)
        //     ->where('status', 1)
        //     ->where('type', 1)
        //     ->get();

        // $subscription_total_count = 0;
        // $subscription_total_price = 0;
        // $subscription_monthly_price = 0;
        // $subscription_id_all = [];

        // foreach ($subscription_data as &$schedule) {
        //     $schedule->calc_next_payment_date_formatted = date('jS M Y', strtotime($schedule->calc_next_payment_date));
        //     $subscription_total_price += $schedule->price;

        //     if (!in_array($schedule->id, $subscription_id_all)) {
        //         $subscription_id_all[] = $schedule->id;
        //         $subscription_total_count++;
        //     }
        // }

        // // count total months in $subscription_data
        // if (count($subscription_data) > 0) {
        //     // $subscription_monthly_price = $subscription_total_price / count($subscription_data);

        //     $to = Carbon::createFromFormat('Y-m-d', $subscription_data[0]->calc_next_payment_date);
        //     $from = Carbon::createFromFormat('Y-m-d', $subscription_data[count($subscription_data) - 1]->calc_next_payment_date);
        //     $diff_in_days = $to->diffInDays($from);
        //     // $diff_in_months = $to->diffInMonths($from);
        //     $diff_in_months = $diff_in_days / 30;
        //     // print_r($diff_in_months); // Output: 1
        //     // dd($diff_in_months);

        //     // dd($subscription_data[0]->calc_next_payment_date, $subscription_data[count($subscription_data) - 1]->calc_next_payment_date);
        //     // dd($diff_in_days, $diff_in_months, $subscription_total_price, number_format((float)($subscription_total_price / $diff_in_months), 2, '.', ''));

        //     if ($diff_in_months < 1) {
        //         $diff_in_months = 1;
        //     }

        //     $diff_in_months = round($diff_in_months);

        //     if ($subscription_total_price > 0 && $diff_in_months > 0) {
        //         $subscription_monthly_price = number_format((float)($subscription_total_price / $diff_in_months), 2, '.', '');
        //     } else if ($diff_in_months == 0) {
        //         $subscription_monthly_price = $subscription_total_price;
        //     }

        //     if ($subscription_monthly_price < 0) {
        //         $subscription_monthly_price = 0;
        //     }
        // }

        // $_SESSION['subscription_monthly_price'] = $subscription_monthly_price;
        // $_SESSION['subscription_total_price'] = $subscription_total_price;
        // $_SESSION['subscription_total_count'] = $subscription_total_count;

        // Get Lifetime data
        $data = [
            'slug' => 'subscription',
        ];

        return view('client/subscription/index', $data);
    }

    private function set_tour_status(Request $request)
    {
        $tour_status = $request->input('tour_status');
        if ($tour_status == 1) {
            UserModel::tour_finished();
        }
        return true;
    }

    private function get_expiry_date_rule(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'payment_date' => 'required|date_format:Y-m-d',
            'refund_days' => 'nullable|integer|min:1|max:' . len()->subscriptions->refund_days,
            'refund_date' => 'nullable|date_format:Y-m-d',
        ]);

        if (!$validator->fails()) {
            $payment_date = $request->input('payment_date');
            $refund_days = $request->input('refund_days');
            $refund_date = $request->input('refund_date');

            // Calculate new date
            if (!empty($refund_date)) {
                return "after_or_equal:$refund_date";
            } else if (!empty($refund_days)) {
                $date = date('Y-m-d', strtotime($payment_date . "+$refund_days days"));
                return "after_or_equal:$date";
            }
        }

        return 'after:today';
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'nullable|boolean',
            'type' => 'required|numeric|digits_between:0,9',
            'folder_id' => [
                'nullable',
                'string',
                'max:255',
                function ($attribute, $value, $fail) use ($request) {
                    if ($value > 0) {
                        $folder = Folder::find($value);
                        if (!$folder) {
                            $folder = Folder::where('name', $value)->where('user_id', Auth::id())->first();
                        }
                        if ($folder) {
                            if (!$folder->price_type) {
                                $folder->price_type = 'All';
                                $folder->save();
                            }
                            $price_type = $request->input('price_type');
                            if ($folder->price_type != $price_type && $folder->price_type != 'All') {
                                $fail(__('The currency code ') . $folder->price_type . __(' of folder ') . $folder->name . __(" doesn't match the currency code ") . $price_type . __(' of the subscription.'));
                            }
                        }
                    }
                }
            ],
            'category_id' => 'nullable|integer',
            'alert_id' => 'required|integer',
            // 'tags' => 'required|numeric',
            // 'client_name' => 'required|string|max:255',
            'brand_id' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'price_type' => 'required|string|max:10',
            'discount_voucher' => 'nullable|string|max:20',
            // 'payment_mode' => 'required|string|max:' . len()->subscriptions->payment_mode,
            'payment_mode_id' => 'required|integer',
            'payment_date' => 'required|date_format:Y-m-d',
            'expiry_date' => 'nullable|date_format:Y-m-d|after:today|after:payment_date|prohibited_if:type,3|' . $this->get_expiry_date_rule($request),
            'recurring' => 'nullable|boolean',
            'note' => 'nullable|string|max:255',
            'include_notes' => 'nullable|boolean',
            'alert_type' => 'nullable|integer|in:1',
            'url' => 'nullable|string|max:255',
            'support_details' => 'nullable|string|max:255',
            'tags' => 'nullable|array|max:255',
            'refund_days' => 'nullable|integer|min:1|max:' . len()->subscriptions->refund_days,
            'billing_frequency' => 'nullable|numeric|digits_between:0,40',
            'billing_cycle' => 'nullable|numeric|digits_between:0,9',
            'billing_type' => 'nullable|integer|in:2',
            'img_path_or_file' => 'required|integer|digits_between:0,1',
            'image' => 'sometimes|nullable|image',
            'rating' => 'nullable|integer',

            'ltdval_price' => 'nullable|numeric|min:0',
            'ltdval_cycle' => 'nullable|numeric|digits_between:0,9',
            'ltdval_frequency' => 'nullable|numeric|digits_between:0,40',

            // Lifetime addon
            'sub_addon' => 'nullable|boolean',
            'sub_id' => [
                'nullable',
                'integer',
                Rule::exists('subscriptions', 'id')->where(function ($query) {
                    $query->where('user_id', Auth::id());
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

        // Check limit
        if (UserModel::limit_reached('subscription')) {
            return lib()->do->get_limit_msg($request, 'subscription');
        }

        // Check tag limit
        if ($this->is_tag_limit_reached($request)) {
            return lib()->do->get_limit_msg($request, 'tag');
        }

        // Get folder details
        $folder_id = $request->input('folder_id');
        if (is_numeric($folder_id) && intval($folder_id) == $folder_id) {
            $folder_id = $request->input('folder_id');
        } else {
            $folder_id = FolderModel::get_or_create($request->input('folder_id'));
        }

        // Get product information
        $product_data = $this->get_product_for_subscription($request->input('brand_id'), $request->input('type'));

        $data = [
            'user_id' => Auth::id(),
            'status' => $request->input('status') ? 1 : 0,
            'type' => $request->input('type'),
            'folder_id' => $folder_id,
            'brand_id' => $product_data['brand_id'],
            'category_id' => $request->input('category_id'),
            'alert_id' => $request->input('alert_id'),
            'product_name' => $product_data['product_name'],
            'description' => $request->input('description'),
            'price' => (float) $request->input('price'),
            'price_type' => $request->input('price_type'),
            'discount_voucher' => $request->input('discount_voucher'),
            // 'payment_mode' => $request->input('payment_mode'),
            'expiry_date' => $request->input('expiry_date'),
            'payment_mode_id' => $request->input('payment_mode_id'),
            'payment_date' => $request->input('payment_date'),
            'payment_date_upd' => $request->input('payment_date'),
            'recurring' => $request->input('recurring') ? 1 : 0,
            'note' => $request->input('note'),
            'include_notes' => $request->input('include_notes'),
            'alert_type' => $request->input('alert_type'),
            'url' => $request->input('url'),
            'support_details' => $request->input('support_details'),
            // 'tags' => $request->input('tags'),
            'refund_days' => $request->input('refund_days'),
            'billing_frequency' => $request->input('billing_frequency'),
            'billing_cycle' => $request->input('billing_cycle'),
            'billing_type' => $request->input('billing_type') ?? 1,
            'timezone' => lib()->user->get_timezone(),
            'base_value' => lib()->do->currency_convert($request->input('price'), $request->input('price_type')),
            'base_currency' => APP_CURRENCY,
            'rating' => $request->input('rating'),

            'ltdval_price' => (float) $request->input('ltdval_price'),
            'ltdval_cycle' => $request->input('ltdval_cycle'),
            'ltdval_frequency' => $request->input('ltdval_frequency'),
        ];

        // Check for empty data
        if (empty($data['billing_frequency'])) {
            $data['billing_frequency'] = 1;
        }
        if (empty($data['billing_cycle'])) {
            $data['billing_cycle'] = 1;
        }

        // Lifetime addon
        if ($request->has('sub_id')) {
            $data['sub_addon'] = 1;
            $data['sub_id'] = $request->input('sub_id');
        }

        // Set product default data
        if (!empty($product_data)) {
            $product_defaults = [
                'favicon',
                'brandname',
                'product_type',
                'pricing_type',
                'currency_code',
            ];

            foreach ($product_data as $key => $val) {
                if (in_array($key, $product_defaults)) {
                    $data[$key] = $val;
                }
            }

            // unset($data['brand_image']);
            // $data['category_id'] = $request->input('category_id');
        }

        if (!empty($request->input('refund_days'))) {
            $data['refund_date'] = date('Y-m-d', strtotime($request->input('payment_date') . ' +' . $request->input('refund_days') . ' days'));
        }

        if ($request->input('img_path_or_file') == 0 && !empty($request->input('image_path'))) {
            $data['image'] = $request->input('image_path');
        }

        $subscription_id = SubscriptionModel::create($data);

        // Add event logs
        $this->add_event([
            'table_row_id' => $subscription_id,
            'event_type_status' => 'create',
            'event_product_id' => $product_data['brand_id'],
            'event_type_schedule' => $request->input('recurring') ? 2 : 1,
        ]);

        // Save image
        $image_path = '';
        // Check if user selected an image
        if ($request->input('img_path_or_file') == 1 && $request->hasFile('image')) {
            $image_path = File::add_get_path($request->file('image'), 'subscription', $subscription_id);
            SubscriptionModel::do_update($subscription_id, [
                'image' => $image_path,
            ]);
        }

        // Check if active
        if ($request->input('status') == 1) {

            // Add events
            SubscriptionModel::set_refund_date($subscription_id);

            // Add history
            SubscriptionModel::create_new_history($subscription_id);
        }

        // Add next schedule date
        // Note: Disabled for performance issue as discussed on 2021.12.10 at 9.55 pm
        // Cron::schedule($subscription_id);

        if (is_array($request->input('tags'))) {
            $user_id = Auth::id();
            $user_tags = TagModel::get_by_user_arr($user_id);
            $tags = [];
            foreach ($request->input('tags') as $val) {
                // Search for exist tag
                if (isset($user_tags[$val])) {
                    $tags[] = [
                        'user_id' => $user_id,
                        'subscription_id' => $subscription_id,
                        'tag_id' => $val,
                    ];
                } else {
                    // Insert the new tag
                    $tag_data = [
                        'user_id' => $user_id,
                        'name' => $val,
                    ];
                    $tag_id = TagModel::create($tag_data);

                    // Insert tag mapping into subscription_tag table
                    $tags[] = [
                        'user_id' => $user_id,
                        'subscription_id' => $subscription_id,
                        'tag_id' => $tag_id,
                    ];
                }
            }

            // Insert all the tags
            if (!empty($tags)) {
                SubscriptionModel::create_tags($tags);
            }
        }

        // Send webhook event
        Webhook::send_event('subscription.created', $subscription_id);

        if ($request->ajax()) {
            return Response::json([
                'status' => true,
                'message' => 'Success',
            ], 200);
        } else {
            return back();
        }
    }

    public function create_quick(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'nullable|boolean',
            'type' => 'required|numeric|digits_between:0,9',
            //folder
              'folder_id' => [
                'nullable',
                'string',
                'max:255',
                function ($attribute, $value, $fail) use ($request) {
                    if ($value > 0) {
                        $folder = Folder::find($value);
                        if (!$folder) {
                            $folder = Folder::where('name', $value)->where('user_id', Auth::id())->first();
                        }
                        if ($folder) {
                            if (!$folder->price_type) {
                                $folder->price_type = 'All';
                                $folder->save();
                            }
                            $price_type = $request->input('price_type');
                            if ($folder->price_type != $price_type && $folder->price_type != 'All') {
                                $fail(__('The currency code ') . $folder->price_type . __(' of folder ') . $folder->name . __(" doesn't match the currency code ") . $price_type . __(' of the subscription.'));
                            }
                        }
                    }
                }
            ],
            'brand_id' => 'required|string|max:255',
            'alert_id' => 'required|integer',
            'description' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'price_type' => 'required|string|max:10',
            'recurring' => 'nullable|boolean',
            'payment_date' => 'required|date_format:Y-m-d',
            'alert_type' => 'nullable|integer|in:1',
            'url' => 'nullable|string|max:255',
            'refund_days' => 'nullable|integer|min:1|max:' . len()->subscriptions->refund_days,
            'billing_frequency' => 'nullable|numeric|digits_between:0,40',
            'billing_cycle' => 'nullable|numeric|digits_between:0,9',
            'billing_type' => 'nullable|integer|in:2',
            'rating' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
            // abort(419);
        }

        // Check limit
        if (UserModel::limit_reached('subscription')) {
            return lib()->do->get_limit_msg($request, 'subscription');
        }

        // Get folder details
        $folder_id = $request->input('folder_id');
        if (is_numeric($folder_id) && intval($folder_id) == $folder_id) {
            $folder_id = $request->input('folder_id');
        } else {
            $folder_id = FolderModel::get_or_create($request->input('folder_id'));
        }

        // Get product information
        $product_data = $this->get_product_for_subscription($request->input('brand_id'), $request->input('type'));

        // Check data
        $product_data_arr = ['brand_id', 'category_id', 'product_name', 'favicon', 'brandname', 'brand_image', 'product_type', 'pricing_type', 'currency_code'];
        foreach ($product_data_arr as $val) {
            if (!isset($product_data[$val])) {
                $product_data[$val] = null;
            }
        }

        $data = [
            'user_id' => Auth::id(),
            'status' => $request->input('status') ? 1 : 0,
            'type' => $request->input('type'),
          //  'folder_id' => lib()->user->default->folder_id,
            'folder_id' => $folder_id,
            'payment_mode_id' => lib()->user->default->payment_mode_id,
            'alert_id' => $request->input('alert_id'),

            'brand_id' => $product_data['brand_id'],
            'category_id' => $product_data['category_id'],
            'product_name' => $product_data['product_name'],
            'image' => $product_data['brand_image'],
            'description' => $request->input('description'),
            'price' => (float) $request->input('price'),
            'price_type' => $request->input('price_type'),
            'payment_date' => $request->input('payment_date'),
            'payment_date_upd' => $request->input('payment_date'),
            'recurring' => $request->input('recurring') ? 1 : 0,
            'alert_type' => $request->input('alert_type'),
            'url' => $request->input('url'),
            'refund_days' => $request->input('refund_days'),
            'billing_frequency' => $request->input('billing_frequency'),
            'billing_cycle' => $request->input('billing_cycle'),
            'billing_type' => $request->input('billing_type') ?? 1,
            'timezone' => lib()->user->get_timezone(),
            'base_value' => lib()->do->currency_convert($request->input('price'), $request->input('price_type')),
            'base_currency' => APP_CURRENCY,
            'rating' => $request->input('rating'),

            'ltdval_price' => $product_data['ltdval_price'],
            'ltdval_cycle' => $product_data['ltdval_cycle'],
            'ltdval_frequency' => $product_data['ltdval_frequency'],
        ];

        // Check for empty data
        if (empty($data['billing_frequency'])) {
            $data['billing_frequency'] = 1;
        }
        if (empty($data['billing_cycle'])) {
            $data['billing_cycle'] = 1;
        }

        // Set product default data
        if (!empty($product_data)) {
            $product_defaults = [
                'favicon',
                'brandname',
                'product_type',
                'pricing_type',
                'currency_code',
            ];

            foreach ($product_data as $key => $val) {
                if (in_array($key, $product_defaults)) {
                    $data[$key] = $val;
                }
            }

            // unset($data['brand_image']);
        }

        if (!empty($request->input('refund_days'))) {
            $data['refund_date'] = date('Y-m-d', strtotime($request->input('payment_date') . ' +' . $request->input('refund_days') . ' days'));
        }
        $subscription_id = SubscriptionModel::create($data);

        // Add event logs
        $this->add_event([
            'table_row_id' => $subscription_id,
            'event_type_status' => 'create_quick',
            'event_product_id' => $product_data['brand_id'],
            'event_type_schedule' => $request->input('recurring') ? 2 : 1,
        ]);

        // Check if active
        if ($request->input('status') == 1) {

            // Add events
            SubscriptionModel::set_refund_date($subscription_id);

            // Add history
            SubscriptionModel::create_new_history($subscription_id);
        }

        // Send webhook event
        Webhook::send_event('subscription.created', $subscription_id);

        // Add next schedule date
        // Note: Disabled for performance issue as discussed on 2021.12.10 at 9.55 pm
        // Cron::schedule($subscription_id);

        if ($request->ajax()) {
            return Response::json([
                'status' => true,
                'message' => 'Success',
            ], 200);
        } else {
            return back();
        }
    }

    public function get(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => [
                'required',
                'integer',
                Rule::exists('subscriptions', 'id')->where(function ($query) {
                    $query->where('user_id', Auth::id());
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

        return Response::json(SubscriptionModel::get($request->input('id')));
    }

    public function edit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => [
                'required',
                'integer',
                Rule::exists('subscriptions', 'id')->where(function ($query) {
                    $query->where('user_id', Auth::id());
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
        // return Response::json(SubscriptionModel::get($request->input('id')));
        // $subscription_data = SubscriptionModel::get($request->input('id'));

        $subscription_data = Subscription::where('subscriptions.id', $request->input('id'))
            ->leftJoin('subscriptions_attachments', 'subscriptions.id', '=', 'subscriptions_attachments.subscription_id')
            ->where('subscriptions.user_id', Auth::id())
            ->select(
                'subscriptions.*',
                DB::raw('count(subscriptions_attachments.subscription_id) as attachment_count'),
            )
            ->groupBy('subscriptions.id')
            ->first();

        if (empty($subscription_data->id)) {
            return lib()->do->json_response(false, __('Subscription not found.'), 403);
        }

        // Check cancel status
        if ($subscription_data->status == 2) {
            return lib()->do->json_response(false, __('Canceled subscription cannot be updated.'), 410);
        }

        // Check refund status
        else if ($subscription_data->status == 3) {
            return lib()->do->json_response(false, __('Refunded subscription cannot be updated.'), 410);
        }

        // Check expired status
        else if ($subscription_data->status == 4) {
            return lib()->do->json_response(false, __('Expired subscription cannot be updated.'), 410);
        }

        $data = [
            'slug' => 'subscription',
            // 'folder' => FolderModel::get_by_user(),
            'data' => $subscription_data,
            'product' => ProductModel::get($subscription_data->brand_id),
            'data_tags' => SubscriptionModel::get_tags_arr($request->input('id')),
        ];

        return view('client/subscription/edit', $data);
    }

    public function delete(Request $request)
    {
        $validator = Validator::make(request()->all(), [
            'id' => [
                'required',
                'integer',
                Rule::exists('subscriptions', 'id')->where(function ($query) {
                    $query->where('user_id', Auth::id());
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

        $subscription_id = $request->input('id');
        $subscription = SubscriptionModel::get($subscription_id);

        if (!empty($subscription)) {

            // Add event logs
            $this->add_event([
                'table_row_id' => $request->input('id'),
                'event_type_status' => 'delete',
                'event_product_id' => $subscription->brand_id,
                'event_type_schedule' => $subscription->recurring,
            ]);

            // Update event logs
            NotificationEngine::set_del_status([
                'event_types' => ['email', 'browser'],
                'subscription_id' => $subscription_id,
            ]);

            // Add push notification in queue for sending
            Subscription::queue_push_notification($subscription, 'subscription_delete');

            // Send webhook event
            Webhook::send_event('subscription.deleted', $subscription->id);

            // Delete all addon
            $this->delete_addon($subscription->id);

            // Delete subscription marketplace
            Marketplace::where('subscription_id', $subscription->id)->delete();

            // Delete all attachments and its directory
            SubscriptionAttachment::deleteBySubscription($subscription->id);
        }

        return Response::json([
            'status' => SubscriptionModel::del($request->input('id')),
            'message' => 'Success',
        ], 200);
    }

    private function delete_addon(int $subscription_id)
    {
        $subscription_child_all = Subscription::where('sub_id', $subscription_id)
            ->where('user_id', Auth::id())
            ->get();

        if (count($subscription_child_all) > 0) {
            foreach ($subscription_child_all as $subscription_child) {

                // Add event logs
                $this->add_event([
                    'table_row_id' => $subscription_child->id,
                    'event_type_status' => 'delete',
                    'event_product_id' => $subscription_child->brand_id,
                    'event_type_schedule' => $subscription_child->recurring,
                ]);

                // Update event logs
                NotificationEngine::set_del_status([
                    'event_types' => ['email', 'browser'],
                    'subscription_id' => $subscription_child->id,
                ]);

                // Add push notification in queue for sending
                Subscription::queue_push_notification($subscription_child, 'subscription_delete');

                // Send webhook event
                Webhook::send_event('subscription.deleted', $subscription_child->id);

                SubscriptionModel::del($subscription_child->id);
            }
        }
    }

    public function cancel(Request $request)
    {
        $validator = Validator::make(request()->all(), [
            'id' => [
                'required',
                'integer',
                Rule::exists('subscriptions', 'id')->where(function ($query) {
                    $query->where('user_id', Auth::id());
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

        $subscription_id = $request->input('id');
        $subscription = SubscriptionModel::get($subscription_id);

        if (!empty($subscription)) {

            // Add event logs
            // $this->add_event([
            //     'table_row_id' => $request->input('id'),
            //     'event_type_status' => 'delete',
            //     'event_product_id' => $subscription->brand_id,
            //     'event_type_schedule' => $subscription->recurring,
            // ]);

            // Update event logs
            NotificationEngine::set_cancel_status([
                'event_types' => ['event', 'email', 'browser'],
                'subscription_id' => $subscription_id,
            ]);

            // Add push notification in queue for sending
            Subscription::queue_push_notification($subscription, 'subscription_cancel');

            // Send webhook event
            Webhook::send_event('subscription.canceled', $subscription->id);

            // Delete subscription marketplace
            Marketplace::where('subscription_id', $subscription->id)->delete();

            // Cancel all addon
            $this->cancel_addon($subscription->id);
        }

        return Response::json([
            'status' => SubscriptionModel::cancel($subscription_id),
            'message' => 'Success',
        ], 200);
    }

    public function cancel_addon(int $subscription_id)
    {
        $subscription_child_all = Subscription::where('sub_id', $subscription_id)
            ->where('user_id', Auth::id())
            ->get();

        if (count($subscription_child_all) > 0) {
            foreach ($subscription_child_all as $subscription_child) {

                // Add event logs
                // $this->add_event([
                //     'table_row_id' => $request->input('id'),
                //     'event_type_status' => 'delete',
                //     'event_product_id' => $subscription->brand_id,
                //     'event_type_schedule' => $subscription->recurring,
                // ]);

                // Update event logs
                NotificationEngine::set_cancel_status([
                    'event_types' => ['event', 'email', 'browser'],
                    'subscription_id' => $subscription_child->id,
                ]);

                // Add push notification in queue for sending
                Subscription::queue_push_notification($subscription_child, 'subscription_cancel');

                // Send webhook event
                Webhook::send_event('subscription.canceled', $subscription_child->id);

                SubscriptionModel::cancel($subscription_child->id);
            }
        }
    }

    public function refund(Request $request)
    {
        $validator = Validator::make(request()->all(), [
            'id' => [
                'required',
                'integer',
                Rule::exists('subscriptions', 'id')->where(function ($query) {
                    $query->where('user_id', Auth::id());
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

        $subscription_id = $request->input('id');
        $subscription = SubscriptionModel::get($subscription_id);

        if (!empty($subscription)) {

            // Add event logs
            $this->add_event([
                'table_row_id' => $request->input('id'),
                'event_type_status' => 'delete',
                'event_product_id' => $subscription->brand_id,
                'event_type_schedule' => $subscription->recurring,
            ]);

            // Update event logs
            NotificationEngine::set_del_status([
                'event_types' => ['email', 'browser'],
                'subscription_id' => $subscription_id,
            ]);

            // Add push notification in queue for sending
            // Subscription::queue_push_notification($subscription, 'subscription_refund');

            // Add Extension notification and Push notification in queue (events table) for sending
            NotificationEngine::add_event_for_subscription_extension_push([
                'table_row_id' => $request->input('id'),
                'event_type_status' => 'refund',
                'event_product_id' => $subscription->brand_id,
                'event_type_schedule' => $subscription->recurring,
                'event_types' => ['extension', 'browser'],
            ]);

            // Send webhook event
            Webhook::send_event('subscription.refunded', $subscription->id);

            // Refund all addon
            $this->refund_addon($subscription->id);
        }

        return Response::json([
            'status' => SubscriptionModel::refund($subscription_id),
            'message' => 'Success',
        ], 200);
    }

    public function refund_addon(int $subscription_id)
    {
        $subscription_child_all = Subscription::where('sub_id', $subscription_id)
            ->where('user_id', Auth::id())
            ->get();

        if (count($subscription_child_all) > 0) {
            foreach ($subscription_child_all as $subscription_child) {

                // Add event logs
                $this->add_event([
                    'table_row_id' => $subscription_child->id,
                    'event_type_status' => 'delete',
                    'event_product_id' => $subscription_child->brand_id,
                    'event_type_schedule' => $subscription_child->recurring,
                ]);

                // Update event logs
                NotificationEngine::set_del_status([
                    'event_types' => ['email', 'browser'],
                    'subscription_id' => $subscription_child->id,
                ]);

                // Add push notification in queue for sending
                // Subscription::queue_push_notification($subscription, 'subscription_refund');

                // Add Extension notification and Push notification in queue (events table) for sending
                NotificationEngine::add_event_for_subscription_extension_push([
                    'table_row_id' => $subscription_child->id,
                    'event_type_status' => 'refund',
                    'event_product_id' => $subscription_child->brand_id,
                    'event_type_schedule' => $subscription_child->recurring,
                    'event_types' => ['extension', 'browser'],
                ]);

                // Send webhook event
                Webhook::send_event('subscription.refunded', $subscription_child->id);

                SubscriptionModel::refund($subscription_child->id);
            }
        }
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => [
                'required',
                'integer',
                Rule::exists('subscriptions', 'id')->where(function ($query) {
                    $query->where('user_id', Auth::id());
                }),
            ],
            'category_id' => 'nullable|integer',
            // 'status' => 'required|numeric|digits_between:0,1',
            'type' => 'nullable|numeric|digits_between:0,9',
            'folder_id' => [
                'nullable',
                'string',
                'max:255',
                function ($attribute, $value, $fail) use ($request) {
                    if ($value > 0) {
                        $folder = Folder::find($value);
                        if (!$folder) {
                            $folder = Folder::where('name', $value)->where('user_id', Auth::id())->first();
                        }
                        if ($folder) {
                            $subscription = Subscription::select('price_type')->find($request->input('id'));
                            if (!$subscription) {
                                return;
                            }
                            $price_type = $subscription->price_type;
                            if ($folder->price_type != $price_type && $folder->price_type != 'All') {
                                $fail(__('The currency code ') . $folder->price_type . __(' of folder ') . $folder->name . __(" doesn't match the currency code ") . $price_type . __(' of the subscription.'));
                            }
                        }
                    }
                }
            ],
            // 'tags' => 'required|numeric',
            // 'client_name' => 'required|string|max:255',
            'alert_id' => 'required|integer',
            'brand_id' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'price_type' => 'nullable|string|max:10',
            'discount_voucher' => 'nullable|string|max:20',
            // 'payment_mode' => 'required|string|max:' . len()->subscriptions->payment_mode,
            'expiry_date' => 'nullable|date_format:Y-m-d|after:today|after:payment_date|prohibited_if:type,3|' . $this->get_expiry_date_rule($request),
            'payment_mode_id' => 'required|integer',
            'payment_date' => 'nullable|date_format:Y-m-d',
            'next_payment_date' => 'nullable|date_format:Y-m-d',
            'recurring' => 'nullable|boolean',
            'note' => 'nullable|string|max:255',
            'include_notes' => 'nullable|boolean',
            'alert_type' => 'nullable|integer|in:1',
            'url' => 'nullable|string|max:255',
            'support_details' => 'nullable|string|max:255',
            'tags' => 'nullable|array|max:255',
            // 'refund_days' => 'nullable|integer|min:0|max:' . len()->subscriptions->refund_days,
            'refund_date' => 'nullable|date_format:Y-m-d',
            'billing_frequency' => 'nullable|numeric|digits_between:0,40',
            'billing_cycle' => 'nullable|numeric|digits_between:0,9',
            'billing_type' => 'nullable|integer|in:2',
            'img_path_or_file' => 'required|integer|digits_between:0,1',
            'image' => 'sometimes|nullable|image',
            'rating' => 'nullable|integer',

            'ltdval_price' => 'nullable|numeric|min:0',
            'ltdval_cycle' => 'nullable|numeric|digits_between:0,9',
            'ltdval_frequency' => 'nullable|numeric|digits_between:0,40',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
            // abort(419);
        }

        $subscription_id = $request->input('id');

        $next_payment_date = $request->input('next_payment_date');
        $next_payment_date_status = false;

        // Check tag limit
        if ($this->is_tag_limit_reached($request)) {
            return lib()->do->get_limit_msg($request, 'tag');
        }

        // Get subscription
        $subscription = SubscriptionModel::get($subscription_id);

        if (empty($subscription)) {
            return Response::json([
                'status' => false,
                'message' => __('Subscription not found'),
            ], 200);
        }

        // Check if draft
        if ($subscription->status == 0) {
            $validator = Validator::make($request->all(), [
                'status' => 'nullable|boolean',
                'payment_date' => 'required|date_format:Y-m-d',
            ]);

            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'message' => $validator->errors(),
                ]);
            }
        }

        // Get product information
        $product_data = $this->get_product_for_subscription($request->input('brand_id'), $request->input('type'));

        $data = [
            'user_id' => Auth::id(),
            'folder_id' => $request->input('folder_id'),
            'alert_id' => $request->input('alert_id'),
            'description' => $request->input('description'),
            'price' => (float) $request->input('price'),
            // 'price_type' => $request->input('price_type'),
            'discount_voucher' => $request->input('discount_voucher'),
            // 'payment_mode' => $request->input('payment_mode'),
            'expiry_date' => $request->input('expiry_date'),
            'payment_mode_id' => $request->input('payment_mode_id'),
            'recurring' => $request->input('recurring') ? 1 : 0,
            'note' => $request->input('note'),
            'include_notes' => $request->input('include_notes'),
            'alert_type' => $request->input('alert_type'),
            'url' => $request->input('url'),
            'support_details' => $request->input('support_details'),
            // 'tags' => $request->input('tags'),
            // 'refund_days' => $request->input('refund_days'),
            'refund_date' => $request->input('refund_date'),
            'billing_frequency' => $request->input('billing_frequency'),
            'billing_cycle' => $request->input('billing_cycle'),
            'billing_type' => $request->input('billing_type') ?? 1,
            'base_value' => lib()->do->currency_convert($request->input('price'), $request->input('price_type')),
            'base_currency' => APP_CURRENCY,

            // Reset upcoming date to calculate again
            // 'next_payment_date' => null,

            'rating' => $request->input('rating'),

            'ltdval_price' => (float) $request->input('ltdval_price'),
            'ltdval_cycle' => $request->input('ltdval_cycle'),
            'ltdval_frequency' => $request->input('ltdval_frequency'),
        ];

        // Check for empty data
        if (empty($data['billing_frequency'])) {
            $data['billing_frequency'] = 1;
        }
        if (empty($data['billing_cycle'])) {
            $data['billing_cycle'] = 1;
        }

        // Check if draft
        if ($subscription->status == 0) {
            $data['status'] = $request->input('status') ? 1 : 0;
            $data['payment_date'] = $request->input('payment_date');
            $data['payment_date_upd'] = $request->input('payment_date');
            $data['price_type'] = $request->input('price_type');
            $data['brand_id'] = $request->input('brand_id');

            // Set product default data
            if (!empty($product_data)) {
                $product_defaults = [
                    'favicon',
                    'product_name',
                    'brandname',
                    'product_type',
                    'pricing_type',
                    'currency_code',
                ];

                foreach ($product_data as $key => $val) {
                    if (in_array($key, $product_defaults)) {
                        $data[$key] = $val;
                    }
                }
                // unset($data['brand_image']);
            }
        }

        // Check if active
        if ($subscription->status == 1) {

            // billing match
            if (
                $subscription->billing_frequency != $request->input('billing_frequency') ||
                $subscription->billing_cycle != $request->input('billing_cycle') ||
                $subscription->billing_type != $request->input('billing_type')
            ) {
                $data['next_payment_date'] = null;
            }

            // next_payment_date match
            if ($subscription->type == 1 && !empty($next_payment_date) && $subscription->next_payment_date != $next_payment_date) {
                $next_payment_date_status = true;
                $data['next_payment_date'] = $next_payment_date;
            }

            // Lifetime
            if ($subscription->type == 3 && !empty($request->input('payment_date'))) {
                $data['payment_date'] = $request->input('payment_date');
                $data['next_payment_date'] = $next_payment_date;
                $data['payment_date_upd'] = $next_payment_date;
            }
        }

        if (!empty($request->input('type'))) {
            $data['type'] = $request->input('type');
        }

        if ($request->input('img_path_or_file') == 0 && !empty($request->input('image_path'))) {
            $data['image'] = $request->input('image_path');
        }

        $data['category_id'] = $request->input('category_id');

        $status = SubscriptionModel::do_update($subscription_id, $data);

        // Check if new data updated
        if (!empty($status)) {

            $addon_data_to_update = [
                'brand_id',
                'description',
                'category_id',
                'favicon',
                'product_name',
                'brandname',
                'product_type',
                'pricing_type',
                'currency_code',
            ];
            $addon_data = [];

            foreach ($addon_data_to_update as $key => $val) {
                if (isset($data[$val])) {
                    $addon_data[$val] = $data[$val];
                }
            }

            // Update child addons
            Subscription::where('sub_id', $subscription_id)
                ->where('user_id', Auth::id())
                ->update($addon_data);
        }

        // Check if active
        if ($subscription->status == 1) {
            if ($subscription->type == 1 && !empty($next_payment_date) && $subscription->next_payment_date != $next_payment_date) {
                $next_payment_date_status = true;
                NotificationEngine::set_next_payment_date([
                    'subscription_id' => $subscription->id,
                    'next_payment_date' => $next_payment_date,
                    'event_types' => ['event', 'email', 'browser'],
                ]);
                $data['next_payment_date'] = $next_payment_date;
            }
        }

        if (!$next_payment_date_status && !empty($subscription)) {
            // Get history
            $history = SubscriptionModel::get_last_history($subscription->id, $subscription->type);

            // Delete history
            if (!empty($history)) {
                if ($subscription->type != 3) {
                    if ($subscription->payment_date < date('Y-m-d')) {
                        SubscriptionHistoryModel::del($history->id);
                    }
                }
            }
        }

        // Add event logs
        $this->add_event([
            'table_row_id' => $subscription_id,
            'event_type_status' => 'update',
            'event_product_id' => $product_data['brand_id'],
            'event_type_schedule' => $request->input('recurring') ? 2 : 1,
        ]);

        // Save image
        $image_path = '';
        // Check if user selected an image
        if ($request->input('img_path_or_file') == 1 && $request->hasFile('image')) {
            $image_path = File::add_get_path($request->file('image'), 'subscription', $subscription_id);
            SubscriptionModel::do_update($subscription_id, [
                'image' => $image_path,
            ]);
        }

        // Check new status
        if ($subscription->status == 0 && $request->input('status') == 1) {

            // Add history
            SubscriptionModel::create_new_history($subscription_id);

            // Add events
            SubscriptionModel::set_refund_date($subscription_id);
        }

        // Add next schedule date and skip adding data in history table
        // Note: Disabled for performance issue as discussed on 2021.12.10 at 9.55 pm
        // Cron::schedule($subscription_id, false);

        // if (!$status) {
        //     return Response::json([
        //         'status' => false,
        //         'message' => __('An error occurred'),
        //     ]);
        // }

        // Delete tags map
        SubscriptionModel::delete_tags($subscription_id);

        // Create tags map
        if (is_array($request->input('tags'))) {
            $user_id = Auth::id();
            $user_tags = TagModel::get_by_user_arr($user_id);
            $tags = [];
            foreach ($request->input('tags') as $val) {
                // Search for exist tag
                if (isset($user_tags[$val])) {
                    $tags[] = [
                        'user_id' => $user_id,
                        'subscription_id' => $subscription_id,
                        'tag_id' => $val,
                    ];
                } else {
                    // Insert the new tag
                    $tag_data = [
                        'user_id' => $user_id,
                        'name' => $val,
                    ];
                    $tag_id = TagModel::create($tag_data);

                    // Insert tag mapping into subscription_tag table
                    $tags[] = [
                        'user_id' => $user_id,
                        'subscription_id' => $subscription_id,
                        'tag_id' => $tag_id,
                    ];
                }
            }

            // Insert all the tags
            if (!empty($tags)) {
                SubscriptionModel::create_tags($tags);
            }
        }

        // Send webhook event
        Webhook::send_event('subscription.updated', $subscription_id);

        if ($request->ajax()) {
            return Response::json([
                'status' => true,
                'message' => 'Success',
            ], 200);
        } else {
            return back();
        }
    }

    function clone(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => [
                'required',
                'integer',
                Rule::exists('subscriptions', 'id')->where(function ($query) {
                    $query->where('user_id', Auth::id());
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
        // return Response::json(SubscriptionModel::get($request->input('id')));

        $subscription_data = Subscription::find($request->input('id'));
        if (empty($subscription_data)) {
            return response()->back();
        }

        // Check cancel status
        if ($subscription_data->status == 2) {
            $subscription_data->status = 0;
        }

        $data = [
            'slug' => 'subscription',
            // 'folder' => FolderModel::get_by_user(),
            'data' => $subscription_data,
            'product' => ProductModel::get($subscription_data->brand_id),
            'data_tags' => SubscriptionModel::get_tags_arr($request->input('id')),
        ];

        return view('client/subscription/clone', $data);
    }

    public function addon(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => [
                'required',
                'integer',
                Rule::exists('subscriptions', 'id')->where(function ($query) {
                    $query->where('user_id', Auth::id());
                    $query->where('status', 1);
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
        // return Response::json(SubscriptionModel::get($request->input('id')));

        $subscription_data = Subscription::find($request->input('id'));
        if (empty($subscription_data->id)) {
            return response()->back();
        }

        $data = [
            'slug' => 'subscription',
            // 'folder' => FolderModel::get_by_user(),
            'data' => $subscription_data,
            'product' => ProductModel::get($subscription_data->brand_id),
            'data_tags' => SubscriptionModel::get_tags_arr($request->input('id')),
        ];

        return view('client/subscription/addon', $data);
    }

    public function get_by_folder($folder_id)
    {
        $user_id = Auth::id();

        $subs_chart_sql = SubscriptionModel::get_subs_chart_by_folder($folder_id, $user_id);
        $ltd_chart_sql = SubscriptionModel::get_ltd_chart_by_folder($folder_id, $user_id);

        $subs_chart_json = $ltd_chart_json = [
            'dates' => [],
            'prices' => [],
        ];

        if (!empty($subs_chart_sql)) {
            foreach ($subs_chart_sql as $val) {
                $subs_chart_json['dates'][] = date('M', strtotime($val->payment_date));
                $subs_chart_json['prices'][] = (float) $val->base_value;
            }
        }

        if (!empty($ltd_chart_sql)) {
            foreach ($ltd_chart_sql as $val) {
                $ltd_chart_json['dates'][] = date('M', strtotime($val->payment_date));
                $ltd_chart_json['prices'][] = (float) $val->base_value;
            }
        }

        $subscription = new \stdClass();
        $lifetime = new \stdClass();

        // Get active subscriptions
        $subscription->total_items = DB::table('subscriptions')
            ->where('type', 1)
            ->where('status', 1)
            ->where('user_id', $user_id)
            ->where('folder_id', $folder_id)
            ->get()
            ->count();

        // Get active lifetime subscriptions
        $lifetime->total_items = DB::table('subscriptions')
            ->where('type', 3)
            ->where('status', 1)
            ->where('user_id', $user_id)
            ->where('folder_id', $folder_id)
            ->get()
            ->count();

        // Get total price of subscriptions
        $subscription->total_price = DB::table('subscriptions')
            ->where('user_id', $user_id)
            ->where('folder_id', $folder_id)
            ->where('type', 1)
            ->get()
            ->sum('base_value');

        // Get total price of lifetime subscriptions
        $lifetime->total_price = DB::table('subscriptions')
            ->where('user_id', $user_id)
            ->where('folder_id', $folder_id)
            ->where('type', 3)
            ->get()
            ->sum('base_value');

        $sub_total_months = 1;
        $ltd_total_months = 1;

        if (!empty($subs_chart_json['dates'])) {
            if (count($subs_chart_json['dates']) > 12) {
                $sub_total_months = 12;
            } else {
                $sub_total_months = count($subs_chart_json['dates']);
            }
        }

        if (!empty($ltd_chart_json['dates'])) {
            if (count($ltd_chart_json['dates']) > 12) {
                $ltd_total_months = 12;
            } else {
                $ltd_total_months = count($ltd_chart_json['dates']);
            }
        }

        if ($subscription->total_price > 0) {
            $subscription->total_monthly_price = $subscription->total_price / $sub_total_months;
        } else {
            $subscription->total_monthly_price = 0;
        }

        if ($lifetime->total_price > 0) {
            $lifetime->total_monthly_price = $lifetime->total_price / $ltd_total_months;
        } else {
            $lifetime->total_monthly_price = 0;
        }

        $data = [
            'slug' => 'subscription',
            'folder_id' => $folder_id,
            'subs_chart' => $subs_chart_json,
            'ltd_chart' => $ltd_chart_json,

            'subscription' => $subscription,
            'lifetime' => $lifetime,
        ];

        return view('client/subscription/index', $data);
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

    private function get_product_id_by_sub_type($type_id)
    {
        switch ($type_id) {
            case 1:
                return 1;
                break;

            case 2:
                return 2;
                break;

            case 3:
                return 3;
                break;

            case 4:
                return 4;
                break;

            default:
                return 0;
        }
    }

    private function is_tag_limit_reached(Request $request)
    {
        if (is_array($request->input('tags'))) {
            $user_id = Auth::id();
            $user_tags = TagModel::get_by_user_arr($user_id);
            foreach ($request->input('tags') as $val) {
                // Search for exist tag
                if (isset($user_tags[$val])) {
                } else {
                    if (UserModel::limit_reached('tag')) {
                        return true;
                    } else {
                        return false;
                    }
                }
            }
        }

        return false;
    }

    private function get_product_for_subscription($brand_id, $type)
    {
        $product_data = [
            'brand_id' => 0,
            'category_id' => 0,
            'product_name' => null,
            'favicon' => null,
            'brandname' => null,
            'brand_image' => null,
            'product_type' => null,
            'pricing_type' => null,
            'currency_code' => null,
            // 'refund_days' => null,
            'ltdval_price' => null,
            'ltdval_cycle' => null,
            'ltdval_frequency' => null,
        ];

        if (is_numeric($brand_id) && intval($brand_id) == $brand_id && $brand_id > 10) {
            $product = ProductModel::get($brand_id);

            // Check active product
            if (!empty($product->id) && $product->status == 1) {
                $product_data['category_id'] = $product->category_id;
                $product_data['brand_id'] = $product->id;
                $product_data['product_name'] = $product->product_name;
                $product_data['favicon'] = $product->favicon;
                $product_data['brandname'] = $product->brandname;
                $product_data['brand_image'] = $product->image;
                $product_data['product_type'] = $product->product_type;
                $product_data['pricing_type'] = $product->pricing_type;
                $product_data['currency_code'] = $product->currency_code;
                // $product_data['refund_days'] = $product->refund_days;
                $product_data['ltdval_price'] = $product->ltdval_price;
                $product_data['ltdval_cycle'] = $product->ltdval_cycle;
                $product_data['ltdval_frequency'] = $product->ltdval_frequency;

                return $product_data;
            }
        }

        $product_data['brand_id'] = $this->get_product_id_by_sub_type($type);
        $product_data['product_name'] = $brand_id;

        return $product_data;
    }

    public function get_chart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|integer|in:1,3',
            'days' => 'required|integer|in:730,365,180,90,30',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
        }

        // Subscription
        if ($request->input('type') == 1) {
            Session::put('subscription_days', $request->input('days'));
        }

        // Lifetime
        else if ($request->input('type') == 3) {
            Session::put('lifetime_days', $request->input('days'));
        }

        $user_id = Auth::id();

        $subs_chart_sql = DB::table('subscriptions')
            ->where('subscriptions.user_id', Auth::id())
            ->where('payment_date', '>=', date('Y-m-d', strtotime("-{$request->input('days')} days")))
            ->where('type', 1)
            ->select('subscriptions.*')
            ->orderBy('subscriptions.payment_date')
            ->get();

        $ltd_chart_sql = DB::table('subscriptions')
            ->where('subscriptions.user_id', Auth::id())
            ->where('payment_date', '>=', date('Y-m-d', strtotime("-{$request->input('days')} days")))
            ->where('type', 3)
            ->select('subscriptions.*')
            ->orderBy('subscriptions.payment_date')
            ->get();

        $subs_chart_json = $ltd_chart_json = [
            'dates' => [],
            'prices' => [],
        ];

        if (!empty($subs_chart_sql)) {
            foreach ($subs_chart_sql as $val) {
                $subs_chart_json['dates'][] = date('M', strtotime($val->date));
                $subs_chart_json['prices'][] = (float) $val->base_value;
                // $subs_chart_json['product_name'][] = $val->product_name;
            }
        }

        if (!empty($ltd_chart_sql)) {
            foreach ($ltd_chart_sql as $val) {
                $ltd_chart_json['dates'][] = date('M', strtotime($val->payment_date));
                $ltd_chart_json['prices'][] = (float) $val->base_value;
                // $subs_chart_json['product_name'][] = $val->product_name;
            }
        }

        $data = [
            'subs_chart' => $subs_chart_json,
            'ltd_chart' => $ltd_chart_json,
        ];

        return Response::json([
            'status' => true,
            'message' => 'Success',
            'data' => $data,
        ], 200);
    }

    public function set_datatable_page_length(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'length' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
        }

        // Set session
        $_SESSION['subscription_datatable_page_length'] = $request->input('length');

        return Response::json([
            'status' => true,
            'message' => 'Success',
        ], 200);
    }

    public function koolreport_both_charts(Request $request)
    {
        $output = [
            'subscription' => [
                'chart_html' => '',
                'total_count' => 0,
                'active_count' => 0,
                'monthly_price' => 0,
                'total_price' => 0,
            ],
            'lifetime' => [
                'chart_html' => '',
                'total_count' => 0,
                'active_count' => 0,
                'this_year_price' => 0,
                'total_price' => 0,
            ],
        ];

        // AreaChart
        lib()->cache->subscription_area_chart = SubscriptionModel::get_subscription_area_chart(local('subscription_days', 0));

        $output['subscription']['chart_html'] = view('client/koolreport/subscription/area_chart')->render();
        $output['subscription']['total_count'] = local('subscription_total_count', 0);
        $output['subscription']['active_count'] = local('subscription_total_count', 0);
        $output['subscription']['monthly_price'] = round(local('subscription_monthly_price', 0), 2);
        $output['subscription']['total_price'] = round(local('subscription_total_price', 0), 2);

        // Drilldown
        // $_SESSION['dashboard_lifetime_kr_period'] = local('dashboard_lifetime_kr_period', 'all_time');
        // $_SESSION['dashboard_lifetime_kr_period'] = 'all_time';
        if (local('subscription_folder_id', 0) > 0) {
            $ltd_subscriptions_filter = [
                'folder_ids' => [local('subscription_folder_id')]
            ];
        } else {
            $ltd_subscriptions_filter = [];
        }
        SubscriptionModel::koolreport_lifetime_drilldown_chart_all_time($ltd_subscriptions_filter);

        $output['lifetime']['chart_html'] = view('client/subscription/koolreport/drilldown_chart')->render();
        $output['lifetime']['total_count'] = lib()->cache->dashboard_kr_lifetime_summary_total_count;
        $output['lifetime']['active_count'] = lib()->cache->dashboard_kr_lifetime_summary_total_count;
        $output['lifetime']['this_year_price'] = round(lib()->cache->dashboard_kr_lifetime_this_year_price, 2);
        $output['lifetime']['total_price'] = round(lib()->cache->dashboard_kr_lifetime_total_price, 2);

        return $output;
    }

    public function koolreport_area_chart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|integer|between:1,' . len()->subscriptions->type,
            'days' => 'required|integer|between:0,730',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
        }

        // AreaChart
        $output = [
            'subscription' => [
                'chart_html' => '',
                'total_count' => 0,
                'active_count' => 0,
                'monthly_price' => 0,
                'total_price' => 0,
            ],
        ];

        // AreaChart
        $_SESSION['subscription_days'] = $request->input('days');
        lib()->cache->subscription_area_chart = SubscriptionModel::get_subscription_area_chart(local('subscription_days', 0));

        $output['subscription']['chart_html'] = view('client/koolreport/subscription/area_chart')->render();
        $output['subscription']['total_count'] = local('subscription_total_count', 0);
        $output['subscription']['active_count'] = local('subscription_total_count', 0);
        $output['subscription']['monthly_price'] = round(local('subscription_monthly_price', 0), 2);
        $output['subscription']['total_price'] = round(local('subscription_total_price', 0), 2);

        return $output;
    }

    public function koolreport_lifetime_drilldown_chart_inside(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'period' => 'nullable|string',
            'folder_ids.*' => 'nullable|integer',
            'tag_ids.*' => 'nullable|integer',
            'payment_method_ids.*' => 'nullable|integer',
            'level' => 'required|string',
            'year' => 'nullable|digits:4|integer',
            'month_name' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()], 422);
        }

        // $_SESSION['report_period'] = $request->input('period');
        $_SESSION['dashboard_lifetime_kr_period'] = $request->input('period');

        // dd(ReportModel::get_subscription_mrp_area_chart());

        $data = [
            'folder_ids' => $request->input('folder_ids'),
            'tag_ids' => $request->input('tag_ids'),
            'payment_method_ids' => $request->input('payment_method_ids'),
            'level' => $request->input('level'),
            'year' => $request->input('year'),
            'month_name' => $request->input('month_name'),
            'period' => $request->input('period'),
        ];

        SubscriptionModel::koolreport_lifetime_drilldown_chart_inside($data);

        return response()->json([
            'html' => view('client/subscription/koolreport/drilldown_chart')->render(),
        ]);
    }

    public function koolreport_lifetime_drilldown_chart_all_time(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|integer|between:1,' . len()->subscriptions->type,
            'days' => 'required|string',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
        }

        // Drilldown
        $output = [
            'lifetime' => [
                'chart_html' => '',
                'total_count' => 0,
                'active_count' => 0,
                'this_year_price' => 0,
                'total_price' => 0,
            ],
        ];

        // $_SESSION['lifetime_days'] = $request->input('days');

        // $_SESSION['report_period'] = 'all_time';
        $_SESSION['dashboard_lifetime_kr_period'] = $request->input('days');

        SubscriptionModel::koolreport_lifetime_drilldown_chart_all_time([
            'days' => $request->input('days'),
        ]);

        $output['lifetime']['chart_html'] = view('client/subscription/koolreport/drilldown_chart')->render();
        $output['lifetime']['total_count'] = lib()->cache->dashboard_kr_lifetime_summary_total_count;
        $output['lifetime']['active_count'] = lib()->cache->dashboard_kr_lifetime_summary_total_count;
        $output['lifetime']['this_year_price'] = round(lib()->cache->dashboard_kr_lifetime_this_year_price, 2);
        $output['lifetime']['total_price'] = round(lib()->cache->dashboard_kr_lifetime_total_price, 2);

        return $output;
    }

    public function attachment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => [
                'required',
                'integer',
                Rule::exists('subscriptions', 'id')->where(function ($query) {
                    $query->where('user_id', Auth::id());
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

        $attachments = SubscriptionAttachment::where('user_id', Auth::id())
            ->where('subscription_id', $request->input('id'))
            ->get();

        if (empty($attachments)) {
            return response()->back();
        }

        $data = [
            'slug' => 'subscription',
            'data' => $attachments,
        ];

        return view('client/subscription/attachment', $data);
    }

    public function attachment_upload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => [
                'required',
                'integer',
                Rule::exists('subscriptions', 'id')->where(function ($query) {
                    $query->where('user_id', Auth::id());
                    $query->where('status', 1);
                }),
            ],
            'file' => 'required|file',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
        }

        // Check limit
        if (UserModel::limit_reached('storage')) {
            return lib()->do->get_limit_msg($request, 'storage');
        }

        // Save file
        $file = $request->file('file');
        $file_path = File::add_get_path($file, 'attachment', $request->input('id'));

        $subscription_attachment = new SubscriptionAttachment();
        $subscription_attachment->user_id = Auth::id();
        $subscription_attachment->subscription_id = $request->input('id');
        $subscription_attachment->file_path = $file_path;
        $subscription_attachment->file_name = $file->getClientOriginalName();
        $subscription_attachment->file_size = $file->getSize();
        $subscription_attachment->file_type = $file->getMimeType();
        $subscription_attachment->save();


        if ($request->ajax()) {
            $subscription_attachment->file_size = lib()->do->get_filesize($subscription_attachment->file_size);
            $subscription_attachment->file_url = img_url($subscription_attachment->file_path);

            return Response::json([
                'status' => true,
                'message' => 'Success',
                'data' => $subscription_attachment,
            ], 200);
        } else {
            return back();
        }
    }

    public function attachment_delete(Request $request)
    {
        $validator = Validator::make(request()->all(), [
            'id' => [
                'required',
                'integer',
                Rule::exists('subscriptions_attachments', 'id')->where(function ($query) {
                    $query->where('user_id', Auth::id());
                }),
            ],
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
        }

        SubscriptionAttachment::find($request->input('id'))->delete();

        return Response::json([
            'status' => true,
            'message' => 'Success',
        ], 200);
    }

    public function history(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => [
                'required',
                'integer',
                Rule::exists('subscriptions', 'id')->where(function ($query) {
                    $query->where('user_id', Auth::id());
                }),
            ],
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
        }

        $data = [
            'slug' => 'subscription',
            'subscription_id' => $request->input('id'),
        ];

        return view('client/subscription/history', $data);
    }

    // Main page of mass update
    public function massUpdate(Request $request)
    {
        return view('client.subscription.massUpdate', [
            'slug' => 'subscription',
            'folders' => \App\Models\FolderModel::get_by_user(),
        ]);
    }

    // search query
    public function massUpdateSearch($request)
    {
        $keyword = $request->keyword ?? '';


        $query = Subscription::leftJoin('products', 'subscriptions.brand_id', '=', 'products.id')
            ->leftJoin('product_types', 'products.product_type', '=', 'product_types.id')
            ->leftJoin('product_categories', 'subscriptions.category_id', '=', 'product_categories.id')
            ->leftJoin('folder', 'subscriptions.folder_id', '=', 'folder.id')
            ->leftJoin('users_payment_methods', 'subscriptions.payment_mode_id', '=', 'users_payment_methods.id')
            ->leftJoin('users_alert', 'subscriptions.alert_id', '=', 'users_alert.id')
            ->leftJoin('subscriptions_attachments', 'subscriptions.id', '=', 'subscriptions_attachments.subscription_id')
            ->where('subscriptions.user_id', Auth::id())
            ->where(local('subscription_folder_id') ? ['folder_id' => local('subscription_folder_id')] : null)
            ->select(
                'subscriptions.*',
                'products.product_name as brand_name',
                'product_types.name as product_type_name',
                'product_categories.name as product_category_name',
                'folder.color as folder_color',
                'folder.name as folder_name',
                'users_payment_methods.name as payment_method_name',
                'users_alert.alert_name as alert_name',
                DB::raw('count(subscriptions_attachments.subscription_id) as attachment_count'),
            )
            ->groupBy('subscriptions.id');

        // keyword
        if ($keyword) {
            $query = $query->where(function ($query) use ($keyword) {
                $query->where('subscriptions.product_name', 'like', '%' . $keyword . '%')
                    ->orWhere('subscriptions.payment_date', 'like', '%' . $keyword . '%')
                    ->orWhereRaw("DATE_FORMAT(subscriptions.payment_date, '%d %M %Y') like ?", ["%$keyword%"])
                    ->orWhereRaw("DATE_FORMAT(subscriptions.payment_date, '%d %b %Y') like ?", ["%$keyword%"])
                    ->orWhere('subscriptions.next_payment_date', 'like', '%' . $keyword . '%')
                    ->orWhereRaw("DATE_FORMAT(subscriptions.next_payment_date, '%d %M %Y') like ?", ["%$keyword%"])
                    ->orWhereRaw("DATE_FORMAT(subscriptions.next_payment_date, '%d %b %Y') like ?", ["%$keyword%"])
                    ->orWhere('subscriptions.price', 'like', '%' . $keyword . '%')
                    ->orWhere('subscriptions.price_type', 'like', '%' . $keyword . '%')
                    ->orWhere('users_payment_methods.name', 'like', '%' . $keyword . '%')
                    ->orWhere('product_categories.name', 'like', '%' . $keyword . '%')
                    ->orWhere('product_types.name', 'like', '%' . $keyword . '%')
                    ->orWhere('subscriptions.product_name', 'like', '%' . $keyword . '%');
            });
        }

        // filters
        if ($request->has('filters')) {
            // type
            if (isset($request->filters['type'])) {
                $query = $query->where('subscriptions.type', '=', $request->filters['type']);
            }

            // rating
            if (isset($request->filters['rating'])) {
                $rating = $request->filters['rating'];
                // if not check all rate
                $stars = $rating['stars'] ?? [];
                $query = $query->whereIn('subscriptions.rating', $stars);
            }

            // product_name
            if (isset($request->filters['product_name'])) {
                $searchValue = $request->filters['product_name']['product_name'];
                $searchValue = lib()->do->filter_unicode($searchValue);
                // 
                $query->where('subscriptions.product_name', 'like', '%' . $searchValue . '%');
            }

            // status
            if (isset($request->filters['status'])) {
                $status = $request->filters['status']['status'] == 'true' ? 1 : 0;
                // 
                $query->where('subscriptions.status', '=', $status);
            }

            // folder
            if (isset($request->filters['folder'])) {
                $folderId = $request->filters['folder']['folder_id'];
                // 
                $query->where('subscriptions.folder_id', '=', $folderId);
            }

            // payment
            if (isset($request->filters['payment'])) {
                $paymentId = $request->filters['payment']['payment_mode_id'];
                // 
                $query->where('subscriptions.payment_mode_id', '=', $paymentId);
            }
            
            // due_date
            if (isset($request->filters['due'])) {
                $dateString = $request->filters['due']['due_date'];
                $date = Carbon::createFromFormat('Y-m-d', $dateString);
                // 
                $query->whereDate('subscriptions.next_payment_date', '=', $date);
            }

            // refund_date
            if (isset($request->filters['refund'])) {
                $dateString = $request->filters['refund']['refund_date'];
                $date = Carbon::createFromFormat('Y-m-d', $dateString);
                // 
                $query->whereDate('subscriptions.refund_date', '=', $date);
            }

            // expiry_date
            if (isset($request->filters['expiry'])) {
                $dateString = $request->filters['expiry']['expiry_date'];
                $date = Carbon::createFromFormat('Y-m-d', $dateString);
                // 
                $query->whereDate('subscriptions.expiry_date', '=', $date);
            }

            // tag
            if (isset($request->filters['tag'])) {
                $tagId = $request->filters['tag']['tag_id'];
                // 
                $query->join('subscriptions_tags', function($join)
                {
                    $join->on('subscriptions_tags.subscription_id', '=', 'subscriptions.id');
                })->where('subscriptions_tags.tag_id', '=', $tagId);
            }
        }

        return $query;
    }

    // List data as json
    public function massUpdateList(Request $request)
    {
        // init
        $sort = $request->sort;
        $perPage = $request->per_page ?? 10;

        // mass update query from request
        $query = $this->massUpdateSearch($request);

        // sort
        if ($sort && $sort['name']) {
            $query = $query->orderBy($sort['name'], ($sort['direction'] ?? 'asc'));
        }
        
        // pagination
        $subscriptions = $query->paginate($perPage);

        return view('client.subscription.massUpdateList', [
            'subscriptions' => $subscriptions,
            'sort' => $sort,
            'folders' => \App\Models\FolderModel::get_by_user(),
        ]);
    }

    // Mass update save all
    public function massUpdateSaveAll(Request $request)
    {
        // mass update query from request
        // $query = $this->massUpdateSearch($request);
        
        $query = Subscription::whereIn('id', $request->ids);

        // update tags
        if ($request->has('mass_tags') && is_array($request->mass_tags)) {
            foreach($query->get() as $subscription) {
                foreach ($request->mass_tags as $tagName) {
                    $subscription->addTag($tagName);
                }
            }
        }

        // remove tags
        if ($request->has('mass_remove_tags') && is_array($request->mass_remove_tags)) {
            foreach($query->get() as $subscription) {
                foreach ($request->mass_remove_tags as $tagName) {
                    $subscription->removeTag($tagName);
                }
            }
        }


        // update folder
        if ($request->has('mass_folder_id') && $request->mass_folder_id) {
            foreach($query->get() as $subscription) {
                $subscription->addFolderById($request->mass_folder_id);
            }
        }

        // update status
        if ($request->has('mass_status') && $request->mass_status != "" && $request->mass_status !== "0") {
            foreach($query->get() as $subscription) {
                $subscription->status = $request->mass_status;
                $subscription->save();
            }
        }
    }

    public function massUpdateSave(Request $request) {
        $subscription = Subscription::find($request->subscription_id);

        // save name
        if ($request->has('product_name')) {
            $subscription->product_name = $request->product_name;
            $subscription->save();
            
            return response()->json([
                'product_name' => $subscription->product_name,
            ]);
        }

        // save description
        if ($request->has('description')) {
            $subscription->description = $request->description;
            $subscription->save();
            
            return response()->json([
                'description' => $subscription->description,
            ]);
        }

        // save rating
        if ($request->has('rating')) {
            $subscription->rating = $request->rating;
            $subscription->save();
            
            return response()->json([
                'rating' => view('client/datatable/subscription/column_rating', [
                    'rating' => $subscription->rating,
                ])->render(),
            ]);
        }

        // save price
        if ($request->has('price')) {
            $subscription->price = $request->price;
            $subscription->save();
            
            return response()->json([
                'price' => $subscription->price,
            ]);
        }

        // save refund_days
        if ($request->has('refund_days')) {
            $subscription->refund_days = $request->refund_days;
            $subscription->save();
            
            return response()->json([
                'refund_days' => $subscription->refund_days,
            ]);
        }

        // save next_payment_date
        if ($request->has('next_payment_date')) {
            $new_payment_date = Carbon::createFromFormat('Y-m-d', $request->next_payment_date, $subscription->timezone);
            $new_payment_date->setTimezone(APP_TIMEZONE);
            $new_payment_date_str = $new_payment_date->format('Y-m-d');
            $subscription->next_payment_date = $new_payment_date_str;
            $subscription->save();
            
            return response()->json([
                'next_payment_date' => date('d M Y', strtotime($subscription->next_payment_date)),
            ]);
        }

        // save refund_date
        if ($request->has('refund_date')) {
            $date = Carbon::createFromFormat('Y-m-d', $request->refund_date, $subscription->timezone);
            $date->setTimezone(APP_TIMEZONE);
            $date_str = $date->format('Y-m-d');
            $subscription->refund_date = $date_str;
            $subscription->save();
            
            return response()->json([
                'refund_date' => date('d M Y', strtotime($subscription->refund_date)),
            ]);
        }

        // save expiry_date
        if ($request->has('expiry_date')) {
            $date = Carbon::createFromFormat('Y-m-d', $request->expiry_date, $subscription->timezone);
            $date->setTimezone(APP_TIMEZONE);
            $date_str = $date->format('Y-m-d');
            $subscription->expiry_date = $date_str;
            $subscription->save();
            
            return response()->json([
                'expiry_date' => date('d M Y', strtotime($subscription->expiry_date)),
            ]);
        }

        // save payment_mode_id
        if ($request->has('payment_mode_id')) {
            $payment_mode = lib()->user->payment_methods->filter(function($item) use ($request) {
                return $item->id == $request->payment_mode_id;
            })->first();

            $subscription->payment_mode = $payment_mode->name;
            $subscription->payment_mode_id = $payment_mode->id;
            $subscription->save();
            
            return response()->json([
                'payment_mode' => $subscription->payment_mode,
            ]);
        }

        // save payment_mode_id
        if ($request->has('status')) {
            $subscription->status = $request->status;
            $subscription->save();
            
            return response()->json([
                'status' => $subscription->status,
            ]);
        }

        // save folder
        if ($request->has('folder_id')) {
            $subscription->folder_id = $request->folder_id;
            $subscription->save();
            
            return response()->json([
                'folder' => $subscription->folder->name,
            ]);
        }

        // save category
        if ($request->has('category_id')) {
            $subscription->category_id = $request->category_id;
            $subscription->save();
            
            return response()->json([
                'category' => $subscription->product_category->name,
            ]);
        }

        // save tags
        if ($request->has('has_tag')) {
            $tagNames = [];
            foreach ($request->inline_mass_tags as $tagName) {
                if (isset($tagName)) {
                    $tagNames[] = $tagName;
                }
            }

            $subscription->updateTags($tagNames);
            
            return response()->json([
                'tags' => view('client/datatable/subscription/column_tags', [
                    'subscription' => $subscription,
                ])->render(),
            ]);
        }
    }
}
