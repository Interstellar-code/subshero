<?php

namespace App\Http\Controllers\Client\Subscription;

use App\Http\Controllers\Controller;
use App\Library\NotificationEngine;
use App\Models\File;
use App\Models\FolderModel;
use App\Models\Marketplace;
use App\Models\Product;
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
use Illuminate\Support\Str;
use App\Models\User;

class AdaptController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
    }

    public function submit(Request $request)
    {
        $validator = Validator::make(request()->all(), [
            'id' => [
                'required',
                'integer',
                Rule::exists('subscriptions', 'id')->where(function ($query) {
                    $query->where('user_id', Auth::id());
                    $query->where('brand_id', '<=', PRODUCT_RESERVED_ID);
                    $query->where(function ($query) {
                        $query->where('product_submission_id', 0);
                        $query->orWhereNull('product_submission_id');
                    });
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
        $subscription = Subscription::find($subscription_id);

        if (!empty($subscription->id)) {
            $product = new ProductModel();
            $product->status = 2;
            $product->category_id = $subscription->category_id;
            $product->sub_platform = $subscription->platform_id;
            $product->pricing_type = $subscription->type;
            $product->image = $subscription->_image;
            $product->product_name = $subscription->product_name;
            $product->brandname = $subscription->product_name;
            $product->product_type = $subscription->product_type;
            $product->description = $subscription->description;
            $product->price1_value = $subscription->price;
            $product->currency_code = $subscription->price_type;
            $product->url = $subscription->url;
            $product->billing_frequency = $subscription->billing_frequency;
            $product->billing_cycle = $subscription->billing_cycle;
            $product->ltdval_price = $subscription->ltdval_price;
            $product->ltdval_frequency = $subscription->ltdval_frequency;
            $product->ltdval_cycle = $subscription->ltdval_cycle;
            $product->refund_days = $subscription->refund_days;
            $product->rating = $subscription->rating;
            $product->created_at = $subscription->created_at;
            $product->created_by = Auth::id();
            $product->save();


            // Move image to new location
            if (!empty($product->image) && Storage::disk('local')->exists($product->image)) {
                $new_dir = 'client/1/product/logos/';
                // $new_file_name = Str::slug($product->product_name, '-') . '.' . pathinfo($product->image, PATHINFO_EXTENSION);

                // Generate new file name which is not in use
                $count = 0;
                do {
                    if ($count == 0) {
                        $new_file_name = Str::slug($product->product_name, '-') . '.' . pathinfo($product->image, PATHINFO_EXTENSION);
                    } else {
                        $new_file_name = Str::slug($product->product_name, '-') . '-' . $count . '.' . pathinfo($product->image, PATHINFO_EXTENSION);
                    }
                    $count++;
                } while (Storage::disk('local')->exists($new_dir . $new_file_name));

                Storage::disk('local')->put($new_dir . $new_file_name, Storage::get($product->image));
                $product->image = $new_dir . $new_file_name;
                $product->save();
            }


            // Update subscription
            $subscription->product_submission_id = $product->id;
            $subscription->save();

            // Prepair product notification email template
            $template = NotificationEngine::staticModel('email')::prepare_message_template([
                '{product_name}' => [
                    'product' => $product,
                ],
                '{product_price}' => [
                    'product_price' => lib()->get->currency_symbol($product->currency_code) . $product->price1_value,
                ],
                '{product_type}' => [
                    // try to get subscription type name
                    'product_type' => table('subscription.type')[$subscription->type],
                ],
                'type' => 'product_notify',
            ]);
    
            // Send product notification email to dmin@subshero.com (User ID = 1)
            NotificationEngine::staticModel('email')::send_message([
                'user' => User::find(1),
                'template' => $template,
            ]);
        }

        return Response::json([
            'status' => true,
            'message' => __('Thank you for submitting the product to our DB'),
        ], 200);
    }

    public function edit(Request $request)
    {
        // Merge the request with the value from the route parameter and validate the request
        $request->merge(['id' => $request->route('id')]);
        $validator = Validator::make($request->all(), [
            'id' => [
                'required',
                'integer',
                Rule::exists('subscriptions', 'id')->where(function ($query) {
                    $query->where('user_id', Auth::id());
                    $query->where('product_avail', 1);
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

        $product = Product::where('id', $subscription_data->product_submission_id)->first();
        if (!empty($product->id)) {
            $subscription_data->brand_id = $product->id;
            $subscription_data->category_id = $product->category_id;
            $subscription_data->product_name = $product->product_name;
            $subscription_data->description = $product->description;
            $subscription_data->url = $product->url;
            // $subscription_data->price = $product->price1_value;
            // $subscription_data->price_type = $product->currency_code;
            $subscription_data->ltdval_price = $product->ltdval_price;
            $subscription_data->ltdval_frequency = $product->ltdval_frequency;
            $subscription_data->ltdval_cycle = $product->ltdval_cycle;
            $subscription_data->refund_days = $product->refund_days;
            // $subscription_data->rating = $product->rating;
        }

        $data = [
            'slug' => 'subscription',
            'data' => $subscription_data,
            // 'product' => ProductModel::get($subscription_data->brand_id),
            'product' => $product,
            'data_tags' => SubscriptionModel::get_tags_arr($request->input('id')),
        ];

        return view('client/subscription/adapt/edit', $data);
    }

    public function accept(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => [
                'required',
                'integer',
                Rule::exists('subscriptions', 'id')->where(function ($query) {
                    $query->where('user_id', Auth::id());
                    $query->where('product_avail', 1);
                }),
            ],
            'category_id' => 'nullable|integer',
            // 'status' => 'required|numeric|digits_between:0,1',
            // 'type' => 'nullable|numeric|digits_between:0,9',
            // 'folder_id' => 'nullable|numeric',
            // 'tags' => 'required|numeric',
            // 'client_name' => 'required|string|max:255',
            // 'alert_id' => 'required|integer',
            'brand_id' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'price_type' => 'nullable|string|max:10',
            // 'discount_voucher' => 'nullable|string|max:20',
            // 'payment_mode' => 'required|string|max:' . len()->subscriptions->payment_mode,
            // 'expiry_date' => 'nullable|date_format:Y-m-d|after:today|after:payment_date|prohibited_if:type,3|' . $this->get_expiry_date_rule($request),
            // 'payment_mode_id' => 'required|integer',
            // 'payment_date' => 'nullable|date_format:Y-m-d',
            // 'next_payment_date' => 'nullable|date_format:Y-m-d',
            // 'recurring' => 'nullable|boolean',
            'note' => 'nullable|string|max:255',
            'include_notes' => 'nullable|boolean',
            // 'alert_type' => 'nullable|integer|in:1',
            'url' => 'nullable|string|max:255',
            // 'support_details' => 'nullable|string|max:255',
            // 'tags' => 'nullable|array|max:255',
            // 'refund_days' => 'nullable|integer|min:0|max:' . len()->subscriptions->refund_days,
            // 'refund_date' => 'nullable|date_format:Y-m-d',
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

        // Get subscription
        $subscription = SubscriptionModel::get($subscription_id);

        if (empty($subscription)) {
            return Response::json([
                'status' => false,
                'message' => __('Subscription not found'),
            ], 200);
        }

        // Get product information
        $product_data = $this->get_product_for_subscription($request->input('brand_id'), $request->input('type'));

        $data = [
            'product_avail' => 0,
            'brand_id' => $request->input('brand_id'),
            'favicon' => $request->input('favicon'),

            // 'user_id' => Auth::id(),
            // 'folder_id' => $request->input('folder_id'),
            // 'alert_id' => $request->input('alert_id'),
            'description' => $request->input('description'),
            'price' => (float) $request->input('price'),
            'price_type' => $request->input('price_type'),
            // 'discount_voucher' => $request->input('discount_voucher'),
            // 'payment_mode' => $request->input('payment_mode'),
            // 'expiry_date' => $request->input('expiry_date'),
            // 'payment_mode_id' => $request->input('payment_mode_id'),
            // 'recurring' => $request->input('recurring') ? 1 : 0,
            'note' => $request->input('note'),
            'include_notes' => $request->input('include_notes'),
            // 'alert_type' => $request->input('alert_type'),
            'url' => $request->input('url'),
            // 'support_details' => $request->input('support_details'),
            // 'tags' => $request->input('tags'),
            // 'refund_days' => $request->input('refund_days'),
            // 'refund_date' => $request->input('refund_date'),
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

                DB::table('user_ltd_cal')
                    ->where('subscription_id', $subscription_id)
                    ->update([
                        'date' => $data['payment_date'],
                    ]);
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
                    'event_types' => ['event', 'email', 'browser'],
                    'subscription_id' => $subscription->id,
                    'next_payment_date' => $next_payment_date,
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
}
