@extends(request()->ajax() ? 'client/layouts/ajax' : 'client/layouts/settings')

@section('head')
@endsection

@section('content')
    <div class="main-card mb-3">
        <h5>@lang('Teams')</h5>
        <div class="row">
            <div class="col-md-6">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">@lang('Name')</th>
                            <th scope="col">@lang('Email')</th>
                            <th scope="col">@lang('Status')</th>
                            <th scope="col">@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach (lib()->team->get_by_user() ?? [] as $val)
                            <tr data-id="{{ $val->id }}">
                                <!-- <td>{{ $loop->iteration }}</td> -->
                                <td>{{ $val->user_name }}</td>
                                <td>{{ $val->user_email }}</td>

                                @if ($val->status == 1)
                                    <td><span class="badge badge-info">{{ table('users_teams.status', $val->status) }}</span></td>
                                @elseif ($val->status == 2)
                                    <td><span class="badge badge-success">{{ table('users_teams.status', $val->status) }}</span></td>
                                @endif

                                <td>
                                    <button onclick="app.settings.team.unlink(this);" title="@lang('Unlink')" class="btn-icon btn-icon-only btn-pill btn btn-outline-danger btn-sm" data-toggle="tooltip" data-placement="top" type="button">
                                        <i class="fa fa-unlink"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <!--Added tooltip for team Add button-->
        <button class="mb-2 mr-2 btn btn-primary" type="button" data-target="#modal_settings_team_link" data-toggle="modal,tooltip" data-placement="right" title="@lang('Click here to invite new user')">
            <i class="fa fa-user-plus"></i>&nbsp;
            @lang('Invite')
        </button>
    </div>
@endsection
