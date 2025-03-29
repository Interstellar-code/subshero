<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Application as lib;
use Illuminate\Support\Carbon;
use App\Models\FolderModel;
use App\Models\SubscriptionModel;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    public function __construct()
    {
        // Call the parent constructor to inherit base controller functionality
        parent::__construct();
        // Apply the 'auth' middleware to ensure only authenticated users can access this controller's methods
        $this->middleware('auth');
    }

    public function index()
    {
        // Set the slug variable for identifying the current page in the view
        $data = [
            'slug' => 'admin/product',
        ];

        // Return the 'admin/product/index' view, passing the $data array for use in the view
        return view('admin/product/index', $data);
    }
}
