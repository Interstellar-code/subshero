<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Subshero</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <meta name="description" content="Subshero">

    <link rel="icon" href="{{ asset_ver('assets/images/favicon.ico') }}" type="image/ico" sizes="48x48">

    <!-- Disable tap highlight on IE -->
    <meta name="msapplication-tap-highlight" content="no">

    @production
        <link href="{{ asset_ver('assets/css/main.min.css') }}" rel="stylesheet">
        <link href="{{ asset_ver('assets/css/custom.min.css') }}" rel="stylesheet">
    @else
        <link href="{{ asset_ver('assets/css/main.css') }}" rel="stylesheet">
        <link href="{{ asset_ver('assets/css/custom.css') }}" rel="stylesheet">
    @endproduction
    <script src="{{ asset_ver('js/jquery/jquery-3.5.1.min.js') }}"></script>

    <!-- reCAPTCHA v3 -->
    <script src="https://www.google.com/recaptcha/api.js"></script>

    <style>
        .footer {
            position: fixed;
            left: 0;
            bottom: 0;
            width: 100%;
            background-color: #0A3638;
            color: white;
            text-align: center;
            font-size: 14px;
            padding: 20px 0 20px 0;
            font-family: "DM Sans";
        }

        .footer a {
            color: white;
        }

        .footer a:hover {
            text-decoration: none;
            color: #ffbe00;
        }

        .footer .company_link {
            color: #ffbe00;
        }

        .red {
            color: #f90c63;
        }

    </style>


    <!-- Custom script -->
    <style>
        {!! lib()->do->get_script_css() !!}

    </style>
</head>

<body>
    <!-- Custom script -->
    {!! lib()->do->get_script_header() !!}


    <div class="app-container app-theme-white body-tabs-shadow">
        <div class="app-container">
            <div class="h-100 bg-animation">
                <div class="mx-auto app-login-box col-md-8">
                    <div class="container-fluid w-100 mx-auto">
                        <main>
                            @yield('content')
                            <br>
                            <br>
                            <br>
                        </main>
                    </div>
                    <div class="text-center text-white opacity-8 mt-3">@lang('Copyright © Subshero') {{ date('Y') }}</div>
                </div>
            </div>

            <div class="footer">
                <div class="row">
                    <div class="col-md-6">
                        <p class="text-center text-white mt-3">
                            © {{ date('Y') }} Subshero | Created with
                            <span class="red">❤</span>
                            by
                            <a class="company_link" href="https://interstellarconsulting.com/">
                                @lang('Novatec Consulting')
                            </a>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-center text-white mt-3">
                            <a class="mr-5" href="https://subshero.com/privacy-policy">
                                @lang('Privacy Policy')
                            </a>
                            <a href="https://subshero.com/terms">
                                @lang('Terms of Service')
                            </a>
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <script src="{{ asset_ver('assets/js/main.js') }}"></script>
    @include('layouts/xeno')

    <!-- Custom script -->
    {!! lib()->do->get_script_footer() !!}

</body>

</html>
