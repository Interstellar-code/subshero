@php
use koolreport\widgets\google\PieChart;

$category_amount = [['category' => 'Books', 'sale' => 32000, 'cost' => 20000, 'profit' => 12000], ['category' => 'Accessories', 'sale' => 43000, 'cost' => 36000, 'profit' => 7000], ['category' => 'Phones', 'sale' => 54000, 'cost' => 39000, 'profit' => 15000], ['category' => 'Movies', 'sale' => 23000, 'cost' => 18000, 'profit' => 5000], ['category' => 'Others', 'sale' => 12000, 'cost' => 6000, 'profit' => 6000]];
@endphp


@if (empty(lib()->cache->report_lifetime_mrp_area_chart))
    <img class="img-fluid mx-auto d-block" src="{{ url('assets/images/placeholder/lifetime.png') }}">
@else
    <div class="report-content">

        @php
            PieChart::create([
                // 'title' => 'Sale Of Category',
                'dataSource' => lib()->cache->report_lifetime_category_pie_chart,
                'columns' => [
                    // 'category',
                    'product_category_name',
                    'price' => [
                        'type' => 'number',
                        'prefix' => "$",
                    ],
                ],
                'options' => [
                    'legend' => 'top',
                ],
            ]);
        @endphp

    </div>
@endif
