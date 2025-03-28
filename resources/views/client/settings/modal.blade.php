@push('modal')
    <div id="modal_settings_billing_coupon_apply" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <form class="" id="frm_settings_billing_coupon_apply" action="{{ route('app/settings/billing/coupon/apply') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">@lang('Apply Coupon Code')</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="position-relative form-group">
                            <label for="settings_billing_coupon_apply_coupon" class="">@lang('Coupon code')</label>
                            <input name="coupon" id="settings_billing_coupon_apply_coupon" maxlength="{{ len()->plan_coupons->coupon }}" type="text" class="form-control" required data-toggle="tooltip" data-placement="left" title="@lang('Enter a Coupon code')">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary pull-right" onclick="app.settings.billing.coupon_apply(this);" data-toggle="tooltip" data-placement="left" title="@lang('Apply Coupon')">
                            <i class="fa fa-check"></i>&nbsp;
                            @lang('Apply')
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endpush
