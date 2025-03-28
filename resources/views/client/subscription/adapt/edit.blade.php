@extends(request()->ajax() ? 'client/layouts/ajax' : 'client/layouts/default')

@section('head')
@endsection

@section('content')
    <form id="frm_subscription_adapt_edit" action="{{ route('app/subscription/adapt/accept', $data->id) }}" method="POST">
        @csrf
        <input type="hidden" name="id" id="subscription_adapt_edit_id" value="{{ $data->id }}">
        <input type="hidden" name="image_path" id="subscription_adapt_edit_image_path" value="{{ $product->_image }}">
        <input type="hidden" name="brand_id" id="subscription_adapt_edit_brand_id_original" value="{{ $product->id }}">
        <input type="hidden" name="favicon" id="subscription_adapt_edit_favicon_original" value="{{ $product->_favicon }}">
        <input type="hidden" name="img_path_or_file" id="subscription_adapt_edit_img_path_or_file" value="0">
        <input type="hidden" name="status" id="subscription_adapt_edit_status" value="{{ $data->status }}">
        <input type="hidden" name="recurring" id="subscription_adapt_edit_recurring" value="{{ $data->recurring }}">
        <input type="hidden" name="rating" id="subscription_adapt_edit_rating" value="{{ $data->rating }}">

        <div class="modal-header subscription_modal_header">
            <img class="favicon img-thumbnail mr-1" src="{{ asset_ver('assets/images/favicon.ico') }}">
            <h5 class="modal-title">
                <span id="">@lang('Accept Changes for') {{ $data->product_name }}</span>
            </h5>

            <button type="button" class="btn_close btn_ripple" data-dismiss="modal" aria-label="Close">
                <img class="icon" src="{{ asset_ver('assets/icons/close.svg') }}">
            </button>
        </div>
        <div class="modal-body row">
            <div class="col-md-6 col-xl-3">
                <div class="position-relative form-group" data-toggle="tooltip" data-placement="left" title="@lang('Edit Select Type')">
                    <label for="subscription_adapt_edit_type" class="">@lang('Type')</label>
                    <select name="type" id="subscription_adapt_edit_type" onchange="app.subscription.update_type_check(this);" class="form-control" {{ $data->status == 0 ? null : 'disabled' }} required>
                        @foreach (table('subscription.type') as $key => $val)
                            <option value="{{ $key }}" {{ $data->type == $key ? 'selected' : null }}>@lang($val)</option>
                        @endforeach
                    </select>
                </div>
                <div class="position-relative form-group text-center mx-auto" style="max-width: 200px; max-height: 200px; min-height: 200px;" data-toggle="tooltip" data-placement="left" title="@lang('Browser image')">
                    <input type="file" class="filepond" id="subscription_adapt_edit_image_file" name="image" accept="image/*" data-size="200x200" style="display: none;">
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="position-relative form-group" data-toggle="tooltip" data-placement="top" title="@lang('Select Company Name')">
                    <label for="subscription_adapt_edit_brand_id" class="">@lang('Company name')</label>
                    <br>
                    {{-- <select name="brand_id" id="subscription_adapt_edit_brand_id" class="form-control select2_init_tags" {{ $data->brand_id <= PRODUCT_RESERVED_ID ? 'disabled' : null }}> --}}
                    <select name="brand_id" id="subscription_adapt_edit_brand_id" class="form-control" disabled>
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
                    <label for="subscription_clone_refund_days" class="">@lang('Refund Days')</label>
                    <input name="refund_days" id="subscription_clone_refund_days" value="{{ $data->refund_days }}" type="number" min="1" max="{{ len()->subscriptions->refund_days }}" class="form-control" data-toggle="tooltip" data-placement="right" title="@lang('Enter Refund Days')">
                </div>
                <div class="position-relative form-group">
                    <label for="subscription_adapt_edit_url" class="">@lang('URL')</label>
                    <input name="url" id="subscription_adapt_edit_url" value="{{ $data->url }}" type="url" class="form-control" onchange="app.ui.modal_favicon(this);" data-toggle="tooltip" data-placement="top" title="@lang('Website URL')">
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div id="subscription_adapt_edit_billing_container" class="position-relative form-group">

                    <div class="switch_container">
                        <label for="subscription_adapt_edit_billing_frequency" class="">@lang('Billing Cycle')</label>

                        <label class="switch ml-2" data-toggle="tooltip" data-placement="right" title="{{ lib()->lang->get_billing_type($data->billing_type) }}">
                            <input type="checkbox" name="billing_type" value="2" onclick="lib.do.billing_toggle_switch(this);" {{ $data->billing_type == 2 ? 'checked' : null }}>
                            <span class="slider round"></span>
                        </label>
                    </div>

                    <div class="input-group">
                        <div class="input-group-prepend">
                            <label for="subscription_adapt_edit_billing_frequency" class="input-group-text">@lang('Every')</span>
                        </div>
                        <select name="billing_frequency" id="subscription_adapt_edit_billing_frequency" {{ $data->type == 3 ? 'disabled' : null }} class="form-control pr-0" required data-toggle="tooltip" data-placement="top" title="@lang('Set Billing Frequency')">
                            @for ($i = 1; $i <= 40; $i++)
                                <option value="{{ $i }}" {{ $data->billing_frequency == $i ? 'selected' : null }}>@lang($i)</option>
                            @endfor
                        </select>
                        <select name="billing_cycle" id="subscription_adapt_edit_billing_cycle" {{ $data->type == 3 ? 'disabled' : null }} class="form-control" required data-toggle="tooltip" data-placement="top" title="@lang('Set Billing Cycle')">
                            @foreach (table('subscription.cycle') as $key => $val)
                                <option value="{{ $key }}" {{ $data->billing_cycle == $key ? 'selected' : null }}>@lang($val)</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="card mb-2 subscription_ltdval_card p-0">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="subscription_adapt_edit_ltdval_price" class="">@lang('Market Recurring Price')</label>
                                <input name="ltdval_price" id="subscription_adapt_edit_ltdval_price" value="{{ $data->ltdval_price }}" min="0" type="number" class="form-control" placeholder="@lang('0.00')" data-toggle="tooltip" data-placement="top" title="@lang('Set Price')">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="subscription_adapt_edit_ltdval_frequency" class="">@lang('MRP Billing Cycle')</label>

                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <label for="subscription_adapt_edit_ltdval_frequency" class="input-group-text">@lang('Every')</label>
                                    </div>
                                    <select name="ltdval_frequency" id="subscription_adapt_edit_ltdval_frequency" class="form-control pr-0" data-toggle="tooltip" data-placement="top" title="@lang('Set Billing Frequency')">
                                        <option selected="" disabled="" value="" style="display: none;">@lang('Select')</option>
                                        @for ($i = 1; $i <= 40; $i++)
                                            <option value="{{ $i }}" {{ $data->ltdval_frequency == $i ? 'selected' : null }}>@lang($i)</option>
                                        @endfor
                                    </select>
                                    <select name="ltdval_cycle" id="subscription_adapt_edit_ltdval_cycle" class="form-control" data-toggle="tooltip" data-placement="top" title="@lang('Set Billing Cycle')">
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
                    <label for="subscription_adapt_edit_price" class="">@lang('Price')</label>
                    <div class="input-group">
                        <input name="price" id="subscription_adapt_edit_price" value="{{ $data->price }}" min="0" type="number" class="form-control" placeholder="@lang('0.00')" data-toggle="tooltip" data-placement="top" title="@lang('Set Price')">
                        <select name="price_type" id="subscription_adapt_edit_price_type" class="form-control text-center" {{ $data->status == 0 ? null : 'disabled' }} required data-toggle="tooltip" data-placement="top" title="@lang('Select Currency Code')">
                            <option selected="" disabled="" value="" style="display: none;">@lang('Select')</option>
                            @foreach (lib()->config->currency as $val)
                                <option value="{{ $val['code'] }}" {{ $data->price_type == $val['code'] ? 'selected' : null }}>{{ $val['code'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="row">
                    <div class="col-md-6 col-xl-6">
                        <div class="position-relative form-group">
                            <label for="subscription_adapt_edit_description" class="">@lang('Description')</label>
                            <textarea name="description" id="subscription_adapt_edit_description" maxlength="{{ len()->subscriptions->description }}" rows="3" class="form-control non_resizable" data-toggle="tooltip" data-placement="left" title="@lang('Product Description')" {{ empty($data->sub_id) ? null : 'readonly' }}>{{ $data->description }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-3 d-none">
                        <div class="position-relative form-group">
                            <label for="subscription_adapt_edit_company_type" class="">@lang('Type')</label>
                            <input name="company_type" id="subscription_adapt_edit_company_type" value="{{ $product ? $product->product_type_name : null }}" type="text" class="form-control" readonly data-toggle="tooltip" data-placement="top" title="@lang('Product Type')">
                        </div>
                    </div>
                    <div class="col-md-12 col-xl-6">
                        <div class="row">
                            <div class="col-md-12 col-xl-6">
                                <div class="position-relative form-group">
                                    <label for="subscription_adapt_edit_category_id" class="">@lang('Category')</label>
                                    <select name="category_id" id="subscription_adapt_edit_category_id" class="form-control" data-toggle="tooltip" data-placement="top" title="@lang('Product Category')" {{ empty($data->sub_id) ? null : 'readonly' }}>
                                        {{-- <option selected="" disabled="" value="" style="display: none;">@lang('Select')</option> --}}
                                        @foreach (lib()->product->category->get_all() as $val)
                                            <option value="{{ $val->id }}" {{ $data->category_id == $val->id ? 'selected' : null }}>@lang($val->name)</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12 col-xl-6 mt-4 pt-1">
                                <button type="submit" class="btn btn-primary btn-lg btn-block pull-right" onclick="app.subscription.adapt.accept(this, '{{ $data->id }}');" data-toggle="tooltip" data-placement="top" title="@lang('Save your Subs/lifetime Changes')">
                                    <i class="fa fa-save"></i>&nbsp;
                                    @lang('Accept')
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        // Load select2 elements
        $('#modal_subscription_adapt_edit .select2_init_multi').select2({
            tags: true,
            theme: 'bootstrap4',
            dropdownParent: $('#modal_subscription_adapt_edit'),
        });


        // Subscription -> Update -> Product (Select2)
        app.subscription.o.edit.select2.brand_id = app.subscription.c.select2.product;
        app.subscription.o.edit.select2.brand_id.dropdownParent = $(app.subscription.e.edit.modal);
        $(app.subscription.e.edit.brand_id).select2(app.subscription.o.edit.select2.brand_id);


        $(app.subscription.e.edit.brand_id).on('select2:select', function(e) {
            if (app.subscription.o.select2.selection) {
                return;
            }

            if (!e.params.data) {
                return;
            }

            lib.sleep(100).then(() => {
                let path = e.params.data.image_path;

                if (!e.params.data.currency_code) {
                    return false;
                }

                // app.subscription.img_edit.destroy();
                app.subscription.img_edit = null;
                $('#subscription_adapt_edit_image_path').val('');

                // New fields
                // $('#subscription_adapt_edit_company_description').val(e.params.data.description);
                $('#subscription_adapt_edit_company_type').val(e.params.data.product_type);
                // $('#subscription_adapt_edit_company_type_label').text(e.params.data.product_type_name);
                $('#subscription_adapt_edit_description').val(e.params.data.description);
                $('#subscription_adapt_edit_price').val(e.params.data.price1_value);
                $('#subscription_adapt_edit_price_type').val(e.params.data.currency_code);
                $('#subscription_adapt_edit_url').val(e.params.data.url);
                $('#subscription_adapt_edit_billing_frequency').val(e.params.data.billing_frequency);
                $('#subscription_adapt_edit_billing_cycle').val(e.params.data.billing_cycle);
                $('#subscription_adapt_edit_type').val(e.params.data.pricing_type);
                $('#subscription_adapt_edit_category_id').val(e.params.data.category_id).trigger('change');
                $('#subscription_adapt_edit_ltdval_price').val(e.params.data.ltdval_price);
                $('#subscription_adapt_edit_ltdval_cycle').val(e.params.data.ltdval_cycle);
                $('#subscription_adapt_edit_ltdval_frequency').val(e.params.data.ltdval_frequency);

                app.subscription.update_type_check('#subscription_adapt_edit_type');
                // app.ui.modal_favicon(e.params.data.url'), '#modal_subscription_adapt_edit .modal-header img.favicon');


                // Load favicon from url
                if (e.params.data.favicon != '') {
                    $('#modal_subscription_adapt_edit .modal-header img.favicon').attr('src', e.params.data.favicon);
                } else {
                    app.ui.modal_favicon(e.params.data.url, '#modal_subscription_adapt_edit .modal-header img.favicon');
                }

                app.subscription.modal_title_change('update');
                app.subscription.edit_recurring_check($('#subscription_adapt_edit_recurring_toggle')[0]);

                if (path) {
                    let filepond_options = Object.assign({}, app.global.filepond_options);
                    filepond_options.files = [{
                        source: btoa(path),
                        options: {
                            type: 'local'
                        }
                    }];

                    lib.sleep(100).then(function() {
                        $('#subscription_adapt_edit_image_path').val(path);
                        app.subscription.img_edit = lib.img.filepond('#subscription_adapt_edit_image_file', filepond_options);
                        $('#subscription_adapt_edit_img_path_or_file').val(0);
                    });

                } else {
                    lib.sleep(100).then(function() {
                        app.subscription.img_edit = lib.img.filepond('#subscription_adapt_edit_image_file');
                        $('#subscription_adapt_edit_img_path_or_file').val(1);
                    });

                    // Default values
                    if (!e.params.data.billing_frequency) {
                        $('#subscription_adapt_edit_billing_frequency').val(app.subscription.o.billing_frequency);
                    }
                    if (!e.params.data.billing_cycle) {
                        $('#subscription_adapt_edit_billing_cycle').val(app.subscription.o.billing_cycle);
                    }
                    if (!e.params.data.currency_code) {
                        $('#subscription_adapt_edit_price_type').val(app.subscription.o.currency_code);
                    }
                }

                app.subscription.o.select2.selection = false;
            });

            app.subscription.o.select2.selection = true;
        });

        $(document).ready(function() {

            // Status toggle button show or hide
            @if ($data->status == 1)
                $('#subscription_adapt_edit_status_toggle_container').hide();
                $('#modal_subscription_adapt_edit .modal-header .modal-title').css('width', '92.5%');

                // Lifetime
                @if ($data->type == 3)
                    $('#subscription_adapt_edit_recurring_toggle_container').hide();
                @else
                    $('#subscription_adapt_edit_recurring_toggle_container').show();
                @endif
                $(app.subscription.e.edit.recurring_toggle_container).css('pointer-events', '');

                $('#subscription_adapt_edit_recurring_toggle_container button.not_for_draft').removeClass('d-none');
            @endif


            // Lifetime addon
            @if (empty($data->sub_id) && $data->status == 1)
                $('#subscription_adapt_edit_recurring_toggle_container #subscription_adapt_edit_popup_addon').removeClass('d-none');
            @else
                $('#subscription_adapt_edit_recurring_toggle_container #subscription_adapt_edit_popup_addon').addClass('d-none');
            @endif

            $('#subscription_adapt_edit_recurring_toggle_container #subscription_adapt_edit_popup_attachment .attachment_count').text({{ $data->attachment_count }});



            $('#subscription_adapt_edit_rating_bar').barrating({
                theme: 'bars-movie',
                showValues: false,
                allowEmpty: true,
                emptyValue: 0,
                initialRating: 0,
                // deselectable: true,
            });
            $('#subscription_adapt_edit_rating_bar').barrating('set', '{{ $data->rating }}');
            $('#subscription_adapt_edit_rating_bar').on('change', function(e) {
                $('#subscription_adapt_edit_rating').val(this.value);
            });

            app.subscription.update_type_check('#subscription_adapt_edit_type');

            app.load.tooltip();


            $('#subscription_adapt_edit_category_id').select2({
                tags: false,
                theme: 'bootstrap4',
                dropdownParent: $('#modal_subscription_adapt_edit'),
                dropdownCssClass: "select2_search_below",
            });


            // $('#subscription_adapt_edit_company_type_label').text($('#subscription_adapt_edit_company_type').val());

            app.subscription.modal_title_change('update');
        });


        // Load filepond image
        var filepond_options = Object.assign({}, app.global.filepond_options);
        filepond_options.files = [{
            source: btoa('{{ empty($product->_image) ? SUB_DImg : $product->_image }}'),
            options: {
                type: 'local'
            }
        }];
        if (app.subscription.adapt.o.edit.image) {
            app.subscription.adapt.o.edit.image.destroy();
            app.subscription.adapt.o.edit.image.destroy = null;
        }
        lib.sleep(100).then(function() {
            app.subscription.adapt.o.edit.image = lib.img.filepond('#subscription_adapt_edit_image_file', filepond_options);
            app.subscription.adapt.o.edit.image.on('removefile', function() {
                $('#subscription_adapt_edit_img_path_or_file').val(1);
            });
        });


        // Load favicon from url
        @if (empty($product->_favicon))
            app.ui.modal_favicon("{{ $data->url }}", '#modal_subscription_adapt_edit .modal-header img.favicon');
        @else
            $('#modal_subscription_adapt_edit .modal-header img.favicon').attr('src', "{{ img_url($product->_favicon) }}");
        @endif
    </script>
@endsection
