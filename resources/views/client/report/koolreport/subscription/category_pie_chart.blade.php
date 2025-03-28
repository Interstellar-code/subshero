@php
use koolreport\widgets\google\PieChart;
@endphp


@if (empty(lib()->cache->report_subscription_category_pie_chart))
    <img class="img-fluid mx-auto d-block" src="{{ url('assets/images/placeholder/subscription.png') }}">
@else
    <div class="report-content">

        @php
            PieChart::create([
                // 'title' => 'Sale Of Category',
                'dataSource' => lib()->cache->report_subscription_category_pie_chart,
                'columns' => [
                    'product_category_name',
                    'price' => [
                        'type' => 'number',
                        'prefix' => "$",
                    ],
                ],
                'options' => [
                    'width' => '10%',
                    'height' => '10%',
                ],
                'options' => [
                    'legend' => 'top',
                ],
            ]);
        @endphp

    </div>
@endif
