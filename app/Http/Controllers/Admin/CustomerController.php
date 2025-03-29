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
        // Call the parent constructor to inherit base controller functionality
        parent::__construct();
        // Apply the 'auth' middleware to ensure only authenticated users can access this controller's methods
        $this->middleware('auth');
    }

    public function index()
    {
    // Set the slug variable for identifying the current page in the view
    $data = [
        'slug' => 'admin/customer',
    ];
    // Return the 'admin/customer/index' view, passing the $data array for use in the view
    return view('admin/customer/index', $data);
}


    public function create(Request $request)
    {
        // Validate the incoming request data to ensure it meets the defined rules
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

        // If the validation fails, return a JSON response with the error messages
        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
            // If validation fails, you might want to log the errors or redirect the user back to the form with the errors.
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

        // Check if an image file was included in the request
        if ($request->hasFile('image')) {
            // If an image was included, store the image and get its path
            $image_path = File::add_get_path($request->file('image'), 'customer', $customer_id);
            // Update the customer model with the image path
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
        // Validate the request to ensure an 'id' is provided and is an integer
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
        ]);

        // If the validation fails, return a JSON response with the error messages
        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
            // If validation fails, you might want to log the errors or redirect the user back to the form with the errors.
            // abort(419);
        }
        // Retrieve the customer data based on the provided 'id' and return it as a JSON response
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

        // Set the slug and retrieve the customer data for the edit view
        $data = [
            'slug' => 'customer',
            'data' => CustomerModel::get($request->input('id')),
        ];
        // Return the 'admin/customer/edit' view, passing the data for rendering the edit form
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

        // Attempt to delete the customer and return a JSON response indicating success or failure
        return Response::json([
            'status' => CustomerModel::del($request->input('id')),
            'message' => 'Success',
        ], 200); // 200 OK status code
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
