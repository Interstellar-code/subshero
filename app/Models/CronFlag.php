<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * CronFlag - Manages cron job flags
 *
 * This model provides methods to get and set flags for cron jobs.
 */
class CronFlag extends Model
{
    use HasFactory;

    /**
     * Get the value of a cron flag
     * @param string $name Flag name
     * @return mixed Flag value
     */
    static function get_flag($name) {
        $flag = self::where('name', $name)->first();
        return $flag->value;
    }

    /**
     * Set the value of a cron flag
     * @param string $name Flag name
     * @param mixed $value Flag value
     * @return void
     */
    static function set_flag($name, $value) {
        $flag = self::where('name', $name)->first();
        $flag->value = $value;
        $flag->save();
    }
}
