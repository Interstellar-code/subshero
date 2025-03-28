@if ($val->status == 1)
    <div class="badge badge-pill badge-success">@lang('Active')</div>
@else
    <div class="badge badge-pill badge-danger">@lang('Inactive')</div>
@endif
