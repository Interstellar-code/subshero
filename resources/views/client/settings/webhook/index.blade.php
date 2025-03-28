<div class="col-lg-6 mb-4">
    <div class="card hp-100">
        <div class="card-header">
            <h5>@lang('Webhooks')</h5>
            <button class="btn btn-primary mt-2" type="button" data-target="#modal_settings_webhook_add" data-toggle="modal,tooltip" data-placement="right" title="@lang('Create Webhook')">
                <i class="fa fa-plus"></i>&nbsp;
                @lang('Create')
            </button>
        </div>
        <div class="card-body table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">@lang('Name')</th>
                        <th scope="col">@lang('Type')</th>
                        {{-- <th scope="col">@lang('Endpoint URL')</th> --}}
                        <th scope="col">@lang('Events')</th>
                        <th scope="col">@lang('Status')</th>
                        {{-- <th scope="col">@lang('Created At')</th> --}}
                        <th scope="col">@lang('Action')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach (lib()->webhook->get_by_user() ?? [] as $val)
                        <tr data-id="{{ $val->id }}">
                            <td class="mw_100px">{{ $val->name }}</td>
                            <td>
                                <span class="text-capitalize badge badge-{{ $val->type == 1 ? 'primary' : 'info' }}">{{ table('webhooks.type', $val->type) }}</span>
                            </td>
                            {{-- <td class="mw_100px">{{ table('webhooks.type', $val->type) }}</td> --}}
                            {{-- <td class="mw_200px">{{ $val->endpoint_url }}</td> --}}
                            {{-- <td>{{ $val->events }}</td> --}}
                            <td>
                                @foreach ($val->events as $event)
                                    <span class="badge badge-light">{{ $event }}</span>
                                    <br>
                                @endforeach
                            </td>

                            <td>
                                {{-- <span class="text-capitalize badge badge-{{ $val->status ? 'success' : 'warning' }}">{{ table('webhooks.status.' . $val->status) }}</span> --}}
                                <span class="text-capitalize badge badge-{{ $val->status ? 'success' : 'warning' }}">{{ table('webhooks.status', $val->status) }}</span>
                            </td>

                            <td style="width: 100px;">
                                <button onclick="app.settings.webhook.edit(this, '{{ $val->id }}');" title="@lang('Edit')" class="btn-icon btn-icon-only btn-pill btn btn-outline-primary btn-sm" data-toggle="tooltip" data-placement="top" type="button">
                                    <i class="fa fa-edit"></i>
                                </button>
                                &nbsp;
                                <button onclick="app.settings.webhook.delete(this);" title="@lang('Delete')" class="btn-icon btn-icon-only btn-pill btn btn-outline-danger btn-sm" data-toggle="tooltip" data-placement="top" type="button">
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
