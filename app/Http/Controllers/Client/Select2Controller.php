<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\BrandModel;
use App\Models\FolderModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\SubscriptionModel;
use App\Models\File;
use App\Models\Product;
use App\Models\ProductModel;
use App\Models\TagModel;
use App\Models\UserModel;
use Illuminate\Support\Facades\Session;

class Select2Controller extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');

        if (!Session::has('subscription_days')) {
            Session::put('subscription_days', 365);
        }
        if (!Session::has('lifetime_days')) {
            Session::put('lifetime_days', 365);
        }
    }

    // create a function to search folder by user id
    public function folder_search(Request $request, $search = null)
    {
        $user_id = Auth::user()->id;

        if (empty($search)) {
            $folders = FolderModel::get_by_user($user_id);
        } else {
            $folders = DB::table('folder')
                ->where('user_id', $user_id)
                ->where('name', 'like', '%' . $search . '%')
                ->get();
        }

        // Make output array
        $output = array();
        foreach ($folders as $folder) {
            $output[] = [
                'id' => $folder->id,
                'text' => $folder->name
            ];
        }

        return Response::json($output);
    }

    public function product_search(Request $request, $search = null)
    {
        // Remove unicode characters
        $search_query = lib()->do->filter_unicode($search);

        // Look for internal values
        $data_pricing_type_id = Product::lookPricingType($search_query);
        $data_billing_cycle_id = Product::lookBillingCycle($search_query);

        // Fetch records
        $records = Product::leftJoin('product_types', 'products.product_type', '=', 'product_types.id')
            ->leftJoin('product_categories', 'products.category_id', '=', 'product_categories.id')
            ->leftJoin('product_platforms', 'products.sub_platform', '=', 'product_platforms.id')

            ->where('products.status', 1)
            ->where('products.id', '>', PRODUCT_RESERVED_ID)
            ->where(function ($query) use ($search_query, $data_pricing_type_id, $data_billing_cycle_id) {
                $query->where('products.product_name', 'like', '%' . $search_query . '%')
                    ->orWhere('products.description', 'like', '%' . $search_query . '%')
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
            })->select(
                'products.*',
                'product_types.name as product_type_name',
                'product_categories.name as product_category_name',
                'product_platforms.name as product_platform_name',
            )
            ->limit(10)
            ->get();

        // Make output array
        $output = array();
        foreach ($records as $record) {
            $data = $record->toArray();

            if (empty($record->getRawOriginal('favicon'))) {
                $data['favicon'] = asset_ver('assets/images/favicon.ico');
            }
            $data['image_path'] = $record->getRawOriginal('image');

            $output[] = $data;
        }

        return response()->json($output);
    }
}
