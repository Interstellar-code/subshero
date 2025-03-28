@extends('layouts.auth')

@section('content')

    <style>
        * {
            font-family: "DM Sans";
            font-style: normal;
            font-weight: normal;
        }

        .custom_container {
            padding: 0;
        }

        /* Confirm your Registration */

        .txt_confirm {
            font-weight: 500;
            font-size: 30px;
            line-height: 45px;
            color: #353535;
        }

        .email_input {
            background: #EAEAEA;
            border-radius: 5px;
            height: 53px;
            width: 316px;
            font-size: 16px;
            line-height: 24px;
            color: #141414;
        }

        .email_input:focus {
            background: #EAEAEA;
            color: #141414;
        }

        .email_img {
            background: #F7F7F7;
        }

        .btn_resend {
            font-size: 16px;
            font-weight: 600;
            line-height: 24px;
            color: #002D2E;
            background: #F9C916;
            border-radius: 5px;
            width: 250px;
            height: 50px;
        }

        .txt_1 {
            color: #383838;
            font-size: 16px;
            line-height: 24px;
        }

        .txt_2 {
            font-weight: normal;
            font-size: 16px;
            line-height: 24px;
            color: #3E3E3E;
        }

        .col_2 {
            background: #F7F7F7;
            min-height: 656px;
            /* box-shadow: 0px 4px 35px rgba(0, 0, 0, 0.1); */
            /* border-radius: 10px; */
        }

        .hero_image {
            position: absolute;
            top: 50%;
            left: 50%;
            -moz-transform: translateX(-50%) translateY(-50%);
            -webkit-transform: translateX(-50%) translateY(-50%);
            transform: translateX(-50%) translateY(-50%);
        }

        @media screen and (max-width: 430px) {
            .email_input {
                width: 100%;
            }

            .btn_resend {
                width: 100%;
            }
        }

    </style>


    <div class="main-card mb-3 text-center">
        <div class="row">
            <div class="col-md-6 p-5 mt-3 mb-5">
                <form id="frm_email_verify" action="{{ route('user/email/verify') }}" method="POST">
                    @csrf

                    <img class="img" src="{{ asset('assets/images/svg/email.svg') }}">
                    <h1 class="txt_confirm mb-4 pb-1">@lang('Confirm your Registration')</h1>

                    @if (isset($email_sent) && $email_sent)
                        {{-- <p class="txt_1">@lang('We just sent an email to the address:')</p> --}}
                    @endif
                    <p class="txt_1">@lang('We just sent an email to the address:')</p>

                    {{-- <input type="email" name="email" value="{{ $email }}" class="form-control email_input mx-auto text-center" readonly> --}}


                    <div class="position-relative form-group">
                        <input type="email" class="form-control email_input mx-auto text-center @error('email') is-invalid @enderror @error('g-recaptcha-response') is-invalid @enderror" name="email" value="{{ $email }}" readonly>

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


                    <p class="txt_2 mt-4 mb-0">
                        @lang('Please check your email and click on the link')
                        <br>
                        @lang('provided to verify your address.')
                    </p>

                    @if (lib()->config->recaptcha_status)
                        <button type="submit" class="btn btn-default btn_resend mt-5 g-recaptcha" data-sitekey="{{ lib()->config->recaptcha_site_key }}" data-callback="onSubmit" data-action="submit">@lang('Resend')</button>
                    @else
                        <button type="submit" class="btn btn-default btn_resend mt-5">@lang('Resend')</button>
                    @endif
                </form>
            </div>
            <div class="col-md-6">
                <div class="p-5 col_2">
                    <img class="hero_image" src="{{ asset('assets/images/svg/hero1.svg') }}">
                </div>
            </div>
        </div>
    </div>

    <script>
        function onSubmit(token) {
            document.getElementById("frm_email_verify").submit();
        }
    </script>

@endsection
