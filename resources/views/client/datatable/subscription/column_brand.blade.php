<div class="row align-items-center subscription_brand_column">
    <div class="col-2">

        {{-- Draft, Active or Cancel status check --}}
        @if ($val->status == 0)
            <span class="badge badge-warning subscription_status_vertical">@lang('Draft')</span>
        @elseif ($val->status == 1)
            {{-- Lifetime addon --}}
            @if ($val->sub_addon == 1)
                <span class="badge badge-dark subscription_status_vertical">@lang('Addon')</span>
            @else
                {{-- Recurring check --}}
                @if ($val->recurring == 1)
                    <span class="badge subscription_status_vertical badge_recurring">@lang('Sub')</span>
                @elseif ($val->type == 3)
                    <span class="badge badge-info subscription_status_vertical">@lang('Lifetime')</span>
                @else
                    <span class="badge badge-info subscription_status_vertical">@lang('Once')</span>
                @endif
            @endif
        @elseif ($val->status == 2)
            <span class="badge badge-danger subscription_status_vertical">@lang('Canceled')</span>
        @elseif ($val->status == 3)
            <span class="badge badge-secondary subscription_status_vertical">@lang('Refund')</span>
        @elseif ($val->status == 4)
            <span class="badge badge-secondary subscription_status_vertical">@lang('Expired')</span>
        @elseif ($val->status == 5)
            <span class="badge badge-dark subscription_status_vertical">@lang('Sold')</span>
        @endif
    </div>
    <div class="col-10 column_image_container">

        {{-- Cancel and Expired status check --}}
        @if (in_array($val->status, [2, 3, 4, 5]))
            <div class="img_btn_container">
                <img src="{{ $val->image }}" class="!rounded-circle" style="max-height: 100px; max-width: 150px;">
            </div>
        @elseif (in_array($val->status, [1]) && in_array($val->type, [1, 3]))
            <div class="img_btn_container">
                <img src="{{ $val->image }}" class="!rounded-circle" style="max-height: 100px; max-width: 150px;">
                <div class="middle">
                    <button class="btn btn-pill btn-sm btn_overlay primary btn_ion_icon" onclick="app.subscription.edit(this);" data-toggle="tooltip" data-placement="top" title="@lang('Edit')">
                        <ion-icon name="pencil-outline" size="small"></ion-icon>
                    </button>
                    @if ($val->brand_id <= PRODUCT_RESERVED_ID && $val->product_submission_id == 0)
                        <button class="btn btn-pill btn-sm btn_overlay primary btn_ion_icon" onclick="app.subscription.adapt.submit(this);" data-toggle="tooltip" data-placement="top" title="@lang('Submit')">
                            <ion-icon name="paper-plane-outline" size="small"></ion-icon>
                        </button>
                    @endif
                    <button class="btn btn-pill btn-sm btn_overlay primary btn_ion_icon" onclick="app.subscription.marketplace.edit(this);" data-toggle="tooltip" data-placement="top" title="@lang('Sell')">
                        <ion-icon name="cart-outline" size="small"></ion-icon>
                    </button>
                </div>
            </div>
            @if (!empty($val->product_avail) && !empty($val->product_submission_id))
                <button class="btn btn_collapse primary" onclick="app.subscription.adapt.edit(this);" data-toggle="tooltip" data-placement="top" title="@lang('Adapt')">
                    <img src="{{ asset_ver('assets/images/logos/icon-v1.png') }}" class="img-fluid" style="height: 20px; width: 34px;">
                    {{-- <i class="fa fa-sync mb-2"></i> --}}
                </button>
            @endif
        @else
            <div class="img_btn_container">
                <img src="{{ $val->image }}" class="!rounded-circle" style="max-height: 100px; max-width: 150px;">
                <div class="middle">
                    <button class="btn btn-pill btn-sm btn_overlay primary btn_ion_icon" onclick="app.subscription.edit(this);" data-toggle="tooltip" data-placement="top" title="@lang('Edit')">
                        <ion-icon name="pencil-outline" size="small"></ion-icon>
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>
