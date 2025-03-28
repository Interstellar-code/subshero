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
use App\Models\ProductModel;
use App\Models\Subscription;
use App\Models\TagModel;
use App\Models\UserModel;
use Illuminate\Support\Facades\Session;

class DataTableController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
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

    public function subscription(Request $request, $search = null)
    {
        $column_map = [
            'column_product_name' => 'product_name',
            'column_type' => 'type',
            'column_due_date' => 'next_payment_date',
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
        $searchValue = lib()->do->filter_unicode($searchValue);

        // Find subscription type
        $subscription_type_id = $this->get_subscription_type($searchValue);
        $subscription_billing_cycle_id = $this->get_subscription_billing_cycle($searchValue);
        $subscription_recurring = $this->get_subscription_recurring($searchValue);


        // Total records
        $totalRecords = Subscription::select('count(*) as allcount')
            ->where('subscriptions.user_id', Auth::id())
            ->count();

        $totalRecordswithFilter = Subscription::select('count(subscriptions.*) as allcount')
            ->leftJoin('products', 'subscriptions.brand_id', '=', 'products.id')
            ->leftJoin('product_types', 'products.product_type', '=', 'product_types.id')
            ->leftJoin('product_categories', 'subscriptions.category_id', '=', 'product_categories.id')
            ->leftJoin('folder', 'subscriptions.folder_id', '=', 'folder.id')
            ->leftJoin('users_payment_methods', 'subscriptions.payment_mode_id', '=', 'users_payment_methods.id')
            ->leftJoin('users_alert', 'subscriptions.alert_id', '=', 'users_alert.id')
            ->where('subscriptions.user_id', Auth::id())
            ->where(local('subscription_folder_id') ? ['subscriptions.folder_id' => local('subscription_folder_id')] : null)

            ->where(function ($query) use ($searchValue, $subscription_type_id, $subscription_billing_cycle_id, $subscription_recurring) {
                $query->where('subscriptions.product_name', 'like', '%' . $searchValue . '%')
                    ->orWhere('subscriptions.payment_date', 'like', '%' . $searchValue . '%')
                    ->orWhereRaw("DATE_FORMAT(subscriptions.payment_date, '%d %M %Y') like ?", ["%$searchValue%"])
                    ->orWhereRaw("DATE_FORMAT(subscriptions.payment_date, '%d %b %Y') like ?", ["%$searchValue%"])
                    ->orWhere('subscriptions.next_payment_date', 'like', '%' . $searchValue . '%')
                    ->orWhereRaw("DATE_FORMAT(subscriptions.next_payment_date, '%d %M %Y') like ?", ["%$searchValue%"])
                    ->orWhereRaw("DATE_FORMAT(subscriptions.next_payment_date, '%d %b %Y') like ?", ["%$searchValue%"])
                    ->orWhere($subscription_type_id ? ['subscriptions.type' => $subscription_type_id] : null)
                    ->orWhere($subscription_billing_cycle_id ? ['subscriptions.billing_cycle' => $subscription_billing_cycle_id] : null)
                    ->orWhere($subscription_recurring !== null ? ['subscriptions.recurring' => $subscription_recurring] : null)
                    ->orWhere('subscriptions.price', 'like', '%' . $searchValue . '%')
                    ->orWhere('subscriptions.price_type', 'like', '%' . $searchValue . '%')
                    ->orWhere('users_payment_methods.name', 'like', '%' . $searchValue . '%')
                    ->orWhere('product_categories.name', 'like', '%' . $searchValue . '%')
                    ->orWhere('product_types.name', 'like', '%' . $searchValue . '%')
                    ->orWhere('subscriptions.product_name', 'like', '%' . $searchValue . '%');
            })

            ->count();

        // Map custom column name
        if (isset($column_map[$columnName])) {
            $columnName = $column_map[$columnName];
        }


        // Fetch records
        $records = Subscription::orderBy($columnName, $columnSortOrder)
            ->leftJoin('products', 'subscriptions.brand_id', '=', 'products.id')
            ->leftJoin('product_types', 'products.product_type', '=', 'product_types.id')
            ->leftJoin('product_categories', 'subscriptions.category_id', '=', 'product_categories.id')
            ->leftJoin('folder', 'subscriptions.folder_id', '=', 'folder.id')
            ->leftJoin('users_payment_methods', 'subscriptions.payment_mode_id', '=', 'users_payment_methods.id')
            ->leftJoin('users_alert', 'subscriptions.alert_id', '=', 'users_alert.id')
            ->leftJoin('subscriptions_attachments', 'subscriptions.id', '=', 'subscriptions_attachments.subscription_id')
            ->where('subscriptions.user_id', Auth::id())
            ->where(local('subscription_folder_id') ? ['folder_id' => local('subscription_folder_id')] : null)

            ->where(function ($query) use ($searchValue, $subscription_type_id, $subscription_billing_cycle_id, $subscription_recurring) {
                $query->where('subscriptions.product_name', 'like', '%' . $searchValue . '%')
                    ->orWhere('subscriptions.payment_date', 'like', '%' . $searchValue . '%')
                    ->orWhereRaw("DATE_FORMAT(subscriptions.payment_date, '%d %M %Y') like ?", ["%$searchValue%"])
                    ->orWhereRaw("DATE_FORMAT(subscriptions.payment_date, '%d %b %Y') like ?", ["%$searchValue%"])
                    ->orWhere('subscriptions.next_payment_date', 'like', '%' . $searchValue . '%')
                    ->orWhereRaw("DATE_FORMAT(subscriptions.next_payment_date, '%d %M %Y') like ?", ["%$searchValue%"])
                    ->orWhereRaw("DATE_FORMAT(subscriptions.next_payment_date, '%d %b %Y') like ?", ["%$searchValue%"])
                    ->orWhere($subscription_type_id ? ['subscriptions.type' => $subscription_type_id] : null)
                    ->orWhere($subscription_billing_cycle_id ? ['subscriptions.billing_cycle' => $subscription_billing_cycle_id] : null)
                    ->orWhere($subscription_recurring !== null ? ['subscriptions.recurring' => $subscription_recurring] : null)
                    ->orWhere('subscriptions.price', 'like', '%' . $searchValue . '%')
                    ->orWhere('subscriptions.price_type', 'like', '%' . $searchValue . '%')
                    ->orWhere('users_payment_methods.name', 'like', '%' . $searchValue . '%')
                    ->orWhere('product_categories.name', 'like', '%' . $searchValue . '%')
                    ->orWhere('product_types.name', 'like', '%' . $searchValue . '%')
                    ->orWhere('subscriptions.product_name', 'like', '%' . $searchValue . '%');
            })

            ->select(
                'subscriptions.*',
                'products.product_name as brand_name',
                'product_types.name as product_type_name',
                'product_categories.name as product_category_name',
                'folder.color as folder_color',
                'folder.name as folder_name',
                'users_payment_methods.name as payment_method_name',
                'users_alert.alert_name as alert_name',
                DB::raw('count(subscriptions_attachments.subscription_id) as attachment_count'),
            )
            ->groupBy('subscriptions.id')
            ->skip($start)
            ->take($row_per_page)
            ->get();

        // $totalRecordswithFilter = $records->count();

        $data_arr = array();

        foreach ($records as $val) {
            if (!$val->type) {
                $val->type = 1;
            }
            if (!$val->billing_cycle) {
                $val->billing_cycle = 3;
            }
            $data_arr[] = array(
                'id' => $val->id,
                'type' => $val->type,
                'brandname' => $val->brandname,
                'product_name' => $val->product_name,
                'next_payment_date' => $val->next_payment_date,
                'price' => lib()->do->get_currency_symbol($val->price_type) . $val->price,
                'payment_method_name' => $val->payment_method_name,
                'created_at' => $val->created_at,
                'column_brand' => view('client/datatable/subscription/column_brand', compact('val'))->render(),
                'column_product_name' => view('client/datatable/subscription/column_product_name', compact('val'))->render(),
                'column_type' => view('client/datatable/subscription/column_type', compact('val'))->render(),
                'column_due_date' => view('client/datatable/subscription/column_due_date', compact('val'))->render(),
                'column_action' => view('client/datatable/subscription/column_action', compact('val'))->render(),
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

    private function get_subscription_type(string $search_term)
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

    private function get_subscription_billing_cycle(string $search_term)
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

    private function get_subscription_recurring(string $search_term)
    {
        // Predefined search terms
        $subscription_type_terms = [

            // Once
            0 => [
                'once',
            ],

            // Recurring
            1 => [
                'recurring',
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
}
