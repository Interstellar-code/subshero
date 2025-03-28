@extends('layouts.app')

@section('content')
    <form id="frm_register" method="POST" action="{{ route('register') }}">
        <div class="modal-body">
            <h5 class="modal-title">
                <h4 class="mt-2">
                    <div>Welcome,</div>
                    <span>It only takes a <span class="text-success">few seconds</span> to create your account</span>
                </h4>
            </h5>
            <div class="divider row"></div>
            @csrf

            <div class="form-row">
                <div class="col-md-12">
                    <div class="position-relative form-group">
                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror @error('g-recaptcha-response') is-invalid @enderror" name="name" value="{{ old('name') }}" placeholder="{{ __('Name here...') }}" required autocomplete="name" autofocus>

                        @error('name')
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

                <div class="col-md-12">
                    <div class="position-relative form-group">
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="{{ __('Email here...') }}" required autocomplete="email">

                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="position-relative form-group">
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="{{ __('Password here...') }}" required autocomplete="new-password">

                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="position-relative form-group">
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="{{ __('Repeat Password here...') }}" required autocomplete="new-password">
                    </div>
                </div>
            </div>

            <div class="mt-3 position-relative form-check">
                <input name="check" id="exampleCheck" type="checkbox" class="form-check-input" required>
                <label for="exampleCheck" class="form-check-label">{{ __('Accept our') }} <a href="https://subshero.com/terms-of-service/" target="_blank">{{ __('Terms and Conditions') }}</a>.</label>
            </div>
            <div class="divider row"></div>
            <h6 class="mb-0">{{ __('Already have an account?') }} <a href="{{ route('login') }}" class="text-primary">{{ __('Sign in') }}</a> | <a href="{{ route('password.request') }}" class="text-primary">Recover Password</a></h6>
        </div>
        <div class="modal-footer d-block text-center">
            @if (lib()->config->recaptcha_status)
                <button type="submit" class="btn-wide btn-pill btn-shadow btn-hover-shine btn btn-primary btn-lg g-recaptcha" data-sitekey="{{ lib()->config->recaptcha_site_key }}" data-callback="onSubmit" data-action="submit">{{ __('Create Account') }}</button>
            @else
                <button type="submit" class="btn-wide btn-pill btn-shadow btn-hover-shine btn btn-primary btn-lg">{{ __('Create Account') }}</button>
            @endif
        </div>
    </form>

    <script>
        function onSubmit(token) {
            document.getElementById("frm_register").submit();
        }
    </script>

@endsection
