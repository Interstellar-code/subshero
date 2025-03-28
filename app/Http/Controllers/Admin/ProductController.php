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
        parent::__construct();
        $this->middleware('auth');
    }

    public function index()
    {
        $data = [
            'slug' => 'admin/product',
        ];
        return view('admin/product/index', $data);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
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
        $result = File::update_images($request, $product_id);

        if ($request->ajax()) {
            return Response::json($result, 200);
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
}
