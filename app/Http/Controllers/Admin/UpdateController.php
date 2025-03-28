<?php

namespace App\Http\Controllers\Admin;

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
use App\Models\UserModel;
use Illuminate\Support\Facades\Hash;
use App\Models\SettingsModel;
use Illuminate\Support\Facades\Mail;
use App\Mail\Test;
use App\Models\EmailModel;
use App\Models\EmailTemplate;
use App\Library\NotificationEngine;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use ZanySoft\Zip\Zip;
use ZanySoft\Zip\ZipFacade;
use PhpMyAdmin\SqlParser\Parser;
use Illuminate\Support\Facades\Artisan;

class UpdateController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');


        // Load updater
        require_once(app_path('../modules/updater/script_update_config.php'));
        require_once(app_path('../modules/updater/script_update_functions.php'));


        // Command definition
        $this->all_command = [
            'version_check' => [
                'message' => [
                    'request' => __('Checking for update'),
                    'success' => __('A new version is available: '),
                    'failure' => __('Version check failed'),
                    'not_available' => __('No updates available'),
                ],
                'next_command' => 'update_start',
            ],
            'update_start' => [
                'message' => [
                    'request' => __('Starting update to the new version'),
                    'success' => __('Update started to the new version'),
                    'failure' => __('Failed to start update'),
                ],
                'next_command' => 'maintenance_enable',
            ],
            'maintenance_enable' => [
                'message' => [
                    'request' => __('Checking maintenance mode'),
                    'success' => __('Maintenance mode enabled'),
                    'failure' => __('Failed to enable maintenance mode'),
                ],
                'next_command' => 'download_start',
            ],
            'download_start' => [
                'message' => [
                    'request' => __('Downloading files for new update'),
                    'success' => __('Update package successfully downloaded'),
                    'failure' => __('Failed to download update package'),
                ],
                'next_command' => 'extract_archive',
            ],
            'extract_archive' => [
                'message' => [
                    'request' => __('Extracting archive files'),
                    'success' => __('Extraction completed'),
                    'failure' => __('Failed to extract package files'),
                ],
                'next_command' => 'sql_execute',
            ],
            'sql_execute' => [
                'message' => [
                    'request' => __('Executing sql queries'),
                    'success' => __('SQL queries execution successful'),
                    'failure' => __('Failed to execute SQL queries'),
                ],
                'next_command' => 'files_replace',
            ],
            'files_replace' => [
                'message' => [
                    'request' => __('Replacing files to the new version'),
                    'success' => __('All files successfully replaced'),
                    'failure' => __('Permission denied to replace files'),
                ],
                'next_command' => 'catalog_check',
            ],
            'catalog_check' => [
                'message' => [
                    'request' => __('Checking for catalog sql'),
                    'success' => __('Found new catalog files'),
                    'failure' => __('No catalog files found'),
                ],
                'next_command' => 'catalog_execute',
            ],
            'catalog_execute' => [
                'message' => [
                    'request' => __('Executing catalog sql files'),
                    'success' => __('Catalog files successfully executed'),
                    'failure' => __('Catalog files failed to execute'),
                ],
                'next_command' => 'maintenance_disable',
            ],
            'maintenance_disable' => [
                'message' => [
                    'request' => __('Checking maintenance mode'),
                    'success' => __('Maintenance mode disabled'),
                    'failure' => __('Failed to disable maintenance mode'),
                ],
                'next_command' => 'update_success',
            ],
            'update_success' => [
                'message' => [
                    'request' => __(''),
                    'success' => __('Application update successfully completed'),
                    'failure' => __('Failed to update'),
                ],
                'next_command' => '',
            ],
        ];
    }


    public function index(Request $request)
    {
        $settings = SettingsModel::get_arr(1);
        $data = [
            'slug' => 'admin/settings/update',
            // 'data' => SettingsModel::get($this->id),
            'all_command' => $this->all_command,
            'version_number' => $settings->versions_name,
        ];
        return view('admin/settings/update/index', $data);
    }

    public function handle(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'command' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
        }

        $command = $request->input('command');

        switch ($command) {
            case 'version_check':
                return $this->cmd_version_check($request, 'version_check');
                break;

            case 'update_start':
                return $this->cmd_update_start($request, 'update_start');
                break;

            case 'maintenance_enable':
                return $this->cmd_maintenance_enable($request, 'maintenance_enable');
                break;

            case 'download_start':
                return $this->cmd_download_start($request, 'download_start');
                break;

            case 'extract_archive':
                return $this->cmd_extract_archive($request, 'extract_archive');
                break;

            case 'sql_execute':
                return $this->cmd_sql_execute($request, 'sql_execute');
                break;

            case 'files_replace':
                return $this->cmd_files_replace($request, 'files_replace');
                break;

            case 'catalog_check':
                return $this->cmd_catalog_check($request, 'catalog_check');
                break;

            case 'catalog_execute':
                return $this->cmd_catalog_execute($request, 'catalog_execute');
                break;

            case 'maintenance_disable':
                return $this->cmd_maintenance_disable($request, 'maintenance_disable');
                break;

            case 'update_success':
                return $this->cmd_update_success($request, 'update_success');
                break;

            default:
                return response()->json([
                    'status' => false,
                    'message' => 'Command not found',
                ]);
        }
    }

    private function cmd_version_check(Request $request, $command)
    {
        $output = [];
        $list_array = $this->get_versions();

        if (empty($list_array)) {
            $output['message'] = $this->all_command[$command]['message']['not_available'];
        } else {

            $download_version = ausGetVersion($list_array[0]['version_number']);
            $version_changelog = $download_version['notification_data'];

            if (empty($version_changelog)) {
                $output['message'] = $this->all_command[$command]['message']['not_available'];
            } else {
                $output['message'] = $this->all_command[$command]['message']['success'] . $list_array[0]['version_number'];
                $output['version_changelog'] = $download_version['notification_data']['version_changelog'];
                $output['version_changelog'] = $list_array[0]['version_number'];

                $output['next_command'] = $this->all_command[$command]['next_command'];

                $request->session()->put('new_version_number', $list_array[0]['version_number']);
            }
        }

        return response()->json($output);
    }

    private function cmd_update_start(Request $request, $command)
    {
        $output = [
            'message' => $this->all_command[$command]['message']['success'],
            'next_command' => $this->all_command[$command]['next_command'],
        ];

        return response()->json($output);
    }

    private function cmd_maintenance_enable(Request $request, $command)
    {
        $output = [
            'message' => $this->all_command[$command]['message']['success'],
            'next_command' => $this->all_command[$command]['next_command'],
        ];

        Storage::disk('local')->put('maintenance.txt', Carbon::now()->toDateTimeString());

        return response()->json($output);
    }

    private function cmd_download_start(Request $request, $command)
    {
        $output = [];

        $list_array = $this->get_versions();
        $download_version = ausGetVersion($list_array[0]['version_number']);
        if (isset($list_array[0]['version_number'])) {

            ini_set('max_execution_time', 0);
            ini_set('memory_limit', '2048M');
            $download_notifications_array = ausDownloadFile('version_upgrade_file', $list_array[0]['version_number']);
            if ($download_notifications_array['notification_case'] == "notification_operation_ok") {
                $download_notifications_array1 = ausDownloadFile('version_upgrade_query', $list_array[0]['version_number']);
                if ($download_notifications_array1['notification_case'] == "notification_operation_ok") {
                    $output['success'] = true;
                    $output['message'] = $this->all_command[$command]['message']['success'];
                    $output['next_command'] = $this->all_command[$command]['next_command'];
                } else {
                    $output['success'] = false;
                    $output['message'] = $this->all_command[$command]['message']['failure'];
                }
            } else {
                $output['success'] = false;
                $output['message'] = $this->all_command[$command]['message']['failure'];
            }
        } else {
            $output['message'] = __('No updates available');
        }

        return response()->json($output);
    }

    private function cmd_extract_archive(Request $request, $command)
    {
        $output = [];

        $zip = Zip::open(base_path('modules/Database_package.zip'));
        $zip->extract(base_path('modules/Database_package'));
        $zip->close();

        $zip = Zip::open(base_path('modules/Update_package.zip'));
        $zip->extract(base_path('modules/Update_package'));
        $zip->close();

        $output = [
            'message' => $this->all_command[$command]['message']['success'],
            'next_command' => $this->all_command[$command]['next_command'],
        ];

        return response()->json($output);
    }

    private function cmd_sql_execute(Request $request, $command)
    {
        $output = [];
        $output = [
            'message' => $this->all_command[$command]['message']['success'],
            'next_command' => $this->all_command[$command]['next_command'],
        ];

        $db_file = base_path('modules/Database_package/SQLupdate/update.sql');
        $file_content = file_get_contents($db_file); // get db content from file
        $parser = new Parser($file_content);
        if ($parser->errors) {
            $output['success'] = false;
            $output['message'] = 'Error in sql file';
        } else {

            $queries = $parser->statements;
            foreach ($queries as $query) {

                DB::select($query->build() . ';');
            }
        }

        return response()->json($output);
    }

    private function cmd_files_replace(Request $request, $command)
    {
        $output = [];
        $output = [
            'message' => $this->all_command[$command]['message']['success'],
            'next_command' => $this->all_command[$command]['next_command'],
        ];


        $zip = Zip::open(base_path('modules/Update_package.zip'));
        $zip->extract(base_path());
        $zip->close();

        // Clear cache
        $code = Artisan::call('cache:clear');
        $code = Artisan::call('view:clear');
        $code = Artisan::call('config:cache');

        return response()->json($output);
    }

    private function cmd_catalog_check(Request $request, $command)
    {
        $output = [];


        $catalog_file = 'modules/Database_package/catalog/catalog.php';
        if (file_exists(base_path($catalog_file))) {
            $output = [
                'message' => $this->all_command[$command]['message']['success'],
                'next_command' => $this->all_command[$command]['next_command'],
            ];
        } else {
            $output = [
                'message' => $this->all_command[$command]['message']['failure'],
                'next_command' => 'maintenance_disable',
            ];
        }

        return response()->json($output);
    }

    private function cmd_catalog_execute(Request $request, $command)
    {
        $output = [];
        $output = [
            'message' => $this->all_command[$command]['message']['success'],
            'next_command' => $this->all_command[$command]['next_command'],
        ];

        $catalog_file = base_path('modules/Database_package/catalog/catalog.php');

        if (file_exists($catalog_file)) {

            require_once($catalog_file);

            if (function_exists('catalog_execute')) {
                $db_config = Config::get('database.connections.' . Config::get('database.default'));
                $connection = mysqli_connect($db_config["host"], $db_config["username"], $db_config["password"], $db_config["database"]);

                catalog_execute($connection);
            }

            $output = [
                'message' => $this->all_command[$command]['message']['success'],
                'next_command' => $this->all_command[$command]['next_command'],
            ];
        } else {
            $output = [
                'message' => $this->all_command[$command]['message']['failure'],
                'next_command' => $this->all_command[$command]['next_command'],
            ];
        }

        return response()->json($output);
    }

    private function cmd_maintenance_disable(Request $request, $command)
    {
        $output = [];
        $output = [
            'message' => $this->all_command[$command]['message']['success'],
            'next_command' => $this->all_command[$command]['next_command'],
        ];

        Storage::disk('local')->delete('maintenance.txt');

        return response()->json($output);
    }

    private function cmd_update_success(Request $request, $command)
    {
        $output = [];
        $output = [
            'message' => $this->all_command[$command]['message']['success'],
            // 'next_command' => $this->all_command[$command]['next_command'],
        ];

        if ($request->session()->has('new_version_number')) {
            DB::table('versions')
                ->where('id', 1)
                ->update([
                    'versions_name' => $request->session()->get('new_version_number'),
                ]);

            // Create event logs
            NotificationEngine::staticModel('event')::create([
                'admin_id' => Auth::id(),
                'event_type' => 'app_update',
                'event_type_status' => 'update',
                'event_status' => 1,
                'table_name' => 'versions',
                'table_row_id' => 1,
                'event_type_function' => __CLASS__ . '::' . __FUNCTION__ . '()',
            ]);

            $request->session()->forget('new_version_number');
        }

        return response()->json($output);
    }
































    public function check()
    {
        $output = [];
        $list_array = $this->get_versions();

        if (empty($list_array)) {
            $output['message'] = __('No updates available');
        } else {

            $download_version = ausGetVersion($list_array[0]['version_number']);
            $version_changelog = $download_version['notification_data'];

            if (empty($version_changelog)) {
                $output['message'] = __('No updates available');
            } else {
                $output['message'] = __('A new version is available: ') . $list_array[0]['version_number'];
                $output['version_changelog'] = $download_version['notification_data']['version_changelog'];
                $output['version_changelog'] = $list_array[0]['version_number'];
            }
        }





        // $download_version = ausGetVersion($list_array[0]['version_number']);
        // $version_changelog = $download_version['notification_data'];
        // if (!$version_changelog) {
        //     $version_changelog = NULL;
        // } else {
        //     $version_changelog = $download_version['notification_data']['version_changelog'];
        // }
        // // if ($updated == '') {
        // //     $this->db->where('id', '1');
        // //     $this->db->update('versions', array('is_update_available' => 0, 'last_checked' => date('Y-m-d')));
        // // }
        // $version_details  =    array(
        //     'settings' => $settings,
        //     'version' => $list_array[0],
        //     'msg' => $msg,
        //     'updated' => $updated,
        //     'version_changelog' => $version_changelog
        // );


        return response()->json($output);
    }



    private function get_versions()
    {
        $list_array = array();
        $version_notifications_array = ausGetAllVersions();
        $settings = SettingsModel::get_arr(1);

        // Convert to array
        $settings = json_decode(json_encode($settings), true);


        // Fetch local app -> version details
        $local_ver_all = explode('_', $settings['versions_name']);
        if (count($local_ver_all) == 2 && $local_ver_all[1] == 'beta') {
            $local_version = [
                'number' => $local_ver_all[0],
                'is_beta' => true,
            ];
        } else {
            $local_version = [
                'number' => $settings['versions_name'],
                'is_beta' => false,
            ];
        }


        if ($version_notifications_array['notification_case'] == "notification_operation_ok") {
            $version = $version_notifications_array['notification_data']['product_versions'];
            $list_array_log = array();
            $flag = false;
            foreach (array_reverse($version) as $key => $value) {

                // Check if version is active
                if ($value['version_status'] != 1) {
                    continue;
                }

                // Fetch API -> version details
                $api_version_all = explode('_', $value['version_number']);
                if (count($api_version_all) == 2 && $api_version_all[1] == 'beta') {
                    $api_version = [
                        'number' => $api_version_all[0],
                        'is_beta' => true,
                    ];
                } else {
                    $api_version = [
                        'number' => $value['version_number'],
                        'is_beta' => false,
                    ];
                }

                // Check if this is beta version and this app is updatable to beta version
                if ($api_version['is_beta'] && empty($settings['betachk'])) {
                    continue;
                }

                // Beta check disable
                $local_version['is_beta'] = false;
                $api_version['is_beta'] = false;

                // Compare Production and Beta versions

                // (1.4 < 1.5)
                if ($local_version['number'] < $api_version['number'] && $local_version['is_beta'] == false && $api_version['is_beta'] == false) {
                    $list_array[] = [
                        'version_number' => $value['version_number'],
                    ];
                    break;
                }

                // (1.4 < 1.5_beta)
                else if ($local_version['number'] < $api_version['number'] && $local_version['is_beta'] == false && $api_version['is_beta'] == true) {
                    $list_array[] = [
                        'version_number' => $value['version_number'],
                    ];
                    break;
                }

                // (1.4_beta < 1.4)
                else if ($local_version['number'] == $api_version['number'] && $local_version['is_beta'] == true && $api_version['is_beta'] == false) {
                    $list_array[] = [
                        'version_number' => $value['version_number'],
                    ];
                    break;
                }

                // (1.4_beta < 1.5)
                else if ($local_version['number'] < $api_version['number'] && $local_version['is_beta'] == true && $api_version['is_beta'] == false) {
                    $list_array[] = [
                        'version_number' => $value['version_number'],
                    ];
                    break;
                }

                // (1.4_beta < 1.5_beta)
                else if ($local_version['number'] < $api_version['number'] && $local_version['is_beta'] == true && $api_version['is_beta'] == true) {
                    $list_array[] = [
                        'version_number' => $value['version_number'],
                    ];
                    break;
                }

                // If not match with any version comparison
                else {
                    continue;
                }
            }
        }

        if (empty($list_array)) {
            // $list_array[0] = array('version_number' => $settings['versions_name']);
        }

        return $list_array;
    }

    public function download(Request $request)
    {
        $output = [];

        $list_array = $this->get_versions();
        $download_version = ausGetVersion($list_array[0]['version_number']);
        if (isset($list_array[0]['version_number'])) {

            ini_set('max_execution_time', 0);
            ini_set('memory_limit', '2048M');
            $download_notifications_array = ausDownloadFile('version_upgrade_file', $list_array[0]['version_number']);
            if ($download_notifications_array['notification_case'] == "notification_operation_ok") {
                $download_notifications_array1 = ausDownloadFile('version_upgrade_query', $list_array[0]['version_number']);
                if ($download_notifications_array1['notification_case'] == "notification_operation_ok") {
                    $output['success'] = true;
                    $output['message'] = __('Update package has been downloaded successfully');
                } else {
                    $output['success'] = false;
                    $output['message'] = __('Program files could not be downloaded.');
                }
            } else {
                $output['success'] = false;
                $output['message'] = __('Program files could not be downloaded.');
            }
        } else {
            $output['message'] = __('No updates available');
        }

        return response()->json($output);
    }
}
