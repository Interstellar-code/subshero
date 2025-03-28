<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class PlanCoupon extends Model
{
    use HasFactory;

    public $table = 'plan_coupons';
    public static $orderByFields = [
        'id',
        'coupon',
        'status',
        'created_at',
        'updated_at',
    ];
    public static $sortableColumns = 'id,coupon,status,created_at,updated_at';

}
