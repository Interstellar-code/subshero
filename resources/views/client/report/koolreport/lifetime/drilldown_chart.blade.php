@php
use Illuminate\Support\Facades\DB;

// DrillDown
use koolreport\drilldown\DrillDown;
use koolreport\processes\CopyColumn;
use koolreport\processes\DateTimeFormat;
// use koolreport\widgets\google\ColumnChart;
use koolreport\chartjs\ColumnChart;

@endphp


@if (empty(lib()->cache->report_lifetime_drilldown_all_chart) && empty(lib()->cache->report_lifetime_drilldown_year_chart) && empty(lib()->cache->report_lifetime_drilldown_month_chart))
    <img class="img-fluid mx-auto d-block" src="{{ url('assets/images/placeholder/lifetime.png') }}">
@else
    <div class="report-content">

        @php
            // All years view
            if (lib()->cache->report_lifetime_level == 'all') {
                DrillDown::create([
                    'name' => 'saleDrillDown',
                    'title' => '&nbsp;',
                    'levels' => [
                        [
                            'title' => 'All Years',
                            'content' => function ($params, $scope) {
                                ColumnChart::create([
                                    'dataSource' => lib()->cache->report_lifetime_drilldown_all_chart,
                                    'columns' => [
                                        'year',
                                        'price' => [
                                            'label' => lib()->cache->report_lifetime_drilldown_folder_name ?? __('Lifetime'),
                                            'prefix' => "$",
                                        ],
                                    ],
                                    'clientEvents' => [
                                        'itemSelect' => "function (params) { app.report.load_lifetime_drilldown_chart('year', params.selectedRow[0]); }",
                                    ],
                                    'colorScheme' => ['#004547'],
                                    'options' => [
                                        'legend' => [
                                            'alignment' => 'center',
                                            'position' => 'bottom',
                                        ],
                                    ],
                                ]);
                            },
                        ],
                    ],
            
                    'options' => [
                        'legend' => [
                            'position' => 'top',
                        ],
                        'plugins' => [
                            'legend' => [
                                'position' => 'top',
                            ],
                        ],
                    ],
                ]);
            }
            
            // Single year view
            if (lib()->cache->report_lifetime_level == 'year') {
                DrillDown::create([
                    'name' => 'saleDrillDown',
                    'title' => '&nbsp;',
                    'levels' => [
                        [
                            'title' => function ($params, $scope) {
                                return 'Year ' . lib()->cache->report_lifetime_drilldown_selected_year;
                            },
                            'content' => function ($params, $scope) {
                                ColumnChart::create([
                                    'dataSource' => lib()->cache->report_lifetime_drilldown_year_chart,
                                    'columns' => [
                                        'month',
                                        'price' => [
                                            'label' => lib()->cache->report_lifetime_drilldown_folder_name ?? __('Lifetime'),
                                            'prefix' => "$",
                                        ],
                                    ],
                                    'clientEvents' => [
                                        'itemSelect' => "function (params) { app.report.load_lifetime_drilldown_chart('month', '" . lib()->cache->report_lifetime_drilldown_selected_year . "', params.selectedRow[0]); }",
                                    ],
                                    'colorScheme' => ['#004547'],
                                    'options' => [
                                        'legend' => [
                                            'alignment' => 'center',
                                            'position' => 'bottom',
                                        ],
                                    ],
                                ]);
                            },
                        ],
                    ],
            
                    'options' => [
                        'legend' => [
                            'position' => 'top',
                        ],
                        'plugins' => [
                            'legend' => [
                                'position' => 'top',
                            ],
                        ],
                    ],
                ]);
            }
            
            // Single month view
            if (lib()->cache->report_lifetime_level == 'month') {
                DrillDown::create([
                    'name' => 'saleDrillDown',
                    'title' => '&nbsp;',
                    'levels' => [
                        [
                            'title' => function ($params, $scope) {
                                return date('F', mktime(0, 0, 0, lib()->cache->report_lifetime_drilldown_selected_month, 10));
                            },
                            'content' => function ($params, $scope) {
                                ColumnChart::create([
                                    'dataSource' => lib()->cache->report_lifetime_drilldown_month_chart,
                                    'columns' => [
                                        'calc_payment_date_formatted',
                                        'price' => [
                                            'label' => lib()->cache->report_lifetime_drilldown_folder_name ?? __('Lifetime'),
                                            'prefix' => "$",
                                        ],
                                    ],
                                    'colorScheme' => ['#004547'],
                                    'options' => [
                                        'legend' => [
                                            'alignment' => 'center',
                                            'position' => 'bottom',
                                        ],
                                        'tooltips' => [
                                            'callbacks' => [
                                                'label' => 'function (tooltip_item, data) { return app.report.f.lifetime.drilldown.tooltip_label(tooltip_item, data); }',
                                            ],
                                        ],
                                    ],
                                ]);
                            },
                        ],
                    ],
            
                    'options' => [
                        'legend' => [
                            'position' => 'top',
                        ],
                        'plugins' => [
                            'legend' => [
                                'position' => 'top',
                            ],
                        ],
                    ],
                ]);
            }
            
        @endphp

    </div>

    <script>
        $(document).ready(function() {
            app.report.o.lifetime.drilldown = JSON.parse('@json(lib()->cache->report_lifetime_drilldown_month_chart)');
        });
    </script>
@endif
