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
        .app-logo-inverse {
            height: 92px;
            width: 360px;
            background-position: right;
        }

        .animation_container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
        }

        .animation_container svg {
            transform-origin: top left;
            transform: scale(1.5);
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

    <div class="animation_container">
        @include('auth/animation')
    </div>

    <div class="app-container app-theme-white body-tabs-shadow">
        <div class="app-container">
            <div class="h-100 bg-animation">
                <div class="d-flex h-100 justify-content-center align-items-center">
                    <div class="mx-auto app-login-box col-md-8">
                        <div class="app-logo-inverse mx-auto mb-3"></div>
                        <div class="modal-dialog w-100 mx-auto">
                            <div class="modal-content">
                                <main>
                                    @yield('content')
                                </main>
                            </div>
                        </div>
                        <div class="text-center text-white opacity-8 mt-3">@lang('Copyright © Subshero') {{ date('Y') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset_ver('assets/js/main.js') }}"></script>

    <script>
        $(document).ready(function() {
            svg_resize();
        });

        $(window).resize(function() {
            svg_resize();
        });

        function svg_resize() {
            let svg = $('.animation_container svg');
            if (!svg.length) {
                return false;
            }

            let svg_height = svg.get(0).clientHeight;
            let svg_width = svg.get(0).clientWidth;

            let scale_x = 1;
            let scale_y = 1;
            let scale_all = 1;

            let height_difference = window.innerHeight - svg_height;
            let width_difference = window.innerWidth - svg_width;

            if (height_difference > 0) {
                scale_y = window.innerHeight / svg_height;
                scale_y = parseFloat(scale_y) * 1.02;
            }

            if (width_difference > 0) {
                scale_x = window.innerWidth / svg_width;
                scale_x = parseFloat(scale_x) * 1.02;
            }

            if (scale_x > scale_y) {
                scale_all = scale_x;
            } else {
                scale_all = scale_y;
            }

            // svg.css('transform', 'scale(' + scale_x + ', ' + scale_y + ')');
            svg.css('transform', 'scale(' + scale_all + ')');
        }
    </script>
    @include('layouts/xeno')

    <!-- Custom script -->
    {!! lib()->do->get_script_footer() !!}

</body>

</html>
