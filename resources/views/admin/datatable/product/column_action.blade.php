<button class="mb-2 mr-2 btn-icon btn-icon-only btn-pill btn btn-outline-primary btn-sm border" onclick="app.product.edit(this);">
    <i class="fa fa-edit"></i>
</button>
<button class="mb-2 mr-2 btn-icon btn-icon-only btn-pill btn btn-outline-danger btn-sm" onclick="app.product.delete(this);" type="button">
    <i class="fa fa-trash-alt"></i>
</button>


{{-- <div class="dropdown d-inline-block">
    <button class="mb-2 mr-2 btn-icon btn-icon-only btn-pill btn btn-outline-primary btn-sm border" type="button" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown">
        <i class="fa fa-ellipsis-v"></i>
    </button>
    <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu-right dropdown-menu-rounded dropdown-menu mt-1">
        <button class="btn-icon btn-icon-only btn-pill btn btn-outline-danger btn-sm" onclick="app.product.delete(this);" type="button">
            @lang('Delete')&nbsp;
            <i class="fa fa-trash-alt"></i>
        </button>
    </div>
</div> --}}
