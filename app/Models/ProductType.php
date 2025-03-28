<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\ProductRelatedEntitiesTrait;

class ProductType extends Model
{
    use HasFactory;
    use ProductRelatedEntitiesTrait;
}
