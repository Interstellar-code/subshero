<?php

namespace App\Http\Controllers\Client\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Application as lib;
use App\Models\ApiTokenModel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\File;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Api\User as ApiUser;

class ApiController extends Controller
{
    private $tokenable_type = 'App\Models\Api\User';

    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
    }

    public function index()
    {
        $data = [
            'slug' => 'api',
        ];
        return view('client/settings/api/index', $data);
    }

    public function token_create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                'max:' . len()->personal_access_tokens->name,
                Rule::unique('personal_access_tokens', 'name')
                    ->where(function ($query) {
                        $query->where('tokenable_type', $this->tokenable_type);
                        return $query->where('tokenable_id', Auth::id());
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

        // Check is user is in free plan
        if (empty(lib()->user->plan->id) || lib()->user->plan->id == 1) {
            return Response::json([
                'status' => false,
                'message' => 'You need to upgrade your plan to create an API token.',
            ]);
        }

        // Check limit
        // if (UserModel::limit_reached('api_token')) {
        //     return lib()->do->get_limit_msg($request, 'api_token');
        // }

        $user = ApiUser::where('id', Auth::id())->first();
        $token_obj = $user->createToken($request->input('name'));

        // Get token token
        $token = '';
        $token_arr = explode('|', $token_obj->plainTextToken);
        if (count($token_arr) == 2) {
            $token = hash('sha256', $token_arr[1]);
        }

        $data = [
            'secret_key' => $token_obj->plainTextToken,
        ];

        $status = ApiTokenModel::where('token', $token)
            ->update($data);

        if ($request->ajax()) {
            return Response::json([
                'status' => true,
                'message' => 'Success',
            ], 200);
        } else {
            return back();
        }
    }

    public function token_update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
            'name' => [
                'required',
                'string',
                'max:' . len()->personal_access_tokens->name,
                Rule::unique('personal_access_tokens', 'name')
                    ->ignore($request->input('id'))
                    ->where(function ($query) {
                        $query->where('tokenable_type', $this->tokenable_type);
                        return $query->where('tokenable_id', Auth::id());
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

        $status = ApiTokenModel::do_update($request->input('id'), $data);

        if ($request->ajax()) {
            return Response::json([
                'status' => true,
                'message' => 'Success',
            ], 200);
        } else {
            return back();
        }
    }

    public function token_edit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => [
                'required',
                'integer',
                Rule::exists('personal_access_tokens')->where(function ($query) use ($request) {
                    $query->where('tokenable_type', $this->tokenable_type);
                    $query->where('tokenable_id', Auth::id());
                    return $query->where('id', $request->input('id'));
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

        $data = ApiTokenModel::get($request->input('id'));
        if (empty($data)) {
            return response()->back();
        }

        $output = [
            'slug' => 'settings/api/token/edit',
            'data' => $data,
        ];
        return view('client/settings/api/token/edit', $output);
    }

    public function token_delete(Request $request)
    {
        $validator = Validator::make(request()->all(), [
            'id' => [
                'required',
                'integer',
                Rule::exists('personal_access_tokens')->where(function ($query) use ($request) {
                    $query->where('tokenable_type', $this->tokenable_type);
                    $query->where('tokenable_id', Auth::id());
                    return $query->where('id', $request->input('id'));
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

        // Delete data
        else {
            $data = ApiTokenModel::get($request->input('id'));

            ApiTokenModel::del($request->input('id'));
            DB::table('personal_access_tokens')
                ->where('tokenable_id', Auth::id())
                ->where('token', $data->token)
                ->delete();

            return Response::json([
                'status' => true,
                'message' => 'Success',
            ], 200);
        }
    }
}
