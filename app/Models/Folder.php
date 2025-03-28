<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Folder extends Model
{
    use HasFactory;

    public $table = 'folder';
    // public $timestamps = false;
    public static $orderByFields = [
        'id',
        'name',
        'is_default',
        'created_at',
    ];
    public static $sortableColumns = 'id,name,is_default,created_at';

    public static function clearDefault()
    {
        return self::where('user_id', Auth::id())
            ->where('is_default', 1)
            ->update(['is_default' => 0]);
    }
}
