@php
use Illuminate\Support\Facades\DB;

// AreaChart
use koolreport\chartjs\AreaChart;

@endphp


@if (empty(lib()->cache->report_subscription_google_area_chart))
    <img class="img-fluid mx-auto d-block" src="{{ url('assets/images/placeholder/subscription.png') }}">
@else
    <div class="report-container">
        <div>
            <script>
                $(document).ready(function() {
                    app.report.o.subscription.google_area_chart = JSON.parse('@json(lib()->cache->report_subscription_google_area_chart)');
                });
            </script>

            <?php
            AreaChart::create([
                // 'title' => 'Sale vs Cost',
                'title' => null,
                'dataSource' => lib()->cache->report_subscription_google_area_chart ?? [],
                'columns' => [
                    'calc_next_payment_date_formatted',
                    'price' => [
                        'label' => lib()->cache->subscription_folder_name ?? __('Subscription'),
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
                            'label' => 'function (tooltip_item, data) { return app.report.f.subscription.google_area_chart.tooltip_label(tooltip_item, data); }',
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
@endif
