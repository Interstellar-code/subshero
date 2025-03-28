@extends(request()->ajax() ? 'client/layouts/ajax' : 'client/layouts/settings')

@section('head')
@endsection

@section('content')
    <div class="main-card mb-3">

        <div class="col-md-6 mb-4">
            <div class="card hp-100">
                <form action="{{ route('app/settings/marketplace/update') }}" id="settings_marketplace_update_form" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ lib()->user->me->id }}">
                    <div class="card-header">
                        <h5>@lang('Payment Information')</h5>
                        <div class="header_toggle_btn_container toggle btn btn-warning {{ lib()->user->me->marketplace_status ? null : 'off' }}" data-toggle="toggle" style="min-width: 85px;">
                            <input name="marketplace_status" value="1" type="checkbox" data-toggle="toggle" data-onstyle="success" data-offstyle="danger" {{ lib()->user->me->marketplace_status ? 'checked' : null }}>
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
                                    <label for="settings_marketplace_paypal_api_username" class="">@lang('PayPal API Username')</label>
                                    <input name="paypal_api_username" id="settings_marketplace_paypal_api_username" value="{{ lib()->user->me->paypal_api_username }}" maxlength="{{ len()->users->paypal_api_username }}" type="text" class="form-control" required>
                                    <small class="text-muted">
                                        @lang('Get your credentials here'):
                                        <a href="https://developer.paypal.com/developer/applications/" target="_blank" class="text-primary link-primary font-weight-bold">@lang('PayPal Developer')</a>
                                    </small>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="position-relative form-group">
                                    <label for="settings_marketplace_paypal_api_password" class="">@lang('PayPal API Password')</label>
                                    <input name="paypal_api_password" id="settings_marketplace_paypal_api_password" value="{{ lib()->user->me->paypal_api_password }}" maxlength="{{ len()->users->paypal_api_password }}" type="text" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="position-relative form-group">
                                    <label for="settings_marketplace_paypal_api_secret" class="">@lang('PayPal API Secret')</label>
                                    <input name="paypal_api_secret" id="settings_marketplace_paypal_api_secret" value="{{ lib()->user->me->paypal_api_secret }}" maxlength="{{ len()->users->paypal_api_secret }}" type="text" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="position-relative form-group">
                                    <label for="settings_marketplace_marketplace_token" class="">
                                        <span>@lang('Unique Cart Link')</span>

                                        {{-- Check if token exists --}}
                                        @if (!empty(lib()->user->me->marketplace_token))
                                            <span class="ml-2">
                                                (<a href="{{ route('app/marketplace/showcase', lib()->user->me->marketplace_token) }}" class="text-primary link-primary font-weight-bold" target="_blank">@lang('View')</a>)
                                            </span>
                                        @endif

                                    </label>
                                    <div class="input-group">
                                        <input name="marketplace_token" id="settings_marketplace_marketplace_token" value="{{ lib()->user->me->marketplace_token }}" maxlength="{{ len()->users->marketplace_token }}" type="text" class="form-control" readonly>
                                        <div class="input-group-append">

                                            {{-- Check if token exists --}}
                                            @if (!empty(lib()->user->me->marketplace_token))
                                                <button class="btn btn-outline-secondary btn_copy" type="button" data-clipboard-text="{{ route('app/marketplace/showcase', lib()->user->me->marketplace_token) }}" title="@lang('Copy to clipboard')" data-toggle="tooltip" data-placement="right" onclick="app.ui.copied(this);">
                                                    <i class="fa fa-copy"></i>
                                                </button>
                                            @endif

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button onclick="app.settings.marketplace.update(this);" type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i>&nbsp;
                            @lang('Save')
                        </button>
                    </div>
                </form>
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
