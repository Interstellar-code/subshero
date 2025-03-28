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
        parent::__construct();
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        return view('admin/product/logos_and_favicons/index');
    }

    public function import(Request $request) {
        $validator = Validator::make($request->all(), $this->import_validation_rules());
        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => join(' ', $validator->errors()->all()),
            ]);
        }
        $import_result = self::try_import($request);
        return response()->json($import_result);
    }

    public function try_import(Request $request)
    {
        if(!$request->file('images-archive')) {
            return [
                'status' => 0,
                'message' => __('Zip archive is required')
            ];
        }
        $zipFilePath = self::upload_zip($request);
        if(!Zip::check($zipFilePath)) {
            return [
                'status' => 0,
                'message' => __('Bad archive')
            ];
        }
        $zip = Zip::open($zipFilePath);
        $listItems = $zip->listFiles();
        $upperLevelItem = array_shift($listItems);
        $imagesType = $request->post('images-type');
        if($upperLevelItem != "$imagesType/") {
            $zip->close();
            unlink($zipFilePath);
            return [
                'status' => 0,
                'message' => __("Inside the zip archive the upper level directory must be '$imagesType'")
            ];
        }
        $extractToDirectoryPath = base_path('storage/app/client/1/product');
        $isExtracted = $zip->extract($extractToDirectoryPath);
        if(!$isExtracted) {
            $zip->close();
            unlink($zipFilePath);
            return [
                'status' => 0,
                'message' => __('Cannot extract files')
            ];
        }
        $zip->close();
        unlink($zipFilePath);
        $imagesType = ucfirst($imagesType);
        return [
            'status' => 1,
            'message' => __("$imagesType are imported")
        ];
    }

    public function upload_zip(Request $request)
    {
        $zipFile = $request->file('images-archive');
        $zipFile->storeAs('client/1/tmp/', $zipFile->getClientOriginalName());
        $zipFilePath = base_path('storage/app/client/1/tmp/' . $zipFile->getClientOriginalName());
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
