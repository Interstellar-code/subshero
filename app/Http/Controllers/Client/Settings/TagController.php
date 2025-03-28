<?php

namespace App\Http\Controllers\Client\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Application as lib;
use Illuminate\Support\Carbon;
use App\Models\FolderModel;
use App\Models\PlanModel;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\File;
use App\Models\ProductModel;
use App\Models\SubscriptionModel;
use App\Models\TagModel;
use App\Models\UserModel;
use App\Models\PaymentMethodModel;
use App\Models\Tag;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class TagController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
    }

    public function index()
    {
        $data = [
            'slug' => 'tag',
        ];
        return view('client/settings/tag/index', $data);
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

        return Response::json(TagModel::get($request->input('id')));
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                'max:20',
                Rule::unique('tags', 'name')
                    ->where(function ($query) {
                        return $query->where('user_id', Auth::id());
                    }),
            ],
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
            // abort(419);
        }

        // Check limit
        if (UserModel::limit_reached('tag')) {
            return lib()->do->get_limit_msg($request, 'tag');
        }

        $data = [
            'user_id' => Auth::id(),
            'name' => $request->input('name'),
        ];

        $tag_id = TagModel::create($data);

        if ($request->ajax()) {
            return Response::json([
                'status' => true,
                'message' => 'Success',
            ], 200);
        } else {
            return back();
        }
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
            'name' => [
                'required',
                'string',
                'max:20',
                Rule::unique('tags', 'name')
                    ->ignore($request->input('id'))
                    ->where(function ($query) {
                        return $query->where('user_id', Auth::id());
                    }),
            ],
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
            // abort(419);
        }

        $data = [
            'name' => $request->input('name'),
        ];

        $status = TagModel::do_update($request->input('id'), $data);

        if ($request->ajax()) {
            return Response::json([
                'status' => true,
                'message' => 'Success',
            ], 200);
        } else {
            return back();
        }
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
        $is_using = TagModel::is_using($request->input('id'));
        if ($is_using) {
            return Response::json([
                'status' => false,
                'message' => __('This tag is currently being used.'),
            ]);
        }

        // Delete data
        else {
            return Response::json([
                'status' => TagModel::del($request->input('id')),
                'message' => 'Success',
            ], 200);
        }
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

        $tags = Tag::where('tags.user_id', Auth::id())
            ->where(function ($query) use ($search) {
                $query->where('tags.name', 'like', '%' . $search . '%');
            })
            ->limit(10)
            ->get();

        return response()->json($tags);
    }
}
