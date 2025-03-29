<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Api\v1\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserAlert;
use App\Models\UserModel;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class AlertController extends Controller
{
    // RESTful API standards

    /**
     * AlertController constructor.
     *
     * This method calls the parent constructor to apply authentication middleware.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
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
                '*.column' => 'required|string|in:' . UserAlert::$sortableColumns,
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
        $query = UserAlert::whereUserId(Auth::id())
            ->where(function ($query) use ($search_query) {
                $query->where('alert_name', 'like', '%' . $search_query . '%');
            })
            ->skip($start)
            ->take($length);

        // Sort the query with multiple columns
        if (!empty($valid_sort_arr)) {
            foreach ($valid_sort_arr as $sort_item) {
                $query->orderBy("users_alert.{$sort_item['column']}", $sort_item['direction']);
            }
        }

        // Get the records
        $records = $query->select(
            'users_alert.*',
        )->get();

        // Return error response if no records found (404 Not Found)
        if ($records->isEmpty()) {
            return response()->json([
                'message' => 'No data found',
            ], 404);
        }

        // Return success response with records (200 OK)
        return response()->json($records, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request)
    {
        // Merge the request with the value from the route parameter and validate the request
        $request->merge(['id' => $request->route('id')]);
        $fields = $request->validate([
            'id' => [
                'required',
                'integer',
                Rule::exists('users_alert', 'id')->where(function ($query) {
                    $query->where('user_id', Auth::id());
                }),
            ],
        ]);

        // Find the record from database and return success response
        $data = UserAlert::find($fields['id']);
        return response()->json($data, 200); // 200 OK status code
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        // Validate the request and get the validated data
        $fields = $request->validate([
            'is_default' => 'nullable|integer|in:0,1',
            'alert_name' => [
                'required',
                'string',
                'max:' . len()->users_alert->alert_name,
                Rule::unique('users_alert', 'alert_name')
                    ->where(function ($query) {
                        return $query->where('user_id', Auth::id());
                    }),
            ],
            'time_period' => 'required|integer|min:0|max:' . len()->users_alert->time_period,
            // 'time_cycle' => 'required|integer',
            'time' => 'required|date_format:H:i',
            'alert_condition' => 'required|integer',
            'alert_contact' => 'nullable|integer',
            // 'alert_type' => 'required|integer',
            'alert_types' => 'required|array|max:' . len()->users_alert->alert_types->array_length,
            'alert_types.*' => 'string|max:' . len()->users_alert->alert_types->string_length,
            'timezone' => 'required|string|max:' . len()->users_alert->timezone,
            'alert_subs_type' => 'required|integer',
        ]);

        // Check limit
        if (UserModel::limit_reached('alert')) {
            return lib()->do->get_limit_msg_api($request, 'alert');
        }

        // Check for default alert
        $is_default = $fields['is_default'] ?? 0;
        if ($is_default) {
            UserAlert::clear_default($fields['alert_subs_type']);
        }

        // Insert data into database
        $row = new UserAlert();
        $row->user_id = Auth::id();
        $row->is_default = $is_default;
        $row->alert_name = $fields['alert_name'];
        $row->time_period = $fields['time_period'];
        // $row->time_cycle = $fields['time_cycle'];
        $row->time = $fields['time'];
        $row->alert_condition = $fields['alert_condition'];
        $row->alert_contact = $fields['alert_contact'] ?? null;
        // $row->alert_type = $fields['alert_type'];
        $row->alert_types = $fields['alert_types'];
        $row->timezone = $fields['timezone'];
        $row->alert_subs_type = $fields['alert_subs_type'];
        $row->save();

        // Return success response with data
        return response()->json($row, 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        // Merge the request with the value from the route parameter and validate the request
        $request->merge(['id' => $request->route('id')]);
        $fields = $request->validate([
            'id' => [
                'required',
                'integer',
                Rule::exists('users_alert', 'id')->where(function ($query) {
                    $query->where('user_id', Auth::id());
                }),
            ],
            'is_default' => 'nullable|integer|in:0,1',
            'alert_name' => 'required|string|max:' . len()->users_alert->alert_name,
            'time_period' => 'required|integer|min:0|max:' . len()->users_alert->time_period,
            // 'time_cycle' => 'required|integer',
            'time' => 'required|date_format:H:i',
            'alert_condition' => 'required|integer',
            'alert_contact' => 'nullable|integer',
            // 'alert_type' => 'required|integer',
            'alert_types' => 'required|array|max:' . len()->users_alert->alert_types->array_length,
            'alert_types.*' => 'string|max:' . len()->users_alert->alert_types->string_length,
            'timezone' => 'required|string|max:' . len()->users_alert->timezone,
            'alert_subs_type' => 'required|integer',
        ]);

        // Check for default alert
        $is_default = $fields['is_default'] ?? 0;
        if ($is_default) {
            UserAlert::clear_default($fields['alert_subs_type']);
        }

        // Update data into database
        $row = UserAlert::find($fields['id']);
        $row->is_default = $is_default;
        $row->alert_name = $fields['alert_name'];
        $row->time_period = $fields['time_period'];
        // $row->time_cycle = $fields['time_cycle'];
        $row->time = $fields['time'];
        $row->alert_condition = $fields['alert_condition'];
        $row->alert_contact = $fields['alert_contact'] ?? null;
        // $row->alert_type = $fields['alert_type'];
        $row->alert_types = $fields['alert_types'];
        $row->timezone = $fields['timezone'];
        $row->alert_subs_type = $fields['alert_subs_type'];
        $row->save();


        // Return success response with updated data (200 OK)
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
                Rule::exists('users_alert', 'id')->where(function ($query) {
                    $query->where('user_id', Auth::id());
                }),
            ],
        ]);

        // Check if in use
        $is_using = UserAlert::is_using($fields['id']);
        if ($is_using) {
            return response()->json([
                'message' => __('This alert profile is currently being used.'),
            ], 409);
        }

        // Delete the data from database
        $row = UserAlert::find($fields['id']);
        if ($row) {
            $row->delete();
        }

        // Return success response with no content (204)
        return response()->noContent();
    }
}
