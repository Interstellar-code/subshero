<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CronFlag extends Model
{
    use HasFactory;

    static function get_flag($name) {
        $flag = self::where('name', $name)->first();
        return $flag->value;
    }

    static function set_flag($name, $value) {
        $flag = self::where('name', $name)->first();
        $flag->value = $value;
        $flag->save();
    }
}
