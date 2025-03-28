<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $route_name = \Request::route()->getName();


        // Guest routes
        $ignore_guest = [
            'cron/specific',
            'webhook/admin/v1',
            'login',
            'logout',
            'register',
            'password.request',
            'password.email',
            'password.reset',
            'password.update',
            'user/confirm',
            'user/team/invite/accept',
        ];


        // Auth routes
        $ignore_auth = [
            'maintenance',
            'cron/specific',
            'user/email/verify',
            'user/email/verify/token',
            'user/team/invite/accept',
        ];

        // Authenticate routes
        if (in_array($route_name, $ignore_guest) && in_array($route_name, $ignore_auth)) {
            return;
        } else if (in_array($route_name, $ignore_guest)) {
            $this->middleware('guest');
            return;
        } else {
            $this->middleware('auth');
        }


        // Check maintenance
        if ($route_name != 'maintenance') {
            if (Storage::disk('local')->exists('maintenance.txt')) {

                $this->middleware(function ($request, $next) {
                    $user_id = Auth::id();
                    $user = UserModel::get($user_id);
                    if (!empty($user) && $user->role_id != 1) {
                        return redirect()->route('maintenance');
                    }

                    return $next($request);
                });
            }

            // Authenticate routes middleware
            if (in_array($route_name, $ignore_auth)) {
                return;
            }



            // Check if user is verified
            $this->middleware(function ($request, $next) use ($route_name) {
                $user_id = Auth::id();
                $user = UserModel::get($user_id);

                if (empty($user)) {
                    Auth::logout();
                    return redirect()->route('login');
                } else {
                    if ($user->status == 1) {
                    } else if ($user->status == 0) {
                        return redirect()->route('user/email/verify');
                    } else {
                        Auth::logout();
                        return redirect()->route('login');
                    }
                }

                return $next($request);
            });
        }


        // Check if user is admin
        $this->middleware(function ($request, $next) use ($route_name) {

            $user_id = Auth::id();
            $user = UserModel::get($user_id);

            if (!empty($user)) {

                // Check if admin found in the route
                if (strpos($route_name, 'admin') !== false) {

                    // Check for non admin user
                    if ($user->role_id != 1) {
                        return redirect()->route('/');
                    }
                }

                // Check if admin not found in the route
                else {

                    // Check for admin user
                    if ($user->role_id == 1) {
                        return redirect()->route('admin/index');
                    }
                }
            }

            return $next($request);
        });


        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function maintenance(Request $request)
    {
        if (Storage::disk('local')->exists('maintenance.txt')) {
            $user_id = Auth::id();
            $user = UserModel::get($user_id);

            if (!empty($user) && $user->role_id != 1) {
                return view('auth/maintenance');
            }
            return redirect()->route('/');
        } else {
            return redirect()->route('/');
        }
    }
}
