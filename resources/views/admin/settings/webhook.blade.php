@extends(request()->ajax() ? 'admin/layouts/ajax' : 'admin/layouts/settings')

@section('head')
@endsection

@section('content')
    <div class="main-card mb-3 row">
        <div class="col-md-12">
            <div class="card hp-100">
                <h5 class="card-header">@lang('Webhook')</h5>
                <form action="{{ route('admin/settings/webhook/update') }}" id="settings_webhook_update_form" method="POST" class="card-body">
                    @csrf
                    <div class="row">
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="position-relative form-group">
                                <label for="settings_webhook_key" class="">@lang('Webhook Key')</label>
                                <input name="webhook_key" id="settings_webhook_key" value="{{ $data->webhook_key }}" maxlength="{{ len()->config->webhook_key }}" type="text" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="position-relative form-group">
                                <label for="settings_webhook_url" class="">@lang('Webhook URL')</label>
                                <div class="input-group mb-3">
                                    <input name="webhook_url" id="settings_webhook_url" value="{{ $data->webhook_url }}" type="text" class="form-control" readonly>
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary btn_copy" type="button" data-clipboard-text="{{ $data->webhook_url }}" title="@lang('Copy to clipboard')" data-toggle="tooltip" data-placement="right" onclick="app.settings.template.copied(this);">
                                            <i class="fa fa-copy"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button onclick="app.settings.webhook.update(this);" type="submit" class="mb-2 mr-2 btn btn-primary btn-lg">
                        <i class="fa fa-save"></i>&nbsp;
                        @lang('Save')
                    </button>
                </form>
            </div>
        </div>
    </div>

    <br>

    <div class="main-card mb-3">
        <div class="card hp-100">
            <h5 class="card-header">@lang('Webhook Logs')</h5>
            <div class="card-body p-0">
                <div class="table-responsive card border-0 p-3">
                    <table id="tbl_settings_webhook_logs" class="align-middle mb-0 table table-borderless table-striped table-hover text-center mb-4">
                        <thead>
                            <tr>
                                <th class="p-3">@lang('#')</th>
                                <th class="p-3">@lang('Date')</th>
                                <th class="p-3">@lang('Actions')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($logs ?? [] as $val)
                                <tr data-id="{{ $val->id }}">
                                    <!-- <td>{{ $loop->iteration }}</td> -->
                                    <td>{{ $val->id }}</td>
                                    <td>{{ date('d M Y', strtotime($val->created_at)) }}</td>
                                    <td>
                                        <button class="mb-2 mr-2 btn-icon btn-icon-only btn-pill btn btn-outline-primary border" onclick="app.settings.webhook.log_view(this);">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>


    <script>
        $(document).ready(function() {
            $('#tbl_settings_webhook_logs').DataTable({});
            var clipboard = new ClipboardJS('.btn_copy');
        });
    </script>

@endsection
