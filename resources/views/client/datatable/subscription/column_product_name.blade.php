@if (empty($val->url))
    <span class="font-weight-bold">{{ $val->product_name }}</span>
@else
    <a href="{{ $val->url }}" target="_blank" class="nav-link d-inline">{{ $val->product_name }}</a>
@endif
<br>
<small>{{ $val->product_category_name }}</small>
