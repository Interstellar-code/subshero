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
use App\Models\File;
use App\Models\Folder;
use App\Models\TagModel;
use App\Models\UserModel;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;
use App\Models\Subscription;

class FolderController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
    }

    public function index()
    {
        return view('client/calendar');
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                'max:20',
                Rule::unique('folder', 'name')
                    ->where(function ($query) {
                        return $query->where('user_id', Auth::id());
                    }),
            ],
            'color' => 'nullable|string|max:10',
            'is_default' => 'nullable|integer|in:0,1',
            'price_type' => 'required|string|max:3',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
            // abort(419);
        }

        // Check limit
        if (UserModel::limit_reached('folder')) {
            return lib()->do->get_limit_msg($request, 'folder');
        }

        $is_default = $request->input('is_default') ? 1 : 0;

        if ($is_default) {
            FolderModel::clear_default();
        }

        $folder_data = [
            'user_id' => Auth::id(),
            'name' => $request->input('name'),
            'color' => $request->input('color'),
            'is_default' => $is_default,
            'price_type' => $request->input('price_type'),
        ];

        FolderModel::create($folder_data);

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

        return Response::json(FolderModel::get($request->input('id')));
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
        return Response::json(FolderModel::get($request->input('id')));

        $data = [
            'slug' => 'folder',
            // 'folder' => FolderModel::get_by_user(),
            'data' => FolderModel::get($request->input('id')),
            'data_tags' => FolderModel::get_tags_arr($request->input('id')),
        ];

        return view('client/folder/edit', $data);
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

        // Check if in use
        $is_using = FolderModel::is_using($request->input('id'));
        if ($is_using) {
            return Response::json([
                'status' => false,
                'message' => __('This folder is currently being used.'),
            ]);
        }

        // Delete data
        else {
            return Response::json([
                'status' => FolderModel::del($request->input('id')),
                'message' => 'Success',
            ], 200);
        }
    }


    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => [
                'required',
                'integer',
                function ($attribute, $value, $fail) use ($request) {
                    $price_type = $request->input('price_type');
                    if ($price_type == 'All') {
                        return;
                    }
                    $subscriptions = Subscription::where('folder_id', $value)->where('user_id', Auth::id())->select('price_type', 'product_name')->get();
                    foreach ($subscriptions as $subscription) {
                        if ($subscription->price_type != $price_type) {
                            $fail(__('The currency code ') . $subscription->price_type . __(' of subscription ') . $subscription->product_name . __(" doesn't match the currency code ") . $price_type . __(' of the folder ') . $request->input('name') . '.');
                        }
                    }
                }
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
            'color' => 'nullable|string|max:' . len()->folder->color,
            'is_default' => 'nullable|integer|in:0,1',
            'price_type' => 'required|string|max:3',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
            // abort(419);
        }

        $is_default = $request->input('is_default') ? 1 : 0;

        if ($is_default) {
            FolderModel::clear_default();
        }

        $folder_id = $request->input('id');
        $data = [
            'user_id' => Auth::id(),
            'name' => $request->input('name'),
            'color' => $request->input('color'),
            'is_default' => $is_default,
            'price_type' => $request->input('price_type'),
        ];
        $status = FolderModel::do_update($folder_id, $data);

        if ($request->ajax()) {
            return Response::json([
                'status' => true,
                'message' => 'Success',
            ], 200);
        } else {
            return back();
        }
    }

    public function refresh(Request $request)
    {
        return view('client/folder/refresh');
    }

    public function session_clear(Request $request)
    {
        // $request->session()->forget('subscription_folder_id');
        // $request->session()->save();

        $_SESSION['subscription_folder_id'] = null;

        return true;
    }

    public function session_set(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return false;
        }

        // $request->session()->put('subscription_folder_id', $request->input('id'));
        // $request->session()->save();

        $_SESSION['subscription_folder_id'] = $request->input('id');

        return true;
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

        $search = $request->input('q');

        $folders = Folder::where('folder.user_id', Auth::id())
            ->where(function ($query) use ($search) {
                $query->where('folder.name', 'like', '%' . $search . '%');
            })
            ->limit(10)
            ->get()
            ->toArray();
        foreach ($folders as &$folder) {
            $folder['currency_symbol'] = lib()->config->currency_symbol[$folder['price_type']] ?? 'All';
        }
        return response()->json($folders);
    }
}
