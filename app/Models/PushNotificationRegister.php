<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PushNotificationRegister extends Model
{
    use HasFactory;

    public static function send(array $message_data)
    {
        return lib()->do->send_push_notification($message_data);
    }
}
