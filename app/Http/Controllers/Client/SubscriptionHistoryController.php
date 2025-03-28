<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use App\Models\SubscriptionHistory;
use App\Models\Subscription;

class SubscriptionHistoryController extends Controller
{
    function get(Request $request, $subscription_id)
    {

        $request_route = [
            'subscription_id' => $subscription_id,
        ];

        $validator = Validator::make($request_route, [
            'subscription_id' => [
                'required',
                'integer',
                Rule::exists('subscriptions_history', 'subscription_id')->where(function ($query) {
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
        $subscription_history = SubscriptionHistory::select('subscriptions_history.id', 'next_payment_date as payment_date', 'price', 'payment_mode as payment_method', 'users_payment_methods.payment_type as payment_method_external')
            ->join('users_payment_methods', 'users_payment_methods.id', '=', 'subscriptions_history.payment_mode_id')
            ->where('subscription_id', $subscription_id)->get();
        foreach ($subscription_history as &$subscription_history_item) {
            if(!$subscription_history_item->payment_method) {
                $subscription_history_item->payment_method = $subscription_history_item->payment_method_external ?? 'Others';
                unset($subscription_history_item->payment_method_external);
            }
            $subscription_history_item->action = 1;
        }
        $payment_methods = SubscriptionHistory::select('payment_mode as payment_method')
            ->distinct()
            ->orderBy('payment_method', 'asc')
            ->get()
            ->pluck('payment_method');
        $subscription = Subscription::find($subscription_id);
        $default_payment_method = $subscription->payment_mode ?? "Others";
        $default_payment_method = array_search($default_payment_method, $payment_methods->toArray());
        $result = [
            'subscription_history' => $subscription_history,
            'payment_methods' => $payment_methods,
            'subscription_image' => $subscription->image,
            'product_name' => $subscription->product_name,
            'currency' => $subscription->currency_code ?? $subscription->base_currency,
            'defaults' => [
                'payment_date' => date('M d Y'),
                'price' => $subscription->price,
                'payment_method' => $default_payment_method,
            ],
        ];

        return Response::json($result);
    }

    function update(Request $request)
    {
        $rules = [
            'payment_method' => [
                'nullable',
                'string',
                Rule::exists('subscriptions_history', 'payment_mode'),
            ],
            'price' => [
                'nullable',
                'string',
                'regex:/^\d+(\.\d{1,2})?( [A-Z]{1,4})?$/',
            ],
            'payment_date' => [
                'nullable',
                'date',
            ],
            'subscription_id' => [
                'nullable',
                'integer',
                Rule::exists('subscriptions', 'id')->where(function ($query) {
                    $query->where('user_id', Auth::id());
                }),
            ],
        ];
        if ($request->id) {
            $rules['id'] = [
                'required',
                'integer',
                Rule::exists('subscriptions_history', 'id')->where(function ($query) {
                    $query->where('user_id', Auth::id());
                }),
            ];
        }
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
            // abort(419);
        }

        $subscription_history_item = $this->update_one_history_item($request);

        $subscription = Subscription::find($subscription_history_item->subscription_id);
        $subscription->actualize_next_payment_date();

        return Response::json([
            'status' => true,
            'message' => 'Subscription history item updated successfully',
            'id' => $subscription_history_item->id,
        ]);
    }

    function update_one_history_item($item)
    {
        if ($item['id'] ?? false) {
            $subscription_history_item = SubscriptionHistory::find($item['id']);
        } else {
            $subscription_history_item = new SubscriptionHistory;
            if ($item['subscription_id'] ?? false) {
                $subscription_history_item->subscription_id = $item['subscription_id'];
            }
            $subscription_history_item->user_id = Auth::id();
        }
        if ($item['payment_method'] ?? false) {
            $subscription_history_item->payment_mode = $item['payment_method'];
        }
        if ($item['price'] ?? false) {
            $price = explode(' ', $item['price']);
            $subscription_history_item->price = $price[0];
        }
        if ($item['payment_date'] ?? false) {
            $subscription_history_item->next_payment_date = $item['payment_date'];
        }
        if ($item['subscription_id'] ?? false) {
            $subscription_history = SubscriptionHistory::select('payment_mode_id')
                ->where('subscription_id', $item['subscription_id'])
                ->where('payment_mode_id', '!=', null)
                ->latest('id')
                ->first();
            if ($subscription_history) {
                $subscription_history_item->payment_mode_id = $subscription_history->payment_mode_id;
            } else {
                $subscription = Subscription::find($item['subscription_id']);
                $subscription_history_item->payment_mode_id = $subscription->payment_mode_id;
            }
        }
        $subscription_history_item->save();
        return $subscription_history_item;
    }

    function update_all(Request $request)
    {
        $history_data = $request->history_data;
        $history_data = json_decode($history_data, true);
        foreach ($history_data as $history_item) {
            $this->update_one_history_item($history_item);
        }

        $subscription = Subscription::find($history_item['subscription_id']);
        $subscription->actualize_next_payment_date();

        return Response::json([
            'status' => true,
            'message' => 'Subscription history updated successfully',
        ]);
    }

    function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => [
                'required',
                'integer',
                Rule::exists('subscriptions_history', 'id')->where(function ($query) {
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

        $subscription_history_item = SubscriptionHistory::find($request->id);
        $subscription_history_item->delete();

        $subscription = Subscription::find($subscription_history_item->subscription_id);
        $subscription->actualize_next_payment_date();

        return Response::json([
            'status' => true,
            'message' => 'Subscription history item deleted successfully',
        ]);
    }
}
