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

    <!-- Disable tap highlight on IE -->
    <meta name="msapplication-tap-highlight" content="no">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" href="{{ asset_ver('assets/images/favicon.ico') }}" type="image/ico" sizes="48x48">

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

        <!-- clipboard.js v2.0.8 -->
        <script src="{{ asset_ver('vendors/clipboard/clipboard.js') }}"></script>

        <!-- Bootstrap v4.5.0 -->
        <link href="{{ asset_ver('vendors/fullcalendar/main.css') }}" rel="stylesheet">
        <script src="{{ asset_ver('vendors/fullcalendar/main.js') }}"></script>

        <link href="{{ asset_ver('assets/css/main.css') }}" rel="stylesheet">
        <link href="{{ asset_ver('css/app.css') }}" rel="stylesheet">
        <link href="{{ asset_ver('assets/css/custom.css') }}" rel="stylesheet">

    @endproduction

    <!-- Pickr 1.7.2 -->
    <link href="{{ asset_ver('vendors/pickr/themes/classic.min.css') }}" rel="stylesheet">
    <link href="{{ asset_ver('vendors/pickr/themes/monolith.min.css') }}" rel="stylesheet">
    <link href="{{ asset_ver('vendors/pickr/themes/nano.min.css') }}" rel="stylesheet">
    <script src="{{ asset_ver('vendors/pickr/pickr.min.js') }}"></script>

    <!-- DataTables 1.10.21 -->
    <link href="{{ asset_ver('vendors/datatables/jquery.dataTables.css') }}" rel="stylesheet">
    <script src="{{ asset_ver('vendors/datatables/jquery.dataTables.js') }}"></script>
    <link href="{{ asset_ver('vendors/datatables/bootstrap4/dataTables.bootstrap4.css') }}" rel="stylesheet">
    <script src="{{ asset_ver('vendors/datatables/bootstrap4/dataTables.bootstrap4.js') }}"></script>

    <!-- jQuery Time Ago 1.6.7 -->
    <script src="{{ asset_ver('vendors/jquery-timeago/jquery.timeago.js') }}"></script>

    <!-- Moment.js v2.29.1 -->
    <script src="{{ asset_ver('vendors/moment/moment-with-locales.js') }}"></script>

    <!-- Bootstrap Multiselect v1.1.1 -->
    <link href="{{ asset_ver('vendors/bootstrap-multiselect/css/bootstrap-multiselect.min.css') }}" rel="stylesheet">
    <script src="{{ asset_ver('vendors/bootstrap-multiselect/js/bootstrap-multiselect.min.js') }}"></script>

    <script src="{{ asset_ver('js/init.js') }}"></script>


    @include('ap-js')
    <script src="{{ asset_ver('js/custom.js') }}"></script>
    <script src="{{ asset_ver('js/admin/settings.js') }}"></script>
    <script src="{{ asset_ver('js/admin/settings/smtp.js') }}"></script>
    <script src="{{ asset_ver('js/admin/settings/template.js') }}"></script>
    <script src="{{ asset_ver('js/admin/settings/update.js') }}"></script>
    <script src="{{ asset_ver('js/admin/settings/script.js') }}"></script>
    <script src="{{ asset_ver('js/admin/settings/webhook.js') }}"></script>
    <script src="{{ asset_ver('js/admin/settings/misc.js') }}"></script>
    <script src="{{ asset_ver('js/admin/settings/cron.js') }}"></script>

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
                url: "{{ route('/') }}",
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
                <div class="app-header__logo">
                    <a href="{{ route('/') }}" class="logo-src"></a>
                </div>

                <div class="app-header-right">
                    <div class="header-btn-lg pr-0">
                        <div class="widget-content p-0">
                            <div class="widget-content-wrapper">
                                <div class="widget-content-left">
                                    <div class="btn-group">
                                        <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="p-0 btn avatar_container">
                                            <img width="42" class="rounded-circle profile_img img_border_color" src="{{ img_url(lib()->user->me->image, User_Default_Img) }}" alt="">
                                            <p class="avatar_text">
                                                <span class="name user_profile_name">{{ lib()->user->me->name }}</span>
                                                <br>
                                                <span class="cost">Saved $247</span>
                                            </p>
                                            <i class="fa fa-angle-down ml-2 opacity-8"></i>
                                        </a>
                                        <div tabindex="-1" role="menu" aria-hidden="true" class="rm-pointers dropdown-menu-lg dropdown-menu dropdown-menu-right">
                                            <div class="dropdown-menu-header">
                                                <div class="dropdown-menu-header-inner bg-info">
                                                    <div class="menu-header-image opacity-2" style="background-image: url('{{ url('assets/images/dropdown-header/city1.jpg') }}');"></div>
                                                    <div class="menu-header-content text-left">
                                                        <div class="widget-content p-0">
                                                            <div class="widget-content-wrapper">
                                                                <div class="widget-content-left mr-3">
                                                                    <img width="42" class="rounded-circle profile_img" src="{{ img_url(lib()->user->me->image, User_Default_Img) }}" alt="">
                                                                </div>
                                                                <div class="widget-content-left">
                                                                    <div class="widget-heading user_profile_name">{{ lib()->user->me->name }}</div>
                                                                    <!-- <div class="widget-subheading opacity-8">A short profile description</div> -->
                                                                </div>
                                                                <div class="widget-content-right mr-2">
                                                                    <a href="{{ route('logout') }}" class="btn-pill btn-shadow btn-shine btn btn-focus">
                                                                        @lang('Logout')
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="scroll-area-xs" style="height: 150px;">
                                                <div class="scrollbar-container ps">
                                                    {{-- <ul class="nav flex-column">
                                                        <li class="nav-item">
                                                            <a href="{{ url('/settings') }}" class="nav-link">@lang('Settings')</a>
                                                        </li>
                                                    </ul> --}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
                                                        <li class="dropdown">
                                                            <a class="menu_toggle {{ ($slug ?? '') == 'admin/settings/email/smtp' ? 'active' : null }}" href="{{ route('admin/settings/email/smtp') }}" data-slug="admin/settings/email/smtp" onclick="app.page.switch('admin/settings/email/smtp');">
                                                                <i class="nav_icon_big fa fa-at"></i>
                                                                <span>@lang('Email SMTP')</span>
                                                            </a>
                                                        </li>
                                                        {{-- <li class="dropdown">
                                                            <a href="{{ route('admin/settings/payment') }}" data-slug="admin/settings/billing" class="menu_toggle {{ ($slug ?? '') == 'billing' ? 'active' : null }}" onclick="app.page.switch('admin/settings/billing');">
                                                                <i class="nav_icon_big fa fa-dollar-sign"></i>
                                                                <span>@lang('Payment Portals')</span>
                                                            </a>
                                                        </li> --}}
                                                        <li class="dropdown">
                                                            <a href="{{ route('admin/settings/email/template') }}" data-slug="admin/settings/email/template" class="menu_toggle {{ ($slug ?? '') == 'admin/settings/email/template' ? 'active' : null }}" onclick="app.page.switch('admin/settings/email/template');">
                                                                <i class="nav_icon_big fa fa-mail-bulk"></i>
                                                                <span>@lang('Email Templates')</span>
                                                            </a>
                                                        </li>
                                                        <li class="dropdown">
                                                            <a href="{{ route('admin/settings/update') }}" data-slug="admin/settings/update" class="menu_toggle {{ ($slug ?? '') == 'admin/settings/update' ? 'active' : null }}" onclick="app.page.switch('admin/settings/update');">
                                                                <i class="nav_icon_big fa fa-cloud-download-alt"></i>
                                                                <span>@lang('Update')</span>
                                                            </a>
                                                        </li>
                                                        <li class="dropdown">
                                                            <a href="{{ route('admin/settings/script') }}" data-slug="admin/settings/script" class="menu_toggle {{ ($slug ?? '') == 'admin/settings/script' ? 'active' : null }}" onclick="app.page.switch('admin/settings/script');">
                                                                <i class="nav_icon_big fa fa-code"></i>
                                                                <span>@lang('Script')</span>
                                                            </a>
                                                        </li>
                                                        <li class="dropdown">
                                                            <a href="{{ route('admin/settings/webhook') }}" data-slug="admin/settings/webhook" class="menu_toggle {{ ($slug ?? '') == 'admin/settings/webhook' ? 'active' : null }}" onclick="app.page.switch('admin/settings/webhook');">
                                                                <i class="nav_icon_big fa fa-globe"></i>
                                                                <span>@lang('Webhook')</span>
                                                            </a>
                                                        </li>
                                                        <li class="dropdown">
                                                            <a href="{{ route('admin/settings/misc') }}" data-slug="admin/settings/misc" class="menu_toggle {{ ($slug ?? '') == 'admin/settings/misc' ? 'active' : null }}" onclick="app.page.switch('admin/settings/misc');">
                                                                <i class="nav_icon_big fa fa-info"></i>
                                                                <span>@lang('Miscellaneous')</span>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                    <div class="page-title-actions">
                                                        <ul class="horizontal-nav-menu">
                                                            <li class="dropdown">
                                                                <a href="{{ url('/admin') }}" data-slug="/admin" class="menu_toggle active">
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

    @include('admin/settings/email/template/modal')
    @include('admin/settings/email/modal')
    @yield('modal')
    @yield('footer')

    <div id="settings_webhook_log_preview_modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Webhook log</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body custom_scrollbar vh-60">
                    <pre class="m-0 overflow-hidden"></pre>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    
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

    <!-- Custom script -->
    {!! lib()->do->get_script_footer() !!}

</body>

</html>
