<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\CheckAlertType;

class EventChrome extends Model
{
    use HasFactory;
    use CheckAlertType;
    protected $table = 'event_chrome';
    public $timestamps = false;
    protected $guarded = [];
    protected $attributes = [
        'event_timezone' => APP_TIMEZONE,
        'event_type_color' => 'green',
        'event_type_schedule' => 0,
        'event_cron' => 0,
        'event_migrate' => 0,
    ];

    public function __construct(array $attributes = [])
    {
        $this->attributes['event_datetime'] = lib()->do->timezone_convert([
            'to_timezone' => APP_TIMEZONE,
        ]);
        $this->attributes['event_url'] = url()->current();
        parent::__construct($attributes);
    }
}
