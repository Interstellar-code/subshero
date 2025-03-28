<a data-toggle="dropdown" data-offset="10" data-display="static" aria-expanded="false" class="btn-shadow btn btn-wide btn-primary mr-3">
    <span>@lang('Import')</span>
    <i class="nav-icon-pointer icon fa fa-angle-down"></i>
</a>
<div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu product-related-import">
    <a href="{{ url('admin/product/import') }}" onclick="app.page.switch('admin/product/import');" tabindex="0" class="dropdown-item">@lang('Import Products')</a>
    <a href="{{ url('admin/product/type') }}" onclick="app.page.switch('admin/product/type');" tabindex="0" class="dropdown-item">@lang('Import Product Types')</a>
    <a href="{{ url('admin/product/category') }}" onclick="app.page.switch('admin/product/category');" tabindex="0" class="dropdown-item">@lang('Import Product Categories')</a>
    <a href="{{ url('admin/product/platform') }}" onclick="app.page.switch('admin/product/platform');" tabindex="0" class="dropdown-item">@lang('Import Product Platforms')</a>
    <a href="{{ url('admin/product/logos_and_favicons') }}" onclick="app.page.switch('admin/product/logos_and_favicons');" tabindex="0" class="dropdown-item">@lang('Import Product Logos and Favicons')</a>
    <a href="{{ url('admin/product/check/favicons') }}" tabindex="0" class="dropdown-item">@lang('Check Product Favicons')</a>
</div>
