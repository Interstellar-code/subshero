@extends('layouts.app')

@section('content')
    <form id="frm_password_email" method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="modal-header">
            <div class="h5 modal-title">Forgot your Password?<h6 class="mt-1 mb-0 opacity-8"><span>Use the form below to recover it.</span></h6>
            </div>
        </div>
        <div class="modal-body">

            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <div class="form-row">
                <label for="email">{{ __('E-Mail') }}</label>

                <div class="col-md-12">
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror @error('g-recaptcha-response') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Email here..." required autocomplete="email" autofocus>

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
            <div class="divider"></div>
            <h6 class="mb-0">
                <a href="{{ route('login') }}" class="text-primary">Sign in existing account</a>
            </h6>
        </div>
        <div class="modal-footer clearfix">
            <div class="float-right">
                @if (lib()->config->recaptcha_status)
                    <button type="submit" class="btn btn-primary btn-lg g-recaptcha" data-sitekey="{{ lib()->config->recaptcha_site_key }}" data-callback="onSubmit" data-action="submit">Recover Password</button>
                @else
                    <button type="submit" class="btn btn-primary btn-lg">Recover Password</button>
                @endif
            </div>
        </div>
    </form>

    <script>
        function onSubmit(token) {
            document.getElementById("frm_password_email").submit();
        }
    </script>

@endsection
