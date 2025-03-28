@push('template')
    <template id="tpl_subscription_history_action_column">
        <div class="d-flex align-items-center w-75 action_column">
            <span>
                <img class="icon mr-3" src="{{ asset_ver('assets/icons/save.svg') }}" data-id="__ID__" onclick="app.subscription.history_save(this);" data-toggle="tooltip" data-placement="top" title="@lang('Save')">
                <i class="fa fa-spinner fa-pulse mr-3" style="display: none; font-size:20px"></i>
            </span>
            <span>
                <img class="icon mr-3" src="{{ asset_ver('assets/icons/trash.svg') }}" data-id="__ID__" onclick="app.subscription.history_delete(this);" data-toggle="tooltip" data-placement="top" title="@lang('Delete')">
                <i class="fa fa-spinner fa-pulse mr-3" style="display: none; font-size:20px"></i>
            </span>
        </div>
    </template>

    <template id="tpl_subscription_history_payment_date_column">
        <div class="d-flex justify-content-between align-items-center w-25">
            <img class="icon wj-elem-dropdown mr-1" src="{{ asset_ver('assets/icons/calendar.svg') }}">
            <p class="payment_date m-0">__DATE__</p>
        </div>
    </template>

    <template id="tpl_subscription_history_payment_method_column">
        <div class="d-flex justify-content-between align-items-center w-50">
            <p class="payment_method_name m-0">__PAYMENT_METHOD_NAME__</p>
            <img class="icon wj-elem-dropdown" src="{{ asset_ver('assets/icons/angle-down.svg') }}">
        </div>
    </template>
@endpush
