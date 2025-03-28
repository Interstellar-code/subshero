@extends('layouts.user')

@section('content')

    <style>
        .circle {
            background-color: #edf7fd;
            padding: 100px;
            border-radius: 500px;

            height: 620px;
            width: 620px;
            text-align: center;
            position: relative;
            padding-top: 0;
            margin-top: 52px;
            margin-bottom: 52px;
        }

        .animation {
            position: absolute;
            top: 250px;
            left: 50%;
            -moz-transform: translateX(-50%);
            -webkit-transform: translateX(-50%);
            transform: translateX(-50%);
        }

        .maintenance_heading {
            color: #065e99;
            font-weight: 600;
            font-size: 50px;
        }

        .maintenance_subtext {
            /* color: #335c77; */
            color: #065e99;
            font-size: 25px;
        }

        .logo {
            height: 27px;
            margin-top: 53px;
            margin-bottom: 53px;
        }

    </style>

    <div class="circle  mx-auto">
        <img class="logo" src="{{ asset_ver('assets/images/logos/logo-text.png') }}">
        <h1 class="maintenance_heading">@lang('This site is under maintenance')</h1>
        <br>
        <p class="maintenance_subtext">@lang("We're preparing to serve you better.")</p>
        <img class="animation" src="{{ asset_ver('assets/images/maintenance/maintenance-animation-transparent.gif') }}">
    </div>

    <script>
        function check_status() {
            // Check maintenance status

            var http = new XMLHttpRequest();
            http.open('HEAD', "{{ route('/') }}");
            http.onreadystatechange = function() {
                if (this.readyState === this.DONE) {
                    let url_slug_arr = this.responseURL.split('/');

                    // Auto redirect if maintenance mode disabled
                    if (url_slug_arr[url_slug_arr.length - 1] == 'maintenance') {
                        setTimeout(check_status, 1000);
                    } else {
                        window.location.href = "{{ route('/') }}";
                    }
                }
            };
            http.send();
        }

        $(document).ready(function() {
            check_status();
        });
    </script>

@endsection
