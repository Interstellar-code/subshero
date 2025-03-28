@extends(request()->ajax() ? 'client/layouts/ajax' : 'client/layouts/settings')

@section('head')
@endsection

@section('content')
    <style>
        .plan_limit {
            height: 34px;
        }

        .plan_limit .progress-bar {
            text-align: left;
            padding-left: 50px;
        }

        .plan_limit_text {
            line-height: 34px;
        }

    </style>

    <form action="{{ route('app/settings/preference/update') }}" id="frm_settings_preference" class="main-card mb-3" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-4 col-lg-3">
                <h5>@lang('General')</h5>
                <div class="position-relative form-group">
                    <label for="settings_preference_general_timezone" class="">@lang('Timezone')</label>
                    <select name="timezone" id="settings_preference_general_timezone" class="form-control" data-toggle="tooltip" data-placement="left" title="@lang('Select the TimeZone')">
                        <option selected="" disabled="" value="" style="display: none;">@lang('Select')</option>
                        @foreach (lib()->config->timezone as $val)
                            <option value="{{ $val['value'] }}" {{ lib()->user->default->timezone_value == $val['value'] ? 'selected' : null }}>
                                {{ '(' . $val['display'] . ') ' . $val['timezone'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
                {{-- <div class="position-relative form-group">
                    <label for="settings_preference_general_language" class="">@lang('Language')</label>
                    <select name="language" id="settings_preference_general_language" class="form-control">
                        <option selected="" disabled="" value="" style="display: none;">@lang('Select')</option>
                        @foreach (lib()->config->language as $val)
                            <option value="{{ $val['name'] }}" {{ lib()->user->default->language_name == $val['name'] ? 'selected' : null }}>{{ __($val['name']) }}</option>
                        @endforeach
                    </select>
                </div> --}}
            </div>
            {{-- <div class="col-md-6 col-lg-9">
                <h5>@lang('Alert Preferences')</h5>
                <div class="row">
                    <div class="col-lg-4">
                        <div class="position-relative form-group" data-toggle="tooltip" data-placement="bottom" title="@lang('Select the Time Period')">
                            <label for="settings_preference_alert_time_period" class="">@lang('Time Period')</label>
                            <select name="time_period" id="settings_preference_alert_time_period" class="form-control select2_init_tags">
                                <option selected="" disabled="" value="" style="display: none;">@lang('Select')</option>
                                @for ($i = 1; $i <= 40; $i++)
                                    <option {{ lib()->user->alert_preference->time_period == $i ? 'selected' : null }}>@lang($i)</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="position-relative form-group" data-toggle="tooltip" data-placement="bottom" title="@lang('Get alerts based on time period set')">
                            <label for="settings_preference_alert_time_cycle" class="">&nbsp;</label>
                            <select name="time_cycle" id="settings_preference_alert_time_cycle" class="form-control select2_init_tags">
                                <option selected="" disabled="" value="" style="display: none;">@lang('Select')</option>
                                @foreach (table('users_alert_preferences.time_cycle') as $key => $val)
                                    <option value="{{ $key }}" {{ lib()->user->alert_preference->time_cycle == $key ? 'selected' : null }}>@lang($val)</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="position-relative form-group">
                            <label for="settings_preference_alert_time" class="">@lang('Time')</label>
                            <input name="time" id="settings_preference_alert_time" value="{{ date('H:i', strtotime(lib()->user->alert_preference->time)) }}" type="time" class="form-control" data-toggle="tooltip" data-placement="bottom" title="@lang('Select the Time when you want to receive a alert')">
                        </div>
                    </div>
                </div>

                <!-- <h5 class="mt-5 pt-1">@lang('Monthly Report')</h5>
                <div class="row">
                    <div class="col-lg-4">
                        <div class="position-relative form-group">
                            <label for="settings_preference_report_time" class="">@lang('Time')</label>
                            <input name="monthly_report_time" id="settings_preference_report_time" value="{{ date('H:i', strtotime(lib()->user->alert_preference->monthly_report_time)) }}" type="time" class="form-control" >
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="position-relative form-group pt-4 mt-2">
                            <label for="settings_preference_report_" class="">@lang('Email Monthly Reports')</label>
                            <input name="monthly_report" id="settings_preference_report_" {{ lib()->user->alert_preference->monthly_report == 1 ? 'checked' : null }} value="1" type="checkbox" data-toggle="toggle">
                        </div>
                    </div>
                </div> -->
            </div> --}}
        </div>

        <h5>@lang('User Defaults')</h5>
        <div class="row">
            <div class="col-md-3">
                <div class="position-relative form-group">
                    <label for="settings_preference_general_currency" class="">@lang('Currency')</label>
                    <select name="currency" id="settings_preference_general_currency" class="form-control" data-toggle="tooltip" data-placement="left" title="@lang('Select your Currency Type')">
                        <option selected="" disabled="" value="" style="display: none;">@lang('Select')</option>
                        @foreach (lib()->config->currency as $val)
                            <option value="{{ $val['code'] }}" {{ lib()->user->default->currency_code == $val['code'] ? 'selected' : null }}>
                                {{ $val['name'] . ' - ' . $val['code'] . ' - ' . $val['symbol'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="position-relative form-group">

                    <div class="switch_container">
                        <label for="settings_preference_general_billing_frequency" class="">@lang('Billing Cycle')</label>

                        <label class="switch ml-2" data-toggle="tooltip" data-placement="right" title="{{ lib()->lang->get_billing_type(lib()->prefer->billing_type) }}">
                            <input type="checkbox" name="billing_type" value="2" onclick="lib.do.billing_toggle_switch(this);" {{ lib()->prefer->billing_type == 2 ? 'checked' : null }}>
                            <span class="slider round"></span>
                        </label>
                    </div>

                    <div class="input-group">
                        <div class="input-group-prepend">
                            <label for="settings_preference_general_billing_frequency" class="input-group-text">@lang('Every')</label>
                        </div>
                        <select name="billing_frequency" id="settings_preference_general_billing_frequency" class="form-control" required data-toggle="tooltip" data-placement="bottom" title="@lang('Select Default Billing Frequecy ')">
                            <option selected="" disabled="" value="" style="display: none;">@lang('Select')</option>
                            @for ($i = 1; $i <= 40; $i++)
                                <option {{ lib()->user->default->billing_frequency == $i ? 'selected' : null }}>@lang($i)</option>
                            @endfor
                        </select>
                        <select name="billing_cycle" id="settings_preference_general_billing_cycle" class="form-control" required data-toggle="tooltip" data-placement="bottom" title="@lang('Select Default Billing Cycle')">
                            <option selected="" disabled="" value="" style="display: none;">@lang('Select')</option>
                            @foreach (table('subscription.cycle') as $key => $val)
                                <option value="{{ $key }}" {{ lib()->user->default->billing_cycle == $key ? 'selected' : null }}>@lang($val)</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="position-relative form-group">
                    <label for="settings_preference_general_payment_mode_id" class="">@lang('Payment mode')</label>
                    <select name="payment_mode_id" id="settings_preference_general_payment_mode_id" class="form-control" required data-toggle="tooltip" data-placement="bottom" title="@lang('Select Default Payment Mode')">
                        <option selected="" disabled="" value="" style="display: none;">@lang('Select')</option>
                        @foreach (lib()->user->payment_methods as $val)
                            <option value="{{ $val->id }}" {{ lib()->user->default->payment_mode_id == $val->id ? 'selected' : null }}>{{ $val->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <button class="mb-2 mr-2 btn btn-primary" onclick="app.settings.preference.update(this);" data-toggle="tooltip" data-placement="right" title="@lang('Save your changes')">
            <i class="fa fa-save"></i>&nbsp;
            @lang('Save')
        </button>
    </form>

@endsection
