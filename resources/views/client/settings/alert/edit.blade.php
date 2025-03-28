<form class="" id="frm_settings_alert_edit" action="{{ route('app/settings/alert/update') }}" method="POST">
    @csrf
    <input name="id" id="settings_alert_edit_id" type="hidden" value="{{ $data->id }}">

    <div class="modal-header">
        <h5 class="modal-title">@lang('Update alert')</h5>

        <div class="switch_container m-0">
            <label class="switch !switch_lg ml-2" data-toggle="tooltip" data-placement="top" title="@lang('Default')">
                <input type="checkbox" name="is_default" value="1" {{ $data->is_default ? 'checked' : null }}>
                <span class="slider round"></span>
            </label>
        </div>

        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="row">

            <div class="col-md-4">
                <div class="position-relative form-group" data-toggle="tooltip" data-placement="left" title="@lang('Select Type')">
                    <label for="settings_alert_edit_alert_subs_type" class="">@lang('Subscription Type')</label>
                    <select name="alert_subs_type" id="settings_alert_edit_alert_subs_type" onchange="app.settings.alert.update_type_check(this);" class="form-control" required>
                        @foreach (table('users_alert.alert_subs_type') as $key => $val)
                            <option value="{{ $key }}" {{ $data->alert_subs_type == $key ? 'selected' : null }}>@lang($val)</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-4">
                <div class="position-relative form-group">
                    <label for="settings_alert_edit_alert_name" class="">@lang('Alert Name')</label>
                    <input name="alert_name" id="settings_alert_edit_alert_name" value="{{ $data->alert_name }}" maxlength="{{ len()->users_alert->alert_name }}" type="text" class="form-control" required data-toggle="tooltip" data-placement="left" title="@lang('Add New Alert Profile')">
                </div>
            </div>

            <div class="col-md-4">
                <div class="position-relative form-group">
                    <label for="settings_alert_edit_time_period" class="">@lang('Enter the Time Period')</label>
                    <div class="input-group">
                        <input name="time_period" id="settings_alert_edit_time_period" value="{{ $data->time_period }}" min="0" max="{{ len()->users_alert->time_period }}" type="number" class="form-control" data-toggle="tooltip" data-placement="bottom" title="@lang('Enter the Time Period')">
                        <select name="time_period_cycle" id="settings_alert_edit_time_period_cycle" class="form-control" required data-toggle="tooltip" data-placement="top" title="@lang('Set Cycle')">
                            @foreach (table('users_alert.time_period_cycle') as $key => $val)
                                <option value="{{ $key }}" {{ $data->time_period_cycle == $key ? 'selected' : null }}>@lang($val)</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- <div class="col-md-4">
                <div class="position-relative form-group" data-toggle="tooltip" data-placement="bottom" title="@lang('Get alerts based on time period set')">
                    <label for="settings_alert_edit_time_cycle" class="">@lang('Time Cycle')</label>
                    <select name="time_cycle" id="settings_alert_edit_time_cycle" class="form-control">
                        <option selected="" disabled="" value="" style="display: none;">@lang('Select')</option>
                        @foreach (table('users_alert.time_cycle') as $key => $val)
                            <option value="{{ $key }}" {{ $data->time_cycle == $key ? 'selected' : null }}>@lang($val)</option>
                        @endforeach
                    </select>
                </div>
            </div> --}}

            <div class="col-md-4">
                <div class="position-relative form-group">
                    <label for="settings_alert_edit_time" class="">@lang('Time')</label>
                    <input name="time" id="settings_alert_edit_time" value="{{ date('H:i', strtotime($data->time)) }}" type="time" class="form-control" data-toggle="tooltip" data-placement="bottom" title="@lang('Select the Time when you want to receive an alert')">
                </div>
            </div>

            <div class="col-md-4">
                <div class="position-relative form-group">
                    <label for="settings_alert_edit_timezone" class="">@lang('Timezone')</label>
                    <select name="timezone" id="settings_alert_edit_timezone" class="form-control" data-toggle="tooltip" data-placement="left" title="@lang('Select the TimeZone')">
                        <option selected="" disabled="" value="" style="display: none;">@lang('Select')</option>
                        @foreach (lib()->config->timezone as $val)
                            <option value="{{ $val['value'] }}" {{ $data->timezone == $val['value'] ? 'selected' : null }}>
                                {{ '(' . $val['display'] . ') ' . $val['timezone'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-4">
                <div class="position-relative form-group" data-toggle="tooltip" data-placement="bottom" title="@lang('Alert Condition')">
                    <label for="settings_alert_edit_alert_condition" class="">@lang('Alert Condition')</label>
                    <select name="alert_condition" id="settings_alert_edit_alert_condition" class="form-control" required>
                        <option selected="" disabled="" value="" style="display: none;">@lang('Select')</option>
                        @foreach (table('users_alert.alert_condition') as $key => $val)
                            <option value="{{ $key }}" {{ $data->alert_condition == $key ? 'selected' : null }} style="display: {{ $data->alert_subs_type == 3 && in_array($key, [1, 2]) ? 'none' : 'block' }}">@lang($val)</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-12">
                <div class="position-relative form-group">
                    <label for="settings_alert_edit_alert_type_email" class="">@lang('Alert Type')</label>
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="alert_types[]" value="email" id="settings_alert_edit_alert_type_email" required {{ in_array("email", $data->alert_types) ? 'checked' : null }}>
                                <label class="form-check-label" for="settings_alert_edit_alert_type_email">
                                    @lang('Email')
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="alert_types[]" value="browser" id="settings_alert_edit_alert_type_browser_notification" required {{ in_array("browser", $data->alert_types) ? 'checked' : null }}>
                                <label class="form-check-label" for="settings_alert_edit_alert_type_browser_notification">
                                    @lang('Browser Notification')
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="alert_types[]" value="extension" id="settings_alert_edit_alert_type_chrome_notification" required {{ in_array("extension", $data->alert_types) ? 'checked' : null }}>
                                <label class="form-check-label" for="settings_alert_edit_alert_type_chrome_notification">
                                    @lang('Chrome Notification')
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary pull-right" onclick="app.settings.alert.update(this);" data-toggle="tooltip" data-placement="left" title="@lang('Save your changes')">
            <i class="fa fa-save"></i>&nbsp;
            @lang('Save')
        </button>
    </div>
</form>
