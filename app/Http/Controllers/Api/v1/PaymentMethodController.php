<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Api\v1\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PaymentMethod;
use App\Models\PaymentMethodModel;
use App\Models\UserModel;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class PaymentMethodController extends Controller
{
    // RESTful API standards

    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        // Validate the request and get the validated data
        $fields = $request->validate([
            'q' => 'nullable|string|max:255',
            'start' => 'nullable|integer|min:0',
            'length' => 'nullable|integer|min:1',
            'sort' => 'nullable|string|max:255',
        ]);

        // Get the user input and initialize default values
        $search_query = $fields['q'] ?? '';
        $start = $fields['start'] ?? 0;
        $length = $fields['length'] ?? 10;
        $sort_str = $fields['sort'] ?? '';
        $valid_sort_arr = [];

        // Remove unicode characters
        $search_query = lib()->do->filter_unicode($search_query);


        // Parse the sorting string
        if (!empty($sort_str)) {

            // Split the string by comma and parse each sort field, e.g. "name:desc"
            $sort_arr = lib()->do->parse_sort($sort_str);

            // Validate the sorting array
            $validator = Validator::make($sort_arr, [
                '*.column' => 'required|string|in:' . PaymentMethod::$sortableColumns,
                '*.direction' => 'required|string|in:asc,desc',
            ]);

            // If validation fails, return error response with validation errors (422 Unprocessable Entity)
            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Invalid sort parameters',
                    'errors' => $validator->errors(),
                ], 422);
            }

            // If validation passes, set the sort array
            $valid_sort_arr = $validator->validated();
        }


        // Get records from database
        $query = PaymentMethod::where('users_payment_methods.user_id', Auth::id())
            ->where(function ($query) use ($search_query) {
                $query->where('users_payment_methods.name', 'like', '%' . $search_query . '%');
            })
            ->skip($start)
            ->take($length);

        // Sort the query with multiple columns
        if (!empty($valid_sort_arr)) {
            foreach ($valid_sort_arr as $sort_item) {
                $query->orderBy("users_payment_methods.{$sort_item['column']}", $sort_item['direction']);
            }
        }

        // Get the records
        $records = $query->select(
            'users_payment_methods.*',
        )->get();

        // Return error response if no records found
        if ($records->isEmpty()) {
            return response()->json([
                'message' => 'No data found',
            ], 404);
        }

        // Return success response with records
        return response()->json($records, 200);
    }

    public function show(Request $request)
    {
        // Merge the request with the value from the route parameter and validate the request
        $request->merge(['id' => $request->route('id')]);
        $fields = $request->validate([
            'id' => [
                'required',
                'integer',
                Rule::exists('users_payment_methods', 'id')->where(function ($query) {
                    $query->where('user_id', Auth::id());
                }),
            ],
        ]);

        // Find the record from database and return success response
        $data = PaymentMethod::find($fields['id']);
        return response()->json($data, 200);
    }

    public function create(Request $request)
    {
        // Validate the request and get the validated data
        $fields = $request->validate([
            'name' => [
                'required',
                'string',
                'max:' . len()->users_payment_methods->name,
                Rule::unique('users_payment_methods', 'name')
                    ->where(function ($query) {
                        return $query->where('user_id', Auth::id());
                    }),
            ],
            'payment_type' => [
                'required',
                'string',
                'max:' . len()->users_payment_methods->payment_type,
                Rule::in(table('subscription.payment_type')),
            ],
            'description' => 'nullable|string|max:' . len()->users_payment_methods->description,
            'expiry' => 'nullable|date_format:Y-m-d',
        ]);

        // Check limit
        if (UserModel::limit_reached('payment_method')) {
            return lib()->do->get_limit_msg_api($request, 'payment_method');
        }

        // Insert data into database
        $row = new PaymentMethod();
        $row->user_id = Auth::id();
        $row->name = $fields['name'];
        $row->payment_type = $fields['payment_type'];
        $row->description = $fields['description'] ?? '';
        $row->expiry = $fields['expiry'] ?? null;
        $row->save();

        // Return success response with data
        return response()->json($row, 201);
    }

    public function update(Request $request)
    {
        // Merge the request with the value from the route parameter and validate the request
        $request->merge(['id' => $request->route('id')]);
        $fields = $request->validate([
            'id' => [
                'required',
                'integer',
                Rule::exists('users_payment_methods', 'id')->where(function ($query) {
                    $query->where('user_id', Auth::id());
                }),
            ],
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
            'payment_type' => [
                'required',
                'string',
                'max:' . len()->users_payment_methods->payment_type,
                Rule::in(table('subscription.payment_type')),
            ],
            'description' => 'nullable|string|max:' . len()->users_payment_methods->description,
            'expiry' => 'nullable|date_format:Y-m-d',
        ]);

        // Update data into database
        $row = PaymentMethod::find($fields['id']);
        $row->name = $fields['name'];
        $row->payment_type = $fields['payment_type'];
        $row->description = $fields['description'] ?? '';
        $row->expiry = $fields['expiry'] ?? null;
        $row->save();

        // Return success response with updated data
        return response()->json($row, 200);
    }

    public function delete(Request $request)
    {
        // Merge the request with the value from the route parameter and validate the request
        $request->merge(['id' => $request->route('id')]);
        $fields = $request->validate([
            'id' => [
                'required',
                'integer',
                Rule::exists('users_payment_methods', 'id')->where(function ($query) {
                    $query->where('user_id', Auth::id());
                }),
            ],
        ]);

        // Check if in use
        $is_using = PaymentMethodModel::is_using($fields['id']);
        if ($is_using) {
            return response()->json([
                'message' => __('This payment method is currently being used.'),
            ], 409);
        }

        // Delete the data from database
        $row = PaymentMethod::find($fields['id']);
        $row->delete();

        // Return success response with no content (204)
        return response()->noContent();
    }
}
