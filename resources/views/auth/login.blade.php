@extends('layouts.app')

@section('content')
    <form id="frm_login" method="POST" action="{{ route('login') }}">
        <div class="modal-body">
            <div class="h5 modal-title text-center">
                <h4 class="mt-2">
                    <div>Welcome back,</div>
                    <span>Please sign in to your account below.</span>
                </h4>
            </div>
            @csrf
            <div class="form-row">
                <div class="col-md-12">
                    <div class="position-relative form-group">
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror @error('g-recaptcha-response') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="{{ __('Email here...') }}" required autocomplete="email" autofocus>

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
                <div class="col-md-12">
                    <div class="position-relative form-group">
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="{{ __('Password here...') }}" required autocomplete="current-password">

                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="position-relative form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label for="remember" class="form-check-label">{{ __('Keep me logged in') }}</label>
            </div>
            <div class="divider"></div>
            <h6 class="mb-0">No account? <a href="{{ route('register') }}" class="text-primary">Sign up now</a></h6>
        </div>
        <div class="modal-footer clearfix">

            @if (Route::has('password.request'))
                <div class="float-left">
                    <a href="{{ route('password.request') }}" class="btn-lg btn btn-link">{{ __('Recover Password') }}</a>
                </div>
            @endif

            <div class="float-right">
                @if (lib()->config->recaptcha_status)
                    <button type="submit" class="btn btn-primary btn-lg g-recaptcha" data-sitekey="{{ lib()->config->recaptcha_site_key }}" data-callback="onSubmit" data-action="submit">{{ __('Login to Dashboard') }}</button>
                @else
                    <button type="submit" class="btn btn-primary btn-lg">{{ __('Login to Dashboard') }}</button>
                @endif
            </div>
        </div>
    </form>


    <script>
        function onSubmit(token) {
            document.getElementById("frm_login").submit();
        }

        // Reset user session
        document.cookie.split(";").forEach(function(c) {
            document.cookie = c.replace(/^ +/, "").replace(/=.*/, "=;expires=" + new Date().toUTCString() + ";path=/");
        });


        // Clear all other data
        localStorage.clear();

        navigator.serviceWorker.getRegistrations().then(function(registrations) {
            for (let registration of registrations) {
                registration.unregister()
            }
        });

        indexedDB.databases().then(function(databases) {
            databases.forEach(function(database) {
                indexedDB.deleteDatabase(database.name);
            });
        });
    </script>
@endsection
