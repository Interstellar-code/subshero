@extends(request()->ajax() ? 'client/layouts/ajax' : 'client/layouts/default')

@section('head')
@endsection

@section('content')
    <form class="row" id="frm_subscription_clone" action="{{ route('app/subscription/create') }}" method="POST">
        @csrf
        <input type="hidden" name="id" id="subscription_clone_id" value="{{ $data->id }}">
        <input type="hidden" name="image_path" id="subscription_clone_image_path" value="{{ $data->_image }}">
        <input type="hidden" name="img_path_or_file" id="subscription_clone_img_path_or_file" value="0">
        <input type="hidden" name="status" id="subscription_clone_status" value="{{ $data->status > 1 ? 1 : $data->status }}">
        <input type="hidden" name="recurring" id="subscription_clone_recurring" value="{{ $data->recurring }}">
        <input type="hidden" name="rating" id="subscription_clone_rating" value="{{ $data->rating }}">

        <div class="col-md-6 col-xl-3">
            <div class="position-relative form-group" data-toggle="tooltip" data-placement="left" title="@lang('Select Type')">
                <label for="subscription_clone_type" class="">@lang('Type')</label>
                <select name="type" id="subscription_clone_type" onchange="app.subscription.clone_type_check(this);" class="form-control" required>
                    @foreach (table('subscription.type') as $key => $val)
                        <option value="{{ $key }}" {{ $data->type == $key ? 'selected' : null }}>@lang($val)</option>
                    @endforeach
                </select>
            </div>
            <div class="position-relative form-group" data-toggle="tooltip" data-placement="left" title="@lang('Select Folder')">
                <label for="subscription_clone_folder_id" class="">@lang('Folder')</label>
                <select name="folder_id" id="subscription_clone_folder_id" class="form-control">
                    <option selected="" disabled="" value="" style="display: none;">@lang('Select')</option>
                    @foreach (lib()->folder->get_by_user() as $val)
                        <option value="{{ $val->id }}" {{ $data->folder_id == $val->id ? 'selected' : null }}>{{ $val->name }} {{ lib()->config->currency_symbol[$val->price_type] ?? 'All' }}</option>
                    @endforeach
                </select>
            </div>
            <div class="position-relative form-group" data-toggle="tooltip" data-placement="left" title="@lang('Add tags')">
                <label for="subscription_clone_tags" class="">@lang('Tags')</label>
                <select name="tags[]" id="subscription_clone_tags" class="form-control select2_init_multi" multiple>
                    @foreach (lib()->tag->get_by_user() as $val)
                        <option value="{{ $val->id }}" {{ isset($data_tags[$val->id]) ? 'selected' : null }}>{{ $val->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="position-relative form-group text-center mx-auto" style="max-width: 200px; max-height: 200px; min-height: 200px;" data-toggle="tooltip" data-placement="left" title="@lang('Browser image')">
                <input type="file" class="filepond" id="subscription_clone_image_file" name="image" accept="image/*" data-size="200x200" style="display: none;">
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="position-relative form-group" data-toggle="tooltip" data-placement="top" title="@lang('Select Company Name')">
                <label for="subscription_clone_brand_id" class="">@lang('Company name')</label>
                <br>
                <select name="brand_id" id="subscription_clone_brand_id" class="form-control select2_init_tags">
                    {{-- <option value=""></option> --}}

                    @php
                        if ($data->brand_id <= PRODUCT_RESERVED_ID) {
                            echo '<option value="' . $data->product_name . '" selected>' . $data->product_name . '</option>';
                        } else {
                            echo '<option value="' . $data->brand_id . '" selected>' . $data->product_name . '</option>';
                        }
                    @endphp

                    {{-- @foreach (lib()->product->get_all_except_default() as $val)
                        <option value="{{ $val->id }}" data-path="{{ $val->image }}" data-product_type="{{ $val->product_type_name }}" data-description="{{ $val->description }}" data-price1_value="{{ $val->price1_value }}" data-currency_code="{{ $val->currency_code }}" data-url="{{ $val->url }}" data-billing_frequency="{{ $val->billing_frequency }}" data-billing_cycle="{{ $val->billing_cycle }}" data-pricing_type="{{ $val->pricing_type }}" data-favicon="{{ img_link($val->favicon) }}" data-category_id="{{ $val->category_id }}" data-ltdval_price="{{ $val->ltdval_price }}" data-ltdval_cycle="{{ $val->ltdval_cycle }}" data-ltdval_frequency="{{ $val->ltdval_frequency }}" data-refund_days="{{ $val->refund_days }}" {{ $data->brand_id > PRODUCT_RESERVED_ID && $data->brand_id == $val->id ? 'selected' : null }}>{{ $val->product_name }}</option>
                    @endforeach --}}
                </select>
            </div>
            <div class="position-relative form-group">
                <label for="subscription_clone_url" class="">@lang('URL')</label>
                <input name="url" id="subscription_clone_url" value="{{ $data->url }}" type="url" class="form-control" onchange="app.ui.modal_favicon(this);" data-toggle="tooltip" data-placement="top" title="@lang('Website URL')">
            </div>
            <div class="position-relative form-group">
                <label for="subscription_clone_discount_voucher" class="">@lang('Discount Voucher')</label>
                <input name="discount_voucher" id="subscription_clone_discount_voucher" value="{{ $data->discount_voucher }}" type="text" class="form-control" data-toggle="tooltip" data-placement="top" title="@lang('Enter Discount Voucher')">
            </div>
            <div class="position-relative form-group">
                <label for="subscription_clone_payment_mode_id" class="">@lang('Payment mode')</label>
                <select name="payment_mode_id" id="subscription_clone_payment_mode_id" class="form-control" required data-toggle="tooltip" data-placement="top" title="@lang('Select Payment mode ')">
                    <option selected="" disabled="" value="" style="display: none;">@lang('Select')</option>
                    @foreach (lib()->user->payment_methods as $val)
                        <option value="{{ $val->id }}" {{ $data->payment_mode_id == $val->id ? 'selected' : null }}>{{ $val->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="position-relative form-group">
                <label for="subscription_clone_support_details" class="">@lang('Support Details')</label>
                <input name="support_details" id="subscription_clone_support_details" value="{{ $data->support_details }}" type="text" class="form-control" data-toggle="tooltip" data-placement="top" title="@lang('Enter Support Details')">
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="position-relative form-group" data-toggle="tooltip" data-placement="top" title="@lang('Set Payment Date')">
                <label for="subscription_clone_payment_date" class="">@lang('Payment Date')</label>
                <div class="input-group">
                    <input name="payment_date" id="subscription_clone_payment_date" value="{{ $data->payment_date }}" type="text" placeholder="yyyy-mm-dd" maxlength="{{ len()->subscriptions->payment_date }}" class="form-control" data-toggle="datepicker-and-icon" required>
                    <div class="input-group-append datepicker-trigger">
                        <div class="input-group-text">
                            <i class="fa fa-calendar-alt"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div id="subscription_clone_billing_container" class="position-relative form-group">

                <div class="switch_container">
                    <label for="subscription_clone_billing_frequency" class="">@lang('Billing Cycle')</label>

                    <label class="switch ml-2" data-toggle="tooltip" data-placement="right" title="{{ lib()->lang->get_billing_type($data->billing_type) }}">
                        <input type="checkbox" name="billing_type" value="2" onclick="lib.do.billing_toggle_switch(this);" {{ $data->billing_type == 2 ? 'checked' : null }}>
                        <span class="slider round"></span>
                    </label>
                </div>

                <div class="input-group">
                    <div class="input-group-prepend">
                        <label for="subscription_clone_billing_frequency" class="input-group-text">@lang('Every')</span>
                    </div>
                    <select name="billing_frequency" id="subscription_clone_billing_frequency" {{ $data->type == 3 ? 'disabled' : null }} class="form-control pr-0" required data-toggle="tooltip" data-placement="top" title="@lang('Set Billing Frequency')">
                        @for ($i = 1; $i <= 40; $i++)
                            <option value="{{ $i }}" {{ $data->billing_frequency == $i ? 'selected' : null }}>@lang($i)</option>
                        @endfor
                    </select>
                    <select name="billing_cycle" id="subscription_clone_billing_cycle" {{ $data->type == 3 ? 'disabled' : null }} class="form-control" required data-toggle="tooltip" data-placement="top" title="@lang('Set Billing Cycle')">
                        @foreach (table('subscription.cycle') as $key => $val)
                            <option value="{{ $key }}" {{ $data->billing_cycle == $key ? 'selected' : null }}>@lang($val)</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="position-relative form-group">
                <label for="subscription_clone_price" class="">@lang('Price')</label>
                <div class="input-group">
                    <input name="price" id="subscription_clone_price" value="{{ $data->price }}" min="0" type="number" class="form-control" placeholder="@lang('0.00')" required data-toggle="tooltip" data-placement="top" title="@lang('Set Price')">
                    <select name="price_type" id="subscription_clone_price_type" class="form-control text-center" required data-toggle="tooltip" data-placement="top" title="@lang('Select Currency Code')">
                        <option selected="" disabled="" value="" style="display: none;">@lang('Select')</option>
                        @foreach (lib()->config->currency as $val)
                            <option value="{{ $val['code'] }}" {{ $data->price_type == $val['code'] ? 'selected' : null }}>{{ $val['code'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="card mb-2 subscription_ltdval_card p-0">
                <div class="row">
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="subscription_clone_ltdval_price" class="">@lang('Market Recurring Price')</label>
                            <input name="ltdval_price" id="subscription_clone_ltdval_price" value="{{ $data->ltdval_price }}" min="0" type="number" class="form-control" placeholder="@lang('0.00')" data-toggle="tooltip" data-placement="top" title="@lang('Set Price')">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="subscription_clone_ltdval_frequency" class="">@lang('MRP Billing Cycle')</label>

                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <label for="subscription_clone_ltdval_frequency" class="input-group-text">@lang('Every')</label>
                                </div>
                                <select name="ltdval_frequency" id="subscription_clone_ltdval_frequency" class="form-control pr-0" data-toggle="tooltip" data-placement="top" title="@lang('Set Billing Frequency')">
                                    <option selected="" disabled="" value="" style="display: none;">@lang('Select')</option>
                                    @for ($i = 1; $i <= 40; $i++)
                                        <option value="{{ $i }}" {{ $data->ltdval_frequency == $i ? 'selected' : null }}>@lang($i)</option>
                                    @endfor
                                </select>
                                <select name="ltdval_cycle" id="subscription_clone_ltdval_cycle" class="form-control" data-toggle="tooltip" data-placement="top" title="@lang('Set Billing Cycle')">
                                    <option selected="" disabled="" value="" style="display: none;">@lang('Select')</option>
                                    @foreach (table('subscription.cycle') as $key => $val)
                                        <option value="{{ $key }}" {{ $data->ltdval_cycle == $key ? 'selected' : null }}>@lang($val)</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="position-relative form-group">

                <div class="switch_container">
                    <label for="subscription_clone_note" class="">@lang('Notes')</label>
                    <label class="switch ml-2" data-toggle="tooltip" data-placement="right" title="@lang('Include Notes in alert')">
                        <input type="checkbox" name="include_notes" value="1" {{ $data->include_notes == 1 ? 'checked' : null }}>
                        <span class="slider round"></span>
                    </label>
                </div>

                <input name="note" id="subscription_clone_note" value="{{ $data->note }}" maxlength="{{ len()->subscriptions->note }}" type="text" class="form-control" data-toggle="tooltip" data-placement="right" title="@lang('Enter Notes')">
            </div>
            <div class="position-relative form-group">
                <div class="input-group">
                    <div class="col-lg-7 p-0">

                        <div class="switch_container">
                            <label for="subscription_clone_alert_id" class="">@lang('Alert Profile')</label>

                            <label class="switch ml-2" data-toggle="tooltip" data-placement="right" title="@lang('Alert notification')">
                                <input type="checkbox" name="alert_type" value="1" {{ $data->alert_type ? 'checked' : null }}>
                                <span class="slider round"></span>
                            </label>
                        </div>

                        <select name="alert_id" id="subscription_clone_alert_id" class="form-control multiselect_init pr-2">
                            {{-- <option value="1">@lang('System Default')</option> --}}
                            @php
                                $system_default_profile = lib()->do->get_alert_profile_system_default();
                                $system_default_profile_ltd = lib()->do->get_alert_profile_system_default_ltd();
                            @endphp
                            @if (!empty($system_default_profile->id))
                                <option value="{{ $system_default_profile->id }}" data-alert_subs_type="{{ $system_default_profile->alert_subs_type }}">{{ $system_default_profile->alert_name }}</option>
                            @endif
                            @if (!empty($system_default_profile_ltd->id))
                                <option value="{{ $system_default_profile_ltd->id }}" data-alert_subs_type="{{ $system_default_profile_ltd->alert_subs_type }}">{{ $system_default_profile_ltd->alert_name }}</option>
                            @endif

                            @foreach (lib()->user->alert->get_by_user() ?? [] as $val)
                                <option value="{{ $val->id }}" data-alert_subs_type="{{ $val->alert_subs_type }}" {{ $data->alert_id == $val->id ? 'selected' : null }}>{{ $val->alert_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-5 p-0">
                        <label for="subscription_clone_refund_days" class="">@lang('Refund Days')</label>
                        <input name="refund_days" id="subscription_clone_refund_days" value="{{ $data->refund_days }}" type="number" min="1" max="{{ len()->subscriptions->refund_days }}" class="form-control" data-toggle="tooltip" data-placement="right" title="@lang('Enter Refund Days')">
                    </div>

                </div>
            </div>

            <div class="position-relative form-group" data-toggle="tooltip" data-placement="top" title="@lang('Set Expiry Date')">
                <label for="subscription_clone_expiry_date" class="">@lang('Expiry Date')</label>
                <div class="input-group">
                    <input name="expiry_date" id="subscription_clone_expiry_date" value="{{ $data->expiry_date }}" type="text" placeholder="yyyy-mm-dd" maxlength="{{ len()->subscriptions->expiry_date }}" class="form-control" data-toggle="datepicker-and-icon">
                    <div class="input-group-append datepicker-trigger">
                        <div class="input-group-text">
                            <i class="fa fa-calendar-alt"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- <button class="mb-2 mr-2 btn-transition btn btn-outline-primary">@lang('Contact Manager')</button>
            <br>
            <button class="mb-2 mr-2 btn-transition btn btn-outline-primary">@lang('Alert Manager')</button> --}}
        </div>

        <div class="col-12">
            <div class="row">
                <div class="col-md-6 col-xl-6">
                    <div class="position-relative form-group">
                        <label for="subscription_clone_description" class="">@lang('Description')</label>
                        <textarea name="description" id="subscription_clone_description" maxlength="{{ len()->subscriptions->description }}" rows="3" class="form-control non_resizable" data-toggle="tooltip" data-placement="left" title="@lang('Product Description')">{{ $data->description }}</textarea>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3 d-none">
                    <div class="position-relative form-group">
                        <label for="subscription_clone_company_type" class="">@lang('Type')</label>
                        <input name="company_type" id="subscription_clone_company_type" value="{{ $product ? $product->product_type_name : null }}" type="text" class="form-control" readonly data-toggle="tooltip" data-placement="top" title="@lang('Product Type')">
                    </div>
                </div>
                <div class="col-md-12 col-xl-6">
                    <div class="row">
                        <div class="col-md-12 col-xl-6">
                            <div class="position-relative form-group">
                                <label for="subscription_clone_category_id" class="">@lang('Category')</label>
                                <select name="category_id" id="subscription_clone_category_id" class="form-control" data-toggle="tooltip" data-placement="top" title="@lang('Product Category')">
                                    {{-- <option selected="" disabled="" value="" style="display: none;">@lang('Select')</option> --}}
                                    @foreach (lib()->product->category->get_all() as $val)
                                        <option value="{{ $val->id }}" {{ $data->category_id == $val->id ? 'selected' : null }}>@lang($val->name)</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12 col-xl-6 mt-4 pt-1">
                            <button type="submit" class="btn btn-primary btn-lg btn-block pull-right" onclick="app.subscription.clone_create(this);" data-toggle="tooltip" data-placement="top" title="@lang('Save your Subs/lifetime')">
                                <i class="fa fa-save"></i>&nbsp;
                                @lang('Add')
                            </button>
                        </div>
                        <div class="container barrating_container">
                            <span>@lang('Subshero Rating'):&nbsp;</span>
                            <div class="subscription_footer_barrating_container" data-toggle="tooltip" data-placement="top" title="@lang('Rate the Product')">
                                <select id="subscription_clone_rating_bar" class="bars-movie" name="rating">
                                    @foreach (table('subscription.rating') as $key => $val)
                                        <option value="{{ $key }}">@lang($val)</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- <div class="col-12">
            <div class="row">
                <div class="col-md-6 col-xl-9">
                </div>
                <div class="col-md-6 col-xl-3">
                    <button type="submit" class="btn btn-primary btn-lg btn-block pull-right" onclick="app.subscription.update(this, '{{ $data->id }}');">
                        <i class="fa fa-save"></i>&nbsp;
                        @lang('Save')
                    </button>
                </div>
            </div>
        </div> --}}
    </form>

    <script>
        // Load select2 elements
        $('#modal_subscription_clone .select2_init_tags').select2({
            tags: true,
            theme: 'bootstrap4',
            dropdownParent: $('#modal_subscription_clone'),
            placeholder: {
                id: '',
                text: app.lang['None Selected'],
            },
        });
        $('#modal_subscription_clone .select2_init_multi').select2({
            tags: true,
            theme: 'bootstrap4',
            dropdownParent: $('#modal_subscription_clone'),
        });



        // Subscription -> Clone -> Product (Select2)
        app.subscription.o.clone.select2.brand_id = app.subscription.c.select2.product;
        app.subscription.o.clone.select2.brand_id.dropdownParent = $(app.subscription.e.clone.modal);
        $(app.subscription.e.clone.brand_id).select2(app.subscription.o.clone.select2.brand_id);


        $(app.subscription.e.clone.brand_id).on('select2:select', function(e) {
            if (app.subscription.o.select2.selection) {
                return;
            }

            if (!e.params.data) {
                return;
            }

            lib.sleep(100).then(() => {
                let path = e.params.data.image_path;

                // if (!e.params.data.currency_code) {
                //     return false;
                // }

                app.subscription.img_clone.destroy();
                app.subscription.img_clone = null;
                $('#subscription_clone_image_path').val('');

                // New fields
                // $('#subscription_clone_company_description').val(e.params.data.description);
                $('#subscription_clone_company_type').val(e.params.data.product_type);
                // $('#subscription_clone_company_type_label').text(e.params.data.product_type_name);
                $('#subscription_clone_description').val(e.params.data.description);
                $('#subscription_clone_price').val(e.params.data.price1_value);
                $('#subscription_clone_price_type').val(e.params.data.currency_code);
                $('#subscription_clone_url').val(e.params.data.url);
                $('#subscription_clone_billing_frequency').val(e.params.data.billing_frequency);
                $('#subscription_clone_billing_cycle').val(e.params.data.billing_cycle);
                $('#subscription_clone_type').val(e.params.data.pricing_type);
                $('#subscription_clone_category_id').val(e.params.data.category_id).trigger('change');
                $('#subscription_clone_ltdval_price').val(e.params.data.ltdval_price);
                $('#subscription_clone_ltdval_cycle').val(e.params.data.ltdval_cycle);
                $('#subscription_clone_ltdval_frequency').val(e.params.data.ltdval_frequency);
                // $('#subscription_clone_refund_days').val(e.params.data.refund_days);

                // Set refund days
                let refund_days = parseInt(e.params.data.refund_days);
                if (refund_days > 0) {
                    $('#subscription_clone_refund_days').val(refund_days);
                } else {
                    $('#subscription_clone_refund_days').val('');
                }

                app.subscription.clone_type_check('#subscription_clone_type');
                // app.ui.modal_favicon(e.params.data.url, '#modal_subscription_clone .modal-header img.favicon');


                // Load favicon from url
                if (e.params.data.favicon != '') {
                    $('#modal_subscription_clone .modal-header img.favicon').attr('src', e.params.data.favicon);
                } else {
                    app.ui.modal_favicon(e.params.data.url, '#modal_subscription_clone .modal-header img.favicon');
                }

                app.subscription.modal_title_change('clone');
                app.subscription.clone_recurring_check($('#subscription_clone_recurring_toggle')[0]);

                if (path) {
                    let filepond_options = Object.assign({}, app.global.filepond_options);
                    filepond_options.files = [{
                        source: btoa(path),
                        options: {
                            type: 'local'
                        }
                    }];

                    lib.sleep(100).then(function() {
                        $('#subscription_clone_image_path').val(path);
                        app.subscription.img_clone = lib.img.filepond('#subscription_clone_image_file', filepond_options);
                        $('#subscription_clone_img_path_or_file').val(0);
                    });

                } else {
                    lib.sleep(100).then(function() {
                        app.subscription.img_clone = lib.img.filepond('#subscription_clone_image_file');
                        $('#subscription_clone_img_path_or_file').val(1);
                    });

                    // Default values
                    if (!e.params.data.billing_frequency) {
                        $('#subscription_clone_billing_frequency').val(app.subscription.o.billing_frequency);
                    }
                    if (!e.params.data.billing_cycle) {
                        $('#subscription_clone_billing_cycle').val(app.subscription.o.billing_cycle);
                    }
                    if (!e.params.data.currency_code) {
                        $('#subscription_clone_price_type').val(app.subscription.o.currency_code);
                    }

                }

                app.subscription.o.select2.selection = false;
            });

            app.subscription.o.select2.selection = true;
        });


        // Load datepicker
        $('[data-toggle="datepicker-and-icon"]').datepicker({
            format: 'yyyy-mm-dd',
        });
        $('[data-toggle="datepicker-and-icon"]').each(function(index) {
            $(this).siblings('.datepicker-trigger').click(function(e) {
                e.stopPropagation();
                $(this).siblings('input[data-toggle="datepicker-and-icon"]').datepicker('show').focus();
            });
        });

        // Load filepond image
        var filepond_options = Object.assign({}, app.global.filepond_options);
        filepond_options.files = [{
            source: btoa('{{ empty($data->_image) ? SUB_DImg : $data->_image }}'),
            options: {
                type: 'local'
            }
        }];
        if (app.subscription.img_clone) {
            app.subscription.img_clone.destroy();
            app.subscription.img_clone.destroy = null;
        }
        lib.sleep(100).then(function() {
            app.subscription.img_clone = lib.img.filepond('#subscription_clone_image_file', filepond_options);
            app.subscription.img_clone.on('removefile', app.subscription.img_clone_on_addfile);
        });

        // Status check for toggle buttons
        var subscription_clone_status = $('#subscription_clone_status').val();
        var subscription_clone_status_toggle = $('#subscription_clone_status_toggle');
        if (subscription_clone_status_toggle.length > 0) {

            // Check draft status
            if (subscription_clone_status == 0) {
                subscription_clone_status_toggle.get(0).checked = false;
                subscription_clone_status_toggle.closest('.toggle[data-toggle="toggle"]').addClass('off');
            }

            // Check active status
            else if (subscription_clone_status == 1) {
                subscription_clone_status_toggle.get(0).checked = true;
                subscription_clone_status_toggle.closest('.toggle[data-toggle="toggle"]').removeClass('off');
            }
        }


        // Recurring check for toggle buttons
        var subscription_clone_recurring = $('#subscription_clone_recurring').val();
        var subscription_clone_recurring_toggle = $('#subscription_clone_recurring_toggle');
        if (subscription_clone_recurring_toggle.length > 0) {

            // Check draft recurring
            if (subscription_clone_recurring == 0) {
                subscription_clone_recurring_toggle.get(0).checked = false;
                subscription_clone_recurring_toggle.closest('.toggle[data-toggle="toggle"]').addClass('off');
            }

            // Check active recurring
            else if (subscription_clone_recurring == 1) {
                subscription_clone_recurring_toggle.get(0).checked = true;
                subscription_clone_recurring_toggle.closest('.toggle[data-toggle="toggle"]').removeClass('off');
            }
        }
        $(app.subscription.e.clone.recurring_toggle_container).css('pointer-events', '');



        $(document).ready(function() {

            // Lifetime
            @if ($data->type == 3)
                $(app.subscription.e.clone.recurring_toggle_container).css('pointer-events', 'none');
            @else
                $(app.subscription.e.clone.recurring_toggle_container).css('pointer-events', '');
            @endif


            $('#subscription_clone_rating_bar').barrating({
                theme: 'bars-movie',
                showValues: false,
                allowEmpty: true,
                emptyValue: 0,
                initialRating: 0,
                // deselectable: true,
            });
            $('#subscription_clone_rating_bar').barrating('set', '{{ $data->rating }}');
            $('#subscription_clone_rating_bar').on('change', function(e) {
                $('#subscription_clone_rating').val(this.value);
            });

            app.subscription.clone_type_check('#subscription_clone_type');

            app.load.tooltip();



            // $('#subscription_clone_brand_id').on('select2:open', function(e) {
            //     lib.sleep(500).then(function() {
            //         lib.do.select2_favicon_load(e.target);
            //     });

            //     $('.select2-search__field').on('keydown change', function() {
            //         lib.sleep(500).then(function() {
            //             lib.do.select2_favicon_load(e.target);
            //         });
            //     });
            // });


            $('#subscription_clone_category_id').select2({
                tags: false,
                theme: 'bootstrap4',
                dropdownParent: $('#modal_subscription_clone'),
                dropdownCssClass: "select2_search_below",
            });


            // $('#subscription_clone_company_type_label').text($('#subscription_clone_company_type').val());

            app.subscription.modal_title_change('clone');

            $('#subscription_clone_alert_id').multiselect('destroy');
            $('#subscription_clone_alert_id').multiselect({
                inheritClass: true,
            });

            // Billing cycle and frequency
            app.subscription.clone_recurring_check($(app.subscription.e.clone.recurring_toggle).get(0));

            // Currency styling
            
            function currencyFormatter (el) {
                const words = el.text.split(' ');
                if (words.length < 2) {
                    return el.text;
                }
                const currency_symbol = words.pop();
                const name = words.join(' ');
                return `${name} <span class="badge badge_recurring">${currency_symbol}</span>`;
            }

            $('#subscription_clone_folder_id').select2({
                tags: true,
                theme: 'bootstrap4',
                templateResult: currencyFormatter,
                escapeMarkup: function (markup) {
                    return markup;
                },
                templateSelection: currencyFormatter,
            });
        });


        // Load favicon from url
        @if (empty($data->_favicon))
            app.ui.modal_favicon("{{ $data->url }}", '#modal_subscription_clone .modal-header img.favicon');
        @else
            $('#modal_subscription_clone .modal-header img.favicon').attr('src', "{{ img_url($data->_favicon) }}");
        @endif
    </script>
@endsection
