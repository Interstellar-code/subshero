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

        <!-- clipboard.js v2.0.8 -->
        <script src="{{ asset_ver('vendors/clipboard/clipboard.min.js') }}"></script>

        <!-- Bootstrap v4.5.0 -->
        <link href="{{ asset_ver('vendors/fullcalendar/main.min.css') }}" rel="stylesheet">
        <script src="{{ asset_ver('vendors/fullcalendar/main.min.js') }}"></script>

        <!-- Custom files -->
        <link href="{{ asset_ver('assets/css/main.min.css') }}" rel="stylesheet">
        <link href="{{ asset_ver('css/app.min.css') }}" rel="stylesheet">
        <link href="{{ asset_ver('assets/css/custom.min.css') }}" rel="stylesheet">

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

        <script src="{{ asset_ver('vendors/charts/apex-charts.js') }}"></script>
        <script src="{{ asset_ver('vendors/form-components/moment.js') }}"></script>
        <!-- <script src="{{ asset_ver('vendors/calendar.js') }}"></script> -->
        <script src="{{ asset_ver('vendors/form-components/datepicker.js') }}"></script>
        <script src="{{ asset_ver('vendors/js-cookie/js.cookie.js') }}"></script>
        <script src="{{ asset_ver('vendors/jquery-validation/jquery.validate.js') }}"></script>

        <!-- clipboard.js v2.0.8 -->
        <script src="{{ asset_ver('vendors/clipboard/clipboard.js') }}"></script>

        <!-- Bootstrap v4.5.0 -->
        <link href="{{ asset_ver('vendors/fullcalendar/main.css') }}" rel="stylesheet">
        <script src="{{ asset_ver('vendors/fullcalendar/main.js') }}"></script>

        <!-- Custom files -->
        <link href="{{ asset_ver('assets/css/main.css') }}" rel="stylesheet">
        <link href="{{ asset_ver('css/app.css') }}" rel="stylesheet">
        <link href="{{ asset_ver('assets/css/custom.css') }}" rel="stylesheet">

    @endproduction

    <!-- Pickr 1.7.2 -->
    <link href="{{ asset_ver('vendors/pickr/themes/classic.min.css') }}" rel="stylesheet">
    <link href="{{ asset_ver('vendors/pickr/themes/monolith.min.css') }}" rel="stylesheet">
    <link href="{{ asset_ver('vendors/pickr/themes/nano.min.css') }}" rel="stylesheet">
    <script src="{{ asset_ver('vendors/pickr/pickr.min.js') }}"></script>



    <!-- jQuery SmartWizard v5 -->
    <link href="{{ asset_ver('vendors/smartwizard/css/smart_wizard_all.css') }}" rel="stylesheet">
    <script src="{{ asset_ver('vendors/smartwizard/js/jquery.smartWizard.js') }}"></script>


    <!-- PapaParse-5.0.2 -->
    <script src="{{ asset_ver('vendors/papaparse/papaparse.js') }}"></script>

    <!-- Bootstrap Multiselect v1.1.1 -->
    <link href="{{ asset_ver('vendors/bootstrap-multiselect/css/bootstrap-multiselect.min.css') }}" rel="stylesheet">
    <script src="{{ asset_ver('vendors/bootstrap-multiselect/js/bootstrap-multiselect.min.js') }}"></script>

    <script src="{{ asset_ver('js/init.js') }}"></script>


    @include('ap-js')
    <script src="{{ asset_ver('js/custom.js') }}"></script>
    <script src="{{ asset_ver('js/app/settings.js') }}"></script>


    <script src="{{ asset_ver('js/app/settings/payment.js') }}"></script>
    <script src="{{ asset_ver('js/app/settings/dump.js') }}"></script>
    <script src="{{ asset_ver('js/app/settings/contact.js') }}"></script>
    <script src="{{ asset_ver('js/app/settings/tag.js') }}"></script>
    {{-- <script src="{{ asset_ver('js/app/settings/account.js') }}"></script> --}}
    <script src="{{ asset_ver('js/app/settings/alert.js') }}"></script>
    <script src="{{ asset_ver('js/app/settings/api.js') }}"></script>
    <script src="{{ asset_ver('js/app/settings/team.js') }}"></script>
    <script src="{{ asset_ver('js/app/settings/recovery.js') }}"></script>
    <script src="{{ asset_ver('js/app/settings/marketplace.js') }}"></script>
    <script src="{{ asset_ver('js/app/settings/billing.js') }}"></script>
    <script src="{{ asset_ver('js/app/settings/profile.js') }}"></script>

    {{-- Check if user has Pro or Team plan --}}
    @if (in_array(Auth::user()->users_plan->plan_id, array_merge(PRO_PLAN_ALL_ID, TEAM_PLAN_ALL_ID)))
        <script src="{{ asset_ver('js/app/settings/webhook.js') }}"></script>
    @endif


    @yield('head')

    <style>
        .datepicker-container {
            /* Fix datepicker in bootstrap modal */
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
            FilePondPluginImageTransform
        );

        FilePond.setOptions({
            server: {
                url: "{{ url('/') }}",
                process: './process',
                // revert: './revert',
                // restore: './restore/',
                load: '/storage/',
                // fetch: './fetch/'
            },
            // instantUpload: false,
            // allowProcess: false,
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

        })
    </script>


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
                        <div class="app-inner-layout__wrapper">
                            {{-- <div class="app-inner-layout__sidebar">
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
                            </div> --}}
                            <div class="app-inner-layout__content">
                                <div class="tab-content">
                                    <div class="container fiori-container custom_container">


                                        <div class="app-page-title">
                                            <div class="container fiori-container">
                                                <div class="page-title-wrapper">
                                                    <ul class="horizontal-nav-menu" id="menu_container">
                                                        <!-- <li class="dropdown">
                                                            <a href="{{ url('/') }}" data-slug="/" class="menu_toggle active">
                                                                <i class="nav_icon_big fa fa-angle-left"></i>
                                                                <span>@lang('Back to Dashboard')</span>
                                                            </a>
                                                        </li> -->
                                                        <li class="dropdown">
                                                            <a class="menu_toggle {{ ($slug ?? '') == 'profile' ? 'active' : null }}" href="{{ url('/settings/profile') }}" data-slug="settings/profile" onclick="app.page.switch('settings/profile');">
                                                                <i class="nav_icon_big fa fa-user"></i>
                                                                <span>@lang('Profile')</span>
                                                            </a>

                                                        </li>
                                                        <li class="dropdown">
                                                            <a href="{{ url('/settings/billing') }}" data-slug="settings/billing" class="menu_toggle {{ ($slug ?? '') == 'billing' ? 'active' : null }}" onclick="app.page.switch('settings/billing');">
                                                                <i class="nav_icon_big fa fa-file-invoice"></i>
                                                                <span>@lang('Billing')</span>
                                                            </a>

                                                            <!-- <i class="nav-icon-big typcn typcn-lightbulb"></i> -->
                                                            <!-- <a data-toggle="dropdown" data-offset="10" data-display="static" aria-expanded="false" class="">
                                                                <i class="nav_icon_big fa fa-user" style="visibility: hidden;"></i>
                                                                <span>@lang('Billing')</span>
                                                                <i class="nav-icon-pointer icon ion-ios-arrow-down"></i>
                                                            </a>
                                                            <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu">
                                                                <a href="{{ url('/settings/billing') }}" onclick="app.page.switch('settings/billing');" tabindex="0" class="dropdown-item">@lang('Plan')</a>
                                                                <a href="{{ url('/settings/payment') }}" onclick="app.page.switch('settings/payment');" tabindex="0" class="dropdown-item">@lang('Payments')</a>
                                                                <a href="{{ url('/settings/contact') }}" onclick="app.page.switch('settings/contact');" tabindex="0" class="dropdown-item">@lang('Contacts')</a>
                                                            </div> -->
                                                        </li>
                                                        <!-- <li class="dropdown">
                                                            <a href="{{ url('/settings/payment') }}" data-slug="settings/payment" class="menu_toggle {{ ($slug ?? '') == 'payment' ? 'active' : null }}" onclick="app.page.switch('settings/payment');">
                                                                <i class="nav_icon_big fa fa-user" style="visibility: hidden;"></i>
                                                                <span>@lang('Payments & More')</span>
                                                            </a>
                                                        </li> -->
                                                        <li class="dropdown">
                                                            <a href="{{ url('/settings/preference') }}" data-slug="settings/preference" class="menu_toggle {{ ($slug ?? '') == 'preference' ? 'active' : null }}" onclick="app.page.switch('settings/preference');">
                                                                <i class="nav_icon_big fa fa-user-cog"></i>
                                                                <span>@lang('Preferences')</span>
                                                            </a>
                                                        </li>
                                                        <li class="dropdown">
                                                            <a data-toggle="dropdown" data-offset="10" data-display="static" aria-expanded="false" class="">
                                                                <!-- <i class=" nav-icon-big typcn typcn-lightbulb"></i> -->
                                                                <i class="nav_icon_big fa fa-cog"></i>
                                                                <span>@lang('Settings')</span>
                                                                <i class="nav-icon-pointer icon fa fa-angle-down"></i>
                                                            </a>
                                                            <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu">
                                                                <a href="{{ url('/settings/payment') }}" onclick="app.page.switch('settings/payment');" tabindex="0" class="dropdown-item">@lang('Payments')</a>
                                                                <a href="{{ url('/settings/contact') }}" onclick="app.page.switch('settings/contact');" tabindex="0" class="dropdown-item">@lang('Contacts')</a>
                                                                <a href="{{ url('/settings/tag') }}" onclick="app.page.switch('settings/tag');" tabindex="0" class="dropdown-item">@lang('Tags')</a>
                                                                <a href="{{ url('/settings/alert') }}" onclick="app.page.switch('settings/alert');" tabindex="0" class="dropdown-item">@lang('Alert Profiles')</a>
                                                                <a href="{{ url('/settings/import') }}" onclick="app.page.switch('settings/import');" tabindex="0" class="dropdown-item">@lang('Import/Export')</a>
                                                                <a href="{{ url('/settings/api') }}" onclick="app.page.switch('settings/api');" tabindex="0" class="dropdown-item">@lang('API')</a>
                                                                <a href="{{ url('/settings/recovery') }}" onclick="app.page.switch('settings/recovery');" tabindex="0" class="dropdown-item">@lang('Backup/Restore')</a>

                                                                @if (in_array(Auth::user()->users_plan->plan_id, TEAM_PLAN_ALL_ID))
                                                                    <a href="{{ url('/settings/team') }}" onclick="app.page.switch('settings/team');" tabindex="0" class="dropdown-item">@lang('Team Accounts')</a>
                                                                @endif

                                                                <a href="{{ url('/settings/marketplace') }}" onclick="app.page.switch('settings/marketplace');" tabindex="0" class="dropdown-item">@lang('Subs Cart')</a>
                                                            </div>
                                                        </li>
                                                        <!-- <li class="dropdown">
                                                            <a href="{{ url('/settings/tag') }}" data-slug="settings/tag" class="menu_toggle {{ ($slug ?? '') == 'tag' ? 'active' : null }}" onclick="app.page.switch('settings/tag');">
                                                                <i class="nav_icon_big typcn typcn-tags" style="visibility: hidden;"></i>
                                                                <span>@lang('Tags')</span>
                                                            </a>
                                                        </li>
                                                        <li class="dropdown">
                                                            <a href="{{ url('/settings/import') }}" data-slug="settings/import" class="menu_toggle {{ ($slug ?? '') == 'import' ? 'active' : null }}" onclick="app.page.switch('settings/import');">
                                                                <i class="nav_icon_big typcn typcn-tags" style="visibility: hidden;"></i>
                                                                <span>@lang('Import/Export')</span>
                                                            </a>
                                                        </li> -->
                                                    </ul>
                                                    <div class="page-title-actions">
                                                        <ul class="horizontal-nav-menu">
                                                            <li class="dropdown">
                                                                <a href="{{ url('/') }}" data-slug="/" class="menu_toggle active">
                                                                    <i class="nav_icon_big fa fa-angle-left"></i>
                                                                    <span>@lang('Back to Dashboard')</span>
                                                                </a>
                                                            </li>
                                                        </ul>
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
    <div class="app-drawer-overlay d-none animated fadeIn"></div>
    <!-- <script src="{{ asset_ver('assets/js/main.js') }}"></script> -->



    <div id="HTML_Static" style="display: none;" hidden>
        <div id="loading_default" class="body-block-example-1" style="cursor: default;">
            <img src="{{ asset('assets/images/loader.gif') }}">
        </div>
    </div>


    @include('client/settings/modal')
    @include('client/settings/contact/modal')
    @include('client/settings/tag/modal')
    @include('client/settings/payment/modal')
    @include('client/settings/alert/modal')
    @include('client/settings/api/token/modal')
    @include('client/settings/team/modal')
    @include('client/settings/recovery/modal')
    @include('client/settings/profile/modal')



    {{-- Check if user has Pro or Team plan --}}
    @if (in_array(Auth::user()->users_plan->plan_id, array_merge(PRO_PLAN_ALL_ID, TEAM_PLAN_ALL_ID)))
        @include('client/settings/webhook/modal')
    @endif


    @stack('modal')




    {{-- Templates --}}
    @include('client/settings/recovery/template')
    @include('client/settings/profile/template')

    @stack('template')



    <script>
        $(document).ready(() => {
            $('#modal_add .select2_init_tags').select2({
                tags: true,
                theme: 'bootstrap',
                dropdownParent: $('#modal_add'),
                placeholder: {
                    id: '',
                    text: "@lang('None Selected')",
                },
            });
            $('#modal_add .select2_init_multi').select2({
                tags: true,
                theme: 'bootstrap',
                dropdownParent: $('#modal_add'),
            });
            $('#modal_quick_add .select2_init_tags').select2({
                tags: true,
                theme: 'bootstrap',
                dropdownParent: $('#modal_quick_add'),
                placeholder: {
                    id: '',
                    text: "@lang('None Selected')",
                },
            });



            // Datepicker

            $('[data-toggle="datepicker"]').datepicker();
            $('[data-toggle="datepicker-year"]').datepicker({
                startView: 2
            });

            $('[data-toggle="datepicker-month"]').datepicker({
                startView: 1
            });

            $('[data-toggle="datepicker-inline"]').datepicker({
                inline: true
            });

            $('[data-toggle="datepicker-icon"]').datepicker({
                trigger: '.datepicker-trigger'
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
                trigger: '.datepicker-trigger-btn'
            });

        });
    </script>
    @include('layouts/xeno')

    <!-- Custom script -->
    {!! lib()->do->get_script_footer() !!}

</body>

</html>
