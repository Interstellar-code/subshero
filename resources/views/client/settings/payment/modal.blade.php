@push('modal')

    <div id="modal_settings_payment_add" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Add new payment')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="" id="frm_settings_payment_add" action="{{ route('app/settings/payment/create') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="settings_payment_add_name" class="">@lang('Name')</label>
                                    <input name="name" id="settings_payment_add_name" type="text" class="form-control" required data-toggle="tooltip" data-placement="left" title="@lang('Enter Payment Name')">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="settings_payment_payment_type" class="">@lang('Payment type')</label>
                                    <select name="payment_type" id="settings_payment_payment_type" class="form-control" required data-toggle="tooltip" data-placement="right" title="@lang('Select Payment Type')">
                                        <option selected="" disabled="" value="" style="display: none;">@lang('Select')</option>
                                        @foreach (table('subscription.payment_type') as $val)
                                            <option value="{{ $val }}">{{ $val }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="settings_payment_description" class="">@lang('Description')</label>
                                    <input name="description" id="settings_payment_description" type="text" class="form-control" data-toggle="tooltip" data-placement="left" title="@lang('Enter Payment Description')">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="settings_payment_expiry" class="">@lang('Expiry')</label>
                                    <input name="expiry" id="settings_payment_expiry" type="date" class="form-control" data-toggle="tooltip" data-placement="right" title="@lang('Set Expiry Date')">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary pull-right" onclick="app.settings.payment.create(this);" data-toggle="tooltip" data-placement="left" title="@lang('Add your Payment Method')">
                            <i class="fa fa-plus"></i>&nbsp;
                            @lang('Add')
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="modal_settings_payment_edit" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Update payment')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="" id="frm_settings_payment_edit" action="{{ route('app/settings/payment/update') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary pull-right" onclick="app.settings.payment.update(this);" data-toggle="tooltip" data-placement="left" title="@lang('Save your changes ')">
                            <i class="fa fa-save"></i>&nbsp;
                            @lang('Save')
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endpush
