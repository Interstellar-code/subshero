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

    {{-- DM Sans - Google font --}}
    {{-- <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans&display=swap" rel="stylesheet"> --}}

    @production
        <!-- Production specific content... -->

        <link href="{{ asset_ver('assets/css/main.min.css') }}" rel="stylesheet">
        <link href="{{ asset_ver('css/app.min.css') }}" rel="stylesheet">

        <script src="{{ asset_ver('js/jquery/jquery-3.5.1.min.js') }}"></script>

        <!-- jQuery Bar Rating Plugin v1.2.2 -->
        <link href="{{ asset_ver('vendors/jquery-bar-rating/themes/bars-movie.css') }}" rel="stylesheet">
        <script src="{{ asset_ver('vendors/jquery-bar-rating/jquery.barrating.min.js') }}"></script>

        <!-- Bootstrap v4.5.0 -->
        <script src="{{ asset_ver('vendors/bootstrap/js/popper.min.js') }}"></script>
        <script src="{{ asset_ver('vendors/bootstrap/js/bootstrap.min.js') }}"></script>

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
        <script src="{{ asset_ver('vendors/select2/js/select2.min.js') }}"></script>

        <!-- FilePond 4.18.0 -->
        <link href="{{ asset_ver('vendors/filepond/main/filepond.min.css') }}" rel="stylesheet">
        <script src="{{ asset_ver('vendors/filepond/main/filepond.min.js') }}"></script>
        <script src="{{ asset_ver('vendors/filepond/main/filepond.jquery.js') }}"></script>

        <!-- FilePond filepond-plugin-file-encode 4.18.0 -->
        <script src="{{ asset_ver('vendors/filepond/filepond-plugin-file-encode/filepond-plugin-file-encode.min.js') }}"></script>

        <!-- FilePond filepond-plugin-file-validate-type 4.18.0 -->
        <script src="{{ asset_ver('vendors/filepond/filepond-plugin-file-validate-type/filepond-plugin-file-validate-type.min.js') }}"></script>

        <!-- FilePond filepond-plugin-image-exif-orientation 4.18.0 -->
        <script src="{{ asset_ver('vendors/filepond/filepond-plugin-image-exif-orientation/filepond-plugin-image-exif-orientation.min.js') }}"></script>

        <!-- FilePond filepond-plugin-image-preview 4.18.0 -->
        <link href="{{ asset_ver('vendors/filepond/filepond-plugin-image-preview/filepond-plugin-image-preview.min.css') }}" rel="stylesheet">
        <script src="{{ asset_ver('vendors/filepond/filepond-plugin-image-preview/filepond-plugin-image-preview.min.js') }}"></script>

        <!-- FilePond filepond-plugin-image-crop 4.18.0 -->
        <script src="{{ asset_ver('vendors/filepond/filepond-plugin-image-crop/filepond-plugin-image-crop.min.js') }}"></script>

        <!-- FilePond filepond-plugin-image-resize 4.18.0 -->
        <script src="{{ asset_ver('vendors/filepond/filepond-plugin-image-resize/filepond-plugin-image-resize.min.js') }}"></script>

        <!-- FilePond filepond-plugin-image-transform 4.18.0 -->
        <script src="{{ asset_ver('vendors/filepond/filepond-plugin-image-transform/filepond-plugin-image-transform.min.js') }}"></script>

        <!-- FilePond filepond-plugin-file-validate-size 2.2.1 -->
        <script src="{{ asset_ver('vendors/filepond/filepond-plugin-file-validate-size/filepond-plugin-file-validate-size.min.js') }}"></script>

        <script src="{{ asset_ver('vendors/charts/apex-charts.min.js') }}"></script>
        <script src="{{ asset_ver('vendors/form-components/moment.js') }}"></script>
        <!-- <script src="{{ asset_ver('vendors/calendar.js') }}"></script> -->
        <script src="{{ asset_ver('vendors/form-components/datepicker.js') }}"></script>
        <script src="{{ asset_ver('vendors/js-cookie/js.cookie.min.js') }}"></script>
        <script src="{{ asset_ver('vendors/jquery-validation/jquery.validate.min.js') }}"></script>

        <!-- Bootstrap v4.5.0 -->
        <link href="{{ asset_ver('vendors/fullcalendar/main.min.css') }}" rel="stylesheet">
        <script src="{{ asset_ver('vendors/fullcalendar/main.min.js') }}"></script>

        <link href="{{ asset_ver('assets/css/custom.min.css') }}" rel="stylesheet">
        <script src="{{ asset_ver('js/app/subscription.min.js') }}"></script>

        <!-- DataTables 1.10.21 -->
        <link href="{{ asset_ver('vendors/datatables/jquery.dataTables.min.css') }}" rel="stylesheet">
        <script src="{{ asset_ver('vendors/datatables/jquery.dataTables.min.js') }}"></script>
        <link href="{{ asset_ver('vendors/datatables/bootstrap4/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
        <script src="{{ asset_ver('vendors/datatables/bootstrap4/dataTables.bootstrap4.min.js') }}"></script>

    @else
        <!-- Development specific content... -->

        <link href="{{ asset_ver('assets/css/main.css') }}" rel="stylesheet">
        <link href="{{ asset_ver('css/app.css') }}" rel="stylesheet">

        <script src="{{ asset_ver('js/jquery/jquery-3.5.1.js') }}"></script>

        <!-- jQuery Bar Rating Plugin v1.2.2 -->
        <link href="{{ asset_ver('vendors/jquery-bar-rating/themes/bars-movie.css') }}" rel="stylesheet">
        <script src="{{ asset_ver('vendors/jquery-bar-rating/jquery.barrating.min.js') }}"></script>

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
        <script src="{{ asset_ver('vendors/select2/js/select2.js') }}"></script>

        <!-- FilePond 4.18.0 -->
        <link href="{{ asset_ver('vendors/filepond/main/filepond.css') }}" rel="stylesheet">
        <script src="{{ asset_ver('vendors/filepond/main/filepond.js') }}"></script>
        <script src="{{ asset_ver('vendors/filepond/main/filepond.jquery.js') }}"></script>

        <!-- FilePond filepond-plugin-file-encode 4.18.0 -->
        <script src="{{ asset_ver('vendors/filepond/filepond-plugin-file-encode/filepond-plugin-file-encode.js') }}"></script>

        <!-- FilePond filepond-plugin-file-validate-type 4.18.0 -->
        <script src="{{ asset_ver('vendors/filepond/filepond-plugin-file-validate-type/filepond-plugin-file-validate-type.js') }}"></script>

        <!-- FilePond filepond-plugin-image-exif-orientation 4.18.0 -->
        <script src="{{ asset_ver('vendors/filepond/filepond-plugin-image-exif-orientation/filepond-plugin-image-exif-orientation.js') }}"></script>

        <!-- FilePond filepond-plugin-image-preview 4.18.0 -->
        <link href="{{ asset_ver('vendors/filepond/filepond-plugin-image-preview/filepond-plugin-image-preview.css') }}" rel="stylesheet">
        <script src="{{ asset_ver('vendors/filepond/filepond-plugin-image-preview/filepond-plugin-image-preview.js') }}"></script>

        <!-- FilePond filepond-plugin-image-crop 4.18.0 -->
        <script src="{{ asset_ver('vendors/filepond/filepond-plugin-image-crop/filepond-plugin-image-crop.js') }}"></script>

        <!-- FilePond filepond-plugin-image-resize 4.18.0 -->
        <script src="{{ asset_ver('vendors/filepond/filepond-plugin-image-resize/filepond-plugin-image-resize.js') }}"></script>

        <!-- FilePond filepond-plugin-image-transform 4.18.0 -->
        <script src="{{ asset_ver('vendors/filepond/filepond-plugin-image-transform/filepond-plugin-image-transform.js') }}"></script>

        <script src="{{ asset_ver('vendors/charts/apex-charts.js') }}"></script>
        <script src="{{ asset_ver('vendors/form-components/moment.js') }}"></script>
        <!-- <script src="{{ asset_ver('vendors/calendar.js') }}"></script> -->
        <script src="{{ asset_ver('vendors/form-components/datepicker.js') }}"></script>
        <script src="{{ asset_ver('vendors/js-cookie/js.cookie.js') }}"></script>
        <script src="{{ asset_ver('vendors/jquery-validation/jquery.validate.js') }}"></script>

        <!-- Bootstrap v4.5.0 -->
        <link href="{{ asset_ver('vendors/fullcalendar/main.css') }}" rel="stylesheet">
        <script src="{{ asset_ver('vendors/fullcalendar/main.js') }}"></script>

        <link href="{{ asset_ver('assets/css/custom.css') }}" rel="stylesheet">
        <script src="{{ asset_ver('js/app/subscription.js') }}"></script>

        <!-- DataTables 1.10.21 -->
        <link href="{{ asset_ver('vendors/datatables/jquery.dataTables.css') }}" rel="stylesheet">
        <script src="{{ asset_ver('vendors/datatables/jquery.dataTables.js') }}"></script>
        <link href="{{ asset_ver('vendors/datatables/bootstrap4/dataTables.bootstrap4.css') }}" rel="stylesheet">
        <script src="{{ asset_ver('vendors/datatables/bootstrap4/dataTables.bootstrap4.js') }}"></script>

    @endproduction

    <!-- Pickr 1.7.2 -->
    <link href="{{ asset_ver('vendors/pickr/themes/classic.min.css') }}" rel="stylesheet">
    <link href="{{ asset_ver('vendors/pickr/themes/monolith.min.css') }}" rel="stylesheet">
    <link href="{{ asset_ver('vendors/pickr/themes/nano.min.css') }}" rel="stylesheet">
    <script src="{{ asset_ver('vendors/pickr/pickr.min.js') }}"></script>

    <script src="{{ asset_ver('js/init.js') }}"></script>

    <!-- reCAPTCHA v3 -->
    <script src="https://www.google.com/recaptcha/api.js"></script>

    @include('ap-js')
    <script src="{{ asset_ver('js/custom.js') }}"></script>
    <script src="{{ asset_ver('js/app/folder.js') }}"></script>

    @yield('head')

    <style>
        .datepicker-container {
            z-index: 1100 !important;
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

    <div class="app-container app-theme-white">
        <div class="app-top-bar bg-plum-plate top-bar-text-light disabled">
        </div>
        <div class="app-header header-shadow">
            <div class="container fiori-container">
                <div class="app-header__mobile-menu">
                    <div>
                        <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                            <span class="hamburger-box">
                                <span class="hamburger-inner"></span>
                            </span>
                        </button>
                    </div>
                </div>
                <div class="app-header__logo mx-auto">
                    <a href="{{ url('/') }}" class="logo-src"></a>
                </div>

                <div class="app-header__menu">
                    <span>
                        <button type="button" class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                            <span class="btn-icon-wrapper">
                                <i class="fa fa-ellipsis-v fa-w-6"></i>
                            </span>
                        </button>
                    </span>
                </div>
            </div>
        </div>
        <div class="app-main">
            <div class="app-main__outer">
                <div class="app-main__inner">
                    <div class="app-inner-layout app-inner-layout-page">
                        <div class="app-inner-layout__wrapper">
                            <div class="app-inner-layout__sidebar">
                                <div class="app-layout__sidebar-inner dropdown-menu-rounded">
                                    <div class="nav flex-column">
                                        <div class="nav-item-header text-primary nav-item">
                                            Dashboards Examples
                                        </div>
                                        <a class="dropdown-item" href="analytics-dashboard.html">Analytics</a>
                                        <a class="dropdown-item" href="management-dashboard.html">Management</a>
                                        <a class="dropdown-item" href="advertisement-dashboard.html">Advertisement</a>
                                        <a class="dropdown-item active" href="index.html">Helpdesk</a>
                                        <a class="dropdown-item" href="monitoring-dashboard.html">Monitoring</a>
                                        <a class="dropdown-item" href="crypto-dashboard.html">Cryptocurrency</a>
                                        <a class="dropdown-item" href="pm-dashboard.html">Project Management</a>
                                        <a class="dropdown-item" href="product-dashboard.html">Product</a>
                                        <a class="dropdown-item" href="statistics-dashboard.html">Statistics</a>
                                    </div>
                                </div>
                            </div>
                            <div class="app-inner-layout__content">
                                <div class="tab-content">
                                    <div class="container fiori-container custom_container">
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


    <div id="HTML_Static" style="display: none;" hidden>
        <div id="loading_default" class="body-block-example-1" style="cursor: default;">
            <div class="loader bg-transparent no-shadow p-0">
                <div class="ball-grid-pulse">
                    <div class="bg-white"></div>
                    <div class="bg-white"></div>
                    <div class="bg-white"></div>
                    <div class="bg-white"></div>
                    <div class="bg-white"></div>
                    <div class="bg-white"></div>
                    <div class="bg-white"></div>
                    <div class="bg-white"></div>
                    <div class="bg-white"></div>
                </div>
            </div>
        </div>
    </div>
    @include('layouts/xeno')

    <!-- Custom script -->
    {!! lib()->do->get_script_footer() !!}

</body>

</html>
