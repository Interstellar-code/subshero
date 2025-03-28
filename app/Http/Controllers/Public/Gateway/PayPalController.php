<?php

namespace App\Http\Controllers\Public\Gateway;

use App\Http\Controllers\Public\Controller;
use App\Models\Marketplace;
use App\Models\MarketplaceOrder;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Srmklive\PayPal\Services\ExpressCheckout;

class PayPalController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->provider = new ExpressCheckout();
    }

    public function initiate()
    {
        if (empty($_SESSION['marketplace']->marketplace_order_id)) {
            if (empty($_SESSION['marketplace']->marketplace_token)) {
                abort(401);
            } else {
                return redirect()->route('app/marketplace/showcase', $_SESSION['marketplace']->marketplace_token);
            }
        }

        // Find the marketplace order
        $marketplace_order = MarketplaceOrder::find($_SESSION['marketplace']->marketplace_order_id);
        $seller = User::find($marketplace_order->seller_user_id);

        if (empty($marketplace_order->id) || empty($seller->id)) {
            abort(404);
        }

        // Generate order details
        $cart = [];
        $order_id = $marketplace_order->id;

        $cart['items'] = [
            [
                'name'  => $marketplace_order->product_name,
                'price' => $marketplace_order->sale_price,
                'qty'   => 1,
            ],
        ];

        $cart['return_url'] = route('app/gateway/paypal/return');
        $cart['invoice_id'] = config('paypal.invoice_prefix', 'INV-') . $order_id;
        $cart['invoice_description'] = "Order #$order_id Invoice";
        $cart['cancel_url'] = route('app/gateway/paypal/cancel');

        $total = 0;
        foreach ($cart['items'] as $item) {
            $total += $item['price'] * $item['qty'];
        }

        $cart['total'] = $total;


        // Add any custom options or data you want to use in the request to PayPal
        $this->setConfig($seller, $marketplace_order);

        // Send the purchase request to PayPal
        try {
            $response = $this->provider->setExpressCheckout($cart, false);

            // If the response was not successful, get the error message and return it
            if (empty($response['TOKEN']) && !empty($response['L_LONGMESSAGE0'])) {
                return redirect()->route('app/marketplace/showcase', $_SESSION['marketplace']->marketplace_token)->with('error', $response['L_LONGMESSAGE0']);
            }

            // Store the token in the database
            $marketplace_order->paypal_token = $response['TOKEN'];
            $marketplace_order->save();

            // $_SESSION['marketplace']->marketplace_order_id = null;

            // If the response was successful, redirect the customer to PayPal
            return view('public/gateway/paypal/initiate', [
                'redirect_url' => $response['paypal_link'],
            ]);
        } catch (\Exception $e) {
            $marketplace_order->status = 'failed';
            $marketplace_order->save();
            Log::error($e->getMessage());
            abort(500);
        }
    }

    public function notify()
    {
        $this->update();
        return response('OK', 200);
    }

    public function return()
    {
        $marketplace_order = $this->update();

        if (!empty($marketplace_order->id)) {
            return view('public/gateway/paypal/thankyou', compact('marketplace_order'));
        } else if (!empty($_SESSION['marketplace']->marketplace_token)) {
            return redirect()->route('app/marketplace/showcase', $_SESSION['marketplace']->marketplace_token)->with('error', 'Your payment was not successful. Please try again.');
        }

        return response('OK', 200);
    }

    public function cancel()
    {
        $this->update();

        if (!empty($_SESSION['marketplace']->marketplace_token)) {
            return redirect()->route('app/marketplace/showcase', $_SESSION['marketplace']->marketplace_token)->with('error', 'Your payment was not successful. Please try again.');
        }

        return response('OK', 200);
    }

    private function update()
    {
        // dd($_REQUEST);
        if (isset($_REQUEST['token'])) {
            $paypal_token = $_REQUEST['token'];
            $marketplace_order = MarketplaceOrder::where('paypal_token', $paypal_token)->with('marketplace_item')->first();

            if (empty($marketplace_order->id)) {
                return null;
            }

            $seller = User::find($marketplace_order->seller_user_id);
            if (empty($seller->id)) {
                return null;
            }

            $this->setConfig($seller, $marketplace_order);
            $response = $this->provider->getExpressCheckoutDetails($paypal_token);


            // Generate order details
            $order_id = $marketplace_order->id;
            $response['items'] = [
                [
                    'name'  => $marketplace_order->product_name,
                    'price' => $marketplace_order->sale_price,
                    'qty'   => 1,
                ],
            ];
            $response['return_url'] = route('app/gateway/paypal/return');
            $response['invoice_id'] = config('paypal.invoice_prefix', 'INV-') . $order_id;
            $response['invoice_description'] = "Order #$order_id Invoice";
            $response['cancel_url'] = route('app/gateway/paypal/cancel');

            $response['total'] = 0;
            foreach ($response['items'] as $item) {
                $response['total'] += $item['price'] * $item['qty'];
            }


            // Check if the payment was successful
            if (isset($response['ACK']) && in_array(strtoupper($response['ACK']), ['SUCCESS', 'SUCCESSWITHWARNING'])) {
                // Perform transaction on PayPal
                $payment_status = $this->provider->doExpressCheckoutPayment($response, $response['TOKEN'] ?? null, $response['PAYERID'] ?? null);

                // Check if the payment was successful
                if (isset($payment_status['ACK']) && in_array(strtoupper($payment_status['ACK']), ['SUCCESS', 'SUCCESSWITHWARNING'])) {
                    $marketplace_order->status = 'completed';
                    $marketplace_order->save();

                    // Mark marketplace item as sold
                    Marketplace::where('id', $marketplace_order->marketplace_item_id)
                        ->update([
                            'status' => 2,
                        ]);

                    // Mark subscription as sold
                    Subscription::where('id', $marketplace_order->subscription_id)
                        ->update([
                            'status' => 5,
                        ]);

                    return $marketplace_order;
                } else {
                    $marketplace_order->status = 'failed';
                    $marketplace_order->save();
                }
            } else {
                $marketplace_order->status = 'failed';
                $marketplace_order->save();
            }
        }

        return null;
    }

    private function setConfig(User $seller, MarketplaceOrder $marketplace_order)
    {
        // Load configuration
        $config = [
            'mode'    => lib()->config->paypal_environment ? 'live' : 'sandbox',
            'sandbox' => [
                'username'    => $seller->paypal_api_username,
                'password'    => $seller->paypal_api_password,
                'secret'      => $seller->paypal_api_secret,
                'certificate' => env('PAYPAL_SANDBOX_API_CERTIFICATE', ''),
                'app_id'      => config('paypal.sandbox.app_id', 'APP-48W726017P516486T'),
            ],
            'live' => [
                'username'    => $seller->paypal_api_username,
                'password'    => $seller->paypal_api_password,
                'secret'      => $seller->paypal_api_secret,
                'certificate' => env('PAYPAL_LIVE_API_CERTIFICATE', ''),
                'app_id'      => config('paypal.sandbox.app_id', 'APP-48W726017P516486T'),
            ],

            'payment_action' => 'Sale',
            // 'currency'       => env('PAYPAL_CURRENCY', 'USD'),
            'currency'       => $marketplace_order->currency_code,
            'billing_type'   => 'MerchantInitiatedBilling',
            'notify_url'     => route('app/gateway/paypal/notify'),
            'locale'         => 'en_US',
            'validate_ssl'   => true,
        ];

        $this->provider->setApiCredentials($config);
    }
}
