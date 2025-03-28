<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Plan extends Model
{
    use HasFactory;

    public $table = 'plans';
    // public $timestamps = false;
    public static $orderByFields = [
        'id',
        'name',
        'created_at',
    ];
    public static $sortableColumns = 'id,name,created_at';
}
