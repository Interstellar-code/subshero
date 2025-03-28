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
        <script src="{{ asset_ver('vendors/select2/js/select2.full.min.js') }}"></script>

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

        <!-- Custom files -->
        <link href="{{ asset_ver('assets/css/main.min.css') }}" rel="stylesheet">
        <link href="{{ asset_ver('css/app.min.css') }}" rel="stylesheet">
        <link href="{{ asset_ver('assets/css/custom.min.css') }}" rel="stylesheet">

        <script src="{{ asset_ver('js/app/subscription.min.js') }}"></script>

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

        <!-- FilePond filepond-plugin-file-validate-size 2.2.1 -->
        <script src="{{ asset_ver('vendors/filepond/filepond-plugin-file-validate-size/filepond-plugin-file-validate-size.js') }}"></script>


        <script src="{{ asset_ver('vendors/charts/apex-charts.js') }}"></script>
        <script src="{{ asset_ver('vendors/form-components/moment.js') }}"></script>
        <!-- <script src="{{ asset_ver('vendors/calendar.js') }}"></script> -->
        <script src="{{ asset_ver('vendors/form-components/datepicker.js') }}"></script>
        <script src="{{ asset_ver('vendors/js-cookie/js.cookie.js') }}"></script>
        <script src="{{ asset_ver('vendors/jquery-validation/jquery.validate.js') }}"></script>
        
        <!-- Bootstrap v4.5.0 -->
        <link href="{{ asset_ver('vendors/fullcalendar/main.css') }}" rel="stylesheet">
        <script src="{{ asset_ver('vendors/fullcalendar/main.js') }}"></script>

        <!-- Custom files -->
        <link href="{{ asset_ver('assets/css/main.css') }}" rel="stylesheet">
        <link href="{{ asset_ver('css/app.css') }}" rel="stylesheet">
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

    <!-- jQuery SmartWizard v5 -->
    <link href="{{ asset_ver('vendors/smartwizard/css/smart_wizard_all.css') }}" rel="stylesheet">
    <script src="{{ asset_ver('vendors/smartwizard/js/jquery.smartWizard.js') }}"></script>

    <!-- jQuery Bar Rating Plugin v1.2.2 -->
    <link href="{{ asset_ver('vendors/jquery-bar-rating/themes/bars-movie.css') }}" rel="stylesheet">
    <script src="{{ asset_ver('vendors/jquery-bar-rating/jquery.barrating.min.js') }}"></script>

    <!-- Bootstrap Multiselect v1.1.1 -->
    <link href="{{ asset_ver('vendors/bootstrap-multiselect/css/bootstrap-multiselect.min.css') }}" rel="stylesheet">
    <script src="{{ asset_ver('vendors/bootstrap-multiselect/js/bootstrap-multiselect.min.js') }}"></script>

    <!-- Materialize v1.0.0 -->
    {{-- <link href="{{ asset_ver('vendors/materialize/floating-action-button/floating-btn-custom.css') }}" rel="stylesheet">
    <link href="{{ asset_ver('vendors/materialize/floating-action-button/material-icons.css') }}" rel="stylesheet">
    <script src="{{ asset_ver('vendors/materialize/floating-action-button/floating-btn-custom.js') }}"></script> --}}

    <!-- ionicons v5.5.3 -->
    {{-- <link href="{{ asset_ver('vendors/ionicons/css/ionicons.css') }}" rel="stylesheet"> --}}
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    {{-- <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script> --}}


    <script src="{{ asset_ver('js/init.js') }}"></script>


    @include('ap-js')
    <script src="{{ asset_ver('js/custom.js') }}"></script>
    <script src="{{ asset_ver('js/app/folder.js') }}"></script>
    <script src="{{ asset_ver('js/app/calendar.js') }}"></script>
    <script src="{{ asset_ver('js/app/report.js') }}"></script>
    <script src="{{ asset_ver('js/app/subscription/marketplace.js') }}"></script>
    <script src="{{ asset_ver('js/app/subscription/adapt.js') }}"></script>
    <script src="{{ asset_ver('js/app/subscription/pdf.js') }}"></script>

    @production
        {{-- Load Node.js modules --}}
        <!-- SystemJS -->
        <script src="{{ asset_ver('node_modules/systemjs/dist/system.src.js') }}"></script>
        <script src="{{ asset_ver('js/app/wijmo/systemjs.config.min.js') }}"></script>
        <script>
            System.import("{{ asset_ver('js/app/wijmo/app.min.js') }}");
        </script>
    @else
        {{-- Load Node.js modules --}}
        <!-- SystemJS -->
        <script src="{{ asset_ver('node_modules/systemjs/dist/system.src.js') }}"></script>
        <script src="{{ asset_ver('js/app/wijmo/systemjs.config.js') }}"></script>
        <script>
            System.import("{{ asset_ver('js/app/wijmo/app.js') }}");
        </script>
    @endproduction


    @yield('head')

    <style>
        .datepicker-container {
            z-index: 1100 !important;
        }
    </style>

    <script>
        FilePond.registerPlugin(
            // encodes the file as base64 data
            FilePondPluginFileEncode,

            // validates files based on input type
            FilePondPluginFileValidateType,

            // corrects mobile image orientation
            FilePondPluginImageExifOrientation,

            // previews the image
            FilePondPluginImagePreview,

            // crops the image to a certain aspect ratio
            FilePondPluginImageCrop,

            // resizes the image to fit a certain size
            FilePondPluginImageResize,

            // applies crop and resize information on the client
            FilePondPluginImageTransform,

            // File size validation
            FilePondPluginFileValidateSize
        );

        FilePond.setOptions({
            server: {
                url: "{{ url('/') }}",
                // process: './process',
                // revert: './revert',
                // restore: './restore/',
                load: '/storage/',
                // fetch: './fetch/'
            },
            instantUpload: false,
            allowProcess: false,
        });

        $(document).ready(function() {
            // $('.filepond').filepond({
            //     labelIdle: `Drag & Drop your picture or <span class="filepond--label-action">Browse</span>`,
            //     imagePreviewHeight: 170,
            //     imageCropAspectRatio: '1:1',
            //     imageResizeTargetWidth: 200,
            //     imageResizeTargetHeight: 200,
            //     stylePanelLayout: 'compact circle',
            //     styleLoadIndicatorPosition: 'center bottom',
            //     styleButtonRemoveItemPosition: 'center bottom',
            //     files: [{
            //         // the server file reference
            //         source: 'subscription-5-nSxyLhVG35RCNb8EoZkxfGVxou3Ev3aWkANtjSqZ.jpeg',

            //         // set type to limbo to tell FilePond this is a temp file
            //         options: {
            //             type: 'local'
            //         }
            //     }]
            // });

            app.subscription.init();
        })
    </script>



    @if (lib()->config->gravitec_status)
        <!-- Gravitec.net Push Notification -->
        <script src="https://cdn.gravitec.net/storage/{{ lib()->config->gravitec_app_key }}/client.js" async></script>

        <script>
            var Gravitec = Gravitec || [];
            Gravitec.push(['init', {
                'autoRegister': true,
            }]);
            // Gravitec.push(['registerUserForPush', function() {

            // }]);

            Gravitec.push([
                'getSubscriptionData',
                function(data) {
                    if (data.subscription) {
                        console.log(data.subscription);

                        let form_data = new FormData();
                        form_data.append('_token', app.config.token);
                        form_data.append('auth', data.subscription.auth);
                        form_data.append('browser', data.subscription.browser);
                        form_data.append('endpoint', data.subscription.endpoint);
                        form_data.append('lang', data.subscription.lang);
                        form_data.append('p256dh', data.subscription.p256dh);
                        form_data.append('reg_id', data.subscription.regID);
                        form_data.append('subscription_spec', data.subscription.subscriptionSpec);
                        form_data.append('subscription_strategy', data.subscription.subscriptionStrategy);

                        // Register the user for push notifications
                        $.ajax({
                            url: "{{ route('app/notification/register') }}",
                            type: 'POST',
                            dataType: 'json',
                            data: form_data,
                            success: function($_data, $_textStatus, $_jqXHR) {},
                            processData: false,
                            contentType: false,
                        });
                    }
                },
                function(err) {
                    console.log(err.message);
                }
            ])
        </script>
    @endif

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
                                    <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="p-0 btn avatar_container">
                                        <img width="42" class="rounded-circle" src="{{ img_url(lib()->user->me->image, User_Default_Img) }}" alt="{{ lib()->user->me->name }}">
                                        {{-- <p class="avatar_text">
                                                <span class="name">{{ lib()->user->me->name }}</span>
                                                <br>
                                                <span class="cost">Saved $247</span>
                                            </p> --}}
                                        <i class="fa fa-sort-down fa-lg ml-2 opacity-8"></i>
                                    </a>

                                    <div tabindex="-1" role="menu" aria-hidden="true" class="rm-pointers dropdown-menu dropdown-menu-right profile_dropdown_menu">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-sm-3 col-md-2">
                                                    <img class="rounded-circle profile_user_image" src="{{ img_url(lib()->user->me->image, User_Default_Img) }}" alt="{{ lib()->user->me->name }}">
                                                </div>
                                                <div class="col-sm-9 col-md-10 profile_user_col2">
                                                    <p class="profile_user_plan_container">
                                                        <span class="profile_user_name">{{ lib()->user->me->name }}</span>
                                                        {{-- <span class="profile_plan_name">Pro</span> --}}
                                                    </p>
                                                    <p class="profile_user_link">
                                                        <a href="{{ url('settings/profile') }}" class="nav-link">@lang('Profile')</a>
                                                        <a href="{{ url('settings/preference') }}" class="nav-link">@lang('Account')</a>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="container profile_menu_current_plan">
                                            <a href="{{ url('settings/billing') }}">
                                                <span class="plan_text">@lang('Current Plan')</span>
                                                <span class="plan_name">{{ lib()->user->plan->name ?? __('Free') }}</span>
                                                <i class="fa fa-chevron-right"></i>
                                            </a>
                                        </div>

                                        <div class="container m-0">
                                            <div class="scroll-area-xs profile_menu">
                                                <div class="scrollbar-container ps">
                                                    <ul class="nav flex-column">
                                                        {{-- <li class="nav-item">
                                                                <a href="{{ url('settings/profile') }}" class="nav-link">@lang('Profile')</a>
                                                            </li> --}}
                                                        <li class="nav-item">
                                                            <a href="{{ url('settings/profile') }}" class="nav-link">@lang('My Account')</a>
                                                        </li>
                                                        {{-- <li class="nav-item notification_nav_item">
                                                                <a href="{{ url('/') }}" class="nav-link">@lang('Notifications')</a>
                                                                <div class="icon">
                                                                    <p class="count">1</p>
                                                                </div>
                                                            </li> --}}
                                                        {{-- <li class="nav-item">
                                                                <a href="https://subshero.com/pricing/" class="nav-link">@lang('Subscriptions')</a>
                                                            </li> --}}
                                                        <li class="nav-item">
                                                            <a href="{{ url('settings') }}" class="nav-link">@lang('Settings')</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a href="{{ url('logout') }}" class="nav-link">@lang('Logout')</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="app-header__menu">
                    <span>
                        <button type="button" class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                            <span class="btn-icon-wrapper">
                                <i class="fa fa-ellipsis-v fa-w-6"></i>
                            </span>
                        </button>
                    </span>
                </div> --}}
            </div>
        </div>
        <div class="app-main">
            <div class="app-main__outer">
                <div class="app-main__inner">
                    <div class="app-inner-layout app-inner-layout-page">

                        {{-- <div class="app-inner-bar">
                            <div class="container fiori-container">
                                <div class="inner-bar-left">
                                    <ul class="nav">
                                        <li class="nav-item">
                                            <a href="#" class="nav-link show-menu-btn">
                                                <i class="fa fa-align-left mr-2"></i>
                                                <span class="hide-text-md">Show page menu</span>
                                            </a>
                                            <a href="#" class="nav-link close-menu-btn">
                                                <i class="fa fa-align-right mr-2"></i>
                                                <span class="hide-text-md">Close page menu</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="inner-bar-center">
                                    <ul class="nav">
                                        <li class="nav-item">
                                            <a role="tab" data-toggle="tab" class="nav-link active" href="#tab-content-0">
                                                <span>Overview</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a role="tab" data-toggle="tab" class="nav-link" href="#tab-content-1">
                                                <span>Audiences</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a role="tab" data-toggle="tab" class="nav-link" href="#tab-content-2">
                                                <span>Demographics</span>
                                            </a>
                                        </li>
                                        <li class="nav-item dropdown">
                                            <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link opacity-8">
                                                <span>More</span>
                                                <i class="fa fa-angle-down ml-1 opacity-6"></i>
                                            </a>
                                            <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu-right rm-pointers dropdown-menu-shadow dropdown-menu-hover-link dropdown-menu">
                                                <h6 tabindex="-1" class="dropdown-header">Header</h6>
                                                <button type="button" tabindex="0" class="dropdown-item"><i class="dropdown-icon lnr-inbox"> </i><span>Menus</span></button>
                                                <button type="button" tabindex="0" class="dropdown-item"><i class="dropdown-icon lnr-file-empty"> </i><span>Settings</span></button>
                                                <button type="button" tabindex="0" class="dropdown-item"><i class="dropdown-icon lnr-book"> </i><span>Actions</span></button>
                                                <div tabindex="-1" class="dropdown-divider"></div>
                                                <div class="p-3 text-right">
                                                    <button class="mr-2 btn-shadow btn-sm btn btn-link">View Details</button>
                                                    <button class="mr-2 btn-shadow btn-sm btn btn-primary">Action</button>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <div class="inner-bar-right">
                                    <ul class="nav">
                                        <li class="nav-item">
                                            <a href="javascript:void(0);" class="nav-link open-right-drawer">
                                                <span class="hide-text-md">Show right drawer</span>
                                                <i class="fa fa-align-right ml-2"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div> --}}

                        <div class="app-inner-layout__wrapper">
                            <div class="app-inner-layout__sidebar border-0">
                                <div class="app-layout__sidebar-inner dropdown-menu-rounded custom_container p-0">


                                    <div @if(request()->is('subscription/mass-update')) style="display:none;" @endif; class="subscription_page_elements left_side_folders_related">
                                        <div class="card-header">
                                            <div class="colo-md-6">
                                                <h3 class="p-0 m-0">@lang('Folders')</h3>
                                            </div>
                                            <div class="colo-md-6 w-50 text-right">
                                                <!--Added tooltip for Folder Add button-->
                                                <button class="btn-icon btn-icon-only btn-pill btn btn-outline-primary btn-sm mt-1" data-target="#modal_folder_add" data-toggle="modal,tooltip" data-placement="top" title="@lang('Click on Add to create a folder')">
                                                    <i class="fa fa-folder-plus"></i>
                                                    &nbsp;
                                                    @lang('Add')
                                                </button>
                                            </div>
                                        </div>
                                        <div class="nav flex-column" id="folder_container">

                                            <a class="dropdown-item p-2 mt-4" href="javascript:void(0);">
                                                <div class="widget-content p-0" onclick="app.folder.sort_disable();">
                                                    <div class="widget-content-wrapper">
                                                        <div class="widget-content-left mr-4">
                                                            <i class="icon fa fa-folder-open fa-2x"></i>
                                                        </div>
                                                        <div class="widget-content-left w-100">
                                                            <div class="folder_text">@lang('All Folders')</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>

                                            @foreach (lib()->folder->get_by_user() as $val)
                                                <a class="dropdown-item p-2 {{ isset($_SESSION['subscription_folder_id']) && $_SESSION['subscription_folder_id'] == $val->id ? 'active' : null }}" data-id="{{ $val->id }}" href="javascript:void(0);">

                                                    <div class="widget-content p-0">
                                                        <div class="widget-content-wrapper">
                                                            <div class="widget-content-left mr-4" onclick="app.subscription.get_by_folder('{{ $val->id }}');">
                                                                <i class="icon fa fa-folder-open fa-2x"></i>
                                                            </div>
                                                            <div class="widget-content-left w-85">
                                                                <div class="folder_text d-flex justify-content-between">
                                                                    <span class="truncate" onclick="app.subscription.get_by_folder('{{ $val->id }}');">
                                                                        {{ $val->name }} <span class="badge badge_recurring">{{ lib()->config->currency_symbol[$val->price_type] ?? 'All' }}</span>
                                                                    </span>
                                                                    <button onclick="app.folder.edit(this, '{{ $val->id }}');" title="@lang('Edit')" class="btn-icon btn-icon-only btn-pill btn btn-outline-primary btn-sm" data-toggle="tooltip" data-placement="top">
                                                                        <i class="fa fa-edit"></i>
                                                                    </button>
                                                                    &nbsp;
                                                                    <button onclick="app.folder.delete(this, '{{ $val->id }}');" title="@lang('Delete')" class="btn-icon btn-icon-only btn-pill btn btn-outline-danger btn-sm" data-toggle="tooltip" data-placement="top">
                                                                        <i class="fa fa-trash-alt"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                            @endforeach


                                            {{-- <ul class="todo-list-wrapper list-group list-group-flush">
                                            <li class="list-group-item item_active pt-0 pb-1 pl-1 pr-0">
                                                <div class="widget-content p-0 pl-3 pr-3" onclick="app.folder.sort_disable();">
                                                    <div class="widget-content-wrapper">
                                                        <div class="widget-content-left mr-4">
                                                            <i class="icon fa fa-folder-open fa-2x"></i>
                                                        </div>
                                                        <div class="widget-content-left w-100">
                                                            <div class="folder_text">@lang('All Folders')</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                        <ul class="todo-list-wrapper list-group list-group-flush">
                                            @foreach (lib()->folder->get_by_user() as $val)
                                                <li class="list-group-item pt-0 pb-1 pl-1 pr-0">
                                                    <div class="widget-content p-0 pl-3 pr-3">
                                                        <div class="widget-content-wrapper">
                                                            <div class="widget-content-left mr-4" onclick="app.subscription.get_by_folder('{{ $val->id }}');">
                                                                <i class="icon fa fa-folder-open fa-2x"></i>
                                                            </div>
                                                            <div class="widget-content-left w-85">
                                                                <div class="folder_text d-flex justify-content-between">
                                                                    <span class="truncate" onclick="app.subscription.get_by_folder('{{ $val->id }}');">
                                                                        {{ $val->name }}
                                                                    </span>
                                                                    <button onclick="app.folder.edit(this, '{{ $val->id }}');" title="@lang('Edit')" class="btn-icon btn-icon-only btn-pill btn btn-outline-primary btn-sm" data-toggle="tooltip" data-placement="top">
                                                                        <i class="fa fa-edit"></i>
                                                                    </button>
                                                                    &nbsp;
                                                                    <button onclick="app.folder.delete(this, '{{ $val->id }}');" title="@lang('Delete')" class="btn-icon btn-icon-only btn-pill btn btn-outline-danger btn-sm" data-toggle="tooltip" data-placement="top">
                                                                        <i class="fa fa-trash-alt"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul> --}}






                                            {{-- <div class="nav-item-header text-primary nav-item">
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
                                        <a class="dropdown-item" href="statistics-dashboard.html">Statistics</a> --}}
                                        </div>
                                    </div>

                                    <div @if(!request()->is('subscription/mass-update')) style="display:none;" @endif; class="subscription_page_elements left_side_types_related">
                                        <div class="card-header">
                                            <div class="colo-md-6">
                                                <h3 class="p-0 m-0">@lang('Type')</h3>
                                            </div>
                                        </div>
                                        <div class="nav flex-column" id="folder_container">
                                            @foreach (table('subscription.type') as $key => $type)
                                                <label class="" href="javascript:void(0);">
                                                    <div class="d-flex">
                                                        <div class="mr-3">
                                                            <input {{ $type == 'Subscription' ? 'checked' : '' }} list-control="filter-type" type="radio" name="subscription_type" value="{{ $key }}">
                                                        </div>
                                                        <div class="">
                                                            <strong class="">@lang($type)</strong>
                                                        </div>
                                                    </div>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>




                                    <div class="report_page_elements">
                                        <div class="container position-relative form-group p-0">
                                            <div class="input-group form-control">
                                                <select id="report_period" class="form-control pull-right select_box_white" onchange="app.report.set_period(this.value);" data-type="1" data-toggle="tooltip" data-placement="right" title="@lang('Select Years or Month from drop-down to your expenses')">
                                                    <option value="all_time" {{ local('report_period') == 'all_time' ? 'selected' : null }}>@lang('All Time')</option>
                                                    <option value="this_month" {{ local('report_period') == 'this_month' ? 'selected' : null }}>@lang('Current Month')</option>
                                                    <option value="this_year" {{ local('report_period') == 'this_year' ? 'selected' : null }}>@lang('Current Year')</option>
                                                    <option value="last_year" {{ local('report_period') == 'last_year' ? 'selected' : null }}>@lang('Last Year')</option>
                                                    <option value="{{ date('Y', strtotime('-2 years')) }}" {{ local('report_period') == date('Y', strtotime('-2 years')) ? 'selected' : null }}>@lang(date('Y', strtotime('-2 years')))</option>
                                                    <option value="{{ date('Y', strtotime('-3 years')) }}" {{ local('report_period') == date('Y', strtotime('-3 years')) ? 'selected' : null }}>@lang(date('Y', strtotime('-3 years')))</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="accordion report_accordion" id="report_accordion">
                                            <form id="report_filter_form" onsubmit="return false;">
                                                <div class="card">
                                                    <div class="card-header py-1" id="report_accordion_subscription_heading" data-toggle="collapse" data-target="#report_accordion_subscription_collapse" aria-expanded="false" aria-controls="report_accordion_subscription_collapse">
                                                        <h2 class="mb-0">
                                                            <button class="btn btn-link btn-block text-left" type="button">
                                                                @lang('Types')
                                                            </button>
                                                        </h2>
                                                    </div>

                                                    <div id="report_accordion_subscription_collapse" class="collapse show" aria-labelledby="report_accordion_subscription_heading" data-parent="#report_accordion">
                                                        <div class="card-body pb-0">
                                                            <div class="filter_container">

                                                                <p class="filter_check">
                                                                    <label class="filter_label">
                                                                        <input type="radio" name="type" value="1" class="filter_value mr-2" onchange="app.report.load_charts(this);" checked>
                                                                        <span class="filter_name">@lang('Subscription')</span>
                                                                    </label>
                                                                </p>

                                                                <p class="filter_check">
                                                                    <label class="filter_label">
                                                                        <input type="radio" name="type" value="3" class="filter_value mr-2" onchange="app.report.load_charts(this);">
                                                                        <span class="filter_name">@lang('Lifetime')</span>
                                                                    </label>
                                                                </p>

                                                                {{-- <p class="filter_check">
                                                                    <label class="filter_label">
                                                                        <input type="radio" name="type" value="2" class="filter_value mr-2" onchange="app.report.load_charts(this);">
                                                                        <span class="filter_name">@lang('Trial')</span>
                                                                    </label>
                                                                </p> --}}

                                                                {{-- @foreach (lib()->subscription->get_by_user() as $val)
                                                                <p class="filter_check">
                                                                    <label class="filter_label">
                                                                        <input type="checkbox" name="subscription_id[]" value="{{ $val->id }}" class="filter_value">
                                                                        <span class="filter_name">{{ $val->product_name }}</span>
                                                                    </label>
                                                                </p>
                                                            @endforeach --}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="card my-3">
                                                    <div class="card-header collapsed py-1" id="report_accordion_folder_heading" data-toggle="collapse" data-target="#report_accordion_folder_collapse" aria-expanded="true" aria-controls="report_accordion_folder_collapse">
                                                        <h2 class="mb-0">
                                                            <button class="btn btn-link btn-block text-left" type="button">
                                                                @lang('Folders')
                                                            </button>
                                                        </h2>
                                                    </div>

                                                    <div id="report_accordion_folder_collapse" class="collapse" aria-labelledby="report_accordion_folder_heading" data-parent="#report_accordion">
                                                        <div class="card-body pb-0">
                                                            <div class="filter_container">
                                                                @foreach (lib()->folder->get_by_user() as $val)
                                                                    <p class="filter_check">
                                                                        <label class="filter_label">
                                                                            <input type="checkbox" name="folder_ids[]" value="{{ $val->id }}" class="filter_value" onchange="app.report.load_charts(this);">
                                                                            <span class="filter_name">{{ $val->name }}</span>
                                                                        </label>
                                                                    </p>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="card my-3">
                                                    <div class="card-header collapsed py-1" id="report_accordion_tag_heading" data-toggle="collapse" data-target="#report_accordion_tag_collapse" aria-expanded="false" aria-controls="report_accordion_tag_collapse">
                                                        <h2 class="mb-0">
                                                            <button class="btn btn-link btn-block text-left collapsed" type="button">
                                                                @lang('Tags')
                                                            </button>
                                                        </h2>
                                                    </div>
                                                    <div id="report_accordion_tag_collapse" class="collapse" aria-labelledby="report_accordion_tag_heading" data-parent="#report_accordion">
                                                        <div class="card-body pb-0">
                                                            <div class="filter_container">
                                                                @foreach (lib()->user->tags ?? [] as $val)
                                                                    <p class="filter_check">
                                                                        <label class="filter_label">
                                                                            <input type="checkbox" name="tag_ids[]" value="{{ $val->id }}" class="filter_value" onchange="app.report.load_charts(this);">
                                                                            <span class="filter_name">{{ $val->name }}</span>
                                                                        </label>
                                                                    </p>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="card my-3">
                                                    <div class="card-header collapsed py-1" id="report_accordion_payment_method_heading" data-toggle="collapse" data-target="#report_accordion_payment_method_collapse" aria-expanded="false" aria-controls="report_accordion_payment_method_collapse">
                                                        <h2 class="mb-0">
                                                            <button class="btn btn-link btn-block text-left collapsed" type="button">
                                                                <span class="text">@lang('Payment Methods')</span>
                                                            </button>
                                                        </h2>
                                                    </div>
                                                    <div id="report_accordion_payment_method_collapse" class="collapse" aria-labelledby="report_accordion_payment_method_heading" data-parent="#report_accordion">
                                                        <div class="card-body pb-0">
                                                            <div class="filter_container">
                                                                @foreach (lib()->user->payment_methods ?? [] as $val)
                                                                    <p class="filter_check">
                                                                        <label class="filter_label">
                                                                            <input type="checkbox" name="payment_method_ids[]" value="{{ $val->id }}" class="filter_value" onchange="app.report.load_charts(this);">
                                                                            <span class="filter_name">{{ $val->name }}</span>
                                                                        </label>
                                                                    </p>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>




                                </div>
                            </div>
                            <div class="app-inner-layout__content">
                                <div class="tab-content">
                                    <div class="container fiori-container custom_container">

                                        <div class="app-page-title">
                                            <div class="container fiori-container">
                                                <div class="page-title-wrapper">
                                                    <ul class="horizontal-nav-menu" id="menu_container">
                                                        <li class="dropdown">

                                                            <div class="subscription_page_elements left_side_types_related" @if(!request()->is('subscription/mass-update')) style="display:none;" @endif;>
                                                                <button type="button" class="btn show-menu-btn pl-0" data-toggle="tooltip" data-placement="top" title="@lang('Show/hide Types')">
                                                                    <span class="btn-icon-wrapper pr-1 opacity-7">
                                                                        <i class="fa fa-align-left fa-lg"></i>
                                                                    </span>
                                                                </button>
                                                                <button type="button" class="btn close-menu-btn pl-0" data-toggle="tooltip" data-placement="top" title="@lang('Show/hide Types')">
                                                                    <span class="btn-icon-wrapper pr-1 opacity-7">
                                                                        <i class="fa fa-align-left fa-lg"></i>
                                                                    </span>
                                                                </button>
                                                            </div>
                                                            <div class="subscription_page_elements left_side_folders_related" @if(request()->is('subscription/mass-update')) style="display:none;" @endif;>
                                                                <button type="button" class="btn show-menu-btn pl-0" data-toggle="tooltip" data-placement="top" title="@lang('Show/hide Folders')">
                                                                    <span class="btn-icon-wrapper pr-1 opacity-7">
                                                                        <i class="fa fa-folder fa-lg"></i>
                                                                    </span>
                                                                </button>
                                                                <button type="button" class="btn close-menu-btn pl-0" data-toggle="tooltip" data-placement="top" title="@lang('Show/hide Folders')">
                                                                    <span class="btn-icon-wrapper pr-1 opacity-7">
                                                                        <i class="fa fa-folder fa-lg"></i>
                                                                    </span>
                                                                </button>
                                                            </div>

                                                            <div class="report_page_elements">
                                                                <button type="button" class="btn show-menu-btn pl-0" data-toggle="tooltip" data-placement="top" title="@lang('Show/hide Filters')">
                                                                    <span class="btn-icon-wrapper pr-1 opacity-7">
                                                                        <i class="fa fa-filter fa-lg"></i>
                                                                    </span>
                                                                </button>
                                                                <button type="button" class="btn close-menu-btn pl-0" data-toggle="tooltip" data-placement="top" title="@lang('Show/hide Filters')">
                                                                    <span class="btn-icon-wrapper pr-1 opacity-7">
                                                                        <i class="fa fa-filter fa-lg"></i>
                                                                    </span>
                                                                </button>
                                                            </div>
                                                        </li>
                                                        <li class="dropdown">
                                                            <div class="btn-group">
                                                                <a href="{{ url('/') }}" onclick="app.page.switch('subscription');" tabindex="0" data-slug="subscription" class="m-0 menu_toggle {{ ($slug ?? '') == 'subscription' ? 'active' : null }}">
                                                                    <i class="nav_icon_big fa fa-database"></i>
                                                                    <span>@lang('Subscriptions')</span>
                                                                </a>
                                                                <a data-toggle="dropdown" data-offset="10" data-display="static" data-slug="subscription" aria-expanded="false" class="menu_toggle {{ ($slug ?? '') == 'subscription' ? 'active' : null }}">
                                                                    <i class="nav-icon-pointer icon fa fa-angle-down"></i>
                                                                </a>
                                                                <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu">
                                                                    <a href="{{ action('App\Http\Controllers\Client\SubscriptionController@massUpdate') }}" tabindex="0" data-slug="subscription/massUpdate" class="dropdown-item menu_toggle">
                                                                        @lang('Mass Update')
                                                                    </a>
                                                                    <a href="{{ url('/settings/import') }}" tabindex="0" data-slug="settings/import" class="dropdown-item menu_toggle">
                                                                        @lang('Import/Export')
                                                                    </a>
                                                                    <a tabindex="0" data-toggle="modal" data-target="#modal_subscription_pdf_import" class="dropdown-item menu_toggle">
                                                                        @lang('Invoice Parser')
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="dropdown">
                                                            <a class="menu_toggle {{ ($slug ?? '') == 'report' ? 'active' : null }}" href="{{ url('/report') }}" data-slug="report" onclick="app.page.switch('report');">
                                                                <i class="nav_icon_big fa fa-chart-line"></i>
                                                                <span>@lang('Reports')</span>
                                                            </a>
                                                        </li>
                                                        {{-- <li class="dropdown">
                                                            <a href="{{ url('/report') }}" data-slug="report" class="menu_toggle {{ ($slug ?? '') == 'report' ? 'active' : null }}" onclick="app.page.switch('report');">
                                                                <i class="nav_icon_big fa fa-file"></i>
                                                                <span>@lang('Reports')</span>
                                                            </a>
                                                        </li> --}}
                                                        <li class="dropdown">
                                                            <a href="{{ url('/calendar') }}" data-slug="calendar" class="menu_toggle {{ ($slug ?? '') == 'calendar' ? 'active' : null }}" onclick="app.page.switch('calendar');">
                                                                <i class="nav_icon_big fa fa-calendar"></i>
                                                                <span>@lang('Calendar')</span>
                                                            </a>
                                                        </li>
                                                        <li class="dropdown">
                                                            <a href="{{ url('/explore') }}" data-slug="explore" class="d-none menu_toggle {{ ($slug ?? '') == 'explore' ? 'active' : null }}" onclick="app.page.switch();">
                                                                <i class="nav_icon_big fa fa-crosshairs"></i>
                                                                <span>@lang('Explore')</span>
                                                            </a>
                                                        </li>
                                                        <li class="dropdown">
                                                            <a href="{{ url('/go') }}" data-slug="go" class="d-none menu_toggle {{ ($slug ?? '') == 'go' ? 'active' : null }}" onclick="app.page.switch();">
                                                                <i class="nav_icon_big typcn typcn-tags"></i>
                                                                <span>@lang('Go')</span>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                    <div class="page-title-actions">
                                                        <!--Added tooltip for Quick button-->
                                                        <button type="button" class="btn-shadow btn btn-wide btn-primary btn-lg mr-3" data-toggle="modal,tooltip" data-target="#modal_subscription_quick_add" onclick="lib.sub.modal_subscription_quick_add();" data-placement="bottom" title="@lang('Quick Add your Subscription/Lifetime with minimal fields.')">
                                                            <span class="btn-icon-wrapper pr-1 opacity-7">
                                                                <i class="fa fa-plus"></i>
                                                            </span>
                                                            @lang('Quick Add')
                                                        </button>
                                                        <!--Added tooltip for Add button-->
                                                        <button type="button" class="btn-shadow btn btn-wide btn-primary btn-lg mr-0" data-toggle="modal,tooltip" data-target="#modal_subscription_add" onclick="lib.sub.modal_subscription_add();" data-placement="bottom" title="@lang('Add your Subscription/Lifetime.')">
                                                            <span class="btn-icon-wrapper pr-1 opacity-7">
                                                                <i class="fa fa-plus"></i>
                                                            </span>
                                                            @lang('Add')
                                                        </button>
                                                        {{-- <button type="button" class="btn-shadow btn btn-wide btn-primary btn-lg mr-3 pl-3 pr-3" data-target="#modal_search_add" data-toggle="modal">
                                                            <span class="btn-icon-wrapper pr-1 opacity-7">
                                                                <i class="fa fa-search fa-lg"></i>
                                                            </span>
                                                        </button> --}}
                                                        <!-- <div class="dropdown d-inline-block">
                                                            <button type="button" class="btn-shadow btn btn-wide btn-primary btn-lg mr-3 pl-3 pr-3" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown">
                                                                <span class="btn-icon-wrapper pr-1 opacity-7">
                                                                    <i class="fa fa-search fa-lg"></i>
                                                                </span>
                                                            </button>
                                                            <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu">
                                                                <ul class="nav flex-column">
                                                                    <li class="nav-item-header nav-item">
                                                                        <input name="search" id="exampleEmail" type="search" class="form-control form-control-sm">
                                                                    </li>
                                                                    <li class="nav-item-btn nav-item mx-auto">
                                                                        <button class="btn-wide btn-shadow btn btn-primary btn-sm">@lang('Search')</button>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div> -->
                                                        {{-- <button type="button" class="btn open-right-drawer btn_folder_toggle" data-toggle="tooltip" data-placement="top" title="@lang('Click here to Add Folders.')">
                                                            <span class="btn-icon-wrapper pr-1 opacity-7">
                                                                <i class="fa fa-folder fa-2x"></i>
                                                            </span>
                                                        </button> --}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>



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
    <div class="app-drawer-wrapper my_drawer">
        <div class="drawer-nav-btn">
            <button type="button" class="hamburger hamburger--elastic is-active">
                <span class="hamburger-box"><span class="hamburger-inner"></span></span>
            </button>
        </div>
        <div class="drawer-content-wrapper">
            <div class="scrollbar-container">
                <div class="drawer-heading row">
                    <div class="colo-md-6">
                        <h3 class="p-0 m-0">@lang('Folders')</h3>
                    </div>
                    <div class="colo-md-6 w-50 text-center">
                        <!--Added tooltip for Folder Add button-->
                        <button class="btn-icon btn-icon-only btn-pill btn btn-outline-primary btn-sm mt-1" data-target="#modal_folder_add" data-toggle="modal,tooltip" data-placement="top" title="@lang('Click on Add to create a folder')">
                            <i class="fa fa-folder-plus"></i>
                            &nbsp;
                            @lang('Add')
                        </button>
                    </div>
                </div>
                <div class="drawer-section pl-0 pr-0 pt-1 hp-100">
                    {{-- <div class="folder_container hp-100 overflow-auto" id="folder_container">
                        <ul class="todo-list-wrapper list-group list-group-flush">
                            <li class="list-group-item item_active pt-0 pb-1 pl-1 pr-0">
                                <div class="widget-content p-0 pl-3 pr-3" onclick="app.folder.sort_disable();">
                                    <div class="widget-content-wrapper">
                                        <div class="widget-content-left mr-4">
                                            <i class="icon fa fa-folder-open fa-2x"></i>
                                        </div>
                                        <div class="widget-content-left w-100">
                                            <div class="folder_text">@lang('All Folders')</div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <ul class="todo-list-wrapper list-group list-group-flush">
                            @foreach (lib()->folder->get_by_user() as $val)
                                <li class="list-group-item pt-0 pb-1 pl-1 pr-0">
                                    <div class="widget-content p-0 pl-3 pr-3">
                                        <div class="widget-content-wrapper">
                                            <div class="widget-content-left mr-4" onclick="app.subscription.get_by_folder('{{ $val->id }}');">
                                                <i class="icon fa fa-folder-open fa-2x"></i>
                                            </div>
                                            <div class="widget-content-left w-85">
                                                <div class="folder_text d-flex justify-content-between">
                                                    <span class="truncate" onclick="app.subscription.get_by_folder('{{ $val->id }}');">
                                                        {{ $val->name }}
                                                    </span>
                                                    <button onclick="app.folder.edit(this, '{{ $val->id }}');" title="@lang('Edit')" class="btn-icon btn-icon-only btn-pill btn btn-outline-primary btn-sm" data-toggle="tooltip" data-placement="top">
                                                        <i class="fa fa-edit"></i>
                                                    </button>
                                                    &nbsp;
                                                    <button onclick="app.folder.delete(this, '{{ $val->id }}');" title="@lang('Delete')" class="btn-icon btn-icon-only btn-pill btn btn-outline-danger btn-sm" data-toggle="tooltip" data-placement="top">
                                                        <i class="fa fa-trash-alt"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
    <div class="app-drawer-overlay d-none animated fadeIn"></div>
    <!-- Disabled for too much problem (bootstrap.bundle) -->
    <!-- <script src="{{ asset_ver('assets/js/main.js') }}"></script> -->

    @include('client/subscription/modal')
    @include('client/subscription/adapt/modal')
    @include('client/subscription/pdf/modal')
    @include('client/folder/modal')
    @include('client/search/modal')



    {{-- Templates --}}
    @include('client/subscription/template')

    @stack('template')




    <div id="HTML_Static" style="display: none;" hidden>
        <div id="loading_default" class="body-block-example-1" style="cursor: default;">
            <img src="{{ asset('assets/images/loader.gif') }}">
        </div>
    </div>



    <template id="tpl_select2_favicon" style="display: none;" hidden>
        {{-- <div class="row select_favicon">
            <div class="col-sm-8">__ITEM_NAME__</div>
            <div class="col-sm-4">
                <img src="__ITEM_IMG_SRC__">
            </div>
        </div> --}}

        <div class="select_favicon">
            <p class="name">
                __ITEM_NAME__
                <small>
                    <span class="badge badge-__ITEM_TYPE_CLASS__ d-none p-0 pl-2 pr-2">__ITEM_TYPE__</span>
                </small>
            </p>
            <img class="favicon" src="__ITEM_IMG_SRC__" style="display: none;">
        </div>
    </template>



    <template id="tpl_select2_subscription_brand_id" style="display: none;" hidden>
        <div class="select2_brand_id" data-id="__ITEM_ID__">
            <img class="favicon" src="__ITEM_FAVICON_SRC__">
            <p class="name">__ITEM_PRODUCT_NAME__</p>
            <span class="badge badge-__ITEM_TYPE_CLASS__ p-0 pl-2 pr-2">__ITEM_TYPE__</span>
        </div>
    </template>

    <template id="tpl_select2_subscription_brand_id_selection" style="display: none;" hidden>
        <div class="select2_brand_id" data-id="__ITEM_ID__">
            <img class="favicon" src="__ITEM_FAVICON_SRC__">
            <p class="name">__ITEM_PRODUCT_NAME__</p>
        </div>
    </template>

    <template id="tpl_select2_subscription_brand_id_new" style="display: none;" hidden>
        <div class="select_favicon">
            <p class="name">__ITEM_TEXT__</p>
            <i class="favicon fa fa-plus-circle fa-lg"></i>
        </div>
    </template>

    <template id="tpl_select2_plus_icon" style="display: none;" hidden>
        <div class="select_favicon">
            <p class="name">__ITEM_NAME__</p>
            <i class="favicon fa fa-plus-circle fa-lg"></i>
        </div>
    </template>


    <script>
        $(document).ready(() => {

            // Datepicker

            $('[data-toggle="datepicker"]').datepicker();
            $('[data-toggle="datepicker-year"]').datepicker({
                startView: 2,
            });

            $('[data-toggle="datepicker-month"]').datepicker({
                startView: 1,
            });

            $('[data-toggle="datepicker-inline"]').datepicker({
                inline: true,
            });

            $('[data-toggle="datepicker-icon"]').datepicker({
                trigger: '.datepicker-trigger',
            });

            $('[data-toggle="datepicker-and-icon"]').datepicker({
                format: 'yyyy-mm-dd',
            });
            $('[data-toggle="datepicker-and-icon"]').each(function(index) {
                $(this).siblings('.datepicker-trigger').click(function(e) {
                    e.stopPropagation();
                    $(this).siblings('input[data-toggle="datepicker-and-icon"]').datepicker('show').focus();
                });
            });

            $('[data-toggle="datepicker-button"]').datepicker({
                trigger: '.datepicker-trigger-btn',
            });


            // $('#subscription_add_brand_id').change(function(e) {
            //     let option = $(this).children('option:selected');
            //     let path = $(this).children('option:selected').data('path');
            //     app.subscription.img_add.destroy();
            //     app.subscription.img_add = null;
            //     $('#subscription_add_image_path').val('');

            //     // New fields
            //     // $('#subscription_add_company_description').val(option.data('description'));
            //     $('#subscription_add_company_type').val(option.data('product_type'));
            //     $('#subscription_add_company_type_label').text($('#subscription_add_company_type').val());
            //     $('#subscription_add_description').val(option.data('description'));
            //     $('#subscription_add_price').val(option.data('price1_value'));
            //     $('#subscription_add_price_type').val(option.data('currency_code'));
            //     $('#subscription_add_url').val(option.data('url'));
            //     $('#subscription_add_billing_frequency').val(option.data('billing_frequency'));
            //     $('#subscription_add_billing_cycle').val(option.data('billing_cycle'));
            //     $('#subscription_add_type').val(option.data('pricing_type'));
            //     // $('#subscription_add_company_type_label').text(option.data('pricing_type'));
            //     $('#subscription_add_category_id').val(option.data('category_id')).trigger('change');
            //     $('#subscription_add_ltdval_price').val(option.data('ltdval_price'));
            //     $('#subscription_add_ltdval_cycle').val(option.data('ltdval_cycle'));
            //     $('#subscription_add_ltdval_frequency').val(option.data('ltdval_frequency'));
            //     $('#subscription_add_refund_days').val(option.data('refund_days'));

            //     // Set refund days
            //     let refund_days = parseInt(option.data('refund_days'));
            //     if (refund_days > 0) {
            //         $('#subscription_add_refund_days').val(refund_days);
            //     } else {
            //         $('#subscription_add_refund_days').val('');
            //     }

            //     app.subscription.create_type_check('#subscription_add_type');
            //     // app.ui.modal_favicon(option.data('url'), '#modal_subscription_add .modal-header img.favicon');


            //     // Load favicon from url
            //     if (option.data('favicon') != '') {
            //         $('#modal_subscription_add .modal-header img.favicon').attr('src', option.data('favicon'));
            //     } else {
            //         app.ui.modal_favicon(option.data('url'), '#modal_subscription_add .modal-header img.favicon');
            //     }

            //     app.subscription.modal_title_change('create');

            //     if (path) {
            //         // console.log(path);
            //         let filepond_options = Object.assign({}, app.global.filepond_options);
            //         filepond_options.files = [{
            //             source: btoa(path),
            //             options: {
            //                 type: 'local',
            //             },
            //         }];

            //         lib.sleep(100).then(function() {
            //             $('#subscription_add_image_path').val(path);
            //             app.subscription.img_add = lib.img.filepond('#subscription_add_image_file', filepond_options);
            //             // console.log('After load');
            //             $('#subscription_add_img_path_or_file').val(0);
            //             app.subscription.img_add.on('removefile', app.subscription.img_add_on_addfile);
            //         });

            //     } else {
            //         lib.sleep(100).then(function() {
            //             app.subscription.img_add = lib.img.filepond('#subscription_add_image_file');
            //             app.subscription.img_add.on('removefile', app.subscription.img_add_on_addfile);
            //             $('#subscription_add_img_path_or_file').val(1);
            //         });

            //         // Default values
            //         if (!option.data('billing_frequency')) {
            //             $('#subscription_add_billing_frequency').val(app.subscription.o.billing_frequency);
            //         }
            //         if (!option.data('billing_cycle')) {
            //             $('#subscription_add_billing_cycle').val(app.subscription.o.billing_cycle);
            //         }
            //         if (!option.data('currency_code')) {
            //             $('#subscription_add_price_type').val(app.subscription.o.currency_code);
            //         }
            //     }

            // });


            // $('#main_quick_add_brand_id').change(function(e) {
            //     let option = $(this).children('option:selected');
            //     let path = $(this).children('option:selected').data('path');

            //     // New fields
            //     $('#main_quick_add_description').val(option.data('description'));
            //     $('#main_quick_add_price').val(option.data('price1_value'));
            //     $('#main_quick_add_price_type').val(option.data('currency_code'));
            //     $('#main_quick_add_url').val(option.data('url'));
            //     $('#main_quick_add_billing_frequency').val(option.data('billing_frequency'));
            //     $('#main_quick_add_billing_cycle').val(option.data('billing_cycle'));
            //     $('#main_quick_add_type').val(option.data('pricing_type'));
            //     // $('#main_quick_add_refund_days').val(option.data('refund_days'));
            //     // $('#main_quick_add_company_type_label').val(option.data('pricing_type'));

            //     // Set refund days
            //     let refund_days = parseInt(option.data('refund_days'));
            //     if (refund_days > 0) {
            //         $('#main_quick_add_refund_days').val(refund_days);
            //     } else {
            //         $('#main_quick_add_refund_days').val('');
            //     }

            //     app.subscription.create_quick_type_check('#main_quick_add_type');

            //     app.subscription.modal_title_change('create_quick');

            //     if (!path) {
            //         // Default values
            //         if (!option.data('billing_frequency')) {
            //             $('#main_quick_add_billing_frequency').val(app.subscription.o.billing_frequency);
            //         }
            //         if (!option.data('billing_cycle')) {
            //             $('#main_quick_add_billing_cycle').val(app.subscription.o.billing_cycle);
            //         }
            //         if (!option.data('currency_code')) {
            //             $('#main_quick_add_price_type').val(app.subscription.o.currency_code);
            //         }
            //     }

            //     // Load favicon from url
            //     if (option.data('favicon') != '') {
            //         $('#modal_subscription_quick_add .modal-header img.favicon').attr('src', option.data('favicon'));
            //     } else {
            //         app.ui.modal_favicon(option.data('url'), '#modal_subscription_quick_add .modal-header img.favicon');
            //     }

            // });



            // $('#subscription_add_brand_id,#main_quick_add_brand_id').on('select2:open', function(e) {
            //     lib.sleep(500).then(function() {
            //         lib.do.select2_favicon_load(e.target);
            //     });

            //     $('.select2-search__field').on('keydown change', function() {
            //         lib.sleep(500).then(function() {
            //             lib.do.select2_favicon_load(e.target);
            //         });
            //     });
            // });


            $('.bars-movie').barrating({
                theme: 'bars-movie',
                showValues: false,
                allowEmpty: true,
                emptyValue: 0,
                initialRating: 0,
                // deselectable: true,
            });
            $('.bars-movie').barrating('set', 0);


            // Default values
            app.config.favicon_url = "{{ asset_ver('assets/images/favicon.ico') }}";

            app.ui.btn_expand('.btn_expand_container');
            app.ui.btn_toggle();
            app.ui.btn_ripple();

            // Reload datatable after closing attachment modal
            $('#modal_subscription_attachment').on('hidden.bs.modal', function(event) {
                $('#tbl_subscription').DataTable().ajax.reload(null, false);
            });

        });
    </script>

    {{-- Show tour --}}
    @if (!lib()->user->tour_status)
        {{-- <script>
            (function(w, d, f) {
                var a = d.getElementsByTagName('head')[0];
                var s = d.createElement('script');
                s.async = 1;
                s.src = f;
                s.setAttribute('id', 'produktlyScript');
                s.dataset.clientToken = "f26460193605aa39e3fcefa4a422191b0ccb6875868dfb411572d63ee3231f16011288a975a80eb5eeadc096974dafaca83b24f5eb5e0db9aec8f3534d39f03290620fe0b5186af15eef00374ed161eca4e8fdd6f86db5f7900a67164b2947b57b58da8e";
                a.appendChild(s);
            })(window, document, "https://public.produktly.com/js/main.js");
        </script> --}}
    @endif
    @include('layouts/xeno')

    <!-- Custom script -->
    {!! lib()->do->get_script_footer() !!}

</body>

</html>
