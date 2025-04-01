<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BrandModel;
use App\Models\FolderModel;
use App\Models\ProductModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\File;
use App\Models\Product;
use App\Models\TagModel;
use Illuminate\Validation\Rule;
use ParseCsv\Csv;


class ProductController extends Controller
{
    public function __construct()
    {
        // Call the parent constructor
        parent::__construct();
        // Ensure the user is authenticated
        $this->middleware('auth');
    }

    public function index()
    {
        // Set the slug for the view
        $data = [
            'slug' => 'admin/product',
        ];
        // Return the product index view
        return view('admin/product/index', $data);
    }

    public function create(Request $request)
    {
        // Create a new product after validating the request data
        $validator = Validator::make($request->all(), [
            'category_id' => 'nullable|integer', // Category ID can be null or an integer
            'product_type' => 'required|integer|digits_between:0,' . len()->products->product_type, // Product type is required and must be an integer within the allowed range
            'pricing_type' => 'required|integer|digits_between:0,' . len()->products->pricing_type,
            'product_name' => 'required|string|max:' . len()->products->product_name,
            'brandname' => 'nullable|string|max:' . len()->products->brandname,
            'description' => 'nullable|string|max:' . len()->products->description,
            'url' => 'nullable|string|max:' . len()->products->url,
            'url_app' => 'nullable|string|max:' . len()->products->url_app,
            'currency_code' => 'required|string|size:' . len()->products->currency_code,
            'price1_name' => 'nullable|string|required_with:price1_value|max:' . len()->products->price1_name,
            'price1_value' => 'nullable|numeric|required_with:price1_name',
            'price2_name' => 'nullable|string|required_with:price2_value|max:' . len()->products->price2_name,
            'price2_value' => 'nullable|numeric|required_with:price2_name',
            'price3_name' => 'nullable|string|required_with:price3_value|max:' . len()->products->price3_name,
            'price3_value' => 'nullable|numeric|required_with:price3_name',
            'refund_days' => 'nullable|integer|max:' . len()->products->refund_days,
            'billing_frequency' => 'nullable|numeric|digits_between:0,40',
            'billing_cycle' => 'nullable|numeric|digits_between:0,9',
            'status' => 'nullable|boolean',
            'sub_ltd' => 'nullable|integer|in:0,1',
            'launch_year' => 'nullable|string|size:4|date_format:Y',
            'sub_platform' => 'nullable|integer',
            'image' => 'sometimes|nullable|image|dimensions:width=320,height=120',
            'image_favicon' => 'sometimes|nullable|image|dimensions:width=128,height=128',

            'ltdval_price' => 'nullable|numeric|min:0',
            'ltdval_frequency' => 'nullable|integer|between:1,' . len()->products->ltdval_frequency,
            'ltdval_cycle' => 'nullable|integer|between:1,' . len()->products->ltdval_cycle,
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
            'admin_id' => Auth::id(),
            'category_id' => $request->input('category_id'),
            'product_type' => $request->input('product_type'),
            'pricing_type' => $request->input('pricing_type'),
            'product_name' => $request->input('product_name'),
            'brandname' => $request->input('brandname'),
            'description' => $request->input('description'),
            'url' => $request->input('url'),
            'url_app' => $request->input('url_app'),
            'currency_code' => $request->input('currency_code'),
            'price1_name' => $request->input('price1_name'),
            'price1_value' => $request->input('price1_value'),
            'price2_name' => $request->input('price2_name'),
            'price2_value' => $request->input('price2_value'),
            'price3_name' => $request->input('price3_name'),
            'price3_value' => $request->input('price3_value'),
            'refund_days' => $request->input('refund_days'),
            'billing_frequency' => $request->input('billing_frequency'),
            'billing_cycle' => $request->input('billing_cycle'),
            'status' => $request->input('status') ? 1 : 0,
            'sub_ltd' => $request->input('sub_ltd') ? 1 : 0,
            'launch_year' => $request->input('launch_year'),
            'sub_platform' => $request->input('sub_platform'),
            'ltdval_price' => $request->input('ltdval_price'),
            'ltdval_frequency' => $request->input('ltdval_frequency'),
            'ltdval_cycle' => $request->input('ltdval_cycle'),
        ];

        $product_id = ProductModel::create($data);

        // Check if user selected an image then save image
        // Update the product images
        $result = File::update_images($request, $product_id);

        // If the request is an AJAX request, return a JSON response
        if ($request->ajax()) {
            return Response::json($result, 200); // 200 OK status code
        } else {
            // If the request is not an AJAX request, redirect back to the previous page
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
        return Response::json(ProductModel::get($request->input('id')));
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
            'slug' => 'product',
            'data' => ProductModel::get($request->input('id')),
        ];
        return view('admin/product/edit', $data);
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
            'status' => ProductModel::del($request->input('id')),
            'message' => 'Success',
        ], 200);
    }


    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
            'category_id' => 'nullable|integer',
            'product_type' => 'required|integer|digits_between:0,' . len()->products->product_type,
            'pricing_type' => 'required|integer|digits_between:0,' . len()->products->pricing_type,
            'product_name' => 'required|string|max:' . len()->products->product_name,
            'brandname' => 'nullable|string|max:' . len()->products->brandname,
            'description' => 'nullable|string|max:' . len()->products->description,
            'url' => 'nullable|string|max:' . len()->products->url,
            'url_app' => 'nullable|string|max:' . len()->products->url_app,
            'currency_code' => 'required|string|size:' . len()->products->currency_code,
            'price1_name' => 'nullable|string|required_with:price1_value|max:' . len()->products->price1_name,
            'price1_value' => 'nullable|numeric|required_with:price1_name',
            'price2_name' => 'nullable|string|required_with:price2_value|max:' . len()->products->price2_name,
            'price2_value' => 'nullable|numeric|required_with:price2_name',
            'price3_name' => 'nullable|string|required_with:price3_value|max:' . len()->products->price3_name,
            'price3_value' => 'nullable|numeric|required_with:price3_name',
            'refund_days' => 'nullable|integer|max:' . len()->products->refund_days,
            'billing_frequency' => 'nullable|numeric|digits_between:0,40',
            'billing_cycle' => 'nullable|numeric|digits_between:0,9',
            'status' => 'nullable|boolean',
            'sub_ltd' => 'nullable|integer|in:0,1',
            'launch_year' => 'nullable|string|size:4|date_format:Y',
            'sub_platform' => 'nullable|integer',
            'image' => 'sometimes|nullable|image|dimensions:width=320,height=120',
            'image_favicon' => 'sometimes|nullable|image|dimensions:width=128,height=128',

            'ltdval_price' => 'nullable|numeric|min:0',
            'ltdval_frequency' => 'nullable|integer|between:1,' . len()->products->ltdval_frequency,
            'ltdval_cycle' => 'nullable|integer|between:1,' . len()->products->ltdval_cycle,
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
            // abort(419);
        }

        $product_id = $request->input('id');
        $data = [
            'admin_id' => Auth::id(),
            'category_id' => $request->input('category_id'),
            'product_type' => $request->input('product_type'),
            'pricing_type' => $request->input('pricing_type'),
            'product_name' => $request->input('product_name'),
            'brandname' => $request->input('brandname'),
            'description' => $request->input('description'),
            'url' => $request->input('url'),
            'url_app' => $request->input('url_app'),
            'currency_code' => $request->input('currency_code'),
            'price1_name' => $request->input('price1_name'),
            'price1_value' => $request->input('price1_value'),
            'price2_name' => $request->input('price2_name'),
            'price2_value' => $request->input('price2_value'),
            'price3_name' => $request->input('price3_name'),
            'price3_value' => $request->input('price3_value'),
            'refund_days' => $request->input('refund_days'),
            'billing_frequency' => $request->input('billing_frequency'),
            'billing_cycle' => $request->input('billing_cycle'),
            'status' => $request->input('status') ? 1 : 0,
            'sub_ltd' => $request->input('sub_ltd') ? 1 : 0,
            'launch_year' => $request->input('launch_year'),
            'sub_platform' => $request->input('sub_platform'),
            'ltdval_price' => $request->input('ltdval_price'),
            'ltdval_frequency' => $request->input('ltdval_frequency'),
            'ltdval_cycle' => $request->input('ltdval_cycle'),
        ];

        if ($request->input('img_path_or_file') == 0 && !empty($request->input('image_path'))) {
            $data['image'] = $request->input('image_path');
        }
        $status = ProductModel::do_update($product_id, $data);

        // Check if user selected an image then save image
        $result = File::update_images($request, $product_id);

        if ($request->ajax()) {
            return Response::json($result, 200);
        } else {
            return back();
        }
    }

    public function import_index(Request $request)
    {
        $data = [
            'slug' => 'import',
        ];
        return view('admin/product/import/index', $data);
    }

    public function import_load(Request $request)
    {
        // $csv = new \ParseCsv\Csv();

        $validator = Validator::make($request->all(), [
            'file' => 'required|file', #|mimes:csv
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
            // abort(419);
        }

        $file = $request->file('file');
        $this->clearUTF8BOM($file->path());

        $csv = new \ParseCsv\Csv();
        $csv->parseFile($file->path());

        $output['data']['items'] = [];

        if (!empty($csv->data)) {
            foreach ($csv->data as $row) {
                $output['data']['items'][] = [
                    'id' => isset($row['id']) ? $row['id'] : null,
                    'admin_id' => isset($row['admin_id']) ? $row['admin_id'] : null,
                    'category_id' => isset($row['category_id']) ? $row['category_id'] : null,
                    'product_name' => isset($row['product_name']) ? $row['product_name'] : null,
                    'brandname' => isset($row['brandname']) ? $row['brandname'] : null,
                    'product_type' => isset($row['product_type']) ? $row['product_type'] : null,
                    'description' => isset($row['description']) ? $row['description'] : null,
                    'url' => isset($row['url']) ? $row['url'] : null,
                    'url_app' => isset($row['url_app']) ? $row['url_app'] : null,
                    'image' => isset($row['image']) ? $row['image'] : null,
                    'favicon' => isset($row['favicon']) ? $row['favicon'] : null,
                    'status' => isset($row['status']) ? $row['status'] : null,
                    'sub_ltd' => isset($row['sub_ltd']) ? $row['sub_ltd'] : null,
                    'launch_year' => isset($row['launch_year']) ? $row['launch_year'] : null,
                    'sub_platform' => isset($row['sub_platform']) ? $row['sub_platform'] : null,
                    'pricing_type' => isset($row['pricing_type']) ? $row['pricing_type'] : null,
                    'currency_code' => isset($row['currency_code']) ? $row['currency_code'] : null,
                    'price1_name' => isset($row['price1_name']) ? $row['price1_name'] : null,
                    'price1_value' => isset($row['price1_value']) ? $row['price1_value'] : null,
                    'price2_name' => isset($row['price2_name']) ? $row['price2_name'] : null,
                    'price2_value' => isset($row['price2_value']) ? $row['price2_value'] : null,
                    'price3_name' => isset($row['price3_name']) ? $row['price3_name'] : null,
                    'price3_value' => isset($row['price3_value']) ? $row['price3_value'] : null,
                    'refund_days' => isset($row['refund_days']) ? $row['refund_days'] : null,
                    'billing_frequency' => isset($row['billing_frequency']) ? $row['billing_frequency'] : null,
                    'billing_cycle' => isset($row['billing_cycle']) ? $row['billing_cycle'] : null,
                    'ltdval_price' => isset($row['ltdval_price']) ? $row['ltdval_price'] : null,
                    'ltdval_frequency' => isset($row['ltdval_frequency']) ? $row['ltdval_frequency'] : null,
                    'ltdval_cycle' => isset($row['ltdval_cycle']) ? $row['ltdval_cycle'] : null,
                    'created_at' => isset($row['created_at']) ? $row['created_at'] : null,
                    'created_by' => isset($row['created_by']) ? $row['created_by'] : null,
                ];
            }
        }

        return view('admin/product/import/edit', $output);
    }

    public function import_validate(Request $request)
    {
        // Validate all the fields dynamically
        $validator = Validator::make($request->all(), $this->import_validation_rules());

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
        }

        return Response::json([
            'status' => true,
            'message' => 'Success',
        ], 200);
    }

    public function import_save(Request $request)
    {
        // Validate all the fields dynamically
        $validator = Validator::make($request->all(), $this->import_validation_rules());

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
        }

        $id = $request->input('id');
        $data = [
            'admin_id' => $request->input('admin_id'),
            'category_id' => $request->input('category_id'),
            'product_type' => $request->input('product_type'),
            'pricing_type' => $request->input('pricing_type'),
            'product_name' => $request->input('product_name'),
            'brandname' => $request->input('brandname'),
            'description' => $request->input('description'),
            'url' => $request->input('url'),
            'url_app' => $request->input('url_app'),
            'currency_code' => $request->input('currency_code'),
            'price1_name' => $request->input('price1_name'),
            'price1_value' => $request->input('price1_value'),
            'price2_name' => $request->input('price2_name'),
            'price2_value' => $request->input('price2_value'),
            'price3_name' => $request->input('price3_name'),
            'price3_value' => $request->input('price3_value'),
            'refund_days' => $request->input('refund_days'),
            'billing_frequency' => $request->input('billing_frequency'),
            'billing_cycle' => $request->input('billing_cycle'),
            'ltdval_price' => $request->input('ltdval_price'),
            'ltdval_frequency' => $request->input('ltdval_frequency'),
            'ltdval_cycle' => $request->input('ltdval_cycle'),
            'status' => $request->input('status'),
            'sub_ltd' => $request->input('sub_ltd'),
            'launch_year' => $request->input('launch_year'),
            'sub_platform' => $request->input('sub_platform'),
            'image' => $request->input('image'),
            'favicon' => $request->input('favicon'),
            'created_at' => $request->input('created_at'),
            'created_by' => $request->input('created_by'),
        ];

        // Default values
        if (empty($data['created_at'])) {
            $data['created_at'] = lib()->do->timezone_convert();
        }
        if (empty($data['created_by'])) {
            $data['created_by'] = Auth::id();
        }

        // Create
        if (empty($id)) {
            $id = ProductModel::create($data);
        }

        // Update
        else {
            $status = ProductModel::do_update($id, $data);
        }

        return Response::json([
            'status' => true,
            'message' => 'Success',
        ], 200);
    }

    private function import_validation_rules()
    {
        return [
            'id' => 'nullable|integer',
            'admin_id' => 'nullable|integer',
            'category_id' => 'nullable|integer',
            'product_type' => 'required|integer|between:0,' . len()->products->product_type,
            'pricing_type' => 'required|integer|between:1,' . len()->products->pricing_type,
            'product_name' => 'required|string|max:' . len()->products->product_name,
            'brandname' => 'nullable|string|max:' . len()->products->brandname,
            'description' => 'nullable|string|max:' . len()->products->description,
            'url' => 'nullable|string|max:' . len()->products->url,
            'url_app' => 'nullable|string|max:' . len()->products->url_app,
            'currency_code' => [
                'required',
                'string',
                'size:' . len()->products->currency_code,
                Rule::in(lib()->config->currency_code),
            ],
            'price1_name' => 'nullable|string|required_with:price1_value|max:' . len()->products->price1_name,
            'price1_value' => 'nullable|numeric|required_with:price1_name',
            'price2_name' => 'nullable|string|required_with:price2_value|max:' . len()->products->price2_name,
            'price2_value' => 'nullable|numeric|required_with:price2_name',
            'price3_name' => 'nullable|string|required_with:price3_value|max:' . len()->products->price3_name,
            'price3_value' => 'nullable|numeric|required_with:price3_name',
            'refund_days' => 'nullable|integer|max:' . len()->products->refund_days,
            'billing_frequency' => 'nullable|integer|between:1,' . len()->products->billing_frequency,
            'billing_cycle' => 'nullable|integer|between:1,' . len()->products->billing_cycle,
            'ltdval_price' => 'nullable|numeric|min:0',
            'ltdval_frequency' => 'nullable|integer|between:1,' . len()->products->ltdval_frequency,
            'ltdval_cycle' => 'nullable|integer|between:1,' . len()->products->ltdval_cycle,
            'status' => 'nullable|integer|in:0,1',
            'sub_ltd' => 'nullable|integer|in:0,1',
            'launch_year' => 'nullable|string|size:4|date_format:Y',
            'sub_platform' => 'nullable|integer',
            'image' => 'nullable|string|max:' . len()->products->image,
            'favicon' => 'nullable|string|max:' . len()->products->favicon,
            'created_at' => 'nullable|date_format:Y-m-d H:i:s',
            'created_by' => 'nullable|integer',
        ];
    }

    public function export(Request $request)
    {
        $data = DB::table('products')
            ->select('products.*')
            ->get();

        $output = [];

        if (!empty($data)) {

            foreach ($data as $val) {

                $output[] = [
                    'id' => $val->id,
                    'admin_id' => $val->admin_id,
                    'category_id' => $val->category_id,
                    'product_name' => $val->product_name,
                    'brandname' => $val->brandname,
                    'product_type' => $val->product_type,
                    'description' => $val->description,
                    'url' => $val->url,
                    'url_app' => $val->url_app,
                    'image' => $val->image,
                    'favicon' => $val->favicon,
                    'status' => $val->status,
                    'sub_ltd' => $val->sub_ltd,
                    'launch_year' => $val->launch_year,
                    'sub_platform' => $val->sub_platform,
                    'pricing_type' => $val->pricing_type,
                    'currency_code' => $val->currency_code,
                    'price1_name' => $val->price1_name,
                    'price1_value' => $val->price1_value,
                    'price2_name' => $val->price2_name,
                    'price2_value' => $val->price2_value,
                    'price3_name' => $val->price3_name,
                    'price3_value' => $val->price3_value,
                    'refund_days' => $val->refund_days,
                    'billing_frequency' => $val->billing_frequency,
                    'billing_cycle' => $val->billing_cycle,
                    'ltdval_price' => $val->ltdval_price,
                    'ltdval_frequency' => $val->ltdval_frequency,
                    'ltdval_cycle' => $val->ltdval_cycle,
                    'created_at' => $val->created_at,
                    'created_by' => $val->created_by,
                ];
            }
        }


        return Response::json([
            'status' => true,
            'message' => 'Success',
            'data' => $output,
        ], 200);
    }

    public function datatable_index(Request $request, $search = null)
    {
        $column_map = [
            'column_status' => 'status',
            'column_type' => 'type',
            'column_due_date' => 'next_payment_date',
            'column_pricing_type' => 'pricing_type',
        ];

        ## Read value
        $draw = $request->get('draw');
        $start = $request->get('start');
        $row_per_page = $request->get('length');

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        // Get column name
        $columnIndex = $columnIndex_arr[0]['column'];
        $columnName = $columnName_arr[$columnIndex]['data'];

        // Ascending (asc) or descending order (desc)
        $columnSortOrder = $order_arr[0]['dir'];
        $searchValue = $search_arr['value'];

        // Remove unicode characters
        $searchValue = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $searchValue);
        $searchValue = preg_replace('/[\x00-\x1F\x7F]/', '', $searchValue);
        $searchValue = preg_replace('/[\x00-\x1F\x7F]/u', '', $searchValue);

        // Find product type
        $data['searchValue'] = $searchValue;
        $data['product_type_id'] = $this->get_product_type($searchValue);
        $data['product_billing_cycle_id'] = $this->get_product_billing_cycle($searchValue);
        $data['product_status'] = $this->get_product_status($searchValue);


        // Total records
        $totalRecords = Product::select('count(*) as allcount')
            ->count();

        $totalRecordswithFilter = $this->datatable_query($data)
            ->select('count(products.*) as allcount')
            ->count();

        // Map custom column name
        if (isset($column_map[$columnName])) {
            $columnName = $column_map[$columnName];
        }


        // Fetch records
        $records = $this->datatable_query($data)
            ->select(
                'products.*',
                'product_types.name as product_type_name',
                'product_categories.name as product_category_name',
                'product_platforms.name as product_platform_name',
            )
            ->orderBy($columnName, $columnSortOrder)
            ->skip($start)
            ->take($row_per_page)
            ->get();

        // $totalRecordswithFilter = $records->count();

        $data_arr = array();

        foreach ($records as $val) {

            $data_arr[] = array(
                'id' => $val->id,
                'product_name' => $val->product_name,
                'pricing_type' => enum('table.product.pricing_type', $val->pricing_type),
                'product_type_name' => $val->product_type_name,
                'product_category_name' => $val->product_category_name,
                'product_platform_name' => $val->product_platform_name,
                'description' => $val->description,
                'status' => enum('table.product.status', $val->status),
                // 'price' => lib()->do->get_currency_symbol($val->price_type) . $val->price,
                'created_at' => $val->created_at,
                'column_brand' => view('admin/datatable/product/column_brand', compact('val'))->render(),
                'column_pricing_type' => view('admin/datatable/product/column_pricing_type', compact('val'))->render(),
                'column_status' => view('admin/datatable/product/column_status', compact('val'))->render(),
                'column_action' => view('admin/datatable/product/column_action', compact('val'))->render(),
            );
        }

        $response = array(
            'draw' => intval($draw),
            'iTotalRecords' => $totalRecords,
            'iTotalDisplayRecords' => $totalRecordswithFilter,
            'aaData' => $data_arr
        );

        return response()->json($response);
    }

    private function datatable_query(array $data)
    {
        return Product::leftJoin('product_types', 'products.product_type', '=', 'product_types.id')
            ->leftJoin('product_categories', 'products.category_id', '=', 'product_categories.id')
            ->leftJoin('product_platforms', 'products.sub_platform', '=', 'product_platforms.id')

            // Get only active and inactive products
            ->whereIn('products.status', [0, 1])

            ->where(function ($query) use ($data) {
                $query->where('products.product_name', 'like', '%' . $data['searchValue'] . '%')
                    ->orWhere($data['product_type_id'] !== null ? ['products.pricing_type' => $data['product_type_id']] : null)
                    ->orWhere($data['product_billing_cycle_id'] !== null ? ['products.billing_cycle' => $data['product_billing_cycle_id']] : null)
                    ->orWhere($data['product_status'] !== null ? ['products.status' => $data['product_status']] : null)
                    ->orWhere('products.description', 'like', '%' . $data['searchValue'] . '%')
                    ->orWhere('products.price1_name', 'like', '%' . $data['searchValue'] . '%')
                    ->orWhere('products.price1_value', 'like', '%' . $data['searchValue'] . '%')
                    ->orWhere('products.price2_name', 'like', '%' . $data['searchValue'] . '%')
                    ->orWhere('products.price2_value', 'like', '%' . $data['searchValue'] . '%')
                    ->orWhere('products.price3_name', 'like', '%' . $data['searchValue'] . '%')
                    ->orWhere('products.price3_value', 'like', '%' . $data['searchValue'] . '%')
                    ->orWhere('product_categories.name', 'like', '%' . $data['searchValue'] . '%')
                    ->orWhere('product_platforms.name', 'like', '%' . $data['searchValue'] . '%')
                    ->orWhere('product_types.name', 'like', '%' . $data['searchValue'] . '%');
            });
    }

    private function get_product_type(string $search_term)
    {
        // Predefined search terms
        $subscription_type_terms = [

            // Subscription
            1 => [
                'sub',
                'subs',
                'subscription',
                'subscriptions',
            ],

            // Trial
            2 => [
                'trial',
            ],

            // Lifetime
            3 => [
                'life',
                'lifetime',
                'lifetimes',
            ],

            // Revenue
            4 => [
                'revenue',
            ],
        ];

        $search_term = strtolower($search_term);
        $all_search_term = explode(' ', $search_term);

        if (count($all_search_term) > 2) {
            $all_search_term = array_slice($all_search_term, 2);
        }

        $subscription_type = null;

        // Search the needle
        foreach ($all_search_term as $val) {
            foreach ($subscription_type_terms as $type_id => $all_terms) {
                foreach ($all_terms as $term) {
                    if (strpos($term, $val) !== false) {
                        $subscription_type = $type_id;
                        break 3;
                    }
                }
            }
        }

        return $subscription_type;
    }

    private function get_product_billing_cycle(string $search_term)
    {
        // Predefined search terms
        $subscription_type_terms = [

            // Daily
            1 => [
                'daily',
            ],

            // Weekly
            2 => [
                'weekly',
            ],

            // Monthly
            3 => [
                'monthly',
            ],

            // Yearly
            4 => [
                'yearly',
            ],
        ];

        $search_term = strtolower($search_term);
        $all_search_term = explode(' ', $search_term);

        if (count($all_search_term) > 2) {
            $all_search_term = array_slice($all_search_term, 2);
        }

        $subscription_type = null;

        // Search the needle
        foreach ($all_search_term as $val) {
            foreach ($subscription_type_terms as $type_id => $all_terms) {
                foreach ($all_terms as $term) {
                    if (strpos($term, $val) !== false) {
                        $subscription_type = $type_id;
                        break 3;
                    }
                }
            }
        }

        return $subscription_type;
    }

    private function get_product_status(string $search_term)
    {
        // Predefined search terms
        $subscription_type_terms = [

            // Active
            1 => [
                'active',
                'act',
                'actv',
                'actve',
                'active',
            ],

            // Inactive
            0 => [
                'inactive',
                'inact',
                'inactv',
                'inactve',
            ],
        ];

        $search_term = strtolower($search_term);
        $all_search_term = explode(' ', $search_term);

        if (count($all_search_term) > 2) {
            $all_search_term = array_slice($all_search_term, 2);
        }

        $subscription_type = null;

        // Search the needle
        foreach ($all_search_term as $val) {
            foreach ($subscription_type_terms as $type_id => $all_terms) {
                foreach ($all_terms as $term) {
                    if (strpos($term, $val) !== false) {
                        $subscription_type = $type_id;
                        break 3;
                    }
                }
            }
        }

        return $subscription_type;
    }

    private function clearUTF8BOM($filePath)
    {
        $fileContent = file_get_contents($filePath);
        $bom = pack('H*', 'EFBBBF');
        $fileContent = preg_replace("/^$bom/", '', $fileContent);
        file_put_contents($filePath, $fileContent);
    }
    /**
     * Search for product logos using web scraping and API services
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchLogo(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'product_name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
        }

        $productName = $request->input('product_name');
        
        // Prepare the search results array
        $results = [];
        
        // Get results from web scraping
        $scrapedResults = $this->searchLogoFromWebScraping($productName);
        
        // Get results from available logo API services as fallback
        $apiResults = $this->searchLogoFromApis($productName);
        
        // Combine results, prioritizing scraped results
        $results = array_merge($scrapedResults, $apiResults);
        
        return Response::json([
            'status' => true,
            'message' => 'Success',
            'data' => $results,
        ]);
    }
    
    /**
     * Search for logos using web scraping
     *
     * @param string $productName
     * @return array
     */
    private function searchLogoFromWebScraping($productName)
    {
        $results = [];
        $searchTerm = urlencode($productName . " logo");
        
        // Google Images search URL
        $url = "https://www.google.com/search?q={$searchTerm}&tbm=isch&tbs=iar:xw,ift:png";
        // Backup URL using Brave Search
        $backupUrl = "https://search.brave.com/search?q={$searchTerm}";
        
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            
            // Check for proxy environment variables
            $envVars = array_change_key_case($_SERVER, CASE_LOWER);
            $httpProxy = isset($envVars['http_proxy']) ? $envVars['http_proxy'] : null;
            $httpsProxy = isset($envVars['https_proxy']) ? $envVars['https_proxy'] : null;
            
            if (!empty($httpProxy)) {
                curl_setopt($ch, CURLOPT_PROXY, $httpProxy);
            } elseif (!empty($httpsProxy)) {
                curl_setopt($ch, CURLOPT_PROXY, $httpsProxy);
            }
            
            $response = curl_exec($ch);
            
            if ($response === false) {
                // If Google search fails, try Brave search as backup
                curl_close($ch);
                
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $backupUrl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');
                curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                
                if (!empty($httpProxy)) {
                    curl_setopt($ch, CURLOPT_PROXY, $httpProxy);
                } elseif (!empty($httpsProxy)) {
                    curl_setopt($ch, CURLOPT_PROXY, $httpsProxy);
                }
                
                $response = curl_exec($ch);
            }
            
            if ($response !== false) {
                // Extract image URLs from the HTML response
                $imageUrls = $this->extractImageUrlsFromPage($response);
                
                // Format the results
                $counter = 0;
                foreach ($imageUrls as $imageUrl) {
                    if ($counter >= 10) break; // Limit to 10 results
                    
                    $results[] = [
                        'source' => 'Web Search',
                        'url' => $imageUrl,
                        'type' => 'logo',
                        'product_name' => $productName
                    ];
                    
                    $counter++;
                }
            }
            
            curl_close($ch);
        } catch (\Exception $e) {
            // Log the error but continue with API results
            \Log::error('Error in web scraping for logos: ' . $e->getMessage());
        }
        
        return $results;
    }
    
    /**
     * Extract image URLs from HTML content
     *
     * @param string $html
     * @return array
     */
    private function extractImageUrlsFromPage($html)
    {
        $imageUrls = [];
        
        try {
            $doc = new \DOMDocument();
            @$doc->loadHTML($html);
            
            $imgTags = $doc->getElementsByTagName('img');
            foreach ($imgTags as $imgTag) {
                $src = $imgTag->getAttribute('src');
                
                // Skip favicons and small icons
                if (!strstr($imgTag->getAttribute('class'), "favicon") &&
                    !strstr($imgTag->getAttribute('class'), "logo") &&
                    $imgTag->getAttribute('width') > 50) {
                    
                    // Ensure it's a valid URL
                    if (filter_var($src, FILTER_VALIDATE_URL)) {
                        $imageUrls[] = $src;
                    }
                    // Handle data URLs
                    elseif (strpos($src, 'data:image/') === 0) {
                        continue; // Skip data URLs for now
                    }
                    // Handle relative URLs
                    elseif (strpos($src, 'http') !== 0 && !empty($src)) {
                        // Convert relative to absolute URL
                        if (strpos($src, '//') === 0) {
                            $src = 'https:' . $src;
                        }
                        
                        if (filter_var($src, FILTER_VALIDATE_URL)) {
                            $imageUrls[] = $src;
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::error('Error extracting image URLs: ' . $e->getMessage());
        }
        
        return $imageUrls;
    }
    
    /**
     * Search for logos from various API services
     *
     * @param string $productName
     * @return array
     */
    private function searchLogoFromApis($productName)
    {
        $results = [];
        
        // NOTE: Clearbit Logo API will be sunset/discontinued
        // This is included as a fallback until fully deprecated
        try {
            // Convert product name to a domain-like format
            $domainName = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $productName));
            
            // Add Clearbit Logo API result
            $clearbitUrl = "https://logo.clearbit.com/{$domainName}.com";
            $results[] = [
                'source' => 'Logo API',
                'url' => $clearbitUrl,
                'type' => 'logo',
                'product_name' => $productName
            ];
            
            // Add with www subdomain
            $clearbitWwwUrl = "https://logo.clearbit.com/www.{$domainName}.com";
            $results[] = [
                'source' => 'Logo API (www)',
                'url' => $clearbitWwwUrl,
                'type' => 'logo',
                'product_name' => $productName
            ];
            
            // Add Favicon API result
            $clearbitFaviconUrl = "https://logo.clearbit.com/{$domainName}.com?size=128";
            $results[] = [
                'source' => 'Favicon API',
                'url' => $clearbitFaviconUrl,
                'type' => 'favicon',
                'product_name' => $productName
            ];
            
            // Add Favicon API with www subdomain
            $clearbitFaviconWwwUrl = "https://logo.clearbit.com/www.{$domainName}.com?size=128";
            $results[] = [
                'source' => 'Favicon API (www)',
                'url' => $clearbitFaviconWwwUrl,
                'type' => 'favicon',
                'product_name' => $productName
            ];
        } catch (\Exception $e) {
            \Log::error('Error in API logo search: ' . $e->getMessage());
        }
        
        return $results;
    }
    
    /**
     * Download and save a logo for a product
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function downloadLogo(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|integer|exists:products,id',
            'logo_url' => 'required|url',
            'type' => 'required|in:logo,favicon',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
        }

        $productId = $request->input('product_id');
        $logoUrl = $request->input('logo_url');
        $type = $request->input('type');
        
        // Get the product to update its image path later
        $product = ProductModel::get($productId);
        if (!$product) {
            return Response::json([
                'status' => false,
                'message' => 'Product not found',
            ]);
        }
        
        try {
            // Download the image
            $imageContents = file_get_contents($logoUrl);
            if (!$imageContents) {
                return Response::json([
                    'status' => false,
                    'message' => 'Failed to download the image',
                ]);
            }
            
            // Create a temporary file
            $tempFile = tempnam(sys_get_temp_dir(), 'logo_');
            file_put_contents($tempFile, $imageContents);
            
            // Create a filename based on the product name
            $fileName = $product->product_name . '.png';
            $fileName = strtolower(preg_replace('/[^a-z0-9]/', '', $fileName)) . '.png';
            
            // Process the image using Intervention Image
            $image = \Intervention\Image\Facades\Image::make($tempFile);
            
            // Create both logo and favicon versions
            $results = [];
            
            // Process logo (320x120)
            if ($type == 'logo' || $request->input('create_both', false)) {
                $logoImage = clone $image;
                $logoImage->resize(320, 120, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                
                // Save to a new temp file
                $logoTempFile = tempnam(sys_get_temp_dir(), 'logo_processed_');
                $logoImage->save($logoTempFile);
                
                // Create an UploadedFile instance for the logo
                $logoUploadedFile = new \Illuminate\Http\UploadedFile(
                    $logoTempFile,
                    $fileName,
                    mime_content_type($logoTempFile),
                    null,
                    true
                );
                
                // Use the existing File model method to save the logo
                $logoResult = File::add_to_product_get_path($logoUploadedFile, $productId, 'logos');
                
                // Clean up the temporary logo file
                @unlink($logoTempFile);
                
                if ($logoResult['status']) {
                    $results['logo'] = $logoResult;
                    // Update the product record with the new logo path
                    $updateData['image'] = $logoResult['path'];
                }
            }
            
            // Process favicon (128x128)
            if ($type == 'favicon' || $request->input('create_both', false)) {
                $faviconImage = clone $image;
                $faviconImage->fit(128, 128, function ($constraint) {
                    $constraint->upsize();
                });
                
                // Save to a new temp file
                $faviconTempFile = tempnam(sys_get_temp_dir(), 'favicon_processed_');
                $faviconImage->save($faviconTempFile);
                
                // Create an UploadedFile instance for the favicon
                $faviconUploadedFile = new \Illuminate\Http\UploadedFile(
                    $faviconTempFile,
                    $fileName,
                    mime_content_type($faviconTempFile),
                    null,
                    true
                );
                
                // Use the existing File model method to save the favicon
                $faviconResult = File::add_to_product_get_path($faviconUploadedFile, $productId, 'favicons');
                
                // Clean up the temporary favicon file
                @unlink($faviconTempFile);
                
                if ($faviconResult['status']) {
                    $results['favicon'] = $faviconResult;
                    // Update the product record with the new favicon path
                    $updateData['favicon'] = $faviconResult['path'];
                }
            }
            
            // Clean up the original temporary file
            @unlink($tempFile);
            
            // Check if we have any successful results
            if (empty($results)) {
                return Response::json([
                    'status' => false,
                    'message' => 'Failed to process and save the image(s)',
                ]);
            }
            
            // Update the product record with the new image paths
            ProductModel::do_update($productId, $updateData);
            
            return Response::json([
                'status' => true,
                'message' => 'Logo successfully downloaded and saved',
                'data' => [
                    'path' => $result['path'],
                    'type' => $type
                ],
            ]);
            
        } catch (\Exception $e) {
            return Response::json([
                'status' => false,
                'message' => 'Error processing the image: ' . $e->getMessage(),
            ]);
        }
    }
}
