<div class="border p-3 mb-5 chart_card">

    <div class="row top_filter_container d-none">
        <div class="col-md-6 col-lg-3">
            {{-- <select id="report_period" class="form-control pull-right select_box_white" onchange="app.report.set_period(this.value);" data-type="1" data-toggle="tooltip" data-placement="right" title="@lang('Select Years or Month from drop-down to your expenses')">
                <option value="this_month" {{ local('report_period') == 'this_month' ? 'selected' : null }}>@lang('Current Month')</option>
                <option value="this_year" {{ local('report_period') == 'this_year' ? 'selected' : null }}>@lang('Current Year')</option>
                <option value="last_year" {{ local('report_period') == 'last_year' ? 'selected' : null }}>@lang('Last Year')</option>
                <option value="{{ date('Y', strtotime('-2 years')) }}" {{ local('report_period') == date('Y', strtotime('-2 years')) ? 'selected' : null }}>@lang(date('Y', strtotime('-2 years')))</option>
                <option value="{{ date('Y', strtotime('-3 years')) }}" {{ local('report_period') == date('Y', strtotime('-3 years')) ? 'selected' : null }}>@lang(date('Y', strtotime('-3 years')))</option>
            </select> --}}
        </div>
        {{-- <div class="col-md-6 col-lg-3">
            <select id="report_lifetime_order" class="form-control pull-right select_box_white" onchange="app.report.set_lifetime_order(this.value);">
                <option selected="" disabled="" value="" style="display: none;">@lang('Sort By')</option>
            </select>
        </div> --}}
    </div>

    <div class="row top_data_container">
        <div class="col-sm-6">
            <div class="text_container">
                <p class="subs_top">
                    @lang('Average Cost')
                    ({{ lib()->cache->report_lifetime_average_currency_code }})
                </p>
                <div class="row bottom_container">
                    <div class="col-md-4">
                        <p class="subs_bottom">
                            @lang('Year'):
                            <span class="text-dark">
                                {{ lib()->cache->report_lifetime_average_currency_symbol }}{{ lib()->cache->report_lifetime_average_yearly_cost }}
                            </span>
                            {{-- <span class="text-dark">${{ local('report_lifetime_average_yearly_cost', 0) }}</span> --}}
                        </p>
                    </div>
                    <div class="col-md-4">
                        <p class="subs_bottom">
                            @lang('Month'):
                            <span class="text-dark">
                                {{ lib()->cache->report_lifetime_average_currency_symbol }}{{ lib()->cache->report_lifetime_average_monthly_cost }}
                            </span>
                            {{-- <span class="text-dark">${{ local('report_lifetime_average_monthly_cost', 0) }}</span> --}}
                        </p>
                    </div>
                    <div class="col-md-4">
                        <p class="subs_bottom">
                            @lang('Week'):
                            <span class="text-dark">
                                {{ lib()->cache->report_lifetime_average_currency_symbol }}{{ lib()->cache->report_lifetime_average_weekly_cost }}
                            </span>
                            {{-- <span class="text-dark">${{ local('report_lifetime_average_weekly_cost', 0) }}</span> --}}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="text_container">
                <p class="subs_top">
                    @lang('Lifetime Summary')
                </p>
                <div class="row bottom_container">
                    <div class="col-md-4">
                        <p class="subs_bottom">
                            @lang('Total'):
                            <span class="text-dark">{{ lib()->cache->report_lifetime_summary_total_count }}</span>
                            {{-- <span class="text-dark">{{ local('report_lifetime_summary_total_count', 0) }}</span> --}}
                        </p>
                    </div>
                    <div class="col-md-4">
                        <p class="subs_bottom">
                            @lang('Active'):
                            <span class="text-dark">{{ lib()->cache->report_lifetime_summary_active_count }}</span>
                            {{-- <span class="text-dark">{{ local('report_lifetime_summary_active_count', 0) }}</span> --}}
                        </p>
                    </div>
                    <div class="col-md-4">
                        <p class="subs_bottom">
                            @lang('Canceled'):
                            <span class="text-dark">{{ lib()->cache->report_lifetime_summary_canceled_count }}</span>
                            {{-- <span class="text-dark">{{ local('report_lifetime_summary_trial_count', 0) }}</span> --}}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="report_koolreport_lifetime_mrp_area_chart">
        @include('client/report/koolreport/lifetime/mrp_area_chart')
    </div>
</div>




<div class="border p-3 mb-5 chart_card">
    <div class="row">
        <div class="col-md-5">
            <div id="report_koolreport_lifetime_category_pie_chart">
                @include('client/report/koolreport/lifetime/category_pie_chart')
            </div>
        </div>
        <div class="col-md-7">
            <div id="report_koolreport_lifetime_drilldown_chart">
                {{-- @include('client/report/koolreport/lifetime/google_area_chart') --}}
                @include('client/report/koolreport/lifetime/drilldown_chart')
            </div>
        </div>
    </div>
</div>
