@extends(request()->ajax() ? 'admin/layouts/ajax' : 'admin/layouts/default')

@section('head')
    <style>
        #tbl_product tbody tr {
            height: 73px;
        }

    </style>
@endsection

@section('content')
    <div class="main-card mb-3">
        {{-- <div class="row">
            <div class="col-md-6">
                <div class="card widget-chart widget-chart2 text-left p-0">
                    <div class="widget-chat-wrapper-outer">
                        <div class="widget-chart-content widget-chart-content-lg pb-0">
                            <div class="widget-chart-flex">
                                <div class="widget-title opacity-5 text-muted text-uppercase">New Accounts since 2018</div>
                            </div>
                            <div class="widget-numbers">
                                <div class="widget-chart-flex">
                                    <div>
                                        <span class="opacity-10 text-success pr-2">
                                            <i class="fa fa-angle-up"></i>
                                        </span>
                                        <span>78</span>
                                        <small class="opacity-5 pl-1">%</small>
                                    </div>
                                    <div class="widget-title ml-2 font-size-lg font-weight-normal text-muted">
                                        <span class="text-success pl-2">+14</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="widget-chart-wrapper he-auto opacity-10 m-0">
                            <div id="subscription_chart_1"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card widget-chart widget-chart2 text-left p-0">
                    <div class="widget-chat-wrapper-outer">
                        <div class="widget-chart-content widget-chart-content-lg pb-0">
                            <div class="widget-chart-flex">
                                <div class="widget-title opacity-5 text-muted text-uppercase">Last Year Total Sales</div>
                            </div>
                            <div class="widget-numbers">
                                <div class="widget-chart-flex">
                                    <div>
                                        <small class="opacity-3 pr-1">$</small>
                                        <span>629</span>
                                        <span class="text-primary pl-3">
                                            <i class="fa fa-angle-down"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="widget-chart-wrapper he-auto opacity-10 m-0">
                            <div id="subscription_chart_2"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <br> --}}
        <div class="table-responsive card p-3">
            <table id="tbl_product" class="align-middle mb-0 table table-borderless table-striped table-hover text-center mb-4">
            </table>
        </div>


    </div>




    <template id="tpl_product_table_buttons">
        @include('admin/product/import_menu')
        <button type="button" class="btn-shadow btn btn-wide btn-primary mr-3" data-toggle="modal" data-target="#product_add_modal">@lang('Add')</button>
    </template>






    <script>
        $(document).ready(() => {

            app.product.init();



            var table = $('#tbl_product').DataTable({
                processing: true,
                serverSide: true,
                // stateSave: true,
                ajax: {
                    type: 'POST',
                    url: "{{ route('admin/product/datatable/index') }}",
                    data: {
                        _token: app.config.token,
                    },
                    beforeSend: function() {
                        // Abort previous ajax request
                        if (table && table.hasOwnProperty('settings')) {
                            table.settings()[0].jqXHR.abort();
                        }
                    }
                },
                order: [
                    [0, 'asc'],
                ],
                aLengthMenu: [
                    [5, 10, 25, 50, 100],
                    [5, 10, 25, 50, 100],
                ],
                iDisplayLength: 10,
                // responsive: true,

                // Customize table row column
                fnCreatedRow: function(nRow, aData, iDataIndex) {
                    $(nRow).attr('data-id', aData.id);
                },
                columns: [{
                        data: 'id',
                        visible: false,
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'column_brand',
                        title: lang('Brand'),
                        orderable: false,
                    },
                    {
                        data: 'product_name',
                        title: lang('Product Name'),
                    },
                    {
                        data: 'product_type_name',
                        title: lang('Product Type'),
                    },
                    {
                        data: 'column_pricing_type',
                        title: lang('Pricing Type'),
                    },
                    {
                        data: 'product_category_name',
                        title: lang('Category'),
                    },
                    {
                        data: 'product_platform_name',
                        title: lang('Platform'),
                    },
                    {
                        data: 'description',
                        title: lang('Description'),
                        width: '100px',
                        class: "single_line mw_100px",
                    },
                    {
                        data: 'column_status',
                        title: lang('Status'),
                    },
                    {
                        data: 'column_action',
                        title: lang('Actions'),
                        width: '100px',
                        orderable: false,
                        searchable: false,
                    },
                ],
                initComplete: function() {
                    $('#tbl_product_filter').prepend($('#tpl_product_table_buttons').html());
                },
            });


















            // $('#tbl_product').DataTable({
            //     // ... skipped ...
            //     // dom: 'l<"toolbar">frtip',
            //     initComplete: function() {
            //         $('#tbl_product_filter').prepend(`
        //             <button type="button" class="btn-shadow btn btn-wide btn-primary mr-3" data-toggle="modal" data-target="#product_add_modal">@lang('Add')</button>
        //             <button type="button" class="btn-shadow btn btn-wide btn-primary mr-3" data-toggle="modal" data-target="#product_add_modal">@lang('Import')</button>
        //         `);
            //     },
            // });



            // Load product table
            // $('#tbl_product').DataTable({
            //     // ... skipped ...
            //     // dom: 'l<"toolbar">frtip',
            //     initComplete: function() {
            //         $('#tbl_product_filter').prepend(`
        //         <a href="{{ route('admin/product/import') }}" onclick="app.page.switch('admin/product/import');" class="btn-shadow btn btn-wide btn-primary mr-3">@lang('Import')</a>
        //         <button type="button" class="btn-shadow btn btn-wide btn-primary mr-3" data-toggle="modal" data-target="#product_add_modal">@lang('Add')</button>
        // `);
            //     },
            // });




            // Disable to use dynamic load to sort or search records
            // var table = $('#tbl_subscription').DataTable({
            //     searching: false,
            //     paging: false,
            //     info: false,

            //     // Disable sorting
            //     bSort: false,

            //     // Disable sorting for specific columns
            //     // orderable: false,
            //     // targets: -1,
            //     // aoColumnDefs: [{
            //     //     bSortable: false,
            //     //     aTargets: [ 1, 2, 3, 4, 5]
            //     // }]
            // });






            // // 1st graph
            // var sparklineData = [47, 45, 54, 38, 56, 24, 65, 31, 37, 39, 62, 51, 35, 41, 35, 27, 93, 53, 61, 27, 54, 43, 19, 46];
            // var dashSpark3 = {
            //     chart: {
            //         type: 'area',
            //         height: 152,
            //         sparkline: {
            //             enabled: true
            //         },
            //     },
            //     colors: ['#3ac47d'],
            //     stroke: {
            //         width: 5,
            //         curve: 'smooth'
            //     },

            //     markers: {
            //         size: 0
            //     },
            //     tooltip: {
            //         fixed: {
            //             enabled: false
            //         },
            //         x: {
            //             show: false
            //         },
            //         y: {
            //             title: {
            //                 formatter: function(seriesName) {
            //                     return '';
            //                 }
            //             }
            //         },
            //         marker: {
            //             show: false
            //         }
            //     },
            //     fill: {
            //         type: 'gradient',
            //         gradient: {
            //             shadeIntensity: 1,
            //             opacityFrom: 0.7,
            //             opacityTo: 0.9,
            //             stops: [0, 90, 100]
            //         }
            //     },
            //     series: [{
            //         data: randomizeArray(sparklineData)
            //     }],
            //     yaxis: {
            //         min: 0
            //     },
            // };
            // // new ApexCharts(document.querySelector("#subscription_chart_1"), dashSpark3).render();


            // // 2nd graph
            // var dashSpark1 = {
            //     chart: {
            //         type: 'area',
            //         height: 152,
            //         sparkline: {
            //             enabled: true
            //         },
            //     },
            //     colors: ["#3f6ad8"],
            //     stroke: {
            //         width: 5,
            //         curve: 'smooth',
            //     },

            //     markers: {
            //         size: 0
            //     },
            //     tooltip: {
            //         fixed: {
            //             enabled: false
            //         },
            //         x: {
            //             show: false
            //         },
            //         y: {
            //             title: {
            //                 formatter: function(seriesName) {
            //                     return '';
            //                 }
            //             }
            //         },
            //         marker: {
            //             show: false
            //         }
            //     },
            //     fill: {
            //         type: 'gradient',
            //         gradient: {
            //             shadeIntensity: 1,
            //             opacityFrom: 0.7,
            //             opacityTo: 0.9,
            //             stops: [0, 90, 100]
            //         },
            //     },
            //     series: [{
            //         data: randomizeArray(sparklineData)
            //     }],
            //     yaxis: {
            //         min: 0
            //     },
            // };
            // // new ApexCharts(document.querySelector("#subscription_chart_2"), dashSpark1).render();
        });
    </script>
@endsection



@push('template')
    <template id="tpl_admin_product_table_btn">
        <a href="{{ route('admin/product/import') }}" class="btn-shadow btn btn-wide btn-primary mr-3">@lang('Import')</a>
        <button type="button" class="btn-shadow btn btn-wide btn-primary mr-3" data-toggle="modal" data-target="#product_add_modal">@lang('Add')</button>
    </template>
@endpush
