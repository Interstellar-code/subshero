<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <meta name="description" content="This is an example dashboard created using build-in elements and components.">

    <link rel="icon" href="{{ asset_ver('assets/images/favicon.ico') }}" type="image/ico" sizes="48x48">

    <!-- Disable tap highlight on IE -->
    <meta name="msapplication-tap-highlight" content="no">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @production
        <!-- Production specific content... -->

        <script src="{{ asset_ver('js/jquery/jquery-3.5.1.min.js') }}"></script>

        <!-- Bootstrap v4.5.0 -->
        <script src="{{ asset_ver('vendors/bootstrap/js/bootstrap.min.js') }}"></script>
        <script src="{{ asset_ver('vendors/bootstrap/js/popper.min.js') }}"></script>

        <!-- Bootstrap v4.5.0 -->
        <!-- <link href="{{ asset_ver('vendors/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet"> -->
        <!-- <script src="{{ asset_ver('vendors/bootstrap/js/bootstrap.min.js') }}"></script> -->

        <!-- Ionicons v2.0.0 -->
        <link href="{{ asset_ver('vendors/ionicons/css/ionicons.min.css') }}" rel="stylesheet">

        <!-- jQuery blockUI version 2.70.0-2014.11.23 -->
        <script src="{{ asset_ver('vendors/blockui/jquery.blockUI.js') }}"></script>

        <!-- toastr v2.1.3 -->
        <link href="{{ asset_ver('vendors/toastr/toastr.min.css') }}" rel="stylesheet">
        <script src="{{ asset_ver('vendors/toastr/toastr.min.js') }}"></script>

        <!-- Sweet Alert v2.1.2 -->
        <script src="{{ asset_ver('vendors/sweetalert/sweetalert.min.js') }}"></script>

        <!-- Select2 version 4.0.13 -->
        <!-- Disabled to load bootstrap 4 styles from main bootstrap.min.css file -->
        {{-- <link href="{{ asset_ver('vendors/select2/css/select2.min.css') }}" rel="stylesheet">
        <link href="{{ asset_ver('vendors/select2/css/select2-bootstrap4.min.css') }}" rel="stylesheet">
        <link href="{{ asset_ver('vendors/select2/bootstrap-theme/select2-bootstrap.min.css') }}" rel="stylesheet"> --}}
        <script src="{{ asset_ver('vendors/select2/js/select2.full.min.js') }}"></script>

        <script src="{{ asset_ver('vendors/charts/apex-charts.js') }}"></script>
        <script src="{{ asset_ver('vendors/form-components/moment.js') }}"></script>
        <script src="{{ asset_ver('vendors/form-components/datepicker.js') }}"></script>
        <script src="{{ asset_ver('vendors/js-cookie/js.cookie.min.js') }}"></script>
        <script src="{{ asset_ver('vendors/jquery-validation/jquery.validate.min.js') }}"></script>

        <!-- DataTables 1.10.21 -->
        <link href="{{ asset_ver('vendors/datatables/jquery.dataTables.min.css') }}" rel="stylesheet">
        <script src="{{ asset_ver('vendors/datatables/jquery.dataTables.min.js') }}"></script>
        <link href="{{ asset_ver('vendors/datatables/bootstrap4/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
        <script src="{{ asset_ver('vendors/datatables/bootstrap4/dataTables.bootstrap4.min.js') }}"></script>
    @else
        <!-- Development specific content... -->

        <script src="{{ asset_ver('js/jquery/jquery-3.5.1.js') }}"></script>

        <!-- Bootstrap v4.5.0 -->
        <script src="{{ asset_ver('vendors/bootstrap/js/bootstrap.bundle.js') }}"></script>
        <script src="{{ asset_ver('vendors/bootstrap/js/popper.js') }}"></script>

        <!-- Bootstrap v4.5.0 -->
        <!-- <link href="{{ asset_ver('vendors/bootstrap/css/bootstrap.css') }}" rel="stylesheet"> -->
        <!-- <script src="{{ asset_ver('vendors/bootstrap/js/bootstrap.js') }}"></script> -->

        <!-- Ionicons v2.0.0 -->
        <link href="{{ asset_ver('vendors/ionicons/css/ionicons.css') }}" rel="stylesheet">

        <!-- jQuery blockUI version 2.70.0-2014.11.23 -->
        <script src="{{ asset_ver('vendors/blockui/jquery.blockUI.js') }}"></script>

        <!-- toastr v2.1.3 -->
        <link href="{{ asset_ver('vendors/toastr/toastr.css') }}" rel="stylesheet">
        <script src="{{ asset_ver('vendors/toastr/toastr.min.js') }}"></script>

        <!-- Sweet Alert v2.1.2 -->
        <script src="{{ asset_ver('vendors/sweetalert/sweetalert.min.js') }}"></script>

        <!-- Select2 version 4.0.13 -->
        <!-- Disabled to load bootstrap 4 styles from main bootstrap.css file -->
        {{-- <link href="{{ asset_ver('vendors/select2/css/select2.css') }}" rel="stylesheet">
        <link href="{{ asset_ver('vendors/select2/css/select2-bootstrap4.css') }}" rel="stylesheet">
        <link href="{{ asset_ver('vendors/select2/bootstrap-theme/select2-bootstrap.css') }}" rel="stylesheet"> --}}
        <script src="{{ asset_ver('vendors/select2/js/select2.full.js') }}"></script>

        <script src="{{ asset_ver('vendors/charts/apex-charts.js') }}"></script>
        <script src="{{ asset_ver('vendors/form-components/moment.js') }}"></script>
        <script src="{{ asset_ver('vendors/form-components/datepicker.js') }}"></script>
        <script src="{{ asset_ver('vendors/js-cookie/js.cookie.js') }}"></script>
        <script src="{{ asset_ver('vendors/jquery-validation/jquery.validate.js') }}"></script>

        <!-- DataTables 1.10.21 -->
        <link href="{{ asset_ver('vendors/datatables/jquery.dataTables.css') }}" rel="stylesheet">
        <script src="{{ asset_ver('vendors/datatables/jquery.dataTables.js') }}"></script>
        <link href="{{ asset_ver('vendors/datatables/bootstrap4/dataTables.bootstrap4.css') }}" rel="stylesheet">
        <script src="{{ asset_ver('vendors/datatables/bootstrap4/dataTables.bootstrap4.js') }}"></script>

    @endproduction

    <!-- Bootstrap v4.5.0 -->
    <link href="{{ asset_ver('vendors/fullcalendar/main.css') }}" rel="stylesheet">
    <script src="{{ asset_ver('vendors/fullcalendar/main.js') }}"></script>

    <!-- jQuery Bar Rating Plugin v1.2.2 -->
    <link href="{{ asset_ver('vendors/jquery-bar-rating/themes/bars-movie.css') }}" rel="stylesheet">
    <script src="{{ asset_ver('vendors/jquery-bar-rating/jquery.barrating.min.js') }}"></script>


    <!-- Custom files -->
    <link href="{{ asset_ver('assets/css/main.css') }}" rel="stylesheet">
    <link href="{{ asset_ver('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset_ver('assets/css/custom.css') }}" rel="stylesheet">


    <script src="{{ asset_ver('js/init.js') }}"></script>


    @include('ap-js')
    <script src="{{ asset_ver('js/custom.js') }}"></script>
    <script src="{{ asset_ver('js/public/marketplace.js') }}"></script>


    @yield('head')

    <!-- Custom script -->
    <style>
        {!! lib()->do->get_script_css() !!}
    </style>
</head>

<body>
    <!-- Custom script -->
    {!! lib()->do->get_script_header() !!}

    <div class="app-container app-theme-white">
        <div class="app-top-bar bg-plum-plate top-bar-text-light disabled">
        </div>
        <div class="app-header header-shadow">
            <div class="container fiori-container justify-content-between">
                <div class="app-header__mobile-menu">
                    <div>
                        <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                            <span class="hamburger-box">
                                <span class="hamburger-inner"></span>
                            </span>
                        </button>
                    </div>
                </div>
                <div class="app-header__logo">
                    <a href="{{ url('/') }}" class="logo-src"></a>
                </div>

                <div class="header-btn-lg pr-0 pl-0 ml-0">
                    <div class="widget-content p-0">
                        <div class="widget-content-wrapper">
                            <div class="widget-content-left">
                                <div class="btn-group">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="app-main">
            <div class="app-main__outer">
                <div class="app-main__inner">
                    <div class="app-inner-layout app-inner-layout-page">

                        <div class="app-inner-layout__wrapper">
                            <div class="app-inner-layout__content">
                                <div class="tab-content">
                                    <div class="container fiori-container marketplace_container">
                                        <main id="page_container">
                                            @yield('content')
                                        </main>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="app-wrapper-footer">
                    <div class="app-footer">
                        <div class="container fiori-container">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="app-drawer-overlay d-none animated fadeIn"></div>

    {{-- Templates --}}
    @stack('template')

    <div id="HTML_Static" style="display: none;" hidden>
        <div id="loading_default" class="body-block-example-1" style="cursor: default;">
            <img src="{{ asset('assets/images/loader.gif') }}">
        </div>
    </div>


    <script>
        $(document).ready(function() {
            @if (Session::has('error'))
                app.alert.err("{{ Session::get('error') }}", 'Payment Failed');
            @endif
        });
    </script>


    <!-- Custom script -->
    {!! lib()->do->get_script_footer() !!}

</body>

</html>
