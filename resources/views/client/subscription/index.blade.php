@extends(request()->ajax() ? 'client/layouts/ajax' : 'client/layouts/default')

@php
    use Illuminate\Support\Facades\DB;
    
    // AreaChart
    use koolreport\chartjs\AreaChart;
    
    // DrillDown
    use koolreport\drilldown\DrillDown;
    use koolreport\processes\CopyColumn;
    use koolreport\processes\DateTimeFormat;
    use koolreport\widgets\google\ColumnChart;
    
@endphp

@section('head')
@endsection

@section('content')
    <style>
        .subscription_page_elements {
            display: block;
        }
    </style>

    <div class="main-card mb-3">

        <div class="row subscription_chart_container">
            <div class="col-lg-6">
                <div class="block pb-3">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="text_container">
                                <p class="subs_top">
                                    @lang('Total Subs'):
                                    <span id="subscription_total_count" class="text-dark">0</span>
                                </p>
                                <p class="subs_bottom">
                                    @lang('Active Subs'):
                                    <span id="subscription_active_count" class="text-dark">0</span>
                                </p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="text_container text-right">
                                <p class="subs_top">
                                    @lang('Monthly'):
                                    <span id="subscription_monthly_price" class="text-dark">$0</span>
                                </p>
                                <p class="subs_bottom">
                                    @lang('Total'):
                                    <span id="subscription_total_price" class="text-dark">$0</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-md-6 col-lg-3 ml-auto">
                            <select id="subscription_chart_days" class="form-control pull-right select_box_white" onchange="app.subscription.koolreport_chart_load(1, this.value);" data-type="1" data-toggle="tooltip" data-placement="right" title="@lang('Select Years or Month from drop-down to your expenses')">
                                <option value="0" {{ local('subscription_days') == 0 ? 'selected' : null }}>@lang('All years')</option>
                                <option value="730" {{ local('subscription_days') == 730 ? 'selected' : null }}>@lang('2 years')</option>
                                <option value="365" {{ local('subscription_days') == 365 ? 'selected' : null }}>@lang('1 year')</option>
                                <option value="180" {{ local('subscription_days') == 180 ? 'selected' : null }}>@lang('6 months')</option>
                                <option value="90" {{ local('subscription_days') == 90 ? 'selected' : null }}>@lang('Quarter')</option>
                                <option value="30" {{ local('subscription_days') == 30 ? 'selected' : null }}>@lang('1 month')</option>
                            </select>
                        </div>
                    </div>

                    <div id="koolreport_subscription_area_chart">
                        {{-- @include('client/koolreport/subscription/area_chart') --}}
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="block">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="text_container">
                                <p class="ltd_top">
                                    @lang('Total LTD'):
                                    <span id="lifetime_total_count" class="text-dark">0</span>
                                </p>
                                <p class="ltd_bottom">
                                    @lang('Active LTD'):
                                    <span id="lifetime_active_count" class="text-dark">0</span>
                                </p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="text_container text-right">
                                <p class="ltd_top">
                                    @lang('This Year'):
                                    <span id="lifetime_this_year_price" class="text-dark">$0</span>
                                </p>
                                <p class="ltd_bottom">
                                    @lang('Total'):
                                    <span id="lifetime_total_price" class="text-dark">$0</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6 col-lg-3 ml-auto">
                            <select id="lifetime_chart_days" class="form-control select_box_white" onchange="app.subscription.koolreport_chart_load(3, this.value);" data-type="3" data-toggle="tooltip" data-placement="right" title="@lang('Select Years or Month from drop-down to your expenses')">>
                                <option value="0" {{ local('dashboard_lifetime_kr_period') == 0 ? 'selected' : null }}>@lang('All years')</option>
                                <option value="last_2_year" {{ local('dashboard_lifetime_kr_period') == 'last_2_year' ? 'selected' : null }}>@lang('2 years')</option>
                                <option value="last_1_year" {{ local('dashboard_lifetime_kr_period') == 'last_1_year' ? 'selected' : null }}>@lang('1 year')</option>
                                <option value="last_6_month" {{ local('dashboard_lifetime_kr_period') == 'last_6_month' ? 'selected' : null }}>@lang('6 months')</option>
                                <option value="last_3_month" {{ local('dashboard_lifetime_kr_period') == 'last_3_month' ? 'selected' : null }}>@lang('Quarter')</option>
                                <option value="last_1_month" {{ local('dashboard_lifetime_kr_period') == 'last_1_month' ? 'selected' : null }}>@lang('1 month')</option>
                            </select>
                        </div>
                    </div>

                    <div id="koolreport_subscription_drilldown">
                        {{-- @include('client/koolreport/subscription/drilldown') --}}
                    </div>
                </div>
            </div>
        </div>

        <br>
        <div class="table-responsive card p-3">
            <table id="tbl_subscription" class="align-middle mb-0 table table-borderless table-striped table-hover text-center mb-4"></table>
        </div>

        {{-- <div class="col-md-4">
            <div class="card folder_container">
                <ul class="todo-list-wrapper list-group list-group-flush">
                    <li class="list-group-item item_active">
                        <div class="widget-content p-2">
                            <div class="widget-content-wrapper">
                                <div class="widget-content-left mr-4">
                                    <i class="icon fa fa-folder-open fa-2x"></i>
                                </div>
                                <div class="widget-content-left">
                                    <div class="folder_text">@lang('Folders')</div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
                <ul class="todo-list-wrapper list-group list-group-flush">
                    @foreach (lib()->folder->get_by_user() as $val)
                        <li class="list-group-item">
                            <div class="widget-content p-2" onclick="alert('{{ $val->id }}');">
                                <div class="widget-content-wrapper">
                                    <div class="widget-content-left mr-4">
                                        <i class="icon fa fa-folder-open fa-2x"></i>
                                    </div>
                                    <div class="widget-content-left">
                                        <div class="folder_text">{{ $val->name }}</div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div> --}}




    </div>

    <script>
        $(document).ready(() => {

            app.subscription.o.table = $('#tbl_subscription').DataTable({
                processing: true,
                serverSide: true,
                // stateSave: true,
                ajax: {
                    type: 'POST',
                    url: "{{ route('app/datatable/subscription') }}",
                    data: {
                        _token: app.config.token,
                    },
                },
                order: [
                    [0, 'desc'],
                ],
                aLengthMenu: [
                    [5, 10, 25, 50, 100],
                    [5, 10, 25, 50, 100],
                ],
                pageLength: 10,
                // iDisplayLength: 5,
                iDisplayLength: "{{ local('subscription_datatable_page_length', 10) }}",

                // Customize table row column
                fnCreatedRow: function(nRow, aData, iDataIndex) {
                    $(nRow).attr('data-id', aData.id);
                    $(nRow).attr('data-product_name', aData.product_name);
                },

                // Set callback on draw event
                drawCallback: function(settings) {
                    app.ui.btn_expand('.btn_expand_container');
                },
                columns: [{
                        data: 'id',
                        visible: false,
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'type',
                        visible: false,
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'brandname',
                        visible: false,
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'created_at',
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
                        data: 'column_product_name',
                        title: lang('Product Name'),
                    },
                    {
                        data: 'column_type',
                        title: lang('Type'),
                    },
                    {
                        data: 'column_due_date',
                        title: lang('Due Date'),
                    },
                    {
                        data: 'price',
                        title: lang('Price'),
                    },
                    {
                        data: 'payment_method_name',
                        title: lang('Payment Mode'),
                    },
                    {
                        data: 'column_action',
                        title: lang('Actions'),
                        width: '50px',
                        orderable: false,
                        searchable: false,
                    },
                ],
            });

            $('#tbl_subscription').on('length.dt', function(e, settings, len) {
                app.subscription.set_datatable_page_length(len);
            });

            app.subscription.o.table.on('draw', function() {
                app.load.tooltip();
            });
        });

        // Load charts on page load
        @if (request()->ajax())
            app.subscription.init();
        @endif
    </script>
@endsection
