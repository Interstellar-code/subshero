<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{
    Response,
    Validator
};


class ProductRelatedEntityController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
    }

    protected function get_model($entity)
    {
        return "App\Models\Product" . ucfirst($entity);
    }

    public function index($productRelatedEntity)
    {
        return view('admin/product/entity/index', ['productRelatedEntity' => $productRelatedEntity]);
    }

    public function datatable_index(Request $request, $productRelatedEntity)
    {
        $productRelatedEntityModel = $this->get_model($productRelatedEntity);
        $search_arr = $request->get('search');
        $searchValue = $search_arr['value'];
        // Remove unicode characters
        $searchValue = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $searchValue);
        $searchValue = preg_replace('/[\x00-\x1F\x7F]/', '', $searchValue);
        $searchValue = preg_replace('/[\x00-\x1F\x7F]/u', '', $searchValue);

        $order_arr = $request->get('order');
        // Ascending (asc) or descending order (desc)
        $columnSortOrder = $order_arr[0]['dir'];
        $columnIndex_arr = $request->get('order');
        $columnIndex = $columnIndex_arr[0]['column'];

        $draw = $request->get('draw');
        $totalRecords = $productRelatedEntityModel::count();
        $filter = [
            'start' => $request->get('start'),
            'row_per_page' => $request->get('length'),
            'searchValue' => $searchValue,
            'columnIndex' => $columnIndex,
            'columnSortOrder' => $columnSortOrder
        ];
        $records = $productRelatedEntityModel::get_filtered($filter);
        if ($searchValue) {
            $totalRecordsWithFilter = $productRelatedEntityModel::count_filtered($filter);
        } else {
            $totalRecordsWithFilter = $totalRecords;
        }
        $data_arr = [];
        foreach ($records as $item) {
            $data_arr[] = [
                'id' => $item->id,
                'name' => $item->name,
                'column_action' => view('admin/datatable/product/entity/column_action', compact('item'))->render()
            ];
        }
        $response = array(
            'draw' => intval($draw),
            'iTotalRecords' => $totalRecords,
            'iTotalDisplayRecords' => $totalRecordsWithFilter,
            'aaData' => $data_arr
        );

        return response()->json($response);
    }

    public function edit(Request $request, $productRelatedEntity)
    {
        $productRelatedEntityModel = $this->get_model($productRelatedEntity);
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
            'slug' => $productRelatedEntity,
            'data' => $productRelatedEntityModel::find($request->input('id')),
            'productRelatedEntity' => $productRelatedEntity
        ];
        return view('admin/product/entity/edit', $data);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_related_entity_name' => 'required|string|max:' . len()->products->product_name,
            'entity' => 'required|string|in:Type,Platform,Category'
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
            // abort(419);
        }

        $productRelatedEntity = $request->input('entity');
        $productRelatedEntityModel = $this->get_model($productRelatedEntity);

        $result = $productRelatedEntityModel::create([
            'name' => $request->input('product_related_entity_name')
        ]);

        if ($request->ajax()) {
            return Response::json($result, 200);
        } else {
            return back();
        }
    }

    public function update(Request $request, $productRelatedEntity)
    {
        $productRelatedEntityModel = $this->get_model($productRelatedEntity);
        $validator = Validator::make($request->all(), [
            'product_related_entity_name' => 'required|string|max:' . len()->products->product_name
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
            // abort(419);
        }

        $result = $productRelatedEntityModel::updateEntity([
            'id' => $request->input('id'),
            'name' => $request->input('product_related_entity_name')
        ]);

        if ($request->ajax()) {
            return Response::json($result, 200);
        } else {
            return back();
        }
    }

    public function delete(Request $request, $productRelatedEntity)
    {
        $productRelatedEntityModel = $this->get_model($productRelatedEntity);
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
        $deletion = $productRelatedEntityModel::del($request->input('id'));
        if ($deletion['status']) {
            return Response::json($deletion, 200);
        } else {
            return Response::json($deletion, 419);
        }
    }
}
