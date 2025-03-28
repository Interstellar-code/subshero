<?php

namespace App\Http\Controllers\Api\v1\Product;

use App\Http\Controllers\Api\v1\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        $fields = $request->validate([
            'q' => 'nullable|string|max:255',
            'start' => 'nullable|integer|min:0',
            'length' => 'nullable|integer|min:1',
            'sort' => 'nullable|string|max:255',
            'filters' => 'nullable|json',
        ]);

        // Get the user input
        $search_query = $fields['q'] ?? '';
        $start = $fields['start'] ?? 0;
        $length = $fields['length'] ?? 10;
        $sort_str = $fields['sort'] ?? '';
        $filters_json_str = $fields['filters'] ?? '';

        // Initialize default values
        $valid_filters = [];
        $valid_sort_arr = [];

        // Remove unicode characters
        $search_query = lib()->do->filter_unicode($search_query);


        // Parse the filters
        if (!empty($filters_json_str)) {
            $filters_json_arr = json_decode($filters_json_str, true);

            // Validate filters
            $validator = Validator::make($filters_json_arr, [
                'product.pricing_type.*' => 'required|integer|between:1,9',
                'product.billing_cycle.*' => 'required|integer:between:1,4',
                'product.featured.*' => 'required|integer|between:0,1',
                'product.rating.*' => 'required|integer|between:0,10',
                'product.category_id.*' => 'required|integer',
                'product.product_type.*' => 'required|integer',
                'product.sub_platform.*' => 'required|integer',
            ]);

            // If validation fails, return error
            if ($validator->fails()) {
                // Return 422 Unprocessable Entity
                return response()->json([
                    'message' => 'Invalid filters provided',
                    'errors' => $validator->errors(),
                ], 422);
            }

            // Get the valid filters
            $valid_filters = $validator->validated();
        }


        // Parse the sorting string
        if (!empty($sort_str)) {

            // Split the string by comma and parse each sort field, e.g. "name:desc"
            $sort_arr = lib()->do->parse_sort($sort_str);

            // Validate the sorting array
            $validator = Validator::make($sort_arr, [
                '*.column' => 'required|string|in:' . Product::$sortableColumns,
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


        // Look for internal values
        $data_pricing_type_id = Product::lookPricingType($search_query);
        $data_billing_cycle_id = Product::lookBillingCycle($search_query);

        // Fetch records
        $query = Product::leftJoin('product_types', 'products.product_type', '=', 'product_types.id')
            ->leftJoin('product_categories', 'products.category_id', '=', 'product_categories.id')
            ->leftJoin('product_platforms', 'products.sub_platform', '=', 'product_platforms.id')
            ->where('products.status', 1)
            ->where('products.id', '>', PRODUCT_RESERVED_ID);


        // Check if filter array exists
        if (!empty($valid_filters)) {

            // Check if array of filters exists
            foreach ([
                'pricing_type',
                'currency_code',
                'billing_cycle',
                'rating',
                'featured',
                'category_id',
                'product_type',
                'sub_platform',
            ] as $key) {
                if (isset($valid_filters['product'][$key])) {
                    $query->whereIn('products.' . $key, $valid_filters['product'][$key]);
                }
            }
        }


        // Query for search value
        $query->where(function ($query) use ($search_query, $data_pricing_type_id, $data_billing_cycle_id) {
            $query->where('products.product_name', 'like', '%' . $search_query . '%')
                ->orWhere('products.brandname', 'like', '%' . $search_query . '%')
                ->orWhere('products.description', 'like', '%' . $search_query . '%')
                ->orWhere('products.url', 'like', '%' . $search_query . '%')
                ->orWhere('products.launch_year', 'like', '%' . $search_query . '%')
                ->orWhere('products.currency_code', 'like', '%' . $search_query . '%')
                ->orWhere('products.price1_name', 'like', '%' . $search_query . '%')
                ->orWhere('products.price1_value', 'like', '%' . $search_query . '%')
                ->orWhere('products.price2_name', 'like', '%' . $search_query . '%')
                ->orWhere('products.price2_value', 'like', '%' . $search_query . '%')
                ->orWhere('products.price3_name', 'like', '%' . $search_query . '%')
                ->orWhere('products.price3_value', 'like', '%' . $search_query . '%')
                ->orWhere('product_categories.name', 'like', '%' . $search_query . '%')
                ->orWhere('product_platforms.name', 'like', '%' . $search_query . '%')
                ->orWhere('product_types.name', 'like', '%' . $search_query . '%');

            // Check if internal values exist
            if (!empty($data_pricing_type_id)) {
                $query->orWhere('products.pricing_type', $data_pricing_type_id);
            }

            if (!empty($data_billing_cycle_id)) {
                $query->orWhere('products.billing_cycle', $data_billing_cycle_id);
            }
        });

        // Select columns
        $query->select(
            'products.*',
            'products.product_name as brand_name',
            'product_types.name as product_type_name',
            'product_categories.name as product_category_name',
            'product_platforms.name as product_platform_name',
        )
            ->groupBy('products.id')
            ->skip($start)
            ->take($length);

        // Check if sort array exists
        if (!empty($valid_sort_arr)) {
            foreach ($valid_sort_arr as $sort_item) {
                $query->orderBy($sort_item['column'], $sort_item['direction']);
            }
        }

        // Get the records
        $records = $query->get();

        // Return error response if no records found
        if ($records->isEmpty()) {
            return response()->json([
                'message' => 'No data found',
            ], 404);
        }

        // Return success response with records
        return response()->json($records, 200);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index()
    // {
    //     $products = Product::get();

    //     return response()->json($products);
    // }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::find($id);

        return response()->json($product);
    }

    /**
     * Search the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $fields = $request->validate([
            'q' => 'nullable|string',
        ]);

        // $search = $fields['q'];
        $search = $request->input('q');

        $products = Product::where('products.id', '>', 10)
            ->where(function ($query) use ($search) {
                $query->where('products.product_name', 'like', '%' . $search . '%')
                    ->orWhere('products.brandname', 'like', '%' . $search . '%')
                    ->orWhere('products.description', 'like', '%' . $search . '%')
                    ->orWhere('products.url', 'like', '%' . $search . '%');
            })
            ->limit(10)
            ->get();

        return response()->json($products);
    }
}
