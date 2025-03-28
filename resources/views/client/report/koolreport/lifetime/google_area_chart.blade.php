@php
use Illuminate\Support\Facades\DB;

// AreaChart
use koolreport\chartjs\AreaChart;

@endphp


@if (empty(lib()->cache->report_lifetime_mrp_area_chart))
    <img class="img-fluid mx-auto d-block" src="{{ url('assets/images/placeholder/lifetime.png') }}">
@else
    <div class="report-container">
        <div>
            <script>
                $(document).ready(function() {
                    app.lifetime.o.lifetime.area_chart = JSON.parse('@json(lib()->cache->report_lifetime_google_area_chart)');
                });
            </script>

            <?php
            AreaChart::create([
                // 'title' => 'Sale vs Cost',
                'title' => null,
                'dataSource' => lib()->cache->report_lifetime_google_area_chart ?? [],
                'columns' => [
                    'calc_next_payment_date_formatted',
                    'price' => [
                        'label' => lib()->cache->lifetime_folder_name ?? __('All Folders'),
                        'type' => 'number',
                        'prefix' => "$",
                    ],
                    // 'product_name',
                ],
                'backgroundOpacity' => 0.8,
                'colorScheme' => ['#f9c916'],
            ]);
            ?>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            let krwidget = $('krwidget[widget-type="koolreport/chartjs/AreaChart"]');

            if (krwidget.length && krwidget.text().search('No data') !== -1) {
                $(app.lifetime.e.koolreport.area_chart).html(`
                    <img class="img-fluid p-4 w-100" src="{{ url('assets/images/placeholder/lifetime.png') }}">
                `);
            }
        });
    </script>
@endif
