<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Api\v1\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Folder;
use App\Models\FolderModel;
use App\Models\UserModel;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class FolderController extends Controller
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
                '*.column' => 'required|string|in:' . Folder::$sortableColumns,
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
        $query = Folder::where('folder.user_id', Auth::id())
            ->where(function ($query) use ($search_query) {
                $query->where('folder.name', 'like', '%' . $search_query . '%');
            })
            ->skip($start)
            ->take($length);

        // Sort the query with multiple columns
        if (!empty($valid_sort_arr)) {
            foreach ($valid_sort_arr as $sort_item) {
                $query->orderBy("folder.{$sort_item['column']}", $sort_item['direction']);
            }
        }

        // Get the records
        $records = $query->select(
            'folder.*',
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
                Rule::exists('folder', 'id')->where(function ($query) {
                    $query->where('user_id', Auth::id());
                }),
            ],
        ]);

        // Find the record from database and return success response
        $data = Folder::find($fields['id']);
        return response()->json($data, 200);
    }

    public function create(Request $request)
    {
        // Validate the request and get the validated data
        $fields = $request->validate([
            'name' => [
                'required',
                'string',
                'max:' . len()->folder->name,
                Rule::unique('folder', 'name')
                    ->where(function ($query) {
                        return $query->where('user_id', Auth::id());
                    }),
            ],
            'color' => [
                'nullable',
                'string',
                'regex:' . lib()->regex->rgb_color,
                'max:' . len()->folder->color,
            ],
            'is_default' => 'nullable|integer|in:0,1',
        ]);

        // Check limit
        if (UserModel::limit_reached('folder')) {
            return lib()->do->get_limit_msg_api($request, 'folder');
        }

        // Check for default folder
        $is_default = $fields['is_default'] ?? 0;
        if ($is_default) {
            Folder::clearDefault();
        }

        // Insert data into database
        $row = new Folder();
        $row->user_id = Auth::id();
        $row->name = $fields['name'];
        $row->color = $fields['color'] ?? null;
        $row->is_default = $is_default;
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
                Rule::exists('folder', 'id')->where(function ($query) {
                    $query->where('user_id', Auth::id());
                }),
            ],
            'name' => [
                'required',
                'string',
                'max:' . len()->folder->name,
                Rule::unique('folder', 'name')
                    ->ignore($request->input('id'))
                    ->where(function ($query) {
                        return $query->where('user_id', Auth::id());
                    }),
            ],
            'color' => [
                'nullable',
                'string',
                'regex:' . lib()->regex->rgb_color,
                'max:' . len()->folder->color,
            ],
            'is_default' => 'nullable|integer|in:0,1',
        ]);

        // Check for default folder
        $is_default = $fields['is_default'] ?? 0;
        if ($is_default) {
            Folder::clearDefault();
        }

        // Update data into database
        $row = Folder::find($fields['id']);
        $row->name = $fields['name'];
        $row->color = $fields['color'] ?? null;
        $row->is_default = $is_default;
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
                Rule::exists('folder', 'id')->where(function ($query) {
                    $query->where('user_id', Auth::id());
                }),
            ],
        ]);

        // Check if in use
        $is_using = FolderModel::is_using($fields['id']);
        if ($is_using) {
            return response()->json([
                'message' => __('This folder is currently being used.'),
            ], 409);
        }

        // Delete the data from database
        $row = Folder::find($fields['id']);
        $row->delete();

        // Return success response with no content (204)
        return response()->noContent();
    }
}
