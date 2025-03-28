@extends(request()->ajax() ? 'client/layouts/ajax' : 'client/layouts/settings')

@section('head')
@endsection

@section('content')
    <div class="main-card mb-3 row">

        <div class="col-lg-6 mb-4">
            <div class="card hp-100">
                <div class="card-header">
                    <h5>@lang('Token Key')</h5>
                    <button class="btn btn-primary mt-2" type="button" data-target="#modal_settings_api_token_add" data-toggle="modal,tooltip" data-placement="right" title="@lang('Create new key')">
                        <i class="fa fa-plus"></i>&nbsp;
                        @lang('Create')
                    </button>
                </div>
                <div class="card-body table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">@lang('Name')</th>
                                <th scope="col">@lang('Last Accessed')</th>
                                <th scope="col">@lang('Created At')</th>
                                <th scope="col">@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach (lib()->api->token->get_by_user() ?? [] as $val)
                                <tr data-id="{{ $val->id }}">
                                    <td>{{ $val->name }}</td>

                                    @if (empty($val->last_used_at))
                                        <td>@lang('Never accessed')</td>
                                    @else
                                        <td>{{ date('d M Y', strtotime($val->last_used_at)) }}</td>
                                    @endif

                                    <td>{{ date('d M Y', strtotime($val->created_at)) }}</td>

                                    <td>

                                        @if (!empty($val->secret_key))
                                            <button onclick="app.settings.api.token.copy('{{ base64_encode($val->secret_key) }}');" title="@lang('Copy Token Key')" class="btn-icon btn-icon-only btn-pill btn btn-outline-primary btn-sm" data-toggle="tooltip" data-placement="right" type="button">
                                                <i class="fa fa-copy"></i>
                                            </button>
                                            &nbsp;
                                        @endif

                                        <button onclick="app.settings.api.token.edit(this, '{{ $val->id }}');" title="@lang('Edit')" class="btn-icon btn-icon-only btn-pill btn btn-outline-primary btn-sm" data-toggle="tooltip" data-placement="top" type="button">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        &nbsp;
                                        <button onclick="app.settings.api.token.delete(this);" title="@lang('Revoke')" class="btn-icon btn-icon-only btn-pill btn btn-outline-danger btn-sm" data-toggle="tooltip" data-placement="top" type="button">
                                            <i class="fa fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Check if user has Pro or Team plan --}}
        @if (in_array(Auth::user()->users_plan->plan_id, array_merge(PRO_PLAN_ALL_ID, TEAM_PLAN_ALL_ID)))
            @include('client/settings/webhook/index')
        @endif

    </div>
@endsection
