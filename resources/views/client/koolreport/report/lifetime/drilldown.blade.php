@php
use Illuminate\Support\Facades\DB;

// DrillDown
use koolreport\drilldown\DrillDown;
use koolreport\processes\CopyColumn;
use koolreport\processes\DateTimeFormat;
// use koolreport\widgets\google\ColumnChart;
use koolreport\chartjs\ColumnChart;

@endphp


<div class="report-content">

    @php
        // All Years = 0
        if (local('lifetime_days', 0) == 0) {
            DrillDown::create([
                'name' => 'saleDrillDown',
                'title' => '&nbsp;',
                'levels' => [
                    [
                        'title' => 'All Years',
                        'content' => function ($params, $scope) {
                            ColumnChart::create([
                                'dataSource' => lib()->kr->get_subscription_drilldown(1, $params),
                                'columns' => [
                                    'year' => [
                                        'type' => 'string',
                                        'label' => __('Year'),
                                    ],
                                    'price' => [
                                        'label' => lib()->cache->subscription_folder_name ?? __('All Folders'),
                                        'prefix' => "$",
                                    ],
                                ],
                                'clientEvents' => [
                                    'itemSelect' => 'function (params) { saleDrillDown.next({year:params.selectedRow[0]}); }',
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
                    [
                        'title' => function ($params, $scope) {
                            return 'Year ' . $params['year'];
                        },
                        'content' => function ($params, $scope) {
                            ColumnChart::create([
                                'dataSource' => lib()->kr->get_subscription_drilldown(2, $params),
                                'columns' => [
                                    'month' => [
                                        'type' => 'string',
                                        'formatValue' => function ($value) use ($params) {
                                            return date('M', mktime(0, 0, 0, $value, 1, $params['year']));
                                        },
                                    ],
                                    'price' => [
                                        'label' => lib()->cache->subscription_folder_name ?? __('All Folders'),
                                        'prefix' => "$",
                                    ],
                                ],
                                'clientEvents' => [
                                    'itemSelect' => 'function (params) { saleDrillDown.next({month:params.selectedRow[0]}); app.subscription.get_drilldown_tooltip(); }',
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
                    [
                        'title' => function ($params, $scope) {
                            return date('F', mktime(0, 0, 0, $params['month'], 10));
                        },
                        'content' => function ($params, $scope) {
                            ColumnChart::create([
                                'dataSource' => lib()->kr->get_subscription_drilldown(3, $params),
                                'columns' => [
                                    'payment_date' => [
                                        'formatValue' => function ($value, $row) {
                                            return date('jS M', strtotime($value));
                                        },
                                    ],
                                    'price' => [
                                        'label' => lib()->cache->subscription_folder_name ?? __('All Folders'),
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
                                            'label' => 'function (tooltip_item, data) { return app.subscription.f.drilldown_tooltip_label(tooltip_item, data); }',
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
        
        // 1 month
        elseif (local('lifetime_days', 0) > 0 && local('lifetime_days', 0) <= 30) {
            DrillDown::create([
                'name' => 'saleDrillDown',
                'title' => '&nbsp;',
                'levels' => [
                    [
                        'title' => 'All Time',
                        'content' => function ($params, $scope) {
                            ColumnChart::create([
                                'dataSource' => lib()->kr->get_subscription_drilldown(10, $params),
                                'columns' => [
                                    'payment_date' => [
                                        'formatValue' => function ($value, $row) {
                                            return date('jS M', strtotime($value));
                                        },
                                    ],
                                    'price' => [
                                        'label' => lib()->cache->subscription_folder_name ?? __('All Folders'),
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
                                            'label' => 'function (tooltip_item, data) { return app.subscription.f.drilldown_tooltip_label(tooltip_item, data); }',
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
        
        // 1 year
        elseif (local('lifetime_days', 0) > 0 && local('lifetime_days', 0) <= 365) {
            DrillDown::create([
                'name' => 'saleDrillDown',
                'title' => '&nbsp;',
                'levels' => [
                    [
                        'title' => 'All Years',
                        'content' => function ($params, $scope) {
                            ColumnChart::create([
                                'dataSource' => lib()->kr->get_subscription_drilldown(20, $params),
                                'columns' => [
                                    'month' => [
                                        'type' => 'string',
                                        'formatValue' => function ($value) use ($params) {
                                            return date('M', mktime(0, 0, 0, $value, 1, date('Y')));
                                        },
                                    ],
                                    'price' => [
                                        'label' => lib()->cache->subscription_folder_name ?? __('All Folders'),
                                        'prefix' => "$",
                                    ],
                                ],
                                'clientEvents' => [
                                    'itemSelect' => 'function (params) { saleDrillDown.next({month:params.selectedRow[0]}); app.subscription.get_drilldown_tooltip(); }',
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
                    [
                        'title' => function ($params, $scope) {
                            return date('F', mktime(0, 0, 0, $params['month'], 10));
                        },
                        'content' => function ($params, $scope) {
                            ColumnChart::create([
                                'dataSource' => lib()->kr->get_subscription_drilldown(21, $params),
                                'columns' => [
                                    'payment_date' => [
                                        'formatValue' => function ($value, $row) {
                                            return date('jS M', strtotime($value));
                                        },
                                    ],
                                    'price' => [
                                        'label' => lib()->cache->subscription_folder_name ?? __('All Folders'),
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
                                            'label' => 'function (tooltip_item, data) { return app.subscription.f.drilldown_tooltip_label(tooltip_item, data); }',
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
        
        // More than 1 year
        elseif (local('lifetime_days', 0) > 365) {
            DrillDown::create([
                'name' => 'saleDrillDown',
                'title' => '&nbsp;',
                'levels' => [
                    [
                        'title' => 'All Years',
                        'content' => function ($params, $scope) {
                            ColumnChart::create([
                                'dataSource' => lib()->kr->get_subscription_drilldown(30, $params),
                                'columns' => [
                                    'year' => [
                                        'type' => 'string',
                                        'label' => __('Year'),
                                    ],
                                    'price' => [
                                        'label' => lib()->cache->subscription_folder_name ?? __('All Folders'),
                                        'prefix' => "$",
                                    ],
                                ],
                                'clientEvents' => [
                                    'itemSelect' => 'function (params) { saleDrillDown.next({year:params.selectedRow[0]}); }',
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
                    [
                        'title' => function ($params, $scope) {
                            return 'Year ' . $params['year'];
                        },
                        'content' => function ($params, $scope) {
                            ColumnChart::create([
                                'dataSource' => lib()->kr->get_subscription_drilldown(31, $params),
                                'columns' => [
                                    'month' => [
                                        'type' => 'string',
                                        'formatValue' => function ($value) use ($params) {
                                            return date('M', mktime(0, 0, 0, $value, 1, $params['year']));
                                        },
                                    ],
                                    'price' => [
                                        'label' => lib()->cache->subscription_folder_name ?? __('All Folders'),
                                        'prefix' => "$",
                                    ],
                                ],
                                'clientEvents' => [
                                    'itemSelect' => 'function (params) { saleDrillDown.next({month:params.selectedRow[0]}); app.subscription.get_drilldown_tooltip(); }',
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
                    [
                        'title' => function ($params, $scope) {
                            return date('F', mktime(0, 0, 0, $params['month'], 10));
                        },
                        'content' => function ($params, $scope) {
                            ColumnChart::create([
                                'dataSource' => lib()->kr->get_subscription_drilldown(32, $params),
                                'columns' => [
                                    'payment_date' => [
                                        'formatValue' => function ($value, $row) {
                                            return date('jS M', strtotime($value));
                                            // return date('F jS, Y', strtotime($value));
                                        },
                                    ],
                                    'price' => [
                                        'label' => lib()->cache->subscription_folder_name ?? __('All Folders'),
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
                                            'label' => 'function (tooltip_item, data) { return app.subscription.f.drilldown_tooltip_label(tooltip_item, data); }',
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
        let krwidget = $('krwidget[widget-type="koolreport/chartjs/ColumnChart"]');

        if (krwidget.length && krwidget.text().search('No data') !== -1) {
            $(app.subscription.e.koolreport.drilldown).html(`
                    <img class="img-fluid p-4 w-100" src="{{ url('assets/images/placeholder/lifetime.png') }}">
                `);
        }

        app.subscription.o.subscription.drilldown = JSON.parse('@json(lib()->cache->subscription_drilldown)');

    });
</script>
