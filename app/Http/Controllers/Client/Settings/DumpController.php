<?php

namespace App\Http\Controllers\Client\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Application as lib;
use App\Library\Cron;
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
use App\Models\ProductCategoryModel;
use App\Models\Subscription;
use App\Models\UserAlert;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class DumpController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
    }

    public function index()
    {
        $data = [
            'slug' => 'import',
        ];
        return view('client/settings/dump/import', $data);
    }

    public function import_validate(Request $request)
    {
        // Check counter
        $validator = Validator::make($request->all(), [
            'count' => 'required|integer',
            'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
        } else {
            $count = $request->input('count');
        }

        // $all_fields = ['type', 'folder', 'product', 'description', 'price', 'price_type', 'payment_date', 'discount_voucher', 'note', 'support_details', 'url', 'billing_frequency', 'billing_cycle', 'tags',];
        $addon_row_no_all_used = [];
        $addon_line_no_all_used = [];

        for ($i = 0; $i < $count; $i++) {

            // Empty check
            // $empty = true;
            // foreach ($all_fields as $val) {
            //     if (!empty($request->input($val . "_$i"))) {
            //         $empty = false;
            //         break;
            //     }
            // }
            // // Skip on empty fields
            // if ($empty) {
            //     continue;
            // }

            // Validate all the fields dynamically
            $validator = Validator::make($request->all(), $this->import_validation($request, $i));

            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'message' => $validator->errors(),
                ]);
            }

            // Validate addon line no
            $addon_line_no = $request->input('addon_line_no' . "_$i");
            if (!empty($addon_line_no)) {
                if (
                    // Check if addon line no is matching with the same line
                    $addon_line_no == ($i + 2) ||

                    // Check if addon line no is out of bound
                    $addon_line_no > ($count + 1) ||

                    // Check if line is already in use
                    isset($addon_row_no_all_used[$addon_line_no]) ||

                    // Check if interlinking with another addon line no
                    isset($addon_line_no_all_used[$i + 2])
                ) {
                    return Response::json([
                        'status' => false,
                        'message' => "The addon line no $i is invalid.",
                    ]);
                }

                // Store all the used row of addon line no and linked line no
                $addon_row_no_all_used[$i + 2] = $request->input('addon_line_no' . "_$i");
                $addon_line_no_all_used[$request->input('addon_line_no' . "_$i")] = $i + 2;
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


    public function import_insert(Request $request)
    {
        // Check counter
        $validator = Validator::make($request->all(), [
            'count' => 'required|integer',
            'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
        } else {
            $count = $request->input('count');
        }

        $addon_line_no_all = [];
        $addon_row_no_all_used = [];
        $addon_line_no_all_used = [];
        $data_id_all = [];

        for ($i = 0; $i < $count; $i++) {

            // Validate all the fields dynamically
            $validator = Validator::make($request->all(), $this->import_validation($request, $i));

            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'message' => $validator->errors(),
                ]);
            }

            // Validate addon line no
            $addon_line_no = $request->input('addon_line_no' . "_$i");
            if (!empty($addon_line_no)) {
                if (
                    // Check if addon line no is matching with the same line
                    $addon_line_no == ($i + 2) ||

                    // Check if addon line no is out of bound
                    $addon_line_no > ($count + 1) ||

                    // Check if line is already in use
                    isset($addon_row_no_all_used[$addon_line_no]) ||

                    // Check if interlinking with another addon line no
                    isset($addon_line_no_all_used[$i + 2])
                ) {
                    return Response::json([
                        'status' => false,
                        'message' => "The addon line no $i is invalid.",
                    ]);
                }

                // Store all the used row of addon line no and linked line no
                $addon_row_no_all_used[$i + 2] = $request->input('addon_line_no' . "_$i");
                $addon_line_no_all_used[$request->input('addon_line_no' . "_$i")] = $i + 2;
            }

            // Store all addon line no in index array
            $addon_line_no_all[] = $request->input('addon_line_no' . "_$i");
        }

        // Check limit once
        if (UserModel::limit_reached(['subscription', 'folder', 'tag', 'payment_method'])) {
            return lib()->do->get_limit_msg($request, ['subscription', 'folder', 'tag', 'payment_method']);
        }

        for ($i = 0; $i < $count; $i++) {
            $product_data = [
                'id' => 1,
                'product_name' => $request->input('product' . "_$i"),
                'image' => null,
                'favicon' => null,
                'brandname' => null,
                'product_type' => null,
                'pricing_type' => null,
                'currency_code' => null,
                'refund_days' => null,
                'description' => null,
                'url' => null,
                'ltdval_price' => null,
                'ltdval_frequency' => null,
                'ltdval_cycle' => null,
            ];


            $type_all = array_flip(['None', 'Subscription', 'Trial', 'Lifetime', 'Revenue']);
            $type = $request->input('type' . "_$i");

            // Get product
            $product = DB::table('products')
                ->where('product_name', $product_data['product_name'])
                ->where('status', 1)
                ->get()
                ->first();

            if (empty($product)) {

                if (isset($type_all[$type])) {
                    $product_data['id'] = $type_all[$type];
                }

                if ($product_data['id'] < 1) {
                    $product_data['id'] = 1;
                }
            } else {
                // Set product default data
                $product_data['id'] = $product->id;
                $product_data['product_name'] = $product->product_name;
                $product_data['image'] = $product->image;
                $product_data['favicon'] = $product->favicon;
                $product_data['brandname'] = $product->brandname;
                $product_data['product_type'] = $product->product_type;
                $product_data['pricing_type'] = $product->pricing_type;
                $product_data['currency_code'] = $product->currency_code;
                $product_data['refund_days'] = $product->refund_days;
                $product_data['description'] = $product->description;
                $product_data['url'] = $product->url;
                $product_data['ltdval_price'] = $product->ltdval_price;
                $product_data['ltdval_frequency'] = $product->ltdval_frequency;
                $product_data['ltdval_cycle'] = $product->ltdval_cycle;
            }



            // $product_id = ProductModel::get_or_create($request->input('product' . "_$i"));
            if (empty($request->input('folder' . "_$i"))) {
                $folder_id = 0;
            } else {
                $folder_id = FolderModel::get_or_create($request->input('folder' . "_$i"));
            }


            // Get category data
            if (empty($request->input('category' . "_$i"))) {
                $category_id = 1;
            } else {
                $category_id = ProductCategoryModel::get_id($request->input('category' . "_$i"));
            }


            // Get alert profile data
            if (empty($request->input('alert_profile' . "_$i"))) {
                // System Default
                $alert_id = 1;
            } else {
                $alert_id = UserAlert::get_id($request->input('alert_profile' . "_$i"));
            }


            $type = $request->input('type' . "_$i");
            $all_type = array_flip(table('subscription.type'));
            if (isset($all_type[$type])) {
                $type = $all_type[$type];
            } else {
                $type = 1;
            }


            // Get billing cycle
            if (empty($request->input('billing_cycle' . "_$i"))) {
                $billing_cycle = null;
            } else {
                $billing_cycle = $request->input('billing_cycle' . "_$i");
                $all_billing_cycle = array_flip(table('subscription.cycle'));
                if (isset($all_billing_cycle[$billing_cycle])) {
                    $billing_cycle = $all_billing_cycle[$billing_cycle];
                } else {
                    $billing_cycle = 3;
                }
            }


            // Get billing type
            if (empty($request->input('billing_type' . "_$i"))) {
                $billing_type = null;
            } else {
                $billing_type = $request->input('billing_type' . "_$i");
                $all_billing_type = array_flip(table('subscription.billing_type'));
                if (isset($all_billing_type[$billing_type])) {
                    $billing_type = $all_billing_type[$billing_type];
                } else {
                    $billing_type = 1;
                }
            }


            // Get recurring
            $recurring = 0;
            if (!empty($request->input('billing_frequency' . "_$i")) && !empty($billing_cycle) && $type != 3) {
                $recurring = 1;
            }


            // Get payment method information
            if (empty($request->input('payment_mode' . "_$i"))) {
                $payment_mode_id = 0;
            } else {
                $payment_mode_id = PaymentMethodModel::get_or_create($request->input('payment_mode' . "_$i"));
            }


            // Get payment mode
            // $payment_mode = $request->input('payment_mode' . "_$i");
            // $payment_mode_all = array_flip(table('subscription.payment.mode'));
            // if (!empty($payment_mode_all) && is_array($payment_mode_all) && isset($payment_mode_all[$payment_mode])) {
            //     $payment_mode = $payment_mode_all[$payment_mode];
            // } else {
            //     $payment_mode = null;
            // }

            // PaymentMethodModel::get_or_create($request->input('payment_mode' . "_$i"));

            $refund_days = $request->input('refund_days' . "_$i");
            if (empty($refund_days)) {
                $refund_days = $product_data['refund_days'];
            }


            // Get billing cycle
            $ltdval_cycle = null;
            if (!empty($request->input('ltdval_cycle' . "_$i"))) {
                $ltdval_cycle = $request->input('ltdval_cycle' . "_$i");
                $all_ltdval_cycle = array_flip(table('subscription.cycle'));
                if (isset($all_ltdval_cycle[$ltdval_cycle])) {
                    $ltdval_cycle = $all_ltdval_cycle[$ltdval_cycle];
                }
            }
            $status = $request->input('status' . "_$i");
            $sub_addon = 0;
            switch ($status) {
                case 'Draft':
                    $status = 0;
                    break;
                case 'Addon':
                    $status = 1;
                    $sub_addon = 1;
                    break;
                case 'Recur':
                    $status = 1;
                    $recurring = 1;
                    break;
                case 'Once':
                    $status = 1;
                    $recurring = 0;
                    break;
                case 'Canceled':
                    $status = 2;
                    break;
                case 'Cancelled':
                    $status = 2;
                    break;
                case 'Refund':
                    $status = 3;
                    break;
                case 'Expired':
                    $status = 4;
                    break;
                default:
                    $status = 0;
                    break;
            }

            $description = $request->input('description' . "_$i");
            if (empty($description)) {
                $description = $product_data['description'];
            }

            $ltdval_price = $request->input('ltdval_price' . "_$i");
            if (empty($ltdval_price)) {
                $ltdval_price = (float)$product_data['ltdval_price'];
            } else {
                $ltdval_price = (float)$ltdval_price;
            }

            $data = [
                'user_id' => Auth::id(),
                'type' => $type,
                'status' => $status,
                'folder_id' => $folder_id,
                'category_id' => $category_id,
                'alert_id' => $alert_id,
                'description' => $description,
                'price' => (float)$request->input('price' . "_$i"),
                'price_type' => $request->input('currency_code' . "_$i"),
                'payment_date' => $request->input('payment_date' . "_$i"),
                'expiry_date' => $request->input('expiry_date' . "_$i"),
                'discount_voucher' => $request->input('discount_voucher' . "_$i"),
                'note' => $request->input('note' . "_$i"),
                'support_details' => $request->input('support_details' . "_$i"),
                'url' => $request->input('url' . "_$i") ?? $product_data['url'],
                // 'refund_days' => $request->input('refund_days' . "_$i"),
                'billing_frequency' => $request->input('billing_frequency' . "_$i"),
                'billing_cycle' => $billing_cycle,
                'billing_type' => $billing_type,
                'recurring' => $recurring,
                'alert_type' => $request->input('alert_type' . "_$i"),
                'ltdval_price' => $ltdval_price,
                'ltdval_cycle' => $ltdval_cycle ?? $product_data['ltdval_cycle'],
                'ltdval_frequency' => $request->input('ltdval_frequency' . "_$i") ?? $product_data['ltdval_frequency'],
                // 'payment_mode' => $request->input('payment_mode' . "_$i"),
                'payment_mode_id' => $payment_mode_id,

                // Prodcut data
                'brand_id' => $product_data['id'],
                'product_name' => $product_data['product_name'],
                'image' => $product_data['image'],
                'favicon' => $product_data['favicon'],
                'brandname' => $product_data['brandname'],
                'product_type' => $product_data['product_type'],
                'pricing_type' => $product_data['pricing_type'],
                'currency_code' => $product_data['currency_code'],
                // 'refund_days' => $product_data['refund_days'],
                'refund_days' => $refund_days,


                'timezone' => lib()->user->get_timezone(),
                'base_value' => lib()->do->currency_convert($request->input('price' . "_$i"), $request->input('currency_code' . "_$i")),
                'base_currency' => APP_CURRENCY,
                'sub_addon' => $sub_addon,
            ];

            // Check refund days
            if (!empty($data['refund_days'])) {
                $data['refund_date'] = date('Y-m-d', strtotime($data['payment_date'] . ' +' . $data['refund_days'] . ' days'));
            }

            $subscription_id = SubscriptionModel::create($data);
            $data_id_all[] = $subscription_id;


            // Add event logs
            $this->add_event([
                'table_row_id' => $subscription_id,
                'event_type_status' => 'create',
                'event_product_id' => $product_data['id'],
                'event_type_schedule' => $request->input('auto_renew' . "_$i") ? 2 : 1,
            ]);


            // Check active status
            if ($status) {

                // Add history
                SubscriptionModel::create_new_history($subscription_id);

                // Add events
                SubscriptionModel::set_refund_date($subscription_id);

                // Add next schedule date
                Cron::schedule($subscription_id);
            }

            if (!empty($request->input('tags' . "_$i"))) {
                $input_tags = explode(',', $request->input('tags' . "_$i"));
            }


            $tags = [];
            if (!empty($input_tags) && is_array($input_tags)) {
                $user_id = Auth::id();
                $user_tags = TagModel::get_by_user_arr($user_id);
                if (!empty($user_tags)) {
                    $user_tags = array_flip($user_tags);
                }

                foreach ($input_tags as $val) {
                    $val = trim($val);

                    // Search for exist tag
                    if (isset($user_tags[$val])) {
                        $tags[] = [
                            'user_id' => $user_id,
                            'subscription_id' => $subscription_id,
                            'tag_id' => $user_tags[$val],
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
        }


        // Lifetime addon link
        if (count($addon_line_no_all) > 0 && count($addon_line_no_all) == count($data_id_all)) {
            foreach ($addon_line_no_all as $key => $val) {

                if (!empty($val) && !empty($data_id_all[$key])) {

                    // Get index
                    $data_count = ((int)$val) - 2;

                    // Check if data is available
                    if (isset($data_id_all[$data_count])) {

                        $child_subscription_id = $data_id_all[$key];
                        $parent_subscription_id = $data_id_all[$data_count];

                        $subscription = Subscription::find($child_subscription_id);

                        if (empty($subscription->id)) {
                            continue;
                        } else {

                            // Lifetime addon
                            $subscription->sub_addon = 1;
                            $subscription->sub_id = $parent_subscription_id;
                            $subscription->save();
                        }
                    }
                }
            }
        }



        if ($request->ajax()) {

            // Create event logs
            NotificationEngine::staticModel('event')::create([
                'user_id' => Auth::id(),
                'event_type' => 'import_export',
                'event_type_status' => 'create',
                'event_status' => 1,
                'table_name' => 'subscriptions',
                'table_row_id' => $subscription_id,
                'event_type_function' => __CLASS__ . '::' . __FUNCTION__ . '()',
            ]);

            return Response::json([
                'status' => true,
                'message' => "Successfully imported $count subscriptions",
            ], 200);
        } else {
            return back();
        }
    }

    private function import_validation(Request $request, int $i)
    {
        return [
            'type' . "_$i" => 'required|string|max:255|in:Subscription,Trial,Lifetime',
            'folder' . "_$i" => 'nullable|string|max:' . len()->folder->name,
            'category' . "_$i" => 'nullable|string|max:' . len()->product_categories->name . '|exists:product_categories,name',
            'product' . "_$i" => 'required|string|max:' . len()->subscriptions->product_name,
            'description' . "_$i" => 'nullable|string|max:' . len()->subscriptions->description,
            'price' . "_$i" => 'required|numeric',
            'currency_code' . "_$i" => [
                'required',
                'string',
                'size:3',
                Rule::in(lib()->config->currency_code),
            ],
            'payment_date' . "_$i" => 'required|date_format:Y-m-d',
            'discount_voucher' . "_$i" => 'nullable|string|max:20',
            'note' . "_$i" => 'nullable|string|max:' . len()->subscriptions->note,
            'support_details' . "_$i" => 'nullable|string|max:' . len()->subscriptions->support_details,
            'url' . "_$i" => 'nullable|url|max:' . len()->subscriptions->url,
            'billing_frequency' . "_$i" => [
                'nullable',
                "prohibited_if:type_$i,Lifetime",
                "required_if:type_$i,Subscription,Trial,Revenue",
                'integer',
                'between:1,40',
            ],
            'billing_cycle' . "_$i" => [
                'nullable',
                "prohibited_if:type_$i,Lifetime",
                "required_if:type_$i,Subscription,Trial,Revenue",
                'string',
                // 'max:' . len()->subscriptions->billing_cycle,
                'max:5',
                'in:Day,Week,Month,Year',
            ],
            'billing_type' . "_$i" => 'nullable|string|max:255|in:days,date',
            'tags' . "_$i" => 'nullable|string',
            'alert_type' . "_$i" => 'required|boolean',
            'payment_mode' . "_$i" => 'required|string|max:' . len()->subscriptions->payment_mode,
            'alert_profile' . "_$i" => [
                'nullable',
                'string',
                'max:' . len()->users_alert->alert_name,
                Rule::exists('users_alert', 'alert_name')
                    ->whereIn('user_id', [0, Auth::id()]),
            ],
            'refund_days' => 'nullable|integer|min:1|max:' . len()->subscriptions->refund_days,
            'ltdval_price' . "_$i" => 'nullable|numeric',
            'ltdval_frequency' . "_$i" => [
                'nullable',
                "required_with:ltdval_cycle_$i",
                'integer',
                'between:1,40',
            ],
            'ltdval_cycle' . "_$i" => [
                'nullable',
                "required_with:ltdval_frequency_$i",
                'string',
                'max:5',
                'in:Day,Week,Month,Year',
            ],
            'addon_line_no' . "_$i" => [
                'nullable',
                'integer',
                'gt:1',
            ],
            'expiry_date' . "_$i" => 'nullable|date_format:Y-m-d|after:today|after:payment_date' . "_$i" . '|prohibited_if:type' . "_$i" . ',3|' . $this->get_expiry_date_rule($request, $i),
            'status' . "_$i" => ['nullable', 'string', 'max:10', 'in:Draft,Addon,Recur,Once,Canceled,Cancelled,Refund,Expired'],
            'recurring' . "_$i" => 'nullable|boolean',
        ];
    }

    private function get_expiry_date_rule(Request $request, int $i)
    {
        $validator = Validator::make($request->all(), [
            'payment_date' . "_$i" => 'required|date_format:Y-m-d',
            'refund_days' . "_$i" => 'nullable|integer|min:1|max:' . len()->subscriptions->refund_days,
            'refund_date' . "_$i" => 'nullable|date_format:Y-m-d',
        ]);

        if (!$validator->fails()) {
            $payment_date = $request->input('payment_date' . "_$i");
            $refund_days = $request->input('refund_days' . "_$i");
            $refund_date = $request->input('refund_date' . "_$i");

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



    public function import_export(Request $request)
    {
        $data = SubscriptionModel::get_by_user();
        // $payment_mode_all = table('subscription.payment.mode');
        $output = [];


        $data_id_all = [];
        $data_count_id_all = [];
        $addon_id_all = [];

        if (!empty($data)) {
            $all_type = table('subscription.type');
            $all_billing_cycle = table('subscription.cycle');
            $all_billing_type = table('subscription.billing_type');

            $count = 0;
            foreach ($data as $val) {

                // Store all id
                $data_id_all[] = $val->id;
                $data_count_id_all[$val->id] = $count;
                $addon_id_all[] = $val->sub_id;


                $type = table('subscription.type')[1];
                if (isset($all_type[$val->type])) {
                    $type = $all_type[$val->type];
                }

                $billing_cycle = table('subscription.cycle')[1];
                if (isset($all_billing_cycle[$val->billing_cycle])) {
                    $billing_cycle = $all_billing_cycle[$val->billing_cycle];
                }


                // Billing type
                $billing_type = table('subscription.billing_type')[1];
                if (isset($all_billing_type[$val->billing_type])) {
                    $billing_type = $all_billing_type[$val->billing_type];
                }


                $tags = SubscriptionModel::get_tags($val->id);
                $output_tags = [];
                foreach ($tags as $tag) {
                    $output_tags[] = $tag->name;
                }

                // Lifetime
                if ($val->type == 3) {
                    $val->billing_frequency = null;
                    $billing_cycle = null;
                }

                $ltdval_cycle = null;
                if (isset($all_billing_cycle[$val->ltdval_cycle])) {
                    $ltdval_cycle = $all_billing_cycle[$val->ltdval_cycle];
                }

                // Get payment mode
                // $payment_mode = $val->payment_mode;
                // if (!empty($payment_mode_all) && is_array($payment_mode_all) && isset($payment_mode_all[$payment_mode])) {
                //     $payment_mode = $payment_mode_all[$payment_mode];
                // } else {
                //     $payment_mode = null;
                // }

                $status = SubscriptionModel::get_status($val);

                $output[] = [
                    'type' => $type,
                    'folder' => $val->folder_name,
                    'category' => $val->product_category_name,
                    'product' => $val->product_name,
                    'description' => $val->description,
                    'price' => $val->price,
                    'currency_code' => $val->price_type,
                    'payment_date' => $val->payment_date,
                    'billing_frequency' => $val->billing_frequency,
                    'billing_cycle' => $billing_cycle,
                    'billing_type' => $billing_type,
                    'discount_voucher' => $val->discount_voucher,
                    'note' => $val->note,
                    'support_details' => $val->support_details,
                    'url' => $val->url,
                    'tags' => implode(',', $output_tags),
                    'alert_type' => $val->alert_type,
                    'payment_mode' => $val->payment_method_name,
                    'alert_profile' => $val->alert_name,
                    'refund_days' => $val->refund_days,
                    'ltdval_price' => $val->ltdval_price,
                    'ltdval_frequency' => $val->ltdval_frequency,
                    'ltdval_cycle' => $ltdval_cycle,
                    'addon_line_no' => null,
                    'expiry_date' => $val->expiry_date,
                    'status' => $status,
                ];

                $count++;
            }

            // Lifetime addon link
            if (count($addon_id_all) > 0) {
                foreach ($addon_id_all as $key => $val) {

                    if (!empty($val)) {
                        if (isset($data_count_id_all[$val])) {
                            $output[$key]['addon_line_no'] = $data_count_id_all[$val] + 2;
                        }
                    }
                }
            }
        }


        return Response::json([
            'status' => true,
            'message' => 'Success',
            'data' => $output,
        ], 200);
    }
}
