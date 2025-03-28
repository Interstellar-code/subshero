<?php

namespace App\Http\Controllers\Client\Subscription;

use App\Http\Controllers\Controller;
use App\Library\NotificationEngine;
use App\Models\FolderModel;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\SubscriptionModel;
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
use Smalot\PdfParser\Parser;
use App\Models\ProductPlatform;
use Illuminate\Validation\ValidationException;

class PDFImportController extends Controller
{
    private $text = '';
    private $lines = [];
    private $platform = '';
    private $platform_array = [];

    // List of Platforms
    private $platform_list = [
        'app_sumo' => 'Appsumo',
        'saas_mantra' => 'SaaSmantra',
        'pitch_ground' => 'PitchGround',
        'martech_fb_group' => 'Martech FB Group',
        'dealify' => 'Dealify',
        'dealmirror' => 'Dealmirror',
        'ltd_hunt' => 'LTDhunt',
        'digital_think' => 'DigitalThink',
        'stack_social' => 'StackSocial',
        'saas_wiz' => 'SaaSwiz',
        'product_hunt' => 'Product hunt',
        'own_site' => 'Own Site',
        'online_marketplace' => 'Online Marketplace',
        'no_ltd' => 'No LTD',
        'bootstrapps' => 'Bootstrapps',
        'deal_mango' => 'Dealmango',
        'grab_ltd' => 'Grabltd',
        'envato' => 'Envato',
        'digital_launchpad' => 'Digitallaunchpad',
        'lifetimo' => 'Lifetimo',
        'deal_fuel' => 'Dealfuel',
        'get_deal' => 'Getdeal',
        'startup' => 'Startup',
    ];

    private $struct = [
        // products table columns
        'product_array' => [
            'id' => 1,
            'admin_id' => 0,
            'category_id' => 0,
            'product_name' => '',
            'brandname' => '',
            'product_type' => 0,
            'description' => '',
            'featured' => 0,
            'rating' => 0,
            'pop_factor' => 0,
            'url' => '',
            'url_app' => '',
            'image' => '',
            'favicon' => '',
            'status' => 0,
            'sub_ltd' => 0,
            'launch_year' => 0,
            'sub_platform' => 0,
            'pricing_type' => 3,
            'currency_code' => 'USD',
            'price1_name' => '',
            'price1_value' => 0.0,
            'price2_name' => '',
            'price2_value' => 0.0,
            'price3_name' => '',
            'price3_value' => 0.0,
            'refund_days' => null,
            'billing_frequency' => 0,
            'billing_cycle' => 0,
            'ltdval_price' => 0.0,
            'ltdval_frequency' => 0,
            'ltdval_cycle' => 0,
            'created_at' => '',
            'created_by' => 0,
            'updated_at' => '',
        ],

        // subscriptions table columns
        'subscription_array' => [
            'id' => 0,
            'user_id' => 0,
            'folder_id' => 0,
            'brand_id' => 0,
            'category_id' => 0,
            'alert_id' => 0,
            'platform_id' => 0,
            'type' => 0,
            'image' => '',
            'favicon' => '',
            'product_name' => '',
            'brandname' => '',
            'product_type' => 0,
            'description' => '',
            'price' => 0.0,
            'price_type' => 0,
            'recurring' => 0,
            'payment_date' => '',
            'next_payment_date' => '',
            'payment_date_upd' => '',
            'expiry_date' => '',
            'contract_expiry' => '',
            'homepage' => '',
            'pay_gateway_id' => 0,
            'note' => '',
            'company_name' => '',
            'discount_voucher' => '',
            'payment_mode' => '',
            'payment_mode_id' => 0,
            'include_notes' => 0,
            'alert_type' => 0,
            'url' => '',
            'support_details' => '',
            'tags' => '',
            'billing_frequency' => 0,
            'billing_cycle' => 0,
            'billing_type' => 1,
            'ltdval_price' => 0.0,
            'ltdval_frequency' => 0,
            'ltdval_cycle' => 0,
            'status' => 0,
            'pricing_type' => 1,
            'timezone' => '',
            'currency_code' => 'USD',
            'refund_days' => 0,
            'refund_date' => '',
            'base_value' => 0.0,
            'base_currency' => '',
            'rating' => 0,
            'sub_addon' => 0,
            'sub_id' => 0,
            'product_avail' => 0,
            'product_submission_id' => 0,
            'created_at' => '',
            'created_by' => 0,
        ],

        // product_platforms table columns
        'platform_array' => [
            'id' => 0,
            'name' => '',
            'created_at' => '',
            'updated_at' => '',
        ],

    ];

    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
    }

    public function import(Request $request)
    {
        // Validate the request
        $fields = $request->validate([
            'file' => 'required|file|mimetypes:application/pdf',
        ]);

        // Parse the PDF
        $parser = (new Parser())->parseFile($fields['file']->getPathname());
        $this->text = $parser->getText();
        $this->parse_metadata();
        $platform_name = $this->parse_platform_name();
        $output = [];

        // Check if the PDF is parsed successfully and the platform is supported
        if ($platform_name == 'app_sumo') {
            $all_subscription = $this->parse_app_sumo_multiple_subscription_array_method_1();
            $output = [
                'all_subscription' => json_decode(json_encode($all_subscription), FALSE),
            ];
        }

        // Check if any subscription is found
        if (empty($output['all_subscription'])) {
            throw ValidationException::withMessages(['No subscription found']);
        } else {
            return Response::json([
                'status' => true,
                'message' => __('Success'),
                'content' => view('client/subscription/pdf/table', $output)->render(),
            ]);
        }

        // Throw error if the PDF is not supported
        throw ValidationException::withMessages(['Failed to parse PDF']);
    }

    public function save(Request $request)
    {
        // Check counter
        $validator = Validator::make($request->all(), [
            'count' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
        } else {
            $count = $request->input('count');
        }

        // Form validation
        for ($i = 0; $i < $count; $i++) {

            // Validate all the fields
            $validator = Validator::make($request->all(), [
                'status' . "_$i" => 'nullable|boolean',
                'type' . "_$i" => 'required|numeric|digits_between:0,9',
                'folder_id' . "_$i" => 'nullable|string|max:255',
                'category_id' . "_$i" => 'nullable|integer',
                'alert_id' . "_$i" => 'required|integer',
                // 'tags' . "_$i" => 'required|numeric',
                // 'client_name' . "_$i" => 'required|string|max:255',
                'brand_id' . "_$i" => 'required|string|max:255',
                'description' . "_$i" => 'nullable|string|max:255',
                'price' . "_$i" => 'required|numeric|min:0',
                'price_type' . "_$i" => 'required|string|max:10',
                'discount_voucher' . "_$i" => 'nullable|string|max:20',
                // 'payment_mode' . "_$i" => 'required|string|max:' . len()->subscriptions->payment_mode,
                'payment_mode_id' . "_$i" => 'required|integer',
                'payment_date' . "_$i" => 'required|date_format:Y-m-d',
                // 'expiry_date' . "_$i" => 'nullable|date_format:Y-m-d|after:today|after:payment_date|prohibited_if:type,3|' . $this->get_expiry_date_rule($request),
                'recurring' . "_$i" => 'nullable|boolean',
                'note' . "_$i" => 'nullable|string|max:255',
                'include_notes' . "_$i" => 'nullable|boolean',
                'alert_type' . "_$i" => 'nullable|integer|in:1',
                'url' . "_$i" => 'nullable|string|max:255',
                'support_details' . "_$i" => 'nullable|string|max:255',
                'tags' . "_$i" => 'nullable|array|max:255',
                'refund_days' . "_$i" => 'nullable|integer|min:0|max:' . len()->subscriptions->refund_days,
                'billing_frequency' . "_$i" => 'nullable|numeric|digits_between:0,40',
                'billing_cycle' . "_$i" => 'nullable|numeric|digits_between:0,9',
                'billing_type' . "_$i" => 'nullable|integer|in:2',
                'image' . "_$i" => 'nullable|string',
                'favicon' . "_$i" => 'nullable|string',
                'rating' . "_$i" => 'nullable|integer',

                'ltdval_price' . "_$i" => 'nullable|numeric|min:0',
                'ltdval_cycle' . "_$i" => 'nullable|numeric|digits_between:0,9',
                'ltdval_frequency' . "_$i" => 'nullable|numeric|digits_between:0,40',

                // Lifetime addon
                'sub_addon' . "_$i" => 'nullable|boolean',
                'sub_id' . "_$i" => [
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
            }
        }

        // Check limit
        if (UserModel::limit_reached('subscription')) {
            return lib()->do->get_limit_msg($request, 'subscription');
        }

        for ($i = 0; $i < $count; $i++) {
            $data = [
                'user_id' => Auth::id(),
                'status' => $request->input('status' . "_$i") ? 1 : 0,
                'type' => $request->input('type' . "_$i"),
                'folder_id' => $request->input('folder_id' . "_$i"),
                'brand_id' => $request->input('brand_id' . "_$i"),
                'category_id' => $request->input('category_id' . "_$i"),
                'alert_id' => $request->input('alert_id' . "_$i"),
                'product_name' => $request->input('product_name' . "_$i"),
                'image' => $request->input('image' . "_$i"),
                'favicon' => $request->input('favicon' . "_$i"),
                'brandname' => $request->input('brandname' . "_$i"),
                'product_type' => $request->input('product_type' . "_$i"),
                'pricing_type' => $request->input('pricing_type' . "_$i"),
                'currency_code' => $request->input('currency_code' . "_$i"),
                'description' => $request->input('description' . "_$i"),
                'price' => (float) $request->input('price' . "_$i"),
                'price_type' => $request->input('price_type' . "_$i"),
                'discount_voucher' => $request->input('discount_voucher' . "_$i"),
                'expiry_date' => $request->input('expiry_date' . "_$i"),
                // 'payment_mode' => $request->input('payment_mode' . "_$i"),
                'payment_mode_id' => $request->input('payment_mode_id' . "_$i"),
                'payment_date' => $request->input('payment_date' . "_$i"),
                'payment_date_upd' => $request->input('payment_date' . "_$i"),
                'recurring' => $request->input('recurring' . "_$i") ? 1 : 0,
                'note' => $request->input('note' . "_$i"),
                'include_notes' => $request->input('include_notes' . "_$i"),
                'alert_type' => $request->input('alert_type' . "_$i"),
                'url' => $request->input('url' . "_$i"),
                'support_details' => $request->input('support_details' . "_$i"),
                // 'tags' => $request->input('tags' . "_$i"),
                'refund_days' => $request->input('refund_days' . "_$i"),
                'billing_frequency' => $request->input('billing_frequency' . "_$i"),
                'billing_cycle' => $request->input('billing_cycle' . "_$i"),
                'billing_type' => $request->input('billing_type' . "_$i") ?? 1,
                'timezone' => lib()->user->get_timezone(),
                'base_value' => lib()->do->currency_convert($request->input('price' . "_$i"), $request->input('price_type' . "_$i")),
                'base_currency' => APP_CURRENCY,
                'rating' => $request->input('rating' . "_$i"),

                'ltdval_price' => (float) $request->input('ltdval_price' . "_$i"),
                'ltdval_cycle' => $request->input('ltdval_cycle' . "_$i"),
                'ltdval_frequency' => $request->input('ltdval_frequency' . "_$i"),
            ];

            // Check for empty data
            if (empty($data['billing_frequency'])) {
                $data['billing_frequency'] = 1;
            }
            if (empty($data['billing_cycle'])) {
                $data['billing_cycle'] = 1;
            }

            // Lifetime addon
            if ($request->has('sub_id' . "_$i")) {
                $data['sub_addon'] = 1;
                $data['sub_id'] = $request->input('sub_id' . "_$i");
            }

            if (!empty($request->input('refund_days' . "_$i")) && $request->input('refund_days' . "_$i") > 0) {
                $data['refund_date'] = date('Y-m-d', strtotime($request->input('payment_date' . "_$i") . ' +' . $request->input('refund_days' . "_$i") . ' days'));
            }

            $subscription_id = SubscriptionModel::create($data);

            // Add event logs
            $this->add_event([
                'table_row_id' => $subscription_id,
                'event_type_status' => 'create',
                'event_product_id' => $request->input('brand_id' . "_$i"),
                'event_type_schedule' => $request->input('recurring' . "_$i") ? 2 : 1,
            ]);

            // Check if active
            if ($request->input('status' . "_$i") == 1) {

                // Add events
                SubscriptionModel::set_refund_date($subscription_id);

                // Add history
                SubscriptionModel::create_new_history($subscription_id);
            }

            // Send webhook event
            Webhook::send_event('subscription.created', $subscription_id);
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

    private function parse_metadata()
    {
        // Remove spaces from all lines
        $this->text = implode("\n", array_map('trim', explode("\n", $this->text)));

        // Remove blank lines
        $this->text = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $this->text);

        // Split lines
        $this->lines = explode("\n", $this->text);

        if (empty($this->lines) || count($this->lines) < 10) return;

        // Check if there are unnecessary spaces
        if (str_contains($this->lines[0], 'In vo ic e')) {
            // Remove unnecessary spaces: In vo ic e
            $this->text = str_replace('  ', "\t", $this->text);
            $this->text = str_replace(' ', '', $this->text);
            $this->text = str_replace("\t", ' ', $this->text);

            // Split lines
            $this->lines = explode("\n", $this->text);
        }
    }

    private function parse_platform_name()
    {
        $result = null;

        // Search for AppSumo platform
        if (empty($result)) {
            foreach ($this->lines as $key => $line) {
                if (
                    str_contains($line, 'Invoice Details') &&
                    isset($this->lines[$key + 1]) &&
                    str_contains($this->lines[$key + 1], 'AppSumo Invoice')
                ) {
                    $result = 'app_sumo';
                    break;
                }
            }
        }

        // Search for PitchGround platform
        if (empty($result)) {
            foreach ($this->lines as $key => $line) {
                if (
                    str_contains($line, 'Invoice from') &&
                    isset($this->lines[$key + 1]) &&
                    str_contains($this->lines[$key + 1], 'PitchGround dba')
                ) {
                    $result = 'pitch_ground';
                    break;
                }
            }
        }

        // Search for SaaS Mantra platform
        if (empty($result)) {
            foreach ($this->lines as $key => $line) {
                if (
                    str_contains($line, 'Provided by') &&
                    isset($this->lines[$key + 1]) &&
                    str_contains($this->lines[$key + 1], 'SaaS Mantra')
                ) {
                    $result = 'saas_mantra';
                    break;
                }
            }
        }

        // Search the platform name in the database
        if (empty($result)) {
            $this->platform_array = $this->struct['platform_array'];
        } else {
            $this->platform = $result;
            $platform_name = $result;

            if (isset($this->platform_list[$result])) {
                $platform_name = $this->platform_list[$result];
            }
            $this->platform_array = ProductPlatform::where('name', 'like', '%' . $platform_name . '%')->first();
            if (empty($this->platform_array)) {
                $this->platform_array = $this->struct['platform_array'];
            }
        }

        return $result;
    }

    private function parse_app_sumo_multiple_subscription_array_method_1()
    {
        $result = [];
        $line_position = 0;
        $payment_method = null;
        $payment_date = null;
        $first_product = true;

        // Count number of products in the invoice
        $item_count = count(array_filter($this->lines, function ($val) {
            return $val == 'Total';
        }));


        // Order summary
        $order_summary_line_number = 0;
        foreach ($this->lines as $key => $line) {
            if (
                str_contains($line, 'Order summary')
            ) {
                $order_summary_line_number = $key;
                break;
            }
        }

        if ($order_summary_line_number <= 0) return $result;
        $line_position = $order_summary_line_number;

        // Parse payment method
        $payment_method_line_number = 0;
        foreach ($this->lines as $key => $line) {
            if (
                str_contains($line, 'Payment type:')
            ) {
                $payment_method_line_number = $key;
                break;
            }
        }
        if (
            $payment_method_line_number > 0
        ) {
            $payment_method_line_text = $this->lines[$payment_method_line_number];
            $payment_method_line_array = explode(':', $payment_method_line_text);

            if (isset($payment_method_line_array[1])) {
                $payment_method_text = trim($payment_method_line_array[1]);
                $subscription['payment_mode'] = $payment_method_text;

                // Search for this payment method in the database
                $payment_method = PaymentMethod::where('user_id', Auth::user()->id)
                    ->where('name', $payment_method_text)->first();
            }
        }


        // Parse payment date
        $payment_date_line_number = 0;
        foreach ($this->lines as $key => $line) {
            if (
                str_contains($line, 'Date:')
            ) {
                $payment_date_line_number = $key;
                break;
            }
        }
        if (
            $payment_date_line_number > 0
        ) {
            $payment_date_line_text = $this->lines[$payment_date_line_number];
            $payment_date_line_array = explode(':', $payment_date_line_text);

            if (isset($payment_date_line_array[1])) {
                $payment_date_text = trim($payment_date_line_array[1]);
                $payment_date = date('Y-m-d', strtotime($payment_date_text));
            }
        }
        if (empty($payment_date)) {
            $payment_date = date('Y-m-d');
        }

        // Parse each product
        for ($counter = 0; $counter < $item_count; $counter++) {
            if ($first_product) {
                $first_product = false;

                // Parse product name
                if (
                    $line_position > 0 &&
                    isset($this->lines[$line_position + 1]) &&
                    str_contains($this->lines[$line_position + 1], 'Product') &&
                    isset($this->lines[$line_position + 2]) &&
                    str_contains($this->lines[$line_position + 2], 'Details') &&
                    isset($this->lines[$line_position + 3])
                ) {
                    // $product['product_name'] = $this->lines[$order_summary_line_number + 3];
                    $product_name = $this->lines[$line_position + 3];

                    // Search for this product in the database
                    $product_info = Product::where('product_name', 'like', '%' . $product_name . '%')
                        ->where('id', '>', PRODUCT_RESERVED_ID)
                        ->where('status', 1)
                        ->first();

                    // Check if the product is already in the database
                    if (empty($product_info->id)) {
                        $product = $this->struct['product_array'];
                        $product['product_name'] = $product_name;
                    } else {
                        $product = $product_info->toArray();
                        $product['image'] = $product_info->_image;
                        $product['favicon'] = $product_info->_favicon;
                    }
                }
            } else {

                // Parse product name
                if (
                    $line_position > 0 &&
                    str_contains($this->lines[$line_position], 'Total') &&
                    isset($this->lines[$line_position + 2])
                ) {
                    $product_name = $this->lines[$line_position + 2];
                    $line_position += 2;

                    // Search for this product in the database
                    $product_info = Product::where('product_name', 'like', '%' . $product_name . '%')
                        ->where('id', '>', PRODUCT_RESERVED_ID)
                        ->where('status', 1)
                        ->first();

                    // Check if the product is already in the database
                    if (empty($product_info->id)) {
                        $product = $this->struct['product_array'];
                        $product['product_name'] = $product_name;
                    } else {
                        $product = $product_info->toArray();
                        $product['image'] = $product_info->_image;
                        $product['favicon'] = $product_info->_favicon;
                    }
                }
            }

            if (empty($product)) return $result;

            // Set subscription data
            $subscription = $this->struct['subscription_array'];
            $subscription['user_id'] = Auth::user()->id;
            $subscription['folder_id'] = FolderModel::get_default_folder_id();
            $subscription['brand_id'] = $product['id'];
            $subscription['product_name'] = $product['product_name'];
            $subscription['brandname'] = $product['brandname'];
            $subscription['product_type'] = $product['product_type'];
            $subscription['platform_id'] = $product['sub_platform'];
            $subscription['image'] = $product['image'];
            $subscription['favicon'] = $product['favicon'];


            // Parse product price
            $total_line_number = 0;
            for ($i = $line_position; $i < count($this->lines); $i++) {
                if (
                    str_contains($this->lines[$i], 'Total')
                ) {
                    $total_line_number = $i;
                    break;
                }
            }
            if (
                $total_line_number > 0 &&
                isset($this->lines[$total_line_number + 1])
            ) {
                $line_position = $total_line_number;

                $price_str = $this->lines[$total_line_number + 1];
                $price_val = floatval(preg_replace('/[^0-9.]/', '', $price_str));

                $product['price1_value'] = $price_val;
            }


            // Set subscription data
            $subscription['platform_id'] = $this->platform_array['id'];
            $subscription['type'] = $product['pricing_type'];
            $subscription['description'] = $product['description'];
            $subscription['price'] = $product['price1_value'];
            $subscription['price_type'] = $product['currency_code'];
            $subscription['recurring'] = $subscription['type'] == 1 ? 1 : 0;
            $subscription['payment_date'] = $payment_date;
            // $subscription['payment_mode_id'] = $product['currency_code'];
            $subscription['billing_frequency'] = $product['billing_frequency'];
            $subscription['billing_cycle'] = $product['billing_cycle'];
            $subscription['ltdval_price'] = $product['ltdval_price'];
            $subscription['ltdval_frequency'] = $product['ltdval_frequency'];
            $subscription['ltdval_cycle'] = $product['ltdval_cycle'];
            $subscription['pricing_type'] = $product['pricing_type'];
            $subscription['timezone'] = lib()->user->get_timezone();
            $subscription['currency_code'] = $product['currency_code'];
            $subscription['refund_days'] = $product['refund_days'];

            if (!empty($payment_method->id)) {
                $subscription['payment_mode_id'] = $payment_method->id;
            }

            $result[] = $subscription;
            unset(
                $subscription,
                $product,
            );
        }

        // Return subscription data
        return $result;
    }
}
