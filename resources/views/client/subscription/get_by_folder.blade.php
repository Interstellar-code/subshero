@extends(request()->ajax() ? 'client/layouts/ajax' : 'client/layouts/default')

@section('head')
@endsection

@section('content')
    <div class="main-card mb-3">
        <div class="row">
            <div class="col-md-6">
                <div class="card widget-chart widget-chart2 text-left p-0">
                    <div class="widget-chat-wrapper-outer">
                        {{-- <div class="widget-chart-content widget-chart-content-lg pb-0">
                            <div class="widget-chart-flex">
                                <div class="widget-title opacity-5 text-muted text-uppercase">New Accounts since 2018</div>
                            </div>
                            <div class="widget-numbers">
                                <div class="widget-chart-flex">
                                    <div>
                                        <span class="opacity-10 text-success pr-2">
                                            <i class="fa fa-angle-up"></i>
                                        </span>
                                        <span>78</span>
                                        <small class="opacity-5 pl-1">%</small>
                                    </div>
                                    <div class="widget-title ml-2 font-size-lg font-weight-normal text-muted">
                                        <span class="text-success pl-2">+14</span>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                        <div class="widget-chart-wrapper he-auto opacity-10 m-0 pl-2 pt-2">
                            <div id="subscription_chart_subs"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card widget-chart widget-chart2 text-left p-0">
                    <div class="widget-chat-wrapper-outer">
                        {{-- <div class="widget-chart-content widget-chart-content-lg pb-0">
                            <div class="widget-chart-flex">
                                <div class="widget-title opacity-5 text-muted text-uppercase">Last Year Total Sales</div>
                            </div>
                            <div class="widget-numbers">
                                <div class="widget-chart-flex">
                                    <div>
                                        <small class="opacity-3 pr-1">$</small>
                                        <span>629</span>
                                        <span class="text-primary pl-3">
                                            <i class="fa fa-angle-down"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                        <div class="widget-chart-wrapper he-auto opacity-10 m-0 pl-2 pt-2">
                            <div id="subscription_chart_ltd"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <br>
        <div class="table-responsive card p-3">
            <table id="tbl_subscription" class="align-middle mb-0 table table-borderless table-striped table-hover text-center mb-4">
                <thead>
                    <tr>
                        <th class="d-none">@lang('Created at')</th>
                        <th class="p-3">@lang('Brand')</th>
                        <th class="p-3">@lang('Product Name')</th>
                        <th class="p-3">@lang('Type')</th>
                        <th class="p-3">@lang('Purchase Date')</th>
                        <th class="p-3">@lang('Price')</th>
                        <th class="p-3 text-right">@lang('Actions')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach (lib()->subscription->get_by_folder($folder_id ?? null) ?? [] as $val)
                        <tr data-id="{{ $val->id }}">
                            <!-- <td>{{ $loop->iteration }}</td> -->
                            <td class="d-none">{{ $val->created_at }}</td>
                            <td>
                                <div class="row align-items-center">
                                    <div class="col-2">

                                        {{-- Draft, Active or Cancel status check --}}
                                        @if ($val->status == 0)
                                            <span class="badge badge-warning subscription_status_vertical">@lang('Draft')</span>
                                        @elseif ($val->status == 1)

                                            {{-- Recurring check --}}
                                            @if ($val->recurring == 1)
                                                <span class="badge subscription_status_vertical badge_recurring">@lang('Recur')</span>
                                            @else
                                                <span class="badge badge-info subscription_status_vertical">@lang('Once')</span>
                                            @endif

                                        @elseif ($val->status == 2)
                                            <span class="badge badge-danger subscription_status_vertical">@lang('Canceled')</span>

                                        @elseif ($val->status == 3)
                                            <span class="badge badge-secondary subscription_status_vertical">@lang('Refund')</span>
                                        @endif
                                    </div>
                                    <div class="col-10">

                                        {{-- Cancel status check --}}
                                        @if ($val->status == 2)
                                            <img src="{{ img_url($val->image, SUB_DImg) }}" class="!rounded-circle" style="max-height: 100px; max-width: 150px;">
                                            <br>
                                        @else
                                            <div class="img_btn_container">
                                                <img src="{{ img_url($val->image, SUB_DImg) }}" class="!rounded-circle" style="max-height: 100px; max-width: 150px;">
                                                <div class="middle">
                                                    <button class="btn btn-pill btn-sm btn_overlay primary" onclick="app.subscription.edit(this);" data-toggle="tooltip" data-placement="top" title="@lang('Edit')">
                                                        <ion-icon name="pencil-outline" size="small"></ion-icon>
                                                    </button>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                {{ $val->product_name }}
                                <br>
                                <small>{{ $val->product_type_name }}</small>
                            </td>
                            <td>
                                {{-- Subscription --}}
                                @if ($val->type == 1)
                                    {{ table('subscription.cycle_ly', $val->billing_cycle) }}
                                @else
                                    {{ table('subscription.type', $val->type) }}
                                @endif

                                @if ($val->next_payment_date)
                                    <br>
                                    <small>{{ date('d M Y', strtotime($val->next_payment_date)) }}</small>
                                @endif
                            </td>
                            <td>{{ date('d M Y', strtotime($val->payment_date)) }}</td>
                            <td>{{ $val->price_type }}&nbsp;{{ $val->price }}</td>
                            <td>
                                <div class="btn_expand_container mx-auto">

                                    @if ($val->status == 1)
                                        <button class="btn btn_collapse warning" onclick="app.subscription.refund(this);" data-toggle="tooltip" data-placement="top" title="@lang('Refund')">
                                            <span class="fa fa-hand-holding-usd"></span>
                                        </button>
                                    @endif

                                    <button class="btn btn_collapse primary" onclick="app.subscription.clone(this);" data-toggle="tooltip" data-placement="top" title="@lang('Clone')">
                                        <ion-icon name="copy-outline" size="small"></ion-icon>
                                    </button>
                                    <button class="btn btn_collapse danger" onclick="app.subscription.delete(this);" data-toggle="tooltip" data-placement="top" title="@lang('Delete')">
                                        <ion-icon name="trash-outline" size="small"></ion-icon>
                                    </button>

                                    {{-- Button display expect cancel status --}}
                                    @unless($val->status == 2)
                                        <button class="btn btn_collapse warning" onclick="app.subscription.cancel(this);" data-toggle="tooltip" data-placement="top" title="@lang('Cancel')">
                                            <ion-icon name="close-circle-outline" size="small"></ion-icon>
                                        </button>
                                    @endunless

                                    <button class="btn btn_toggle">
                                        <ion-icon name="settings-outline" size="small"></ion-icon>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
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

            // Disable to use dynamic load to sort or search records
            // var table = $('#tbl_subscription').DataTable({
            //     searching: false,
            //     paging: false,
            //     info: false,

            //     // Disable sorting
            //     bSort: false,

            //     // Disable sorting for specific columns
            //     // orderable: false,
            //     // targets: -1,
            //     // aoColumnDefs: [{
            //     //     bSortable: false,
            //     //     aTargets: [ 1, 2, 3, 4, 5]
            //     // }]
            // });




            $('#tbl_subscription').DataTable({
                drawCallback: function(settings) {
                    app.ui.btn_expand('.btn_expand_container');
                },
                "columnDefs": [{
                    "targets": [0],
                    "visible": false,
                    "searchable": false,
                }, ],
                "order": [
                    [0, "desc"]
                ],
                // "targets": 'no-sort',
                // "bSort": false,
                // "order": [],
            });




            // // 1st graph
            // var sparklineData = [47, 45, 54, 38, 56, 24, 65, 31, 37, 39, 62, 51, 35, 41, 35, 27, 93, 53, 61, 27, 54, 43, 19, 46];
            // var dashSpark3 = {
            //     chart: {
            //         type: 'area',
            //         height: 152,
            //         sparkline: {
            //             enabled: true
            //         },
            //     },
            //     colors: ['#3ac47d'],
            //     stroke: {
            //         width: 5,
            //         curve: 'smooth'
            //     },

            //     markers: {
            //         size: 0
            //     },
            //     tooltip: {
            //         fixed: {
            //             enabled: false
            //         },
            //         x: {
            //             show: false
            //         },
            //         y: {
            //             title: {
            //                 formatter: function(seriesName) {
            //                     return '';
            //                 }
            //             }
            //         },
            //         marker: {
            //             show: false
            //         }
            //     },
            //     fill: {
            //         type: 'gradient',
            //         gradient: {
            //             shadeIntensity: 1,
            //             opacityFrom: 0.7,
            //             opacityTo: 0.9,
            //             stops: [0, 90, 100]
            //         }
            //     },
            //     series: [{
            //         data: randomizeArray(sparklineData)
            //     }],
            //     yaxis: {
            //         min: 0
            //     },
            // };
            // new ApexCharts(document.querySelector("#subscription_chart_1"), dashSpark3).render();


            // // 2nd graph
            // var dashSpark1 = {
            //     chart: {
            //         type: 'area',
            //         height: 152,
            //         sparkline: {
            //             enabled: true
            //         },
            //     },
            //     colors: ["#3f6ad8"],
            //     stroke: {
            //         width: 5,
            //         curve: 'smooth',
            //     },

            //     markers: {
            //         size: 0
            //     },
            //     tooltip: {
            //         fixed: {
            //             enabled: false
            //         },
            //         x: {
            //             show: false
            //         },
            //         y: {
            //             title: {
            //                 formatter: function(seriesName) {
            //                     return '';
            //                 }
            //             }
            //         },
            //         marker: {
            //             show: false
            //         }
            //     },
            //     fill: {
            //         type: 'gradient',
            //         gradient: {
            //             shadeIntensity: 1,
            //             opacityFrom: 0.7,
            //             opacityTo: 0.9,
            //             stops: [0, 90, 100]
            //         },
            //     },
            //     series: [{
            //         data: randomizeArray(sparklineData)
            //     }],
            //     yaxis: {
            //         min: 0
            //     },
            // };
            // new ApexCharts(document.querySelector("#subscription_chart_2"), dashSpark1).render();








            var subs_chart_data = JSON.parse('@json($subs_chart)');
            var ltd_chart_data = JSON.parse('@json($ltd_chart)');




            var options = {
                series: [{
                    name: "@lang('Subscription')",
                    data: subs_chart_data.prices,
                }],
                chart: {
                    type: 'area',
                    height: 250,
                    zoom: {
                        enabled: false
                    },
                    toolbar: {
                        show: false,
                    },
                },
                colors: ['#f9c916'],
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'straight'
                },

                title: {
                    text: "@lang('Subscriptions')",
                    align: 'left'
                },
                // subtitle: {
                //     text: 'Price Movements',
                //     align: 'left'
                // },
                labels: subs_chart_data.dates,
                xaxis: {
                    type: 'month',
                },
                yaxis: {
                    opposite: true
                },
                legend: {
                    horizontalAlign: 'left'
                }
            };

            var chart = new ApexCharts(document.querySelector("#subscription_chart_subs"), options);
            chart.render();





            var options = {
                series: [{
                    name: "@lang('LTD')",
                    data: ltd_chart_data.prices,
                }],
                chart: {
                    type: 'area',
                    height: 250,
                    zoom: {
                        enabled: false
                    },
                    toolbar: {
                        show: false,
                    },
                },
                colors: ['#004547'],
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'straight'
                },

                title: {
                    text: "@lang('Lifetime')",
                    align: 'left'
                },
                // subtitle: {
                //     text: 'Price Movements',
                //     align: 'left'
                // },
                labels: ltd_chart_data.dates,
                xaxis: {
                    type: 'month',
                },
                yaxis: {
                    opposite: true
                },
                legend: {
                    horizontalAlign: 'left'
                }
            };

            var chart = new ApexCharts(document.querySelector("#subscription_chart_ltd"), options);
            chart.render();



        });
    </script>

@endsection
