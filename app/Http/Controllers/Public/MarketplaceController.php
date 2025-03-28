<?php

namespace App\Http\Controllers\Public;

use App\Models\Marketplace;
// use App\Http\Controllers\Controller;
use App\Models\MarketplaceOrder;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use stdClass;

class MarketplaceController extends Controller
{
    // List of variables that are used in the session
    private const variables = [
        'seller_user_id',
        'buyer_user_id',
        'marketplace_token',
        'marketplace_item_id',
        'marketplace_order_id',
    ];


    public function __construct()
    {
        parent::__construct();

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['marketplace'])) {
            $_SESSION['marketplace'] = new stdClass();
        }

        // Check if the session variables are set
        foreach (self::variables as $key => $value) {
            if (!isset($_SESSION['marketplace']->$value)) {
                $_SESSION['marketplace']->$value = null;
            }
        }
    }

    public function showcase(Request $request)
    {
        // Merge the request with the value from the route parameter and validate the request
        $request->merge(['marketplace_token' => $request->route('marketplace_token')]);
        $validator = Validator::make($request->all(), [
            'marketplace_token' => [
                'required',
                'string',
                Rule::exists('users', 'marketplace_token')->where(function ($query) {
                    $query->where('marketplace_status', 1);
                }),
            ],
        ]);

        if ($validator->fails()) {
            abort(404);
        }

        // Check if the user visiting another marketplace
        if (!empty($_SESSION['marketplace']->marketplace_token) && $_SESSION['marketplace']->marketplace_token != $request->input('marketplace_token')) {
            // Reset the session variables
            foreach (self::variables as $key => $value) {
                $_SESSION['marketplace']->$value = null;
            }
        }

        // Get the seller user
        $seller = User::where('marketplace_token', $request->input('marketplace_token'))->first();
        $_SESSION['marketplace']->seller_user_id = $seller->id;
        $_SESSION['marketplace']->marketplace_token = $request->input('marketplace_token');
        $_SESSION['marketplace']->buyer_user_id = Auth::id();

        // Get all products from the seller
        $marketplace_items = Marketplace::where('subscription_cart.user_id', $seller->id)
            ->where('subscription_cart.status', 1)
            ->with('user', 'subscription', 'product_category', 'product_platform')
            ->get();

        return view('public/marketplace/showcase', compact('seller', 'marketplace_items'));
    }

    public function buy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => [
                'required',
                'integer',

                // Check if the id exists and is active and marketplace_token is valid
                Rule::exists('subscription_cart', 'subscription_cart.id')
                    ->where(function ($query) {
                        $query->whereIn('user_id', function ($query) {
                            $query->select('id')
                                ->from('users')
                                ->where('marketplace_status', 1)
                                ->where('marketplace_token', $_SESSION['marketplace']->marketplace_token);
                        });
                        $query->where('subscription_cart.status', 1);
                    }),
            ],
        ]);

        if ($validator->fails()) {
            abort(404);
        }

        $_SESSION['marketplace']->marketplace_item_id = $request->input('id');

        return Response::json([
            'status' => true,
            'message' => 'Success',
        ], 200);
    }

    public function checkout_show(Request $request)
    {
        $marketplace_item = Marketplace::find($_SESSION['marketplace']->marketplace_item_id);
        $payment_methods = [];
        $charges = [];

        if (empty($marketplace_item)) {
            if (empty($_SESSION['marketplace']->marketplace_token)) {
                abort(404);
            } else {
                return redirect()->route('app/marketplace/showcase', $_SESSION['marketplace']->marketplace_token);
            }
        }

        // Get the buyer user
        $buyer = User::find($_SESSION['marketplace']->buyer_user_id);

        // Load payment methods
        $payment_methods = [
            'paypal' => (object) [
                'id' => 'paypal',
                'name' => 'PayPal',
                'title' => 'Pay with PayPal',
                'image' => 'assets/images/payment/paypal.png',
                'description' => 'PayPal is the faster, safer way to send money, make an online payment, receive money or set up a merchant account.',
            ],
        ];

        // Load charges
        $charges = (object) [
            'subtotal' => 0,
            'tax' => 0,
            'total' => 0,
        ];

        // Calculate charges
        $charges->subtotal = $marketplace_item->sale_price;
        $charges->total = $charges->subtotal + $charges->tax;

        return view('public/marketplace/checkout', compact('marketplace_item', 'buyer', 'payment_methods', 'charges'));
    }


    public function checkout_save(Request $request)
    {
        $marketplace_item = Marketplace::find($_SESSION['marketplace']->marketplace_item_id);
        $seller = User::find($_SESSION['marketplace']->seller_user_id);

        $validator = Validator::make($request->all(), [
            'buyer_first_name' => 'required|string|max:' . len()->marketplace_orders->buyer_first_name,
            'buyer_last_name' => 'required|string|max:' . len()->marketplace_orders->buyer_last_name,
            'buyer_email' => 'required|string|max:' . len()->marketplace_orders->buyer_email . '|not_in:' . (empty($seller->id) ? null : $seller->email),
            'buyer_phone' => 'required|string|max:' . len()->marketplace_orders->buyer_phone,
            'buyer_company_name' => 'nullable|string|max:' . len()->marketplace_orders->buyer_company_name,
            'buyer_country' => 'required|string|max:' . len()->marketplace_orders->buyer_country,
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
        }

        // Create the order
        $marketplace_order = new MarketplaceOrder();
        $marketplace_order->marketplace_item_id = $marketplace_item->id;
        $marketplace_order->seller_user_id = $_SESSION['marketplace']->seller_user_id;
        $marketplace_order->seller_paypal_api_username = $seller->paypal_api_username;
        $marketplace_order->buyer_first_name = $request->input('buyer_first_name');
        $marketplace_order->buyer_last_name = $request->input('buyer_last_name');
        $marketplace_order->buyer_email = $request->input('buyer_email');
        $marketplace_order->buyer_phone = $request->input('buyer_phone');
        $marketplace_order->buyer_company_name = $request->input('buyer_company_name');
        $marketplace_order->buyer_country = $request->input('buyer_country');
        $marketplace_order->subscription_id = $marketplace_item->subscription_id;
        $marketplace_order->product_id = $marketplace_item->product_id;
        $marketplace_order->product_name = $marketplace_item->product_name;
        $marketplace_order->product_logo = $marketplace_item->_product_logo;
        $marketplace_order->sale_price = $marketplace_item->sale_price;
        $marketplace_order->currency_code = $marketplace_item->currency_code;
        $marketplace_order->subtotal = $marketplace_item->sale_price;
        $marketplace_order->charges = 0;
        $marketplace_order->total = $marketplace_order->subtotal;
        $marketplace_order->status = 'pending';
        $marketplace_order->payment_method = 'paypal';
        $marketplace_order->save();

        $_SESSION['marketplace']->marketplace_order_id = $marketplace_order->id;

        // return redirect()->route('app/gateway/paypal/initiate');

        return Response::json([
            'status' => true,
            'message' => 'Success',
            'redirect' => route('app/gateway/paypal/initiate'),
        ], 200);
    }
}
