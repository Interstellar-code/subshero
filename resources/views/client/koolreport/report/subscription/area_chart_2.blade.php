@php
use Illuminate\Support\Facades\DB;

// AreaChart
// use koolreport\chartjs\AreaChart;

// https://www.koolreport.com/examples/reports/morris_chart/area/
// use \koolreport\morris_chart\Area;

use koolreport\morris_chart\Area;

$time_sale = [['month' => 'January', 'sale' => 32000, 'cost' => 40000], ['month' => 'February', 'sale' => 48000, 'cost' => 39000], ['month' => 'March', 'sale' => 35000, 'cost' => 38000], ['month' => 'April', 'sale' => 40000, 'cost' => 37000], ['month' => 'May', 'sale' => 60000, 'cost' => 45000], ['month' => 'June', 'sale' => 73000, 'cost' => 47000], ['month' => 'July', 'sale' => 80000, 'cost' => 60000], ['month' => 'August', 'sale' => 78000, 'cost' => 65000], ['month' => 'September', 'sale' => 60000, 'cost' => 45000], ['month' => 'October', 'sale' => 83000, 'cost' => 71000], ['month' => 'November', 'sale' => 45000, 'cost' => 40000], ['month' => 'December', 'sale' => 39000, 'cost' => 60000]];

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
            'title' => 'Sale vs Cost',
            'dataSource' => $time_sale,
            'columns' => [
                'month',
                'sale' => [
                    'label' => 'Sale',
                    'type' => 'number',
                    'prefix' => "$",
                ],
                'cost' => [
                    'label' => 'Cost',
                    'type' => 'number',
                    'prefix' => "$",
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
