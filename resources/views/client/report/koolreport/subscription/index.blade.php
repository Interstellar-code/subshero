@extends(request()->ajax() ? 'client/layouts/ajax' : 'client/layouts/default')

@section('head')
@endsection

@section('content')
    {{-- <style>
        #menu_container>li:nth-child(1) {
            display: none;
        }

    </style> --}}

    <div class="main-card mb-3">

        <div class="row subscription_chart_container">
            <div class="col-xl-2 col-lg-3 col-md-4">
                <div class="accordion report_accordion" id="report_accordion">


                    <div class="card">
                        <div class="card-header py-1" id="report_accordion_subscription_heading" data-toggle="collapse" data-target="#report_accordion_subscription_collapse" aria-expanded="false" aria-controls="report_accordion_subscription_collapse">
                            <h2 class="mb-0">
                                <button class="btn btn-link btn-block text-left" type="button">
                                    @lang('Types')
                                </button>
                            </h2>
                        </div>

                        <div id="report_accordion_subscription_collapse" class="collapse" aria-labelledby="report_accordion_subscription_heading" data-parent="#report_accordion">
                            <div class="card-body pb-0">
                                <div class="filter_container">

                                    <p class="filter_check">
                                        <label class="filter_label">
                                            <input type="checkbox" name="type[]" value="subscription" class="filter_value">
                                            <span class="filter_name">@lang('Subscription')</span>
                                        </label>
                                    </p>

                                    <p class="filter_check">
                                        <label class="filter_label">
                                            <input type="checkbox" name="type[]" value="lifetime" class="filter_value">
                                            <span class="filter_name">@lang('Lifetime')</span>
                                        </label>
                                    </p>

                                    <p class="filter_check">
                                        <label class="filter_label">
                                            <input type="checkbox" name="type[]" value="trial" class="filter_value">
                                            <span class="filter_name">@lang('Trial')</span>
                                        </label>
                                    </p>

                                    {{-- @foreach (lib()->subscription->get_by_user() as $val)
                                        <p class="filter_check">
                                            <label class="filter_label">
                                                <input type="checkbox" name="subscription_id[]" value="{{ $val->id }}" class="filter_value">
                                                <span class="filter_name">{{ $val->product_name }}</span>
                                            </label>
                                        </p>
                                    @endforeach --}}
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="card my-3">
                        <div class="card-header py-1" id="report_accordion_folder_heading" data-toggle="collapse" data-target="#report_accordion_folder_collapse" aria-expanded="true" aria-controls="report_accordion_folder_collapse">
                            <h2 class="mb-0">
                                <button class="btn btn-link btn-block text-left" type="button">
                                    @lang('Folders')
                                </button>
                            </h2>
                        </div>

                        <div id="report_accordion_folder_collapse" class="collapse show" aria-labelledby="report_accordion_folder_heading" data-parent="#report_accordion">
                            <div class="card-body pb-0">
                                <div class="filter_container">
                                    @foreach (lib()->folder->get_by_user() as $val)
                                        <p class="filter_check">
                                            <label class="filter_label">
                                                <input type="checkbox" name="folder_id[]" value="{{ $val->id }}" class="filter_value">
                                                <span class="filter_name">{{ $val->name }}</span>
                                            </label>
                                        </p>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="card my-3">
                        <div class="card-header py-1" id="report_accordion_tag_heading" data-toggle="collapse" data-target="#report_accordion_tag_collapse" aria-expanded="false" aria-controls="report_accordion_tag_collapse">
                            <h2 class="mb-0">
                                <button class="btn btn-link btn-block text-left collapsed" type="button">
                                    @lang('Tags')
                                </button>
                            </h2>
                        </div>
                        <div id="report_accordion_tag_collapse" class="collapse" aria-labelledby="report_accordion_tag_heading" data-parent="#report_accordion">
                            <div class="card-body pb-0">
                                <div class="filter_container">
                                    @foreach (lib()->user->tags ?? [] as $val)
                                        <p class="filter_check">
                                            <label class="filter_label">
                                                <input type="checkbox" name="folder_id[]" value="{{ $val->id }}" class="filter_value">
                                                <span class="filter_name">{{ $val->name }}</span>
                                            </label>
                                        </p>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="card my-3">
                        <div class="card-header py-1" id="report_accordion_payment_method_heading" data-toggle="collapse" data-target="#report_accordion_payment_method_collapse" aria-expanded="false" aria-controls="report_accordion_payment_method_collapse">
                            <h2 class="mb-0">
                                <button class="btn btn-link btn-block text-left collapsed" type="button">
                                    @lang('Payment Methods')
                                </button>
                            </h2>
                        </div>
                        <div id="report_accordion_payment_method_collapse" class="collapse" aria-labelledby="report_accordion_payment_method_heading" data-parent="#report_accordion">
                            <div class="card-body pb-0">
                                <div class="filter_container">
                                    @foreach (lib()->user->payment_methods ?? [] as $val)
                                        <p class="filter_check">
                                            <label class="filter_label">
                                                <input type="checkbox" name="folder_id[]" value="{{ $val->id }}" class="filter_value">
                                                <span class="filter_name">{{ $val->name }}</span>
                                            </label>
                                        </p>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
            <div class="col-xl-10 col-lg-9 col-md-8">
                <div class="block pb-3">
                    {{-- <div class="row">
                        <div class="col-sm-6">
                            <div class="text_container">
                                <p class="subs_top">
                                    @lang('Total Subs'):
                                    <span class="text-dark">{{ local('subscription_total_count', 0) }}</span>
                                </p>
                                <p class="subs_bottom">
                                    @lang('Active Subs'):
                                    <span class="text-dark">{{ local('subscription_total_count', 0) }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="text_container text-right">
                                <p class="subs_top">
                                    @lang('Monthly'):
                                    <span class="text-dark">${{ local('subscription_monthly_price', 0) }}</span>
                                </p>
                                <p class="subs_bottom">
                                    @lang('Total'):
                                    <span class="text-dark">${{ local('subscription_total_price', 0) }}</span>
                                </p>
                            </div>
                        </div>
                    </div> --}}

                    <div class="row mt-2">
                        <div class="col-md-6 col-lg-3">
                            <select id="report_period" class="form-control pull-right select_box_white" onchange="app.report.set_subscription_period(this.value);" data-type="1" data-toggle="tooltip" data-placement="right" title="@lang('Select Years or Month from drop-down to your expenses')">
                                <option value="this_month" {{ local('report_period') == 'this_month' ? 'selected' : null }}>@lang('Current Month')</option>
                                <option value="this_year" {{ local('report_period') == 'this_year' ? 'selected' : null }}>@lang('Current Year')</option>
                                <option value="last_year" {{ local('report_period') == 'last_year' ? 'selected' : null }}>@lang('Last Year')</option>
                                <option value="{{ date('Y', strtotime('-2 years')) }}" {{ local('report_period') == date('Y', strtotime('-2 years')) ? 'selected' : null }}>@lang(date('Y', strtotime('-2 years')))</option>
                                <option value="{{ date('Y', strtotime('-3 years')) }}" {{ local('report_period') == date('Y', strtotime('-3 years')) ? 'selected' : null }}>@lang(date('Y', strtotime('-3 years')))</option>
                            </select>
                        </div>
                    </div>

                    <div id="report_koolreport_subscription_mrp_area_chart">
                        @include('client/report/koolreport/subscription/mrp_area_chart')
                    </div>
                </div>
            </div>

        </div>




















    </div>
@endsection
