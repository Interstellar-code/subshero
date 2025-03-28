
$(document).ready(function () {
    app.register('subscription', new clsApp_Subscription);
});


class clsApp_Subscription {
    _form_add = '#frm_subscription_add';
    _form_quick_add = '#frm_main_quick_add';
    _form_edit = '#frm_subscription_edit';
    image = '#subscription_add_image';
    img_add;
    img_edit;
    img_clone;
    pdf_invoice_add;

    e = {
        // Elements

        add: {
            modal: '#modal_subscription_add',
            form: '#frm_subscription_add',
            folder: '#subscription_add_folder_id',
            brand_id: '#subscription_add_brand_id',
            type: '#subscription_add_type',
            frequency: '#subscription_add_billing_frequency',
            cycle: '#subscription_add_billing_cycle',
            recurring: '#subscription_add_recurring',
            recurring_toggle: '#subscription_add_recurring_toggle_container',
            status_toggle: '#subscription_add_status_toggle_container',
            billing_container: '#subscription_add_billing_container',
            billing_type: '#frm_subscription_add input[name="billing_type"]',
            tags: '#frm_subscription_add #subscription_add_tags',
        },
        add_quick: {
            modal: '#modal_subscription_quick_add',
            form: '#frm_main_quick_add',
            folder: '#main_quick_add_folder',
            brand_id: '#main_quick_add_brand_id',
            type: '#main_quick_add_type',
            frequency: '#main_quick_add_billing_frequency',
            cycle: '#main_quick_add_billing_cycle',
            recurring: '#main_quick_add_recurring',
            recurring_toggle: '#main_quick_add_recurring_toggle_container',
            status_toggle: '#main_quick_add_status_toggle_container',
            billing_container: '#main_quick_add_billing_container',
            billing_type: '#frm_main_quick_add input[name="billing_type"]',
        },
        invoice_parse: {
            modal: '#modal_subscription_invoice_parse',
            form: '#frm_main_invoice_parse',
            status_toggle: '#subscription_invoice_parse_status_toggle_container',
        },
        edit: {
            modal: '#modal_subscription_edit',
            form: '#frm_subscription_edit',
            brand_id: '#subscription_edit_brand_id',
            type: '#subscription_edit_type',
            frequency: '#subscription_edit_billing_frequency',
            cycle: '#subscription_edit_billing_cycle',
            recurring: '#subscription_edit_recurring',
            recurring_toggle: '#subscription_edit_recurring_toggle',
            recurring_toggle_container: '#subscription_edit_recurring_toggle_container',
            status_toggle: '#subscription_edit_status_toggle_container',
            billing_container: '#subscription_edit_billing_container',
            billing_type: '#frm_subscription_edit input[name="billing_type"]',
        },
        clone: {
            modal: '#modal_subscription_clone',
            form: '#frm_subscription_clone',
            brand_id: '#subscription_clone_brand_id',
            type: '#subscription_clone_type',
            frequency: '#subscription_clone_billing_frequency',
            cycle: '#subscription_clone_billing_cycle',
            recurring: '#subscription_clone_recurring',
            recurring_toggle: '#subscription_clone_recurring_toggle',
            recurring_toggle_container: '#subscription_clone_recurring_toggle_container',
            status_toggle: '#subscription_clone_status_toggle_container',
            billing_container: '#subscription_clone_billing_container',
            billing_type: '#frm_subscription_clone input[name="billing_type"]',
        },
        addon: {
            form: '#frm_subscription_addon',
            type: '#subscription_addon_type',
            frequency: '#subscription_addon_billing_frequency',
            cycle: '#subscription_addon_billing_cycle',
            recurring: '#subscription_addon_recurring',
            recurring_toggle: '#subscription_addon_recurring_toggle',
            recurring_toggle_container: '#subscription_addon_recurring_toggle_container',
            status_toggle: '#subscription_addon_status_toggle_container',
            billing_container: '#subscription_addon_billing_container',
            billing_type: '#frm_subscription_addon input[name="billing_type"]',
        },
        attachment: {
            form: '#frm_subscription_attachment',
            type: '#subscription_attachment_type',
            frequency: '#subscription_attachment_billing_frequency',
            cycle: '#subscription_attachment_billing_cycle',
            recurring: '#subscription_attachment_recurring',
            recurring_toggle: '#subscription_attachment_recurring_toggle',
            recurring_toggle_container: '#subscription_attachment_recurring_toggle_container',
            status_toggle: '#subscription_attachment_status_toggle_container',
            billing_container: '#subscription_attachment_billing_container',
            billing_type: '#frm_subscription_attachment input[name="billing_type"]',
            filepond: '#subscription_attachment_image_file',
            modal: '#modal_subscription_attachment',
            list: '#modal_subscription_attachment table tbody',
            template: '#tpl_subscription_attachment_row',
        },
        container: '#folder_container',


        subscription: {
            chart: '#subscription_chart_subs',
        },
        lifetime: {
            chart: '#subscription_chart_ltd',
        },

        koolreport: {
            area_chart: '#koolreport_subscription_area_chart',
            drilldown: '#koolreport_subscription_drilldown',
        },

        chart: {
            subscription_total_count: '#subscription_total_count',
            subscription_active_count: '#subscription_active_count',
            subscription_monthly_price: '#subscription_monthly_price',
            subscription_total_price: '#subscription_total_price',

            lifetime_total_count: '#lifetime_total_count',
            lifetime_active_count: '#lifetime_active_count',
            lifetime_this_year_price: '#lifetime_this_year_price',
            lifetime_total_price: '#lifetime_total_price',
        },

        select2: {
            brand_id: '#tpl_select2_favicon',
        },
    };
    d = {
        // Default values

        subscription: {
            chart: {
                series: [{
                    name: lang('Subscription'),
                    data: {},
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
                    curve: 'smooth'
                },

                // title: {
                //     text: "@lang('Subscriptions')",
                //     align: 'left'
                // },
                // subtitle: {
                //     text: 'Price Movements',
                //     align: 'left'
                // },
                labels: {},
                xaxis: {
                    type: 'month',
                },
                yaxis: {
                    opposite: true
                },
                legend: {
                    horizontalAlign: 'left'
                },
            },
        },
        lifetime: {
            chart: {
                series: [{
                    name: lang('LTD'),
                    data: {},
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
                    curve: 'smooth'
                },

                // title: {
                //     text: "@lang('Lifetime')",
                //     align: 'left'
                // },
                // subtitle: {
                //     text: 'Price Movements',
                //     align: 'left'
                // },
                labels: {},
                xaxis: {
                    type: 'month',
                },
                yaxis: {
                    opposite: true
                },
                legend: {
                    horizontalAlign: 'left'
                },
            },
        },
    };
    o = {
        // Objects

        billing_frequency: null,
        billing_cycle: null,
        currency_code: null,
        payment_mode: null,

        subscription: {
            chart: {
                config: null,
                data: null,
                instance: null,
            },
            area_chart: null,
            table: null,
        },
        lifetime: {
            chart: {
                config: null,
                data: null,
                instance: null,
            },
            drilldown: null,
        },

        area_chart: null,
        drilldown: null,

        attachment: {
            filepond: null,
            files: [],
            data_id: null,
        },

        ajax: {
            current_request: null,
        },

        select2: {
            selection: null,
        },

        add: {
            select2: {
                brand_id: null,
            },
        },

        add_quick: {
            select2: {
                brand_id: null,
            },
        },

        edit: {
            select2: {
                brand_id: null,
            },
        },

        clone: {
            select2: {
                brand_id: null,
            },
        },

    };
    c = {
        // Configuration

        select2: {
            product: {
                tags: true,
                theme: 'bootstrap4',
                dropdownParent: $('#modal_subscription_add'),
                // dropdownCssClass: 'select2_search_below',
                placeholder: {
                    id: '',
                    text: app.lang['None Selected'],
                },
                insertTag: function (data, tag) {
                    // Disable selecting numeric value
                    if (isNaN(tag.text)) {
                        data.push(tag);
                    }
                },
                ajax: {
                    url: function (params) {
                        if (params.term) {
                            return app.url + 'select2/product/search/' + params.term;
                        } else {
                            return app.url + 'select2/product/search/';
                        }
                    },
                    processResults: function (data) {
                        return {
                            results: data,
                        };
                    },
                },
                templateResult: function (state) {
                    let output = '';

                    if (typeof state.pricing_type !== 'undefined') {
                        // Replace variables
                        output = $(app.subscription.t.select2.get_brand_id(state.id, state.pricing_type, state.product_name, state.favicon));
                    } else if (!state.loading) {
                        output = $(app.subscription.t.select2.get_brand_id_new_item(state.text));
                    } else {
                        return state.text;
                    }

                    return output;
                },
                templateSelection: function (state) {
                    if (typeof state.pricing_type !== 'undefined') {
                        // Replace variables
                        return state.product_name;
                        // return $(app.subscription.t.select2.get_brand_id_selection(state.id, state.product_name, state.favicon));
                    }

                    return state.text;
                },
            },
        },
    };
    f = {
        // Callback function

        area_chart_tooltip_label: function (tooltip_item, data) {
            var item_index = tooltip_item['index'];
            var item = app.subscription.o.subscription.area_chart[item_index];
            if (typeof item !== 'undefined') {
                return item.product_name + ' • ' + app.subscription.get_billing_cycle(item.billing_cycle);
            }
            return null;
        },

        lifetime: {
            drilldown_tooltip_label: function (tooltip_item, data) {
                var item_index = tooltip_item['index'];
                var item = app.subscription.o.lifetime.drilldown[item_index];
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
    };
    t = {
        // Template

        history: {
            table: {
                action: $('#tpl_subscription_history_action_column').html(),
                get_action: function (id) {
                    return app.subscription.t.history.table.action
                        .replace(/__ID__/g, id);
                },

                payment_date: $('#tpl_subscription_history_payment_date_column').html(),
                get_payment_date: function (payment_date) {
                    const date = new Date(payment_date);
                    const formattedDate = date.toLocaleDateString('en-GB', {
                        day: 'numeric', month: 'short', year: 'numeric'
                    });

                    return app.subscription.t.history.table.payment_date
                        .replace(/__DATE__/g, formattedDate);
                },

                payment_method: $('#tpl_subscription_history_payment_method_column').html(),
                get_payment_method: function (payment_method_name) {
                    return app.subscription.t.history.table.payment_method
                        .replace(/__PAYMENT_METHOD_NAME__/g, payment_method_name);
                },
            },
        },

        select2: {
            brand_id: $('#tpl_select2_subscription_brand_id').html(),
            get_brand_id: function (id, type, product_name, favicon) {

                // Check type
                let output_type = '';
                let output_type_class = '';
                if (type) {

                    // Subscription
                    if (type == 1) {
                        output_type = 'Sub';
                        output_type_class = 'warning'
                    }

                    // Lifetime
                    else if (type == 3) {
                        output_type = 'LTD';
                        output_type_class = 'info'
                    }
                }

                return app.subscription.t.select2.brand_id
                    .replace(/__ITEM_ID__/g, id)
                    .replace(/__ITEM_TYPE__/g, output_type)
                    .replace(/__ITEM_TYPE_CLASS__/g, output_type_class)
                    .replace(/__ITEM_PRODUCT_NAME__/g, product_name)
                    .replace(/__ITEM_FAVICON_SRC__/g, favicon);
            },

            brand_id_selection: $('#tpl_select2_subscription_brand_id_selection').html(),
            get_brand_id_selection: function (id, product_name, favicon) {
                return app.subscription.t.select2.brand_id_selection
                    .replace(/__ITEM_ID__/g, id)
                    .replace(/__ITEM_PRODUCT_NAME__/g, product_name)
                    .replace(/__ITEM_FAVICON_SRC__/g, favicon);
            },

            brand_id_new_item: $('#tpl_select2_subscription_brand_id_new').html(),
            get_brand_id_new_item: function (text) {
                return app.subscription.t.select2.brand_id_new_item
                    .replace(/__ITEM_TEXT__/g, text);
            },
        },
    };

    constructor() {
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

    init() {

        if (this.img_add) {
            this.img_add.destroy();
            this.img_add.destroy = null;
        }
        if (this.pdf_invoice_add) {
            this.pdf_invoice_add.destroy();
            this.pdf_invoice_add.destroy = null;
        }
        lib.sleep(100).then(function () {
            app.subscription.img_add = lib.img.filepond('#subscription_add_image_file');
            let filepond_invoice_options = {
                labelIdle: `<p class="subscription_invoice_big_title"><img src="/assets/icons/invoice_parsing/invoice.svg" class="subscription_invoice_icon m-2"/>Drop Invoices Here or Click to Upload</p><p>Allowed file types: pdf</p>`,
                imagePreviewHeight: 600,
                imageCropAspectRatio: '1:1',
                imageResizeTargetWidth: 600,
                imageResizeTargetHeight: 600,
                stylePanelLayout: 'integrated',
                styleLoadIndicatorPosition: 'center bottom',
                styleButtonRemoveItemPosition: 'center bottom',
            };
            app.subscription.pdf_invoice_add = lib.img.filepond('#subscription_invoice_file', filepond_invoice_options);
            if (app.subscription.pdf_invoice_add) {
                app.subscription.pdf_invoice_add.onaddfile = function (error, file) {
                    if (error) {
                        console.log('error', error);
                        return;
                    }
                    const form = new FormData($(app.subscription.e.invoice_parse.form)[0]);
                    if (file && file.file) {
                        form.append('file', file.file);
                    }
                    $('#subscription_invoice_load_msg').show();
                    // $(app.subscription.e.invoice_parse.form).hide();
                    $.ajax({
                        url: $(app.subscription.e.invoice_parse.form).attr('action'),
                        type: 'POST',
                        data: form,
                        dataType: 'json',
                        success: function (response) {
                            console.log(response);
                            $('#subscription_invoice_load_msg').hide();
                            app.subscription.pdf_invoice_add.removeFiles();
                            $(app.subscription.e.invoice_parse.form).hide();
                            $('#subscription_invoice_preview_table').show();
                            subscription_invoice_make_table(response);
                        },
                        error: function (response) {
                            console.log(response);
                            $('#subscription_invoice_load_msg').hide();
                            app.subscription.pdf_invoice_add.removeFiles();
                            $(app.subscription.e.invoice_parse.form).hide();
                            $('#subscription_invoice_preview_table').show();
                        },
                        processData: false,
                        contentType: false,
                    });
                };
            };

            // Check if element found and loaded
            if (app.subscription.img_add) {
                app.subscription.img_add.on('removefile', app.subscription.img_add_on_addfile);
            }
        });

        // this._img_edit = lib.img.filepond('#main_edit_image_file');

        // let filepond_options = this.filepond_options;
        // this.img_add = $('#subscription_add_image_file').filepond(filepond_options);
        // this.img_add = FilePond.create($('#subscription_add_image_file')[0], filepond_options);



        $('#modal_subscription_add .select2_init_tags').select2({
            tags: true,
            theme: 'bootstrap4',
            dropdownParent: $('#modal_subscription_add'),
            placeholder: {
                id: '',
                text: app.lang['None Selected'],
            },
            insertTag: function (data, tag) {
                // Disable selecting numeric value
                if (isNaN(tag.text)) {
                    data.push(tag);
                }
            },
        });
        $('#modal_subscription_add .select2_init_multi').select2({
            tags: true,
            theme: 'bootstrap4',
            dropdownParent: $('#modal_subscription_add'),
            insertTag: function (data, tag) {
                // Disable selecting numeric value
                if (isNaN(tag.text)) {
                    data.push(tag);
                }
            },
        });
        $('#modal_subscription_quick_add .select2_init_tags').select2({
            tags: true,
            theme: 'bootstrap4',
            dropdownParent: $('#modal_subscription_quick_add'),
            placeholder: {
                id: '',
                text: app.lang['None Selected'],
            },
        });

        // $('#modal_subscription_edit .select2_init_tags').select2({
        //     tags: true,
        //     theme: 'bootstrap4',
        //     dropdownParent: $('#modal_subscription_edit'),
        //     placeholder: {
        //         id: '',
        //         text: app.lang['None Selected'],
        //     },
        // });
        // $('#modal_subscription_edit .select2_init_multi').select2({
        //     tags: true,
        //     theme: 'bootstrap4',
        //     dropdownParent: $('#modal_subscription_edit'),
        // });



        $('#subscription_add_category_id').select2({
            tags: false,
            theme: 'bootstrap4',
            dropdownParent: $('#modal_subscription_add'),
            dropdownCssClass: 'select2_search_below',
        });


        $('.bars-movie').barrating('clear');
        $('.bars-movie').barrating('set', 0);


        // Subscription -> Create -> Tag (Select2)
        $(app.subscription.e.add.tags).select2({
            tags: true,
            theme: 'bootstrap4',
            dropdownParent: $('#modal_subscription_add'),
            dropdownCssClass: 'select2_search_below',
            maximumSelectionLength: 10,
            ajax: {
                url: app.url + 'settings/tag/search/',
                data: function (params) {
                    var query = {
                        q: params.term,
                    };

                    // Query parameters will be ?search=[term]
                    return query;
                },
                processResults: function (data) {
                    // Transforms the top-level key of the response object from 'items' to 'results'

                    let output = [];
                    data.forEach(element => {

                        // Return the whole object
                        element.text = element.name;
                        output.push(element);
                    });

                    return {
                        results: output,
                    };
                },
            },
        });


        // Subscription -> Create -> Folder (Select2)
        $(app.subscription.e.add.folder).select2({
            tags: true,
            theme: 'bootstrap4',
            dropdownParent: $('#modal_subscription_add'),
            // dropdownCssClass: 'select2_search_below',
            ajax: {
                url: app.url + 'folder/search',
                data: function (params) {
                    var query = {
                        q: params.term,
                    };

                    // Query parameters will be ?search=[term]
                    return query;
                },
                processResults: function (data) {
                    // Transforms the top-level key of the response object from 'items' to 'results'

                    let output = [];
                    data.forEach(element => {

                        // Return the whole object
                        element.text = `${element.name} <span class="badge badge_recurring">${element.currency_symbol}</span>`;
                        output.push(element);
                    });

                    return {
                        results: output,
                    };
                },
            },
            templateResult: function (el) {
                if (!el.name) {
                    return el.text;
                }
                if (!el.currency_symbol) {
                    el.currency_symbol = 'All';
                }
                return `${el.name} <span class="badge badge_recurring">${el.currency_symbol}</span>`
            },
            escapeMarkup: function (markup) {
                return markup;
            },
            templateSelection: function (el) {
                if (el.text.includes('<span') && el.text.includes('</span>')) {
                    return el.text;
                }
                const words = el.text.split(' ');
                if (words.length < 2) {
                    return el.text;
                }
                const currency_symbol = words.pop();
                const name = words.join(' ');
                return `${name} <span class="badge badge_recurring">${currency_symbol}</span>`;
            }
        });

        //Folder for quick add 
           // Subscription -> Create -> Folder (Select2)
           $(app.subscription.e.add_quick.folder).select2({
            tags: true,
            theme: 'bootstrap4',
            dropdownParent: $('#modal_subscription_quick_add'),
            // dropdownCssClass: 'select2_search_below',
            ajax: {
                url: app.url + 'folder/search',
                data: function (params) {
                    var query = {
                        q: params.term,
                    };

                    // Query parameters will be ?search=[term]
                    return query;
                },
                processResults: function (data) {
                    // Transforms the top-level key of the response object from 'items' to 'results'

                    let output = [];
                    data.forEach(element => {

                        // Return the whole object
                        element.text = `${element.name} <span class="badge badge_recurring">${element.currency_symbol}</span>`;
                        output.push(element);
                    });

                    return {
                        results: output,
                    };
                },
            },
            templateResult: function (el) {
                if (!el.name) {
                    return el.text;
                }
                if (!el.currency_symbol) {
                    el.currency_symbol = 'All';
                }
                return `${el.name} <span class="badge badge_recurring">${el.currency_symbol}</span>`
            },
            escapeMarkup: function (markup) {
                return markup;
            },
            templateSelection: function (el) {
                if (el.text.includes('<span') && el.text.includes('</span>')) {
                    return el.text;
                }
                const words = el.text.split(' ');
                if (words.length < 2) {
                    return el.text;
                }
                const currency_symbol = words.pop();
                const name = words.join(' ');
                return `${name} <span class="badge badge_recurring">${currency_symbol}</span>`;
            }
        });


        // Subscription -> Create -> Product (Select2)
        app.subscription.o.add.select2.brand_id = app.subscription.c.select2.product;
        app.subscription.o.add.select2.brand_id.dropdownParent = $(app.subscription.e.add.modal);
        $(app.subscription.e.add.brand_id).select2(app.subscription.o.add.select2.brand_id);


        $(app.subscription.e.add.brand_id).on('select2:select', function (e) {
            if (app.subscription.o.select2.selection) {
                return;
            }

            if (!e.params.data) {
                return;
            }

            lib.sleep(100).then(() => {
                let path = e.params.data.image_path;
                app.subscription.img_add.destroy();
                app.subscription.img_add = null;
                $('#subscription_add_image_path').val('');

                // New fields
                // $('#subscription_add_company_description').val(e.params.data.description);
                $('#subscription_add_company_type').val(e.params.data.product_type);
                // $('#subscription_add_company_type_label').text(e.params.data.product_type_name);
                $('#subscription_add_description').val(e.params.data.description);
                $('#subscription_add_price').val(e.params.data.price1_value);
                $('#subscription_add_price_type').val(e.params.data.currency_code);
                $('#subscription_add_url').val(e.params.data.url);
                $('#subscription_add_billing_frequency').val(e.params.data.billing_frequency);
                $('#subscription_add_billing_cycle').val(e.params.data.billing_cycle);
                $('#subscription_add_type').val(e.params.data.pricing_type);
                // $('#subscription_add_company_type_label').text(e.params.data.pricing_type);
                $('#subscription_add_category_id').val(e.params.data.category_id).trigger('change');
                $('#subscription_add_ltdval_price').val(e.params.data.ltdval_price);
                $('#subscription_add_ltdval_cycle').val(e.params.data.ltdval_cycle);
                $('#subscription_add_ltdval_frequency').val(e.params.data.ltdval_frequency);
                $('#subscription_add_refund_days').val(e.params.data.refund_days);

                // Set refund days
                let refund_days = parseInt(e.params.data.refund_days);
                if (refund_days > 0) {
                    $('#subscription_add_refund_days').val(refund_days);
                } else {
                    $('#subscription_add_refund_days').val('');
                }

                app.subscription.create_type_check('#subscription_add_type');
                app.subscription.create_recurring_check($('#subscription_add_recurring')[0]);
                // app.ui.modal_favicon(e.params.data.url'), '#modal_subscription_add .modal-header img.favicon');


                // Load favicon from url
                if (e.params.data.favicon != '') {
                    $('#modal_subscription_add .modal-header img.favicon').attr('src', e.params.data.favicon);
                } else {
                    app.ui.modal_favicon(e.params.data.url, '#modal_subscription_add .modal-header img.favicon');
                }

                app.subscription.modal_title_change('create');

                if (path) {
                    // console.log(path);
                    let filepond_options = Object.assign({}, app.global.filepond_options);
                    filepond_options.files = [{
                        source: btoa(path),
                        // source: path,
                        options: {
                            type: 'local',
                        },
                    }];

                    lib.sleep(100).then(function () {
                        $('#subscription_add_image_path').val(path);
                        app.subscription.img_add = lib.img.filepond('#subscription_add_image_file', filepond_options);
                        // console.log('After load');
                        $('#subscription_add_img_path_or_file').val(0);
                        app.subscription.img_add.on('removefile', app.subscription.img_add_on_addfile);
                    });

                } else {
                    lib.sleep(100).then(function () {
                        app.subscription.img_add = lib.img.filepond('#subscription_add_image_file');
                        app.subscription.img_add.on('removefile', app.subscription.img_add_on_addfile);
                        $('#subscription_add_img_path_or_file').val(1);
                    });

                    // Default values
                    if (!e.params.data.billing_frequency) {
                        $('#subscription_add_billing_frequency').val(app.subscription.o.billing_frequency);
                    }
                    if (!e.params.data.billing_cycle) {
                        $('#subscription_add_billing_cycle').val(app.subscription.o.billing_cycle);
                    }
                    if (!e.params.data.currency_code) {
                        $('#subscription_add_price_type').val(app.subscription.o.currency_code);
                    }
                }

                app.subscription.o.select2.selection = false;
            });

            app.subscription.o.select2.selection = true;
        });

        // Subscription -> Create Quick -> Product (Select2)
        app.subscription.o.add_quick.select2.brand_id = app.subscription.c.select2.product;
        app.subscription.o.add_quick.select2.brand_id.dropdownParent = $(app.subscription.e.add_quick.modal);
        $(app.subscription.e.add_quick.brand_id).select2(app.subscription.o.add_quick.select2.brand_id);

        $(app.subscription.e.add_quick.brand_id).on('select2:select', function (e) {
            if (app.subscription.o.select2.selection) {
                return;
            }

            if (!e.params.data) {
                return;
            }

            lib.sleep(100).then(() => {
                let path = e.params.data.image_path;

                // New fields
                $('#main_quick_add_description').val(e.params.data.description);
                $('#main_quick_add_price').val(e.params.data.price1_value);
                $('#main_quick_add_price_type').val(e.params.data.currency_code);
                $('#main_quick_add_url').val(e.params.data.url);
                $('#main_quick_add_billing_frequency').val(e.params.data.billing_frequency);
                $('#main_quick_add_billing_cycle').val(e.params.data.billing_cycle);
                $('#main_quick_add_type').val(e.params.data.pricing_type);
                // $('#main_quick_add_refund_days').val(e.params.data.refund_days);
                // $('#main_quick_add_company_type_label').val(e.params.data.pricing_type);

                // Set refund days
                let refund_days = parseInt(e.params.data.refund_days);
                if (refund_days > 0) {
                    $('#main_quick_add_refund_days').val(refund_days);
                } else {
                    $('#main_quick_add_refund_days').val('');
                }

                app.subscription.create_quick_type_check('#main_quick_add_type');

                app.subscription.modal_title_change('create_quick');
                app.subscription.create_quick_recurring_check($('#main_quick_add_recurring')[0]);

                if (!path) {
                    // Default values
                    if (!e.params.data.billing_frequency) {
                        $('#main_quick_add_billing_frequency').val(app.subscription.o.billing_frequency);
                    }
                    if (!e.params.data.billing_cycle) {
                        $('#main_quick_add_billing_cycle').val(app.subscription.o.billing_cycle);
                    }
                    if (!e.params.data.currency_code) {
                        $('#main_quick_add_price_type').val(app.subscription.o.currency_code);
                    }
                }

                // Load favicon from url
                if (e.params.data.favicon != '') {
                    $('#modal_subscription_quick_add .modal-header img.favicon').attr('src', e.params.data.favicon);
                } else {
                    app.ui.modal_favicon(e.params.data.url, '#modal_subscription_quick_add .modal-header img.favicon');
                }

                app.subscription.o.select2.selection = false;
            });

            app.subscription.o.select2.selection = true;
        });



        // Subscription -> Attachment
        let attachment_filepond_options = {
            allowReorder: true,
            allowImagePreview: false,
            maxFileSize: '8MB',
            acceptedFileTypes: ['image/*', 'application/pdf', 'text/csv',],
        };
        app.subscription.o.attachment.filepond = FilePond.create($(app.subscription.e.attachment.filepond)[0], attachment_filepond_options);
        app.subscription.o.attachment.filepond.onaddfile = function (error, file) {
            console.log('error', error);
            console.log('addfile', file);
            if (error === null) {
                app.subscription.attachment_load_file(file);
            }
        };

        // Load both the charts
        app.subscription.koolreport_both_charts_load();
    }

    img_add_on_addfile(error, file) {
        $('#subscription_add_img_path_or_file').val(1);
        // console.log(error);
        // console.log(file);
    }

    img_edit_on_addfile(error, file) {
        $('#subscription_edit_img_path_or_file').val(1);
        // console.log(error);
        // console.log(file);
    }

    img_clone_on_addfile(error, file) {
        $('#subscription_clone_img_path_or_file').val(1);
        // console.log(error);
        // console.log(file);
    }

    create(ctl) {
        app.global.create({
            form: app.subscription._form_add,
            slug: 'subscription/create',
            image: (this.img_add.getFile() ? this.img_add.getFile().file : null),
            btn: ctl,
            success: function (response) {
                if (response.status) {
                    app.alert.succ(response.message);
                    app.subscription.reset_type_fields();
                    lib.sub.modal_subscription_add_done();
                    $(app.subscription._form_add)[0].reset();
                    app.subscription.img_add.removeFile();
                    app.subscription.init();
                    // app.page.switch('subscription');
                    app.subscription.load_page();
                    app.folder.refresh();

                    // lib.img.reset($(image), 200);
                    // app.page.switch('main');
                } else {
                    app.alert.validation(response.message);
                }
            },
        });
    }

    create_quick(ctl) {
        app.global.create({
            form: app.subscription._form_quick_add,
            slug: 'subscription/create_quick',
            btn: ctl,
            success: function (response) {
                if (response.status) {
                    app.alert.succ(response.message);
                    app.subscription.reset_type_fields();
                    lib.sub.modal_subscription_quick_add_done();
                    $(app.subscription._form_quick_add)[0].reset();
                    app.subscription.init();
                    // app.page.switch('subscription');
                    // app.page.switch('main');
                    app.subscription.load_page();
                    app.folder.refresh();
                } else {
                    app.alert.validation(response.message);
                }
            },
        });
    }

    edit(ctl) {
        let id = lib.get_id(ctl);
        if (!id) {
            return false;
        }

        let form_data = new FormData();
        form_data.append('_token', app.config.token);
        form_data.append('id', id);

        $.ajax({
            url: app.url + 'subscription/edit/' + id,
            type: 'POST',
            data: form_data,
            beforeSend: function (xhr) {
                app.loading.btn(ctl);
            },
            success: function (response) {
                if (response) {
                    $('#modal_subscription_edit .modal-body').html(response);
                    $('#modal_subscription_edit').modal();
                    app.load.tooltip();
                }
            },
            error: function (response) {
                app.alert.response(response);
            },
            complete: function () {
                app.loading.btn(ctl);
            },
            processData: false,
            contentType: false,
        });
    }

    update(ctl, id) {
        app.global.create({
            form: app.subscription._form_edit,
            slug: 'subscription/update/' + id,
            image: (this.img_edit.getFile() ? this.img_edit.getFile().file : null),
            btn: ctl,
            success: function (response) {
                if (response.status) {
                    app.alert.succ(response.message);
                    app.subscription.reset_type_fields();
                    $('#modal_subscription_edit').modal('hide');
                    $(app.subscription._form_edit)[0].reset();
                    app.subscription.img_edit.removeFile();
                    app.subscription.init();
                    // app.page.switch('subscription');
                    app.subscription.load_page();
                    app.folder.refresh();

                    // lib.img.reset($(image), 200);
                    // app.page.switch('main');
                } else {
                    app.alert.validation(response.message);
                }
            },
        });
    }

    history_clear_item(history_item, id) {
        history_item = { ...history_item };
        delete (history_item.action);
        const payment_method_id = parseInt(history_item.payment_method);
        if (payment_method_id) {
            const payment_methods = document.wijmoApp._dataSvc.getPaymentMethods();
            history_item.payment_method = payment_methods[payment_method_id];
        } else {
            delete (history_item.payment_method);
        }
        const parsed_date = wijmo.Globalize.parseDate(history_item.payment_date, 'MMM d yyyy');
        if (parsed_date) {
            history_item.payment_date = `${parsed_date.getFullYear()}-${parsed_date.getMonth() + 1}-${parsed_date.getDate()}`;
        } else {
            delete (history_item.payment_date);
        }
        if (!id) {
            delete (history_item.id);
        }
        history_item.subscription_id = $('#subscription_history_table_wijmo_grid').data('subscription_id');
        return history_item;
    }

    history_save(el) {
        const id = parseInt(el.dataset.id);
        $(el).hide();
        $(el).parent().find('i').show();
        const view = document.wijmoApp._theGrid.collectionView;
        let history_item = { ...view.currentItem };
        history_item = this.history_clear_item(history_item, id);
        history_item._token = app.config.token;
        const form_data = new FormData();
        for (const key in history_item) {
            form_data.append(key, history_item[key]);
        }
        $.ajax({
            url: app.url + 'subscription_history/update',
            type: 'POST',
            data: form_data,
            dataType: 'json',
            beforeSend: function (xhr) {
                //app.loading.btn(ctl);
            },
            success: function (response) {
                if (response.status) {
                    const view = document.wijmoApp._theGrid.collectionView;
                    const length = view.items.length;
                    const latest = length - 1;
                    const latest_item = view.items[latest];
                    if (!latest_item.id) {
                        latest_item.id = response.id;
                        latest_item.action = 1;
                        delete (latest_item.subscription_id);
                        delete (latest_item._token);
                    }
                    view.commitEdit();
                    document.wijmoApp._theGrid.collectionView.refresh()
                    app.alert.succ(response.message);
                } else {
                    app.alert.validation(response.message);
                }
            },
            error: function (response) {
                app.alert.err(response.message);
            },
            complete: function () {
                //app.loading.btn(ctl);
            },
            processData: false,
            contentType: false,
        });
    }

    history_save_all() {
        let history_data = JSON.parse(JSON.stringify(document.wijmoApp._theGrid.collectionView._src));
        const cleared_data = history_data.map(item => this.history_clear_item(item, parseInt(item.id ?? 0)));
        history_data = JSON.stringify(cleared_data);
        const form_data = new FormData();
        form_data.append('_token', app.config.token);
        form_data.append('history_data', history_data);
        $.ajax({
            url: app.url + 'subscription_history/update_all',
            type: 'POST',
            data: form_data,
            dataType: 'json',
            beforeSend: function (xhr) {
            },
            success: function (response) {
                if (response.status) {
                    app.alert.succ(response.message);
                    const subscription_id = $('#subscription_history_table_wijmo_grid').data('subscription_id');
                    $.ajax({
                        url: `${app.url}subscription_history/get/${subscription_id}`,
                        type: 'GET',
                        dataType: 'json',
                        success: function (real_data) {
                            const view = document.wijmoApp._theGrid.collectionView;
                            view._src = real_data.subscription_history;
                            view.refresh();
                        },
                    });
                } else {
                    app.alert.validation(response.message);
                }
            },
            error: function (response) {
                app.alert.response(response);
            },
            complete: function () {
            },
            processData: false,
            contentType: false,
        });
    }

    history_add() {
        const view = document.wijmoApp._theGrid.collectionView;
        const history_new_item = view.addNew();
        history_new_item.id = 0;
        const defaults = document.wijmoApp._dataSvc._real_data.defaults;
        history_new_item.payment_date = defaults.payment_date;
        history_new_item.price = defaults.price;
        history_new_item.payment_method = defaults.payment_method;
        delete (history_new_item.history);
        view.commitNew();
    }

    history_delete(el) {
        const id = el.dataset.id;
        $(el).hide();
        $(el).parent().find('i').show();
        const view = document.wijmoApp._theGrid.collectionView;
        view.remove(view.currentItem);
        const data = {
            id: id,
            _token: app.config.token,
        }
        const form_data = new FormData();
        for (const key in data) {
            form_data.append(key, data[key]);
        }
        $.ajax({
            url: app.url + 'subscription_history/delete',
            type: 'POST',
            data: form_data,
            dataType: 'json',
            beforeSend: function (xhr) {
                //app.loading.btn(ctl);
            },
            success: function (response) {
                if (response.status) {
                    app.alert.succ(response.message);
                } else {
                    app.alert.validation(response.message);
                }
            },
            error: function (response) {
                app.alert.response(response);
            },
            complete: function () {
                //app.loading.btn(ctl);
            },
            processData: false,
            contentType: false,
        });
    }

    delete(ctl) {
        let id = lib.get_id(ctl);
        if (!id) {
            return false;
        }

        swal({
            title: 'Are you sure?',
            text: 'Once deleted, you will not be able to recover this!',
            icon: 'warning',
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                let form_data = new FormData();
                form_data.append('_token', app.config.token);
                form_data.append('id', id);

                $.ajax({
                    url: app.url + 'subscription/delete/' + id,
                    type: 'POST',
                    data: form_data,
                    dataType: 'json',
                    beforeSend: function (xhr) {
                        app.loading.btn(ctl);
                    },
                    success: function (response) {
                        if (response) {
                            $('#modal_subscription_edit').modal('hide');
                            // app.page.switch('subscription');
                            $('#tbl_subscription').DataTable().ajax.reload(null, false);
                            app.alert.succ(response.message);
                        } else {
                            app.alert.warn(response.message);
                        }
                    },
                    error: function (response) {
                        app.alert.response(response);
                    },
                    complete: function () {
                        app.loading.btn(ctl);
                    },
                    processData: false,
                    contentType: false,
                });
            }
        });
    }

    cancel(ctl) {
        let id = lib.get_id(ctl);
        if (!id) {
            return false;
        }

        swal({
            title: 'Are you sure?',
            text: 'Once canceled, you will not be able to activate this!',
            icon: 'warning',
            buttons: true,
            dangerMode: true,
            buttons: ['Close', 'OK'],
        }).then((willDelete) => {
            if (willDelete) {
                let form_data = new FormData();
                form_data.append('_token', app.config.token);
                form_data.append('id', id);

                $.ajax({
                    url: app.url + 'subscription/cancel/' + id,
                    type: 'POST',
                    data: form_data,
                    dataType: 'json',
                    beforeSend: function (xhr) {
                        app.loading.btn(ctl);
                    },
                    success: function (response) {
                        if (response) {
                            $('#modal_subscription_edit').modal('hide');
                            // app.page.switch('subscription');
                            $('#tbl_subscription').DataTable().ajax.reload(null, false);
                            app.alert.succ(response.message);
                        } else {
                            app.alert.warn(response.message);
                        }
                    },
                    error: function (response) {
                        app.alert.response(response);
                    },
                    complete: function () {
                        app.loading.btn(ctl);
                    },
                    processData: false,
                    contentType: false,
                });
            }
        });
    }

    clone(ctl) {
        let id = lib.get_id(ctl);
        if (!id) {
            return false;
        }

        let form_data = new FormData();
        form_data.append('_token', app.config.token);
        form_data.append('id', id);

        $.ajax({
            url: app.url + 'subscription/clone/' + id,
            type: 'POST',
            data: form_data,
            beforeSend: function (xhr) {
                app.loading.btn(ctl);
            },
            success: function (response) {
                if (response) {
                    $('#modal_subscription_clone .modal-body').html(response);
                    $('#modal_subscription_clone').modal();
                    app.load.tooltip();
                }
            },
            error: function (response) {
                app.alert.response(response);
            },
            complete: function () {
                app.loading.btn(ctl);
            },
            processData: false,
            contentType: false,
        });
    }

    addon(ctl) {
        let id = lib.get_id(ctl);
        if (!id) {
            return false;
        }

        let form_data = new FormData();
        form_data.append('_token', app.config.token);
        form_data.append('id', id);

        $.ajax({
            url: app.url + 'subscription/addon/' + id,
            type: 'POST',
            data: form_data,
            beforeSend: function (xhr) {
                app.loading.btn(ctl);
            },
            success: function (response) {
                if (response) {
                    $('#modal_subscription_addon .modal-body').html(response);
                    $('#modal_subscription_addon').modal();
                    app.load.tooltip();
                }
            },
            error: function (response) {
                app.alert.response(response);
            },
            complete: function () {
                app.loading.btn(ctl);
            },
            processData: false,
            contentType: false,
        });
    }

    // pause(ctl) {
    //     let id = lib.get_id(ctl);
    //     if (!id) {
    //         return false;
    //     }

    //     swal({
    //         title: 'Are you sure?',
    //         text: '',
    //         icon: 'warning',
    //         buttons: true,
    //         dangerMode: true,
    //     }).then((willDelete) => {
    //         if (willDelete) {
    //             let form_data = new FormData();
    //             form_data.append('_token', app.config.token);
    //             form_data.append('id', id);

    //             $.ajax({
    //                 url: app.url + 'subscription/pause/' + id,
    //                 type: 'POST',
    //                 data: form_data,
    //                 dataType: 'json',
    //                 beforeSend: function (xhr) {
    //                     app.loading.btn(ctl);
    //                 },
    //                 success: function (response) {
    //                     if (response) {
    //                         app.page.switch('subscription');
    //                         app.alert.succ(response.message);
    //                     } else {
    //                         app.alert.warn(response.message);
    //                     }
    //                 },
    //                 error: function (response) {
    //                     app.alert.response(response);
    //                 },
    //                 complete: function () {
    //                     app.loading.btn(ctl);
    //                 },
    //                 processData: false,
    //                 contentType: false,
    //             });
    //         }
    //     });
    // }

    refund(ctl) {
        let id = lib.get_id(ctl);
        if (!id) {
            return false;
        }

        swal({
            title: 'Are you sure?',
            text: 'Once refunded, you will not be able to activate this!',
            icon: 'warning',
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                let form_data = new FormData();
                form_data.append('_token', app.config.token);
                form_data.append('id', id);

                $.ajax({
                    url: app.url + 'subscription/refund/' + id,
                    type: 'POST',
                    data: form_data,
                    dataType: 'json',
                    beforeSend: function (xhr) {
                        app.loading.btn(ctl);
                    },
                    success: function (response) {
                        if (response) {
                            $('#modal_subscription_edit').modal('hide');
                            // app.page.switch('subscription');
                            $('#tbl_subscription').DataTable().ajax.reload(null, false);
                            app.alert.succ(response.message);
                        } else {
                            app.alert.warn(response.message);
                        }
                    },
                    error: function (response) {
                        app.alert.response(response);
                    },
                    complete: function () {
                        app.loading.btn(ctl);
                    },
                    processData: false,
                    contentType: false,
                });
            }
        });
    }

    attachment(ctl) {
        let id = lib.get_id(ctl);
        if (!id) {
            id = $(ctl).closest('.modal').find('form input[name="id"]').val();

            if (!id) {
                return false;
            }
        }

        $('#modal_subscription_edit').modal('hide');

        let form_data = new FormData();
        form_data.append('_token', app.config.token);
        form_data.append('id', id);

        $.ajax({
            url: app.url + 'subscription/attachment/' + id,
            type: 'POST',
            data: form_data,
            beforeSend: function (xhr) {
                app.loading.btn(ctl);
            },
            success: function (response) {
                if (response) {
                    app.subscription.o.attachment.data_id = id;
                    $('#modal_subscription_attachment .modal-body .item_container').html(response);
                    $('#modal_subscription_attachment').modal('show');
                    app.load.tooltip();
                }
            },
            error: function (response) {
                app.alert.response(response);
            },
            complete: function () {
                app.loading.btn(ctl);
            },
            processData: false,
            contentType: false,
        });
    }

    attachment_load_file() {
        let file_obj = {};

        file_obj.id = 'file_' + app.subscription.o.attachment.files.length;
        file_obj.file = app.subscription.o.attachment.filepond.getFile().file;
        file_obj.file = new File([app.subscription.o.attachment.filepond.getFile().file], file_obj.file.name, { type: file_obj.file.type });
        app.subscription.o.attachment.filepond.removeFiles();

        let html = $(app.subscription.e.attachment.template).html();
        html = html.replace(/__FILE_ID__/g, file_obj.id);
        html = html.replace(/__FILE_NAME__/g, file_obj.file.name);
        html = html.replace(/__FILE_SIZE__/g, file_obj.file.size);
        $(app.subscription.e.attachment.list).append(html);

        app.subscription.o.attachment.files.push(file_obj);
        app.subscription.attachment_upload(file_obj.id);
    }

    attachment_upload(file_id) {
        if (isNaN(file_id)) {
            file_id = parseInt(file_id.replace('file_', ''));
        } else {
            file_id = parseInt(file_id);
        }

        if (typeof app.subscription.o.attachment.files[file_id] === 'undefined') {
            return false;
        }

        let file_obj = app.subscription.o.attachment.files[file_id];

        let form_data = new FormData();
        form_data.append('_token', app.config.token);
        form_data.append('id', app.subscription.o.attachment.data_id);
        form_data.append('file', file_obj.file);

        file_obj.jqXHR = $.ajax({
            url: app.url + 'subscription/attachment_upload/' + app.subscription.o.attachment.data_id,
            type: 'POST',
            data: form_data,
            dataType: 'json',
            beforeSend: function (xhr) {
                // app.loading.btn(ctl);
                app.load.tooltip();
                app.subscription.attachment_progress(file_obj.id, 0);
                app.subscription.attachment_display(file_obj.id, 'uploading');
            },
            xhr: function () {
                var xhr = new window.XMLHttpRequest();

                // Upload progress report
                xhr.upload.addEventListener('progress', function (evt) {
                    if (evt.lengthComputable) {
                        var percentComplete = evt.loaded / evt.total;
                        app.subscription.attachment_progress(file_obj.id, percentComplete);
                    }
                }, false);

                return xhr;
            },
            success: function (response) {
                if (response.status) {
                    // app.subscription.attachment(app.subscription.o.attachment.data_id);
                    app.subscription.attachment_display(file_obj.id, 'success');
                    let tr_element = $(app.subscription.e.attachment.list).find('tr[data-id="' + file_obj.id + '"]');
                    if (tr_element.length > 0 && response.data) {
                        tr_element.find('.file_size').text(response.data.file_size);
                        tr_element.find('.action_buttons a.btn_view').attr('href', response.data.file_url);
                        // tr_element.find('.action_buttons a.btn_download').attr('href', response.data.file_url).attr('download', response.data.file_name);
                        tr_element.find('.action_buttons .btn_download').attr('onclick', `lib.do.download_file('${response.data.file_url}', '${response.data.file_name}', this);`);
                        tr_element.find('.action_buttons').show();
                        tr_element.attr('data-id', response.data.id);
                    }

                } else {
                    app.alert.warn(response.message);
                    app.subscription.attachment_display(file_obj.id, 'failed');
                }
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

    attachment_display(file_id, status) {
        let tr_element = $(app.subscription.e.attachment.list).find('tr[data-id="' + file_id + '"]');
        if (tr_element.length > 0) {
            if (status == 'uploading') {
                tr_element.find('.btn_container .btn_cancel').show();
                tr_element.find('.btn_container .btn_retry').hide();
                tr_element.find('.file_size .progress').show();
                tr_element.find('.file_size .status_failed').hide();
            }
            else if (status == 'success') {
                tr_element.find('.btn_container .upload_buttons').remove();
                // tr_element.find('.btn_container .action_buttons').show();
                tr_element.find('.file_size').html(null);
            }
            else if (status == 'failed') {
                // tr_element.find('.btn_container .btn_cancel').hide();
                tr_element.find('.btn_container .btn_retry').show();
                tr_element.find('.file_size .progress').hide();
                tr_element.find('.file_size .status_failed').show();
            }
        }
    }

    attachment_progress(id, percent) {
        let progress_bar = $(app.subscription.e.attachment.list).find('tr[data-id="' + id + '"] .progress-bar');
        if (progress_bar.length > 0) {
            percent = Math.round(percent * 100);
            progress_bar.css('width', percent + '%');
            progress_bar.attr('aria-valuenow', percent);
            // progress_bar.text(percent + '%');
        }
    }

    attachment_cancel(file_id) {
        if (isNaN(file_id)) {
            let int_id = parseInt(file_id.replace('file_', ''));
            if (int_id >= 0 && typeof app.subscription.o.attachment.files[int_id] !== 'undefined') {
                app.subscription.o.attachment.files[int_id].jqXHR.abort();
                // app.subscription.attachment(app.subscription.o.attachment.data_id);
                $(app.subscription.e.attachment.list).find('tr[data-id="' + file_id + '"]').remove();
                app.load.tooltip();
                return true;
            }
        }
        return false;
    }

    attachment_delete(ctl) {
        let id = lib.get_id(ctl);
        if (!id) {
            return false;
        }

        swal({
            title: 'Are you sure?',
            text: 'Once deleted, you will not be able to recover this!',
            icon: 'warning',
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                let form_data = new FormData();
                form_data.append('_token', app.config.token);
                form_data.append('id', id);

                $.ajax({
                    url: app.url + 'subscription/attachment_delete/' + id,
                    type: 'POST',
                    data: form_data,
                    dataType: 'json',
                    beforeSend: function (xhr) {
                        app.loading.btn(ctl);
                    },
                    success: function (response) {
                        if (response) {
                            // app.subscription.attachment(app.subscription.o.attachment.data_id);
                            $(app.subscription.e.attachment.list).find('tr[data-id="' + id + '"]').remove();
                            app.load.tooltip();
                            app.alert.succ(response.message);
                        } else {
                            app.alert.warn(response.message);
                        }
                    },
                    error: function (response) {
                        app.alert.response(response);
                    },
                    complete: function () {
                        app.loading.btn(ctl);
                    },
                    processData: false,
                    contentType: false,
                });
            }
        });
    }

    clone_create(ctl) {
        app.global.create({
            form: app.subscription.e.clone.form,
            slug: 'subscription/create',
            image: (this.img_clone.getFile() ? this.img_clone.getFile().file : null),
            btn: ctl,
            success: function (response) {
                if (response.status) {
                    app.alert.succ(response.message);
                    $('#modal_subscription_clone').modal('hide');
                    app.subscription.init();
                    // app.page.switch('subscription');
                    $('#tbl_subscription').DataTable().ajax.reload(null, false);
                    app.folder.refresh();

                    // lib.img.reset($(image), 200);
                    // app.page.switch('main');
                } else {
                    app.alert.validation(response.message);
                }
            },
        });
    }

    addon_create(ctl) {
        app.global.create({
            form: app.subscription.e.addon.form,
            slug: 'subscription/create',
            image: (this.img_addon.getFile() ? this.img_addon.getFile().file : null),
            btn: ctl,
            success: function (response) {
                if (response.status) {
                    app.alert.succ(response.message);
                    $('#modal_subscription_addon').modal('hide');
                    app.subscription.init();
                    // app.page.switch('subscription');
                    $('#tbl_subscription').DataTable().ajax.reload(null, false);
                    app.folder.refresh();

                    // lib.img.reset($(image), 200);
                    // app.page.switch('main');
                } else {
                    app.alert.validation(response.message);
                }
            },
        });
    }

    get_by_folder(id) {
        event && event.preventDefault();

        let slug = location.href.split('/');

        $('#folder_container .dropdown-item.active').removeClass('active');
        $('#folder_container .dropdown-item[data-id=' + id + ']').addClass('active');

        // Set folder selection
        let form_data = new FormData();
        form_data.append('_token', app.config.token);
        form_data.append('id', id);

        $.ajax({
            url: app.url + 'folder/session/set',
            type: 'POST',
            data: form_data,
            success: function (response) {

                if (slug[slug.length - 1] == 'calendar') {

                    // Set folder selection
                    app.calendar.o.filter.folder_id = id;
                    app.calendar.o.instance.render();

                } else {
                    app.calendar.o.filter.folder_id = null;
                    app.page.switch('subscription');
                }

            },
            error: function (response) {
            },
            complete: function () {
            },
            processData: false,
            contentType: false,
        });

        return true;
    }

    filter_alert_profile(select_box, type) {
        select_box = $(select_box);
        select_box.find('option').attr('disabled', false);

        // Check if this is lifetime
        if (type == 3) {
            select_box.find('option[data-alert_subs_type!="' + type + '"]').attr('disabled', true);
        } else {
            select_box.find('option[data-alert_subs_type!="' + type + '"]:not(:first)').attr('disabled', true);
        }

        let selected_type = select_box.find(':selected').data('alert_subs_type');

        // if (selected_type != type && select_box.get(0) && select_box.get(0).selectedIndex) {
        if (selected_type != type) {
            select_box.prop('selectedIndex', (type == 3 ? 1 : 0));
        }

        select_box.multiselect('refresh');
    }

    create_type_check(el) {
        let type_el = $(el);
        let type = type_el.find(':selected').val();

        // Check if this is lifetime
        if (type == 3) {
            $(app.subscription.e.add.frequency).attr('disabled', true);
            $(app.subscription.e.add.cycle).attr('disabled', true);
            $(app.subscription.e.add.billing_type).attr('disabled', true);
            // $(app.subscription.e.add.recurring).attr('disabled', true);

            $(app.subscription.e.add.frequency).val($(app.subscription.e.add.frequency).find('option:first').val());
            $(app.subscription.e.add.cycle).val($(app.subscription.e.add.cycle).find('option:first').val());
            $(app.subscription.e.add.billing_type).prop('checked', false);
            // $(app.subscription.e.add.recurring).prop('checked', false);

            // Expiry date
            $(app.subscription.e.add.form).find('input[name="expiry_date"]').val('');
            $(app.subscription.e.add.form).find('input[name="expiry_date"]').closest('.form-group').hide();


            $(app.subscription.e.add.billing_container).hide();

            // Toggle disable
            if ($(app.subscription.e.add.recurring).is(':checked')) {
                $(app.subscription.e.add.recurring_toggle).click();
            }
            $(app.subscription.e.add.recurring_toggle).css('pointer-events', 'none');
        }

        // For other types
        else {
            $(app.subscription.e.add.frequency).attr('disabled', false);
            $(app.subscription.e.add.cycle).attr('disabled', false);
            $(app.subscription.e.add.billing_type).attr('disabled', false);
            // $(app.subscription.e.add.recurring).attr('disabled', false);

            // Expiry date
            $(app.subscription.e.add.form).find('input[name="expiry_date"]').closest('.form-group').show();

            $(app.subscription.e.add.recurring_toggle).css('pointer-events', '');
            $(app.subscription.e.add.billing_container).show();
        }

        app.subscription.filter_alert_profile('#subscription_add_alert_id', type);
        app.subscription.modal_title_change('create');
        app.subscription.create_recurring_check($('#subscription_add_recurring')[0]);
    }

    create_quick_type_check(el) {
        let type_el = $(el);
        let type = type_el.find(':selected').val();

        // Check if this is lifetime
        if (type == 3) {
            $(app.subscription.e.add_quick.frequency).attr('disabled', true);
            $(app.subscription.e.add_quick.cycle).attr('disabled', true);
            $(app.subscription.e.add.billing_type).attr('disabled', true);
            // $(app.subscription.e.add_quick.recurring).attr('disabled', true);

            $(app.subscription.e.add_quick.frequency).val($(app.subscription.e.add_quick.frequency).find('option:first').val());
            $(app.subscription.e.add_quick.cycle).val($(app.subscription.e.add_quick.cycle).find('option:first').val());
            $(app.subscription.e.add_quick.billing_type).prop('checked', false);
            // $(app.subscription.e.add_quick.recurring).prop('checked', false);

            $(app.subscription.e.add_quick.billing_container).hide();

            // Toggle disable
            if ($(app.subscription.e.add_quick.recurring).is(':checked')) {
                $(app.subscription.e.add_quick.recurring_toggle).click();
            }
            $(app.subscription.e.add_quick.recurring_toggle).css('pointer-events', 'none');
        }

        // For other types
        else {
            $(app.subscription.e.add_quick.frequency).attr('disabled', false);
            $(app.subscription.e.add_quick.cycle).attr('disabled', false);
            $(app.subscription.e.add_quick.billing_type).attr('disabled', false);
            // $(app.subscription.e.add_quick.recurring).attr('disabled', false);

            $(app.subscription.e.add_quick.recurring_toggle).css('pointer-events', '');
            $(app.subscription.e.add_quick.billing_container).show();
        }

        app.subscription.filter_alert_profile('#main_quick_add_alert_id', type);
        app.subscription.modal_title_change('create_quick');
        app.subscription.create_quick_recurring_check($('#main_quick_add_recurring')[0]);
    }

    update_type_check(el) {
        let type_el = $(el);
        let type = type_el.find(':selected').val();

        // Check if this is lifetime type
        if (type == 3) {
            $(app.subscription.e.edit.frequency).attr('disabled', true);
            $(app.subscription.e.edit.cycle).attr('disabled', true);
            $(app.subscription.e.edit.billing_type).attr('disabled', true);
            // $(app.subscription.e.edit.recurring).attr('disabled', true);

            $(app.subscription.e.edit.frequency).val($(app.subscription.e.edit.frequency).find('option:first').val());
            $(app.subscription.e.edit.cycle).val($(app.subscription.e.edit.cycle).find('option:first').val());
            $(app.subscription.e.edit.billing_type).prop('checked', false);
            // $(app.subscription.e.edit.recurring).prop('checked', false);

            // Expiry date
            $(app.subscription.e.edit.form).find('input[name="expiry_date"]').val('');
            $(app.subscription.e.edit.form).find('input[name="expiry_date"]').closest('.form-group').hide();

            $(app.subscription.e.edit.billing_container).hide();

            // Toggle disable
            if ($(app.subscription.e.edit.recurring_toggle).is(':checked')) {
                $(app.subscription.e.edit.recurring_toggle_container).click();
            }
            $(app.subscription.e.edit.recurring_toggle_container).css('pointer-events', 'none');
        }

        // For other types
        else {
            $(app.subscription.e.edit.frequency).attr('disabled', false);
            $(app.subscription.e.edit.cycle).attr('disabled', false);
            $(app.subscription.e.edit.billing_type).attr('disabled', false);
            // $(app.subscription.e.edit.recurring).attr('disabled', false);

            // Expiry date
            $(app.subscription.e.edit.form).find('input[name="expiry_date"]').closest('.form-group').show();

            $(app.subscription.e.edit.recurring_toggle_container).css('pointer-events', '');
            $(app.subscription.e.edit.billing_container).show();
        }

        // Billing cycle and frequency
        app.subscription.edit_recurring_check($(app.subscription.e.edit.recurring_toggle).get(0));

        app.subscription.filter_alert_profile('#subscription_edit_alert_id', type);
        app.subscription.modal_title_change('update');
        app.subscription.edit_recurring_check($('#subscription_edit_recurring_toggle')[0]);
    }

    clone_type_check(el) {
        let type_el = $(el);
        let type = type_el.find(':selected').val();

        // Check if this is lifetime
        if (type == 3) {
            $(app.subscription.e.clone.frequency).attr('disabled', true);
            $(app.subscription.e.clone.cycle).attr('disabled', true);
            $(app.subscription.e.clone.billing_type).attr('disabled', true);
            // $(app.subscription.e.clone.recurring).attr('disabled', true);

            $(app.subscription.e.clone.frequency).val($(app.subscription.e.clone.frequency).find('option:first').val());
            $(app.subscription.e.clone.cycle).val($(app.subscription.e.clone.cycle).find('option:first').val());
            $(app.subscription.e.clone.billing_type).prop('checked', false);
            // $(app.subscription.e.clone.recurring).prop('checked', false);

            // Expiry date
            $(app.subscription.e.clone.form).find('input[name="expiry_date"]').val('');
            $(app.subscription.e.clone.form).find('input[name="expiry_date"]').closest('.form-group').hide();

            $(app.subscription.e.clone.billing_container).hide();

            // Toggle disable
            if ($(app.subscription.e.clone.recurring_toggle).is(':checked')) {
                $(app.subscription.e.clone.recurring_toggle_container).click();
            }
            $(app.subscription.e.clone.recurring_toggle_container).css('pointer-events', 'none');
        }

        // For other types
        else {
            $(app.subscription.e.clone.frequency).attr('disabled', false);
            $(app.subscription.e.clone.cycle).attr('disabled', false);
            $(app.subscription.e.clone.billing_type).attr('disabled', false);
            // $(app.subscription.e.clone.recurring).attr('disabled', false);

            // Expiry date
            $(app.subscription.e.clone.form).find('input[name="expiry_date"]').closest('.form-group').show();

            $(app.subscription.e.clone.recurring_toggle_container).css('pointer-events', '');
            $(app.subscription.e.clone.billing_container).show();
        }

        // Billing cycle and frequency
        app.subscription.clone_recurring_check($(app.subscription.e.clone.recurring_toggle).get(0));

        app.subscription.filter_alert_profile('#subscription_clone_alert_id', type);
        app.subscription.modal_title_change('clone');
        app.subscription.clone_recurring_check($('#subscription_clone_recurring_toggle')[0]);
    }

    addon_type_check(el) {
        let type_el = $(el);
        let type = type_el.find(':selected').val();

        // Check if this is lifetime
        if (type == 3) {
            $(app.subscription.e.addon.frequency).attr('disabled', true);
            $(app.subscription.e.addon.cycle).attr('disabled', true);
            $(app.subscription.e.addon.billing_type).attr('disabled', true);
            // $(app.subscription.e.addon.recurring).attr('disabled', true);

            $(app.subscription.e.addon.frequency).val($(app.subscription.e.addon.frequency).find('option:first').val());
            $(app.subscription.e.addon.cycle).val($(app.subscription.e.addon.cycle).find('option:first').val());
            $(app.subscription.e.addon.billing_type).prop('checked', false);
            // $(app.subscription.e.addon.recurring).prop('checked', false);

            // Expiry date
            $(app.subscription.e.addon.form).find('input[name="expiry_date"]').val('');
            $(app.subscription.e.addon.form).find('input[name="expiry_date"]').closest('.form-group').hide();

            $(app.subscription.e.addon.billing_container).hide();

            // Toggle disable
            if ($(app.subscription.e.addon.recurring_toggle).is(':checked')) {
                $(app.subscription.e.addon.recurring_toggle_container).click();
            }
            $(app.subscription.e.addon.recurring_toggle_container).css('pointer-events', 'none');
        }

        // For other types
        else {
            $(app.subscription.e.addon.frequency).attr('disabled', false);
            $(app.subscription.e.addon.cycle).attr('disabled', false);
            $(app.subscription.e.addon.billing_type).attr('disabled', false);
            // $(app.subscription.e.addon.recurring).attr('disabled', false);

            // Expiry date
            $(app.subscription.e.addon.form).find('input[name="expiry_date"]').closest('.form-group').show();

            $(app.subscription.e.addon.recurring_toggle_container).css('pointer-events', '');
            $(app.subscription.e.addon.billing_container).show();
        }

        // Billing cycle and frequency
        app.subscription.addon_recurring_check($(app.subscription.e.addon.recurring_toggle).get(0));

        app.subscription.filter_alert_profile('#subscription_addon_alert_id', type);
        app.subscription.modal_title_change('addon');
    }

    reset_type_fields() {
        $(app.subscription.e.add.frequency).attr('disabled', false);
        $(app.subscription.e.add.cycle).attr('disabled', false);
        $(app.subscription.e.add.recurring).attr('disabled', false);
        $(app.subscription.e.add.billing_type).attr('disabled', false);
        $(app.subscription.e.add.recurring_toggle).css('pointer-events', '');
        $(app.subscription.e.add.billing_container).show();
        $(app.subscription.e.add.form).find('input[name="expiry_date"]').closest('.form-group').show();

        $(app.subscription.e.add_quick.frequency).attr('disabled', false);
        $(app.subscription.e.add_quick.cycle).attr('disabled', false);
        $(app.subscription.e.add_quick.recurring).attr('disabled', false);
        $(app.subscription.e.add_quick.billing_type).attr('disabled', false);
        $(app.subscription.e.add_quick.recurring_toggle).css('pointer-events', '');
        $(app.subscription.e.add_quick.billing_container).show();

        $(app.subscription.e.edit.frequency).attr('disabled', false);
        $(app.subscription.e.edit.cycle).attr('disabled', false);
        $(app.subscription.e.edit.recurring).attr('disabled', false);
        $(app.subscription.e.edit.billing_type).attr('disabled', false);
        $(app.subscription.e.edit.billing_container).show();
        $(app.subscription.e.edit.form).find('input[name="expiry_date"]').closest('.form-group').show();

        // $('#subscription_add_company_type_label').text('');
        // $('#subscription_edit_company_type_label').text('');
        // $('#main_quick_add_company_type_label').text('');
        // $('#subscription_clone_company_type_label').text('');

        app.subscription.filter_alert_profile('#subscription_add_alert_id', 1);
        app.subscription.filter_alert_profile('#main_quick_add_alert_id', 1);
        app.subscription.filter_alert_profile('#subscription_edit_alert_id', 1);
        app.subscription.filter_alert_profile('#subscription_clone_alert_id', 1);

        setTimeout(function () {
            $('#subscription_add_alert_id').multiselect('refresh');
            $('#main_quick_add_alert_id').multiselect('refresh');
            $('#subscription_edit_alert_id').multiselect('refresh');
            $('#subscription_clone_alert_id').multiselect('refresh');
        }, 1000);


        $(app.subscription.e.clone.recurring_toggle_container).css('pointer-events', '');

        $('img.favicon').attr('src', app.config.favicon_url);

        app.subscription.modal_title_change('reset');

    }






    create_recurring_check(el) {
        let recurring = el.checked;

        // Check if this is once
        if (recurring == false) {
            $(app.subscription.e.add.frequency).attr('disabled', true);
            $(app.subscription.e.add.cycle).attr('disabled', true);
            $(app.subscription.e.add.billing_type).attr('disabled', true);
            // $(app.subscription.e.add.recurring).attr('disabled', true);

            $(app.subscription.e.add.frequency).val($(app.subscription.e.add.frequency).find('option:first').val());
            $(app.subscription.e.add.cycle).val($(app.subscription.e.add.cycle).find('option:first').val());
            $(app.subscription.e.add.billing_type).prop('checked', false);
            // $(app.subscription.e.add.recurring).prop('checked', false);

            // $(app.subscription.e.add.billing_container).hide();

            // Toggle disable
            // if ($(app.subscription.e.add.recurring).is(':checked')) {
            //     $(app.subscription.e.add.recurring_toggle).click();
            // }
            // $(app.subscription.e.add.recurring_toggle).css('pointer-events', 'none');
        }

        // For recurring
        else {
            $(app.subscription.e.add.frequency).attr('disabled', false);
            $(app.subscription.e.add.cycle).attr('disabled', false);
            $(app.subscription.e.add.billing_type).attr('disabled', false);
            // $(app.subscription.e.add.recurring).attr('disabled', false);

            // $(app.subscription.e.add.recurring_toggle).css('pointer-events', '');
            // $(app.subscription.e.add.billing_container).show();
        }

        // Reset tooltip index
        lib.do.billing_toggle_switch(app.subscription.e.add.billing_type);

        // app.subscription.filter_alert_profile('#subscription_add_alert_id', recurring);
        app.subscription.modal_title_change('create');
    }

    create_quick_recurring_check(el) {
        let recurring = el.checked;

        // Check if this is once
        if (recurring == false) {
            $(app.subscription.e.add_quick.frequency).attr('disabled', true);
            $(app.subscription.e.add_quick.cycle).attr('disabled', true);
            $(app.subscription.e.add_quick.billing_type).attr('disabled', true);
            // $(app.subscription.e.add_quick.recurring).attr('disabled', true);

            $(app.subscription.e.add_quick.frequency).val($(app.subscription.e.add_quick.frequency).find('option:first').val());
            $(app.subscription.e.add_quick.cycle).val($(app.subscription.e.add_quick.cycle).find('option:first').val());
            $(app.subscription.e.add_quick.billing_type).prop('checked', false);
            // $(app.subscription.e.add_quick.recurring).prop('checked', false);

            // $(app.subscription.e.add_quick.billing_container).hide();

            // Toggle disable
            // if ($(app.subscription.e.add_quick.recurring).is(':checked')) {
            //     $(app.subscription.e.add_quick.recurring_toggle).click();
            // }
            // $(app.subscription.e.add_quick.recurring_toggle).css('pointer-events', 'none');
        }

        // For recurring
        else {
            $(app.subscription.e.add_quick.frequency).attr('disabled', false);
            $(app.subscription.e.add_quick.cycle).attr('disabled', false);
            $(app.subscription.e.add_quick.billing_type).attr('disabled', false);
            // $(app.subscription.e.add_quick.recurring).attr('disabled', false);

            // $(app.subscription.e.add_quick.recurring_toggle).css('pointer-events', '');
            // $(app.subscription.e.add_quick.billing_container).show();
        }

        // Reset tooltip index
        lib.do.billing_toggle_switch(app.subscription.e.add_quick.billing_type);

        // app.subscription.filter_alert_profile('#main_quick_add_alert_id', recurring);
        app.subscription.modal_title_change('create_quick');
    }

    edit_recurring_check(el) {
        let recurring = el.checked;

        // Check if this is once
        if (recurring == false) {
            $(app.subscription.e.edit.frequency).attr('disabled', true);
            $(app.subscription.e.edit.cycle).attr('disabled', true);
            $(app.subscription.e.edit.billing_type).attr('disabled', true);
            // $(app.subscription.e.edit.recurring).attr('disabled', true);

            $(app.subscription.e.edit.frequency).val($(app.subscription.e.edit.frequency).find('option:first').val());
            $(app.subscription.e.edit.cycle).val($(app.subscription.e.edit.cycle).find('option:first').val());
            $(app.subscription.e.edit.billing_type).prop('checked', false);
            // $(app.subscription.e.edit.recurring).prop('checked', false);

            // $(app.subscription.e.edit.billing_container).hide();

            // Toggle disable
            // if ($(app.subscription.e.edit.recurring_toggle).is(':checked')) {
            //     $(app.subscription.e.edit.recurring_toggle_container).click();
            // }
            // $(app.subscription.e.edit.recurring_toggle_container).css('pointer-events', 'none');
        }

        // For recurring
        else {
            $(app.subscription.e.edit.frequency).attr('disabled', false);
            $(app.subscription.e.edit.cycle).attr('disabled', false);
            $(app.subscription.e.edit.billing_type).attr('disabled', false);
            // $(app.subscription.e.edit.recurring).attr('disabled', false);

            // $(app.subscription.e.edit.recurring_toggle_container).css('pointer-events', '');
            // $(app.subscription.e.edit.billing_container).show();
        }

        // Reset tooltip index
        lib.do.billing_toggle_switch(app.subscription.e.edit.billing_type);

        // app.subscription.filter_alert_profile('#subscription_edit_alert_id', recurring);
        app.subscription.modal_title_change('update');
    }

    clone_recurring_check(el) {
        let recurring = el.checked;

        // Check if this is once
        if (recurring == false) {
            $(app.subscription.e.clone.frequency).attr('disabled', true);
            $(app.subscription.e.clone.cycle).attr('disabled', true);
            $(app.subscription.e.clone.billing_type).attr('disabled', true);
            // $(app.subscription.e.clone.recurring).attr('disabled', true);

            $(app.subscription.e.clone.frequency).val($(app.subscription.e.clone.frequency).find('option:first').val());
            $(app.subscription.e.clone.cycle).val($(app.subscription.e.clone.cycle).find('option:first').val());
            $(app.subscription.e.clone.billing_type).prop('checked', false);
            // $(app.subscription.e.clone.recurring).prop('checked', false);

            // $(app.subscription.e.clone.billing_container).hide();

            // Toggle disable
            // if ($(app.subscription.e.clone.recurring_toggle).is(':checked')) {
            //     $(app.subscription.e.clone.recurring_toggle_container).click();
            // }
            // $(app.subscription.e.clone.recurring_toggle_container).css('pointer-events', 'none');
        }

        // For recurring
        else {
            $(app.subscription.e.clone.frequency).attr('disabled', false);
            $(app.subscription.e.clone.cycle).attr('disabled', false);
            $(app.subscription.e.clone.billing_type).attr('disabled', false);
            // $(app.subscription.e.clone.recurring).attr('disabled', false);

            // $(app.subscription.e.clone.recurring_toggle_container).css('pointer-events', '');
            // $(app.subscription.e.clone.billing_container).show();
        }

        // Reset tooltip index
        lib.do.billing_toggle_switch(app.subscription.e.clone.billing_type);

        // app.subscription.filter_alert_profile('#subscription_clone_alert_id', recurring);
        app.subscription.modal_title_change('clone');
    }

    addon_recurring_check(el) {
        let recurring = el.checked;

        // Check if this is once
        if (recurring == false) {
            $(app.subscription.e.addon.frequency).attr('disabled', true);
            $(app.subscription.e.addon.cycle).attr('disabled', true);
            $(app.subscription.e.addon.billing_type).attr('disabled', true);
            // $(app.subscription.e.addon.recurring).attr('disabled', true);

            $(app.subscription.e.addon.frequency).val($(app.subscription.e.addon.frequency).find('option:first').val());
            $(app.subscription.e.addon.cycle).val($(app.subscription.e.addon.cycle).find('option:first').val());
            $(app.subscription.e.addon.billing_type).prop('checked', false);
            // $(app.subscription.e.addon.recurring).prop('checked', false);

            // $(app.subscription.e.addon.billing_container).hide();

            // Toggle disable
            // if ($(app.subscription.e.addon.recurring_toggle).is(':checked')) {
            //     $(app.subscription.e.addon.recurring_toggle_container).click();
            // }
            // $(app.subscription.e.addon.recurring_toggle_container).css('pointer-events', 'none');
        }

        // For recurring
        else {
            $(app.subscription.e.addon.frequency).attr('disabled', false);
            $(app.subscription.e.addon.cycle).attr('disabled', false);
            $(app.subscription.e.addon.billing_type).attr('disabled', false);
            // $(app.subscription.e.addon.recurring).attr('disabled', false);

            // $(app.subscription.e.addon.recurring_toggle_container).css('pointer-events', '');
            // $(app.subscription.e.addon.billing_container).show();
        }

        // Reset tooltip index
        lib.do.billing_toggle_switch(app.subscription.e.addon.billing_type);

        // app.subscription.filter_alert_profile('#subscription_addon_alert_id', recurring);
        app.subscription.modal_title_change('addon');
    }

    update_recurring_check(el) {
        let recurring = el.checked;

        // Check if this is once
        if (recurring == false) {
            $(app.subscription.e.edit.frequency).attr('disabled', true);
            $(app.subscription.e.edit.cycle).attr('disabled', true);
            $(app.subscription.e.edit.billing_type).attr('disabled', true);
            // $(app.subscription.e.edit.recurring).attr('disabled', true);

            $(app.subscription.e.edit.frequency).val($(app.subscription.e.edit.frequency).find('option:first').val());
            $(app.subscription.e.edit.cycle).val($(app.subscription.e.edit.cycle).find('option:first').val());
            $(app.subscription.e.edit.billing_type).prop('checked', false);
            // $(app.subscription.e.edit.recurring).prop('checked', false);

            // $(app.subscription.e.edit.billing_container).hide();

            // Toggle disable
            // if ($(app.subscription.e.edit.recurring_toggle).is(':checked')) {
            //     $(app.subscription.e.edit.recurring_toggle_container).click();
            // }
            // $(app.subscription.e.edit.recurring_toggle_container).css('pointer-events', 'none');
        }

        // For recurring
        else {
            $(app.subscription.e.edit.frequency).attr('disabled', false);
            $(app.subscription.e.edit.cycle).attr('disabled', false);
            $(app.subscription.e.edit.billing_type).attr('disabled', false);
            // $(app.subscription.e.edit.recurring).attr('disabled', false);

            // $(app.subscription.e.edit.recurring_toggle_container).css('pointer-events', '');
            // $(app.subscription.e.edit.billing_container).show();
        }

        // app.subscription.filter_alert_profile('#subscription_edit_alert_id', type);
        app.subscription.modal_title_change('update');
    }







    load_page() {
        let slug = app.page.get_slug();

        if (slug == 'calendar') {
            app.page.switch('calendar');
        } else {
            // app.page.switch('subscription');
            $('#tbl_subscription').DataTable().ajax.reload(null, false);
        }
    }




    load_chart(type, days) {
        let form_data = new FormData();
        form_data.append('_token', app.config.token);
        form_data.append('type', type);
        form_data.append('days', days);

        $.ajax({
            url: app.url + 'subscription/chart',
            type: 'POST',
            data: form_data,
            dataType: 'json',
            beforeSend: function (xhr) {
                // app.loading.btn(ctl);
            },
            success: function (response) {
                if (response.status) {
                    // app.subscription.o.subscription.chart.data

                    // Subscription
                    if (type == 1) {
                        app.subscription.o.subscription.chart.data = response.data.subs_chart;
                    }

                    // Lifetime
                    else if (type == 3) {
                        app.subscription.o.lifetime.chart.data = response.data.ltd_chart;
                    }

                    app.subscription.render_chart(type);
                } else {
                    app.alert.validation(response.message);
                }
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

    render_chart(type) {
        return;

        // Subscription
        if (type == 1) {
            if (app.subscription.o.subscription.chart.instance) {
                app.subscription.o.subscription.chart.instance.destroy();
            }

            app.subscription.o.subscription.chart.config = JSON.parse(JSON.stringify(app.subscription.d.subscription.chart));
            app.subscription.o.subscription.chart.config.series[0].data = app.subscription.o.subscription.chart.data.prices;
            app.subscription.o.subscription.chart.config.labels = app.subscription.o.subscription.chart.data.dates;
            app.subscription.o.subscription.chart.instance = new ApexCharts(document.querySelector(app.subscription.e.subscription.chart), app.subscription.o.subscription.chart.config);
            app.subscription.o.subscription.chart.instance.render();
        }

        // Lifetime
        else if (type == 3) {
            if (app.subscription.o.lifetime.chart.instance) {
                app.subscription.o.lifetime.chart.instance.destroy();
            }

            app.subscription.o.lifetime.chart.config = JSON.parse(JSON.stringify(app.subscription.d.lifetime.chart));
            app.subscription.o.lifetime.chart.config.series[0].data = app.subscription.o.lifetime.chart.data.prices;
            app.subscription.o.lifetime.chart.config.labels = app.subscription.o.lifetime.chart.data.dates;
            app.subscription.o.lifetime.chart.instance = new ApexCharts(document.querySelector(app.subscription.e.lifetime.chart), app.subscription.o.lifetime.chart.config);
            app.subscription.o.lifetime.chart.instance.render();
        } else {

            if (app.subscription.o.subscription.chart.instance) {
                app.subscription.o.subscription.chart.instance.destroy();
            }

            app.subscription.o.subscription.chart.config = JSON.parse(JSON.stringify(app.subscription.d.subscription.chart));
            app.subscription.o.subscription.chart.config.series[0].data = app.subscription.o.subscription.chart.data.prices;
            app.subscription.o.subscription.chart.config.labels = app.subscription.o.subscription.chart.data.dates;
            app.subscription.o.subscription.chart.instance = new ApexCharts(document.querySelector(app.subscription.e.subscription.chart), app.subscription.o.subscription.chart.config);
            app.subscription.o.subscription.chart.instance.render();

            app.subscription.o.lifetime.chart.config = JSON.parse(JSON.stringify(app.subscription.d.lifetime.chart));
            app.subscription.o.lifetime.chart.config.series[0].data = app.subscription.o.lifetime.chart.data.prices;
            app.subscription.o.lifetime.chart.config.labels = app.subscription.o.lifetime.chart.data.dates;
            app.subscription.o.lifetime.chart.instance = new ApexCharts(document.querySelector(app.subscription.e.lifetime.chart), app.subscription.o.lifetime.chart.config);
            app.subscription.o.lifetime.chart.instance.render();
        }
    }

    koolreport_chart_load(type, days) {

        // Subscription
        if (type == 1) {
            let form_data = new FormData();
            form_data.append('_token', app.config.token);
            form_data.append('type', type);
            form_data.append('days', days);

            $.ajax({
                url: app.url + 'subscription/koolreport/area_chart',
                type: 'POST',
                data: form_data,
                dataType: 'json',
                beforeSend: function (xhr) {
                    // app.loading.btn(ctl);
                },
                success: function (response) {
                    app.subscription.set_chart_data(1, response);
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

        // Lifetime
        else if (type == 3) {
            let form_data = new FormData();
            form_data.append('_token', app.config.token);
            form_data.append('type', type);
            form_data.append('days', days);

            $.ajax({
                url: app.url + 'subscription/koolreport/lifetime_drilldown_chart_all_time',
                type: 'POST',
                data: form_data,
                dataType: 'json',
                beforeSend: function (xhr) {
                    // app.loading.btn(ctl);
                },
                success: function (response) {
                    app.subscription.set_chart_data(3, response);
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
        } else {
            let form_data = new FormData();
            form_data.append('_token', app.config.token);
            form_data.append('type', type);
            form_data.append('days', days);

            $.ajax({
                url: app.url + 'subscription/koolreport/area_chart',
                type: 'POST',
                data: form_data,
                dataType: 'json',
                beforeSend: function (xhr) {
                    // app.loading.btn(ctl);
                },
                success: function (response) {
                    app.subscription.set_chart_data(1, response);
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
    }

    set_chart_data(type, data) {
        // Subscription
        if (type == 1) {

            // Set the values
            $(app.subscription.e.chart.subscription_total_count).text(data.subscription.total_count);
            $(app.subscription.e.chart.subscription_active_count).text(data.subscription.total_count);
            $(app.subscription.e.chart.subscription_monthly_price).text('$' + data.subscription.monthly_price);
            $(app.subscription.e.chart.subscription_total_price).text('$' + data.subscription.total_price);

            // Render the chart
            $(app.subscription.e.koolreport.area_chart).html(data.subscription.chart_html);
        }

        // Lifetime
        else if (type == 3) {

            // Set the values
            $(app.subscription.e.chart.lifetime_total_count).text(data.lifetime.total_count);
            $(app.subscription.e.chart.lifetime_active_count).text(data.lifetime.total_count);
            $(app.subscription.e.chart.lifetime_this_year_price).text('$' + data.lifetime.this_year_price);
            $(app.subscription.e.chart.lifetime_total_price).text('$' + data.lifetime.total_price);

            // Render the chart
            $(app.subscription.e.koolreport.drilldown).html(data.lifetime.chart_html);
        }
    }

    modal_title_change(modal) {
        switch (modal) {
            case 'create':

                var type = $('#subscription_add_type').find(':selected').text();
                var product = $('#subscription_add_brand_id').children('option:selected').text();

                if (product) {
                    if (type) {
                        $('#modal_subscription_add_title').text(lang('Add') + ' ' + product + ' ' + type);
                    } else {
                        $('#modal_subscription_add_title').text(lang('Add') + ' ' + product);
                    }
                } else {
                    $('#modal_subscription_add_title').text(lang('Add Subscription'));
                }

                break;

            case 'create_quick':

                var type = $('#main_quick_add_type').find(':selected').text();
                var product = $('#main_quick_add_brand_id').children('option:selected').text();

                if (product) {
                    if (type) {
                        $('#modal_subscription_quick_add_title').text(lang('Add') + ' ' + product + ' ' + type);
                    } else {
                        $('#modal_subscription_quick_add_title').text(lang('Add') + ' ' + product);
                    }
                } else {
                    $('#modal_subscription_quick_add_title').text(lang('Add Subscription (Quick)'));
                }

                break;

            case 'update':

                var type = $('#subscription_edit_type').find(':selected').text();
                var product = $('#subscription_edit_brand_id').children('option:selected').text();

                if (product) {
                    if (type) {
                        $('#modal_subscription_edit_title').text(lang('Update') + ' ' + product + ' ' + type);
                    } else {
                        $('#modal_subscription_edit_title').text(lang('Update') + ' ' + product);
                    }
                } else {
                    $('#modal_subscription_edit_title').text(lang('Subscription Update'));
                }

                break;

            case 'clone':

                var type = $('#subscription_clone_type').find(':selected').text();
                var product = $('#subscription_clone_brand_id').children('option:selected').text();

                if (product) {
                    if (type) {
                        $('#modal_subscription_clone_title').text(lang('Clone') + ' ' + product + ' ' + type);
                    } else {
                        $('#modal_subscription_clone_title').text(lang('Clone') + ' ' + product);
                    }
                } else {
                    $('#modal_subscription_clone_title').text(lang('Subscription Clone'));
                }

                break;

            case 'addon':
                // Lifetime addon

                var type = $('#subscription_addon_type').find(':selected').text();
                var product = $('#subscription_addon_brand_id').children('option:selected').text();

                if (product) {
                    if (type) {
                        $('#modal_subscription_addon_title').text(lang('Addon for') + ' ' + product + ' ' + type);
                    } else {
                        $('#modal_subscription_addon_title').text(lang('Addon for') + ' ' + product);
                    }
                } else {
                    $('#modal_subscription_addon_title').text(lang('Lifetime Addon'));
                }

                break;

            default:
                $('#modal_subscription_add_title').text(lang('Add Subscription'));
                $('#modal_subscription_quick_add_title').text(lang('Add Subscription (Quick)'));
                $('#modal_subscription_edit_title').text(lang('Subscription Update'));
                $('#modal_subscription_clone_title').text(lang('Subscription Clone'));
                $('#modal_subscription_addon_title').text(lang('Lifetime Addon'));
                return false;
        }
    }

    update_popup_refund(el) {
        let id = $(el).closest('.modal').find('form input[name="id"]');

        if (!id.length) {
            return false;
        }

        app.subscription.refund(id.val());
    }

    update_popup_clone(el) {
        let id = $(el).closest('.modal').find('form input[name="id"]');

        if (!id.length) {
            return false;
        }

        $('#modal_subscription_edit').modal('hide');
        app.subscription.clone(id.val());
    }

    update_popup_delete(el) {
        let id = $(el).closest('.modal').find('form input[name="id"]');

        if (!id.length) {
            return false;
        }

        app.subscription.delete(id.val());
    }

    update_popup_cancel(el) {
        let id = $(el).closest('.modal').find('form input[name="id"]');

        if (!id.length) {
            return false;
        }

        app.subscription.cancel(id.val());
    }

    update_popup_addon(el) {
        let id = $(el).closest('.modal').find('form input[name="id"]');

        if (!id.length) {
            return false;
        }

        $('#modal_subscription_edit').modal('hide');
        app.subscription.addon(id.val());
    }





    set_datatable_page_length(length) {
        let form_data = new FormData();
        form_data.append('_token', app.config.token);
        form_data.append('length', length);

        $.ajax({
            url: app.url + 'subscription/session/set_datatable_page_length',
            type: 'POST',
            data: form_data,
            dataType: 'json',
            beforeSend: function (xhr) {
                // app.loading.btn(ctl);
            },
            success: function (response) {
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

    koolreport_both_charts_load() {
        if (window.location.pathname == '/user/email/verify') {
            return;
        }
        let form_data = new FormData();
        form_data.append('_token', app.config.token);
        // form_data.append('type', type);
        // form_data.append('days', days);

        $.ajax({
            url: app.url + 'subscription/koolreport/both_charts',
            type: 'POST',
            data: form_data,
            dataType: 'json',
            beforeSend: function (xhr) {
                // app.loading.btn(ctl);
            },
            success: function (response) {
                app.subscription.set_chart_data(1, response);
                app.subscription.set_chart_data(3, response);
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

    load_lifetime_drilldown_chart(level, year, month_name = null) {
        let form_data = new FormData();
        form_data.append('_token', app.config.token);
        // form_data.append('period', app.subscription.o.filter.period);
        form_data.append('level', level);
        form_data.append('year', year);
        form_data.append('month_name', month_name);

        app.subscription.o.ajax.current_request = $.ajax({
            url: app.url + 'subscription/koolreport/lifetime_drilldown_chart_inside',
            type: 'POST',
            data: form_data,
            dataType: 'json',
            beforeSend: function (xhr) {

                // Cancel the previous request
                if (app.subscription.o.ajax.current_request != null) {
                    app.subscription.o.ajax.current_request.abort();
                }

                // app.loading.btn(ctl);
                // app.loading.block(app.subscription.e.chart.container);
            },
            success: function (response) {
                // $(app.subscription.e.lifetime.drilldown).html(response.html);

                // Render the chart
                $(app.subscription.e.koolreport.drilldown).html(response.html);
            },
            error: function (response) {
                // app.alert.response(response);
            },
            complete: function () {
                app.loading.unblock(app.subscription.e.chart.container);
                // app.loading.btn(ctl);
            },
            processData: false,
            contentType: false,
        });
    }

    history(ctl) {
        let id = lib.get_id(ctl);
        if (!id) {
            return false;
        }

        let form_data = new FormData();
        form_data.append('_token', app.config.token);
        form_data.append('id', id);

        $.ajax({
            url: app.url + 'subscription/history/' + id,
            type: 'POST',
            data: form_data,
            beforeSend: function (xhr) {
                app.loading.btn(ctl);
            },
            success: function (response) {
                if (response) {
                    $('#modal_subscription_history .modal-content').html(response);
                    $('#modal_subscription_history').modal();
                    app.load.tooltip();
                }
            },
            error: function (response) {
                app.alert.response(response);
            },
            complete: function () {
                app.loading.btn(ctl);
            },
            processData: false,
            contentType: false,
        });
    }
}
