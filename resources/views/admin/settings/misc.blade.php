@extends(request()->ajax() ? 'admin/layouts/ajax' : 'admin/layouts/settings')

@section('head')
@endsection

@section('content')
    <div class="main-card mb-3 row">

        <div class="col-md-6 mb-4">
            <div class="card hp-100">
                <form action="{{ route('admin/settings/misc/recaptcha/update') }}" id="settings_misc_recaptcha_update_form" method="POST">
                    <div class="card-header">
                        <h5>@lang('reCAPTCHA v3')</h5>
                        <div class="header_toggle_btn_container toggle btn btn-warning" data-toggle="toggle" style="min-width: 85px;">
                            <input name="recaptcha_status" value="1" type="checkbox" data-toggle="toggle" data-onstyle="success" data-offstyle="danger" {{ $data->recaptcha_status ? 'checked' : null }}>
                            <div class="toggle-group">
                                <label class="btn btn-success toggle-on">@lang('Enable')</label>
                                <label class="btn btn-danger toggle-off">@lang('Disable')</label>
                                <span class="toggle-handle btn btn-light"></span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="position-relative form-group">
                                    <label for="settings_misc_recaptcha_site_key" class="">@lang('Site key')</label>
                                    <input name="recaptcha_site_key" id="settings_misc_recaptcha_site_key" value="{{ $data->recaptcha_site_key }}" maxlength="{{ len()->config->recaptcha_site_key }}" type="text" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="position-relative form-group">
                                    <label for="settings_misc_recaptcha_secret_key" class="">@lang('Secret key')</label>
                                    <input name="recaptcha_secret_key" id="settings_misc_recaptcha_secret_key" value="{{ $data->recaptcha_secret_key }}" maxlength="{{ len()->config->recaptcha_secret_key }}" type="text" class="form-control" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button onclick="app.settings.misc.recaptcha_update(this);" type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i>&nbsp;
                            @lang('Save')
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card hp-100">
                <form action="{{ route('admin/settings/misc/cdn/update') }}" id="settings_misc_cdn_update_form" method="POST">
                    <div class="card-header">
                        <h5>@lang('CDN URL')</h5>
                    </div>
                    <div class="card-body">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="position-relative form-group">
                                    <label for="settings_misc_cdn_base_url" class="">@lang('Base URL')</label>
                                    <input name="cdn_base_url" id="settings_misc_cdn_base_url" value="{{ $data->cdn_base_url }}" maxlength="{{ len()->config->cdn_base_url }}" type="url" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button onclick="app.settings.misc.cdn_update(this);" type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i>&nbsp;
                            @lang('Save')
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card hp-100">
                <form action="{{ route('admin/settings/misc/cron/update') }}" id="settings_misc_cron_update_form" method="POST">
                    @csrf
                    <div class="card-header">
                        <h5>@lang('Cron Parameters')</h5>
                    </div>
                    <div class="card-body">

                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="position-relative form-group mb-3">
                                    <label for="settings_cron_misc_url" class="">@lang('Miscellaneous Cron URL')</label>
                                    <div class="input-group">
                                        <input name="cron_misc_url" id="settings_cron_misc_url" value="{{ $data->cron->misc_url }}" type="text" class="form-control" readonly>
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary btn_copy" type="button" data-clipboard-text="{{ $data->cron->misc_url }}" title="@lang('Copy to clipboard')" data-toggle="tooltip" data-placement="right" onclick="app.ui.copied(this);">
                                                <i class="fa fa-copy"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <small class="text-muted">@lang('Used to: Delete old events, webhook logs, email logs')</small>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="position-relative form-group">
                                    <label for="settings_cron_misc_days" class="">@lang('Days Before')</label>
                                    <input name="cron_misc_days" id="settings_cron_misc_days" value="{{ $data->cron_misc_days }}" min="0" type="number" class="form-control">
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card-footer">
                        <button onclick="app.settings.misc.cron_update(this);" type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i>&nbsp;
                            @lang('Save')
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card hp-100">
                <form action="{{ route('admin/settings/misc/xeno/update') }}" id="settings_misc_xeno_update_form" method="POST">
                    <div class="card-header">
                        <h5>@lang('Xeno integration')</h5>
                        <div class="header_toggle_btn_container toggle btn btn-warning" data-toggle="toggle" style="min-width: 85px;">
                            <input name="xeno_send_data" value="1" type="checkbox" data-toggle="toggle" data-onstyle="success" data-offstyle="danger" {{ $data->xeno_send_data ? 'checked' : null }}>
                            <div class="toggle-group">
                                <label class="btn btn-success toggle-on">@lang('Enable')</label>
                                <label class="btn btn-danger toggle-off">@lang('Disable')</label>
                                <span class="toggle-handle btn btn-light"></span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="position-relative form-group">
                                    <label for="settings_misc_recaptcha_site_key" class="">@lang('Xeno public key')</label>
                                    <input name="xeno_public_key" id="settings_misc_xeno_public_key" value="{{ $data->xeno_public_key }}" maxlength="{{ len()->config->xeno_public_key }}" type="text" class="form-control" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button onclick="app.settings.misc.xeno_update(this);" type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i>&nbsp;
                            @lang('Save')
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card hp-100">
                <form action="{{ route('admin/settings/misc/gravitec/update') }}" id="settings_misc_gravitec_update_form" method="POST" enctype="multipart/form-data">
                    <div class="card-header">
                        <h5>@lang('Gravitec.net Push Notification')</h5>
                        <div class="header_toggle_btn_container toggle btn btn-warning {{ $data->gravitec_status ? null : 'off' }}" data-toggle="toggle" style="min-width: 85px;">
                            <input name="gravitec_status" value="1" type="checkbox" data-toggle="toggle" data-onstyle="success" data-offstyle="danger" {{ $data->gravitec_status ? 'checked' : null }}>
                            <div class="toggle-group">
                                <label class="btn btn-success toggle-on">@lang('Enable')</label>
                                <label class="btn btn-danger toggle-off">@lang('Disable')</label>
                                <span class="toggle-handle btn btn-light"></span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="position-relative form-group">
                                    <label for="settings_misc_gravitec_app_key" class="">@lang('App key')</label>
                                    <input name="gravitec_app_key" id="settings_misc_gravitec_app_key" value="{{ $data->gravitec_app_key }}" maxlength="{{ len()->config->gravitec_app_key }}" type="text" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="position-relative form-group">
                                    <label for="settings_misc_gravitec_app_secret" class="">@lang('App secret')</label>
                                    <input name="gravitec_app_secret" id="settings_misc_gravitec_app_secret" value="{{ $data->gravitec_app_secret }}" maxlength="{{ len()->config->gravitec_app_secret }}" type="text" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="position-relative form-group">
                                    <label for="settings_misc_gravitec_manifest_json" class="">
                                        <span>manifest.json file</span>

                                        {{-- Check if manifest.json file exists --}}
                                        @if (file_exists(storage_path(DIR_GRAVITEC_PUSH . 'manifest.json')))
                                            <span class="ml-2">
                                                (<a href="{{ url('manifest.json') }}" class="text-primary link-primary font-weight-bold" target="_blank">@lang('View')</a>)
                                            </span>
                                        @endif

                                    </label>
                                    <input name="gravitec_manifest_json" id="settings_misc_gravitec_manifest_json" type="file" class="form-control" accept="application/json">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="position-relative form-group">
                                    <label for="settings_misc_gravitec_push_worker_js" class="">
                                        <span>push-worker.js file</span>

                                        {{-- Check if push-worker.js file exists --}}
                                        @if (file_exists(storage_path(DIR_GRAVITEC_PUSH . 'push-worker.js')))
                                            <span class="ml-2">
                                                (<a href="{{ url('push-worker.js') }}" class="text-primary link-primary font-weight-bold" target="_blank">@lang('View')</a>)
                                            </span>
                                        @endif

                                    </label>
                                    <input name="gravitec_push_worker_js" id="settings_misc_gravitec_push_worker_js" type="file" class="form-control" accept="application/javascript">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button onclick="app.settings.misc.gravitec_update(this);" type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i>&nbsp;
                            @lang('Save')
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card hp-100">
                <div class="card-header">
                    <h5>@lang('Miscellaneous Toggle Buttons')</h5>
                </div>
                <div class="card-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">@lang('Description')</th>
                                <th scope="col">@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($misc_toggle_options as $key => $value)
                                <tr>
                                    <td>
                                        <strong>{{ __($value['name']) }}</strong><br>
                                        <small class="text-muted">{{ __($value['description']) }}</small>
                                    </td>
                                    <td>
                                        <div class="toggle btn btn-warning {{ $value['status'] ? null : 'off' }}" style="min-width: 85px;" onclick="app.settings.misc.toggle_update(this, '{{ $key }}');">
                                            <input name="settings_misc_toggle_{{ $key }}" value="1" type="checkbox" data-toggle="toggle" data-onstyle="success" data-offstyle="danger" {{ $value['status'] ? 'checked' : null }}>
                                            <div class="toggle-group">
                                                <label class="btn btn-success toggle-on">@lang('Enable')</label>
                                                <label class="btn btn-danger toggle-off">@lang('Disable')</label>
                                                <span class="toggle-handle btn btn-light"></span>
                                            </div>
                                        </div>
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
            app.ui.btn_toggle();

            var clipboard = new ClipboardJS('.btn_copy');

        });
    </script>
@endsection
