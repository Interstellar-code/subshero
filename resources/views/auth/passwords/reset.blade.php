@extends('layouts.app')

@section('content')
    <div class="modal-header">
        <div class="h5 modal-title">Forgot your Password?<h6 class="mt-1 mb-0 opacity-8"><span>Use the form below to recover it.</span></h6>
        </div>
    </div>

    <form id="frm_password_reset" method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="modal-body">

            <div class="form-group row">
                <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                <div class="col-md-6">
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror @error('g-recaptcha-response') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror

                    @error('g-recaptcha-response')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                <div class="col-md-6">
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                <div class="col-md-6">
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                </div>
            </div>
        </div>

        <div class="modal-footer clearfix">
            <div class="float-right">
                @if (lib()->config->recaptcha_status)
                    <button type="submit" class="btn btn-primary btn-lg g-recaptcha" data-sitekey="{{ lib()->config->recaptcha_site_key }}" data-callback="onSubmit" data-action="submit">@lang('Reset Password')</button>
                @else
                    <button type="submit" class="btn btn-primary btn-lg">@lang('Reset Password')</button>
                @endif
            </div>
        </div>

    </form>

    <script>
        function onSubmit(token) {
            document.getElementById("frm_password_reset").submit();
        }
    </script>

@endsection
