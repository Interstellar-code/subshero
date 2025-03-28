<?php

namespace App\Http\Controllers\Client\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Application as lib;
use App\Library\Cron;
use Illuminate\Support\Carbon;
use App\Models\FolderModel;
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
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PaymentController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
    }

    public function index()
    {
        $data = [
            'slug' => 'settings/payment',
        ];
        return view('client/settings/payment/index', $data);
    }

    public function get(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
            // abort(419);
        }

        return Response::json(PaymentMethodModel::get($request->input('id')));
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                'max:' . len()->users_payment_methods->name,
                Rule::unique('users_payment_methods', 'name')
                    ->where(function ($query) {
                        return $query->where('user_id', Auth::id());
                    }),
            ],
            'payment_type' => 'required|string|max:' . len()->users_payment_methods->payment_type,
            'description' => 'nullable|string|max:' . len()->users_payment_methods->description,
            'expiry' => 'nullable|date_format:Y-m-d',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
            // abort(419);
        }

        // Check limit
        if (UserModel::limit_reached('payment_method')) {
            return lib()->do->get_limit_msg($request, 'payment_method');
        }

        $data = [
            'user_id' => Auth::id(),
            'name' => $request->input('name'),
            'payment_type' => $request->input('payment_type'),
            'description' => $request->input('description'),
            'expiry' => $request->input('expiry'),
        ];

        $payment_method_id = PaymentMethodModel::create($data);

        if ($request->ajax()) {
            return Response::json([
                'status' => true,
                'message' => 'Success',
            ], 200);
        } else {
            return back();
        }
    }

    public function edit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
            // abort(419);
        }

        $data = PaymentMethodModel::get($request->input('id'));
        if (empty($data)) {
            return response()->back();
        }

        $output = [
            'slug' => 'settings/payment',
            'data' => $data,
        ];
        return view('client/settings/payment/edit', $output);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
            'name' => [
                'required',
                'string',
                'max:' . len()->users_payment_methods->name,
                Rule::unique('users_payment_methods', 'name')
                    ->ignore($request->input('id'))
                    ->where(function ($query) {
                        return $query->where('user_id', Auth::id());
                    }),
            ],
            'payment_type' => 'required|string|max:' . len()->users_payment_methods->payment_type,
            'description' => 'nullable|string|max:' . len()->users_payment_methods->description,
            'expiry' => 'nullable|date_format:Y-m-d',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
            // abort(419);
        }

        $data = [
            'name' => $request->input('name'),
            'payment_type' => $request->input('payment_type'),
            'description' => $request->input('description'),
            'expiry' => $request->input('expiry'),
        ];

        $status = PaymentMethodModel::do_update($request->input('id'), $data);

        if ($request->ajax()) {
            return Response::json([
                'status' => true,
                'message' => 'Success',
            ], 200);
        } else {
            return back();
        }
    }

    public function delete(Request $request)
    {
        $validator = Validator::make(request()->all(), [
            'id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
            // abort(419);
        }

        // Check if in use
        $is_using = PaymentMethodModel::is_using($request->input('id'));
        if ($is_using) {
            return Response::json([
                'status' => false,
                'message' => __('This payment method is currently being used.'),
            ]);
        }

        // Delete payment method
        else {
            return Response::json([
                'status' => PaymentMethodModel::del($request->input('id')),
                'message' => 'Success',
            ], 200);
        }
    }
}
