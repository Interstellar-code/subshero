@php
use Illuminate\Support\Facades\DB;

// AreaChart
// use koolreport\chartjs\AreaChart;

// https://www.koolreport.com/examples/reports/morris_chart/area/
use \koolreport\morris_chart\Area;

@endphp


<div class="report-container">
    <div>
        <script>
            $(document).ready(function() {
                app.report.o.subscription.area_chart_1 = JSON.parse('@json(lib()->cache->report_subscription_area_chart_1)');
            });
        </script>

        <?php
        Area::create([
            'title' => null,
            'dataSource' => lib()->cache->report_subscription_area_chart_1 ?? [],
            'columns' => [
                'calc_next_payment_date_formatted',
                'price' => [
                    'label' => lib()->cache->subscription_folder_name ?? __('All Folders'),
                    'type' => 'number',
                    'prefix' => "$",
                ],
                // 'product_name',
            ],
            'backgroundOpacity' => 0.8,
            'colorScheme' => ['#f9c916'],
        
            'options' => [
                'tooltips' => [
                    'callbacks' => [
                        'label' => 'function (tooltip_item, data) { return app.report.f.subscription.area_chart_1.tooltip_label(tooltip_item, data); }',
                    ],
                ],
                'legend' => [
                    'position' => 'bottom',
                ],
            ],
        ]);
        ?>
    </div>
</div>

<script>
    $(document).ready(function() {
        let krwidget = $('krwidget[widget-type="koolreport/chartjs/AreaChart"]');

        if (krwidget.length && krwidget.text().search('No data') !== -1) {
            $(app.subscription.e.koolreport.area_chart).html(`
                    <img class="img-fluid p-4 w-100" src="{{ url('assets/images/placeholder/subscription.png') }}">
                `);
        }
    });
</script>
