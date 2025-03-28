// Instantiate this class
$(document).ready(function () {
    app.register('report', new clsApp_Report);
});


class clsApp_Report {

    e = {
        // Elements

        container: '#folder_container',
        filter_container: '#report_filter_container',
        subscription_folder_container: '#subscription_folder_container',


        filter: {
            form: '#report_filter_form',
            period: '#report_period',
        },

        chart: {
            container: '#report_chart_container',
        },



        subscription: {
            mrp_area_chart: '#report_koolreport_subscription_mrp_area_chart',
            category_pie: '#report_koolreport_subscription_category_pie',
            all_drilldown: '#report_koolreport_subscription_all_drilldown',
        },
        lifetime: {
            // chart: '#subscription_chart_ltd',
            drilldown: '#report_koolreport_lifetime_drilldown_chart',
        },

        // koolreport: {
        //     area_chart: '#koolreport_subscription_area_chart',
        //     drilldown: '#koolreport_subscription_drilldown',
        // },

    };
    d = {
        // Default values

    };
    o = {
        // Objects

        filter: {
            period: null,
        },

        subscription: {
            // period: null,
            filter: {
                config: null,
                data: null,
                instance: null,
            },
        },
        lifetime: {
            // period: null,
            chart: {
                config: null,
                data: null,
                instance: null,
            },
            drilldown: {
                year: null,
            },
        },

        area_chart: null,
        drilldown: null,

        ajax: {
            current_request: null,
        },
    };
    c = {
        // Configuration

    };
    f = {
        // Callback function

        subscription: {
            mrp_area_chart: {
                tooltip_label: function (index, options, content, row) {
                    if (typeof app.report.o.subscription.mrp_area_chart === 'undefined') {
                        return null;
                    }

                    let item = app.report.o.subscription.mrp_area_chart[index];
                    if (typeof item === 'undefined') {
                        return null;
                    }

                    // Get the tooltip label for billing cycle
                    let billing_cycle = '';
                    if (item.recurring == 1) {
                        billing_cycle = app.report.get_billing_cycle(item.billing_cycle);
                    } else {
                        billing_cycle = lang('Once');
                    }

                    let html = '';
                    let tpl_title = `<div class="morris-hover-row-label">{__TITLE_VALUE__}</div>`;
                    let tpl_item = `
                        <div class="morris-hover-point" style="color: {__ITEM_COLOR__}">
                            {__ITEM_VALUE__}
                        </div>`;
                    let tpl_item_value = `
                        <div class="morris-hover-point" style="color: {__ITEM_COLOR__}">
                            {__ITEM_NAME__}:
                            {__ITEM_VALUE__}
                        </div>`;

                    html += tpl_title.replace('{__TITLE_VALUE__}', row['calc_next_payment_date_formatted']);

                    // Print product name and billing cycle
                    html += tpl_item
                        .replace('{__ITEM_COLOR__}', '#0b62a4')
                        .replace('{__ITEM_VALUE__}', item.product_name + ' • ' + billing_cycle);

                    // Print price
                    html += tpl_item_value
                        .replace('{__ITEM_COLOR__}', '#cb4b4b')
                        .replace('{__ITEM_NAME__}', lang('Price'))
                        .replace('{__ITEM_VALUE__}', '$' + item.price);

                    // Print MRR price
                    if (item.ltdval_price != null) {
                        html += tpl_item_value
                            .replace('{__ITEM_COLOR__}', '#9440ed')
                            .replace('{__ITEM_NAME__}', lang('MRR'))
                            .replace('{__ITEM_VALUE__}', '$' + item.ltdval_price);
                    }

                    return html;
                },
            },
            google_area_chart: {
                tooltip_label: function (tooltip_item, data) {
                    var item_index = tooltip_item['index'];
                    var item = app.report.o.subscription.google_area_chart[item_index];
                    if (typeof item !== 'undefined') {
                        return item.product_name + ' • ' + app.report.get_billing_cycle(item.billing_cycle);
                    }
                    return null;
                },
            },
        },

        lifetime: {
            mrp_area_chart: {
                tooltip_label: function (index, options, content, row) {
                    if (typeof app.report.o.lifetime.mrp_area_chart === 'undefined') {
                        return null;
                    }

                    let item = app.report.o.lifetime.mrp_area_chart[index];
                    if (typeof item === 'undefined') {
                        return null;
                    }

                    let html = '';
                    let tpl_title = `<div class="morris-hover-row-label">{__TITLE_VALUE__}</div>`;
                    let tpl_item = `
                        <div class="morris-hover-point" style="color: {__ITEM_COLOR__}">
                            {__ITEM_VALUE__}
                        </div>`;
                    let tpl_item_value = `
                        <div class="morris-hover-point" style="color: {__ITEM_COLOR__}">
                            {__ITEM_NAME__}:
                            {__ITEM_VALUE__}
                        </div>`;

                    html += tpl_title.replace('{__TITLE_VALUE__}', row['calc_next_payment_date_formatted']);

                    // Print product name
                    html += tpl_item
                        .replace('{__ITEM_COLOR__}', '#0b62a4')
                        .replace('{__ITEM_VALUE__}', item.product_name);

                    // Print price
                    html += tpl_item_value
                        .replace('{__ITEM_COLOR__}', '#cb4b4b')
                        .replace('{__ITEM_NAME__}', lang('Price'))
                        .replace('{__ITEM_VALUE__}', '$' + item.price);

                    // Print MRR price
                    if (item.ltdval_price != null) {
                        html += tpl_item_value
                            .replace('{__ITEM_COLOR__}', '#9440ed')
                            .replace('{__ITEM_NAME__}', lang('MRR'))
                            .replace('{__ITEM_VALUE__}', '$' + item.ltdval_price);
                    }

                    return html;
                },
            },
            drilldown: {
                tooltip_label: function (tooltip_item, data) {
                    var item_index = tooltip_item['index'];
                    var item = app.report.o.lifetime.drilldown[item_index];
                    if (typeof item !== 'undefined') {
                        if (typeof item.product_name === 'undefined') {
                            return '$' + tooltip_item.value;
                        } else {
                            return item.product_name + ' • $' + tooltip_item.value;
                        }
                    }
                    return null;
                },
            },
        },
    };

    constructor() {
    }

    init() {

    }

    get_billing_cycle(billing_cycle) {
        if (billing_cycle == 1) {
            return 'Daily';
        } else if (billing_cycle == 2) {
            return 'Weekly';
        } else if (billing_cycle === 3) {
            return 'Monthly';
        } else if (billing_cycle === 4) {
            return 'Yearly';
        }
        return billing_cycle;
    }

    get_drilldown_tooltip() {
        let form_data = new FormData();
        form_data.append('_token', app.config.token);


        setTimeout(function () {
            var setInterval_id = setInterval(function () {
                if ($.active <= 0) {
                    clearInterval(setInterval_id);
                    $.ajax({
                        url: app.url + 'koolreport/subscription/get_drilldown_tooltip',
                        type: 'POST',
                        data: form_data,
                        dataType: 'json',
                        beforeSend: function (xhr) {
                            // app.loading.btn(ctl);
                        },
                        success: function (response) {
                            app.subscription.o.subscription.drilldown = response;
                        },
                        error: function (response) {
                            app.alert.response(response);
                        },
                        complete: function () {
                            // app.loading.btn(ctl);
                        },
                        processData: false,
                        contentType: false,
                    });
                }
            }, 100);
        }, 100);
    }

    set_period(period) {
        app.report.o.filter.period = period;
        app.report.load_charts();
    }

    load_mrp_area_chart() {
        let form_data = new FormData();
        form_data.append('_token', app.config.token);
        form_data.append('period', app.report.o.filter.period);

        $.ajax({
            url: app.url + 'report/koolreport/subscription/mrp_area_chart',
            type: 'POST',
            data: form_data,
            dataType: 'json',
            beforeSend: function (xhr) {
                // app.loading.btn(ctl);
                app.loading.block(app.report.e.subscription.mrp_area_chart);
            },
            success: function (response) {
                $(app.report.e.subscription.mrp_area_chart).html(response.html);
            },
            error: function (response) {
                app.alert.response(response);
            },
            complete: function () {
                // app.loading.btn(ctl);
            },
            processData: false,
            contentType: false,
        });
    }

    load_charts() {
        let form_data = new FormData($(app.report.e.filter.form)[0]);
        form_data.append('_token', app.config.token);

        if (!app.report.o.filter.period) {
            app.report.o.filter.period = $(app.report.e.filter.period).val();
        }
        form_data.append('period', app.report.o.filter.period);

        app.report.o.ajax.current_request = $.ajax({
            url: app.url + 'report/subscription/all_charts',
            type: 'POST',
            data: form_data,
            dataType: 'json',
            beforeSend: function (xhr) {

                // Cancel the previous request
                if (app.report.o.ajax.current_request != null) {
                    app.report.o.ajax.current_request.abort();
                }

                // app.loading.btn(ctl);
                app.loading.block(app.report.e.chart.container);
            },
            success: function (response) {
                $(app.report.e.chart.container).html(response.html);
            },
            error: function (response) {
                // app.alert.response(response);
            },
            complete: function () {
                // app.loading.btn(ctl);
            },
            processData: false,
            contentType: false,
        });
    }

    load_lifetime_drilldown_chart(level, year, month_name = null) {
        let form_data = new FormData($(app.report.e.filter.form)[0]);
        form_data.append('_token', app.config.token);
        form_data.append('period', app.report.o.filter.period);
        form_data.append('level', level);
        form_data.append('year', year);
        form_data.append('month_name', month_name);

        app.report.o.ajax.current_request = $.ajax({
            url: app.url + 'report/koolreport/lifetime/drilldown_chart',
            type: 'POST',
            data: form_data,
            dataType: 'json',
            beforeSend: function (xhr) {

                // Cancel the previous request
                if (app.report.o.ajax.current_request != null) {
                    app.report.o.ajax.current_request.abort();
                }

                // app.loading.btn(ctl);
                app.loading.block(app.report.e.chart.container);
            },
            success: function (response) {
                $(app.report.e.lifetime.drilldown).html(response.html);
            },
            error: function (response) {
                // app.alert.response(response);
            },
            complete: function () {
                app.loading.unblock(app.report.e.chart.container);
                // app.loading.btn(ctl);
            },
            processData: false,
            contentType: false,
        });
    }
}

