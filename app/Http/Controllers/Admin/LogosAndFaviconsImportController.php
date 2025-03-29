<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use ZanySoft\Zip\Zip;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class LogosAndFaviconsImportController extends Controller
{
    public function __construct()
    {
        // Call the parent constructor
        parent::__construct();
        // Ensure the user is authenticated
        $this->middleware('auth');
    }

    /**
     * Display the form for importing logos and favicons.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Return the logos and favicons import view
        return view('admin/product/logos_and_favicons/index');
    }

    /**
     * Handles the import of logos and favicons from a zip archive.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function import(Request $request) {
        // Validate the request data against the defined import validation rules
        $validator = Validator::make($request->all(), $this->import_validation_rules());
        // If the validation fails, return a JSON response with the error messages
        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => join(' ', $validator->errors()->all()),
            ]);
        }
        // Attempt to import the logos and favicons using the try_import method
        $import_result = self::try_import($request);
        // Return the result of the import attempt as a JSON response
        return response()->json($import_result);
    }

    /**
     * Attempts to import the logos and favicons from the uploaded zip archive.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function try_import(Request $request)
    {
        // Check if the 'images-archive' file is present in the request
        if(!$request->file('images-archive')) {
            return [
                'status' => 0,
                'message' => __('Zip archive is required')
            ];
        }
        // Upload the zip archive to a temporary location
        $zipFilePath = self::upload_zip($request);
        // Check if the uploaded zip archive is valid
        if(!Zip::check($zipFilePath)) {
            return [
                'status' => 0,
                'message' => __('Bad archive')
            ];
        }
        // Open the zip archive
        $zip = Zip::open($zipFilePath);
        // Get the list of items in the zip archive
        $listItems = $zip->listFiles();
        // Get the upper level item in the zip archive
        $upperLevelItem = array_shift($listItems);
        // Get the image type from the request
        $imagesType = $request->post('images-type');
        // Check if the upper level item matches the image type
        if($upperLevelItem != "$imagesType/") {
            $zip->close();
            unlink($zipFilePath);
            return [
                'status' => 0,
                'message' => __("Inside the zip archive the upper level directory must be '$imagesType'")
            ];
        }
        // Set the directory path to extract the files to
        $extractToDirectoryPath = base_path('storage/app/client/1/product');
        // Extract the files from the zip archive
        $isExtracted = $zip->extract($extractToDirectoryPath);
        // If the extraction fails, return an error message
        if(!$isExtracted) {
            $zip->close();
            unlink($zipFilePath);
            return [
                'status' => 0,
                'message' => __('Cannot extract files')
            ];
        }
        // Close the zip archive
        $zip->close();
        // Delete the temporary zip file
        unlink($zipFilePath);
        // Convert the image type to uppercase
        $imagesType = ucfirst($imagesType);
        return [
            'status' => 1,
            'message' => __("$imagesType are imported")
        ];
    }

    /**
     * Uploads the zip archive to a temporary location.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    public function upload_zip(Request $request)
    {
        // Get the uploaded zip file from the request
        $zipFile = $request->file('images-archive');
        // Store the zip file in a temporary directory
        $zipFile->storeAs('client/1/tmp/', $zipFile->getClientOriginalName());
        // Get the path to the uploaded zip file
        $zipFilePath = base_path('storage/app/client/1/tmp/' . $zipFile->getClientOriginalName());
        // Return the path to the uploaded zip file
        return $zipFilePath;
    }

    protected function import_validation_rules()
    {
        return [
            'images-type' => 'required|string|in:logos,favicons',
            'images-archive' => 'required|file|mimes:zip'
        ];
    }

    public function check_favicons()
    {
        $favicons = DB::table('products')->where('favicon', '!=', null)->pluck('favicon')->all();
        $favicons = array_unique($favicons);
        $csvFileName = uniqid() . '.csv';
        Storage::append($csvFileName, __('path,status'));
        foreach($favicons as $favicon) {
            $status = file_exists(Storage::path($favicon)) ? __('found') : __('not found');
            Storage::append($csvFileName, "$favicon,$status");
        }
        return Storage::download($csvFileName, 'faviconsCheck.csv');
    }
}
