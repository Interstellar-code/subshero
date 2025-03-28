<?php

namespace App\Models;

use App\BaseModel;
use Illuminate\Support\Facades\DB;

class EventWebhook extends BaseModel
{
    public $table = 'event_webhook';
    protected $guarded = [];
    public $timestamps = false;
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

    public static function send_messages()
    {
        return 0;
    }
}
