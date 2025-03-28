<?php
namespace App\Models\Traits;
use App\Models\Product;

trait ProductRelatedEntitiesTrait
{
    protected static $products_column_names = [
        'App\Models\ProductCategory' => 'category_id',
        'App\Models\ProductPlatform' => 'sub_platform',
        'App\Models\ProductType'=> 'product_type'
    ];

    protected static $entity_name = [
        'App\Models\ProductCategory' => 'Category',
        'App\Models\ProductPlatform' => 'Platform',
        'App\Models\ProductType'=> 'Type'
    ];

    public static function get_filtered($filter = [])
    {
        $modelName = get_called_class();
        $query = $modelName::select('id', 'name');
        if(isset($filter['searchValue'])) {
            $query->where('name', 'like', "%$filter[searchValue]%");
        }
        if(isset($filter['columnIndex']) && isset($filter['columnSortOrder'])) {
            if($filter['columnIndex'] == 1) {
                $query->orderBy('name', $filter['columnSortOrder']);
            }
        }
        if(isset($filter['start'])) {
            $query->skip($filter['start']);
        }
        if(isset($filter['row_per_page'])) {
            $query->take($filter['row_per_page']);
        }
        return $query->get();
    }

    public static function del($id)
    {
        $model = get_called_class();
        $used = $model::check_used($id);
        if($used == 0) {
            return [
                'status' => $model::find($id)->delete(),
                'message' => __('Success')
            ];
        } else {
            if($used > 1) {
                $s = 's';
            } else {
                $s = '';
            }
            $entity_name = $model::$entity_name[$model];
            return [
                'status' => false,
                'message' => __("$entity_name is used $used time$s")
            ];
        }
    }

    public static function check_used($id)
    {
        $model = get_called_class();
        $products_column = $model::$products_column_names[$model];
        return Product::where($products_column, '=', $id)->count();
    }

    public static function check_used_names($name) {
        $model = get_called_class();
        return $model::where('name', $name)->count();
    }

    public static function create($data) {
        $model = get_called_class();
        $used = $model::check_used_names($data['name']);
        if($used) {
            if($used > 1) {
                $s = 's';
            } else {
                $s = '';
            }
            $result = [
                'status' => false,
                'message' => __("Name $data[name] is already used $used time$s")
            ];
        } else {
            $entity = new $model;
            $entity->name = $data['name'];
            $saved = $entity->save();
            if($saved) {
                $result = [
                    'status' => true,
                    'message' => __('Success'),
                ];
            } else {
                $result = [
                    'status' => false,
                    'message' => __('Cannot save'),
                ];
            }
        }
        return $result;
    }

    public static function updateEntity($data) {
        $model = get_called_class();
        $used = $model::check_used_names($data['name']);
        if($used) {
            if($used > 1) {
                $s = 's';
            } else {
                $s = '';
            }
            $result = [
                'status' => false,
                'message' => __("Name $data[name] is already used $used time$s")
            ];
        } else {
            $entity = $model::find($data['id']);
            $entity->name = $data['name'];
            $saved = $entity->save();
            if($saved) {
                $result = [
                    'status' => true,
                    'message' => __('Success'),
                ];
            } else {
                $result = [
                    'status' => false,
                    'message' => __('Cannot save'),
                ];
            }
        }
        return $result;
    }

    public static function count_filtered($filter = []) {
        $modelName = get_called_class();
        if(isset($filter['searchValue'])) {
            return $modelName::where('name', 'like', "%$filter[searchValue]%")->count();
        } else {
            return $modelName::count();
        }
    }
}
