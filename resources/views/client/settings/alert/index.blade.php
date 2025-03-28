@extends(request()->ajax() ? 'client/layouts/ajax' : 'client/layouts/settings')

@section('head')
@endsection

@section('content')

    <div class="main-card mb-3">
        <h5>@lang('Alert Profiles')</h5>
        <div class="row">
            <div class="col-md-6">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">@lang('Name')</th>
                            <th scope="col">@lang('Condition')</th>
                            <th scope="col">@lang('Subscription Type')</th>
                            <th scope="col">@lang('Number of days')</th>
                            <th scope="col">@lang('Default')</th>
                            <th scope="col" class="pr-5">@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>

                        {{-- System default alert profile --}}
                        @php
                            $system_default_profile = lib()->do->get_alert_profile_system_default();
                            $system_default_profile_ltd = lib()->do->get_alert_profile_system_default_ltd();
                        @endphp

                        @if (!empty($system_default_profile->id))
                            <tr data-id="{{ $system_default_profile->id }}">
                                <td>{{ $system_default_profile->alert_name }}</td>
                                <td>{{ table('users_alert.alert_condition.' . $system_default_profile->alert_condition) }}</td>
                                <td>{{ table('users_alert.alert_subs_type.' . $system_default_profile->alert_subs_type) }}</td>
                                <td>{{ $system_default_profile->time_period }}</td>
                                <td>
                                    <span class="text-capitalize badge badge-{{ $system_default_profile->is_default ? 'success' : 'warning' }}">{{ table('users_alert.is_default.' . $system_default_profile->is_default) }}</span>
                                </td>
                                <td>
                                    &nbsp;
                                </td>
                            </tr>
                        @endif

                        @if (!empty($system_default_profile_ltd->id))
                            <tr data-id="{{ $system_default_profile_ltd->id }}">
                                <td>{{ $system_default_profile_ltd->alert_name }}</td>
                                <td>{{ table('users_alert.alert_condition.' . $system_default_profile_ltd->alert_condition) }}</td>
                                <td>{{ table('users_alert.alert_subs_type.' . $system_default_profile_ltd->alert_subs_type) }}</td>
                                <td>{{ $system_default_profile_ltd->time_period }}</td>
                                <td>
                                    <span class="text-capitalize badge badge-{{ $system_default_profile_ltd->is_default ? 'success' : 'warning' }}">{{ table('users_alert.is_default.' . $system_default_profile_ltd->is_default) }}</span>
                                </td>
                                <td>
                                    &nbsp;
                                </td>
                            </tr>
                        @endif

                        @foreach (lib()->user->alert->get_by_user() ?? [] as $val)
                            <tr data-id="{{ $val->id }}">
                                <!-- <td>{{ $loop->iteration }}</td> -->
                                <td>{{ $val->alert_name }}</td>
                                <td>{{ table('users_alert.alert_condition.' . $val->alert_condition) }}</td>
                                <td>{{ table('users_alert.alert_subs_type.' . $val->alert_subs_type) }}</td>
                                <td>{{ $val->time_period }}</td>
                                <td>
                                    <span class="text-capitalize badge badge-{{ $val->is_default ? 'success' : 'warning' }}">{{ table('users_alert.is_default.' . $val->is_default) }}</span>
                                </td>
                                <td>
                                    <button onclick="app.settings.alert.edit(this, '{{ $val->id }}');" title="@lang('Edit')" class="btn-icon btn-icon-only btn-pill btn btn-outline-primary btn-sm" data-toggle="tooltip" data-placement="top" type="button">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    &nbsp;
                                    <button onclick="app.settings.alert.delete(this);" title="@lang('Delete')" class="btn-icon btn-icon-only btn-pill btn btn-outline-danger btn-sm" data-toggle="tooltip" data-placement="top" type="button">
                                        <i class="fa fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <button class="mb-2 mr-2 btn btn-primary" type="button" data-target="#modal_settings_alert_add" data-toggle="modal,tooltip" data-placement="right" title="@lang('Click on Add to Add New Alert Profile')">
            <i class="fa fa-plus"></i>&nbsp;
            @lang('Add New')
        </button>
    </div>

@endsection
