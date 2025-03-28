<?php

namespace App\Models;

use App\BaseModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class File extends BaseModel
{

    protected $fillable = ['relation', 'relation_id', 'name', 'extension', 'mime_type', 'description', 'size', 'path'];
    // public static const _type = ['Unknown', 'Subscription', 'Trial', 'Lifetime', 'Revenue'];
    public $timestamps = false;

    public static function get($id = NULL)
    {
        if (empty($id)) {
            return DB::table('files')
                ->where('is_deleted', 0)
                ->get();
        } else {
            return DB::table('files')
                ->where('id', $id)
                ->where('is_deleted', 0)
                ->get()
                ->first();
        }
    }

    public static function get_by_relation($relation, $relation_id)
    {
        return DB::table('files')
            ->where('relation', $relation)
            ->where('relation_id', $relation_id)
            ->where('is_deleted', 0)
            ->orderBy('id', 'desc')
            ->limit(1)
            ->get()
            ->first();
    }

    public static function del_by_relation($relation, $relation_id)
    {
        return DB::table('files')
            ->where('relation', $relation)
            ->where('relation_id', $relation_id)
            ->update(['is_deleted' => 1]);
    }

    // public function save($data)
    // {
    //     return DB::table('files')->insertGetId([
    //         'relation_id' => (empty($data['relation_id']) ? NULL : $data['relation_id']),
    //         'relation' => (empty($data['relation']) ? NULL : $data['relation']),
    //         'name' => (empty($data['name']) ? NULL : $data['name']),
    //         'description' => (empty($data['description']) ? NULL : $data['description']),
    //         'size' => (empty($data['size']) ? NULL : $data['size']),
    //         'path' => (empty($data['path']) ? NULL : $data['path']),
    //     ]);
    // }

    public static function add($file, $relation, $relation_id, $path = NULL, $return = NULL)
    {
        $path = (string) $path;
        // Check if slash is required
        if (!empty($path) && $path[0] !== '/') {
            $path = '/' . $path;
        }

        $id = DB::table('files')->insertGetId([
            'relation' => $relation,
            'relation_id' => $relation_id,
            'name' => $file->getClientOriginalName(),
            'extension' => $file->getClientOriginalExtension(),
            'mime_type' => $file->getMimeType(),
            'description' => json_encode($file),
            'size' => $file->getSize(),
            'path' => $storage_path = $file->store($relation . '/' . $relation_id . $path),
        ]);

        // Check if Return type is path
        if ($return == 'path') {
            return $storage_path;
        }

        return $id;
    }

    public static function add_get_path($file, $relation, $relation_id, $path = NULL, $return = NULL)
    {
        $path = (string) $path;
        // Check if slash is required
        if (!empty($path) && $path[0] !== '/') {
            $path = '/' . $path;
        }

        // Set custom path and upload file
        $path = 'client/1/user/' . Auth::id();
        if ($relation == 'avatar') {
            $storage_path = $file->storeAs($path, 'avatar.jpg');
        } else {
            $path .= '/' . $relation;
            $storage_path = $file->store($path);
        }

        $id = DB::table('files')->insertGetId([
            'relation' => $relation,
            'relation_id' => $relation_id,
            'name' => $file->getClientOriginalName(),
            'extension' => $file->getClientOriginalExtension(),
            'mime_type' => $file->getMimeType(),
            'description' => json_encode($file),
            'size' => $file->getSize(),
            'path' => $storage_path,
        ]);

        // Check if Return type is path
        if ($return == 'path') {
            return $storage_path;
        }

        return $storage_path;
    }

    public static function add_to_product_get_path($file, $product_id, $image_type = 'logos')
    {
        $allowed_types = ['logos', 'favicons'];
        if (!in_array($image_type, $allowed_types)) {
            return [
                'status' => false,
                'error' => __('Incorrect image type')
            ];
        }
        $filename = $file->getClientOriginalName();
        if (!File::check_filename($filename)) {
            return [
                'status' => false,
                'error' => __('Incorrect filename ') . $filename . __('. Allowed characters are a-z, and a type of the file must be png.')
            ];
        }
        $relation = 'product';
        $unique = [
            'relation' => $relation,
            'relation_id' => $product_id
        ];
        $data = [
            'name' => $file->getClientOriginalName(),
            'extension' => $file->getClientOriginalExtension(),
            'mime_type' => $file->getMimeType(),
            'description' => json_encode($file),
            'size' => $file->getSize(),
            'path' => $file->storeAs("client/1/$relation/$image_type", $filename),
        ];
        $result = File::updateOrCreate($unique, $data);
        if (self::count_product_related_files($product_id) > 1) {
            self::clear_product_related_files($product_id);
        }
        return [
            'status' => true,
            'path' => $result->path
        ];
    }

    protected static function count_product_related_files($product_id)
    {
        return File::where('relation', 'product')
            ->where('relation_id', $product_id)
            ->count();
    }

    protected static function clear_product_related_files($product_id)
    {
        $files = File::where('relation', 'product')
            ->where('relation_id', $product_id)
            ->get();
        $files->shift();
        foreach ($files as $file) {
            $file->delete();
        }
    }

    public static function update_images($request, $product_id)
    {
        $result = ['status' => true, 'message' => __('Success')];
        if ($request->hasFile('image')) {
            $image_path = File::add_to_product_get_path($request->file('image'), $product_id);
            if ($image_path['status']) {
                ProductModel::do_update($product_id, [
                    'image' => $image_path['path']
                ]);
                $result = ['status' => true, 'message' => __('Success')];
            } else {
                $result = ['status' => false, 'message' => $image_path['error']];
                return $result;
            }
        }
        if ($request->hasFile('image_favicon')) {
            $image_path = File::add_to_product_get_path($request->file('image_favicon'), $product_id, 'favicons');
            if ($image_path['status']) {
                ProductModel::do_update($product_id, [
                    'favicon' => $image_path['path']
                ]);
                $result = ['status' => true, 'message' => __('Success')];
            } else {
                $result = ['status' => false, 'message' => $image_path['error']];
            }
        }
        return $result;
    }

    protected static function check_filename($filename)
    {
        return preg_match('/^[a-z]+\.png$/', $filename);
    }
}
