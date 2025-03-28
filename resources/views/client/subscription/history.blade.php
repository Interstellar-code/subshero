@extends(request()->ajax() ? 'client/layouts/ajax' : 'client/layouts/default')

@section('head')
@endsection

@section('content')
    <div class="modal-header">
        <h5 class="modal-title d-flex align-items-center">
            <img class="product_image mr-3" src="{{ asset_ver('assets/images/dropdown-header/abstract6.jpg') }}" id="subscription_history_product_image">
            <strong><strong id="subscription_history_product_name"></strong> @lang('Subscription History')</strong>
        </h5>

        <button type="button" class="btn btn_app_primary btn_ripple mr-3" onclick="app.subscription.history_add();">
            <i class="icon fas fa-plus"></i>
            <span class="label mr-2">@lang('Add')</span>
        </button>

        <button type="button" class="btn btn_app_primary btn_ripple mr-3" onclick="app.subscription.history_save_all();">
            <i class="icon fas fa-save"></i>
            <span class="label mr-2">@lang('Save All')</span>
        </button>

        <button type="button" class="btn_close btn_ripple" data-dismiss="modal" aria-label="Close">
            <img class="icon" src="{{ asset_ver('assets/icons/close.svg') }}">
        </button>
    </div>
    <div class="modal-body">
        <div id="subscription_history_table_wijmo_grid" data-subscription_id="{{ $subscription_id }}"></div>
    </div>

    <script>
        app.subscription.history_load();
        $(document).ready(function() {
            app.ui.btn_ripple();
        });
    </script>
@endsection
