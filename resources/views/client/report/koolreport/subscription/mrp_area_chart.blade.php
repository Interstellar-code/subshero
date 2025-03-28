@php
use Illuminate\Support\Facades\DB;

// AreaChart
// use koolreport\chartjs\AreaChart;

// https://www.koolreport.com/examples/reports/morris_chart/area/
// use \koolreport\morris_chart\Area;

use koolreport\morris_chart\Area;

@endphp


@if (empty(lib()->cache->report_subscription_mrp_area_chart))
    <img class="img-fluid mx-auto d-block" src="{{ url('assets/images/placeholder/subscription.png') }}">
@else
    <div class="report-container">
        <div>
            <script>
                $(document).ready(function() {
                    app.report.o.subscription.mrp_area_chart = JSON.parse('@json(lib()->cache->report_subscription_mrp_area_chart)');
                });
            </script>

            <?php
            Area::create([
                // 'title' => 'Sale vs Cost',
                'dataSource' => lib()->cache->report_subscription_mrp_area_chart,
                'columns' => [
                    'calc_next_payment_date_formatted',
                    'price' => [
                        'label' => 'Price',
                        'type' => 'number',
                        'prefix' => "$",
                    ],
                    'ltdval_price' => [
                        'label' => 'MRR',
                        'type' => 'number',
                        'prefix' => "$",
                    ],
                ],
            
                'options' => [
                    'hoverCallback' => 'function(index, options, content, row) { return app.report.f.subscription.mrp_area_chart.tooltip_label(index, options, content, row); }',
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
@endif
