<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\File;
use App\Models\TmpMigratePath;
use App\Models\SubscriptionModel;
use App\Models\SubscriptionAttachment;
use App\Models\User;

class PathsMigrate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'paths:migrate {argument?} {--limit=} {--force} {--can_be_copied}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate old paths to new after update v1.77. To see more info, php artisan paths:migrate help';

    /**
     * Array of possible arguments
     *
     * @var array
     */
    protected $arguments = [
        [
            'name' => 'help',
            'description' => 'Help info about subcommands',
        ],
        [
            'name' => 'all',
            'description' => 'Run all subcommands in correct order with safe mode',
        ],
        [
            'name' => 'prepare_paths',
            'description' => '1. Prepare paths for migration in tmp_migrate_paths table',
        ],
        [
            'name' => 'show_paths',
            'description' => '2. Show paths to migrate from tmp_migrate_paths table. Use --limit option to set count of table lines to show',
        ],
        [
            'name' => 'migrate_paths',
            'description' => '3. Migrate paths',
        ],
        [
            'name' => 'clean_old_files',
            'description' => '4. Remove old files after migration',
        ],
        [
            'name' => 'clean_tmp_paths',
            'description' => '5. Clean paths from tmp_migrate_paths table',
        ],
    ];

    /**
     * Array of model path columns
     * 
     * @var array
     */
    protected $model_path_columns = [
        'File' => [
            'model' => File::class,
            'column' => 'path',
        ],
        'Subscription' => [
            'model' => SubscriptionModel::class,
            'column' => 'image',
        ],
        'SubscriptionAttachment' => [
            'model' => SubscriptionAttachment::class,
            'column' => 'file_path',
        ],
        'User' => [
            'model' => User::class,
            'column' => 'image',
        ],
    ];

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $argument = $this->argument('argument');
        if (!$argument) {
            $this->help();
            return 0;
        } else {
            $allowed_arguments = array_column($this->arguments, 'name');
            if (in_array($argument, $allowed_arguments)) {
                $this->$argument();
                return 0;
            } else {
                $this->error("Argument $argument is not allowed. Allowed arguments: " . implode(', ', $allowed_arguments));
                return 1;
            }
        }

        return 0;
    }

    /**
     * Show all subcommands
     * @return void
     */
    protected function help(): void
    {
        $this->info("List of subcommands:");
        foreach ($this->arguments as $argument) {
            $this->info("php artisan paths:migrate {$argument['name']} - {$argument['description']}");
        }
    }

    /**
     * Prepare paths for migration in tmp_migrate_paths table
     * @return void
     */
    protected function prepare_paths(): void
    {
        $this->info("Prepare paths for migration in tmp_migrate_paths table");
        $count = TmpMigratePath::count();
        if ($count > 0) {
            $this->error("Table tmp_migrate_paths is not empty. Count: $count. Run php artisan paths:migrate help to see all subcommands");
            return;
        }
        $this->prepare_profile_paths();
        $this->prepare_subscription_paths();
    }

    /**
     * Prepare profile paths for migration in tmp_migrate_paths table
     * @return void
     */
    protected function prepare_profile_paths(): void
    {
        $this->info("Prepare profile paths for migration in tmp_migrate_paths table");
        /*
            file
            app\Models\UserModel.php

            path update v1.77, change file name and path
            $destination = "client/1/profile/$user_id/$name.$extension";
            $destination = "client/1/user/$user_id/avatar.jpg";

            files
            app\Http\Controllers\Admin\SettingsController.php
            app\Http\Controllers\Client\SettingsController.php

            path update v1.77, change file relation
            $image_path = File::add_get_path($request->file('picture'), 'profile', Auth::id()); //old
            $image_path = File::add_get_path($request->file('picture'), 'avatar', Auth::id()); //new

            file
            app\Models\File.php

            path update v1.77, change filename for avatar relation
            $path = 'client/1/user/' . Auth::id() . '/';
            if ($relation == 'avatar') {
                $storage_path = $file->storeAs($path, 'avatar.jpg');
            } else {
                $path .= $relation;
                $storage_path = $file->store($path);
            } // add new lines

            Final movement:
                From: "client/1/profile/$user_id/$name.$extension"
                To: "client/1/user/$user_id/avatar.jpg"
            Final relation update:
                From: "profile"
                To: "avatar"
        */

        $files = File::where('relation', 'profile')->get();
        $count_processed = 0;
        foreach ($files as $file) {
            $tmpMigratePath = new TmpMigratePath();
            $tmpMigratePath->model = 'File';
            $tmpMigratePath->row_id = $file->id;
            $tmpMigratePath->old_file_type = 'profile';
            $tmpMigratePath->old_file_path = $file->path;
            $tmpMigratePath->old_file_exists = Storage::disk()->exists($tmpMigratePath->old_file_path);
            $tmpMigratePath->new_file_type = 'avatar';
            $tmpMigratePath->new_file_path = "client/1/user/{$file->relation_id}/avatar.{$file->extension}";
            $tmpMigratePath->new_file_exists = Storage::disk()->exists($tmpMigratePath->new_file_path);
            $tmpMigratePath->save();
            $count_processed++;
        }
        $this->info("$count_processed files with profile relation added to tmp_migrate_paths table");
        $this->prepare_user_model_paths();
    }

    /**
     * Prepare subscription paths for migration in tmp_migrate_paths table
     * @return void
     */
    protected function prepare_subscription_paths(): void
    {
        $this->info("Prepare subscription paths for migration in tmp_migrate_paths table");
        /*
            file
            app\Http\Controllers\Client\SubscriptionController.php

            path update v1.77, change file relation
            $file_path = File::add_get_path($file, 'subscription', $request->input('id'), 'attachments'); //old
            $file_path = File::add_get_path($file, 'attachment', $request->input('id')); //new

            file
            app\Library\Application.php

            path update v1.77, change directory tree
            public $subscription_image_path = '/^client\/1\/subscription\/[0-9]+\/.*$/i';
            public $subscription_image_path = '/^client\/1\/user\/[0-9]+\/subscription\/.*$/i';
            
            Final movement:
                From: "client/1/subscription/$subscription_id/$name.$extension" or "client/1/subscription/$subscription_id/attachments/$name.$extension"
                To: "client/1/user/$user_id/subscription/$name.$extension"

            Final relation update:
                From: "subscription"
                To: "attachment"
        */

        $files = File::where('relation', 'subscription')->get();
        $count_processed = 0;
        foreach ($files as $file) {
            $tmpMigratePath = new TmpMigratePath();
            $tmpMigratePath->model = 'File';
            $tmpMigratePath->row_id = $file->id;
            $tmpMigratePath->old_file_type = 'subscription';
            $tmpMigratePath->old_file_path = $file->path;
            $tmpMigratePath->old_file_exists = Storage::disk()->exists($tmpMigratePath->old_file_path);
            $tmpMigratePath->new_file_type = 'attachment';
            preg_match('!^client/1/(subscription/[0-9]+|user/(?<user_id>[0-9]+)/subscription)/(attachments/)?(?<file_name>.+)$!', $tmpMigratePath->old_file_path, $matches);
            if (empty($matches)) {
                $this->error("Path {$tmpMigratePath->old_file_path} cannot be parsed");
                continue;
            }
            if (isset($matches['user_id']) && $matches['user_id']) {
                $user_id = $matches['user_id'];
            } else {
                $subscription = SubscriptionModel::find($file->relation_id);
                if (!$subscription) {
                    continue;
                }
                $user_id = $subscription->user_id;
            }
            if (isset($matches['file_name']) && $matches['file_name']) {
                $file_name = $matches['file_name'];
            } else {
                $this->error("File name cannot be parsed from {$tmpMigratePath->old_file_path}");
                continue;
            }
            $tmpMigratePath->new_file_path = "client/1/user/{$user_id}/subscription/{$file_name}";
            $tmpMigratePath->new_file_exists = Storage::disk()->exists($tmpMigratePath->new_file_path);
            $tmpMigratePath->save();
            $count_processed++;
        }
        $this->info("$count_processed files with subscription relation added to tmp_migrate_paths table");
        $this->prepare_subscription_model_paths();
        $this->prepare_subscription_attachment_model_paths();
    }

    /**
     * Show paths to migrate
     * @return void
     */
    protected function show_paths(): void
    {
        $this->info("Show prepared paths to migrate");
        $count = TmpMigratePath::count();
        $limit = $this->option('limit');
        if (!$limit) {
            $limit = 10;
        }
        $this->info("Default limit is 10, current limit is $limit. Use --limit option to change limit. Use --can_be_copied option to show only paths that can be copied");
        $this->info("$count paths to migrate were prepared, you can prepare these by php artisan paths:migrate prepare_paths\n");
        $oldExistingCount = TmpMigratePath::where('old_file_exists', true)->count();
        $this->info("$oldExistingCount old paths exists");
        $oldNonExistingCount = TmpMigratePath::where('old_file_exists', false)->count();
        $this->info("$oldNonExistingCount old paths not exists");
        $newExistingCount = TmpMigratePath::where('new_file_exists', true)->count();
        $this->info("$newExistingCount new paths already exists now");
        $canBeCopiedCount = TmpMigratePath::where('old_file_exists', true)->where('new_file_exists', false)->count();
        $this->info("$canBeCopiedCount paths can be copied to new path");
        if ($this->option('can_be_copied')) {
            $this->info("Paths can be copied to new path:");
            $this->info("model, row_id, old_file_type, old_file_path, old_file_exists, new_file_type, new_file_path, new_file_exists");
            $tmpMigratePaths = TmpMigratePath::where('old_file_exists', true)->where('new_file_exists', false)->limit($limit)->get();
            foreach ($tmpMigratePaths as $tmpMigratePath) {
                $this->info("{$tmpMigratePath->model}, {$tmpMigratePath->row_id}, {$tmpMigratePath->old_file_type}, {$tmpMigratePath->old_file_path}, {$tmpMigratePath->old_file_exists}, {$tmpMigratePath->new_file_type}, {$tmpMigratePath->new_file_path}, {$tmpMigratePath->new_file_exists}");
            }
            return;
        }
        if ($count) {
            $this->info("model, row_id, old_file_type, old_file_path, old_file_exists, new_file_type, new_file_path, new_file_exists");
        }
        $tmpMigratePaths = TmpMigratePath::limit($limit)->get();
        foreach ($tmpMigratePaths as $tmpMigratePath) {
            $this->info("{$tmpMigratePath->model}, {$tmpMigratePath->row_id}, {$tmpMigratePath->old_file_type}, {$tmpMigratePath->old_file_path}, {$tmpMigratePath->old_file_exists}, {$tmpMigratePath->new_file_type}, {$tmpMigratePath->new_file_path}, {$tmpMigratePath->new_file_exists}");
        }
    }

    /**
     * Clean tmp paths
     * @return void
     */
    protected function clean_tmp_paths(): void
    {
        if ($this->option('force')) {
            $this->info("Force clean tmp paths from tmp_migrate_paths table");
            TmpMigratePath::truncate();
            return;
        }
        $this->info("Clean tmp paths from tmp_migrate_paths table");
        $tmpMigratePaths = TmpMigratePath::all();
        foreach ($tmpMigratePaths as $tmpMigratePath) {
            $tmpMigratePath->old_file_exists = Storage::disk()->exists($tmpMigratePath->old_file_path);
            $tmpMigratePath->new_file_exists = Storage::disk()->exists($tmpMigratePath->new_file_path);
            $tmpMigratePath->save();
        }
        $oldExistingFiles = TmpMigratePath::where('old_file_exists', true)->select('old_file_path')->get();
        $oldExistingFilesCount = $oldExistingFiles->count();
        if ($oldExistingFilesCount) {
            $this->info("Found $oldExistingFilesCount old existing files. It is recommended to clean by php artisan paths:migrate clean_old_files.");
            $this->info("Old existing files:");
            foreach ($oldExistingFiles as $oldExistingFile) {
                $this->info($oldExistingFile->old_file_path);
            }
        } else {
            $this->info("No old existing files found, it is good");
        }
        $newNonExistingFiles = TmpMigratePath::where('new_file_exists', false)->select('new_file_path')->get();
        $newNonExistingFilesCount = $newNonExistingFiles->count();
        if ($newNonExistingFilesCount) {
            $this->info("Found $newNonExistingFilesCount new non existing files. It is recommended to migrate by php artisan paths:migrate migrate_paths.");
            $this->info("New non existing files:");
            foreach ($newNonExistingFiles as $newNonExistingFile) {
                $this->info($newNonExistingFile->new_file_path);
            }
        } else {
            $this->info("No new non existing files found, it is good");
        }
        if ((!$oldExistingFilesCount) && (!$newNonExistingFilesCount)) {
            $this->info("No files to migrate, it is good");
            TmpMigratePath::truncate();
        } else {
            $this->info("\nYou can also force clean tmp_migrate_paths table by php artisan paths:migrate clean_tmp_paths --force");
        }
    }

    /**
     * Prepare product paths
     * @return void
     */
    protected function prepare_product_paths()
    {
        $this->info("Prepare product paths for migration in tmp_migrate_paths table");
        /*
            file
            app\Models\File.php

            path update v1.72, change directory tree
            'path' => $storage_path = $file->store('client/1/' . $relation . '/' . $relation_id . $path),
            'path' => $file->storeAs("client/1/$relation/$image_type", $file->getClientOriginalName()),

            Final directory tree update:
                From: "client/1/product/$product_id/$generated_file_name"
                To: "client/1/product/(logos|favicons)/$english_file_name"
                // it is not about user files, don't need to move them
                // don't touch tables: products
        */
    }

    /**
     * Prepare subscription model paths
     * @return void
     */
    protected function prepare_subscription_model_paths()
    {
        $this->info("Prepare subscription model paths for migration in tmp_migrate_paths table");
        /*
            model
            Subscription

            Final directory tree update, like on $this->prepare_subscription_paths():
                From: "client/1/subscription/$subscription_id/$name.$extension" or "client/1/subscription/$subscription_id/attachments/$name.$extension"
                To: "client/1/user/$user_id/subscription/$name.$extension"
        */

        $subscriptions = SubscriptionModel::where('image', 'like', 'client/1/subscription/%')->get();
        $count_processed = 0;
        foreach ($subscriptions as $subscription) {
            $tmpMigratePath = new TmpMigratePath();
            $tmpMigratePath->model = 'Subscription';
            $tmpMigratePath->row_id = $subscription->id;
            $tmpMigratePath->old_file_path = $subscription->image;
            $tmpMigratePath->old_file_exists = Storage::disk()->exists($tmpMigratePath->old_file_path);
            preg_match('/\w+\.\w+$/', $tmpMigratePath->old_file_path, $matches);
            if (isset($matches[0]) && $matches[0]) {
                $new_file_name = $matches[0];
            } else {
                $new_file_name = null;
            }
            $tmpMigratePath->new_file_path = "client/1/user/{$subscription->user_id}/subscription/{$new_file_name}";
            if ($tmpMigratePath->new_file_path == $tmpMigratePath->old_file_path) {
                continue;
            }
            $tmpMigratePath->new_file_exists = Storage::disk()->exists($tmpMigratePath->new_file_path);
            if ($new_file_name) {
                $tmpMigratePath->save();
                $count_processed++;
            } else {
                $this->error("Subscription id={$subscription->id} file {$subscription->image} has no image file name");
            }
        }
        $this->info("Processed $count_processed subscriptions with image path to migrate");
    }

    /**
     * Prepare subscription attachment model paths
     * @return void
     */
    protected function prepare_subscription_attachment_model_paths()
    {
        $this->info("Prepare subscription attachment model paths for migration in tmp_migrate_paths table");
        /*
            model subscriptions_attachments
            
            Final directory tree update, like on $this->prepare_subscription_paths():
                From: "client/1/subscription/$subscription_id/$name.$extension" or "client/1/subscription/$subscription_id/attachments/$name.$extension"
                To: "client/1/user/$user_id/subscription/$name.$extension"
        */

        $subscriptionAttachments = SubscriptionAttachment::all();
        $count_processed = 0;
        foreach ($subscriptionAttachments as $subscriptionAttachment) {
            $tmpMigratePath = new TmpMigratePath();
            $tmpMigratePath->model = 'SubscriptionAttachment';
            $tmpMigratePath->row_id = $subscriptionAttachment->id;
            $tmpMigratePath->old_file_path = $subscriptionAttachment->file_path;
            $tmpMigratePath->old_file_exists = Storage::disk()->exists($tmpMigratePath->old_file_path);
            preg_match('/\w+\.\w+$/', $tmpMigratePath->old_file_path, $matches);
            if (isset($matches[0]) && $matches[0]) {
                $new_file_name = $matches[0];
            } else {
                $new_file_name = null;
            }
            $tmpMigratePath->new_file_path = "client/1/user/{$subscriptionAttachment->user_id}/subscription/{$new_file_name}";
            if ($tmpMigratePath->new_file_path == $tmpMigratePath->old_file_path) {
                continue;
            }
            $tmpMigratePath->new_file_exists = Storage::disk()->exists($tmpMigratePath->new_file_path);
            if ($new_file_name) {
                $tmpMigratePath->save();
                $count_processed++;
            } else {
                $this->error("Subscription attachment id={$subscriptionAttachment->id} file {$subscriptionAttachment->path} has no image file name");
            }
        }
        $this->info("Processed $count_processed subscription attachment model paths to migrate");
    }

    /**
     * Prepare User model paths
     * @return void
     */
    protected function prepare_user_model_paths()
    {
        $this->info("Prepare User model paths for migration in tmp_migrate_paths table");
        /*
            model user
            
            Final directory tree update, like on $this->prepare_profile_paths():
                From: "client/1/profile/$user_id/$name.$extension"
                To: "client/1/user/$user_id/avatar.jpg"
        */
        $users = User::all();
        $count_processed = 0;
        foreach ($users as $user) {
            $tmpMigratePath = new TmpMigratePath();
            $tmpMigratePath->model = 'User';
            $tmpMigratePath->row_id = $user->id;
            $tmpMigratePath->old_file_path = $user->image;
            $tmpMigratePath->old_file_exists = Storage::disk()->exists($tmpMigratePath->old_file_path);
            $extension = pathinfo($tmpMigratePath->old_file_path, PATHINFO_EXTENSION);
            if (!$extension) {
                $this->error("User id={$user->id} file {$user->image} has no image file extension");
                continue;
            }
            $tmpMigratePath->new_file_path = "client/1/user/{$user->id}/avatar.$extension";
            if ($tmpMigratePath->new_file_path == $tmpMigratePath->old_file_path) {
                continue;
            }
            $tmpMigratePath->new_file_exists = Storage::disk()->exists($tmpMigratePath->new_file_path);
            $tmpMigratePath->save();
            $count_processed++;
        }
        $this->info("Processed $count_processed user model paths to migrate");
    }

    /**
     * Migrate paths
     * @return void
     */
    protected function migrate_paths(): void
    {
        $this->info("Migrate paths");
        $tmpMigratePaths = TmpMigratePath::all();
        $count = $tmpMigratePaths->count();
        $this->info("Found $count paths to migrate");
        $count_processed = 0;
        foreach ($tmpMigratePaths as $tmpMigratePath) {
            $model = $this->model_path_columns[$tmpMigratePath->model]['model'];
            $column = $this->model_path_columns[$tmpMigratePath->model]['column'];
            $table_row = $model::find($tmpMigratePath->row_id);
            $is_avatar = ($model == File::class && ($table_row->relation == 'profile' || $table_row->relation == 'avatar'));
            $tmpMigratePath->old_file_exists = Storage::disk()->exists($tmpMigratePath->old_file_path);
            $tmpMigratePath->new_file_exists = Storage::disk()->exists($tmpMigratePath->new_file_path);
            if ($tmpMigratePath->new_file_exists && !$is_avatar) {
                $this->error("New file {$tmpMigratePath->new_file_path} already exists");
            }
            if (!$tmpMigratePath->old_file_exists) {
                $this->error("Old file {$tmpMigratePath->old_file_path} does not exist");
            }
            if ($tmpMigratePath->old_file_exists && $tmpMigratePath->old_file_path != $tmpMigratePath->new_file_path) {
                if (!$tmpMigratePath->new_file_exists || $is_avatar) {
                    Storage::disk()->copy($tmpMigratePath->old_file_path, $tmpMigratePath->new_file_path);
                    $tmpMigratePath->new_file_exists = Storage::disk()->exists($tmpMigratePath->new_file_path);
                    $this->info("Copy {$tmpMigratePath->old_file_path} to {$tmpMigratePath->new_file_path}");
                }
            }
            $tmpMigratePath->save();
            if ($tmpMigratePath->new_file_exists) {
                $table_row->$column = $tmpMigratePath->new_file_path;
                $this->info("Replace {$tmpMigratePath->old_file_path} to {$tmpMigratePath->new_file_path} for model {$tmpMigratePath->model}");
                if ($tmpMigratePath->model == 'File') {
                    if ($tmpMigratePath->old_file_type == 'profile') {
                        $table_row->relation = 'avatar';
                    }
                    if ($tmpMigratePath->old_file_type == 'subscription') {
                        $table_row->relation = 'attachment';
                    }
                }
            }
            $table_row->save();
            $count_processed++;
        }
        $this->info("Processed $count_processed paths to migrate");
    }

    /**
     * Clean old files
     * @return void
     */
    protected function clean_old_files(): void
    {
        $this->info("Clean old files");
        $tmpMigratePaths = TmpMigratePath::where('old_file_exists', true)
            ->where('new_file_exists', true)
            ->where('old_file_path', '!=', 'new_file_path')
            ->get();
        $count = $tmpMigratePaths->count();
        $this->info("Found $count old files to clean");
        foreach ($tmpMigratePaths as $tmpMigratePath) {
            $this->info("Delete {$tmpMigratePath->old_file_path}");
            Storage::disk()->delete($tmpMigratePath->old_file_path);
            $tmpMigratePath->old_file_exists = Storage::disk()->exists($tmpMigratePath->old_file_path);
            $tmpMigratePath->save();
        }
    }

    /**
     * Run all subcommands
     * @return void
     */
    public function all(): void
    {
        $this->prepare_paths();
        $this->show_paths();
        $this->migrate_paths();
        $this->show_paths();
        $this->clean_old_files();
        $this->show_paths();
        $this->clean_tmp_paths();
    }
}
