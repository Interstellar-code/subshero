<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionTagMap extends Model
{
    use HasFactory;

    public $timestamps = false;
    public $table = 'subscriptions_tags';
    public static $sortableColumns = 'id';
}
