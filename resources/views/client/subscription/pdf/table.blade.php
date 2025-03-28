@extends(request()->ajax() ? 'client/layouts/ajax' : 'client/layouts/default')

@section('head')
@endsection

@section('content')
    @php
        $system_default_profile = lib()->do->get_alert_profile_system_default();
        $system_default_profile_ltd = lib()->do->get_alert_profile_system_default_ltd();
        
        $system_default_profile_id = 0;
        $system_default_profile_ltd_id = 0;
        
        if (!empty($system_default_profile->id)) {
            $system_default_profile_id = $system_default_profile->id;
        }
        
        if (!empty($system_default_profile_ltd->id)) {
            $system_default_profile_ltd_id = $system_default_profile_ltd->id;
        }
    @endphp

    <form id="frm_subscription_pdf_import" action="{{ route('app/subscription/pdf/save') }}" method="POST">
        @csrf
        <input type="hidden" name="count" value="{{ count($all_subscription) }}">
        <div class="table-responsive card">
            <table id="tbl_subscription_pdf_import" class="align-middle mb-0 table table-borderless table-striped table-hover text-center mb-4">
                <thead>
                    <tr>
                        <th class="text-center">@lang('Name')</th>
                        <th class="text-center">@lang('Type')</th>
                        <th class="text-center">@lang('Price')</th>
                        <th class="text-center">@lang('Payment Date')</th>
                        <th class="text-center">@lang('Payment Method')</th>
                        <th class="text-center" style="width: 260px;">@lang('Billing Cycle')</th>
                        <th class="text-center">&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($all_subscription as $subscription)
                        <tr data-id="{{ $loop->index }}">
                            <td class="text-center">
                                <input type="hidden" name="brand_id_{{ $loop->index }}" value="{{ $subscription->brand_id }}">
                                <input type="hidden" name="folder_id_{{ $loop->index }}" value="{{ $subscription->folder_id }}">
                                <input type="hidden" name="alert_id_{{ $loop->index }}" value="{{ $subscription->type == 3 ? $system_default_profile_ltd_id : $system_default_profile_id }}">
                                <input type="hidden" name="product_name_{{ $loop->index }}" value="{{ $subscription->product_name }}">
                                <input type="hidden" name="brandname_{{ $loop->index }}" value="{{ $subscription->brandname }}">
                                <input type="hidden" name="product_type_{{ $loop->index }}" value="{{ $subscription->product_type }}">
                                <input type="hidden" name="platform_id_{{ $loop->index }}" value="{{ $subscription->platform_id }}">
                                <input type="hidden" name="image_{{ $loop->index }}" value="{{ $subscription->image }}">
                                <input type="hidden" name="favicon_{{ $loop->index }}" value="{{ $subscription->favicon }}">
                                {{-- <input type="hidden" name="type_{{ $loop->index }}" value="{{ $subscription->type }}"> --}}
                                <input type="hidden" name="price_{{ $loop->index }}" value="{{ $subscription->price }}">
                                <input type="hidden" name="price_type_{{ $loop->index }}" value="{{ $subscription->price_type }}">
                                <input type="hidden" name="description_{{ $loop->index }}" value="{{ $subscription->description }}">
                                <input type="hidden" name="recurring_{{ $loop->index }}" value="{{ $subscription->recurring }}">
                                <input type="hidden" name="payment_date_{{ $loop->index }}" value="{{ $subscription->payment_date }}">
                                {{-- <input type="hidden" name="payment_mode_id_{{ $loop->index }}" value="{{ $subscription->payment_mode_id }}"> --}}
                                <input type="hidden" name="ltdval_price_{{ $loop->index }}" value="{{ $subscription->ltdval_price }}">
                                <input type="hidden" name="ltdval_frequency_{{ $loop->index }}" value="{{ $subscription->ltdval_frequency }}">
                                <input type="hidden" name="pricing_type_{{ $loop->index }}" value="{{ $subscription->pricing_type }}">
                                <input type="hidden" name="timezone_{{ $loop->index }}" value="{{ $subscription->timezone }}">
                                <input type="hidden" name="currency_code_{{ $loop->index }}" value="{{ $subscription->currency_code }}">
                                <input type="hidden" name="refund_days_{{ $loop->index }}" value="{{ $subscription->refund_days }}">

                                <span>{{ $subscription->product_name }}</span>
                            </td>
                            <td class="text-center">
                                <select name="type_{{ $loop->index }}" class="form-control" onchange="app.subscription.pdf.type_check(this);" required>
                                    @foreach (table('subscription.type') as $key => $val)
                                        <option value="{{ $key }}" {{ $subscription->type == $key ? 'selected' : null }}>@lang($val)</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="text-center">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <label for="subscription_pdf_import_price_{{ $loop->index }}" class="input-group-text">
                                            {{ lib()->do->get_currency_symbol($subscription->price_type) }}
                                        </label>
                                    </div>
                                    <input name="price_{{ $loop->index }}" id="subscription_pdf_import_price_{{ $loop->index }}" value="{{ $subscription->price }}" min="0" type="number" class="form-control" placeholder="@lang('0.00')" required data-toggle="tooltip" data-placement="top" title="@lang('Set Price')">
                                </div>
                            </td>
                            <td class="text-center">
                                <span>{{ date('d M Y', strtotime($subscription->payment_date)) }}</span>
                            </td>
                            <td class="text-center">
                                <select name="payment_mode_id_{{ $loop->index }}" class="form-control" required data-toggle="tooltip" data-placement="top" title="@lang('Select Payment mode ')">
                                    <option selected="" disabled="" value="" style="display: none;">@lang('Select')</option>
                                    @foreach (lib()->user->payment_methods as $val)
                                        <option value="{{ $val->id }}" {{ $subscription->payment_mode_id == $val->id ? 'selected' : null }}>{{ $val->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="text-center">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <label for="subscription_pdf_import_billing_frequency_{{ $loop->index }}" class="input-group-text">@lang('Every')</span>
                                    </div>
                                    <select name="billing_frequency_{{ $loop->index }}" id="subscription_pdf_import_billing_frequency_{{ $loop->index }}" {{ $subscription->type == 3 ? 'disabled' : null }} class="form-control pr-0" required data-toggle="tooltip" data-placement="top" title="@lang('Set Billing Frequency')">
                                        @for ($i = 1; $i <= 40; $i++)
                                            <option value="{{ $i }}" {{ $subscription->billing_frequency == $i ? 'selected' : null }}>@lang($i)</option>
                                        @endfor
                                    </select>
                                    <select name="billing_cycle_{{ $loop->index }}" {{ $subscription->type == 3 ? 'disabled' : null }} class="form-control" style="width: 70px;" required data-toggle="tooltip" data-placement="top" title="@lang('Set Billing Cycle')">
                                        @foreach (table('subscription.cycle') as $key => $val)
                                            <option value="{{ $key }}" {{ $subscription->billing_cycle == $key ? 'selected' : null }}>@lang($val)</option>
                                        @endforeach
                                    </select>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="toggle btn btn-warning mr-3" data-toggle="toggle" style="width: 85px;">
                                    <input type="checkbox" name="status_{{ $loop->index }}" value="1" data-toggle="toggle" data-onstyle="success" data-offstyle="danger" checked>
                                    <div class="toggle-group" data-toggle="tooltip" data-placement="left" title="@lang('If Active your Subs/Lifetime is tracked , Draft can not be track your Subs/Lifetime')">
                                        <label class="btn btn-success toggle-on">@lang('Active')</label>
                                        <label class="btn btn-warning toggle-off">@lang('Draft')</label>
                                        <span class="toggle-handle btn btn-light"></span>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <br>
        <div class="row">
            <div class="col-md-6 col-xl-9">
            </div>
            <div class="col-md-6 col-xl-3">
                <button type="submit" class="btn-shadow btn btn-wide btn-primary btn-lg btn-block" data-toggle="tooltip" onclick="app.subscription.pdf.save(this);" data-placement="right" title="@lang('Add all Subscription/Lifetime')">
                    <i class="fa fa-plus"></i>&nbsp;
                    @lang('Add All')
                </button>
            </div>
        </div>
    </form>

    <script>
        $(document).ready(function() {
            app.ui.btn_toggle();
        });
    </script>
@endsection
