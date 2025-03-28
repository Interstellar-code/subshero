<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BaseModel extends Model
{
    protected static $_user_id = NULL;
    // code

    public static function _add_created(array $data)
    {
        if (!isset($data['created_at'])) {
            $data['created_at'] = lib()->do->timezone_convert();
        }

        if (!isset($data['created_by'])) {
            $data['created_by'] = Auth::id();
        }

        return $data;
    }

    public static function _add_updated(array $data)
    {
        if (!isset($data['updated_at'])) {
            $data['updated_at'] = lib()->do->timezone_convert();
        }

        if (!isset($data['updated_by'])) {
            $data['updated_by'] = Auth::id();
        }

        return $data;
    }

    public static function obj_to_key_val_pair(\Illuminate\Support\Collection $data, $key_name, $val_name)
    {
        // Convert to key value paired array
        $output = [];
        if (!empty($data)) {
            foreach ($data as $key => $val) {
                if (property_exists($val, $key_name) && property_exists($val, $val_name)) {
                    $output[$val->{$key_name}] = $val->{$val_name};
                }
            }
        }
        return $output;
    }

    protected static function _get_user_id($user_id = NULL)
    {
        if (empty(self::$_user_id)) {
            self::$_user_id = Auth::id();
        }

        if (empty($user_id)) {
            return self::$_user_id;
        } else {
            return $user_id;
        }
    }

    protected static function _is_exist($table_name, $column_name, $column_value)
    {
        return !empty(DB::table($table_name)->where($column_name, $column_value)->first());
    }

    /**
     * Search for a string in array and return the key
     *
     * @param  mixed $search_term
     * @param  mixed $items
     * @return void
     */
    protected static function _filter(string $search_term, array $items)
    {
        $search_term = strtolower($search_term);
        $all_search_term = explode(' ', $search_term);

        if (count($all_search_term) > 2) {
            $all_search_term = array_slice($all_search_term, 2);
        }

        $type = null;

        // Find the needle
        foreach ($all_search_term as $val) {
            foreach ($items as $type_id => $all_terms) {
                foreach ($all_terms as $term) {
                    if (strpos($term, $val) !== false) {
                        $type = $type_id;
                        break 3;
                    }
                }
            }
        }

        return $type;
    }
}
