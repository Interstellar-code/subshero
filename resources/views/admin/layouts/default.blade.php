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

        <!-- Bootstrap v4.5.0 -->
        <link href="{{ asset_ver('vendors/fullcalendar/main.min.css') }}" rel="stylesheet">
        <script src="{{ asset_ver('vendors/fullcalendar/main.min.js') }}"></script>

        <!-- DataTables 1.10.21 -->
        <link href="{{ asset_ver('vendors/datatables/jquery.dataTables.min.css') }}" rel="stylesheet">
        <script src="{{ asset_ver('vendors/datatables/jquery.dataTables.min.js') }}"></script>
        <link href="{{ asset_ver('vendors/datatables/bootstrap4/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
        <script src="{{ asset_ver('vendors/datatables/bootstrap4/dataTables.bootstrap4.min.js') }}"></script>
        <script src="{{ asset_ver('vendors/datatables/dataTables.buttons.min.js') }}"></script>

        <!-- clipboard.js v2.0.8 -->
        <script src="{{ asset_ver('vendors/clipboard/clipboard.min.js') }}"></script>

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

        <!-- DataTables 1.10.21 -->
        <link href="{{ asset_ver('vendors/datatables/jquery.dataTables.css') }}" rel="stylesheet">
        <script src="{{ asset_ver('vendors/datatables/jquery.dataTables.js') }}"></script>
        <link href="{{ asset_ver('vendors/datatables/bootstrap4/dataTables.bootstrap4.css') }}" rel="stylesheet">
        <script src="{{ asset_ver('vendors/datatables/bootstrap4/dataTables.bootstrap4.js') }}"></script>
        <script src="{{ asset_ver('vendors/datatables/dataTables.buttons.js') }}"></script>

        <!-- clipboard.js v2.0.8 -->
        <script src="{{ asset_ver('vendors/clipboard/clipboard.js') }}"></script>

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
    {{-- <script src="{{ asset_ver('js/custom.js?v=') . Str::random(9) }}"></script> --}}
    <script src="{{ asset_ver('js/custom.js') }}"></script>
    <script src="{{ asset_ver('js/admin/product.js') }}"></script>
    <script src="{{ asset_ver('js/admin/product/product_related_entity.js') }}"></script>
    <script src="{{ asset_ver('js/admin/product/product_images_import.js') }}"></script>
    <script src="{{ asset_ver('js/admin/customer.js') }}"></script>
    <script src="{{ asset_ver('js/admin/product/adapt.js') }}"></script>
    
    <!-- Logo Search Feature CSS -->
    <link href="{{ asset_ver('css/admin/logo-search.css') }}" rel="stylesheet">

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
            FilePondPluginImageTransform
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
                    <a href="{{ url('/') }}" class="logo-src"></a>
                </div>

                <div class="app-header-right">
                    <div class="header-btn-lg pr-0">
                        <div class="widget-content p-0">
                            <div class="widget-content-wrapper">
                                <div class="widget-content-left">
                                    <div class="btn-group">
                                        <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="p-0 btn avatar_container">
                                            <img width="42" class="rounded-circle img_border_color" src="{{ img_url(lib()->user->me->image, User_Default_Img) }}" alt="{{ lib()->user->me->name }}">
                                            <p class="avatar_text">
                                                <span class="name">{{ lib()->user->me->name }}</span>
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
                                                                    <img width="42" class="rounded-circle" src="{{ img_url(lib()->user->me->image, User_Default_Img) }}" alt="{{ lib()->user->me->name }}">
                                                                </div>
                                                                <div class="widget-content-left">
                                                                    <div class="widget-heading">{{ lib()->user->me->name }}</div>
                                                                    <!-- <div class="widget-subheading opacity-8">A short profile description</div> -->
                                                                </div>
                                                                <div class="widget-content-right mr-2">
                                                                    <a href="{{ url('logout') }}" class="btn-pill btn-shadow btn-shine btn btn-focus">
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
                                                    </ul>
                                                    <div class="page-title-actions mb-2 mt-2 pt-1 pb-1">
                                                        <a href="{{ route('admin/product/adapt') }}" onclick="app.page.switch('admin/product/adapt');" class="btn-shadow btn btn-wide btn-primary btn-lg mr-3">
                                                            <span class="btn-icon-wrapper pr-1 opacity-7">
                                                                <i class="fa fa-user"></i>
                                                            </span>
                                                            @lang("User's")
                                                        </a>
                                                        <a href="{{ route('admin/product') }}" onclick="app.page.switch('admin/product');" class="btn-shadow btn btn-wide btn-primary btn-lg mr-3">
                                                            <span class="btn-icon-wrapper pr-1 opacity-7">
                                                                <i class="fa fa-building"></i>
                                                            </span>
                                                            @lang('Product')
                                                        </a>
                                                        <a href="{{ route('admin/customer') }}" onclick="app.page.switch('admin/customer');" class="btn-shadow btn btn-wide btn-primary btn-lg mr-3">
                                                            <span class="btn-icon-wrapper pr-1 opacity-7">
                                                                <i class="fa fa-user-friends"></i>
                                                            </span>
                                                            @lang('Customers')
                                                        </a>
                                                        <a href="{{ route('admin/settings') }}" class="btn-shadow btn btn-wide btn-primary btn-lg">
                                                            <span class="btn-icon-wrapper pr-1 opacity-7">
                                                                <i class="fa fa-cog"></i>
                                                            </span>
                                                            @lang('Settings')
                                                        </a>
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
                        <button class="btn-icon btn-icon-only btn-pill btn btn-outline-primary btn-sm mt-1" data-target="#modal_folder_add" data-toggle="modal">
                            <i class="fa fa-folder-plus"></i>
                            &nbsp;
                            @lang('Add')
                        </button>
                    </div>
                </div>
                <div class="drawer-section pl-0 pr-0 pt-1 hp-100">
                    <div class="folder_container hp-100 overflow-auto" id="folder_container">
                        <ul class="todo-list-wrapper list-group list-group-flush">
                            <li class="list-group-item item_active pt-0 pb-1 pl-1 pr-0">
                                <div class="widget-content p-0 pl-3 pr-3" onclick="app.page.switch('subscription');">
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
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="app-drawer-overlay d-none animated fadeIn"></div>
    <!-- Disabled for too much problem (bootstrap.bundle) -->
    <!-- <script src="{{ asset_ver('assets/js/main.js') }}"></script> -->

    @yield('footer')

    @include('admin/product/modal')
    @include('admin/customer/modal')
    @include('admin/product/entity/modal')
    @include('admin/product/adapt/modal')
    @stack('modal')

    @stack('template')



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

    <script>
        $(document).ready(() => {

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


            // Default values
            app.config.favicon_url = "{{ asset_ver('assets/images/favicon.ico') }}";


        });

        $('#subscription_add_brand_id').change(function(e) {
            let path = $(this).children('option:selected').data('path');
            app.subscription.img_add.destroy();
            app.subscription.img_add = null;
            $('#subscription_add_image_path').val('');

            if (path) {
                // console.log(path);
                let filepond_options = Object.assign({}, app.global.filepond_options);
                filepond_options.files = [{
                    source: btoa(path),
                    options: {
                        type: 'local'
                    }
                }];

                lib.sleep(100).then(function() {
                    $('#subscription_add_image_path').val(path);
                    app.subscription.img_add = lib.img.filepond('#subscription_add_image_file', filepond_options);
                    // console.log('After load');
                    $('#subscription_add_img_path_or_file').val(0);
                    app.subscription.img_add.on('removefile', app.subscription.img_add_on_addfile);
                });

            } else {
                lib.sleep(100).then(function() {
                    app.subscription.img_add = lib.img.filepond('#subscription_add_image_file');
                    app.subscription.img_add.on('removefile', app.subscription.img_add_on_addfile);
                    $('#subscription_add_img_path_or_file').val(1);
                });
            }
        });
    </script>


    <!-- Custom script -->
    {!! lib()->do->get_script_footer() !!}

</body>

</html>
