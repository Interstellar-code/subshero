@if ($val->pricing_type == 1)
    <div class="badge badge-pill badge-warning">
        @lang(enum('table.product.pricing_type', $val->pricing_type))
    </div>
@elseif ($val->pricing_type == 3)
    <div class="badge badge-pill badge-info">
        @lang(enum('table.product.pricing_type', $val->pricing_type))
    </div>
@elseif (in_array($val->pricing_type, [1, 2, 3]))
    <div class="badge badge-pill badge-dark">
        @lang(enum('table.product.pricing_type', $val->pricing_type))
    </div>
@else
    <div class="badge badge-pill badge-white">
        @lang('Unknown')
    </div>
@endif
