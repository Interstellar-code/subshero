@extends(request()->ajax() ? 'client/layouts/ajax' : 'client/layouts/default')

@section('head')
@endsection

@section('content')
    <style>
        .subscription_page_elements {
            display: block;
        }


    </style>

    <div class="main-card mb-3">


        <div class="container fiori-container body-tabs-shadow my_tab">
            {{-- <ul class="body-tabs body-tabs-layout tabs-animated body-tabs-animated nav row my_tab_head mb-4">
                <li class="nav-item col-md-6">
                    <a role="tab" class="nav-link active" id="tab-0" data-toggle="tab" href="#tab_monthly_view">
                        <div class="my_tab_nav w-100">
                            <i class="fa fa-calendar-alt fa-2x mr-4"></i>&nbsp;
                            <h5 class="m-0 mr-4">@lang('Monthly View')</h5>
                        </div>
                    </a>
                </li>
                <li class="nav-item col-md-6">
                    <a role="tab" class="nav-link" id="tab-1" data-toggle="tab" href="#tab_list_view">
                        <div class="my_tab_nav w-100">
                            <i class="fa fa-list fa-2x mr-4"></i>&nbsp;
                            <h5 class="m-0 mr-4">@lang('List View')</h5>
                        </div>
                    </a>
                </li>
            </ul> --}}

            <div class="tab-content">
                <div class="tab-pane tabs-animation fade show active" id="tab_monthly_view" role="tabpanel">
                    <div id="calendar"></div>
                </div>
                <div class="tab-pane tabs-animation fade" id="tab_list_view" role="tabpanel">
                    <div class="widget-chart-wrapper widget-chart-wrapper-lg opacity-10 m-0">
                        <div style="height: 274px;">
                            <div id="chart-combined-tab-3"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>








    </div>

    <script>
        $(document).ready(function() {

            // Reset filters
            app.calendar.o.filter.folder_id = null;
            const events = JSON.parse(`{!! json_encode($events) !!}`);
            app.calendar.o.instance = new FullCalendar.Calendar($('#calendar')[0], {
                // schedulerLicenseKey: '0738486686-fcs-1574578951',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,listWeek,listDay' // timeGridDay
                },
                views: {
                    listDay: {
                        buttonText: 'list day'
                    },
                    listWeek: {
                        buttonText: 'list week',
                    },
                    listMonth: {
                        buttonText: 'list month'
                    }
                },
                themeSystem: 'bootstrap',
                bootstrapFontAwesome: true,
                // defaultDate: "{{ date('Y-m-d') }}",
                navLinks: true, // can click day/week names to navigate views
                editable: true,
                // eventLimit: true, // allow "more" link when too many events
                events: events,

                droppable: false,
                // disableDragging: true,
                editable: false,
                eventStartEditable: false,

                eventClassNames: function(arg) {
                    // Check filter
                    if (app.calendar.o.filter.folder_id) {
                        if (arg.event.extendedProps.folder_id == app.calendar.o.filter.folder_id) {
                            return;
                        } else {
                            return ['d-none'];
                        }
                    } else {
                        return;
                    }
                },

                eventClick: function(info) {
                    // alert('Coordinates: ' + info.jsEvent.pageX + ',' + info.jsEvent.pageY);
                    // alert('View: ' + info.view.type);

                    // // change the border color just for fun
                    // info.el.style.borderColor = 'red';

                    if (info.event.extendedProps.subscription_id && info.event.extendedProps.kind == 'subscription') {
                        app.subscription.edit(info.event.extendedProps.subscription_id);
                    }
                    if (info.event.extendedProps.kind == 'notification') {
                        app.calendar.notification_edit({
                            'id': info.event.extendedProps.notification_id,
                            'type': info.event.extendedProps.notification_type,
                        });
                    }
                },

                eventContent: function(info) {
                    return { html: info.event.title };
                },

            });

            app.calendar.o.instance.render();


            // Report
            var optionsCommerce = {
                chart: {
                    height: 274,
                    type: 'bar',
                    stacked: true,
                    toolbar: {
                        show: false,
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: true,
                    },

                },
                stroke: {
                    width: 1,
                    colors: ['#fff']
                },
                series: [{
                    name: 'Marine',
                    data: [44, 55, 41, 37, 22, 43]
                }, {
                    name: 'Striking',
                    data: [53, 32, 33, 52, 13, 43]
                }, {
                    name: 'Tank',
                    data: [12, 17, 11, 9, 15, 11]
                }, {
                    name: 'Bucket',
                    data: [9, 7, 5, 8, 6, 9]
                }, {
                    name: 'Reborn',
                    data: [25, 12, 19, 32, 25, 24]
                }],
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return val + "K";
                        }
                    }
                },
                fill: {
                    opacity: 1

                },

                legend: {
                    position: 'top',
                    horizontalAlign: 'center',
                }
            };
            setTimeout(function() {
                new ApexCharts(document.querySelector("#chart-combined-tab-3"), optionsCommerce).render();
            }, 500);


            // Filter
            @if (!empty(local('subscription_folder_id')))
                app.subscription.get_by_folder({{ local('subscription_folder_id') }});
            @endif

        });


        function btn_arrow_switch(ctl) {
            $('.btn_down_arrow').removeClass('active');
            $(ctl).addClass('active');
        }
    </script>
@endsection
