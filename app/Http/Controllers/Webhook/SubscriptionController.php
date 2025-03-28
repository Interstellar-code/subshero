<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Webhook\Controller;
use App\Models\BrandModel;
use App\Library\NotificationEngine;
use App\Models\FolderModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\SubscriptionModel;
use App\Models\File;
use App\Models\ProductModel;
use App\Models\Subscription;
use App\Models\SubscriptionHistoryModel;
use App\Models\SubscriptionAttachment;
use App\Models\TagModel;
use App\Models\UserModel;
use App\Models\Webhook;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class SubscriptionController extends Controller
{
    private $user_id = null;

    public function __construct(int $user_id)
    {
        parent::__construct();
        $this->user_id = $user_id;
    }

    public function index()
    {
        $data = Subscription::where('user_id', $this->user_id)->get();

        return response($data, 200);
    }

    public function show($id)
    {
        $data = Subscription::where('user_id', $this->user_id)
            ->where('id', $id)
            ->first();

        return response()->json($data);
    }

    public function create(Request $request, array $data)
    {
        $validator = Validator::make($data, [
            'status' => 'nullable|boolean',
            'type' => 'required|numeric|digits_between:0,9',
            'folder_id' => 'nullable|string|max:255',
            // 'category_id' => 'nullable|integer',
            // 'alert_id' => 'required|integer',
            // 'tags' => 'required|numeric',
            // 'client_name' => 'required|string|max:255',
            'brand_id' => 'required|string|max:255',
            // 'description' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'price_type' => 'required|string|max:10',
            // 'discount_voucher' => 'nullable|string|max:20',
            // 'payment_mode' => 'required|string|max:' . len()->subscriptions->payment_mode,
            // 'payment_mode_id' => 'required|integer',
            'payment_date' => 'required|date_format:Y-m-d',
            'recurring' => 'nullable|in:0,1',
            // 'note' => 'nullable|string|max:255',
            // 'include_notes' => 'nullable|boolean',
            // 'alert_type' => 'nullable|integer|in:1',
            // 'url' => 'nullable|string|max:255',
            // 'support_details' => 'nullable|string|max:255',
            // 'tags' => 'nullable|array|max:255',
            // 'refund_days' => 'nullable|integer|min:1|max:' . len()->subscriptions->refund_days,
            'billing_frequency' => 'nullable|numeric|digits_between:0,40',
            'billing_cycle' => 'nullable|numeric|digits_between:0,9',
            'billing_type' => 'nullable|integer|in:2',
            // 'img_path_or_file' => 'required|integer|digits_between:0,1',
            // 'rating' => 'nullable|integer',

            // 'ltdval_price' => 'nullable|numeric|min:0',
            // 'ltdval_cycle' => 'nullable|numeric|digits_between:0,9',
            // 'ltdval_frequency' => 'nullable|numeric|digits_between:0,40',
        ]);

        if ($validator->fails()) {
            return 400;
        }


        // Check limit
        if (UserModel::limit_reached('subscription')) {
            return lib()->do->get_limit_msg($request, 'subscription');
        }


        // Check tag limit
        // if ($this->is_tag_limit_reached($request)) {
        //     return lib()->do->get_limit_msg($request, 'tag');
        // }

        // Get folder details
        $folder_id = $request->input('folder_id');

        // Set default folder
        if (empty($folder_id)) {
            $folder_id = lib()->user->default->folder_id;
        } else {
            if (is_numeric($folder_id) && intval($folder_id) == $folder_id) {
                $folder_id = $request->input('folder_id');
            } else {
                $folder_id = FolderModel::get_or_create($request->input('folder_id'));
            }
        }


        // Get product information
        $product_data = $this->get_product_for_subscription($request->input('brand_id'), $request->input('type'));

        $data = [
            'user_id' => $this->user_id,
            'status' => $request->input('status') ? 1 : 0,
            'type' => $request->input('type'),
            'folder_id' => $folder_id,
            'brand_id' => $product_data['brand_id'],
            // 'category_id' => $request->input('category_id'),
            // 'alert_id' => $request->input('alert_id'),
            'product_name' => $product_data['product_name'],
            'image' => $product_data['brand_image'],
            'description' => $product_data['description'],
            'url' => $product_data['url'],
            // 'description' => $request->input('description'),
            'price' => (float)$request->input('price'),
            'price_type' => $request->input('price_type'),
            // 'discount_voucher' => $request->input('discount_voucher'),
            // 'payment_mode' => $request->input('payment_mode'),
            // 'payment_mode_id' => $request->input('payment_mode_id'),
            'payment_mode' => lib()->user->default->payment_mode,
            'payment_mode_id' => lib()->user->default->payment_mode_id,
            'payment_date' => $request->input('payment_date'),
            'payment_date_upd' => $request->input('payment_date'),
            'recurring' => $request->input('recurring') ? 1 : 0,
            // 'note' => $request->input('note'),
            // 'include_notes' => $request->input('include_notes'),
            // 'alert_type' => $request->input('alert_type'),
            // 'url' => $request->input('url'),
            // 'support_details' => $request->input('support_details'),
            // 'tags' => $request->input('tags'),
            // 'refund_days' => $request->input('refund_days'),
            'billing_frequency' => $request->input('billing_frequency'),
            'billing_cycle' => $request->input('billing_cycle'),
            'billing_type' => $request->input('billing_type') ?? 1,
            'timezone' => lib()->user->get_timezone(),
            'base_value' => lib()->do->currency_convert($request->input('price'), $request->input('price_type')),
            'base_currency' => APP_CURRENCY,
            // 'rating' => $request->input('rating'),

            // 'ltdval_price' => (float)$request->input('ltdval_price'),
            // 'ltdval_cycle' => $request->input('ltdval_cycle'),
            // 'ltdval_frequency' => $request->input('ltdval_frequency'),
        ];

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

        // if (!empty($request->input('refund_days'))) {
        //     $data['refund_date'] = date('Y-m-d', strtotime($request->input('payment_date') . ' +' . $request->input('refund_days') . ' days'));
        // }

        // if ($request->input('img_path_or_file') == 0 && !empty($request->input('image_path'))) {
        //     $data['image'] = $request->input('image_path');
        // }
        $subscription_id = SubscriptionModel::create($data);

        // Add event logs
        $this->add_event([
            'table_row_id' => $subscription_id,
            'event_type_status' => 'create',
            'event_product_id' => $product_data['brand_id'],
            'event_type_schedule' => $request->input('recurring') ? 2 : 1,
        ]);

        // // Save image
        // $image_path = '';
        // // Check if user selected an image
        // if ($request->input('img_path_or_file') == 1 && $request->hasFile('image')) {
        //     $image_path = File::add_get_path($request->file('image'), 'subscription', $subscription_id);
        //     SubscriptionModel::do_update($subscription_id, [
        //         'image' => $image_path,
        //     ]);
        // }

        // Check if active
        if ($request->input('status') == 1) {

            // Add events
            // SubscriptionModel::set_refund_date($subscription_id);

            // Add history
            SubscriptionModel::create_new_history($subscription_id);
        }

        // Add next schedule date
        // Note: Disabled for performance issue as discussed on 2021.12.10 at 9.55 pm
        // Cron::schedule($subscription_id);

        // if (is_array($request->input('tags'))) {
        //     $user_id = $this->user_id;
        //     $user_tags = TagModel::get_by_user_arr($this->user_id);
        //     $tags = [];
        //     foreach ($request->input('tags') as $val) {
        //         // Search for exist tag
        //         if (isset($user_tags[$val])) {
        //             $tags[] = [
        //                 'user_id' => $this->user_id,
        //                 'subscription_id' => $subscription_id,
        //                 'tag_id' => $val,
        //             ];
        //         } else {
        //             // Insert the new tag
        //             $tag_data = [
        //                 'user_id' => $this->user_id,
        //                 'name' => $val,
        //             ];
        //             $tag_id = TagModel::create($tag_data);

        //             // Insert tag mapping into subscription_tag table
        //             $tags[] = [
        //                 'user_id' => $this->user_id,
        //                 'subscription_id' => $subscription_id,
        //                 'tag_id' => $tag_id,
        //             ];
        //         }
        //     }

        //     // Insert all the tags
        //     if (!empty($tags)) {
        //         SubscriptionModel::create_tags($tags);
        //     }
        // }


        $subscription = Subscription::find($subscription_id);


        // Send webhook event
        Webhook::send_event('subscription.created', $subscription_id);

        return 200;

        return 422;
    }

    public function update(Request $request, $data)
    {
        $validator = Validator::make($data, [
            'id' => [
                'required',
                'integer',
                Rule::exists('subscriptions', 'id')->where(function ($query) {
                    $query->where('user_id', $this->user_id);
                    $query->whereIn('status', [0, 1]);
                }),
            ],
            'category_id' => 'nullable|integer',
            // 'status' => 'required|numeric|digits_between:0,1',
            'type' => 'nullable|numeric|digits_between:0,9',
            // 'folder_id' => 'nullable|numeric',
            // 'tags' => 'required|numeric',
            // 'client_name' => 'required|string|max:255',
            // 'alert_id' => 'required|integer',
            // 'brand_id' => 'nullable|string|max:255',
            // 'description' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'price_type' => 'nullable|string|max:10',
            // 'discount_voucher' => 'nullable|string|max:20',
            // 'payment_mode' => 'required|string|max:' . len()->subscriptions->payment_mode,
            // 'payment_mode_id' => 'required|integer',
            'payment_date' => 'nullable|date_format:Y-m-d',
            'next_payment_date' => 'nullable|date_format:Y-m-d',
            // 'recurring' => 'nullable|boolean',
            // 'note' => 'nullable|string|max:255',
            // 'include_notes' => 'nullable|boolean',
            // 'alert_type' => 'nullable|integer|in:1',
            // 'url' => 'nullable|string|max:255',
            // 'support_details' => 'nullable|string|max:255',
            // 'tags' => 'nullable|array|max:255',
            // 'refund_days' => 'nullable|integer|min:0|max:' . len()->subscriptions->refund_days,
            // 'refund_date' => 'nullable|date_format:Y-m-d',
            'billing_frequency' => 'nullable|numeric|digits_between:0,40',
            'billing_cycle' => 'nullable|numeric|digits_between:0,9',
            'billing_type' => 'nullable|integer|in:2',
            // 'img_path_or_file' => 'required|integer|digits_between:0,1',
            // 'image' => 'sometimes|nullable|image',
            // 'rating' => 'nullable|integer',

            // 'ltdval_price' => 'nullable|numeric|min:0',
            // 'ltdval_cycle' => 'nullable|numeric|digits_between:0,9',
            // 'ltdval_frequency' => 'nullable|numeric|digits_between:0,40',
        ]);



        // dd($fields);



        if ($validator->fails()) {
            return 400;
        }

        $id = $data['id'];



        // $validator = Validator::make($request->all(), [
        //     // 'id' => 'required|integer',
        //     'category_id' => 'nullable|integer',
        //     // 'status' => 'required|numeric|digits_between:0,1',
        //     'type' => 'nullable|numeric|digits_between:0,9',
        //     // 'folder_id' => 'nullable|numeric',
        //     // 'tags' => 'required|numeric',
        //     // 'client_name' => 'required|string|max:255',
        //     // 'alert_id' => 'required|integer',
        //     // 'brand_id' => 'nullable|string|max:255',
        //     // 'description' => 'nullable|string|max:255',
        //     'price' => 'required|numeric|min:0',
        //     'price_type' => 'nullable|string|max:10',
        //     // 'discount_voucher' => 'nullable|string|max:20',
        //     // 'payment_mode' => 'required|string|max:' . len()->subscriptions->payment_mode,
        //     // 'payment_mode_id' => 'required|integer',
        //     'payment_date' => 'nullable|date_format:Y-m-d',
        //     'next_payment_date' => 'nullable|date_format:Y-m-d',
        //     // 'recurring' => 'nullable|boolean',
        //     // 'note' => 'nullable|string|max:255',
        //     // 'include_notes' => 'nullable|boolean',
        //     // 'alert_type' => 'nullable|integer|in:1',
        //     // 'url' => 'nullable|string|max:255',
        //     // 'support_details' => 'nullable|string|max:255',
        //     // 'tags' => 'nullable|array|max:255',
        //     // 'refund_days' => 'nullable|integer|min:0|max:' . len()->subscriptions->refund_days,
        //     // 'refund_date' => 'nullable|date_format:Y-m-d',
        //     'billing_frequency' => 'nullable|numeric|digits_between:0,40',
        //     'billing_cycle' => 'nullable|numeric|digits_between:0,9',
        //     'billing_type' => 'nullable|integer|in:2',
        //     // 'img_path_or_file' => 'required|integer|digits_between:0,1',
        //     // 'image' => 'sometimes|nullable|image',
        //     // 'rating' => 'nullable|integer',

        //     // 'ltdval_price' => 'nullable|numeric|min:0',
        //     // 'ltdval_cycle' => 'nullable|numeric|digits_between:0,9',
        //     // 'ltdval_frequency' => 'nullable|numeric|digits_between:0,40',
        // ]);

        // if ($validator->fails()) {
        //     return Response::json([
        //         'status' => false,
        //         'message' => $validator->errors(),
        //     ]);
        //     // abort(419);
        // }

        // $subscription_id = $request->input('id');
        $subscription_id = $id;

        $next_payment_date = $request->input('next_payment_date');
        $next_payment_date_status = false;


        // Check tag limit
        if ($this->is_tag_limit_reached($request)) {
            return lib()->do->get_limit_msg($request, 'tag');
        }


        // Get subscription
        $subscription = SubscriptionModel::get($subscription_id);

        if (empty($subscription->id)) {
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
            'user_id' => $this->user_id,
            // 'folder_id' => $request->input('folder_id'),
            // 'alert_id' => $request->input('alert_id'),
            // 'description' => $request->input('description'),
            'price' => (float)$request->input('price'),
            // 'price_type' => $request->input('price_type'),
            'discount_voucher' => $request->input('discount_voucher'),
            // 'payment_mode' => $request->input('payment_mode'),
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

            // 'rating' => $request->input('rating'),

            // 'ltdval_price' => (float)$request->input('ltdval_price'),
            // 'ltdval_cycle' => $request->input('ltdval_cycle'),
            // 'ltdval_frequency' => $request->input('ltdval_frequency'),
        ];

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
        // if ($request->input('img_path_or_file') == 1 && $request->hasFile('image')) {
        //     $image_path = File::add_get_path($request->file('image'), 'subscription', $subscription_id);
        //     SubscriptionModel::do_update($subscription_id, [
        //         'image' => $image_path,
        //     ]);
        // }

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

        if (is_array($request->input('tags'))) {
            $user_id = $this->user_id;
            $user_tags = TagModel::get_by_user_arr($this->user_id);
            $tags = [];
            foreach ($request->input('tags') as $val) {
                // Search for exist tag
                if (isset($user_tags[$val])) {
                    $tags[] = [
                        'user_id' => $this->user_id,
                        'subscription_id' => $subscription_id,
                        'tag_id' => $val,
                    ];
                } else {
                    // Insert the new tag
                    $tag_data = [
                        'user_id' => $this->user_id,
                        'name' => $val,
                    ];
                    $tag_id = TagModel::create($tag_data);

                    // Insert tag mapping into subscription_tag table
                    $tags[] = [
                        'user_id' => $this->user_id,
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

        return 200;

        return 422;
    }

    public function delete(Request $request, $data)
    {
        $validator = Validator::make($data, [
            'id' => [
                'required',
                'integer',
                Rule::exists('subscriptions', 'id')->where(function ($query) {
                    $query->where('user_id', $this->user_id);
                }),
            ],
        ]);

        if ($validator->fails()) {
            return 400;
        }

        $subscription_id = $data['id'];
        $subscription = SubscriptionModel::get($subscription_id);
        if (!empty($subscription->id)) {

            // Add event logs
            $this->add_event([
                'table_row_id' => $subscription_id,
                'event_type_status' => 'delete',
                'event_product_id' => $subscription->brand_id,
                'event_type_schedule' => $subscription->recurring,
            ]);

            // Update event logs
            NotificationEngine::set_del_status([
                'subscription_id' => $subscription_id,
                'event_types' => ['email', 'browser'],
            ]);

            // Send webhook event
            Webhook::send_event('subscription.deleted', $subscription_id);

            // Delete all attachments and its directory
            SubscriptionAttachment::deleteBySubscription($subscription->id);

            SubscriptionModel::del($subscription_id);

            return 200;
        }

        return 422;
    }

    public function refund(Request $request, $data)
    {
        $validator = Validator::make($data, [
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
            return 400;
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
                'subscription_id' => $subscription_id,
                'event_types' => ['email', 'browser'],
            ]);
            SubscriptionModel::refund($subscription_id);

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

            return 200;
        }

        return 422;
    }

    public function cancel(Request $request, $data)
    {
        $validator = Validator::make($data, [
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
            return 400;
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
                'subscription_id' => $subscription_id,
                'event_types' => ['event', 'email', 'browser'],
            ]);
            SubscriptionModel::cancel($subscription_id);

            // Add push notification in queue for sending
            Subscription::queue_push_notification($subscription, 'subscription_cancel');


            // Send webhook event
            Webhook::send_event('subscription.canceled', $subscription->id);

            return 200;
        }

        return 422;
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
            $user_id = $this->user_id;
            $user_tags = TagModel::get_by_user_arr($this->user_id);
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
            'description' => null,
            'url' => null,
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
                $product_data['description'] = $product->description;
                $product_data['url'] = $product->url;

                return $product_data;
            }
        }

        $product_data['brand_id'] = $this->get_product_id_by_sub_type($type);
        $product_data['product_name'] = $brand_id;

        return $product_data;
    }

    private function add_event($data)
    {
        if (in_array($data['event_type_status'], ['create', 'create_quick', 'update', 'delete'])) {
            $old_event_id = NotificationEngine::staticModel('event')::get_event_id_by_table_row_id($data['table_row_id']);

            // Create webhook event logs
            NotificationEngine::staticModel('webhook')::create([
                'user_id' => $this->user_id,
                'event_type' => 'webhook',
                'event_type_status' => $data['event_type_status'],
                'event_status' => 1,
                'table_name' => 'subscriptions',
                'table_row_id' => $data['table_row_id'],
                'event_type_function' => __CLASS__ . '::' . __FUNCTION__ . '()',
                'event_cron' => 0,
                'event_product_id' => $data['event_product_id'],
                'event_type_schedule' => $data['event_type_schedule'],
            ]);

            if (!$old_event_id) {

                // Create event logs
                NotificationEngine::staticModel('event')::create([
                    'user_id' => $this->user_id,
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
                    'user_id' => $this->user_id,
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
}
