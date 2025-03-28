<?php

namespace App\Http\Controllers\Client\Subscription;

use App\Models\Marketplace;
use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Product;
use App\Models\ProductModel;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class MarketplaceController extends Controller
{
    public function edit(Request $request)
    {
        // Merge the request with the value from the route parameter and validate the request
        $request->merge(['id' => $request->route('id')]);
        $validator = Validator::make($request->all(), [
            'id' => [
                'required',
                'integer',
                // 'unique:subscription_cart,subscription_id',
                Rule::exists('subscriptions', 'id')->where(function ($query) {
                    $query->where('user_id', Auth::id());
                    $query->whereIn('type', [1, 3]);
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

        $marketplace = Marketplace::where('subscription_id', $request->input('id'))
            ->with('subscription', 'product', 'product_category')
            ->first();

        if (empty($marketplace->id)) {
            $subscription = Subscription::where('id', $request->input('id'))
                ->with('product', 'product_category', 'product_type')
                ->first();

            return view('client/subscription/marketplace/create', compact('subscription'));
        } else {
            return view('client/subscription/marketplace/update', compact('marketplace'));
        }
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subscription_id' => [
                'required',
                'integer',
                Rule::exists('subscriptions', 'id')->where(function ($query) {
                    $query->where('user_id', Auth::id());
                    $query->whereIn('type', [1, 3]);
                    $query->where('status', 1);
                }),
            ],
            'product_category_id' => 'nullable|integer|exists:product_categories,id',
            'product_platform_id' => 'nullable|integer|exists:product_platforms,id',
            // 'type' => 'nullable|integer|between:0,9',
            'status' => 'nullable|boolean',
            // 'product_id' => 'nullable|string|max:255',
            'product_description' => 'nullable|string|max:' . len()->subscription_cart->product_description,
            'sale_price' => 'required|numeric|min:1',
            'currency_code' => 'nullable|string|max:' . len()->subscription_cart->currency_code,
            'notes' => 'nullable|string|max:' . len()->subscription_cart->notes,
            'product_url' => 'nullable|string|max:' . len()->subscription_cart->product_url,
            'sales_url' => 'nullable|string|max:' . len()->subscription_cart->sales_url,
            'plan_name' => 'nullable|string|max:' . len()->subscription_cart->plan_name,
            'image' => 'sometimes|nullable|image',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
        }

        // Check tag limit
        // if ($this->is_tag_limit_reached($request)) {
        //     return lib()->do->get_limit_msg($request, 'tag');
        // }

        // Get subscription
        $subscription = Subscription::where('id', $request->input('subscription_id'))
            ->with('product')
            ->first();

        if (empty($subscription->id) || empty($subscription->product->id)) {
            return Response::json([
                'status' => false,
                'message' => __('Invalid subscription or product'),
            ]);
        }

        $marketplace = new Marketplace();
        $marketplace->user_id = Auth::id();
        $marketplace->subscription_id = $request->input('subscription_id');
        $marketplace->product_description = $request->input('product_description');
        $marketplace->sale_price = (float) $request->input('sale_price');
        $marketplace->currency_code = $request->input('currency_code');
        // $marketplace->currency_code = $subscription->price_type;
        $marketplace->notes = $request->input('notes');
        $marketplace->product_id = $subscription->product->id;
        // $marketplace->product_platform_id = $subscription->product->sub_platform;
        $marketplace->product_category_id = $request->input('product_category_id');
        $marketplace->product_platform_id = $request->input('product_platform_id');
        // $marketplace->product_type_id = $product->type_id;
        $marketplace->product_url = $request->input('product_url');
        $marketplace->sales_url = $request->input('sales_url');
        $marketplace->plan_name = $request->input('plan_name');
        $marketplace->product_name = $subscription->product_name;
        $marketplace->status = $request->input('status') ? 1 : 0;
        $marketplace->save();

        if ($request->hasFile('image')) {
            $image_path = File::add_get_path($request->file('image'), 'marketplace', $marketplace->id);
        } else {
            $image_path = $subscription->_image;
        }
        $marketplace->product_logo = $image_path;
        $marketplace->save();

        return Response::json([
            'status' => true,
            'message' => 'Success',
        ], 200);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:subscription_cart,id',
            'product_category_id' => 'nullable|integer|exists:product_categories,id',
            'product_platform_id' => 'nullable|integer|exists:product_platforms,id',
            // 'type' => 'nullable|integer|between:0,9',
            'status' => 'nullable|boolean',
            // 'product_id' => 'nullable|string|max:255',
            'product_description' => 'nullable|string|max:' . len()->subscription_cart->product_description,
            'sale_price' => 'required|numeric|min:1',
            'currency_code' => 'nullable|string|max:' . len()->subscription_cart->currency_code,
            'notes' => 'nullable|string|max:' . len()->subscription_cart->notes,
            'product_url' => 'nullable|string|max:' . len()->subscription_cart->product_url,
            'sales_url' => 'nullable|string|max:' . len()->subscription_cart->sales_url,
            'plan_name' => 'nullable|string|max:' . len()->subscription_cart->plan_name,
            'image' => 'sometimes|nullable|image',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
        }

        // Check tag limit
        // if ($this->is_tag_limit_reached($request)) {
        //     return lib()->do->get_limit_msg($request, 'tag');
        // }

        $marketplace = Marketplace::find($request->input('id'));

        // Get subscription
        $subscription = Subscription::where('id', $marketplace->subscription_id)
            ->with('product')
            ->first();

        if (empty($subscription->id) || empty($subscription->product->id)) {
            return Response::json([
                'status' => false,
                'message' => __('Invalid subscription or product'),
            ]);
        }

        $marketplace->product_description = $request->input('product_description');
        $marketplace->sale_price = (float) $request->input('sale_price');
        $marketplace->currency_code = $request->input('currency_code');
        // $marketplace->currency_code = $subscription->price_type;
        $marketplace->notes = $request->input('notes');
        // $marketplace->product_platform_id = $subscription->product->sub_platform;
        $marketplace->product_category_id = $request->input('product_category_id');
        $marketplace->product_platform_id = $request->input('product_platform_id');
        // $marketplace->product_type_id = $product->type_id;
        $marketplace->product_url = $request->input('product_url');
        $marketplace->sales_url = $request->input('sales_url');
        $marketplace->plan_name = $request->input('plan_name');
        $marketplace->product_name = $subscription->product_name;
        $marketplace->status = $request->input('status') ? 1 : 0;
        $marketplace->save();

        if ($request->hasFile('image')) {
            $marketplace->product_logo = File::add_get_path($request->file('image'), 'marketplace', $marketplace->id);
        }

        $marketplace->save();

        return Response::json([
            'status' => true,
            'message' => 'Success',
        ], 200);
    }
}
