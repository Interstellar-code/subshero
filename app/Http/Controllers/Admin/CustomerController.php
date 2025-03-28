<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BrandModel;
use App\Models\FolderModel;
use App\Models\CustomerModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\File;
use App\Models\TagModel;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
    }

    public function index()
    {
        $data = [
            'slug' => 'admin/customer',
        ];
        return view('admin/customer/index', $data);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_type' => 'required|integer|digits_between:0,' . len()->customers->customer_type,
            'pricing_type' => 'required|integer|digits_between:0,' . len()->customers->pricing_type,
            'customer_name' => 'required|string|max:' . len()->customers->customer_name,
            'brandname' => 'nullable|string|max:' . len()->customers->brandname,
            'description' => 'nullable|string|max:' . len()->customers->description,
            'url' => 'nullable|string|max:' . len()->customers->url,
            'currency_code' => 'required|string|size:' . len()->customers->currency_code,
            'price1_name' => 'nullable|string|required_with:price1_value|max:' . len()->customers->price1_name,
            'price1_value' => 'nullable|numeric|required_with:price1_name',
            'price2_name' => 'nullable|string|required_with:price2_value|max:' . len()->customers->price2_name,
            'price2_value' => 'nullable|numeric|required_with:price2_name',
            'price3_name' => 'nullable|string|required_with:price3_value|max:' . len()->customers->price3_name,
            'price3_value' => 'nullable|numeric|required_with:price3_name',
            'refund_days' => 'nullable|integer|max:' . len()->customers->refund_days,
            'image' => 'image|dimensions:min_width=100,min_height=200',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
            // abort(419);
        }

        $data = [
            'customer_type' => $request->input('customer_type'),
            'pricing_type' => $request->input('pricing_type'),
            'customer_name' => $request->input('customer_name'),
            'brandname' => $request->input('brandname'),
            'description' => $request->input('description'),
            'url' => $request->input('url'),
            'currency_code' => $request->input('currency_code'),
            'price1_name' => $request->input('price1_name'),
            'price1_value' => (float)$request->input('price1_value'),
            'price2_name' => $request->input('price2_name'),
            'price2_value' => (float)$request->input('price2_value'),
            'price3_name' => $request->input('price3_name'),
            'price3_value' => (float)$request->input('price3_value'),
            'refund_days' => $request->input('refund_days'),
        ];

        $customer_id = CustomerModel::create($data);

        // Check if user selected an image then save image
        if ($request->hasFile('image')) {
            $image_path = File::add_get_path($request->file('image'), 'customer', $customer_id);
            CustomerModel::do_update($customer_id, [
                'image' => $image_path,
            ]);
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
        return Response::json(CustomerModel::get($request->input('id')));
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

        $data = [
            'slug' => 'customer',
            'data' => CustomerModel::get($request->input('id')),
        ];
        return view('admin/customer/edit', $data);
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

        return Response::json([
            'status' => CustomerModel::del($request->input('id')),
            'message' => 'Success',
        ], 200);
    }


    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
            'plan_id' => 'required|integer|exists:plans,id',
            'email' => [
                'required',
                'email',
                'max:' . len()->users->email,
                Rule::unique('users', 'email')
                    ->ignore($request->input('id')),
            ],
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
            // abort(419);
        }

        $customer_id = $request->input('id');
        $data = [
            'plan_id' => $request->input('plan_id'),
            'email' => $request->input('email'),
        ];

        $status = CustomerModel::do_update($customer_id, $data);

        if ($request->ajax()) {
            return Response::json([
                'status' => true,
                'message' => 'Success',
            ], 200);
        } else {
            return back();
        }
    }
}
