@extends(request()->ajax() ? 'admin/layouts/ajax' : 'admin/layouts/default')

@section('head')
@endsection

@section('content')
    <form class="row" id="product_edit_form" action="{{ route('admin/product/update', $data->id) }}" method="POST">
        @csrf
        <input type="hidden" name="id" id="product_edit_id" value="{{ $data->id }}">
        <input type="hidden" name="status" id="product_edit_status" value="{{ $data->status }}">

        <div class="col-md-6 col-xl-3">
            <div class="position-relative form-group">
                <label for="product_edit_product_type" class="">@lang('Product Type')</label>
                <select name="product_type" id="product_edit_product_type" class="form-control" required>
                    @foreach (lib()->product->type->get_all() as $val)
                        <option value="{{ $val->id }}" {{ $data->product_type == $val->id ? 'selected' : null }}>@lang($val->name)</option>
                    @endforeach
                </select>
            </div>
            <div class="position-relative form-group">
                <label for="product_edit_pricing_type" class="">@lang('Pricing Type')</label>
                <select name="pricing_type" id="product_edit_pricing_type" class="form-control">
                    <option selected="" disabled="" value="" style="display: none;">@lang('Select')</option>
                    @foreach (table('product.pricing_type') as $key => $val)
                        <option value="{{ $key }}" {{ $data->pricing_type == $key ? 'selected' : null }}>@lang($val)</option>
                    @endforeach
                </select>
            </div>
            <div class="position-relative form-group text-center mx-auto" style="max-width: 200px; max-height: 200px; min-height: 200px;">
                <input type="file" class="filepond" id="product_edit_image_file" name="image" accept="image/*" data-size="200x200" style="display: none;">
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="position-relative form-group">
                <label for="product_edit_brandname" class="">@lang('Brand Name')</label>
                <input name="brandname" id="product_edit_brandname" value="{{ $data->brandname }}" maxlength="{{ len()->products->brandname }}" type="text" class="form-control">
            </div>
            <div class="position-relative form-group">
                <label for="product_edit_product_name" class="">@lang('Product Name')</label>
                <input name="product_name" id="product_edit_product_name" value="{{ $data->product_name }}" maxlength="{{ len()->products->product_name }}" type="text" class="form-control" required>
            </div>
            <div class="position-relative form-group">
                <label for="product_edit_description" class="">@lang('Description (optional)')</label>
                <input name="description" id="product_edit_description" value="{{ $data->description }}" maxlength="{{ len()->products->description }}" type="text" class="form-control">
            </div>
            <div class="position-relative form-group">
                <label for="product_edit_category_id" class="">@lang('Product Category')</label>
                <select name="category_id" id="product_edit_category_id" class="form-control">
                    <option selected="" disabled="" value="" style="display: none;">@lang('Select')</option>
                    @foreach (lib()->product->category->get_all() as $val)
                        <option value="{{ $val->id }}" {{ $data->category_id == $val->id ? 'selected' : null }}>@lang($val->name)</option>
                    @endforeach
                </select>
            </div>
            <div class="position-relative form-group">
                <label for="product_edit_sub_platform" class="">@lang('Product Platform')</label>
                <select name="sub_platform" id="product_edit_sub_platform" class="form-control">
                    <option selected="" disabled="" value="" style="display: none;">@lang('Select')</option>
                    @foreach (lib()->product->platform->get_all() as $val)
                        <option value="{{ $val->id }}" {{ $data->sub_platform == $val->id ? 'selected' : null }}>@lang($val->name)</option>
                    @endforeach
                </select>
            </div>
            <div class="position-relative form-group">
                <label for="product_add_ltdval_price" class="">@lang('LTD Price')</label>
                <input name="ltdval_price" id="product_add_ltdval_price" value="{{ $data->ltdval_price }}" min="0" type="number" class="form-control" placeholder="@lang('0.00')" data-toggle="tooltip" data-placement="top" title="@lang('Set Price')">
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="position-relative form-group">
                <label for="product_add_price1_name" class="">@lang('Price 1')</label>
                <div class="input-group">
                    <input name="price1_name" id="product_add_price1_name" value="{{ $data->price1_name }}" maxlength="{{ len()->products->price2_name }}" type="text" class="form-control bg-white" placeholder="@lang('Name')">
                    <input name="price1_value" id="product_add_price1_value" value="{{ $data->price1_value }}" type="number" min="0" class="form-control bg-white" placeholder="@lang('Price')">
                </div>
            </div>
            <div class="position-relative form-group">
                <label for="product_add_price2_name" class="">@lang('Price 2')</label>
                <div class="input-group">
                    <input name="price2_name" id="product_add_price2_name" value="{{ $data->price2_name }}" maxlength="{{ len()->products->price2_name }}" type="text" class="form-control bg-white" placeholder="@lang('Name')">
                    <input name="price2_value" id="product_add_price2_value" value="{{ $data->price2_value }}" type="number" min="0" class="form-control bg-white" placeholder="@lang('Price')">
                </div>
            </div>
            <div class="position-relative form-group">
                <label for="product_add_price3_name" class="">@lang('Price 3')</label>
                <div class="input-group">
                    <input name="price3_name" id="product_add_price3_name" value="{{ $data->price3_name }}" maxlength="{{ len()->products->price2_name }}" type="text" class="form-control bg-white" placeholder="@lang('Name')">
                    <input name="price3_value" id="product_add_price3_value" value="{{ $data->price3_value }}" type="number" min="0" class="form-control bg-white" placeholder="@lang('Price')">
                </div>
            </div>
            <div class="position-relative form-group">
                <label for="product_edit_payment_date" class="">@lang('Billing Cycle')</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <label for="product_edit_billing_frequency" class="input-group-text">@lang('Every')</span>
                    </div>
                    <select name="billing_frequency" id="product_edit_billing_frequency" class="form-control" required>
                        @for ($i = 1; $i <= 40; $i++)
                            <option {{ $data->billing_frequency == $i ? 'selected' : null }}>@lang($i)</option>
                        @endfor
                    </select>
                    <select name="billing_cycle" id="product_edit_billing_cycle" class="form-control" required>
                        @foreach (table('subscription.cycle') as $key => $val)
                            <option value="{{ $key }}" {{ $data->billing_cycle == $key ? 'selected' : null }}>@lang($val)</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="position-relative form-group">
                <label for="product_edit_launch_year" class="">@lang('Launch year')</label>
                <input name="launch_year" id="product_edit_launch_year" value="{{ $data->launch_year }}" type="number" min="0" max="9999" step="1" class="form-control">
            </div>
            <div class="position-relative form-group">
                <label for="product_edit_ltdval_frequency" class="">@lang('LTD Billing Cycle')</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <label for="product_edit_ltdval_frequency" class="input-group-text">@lang('Every')</label>
                    </div>
                    <select name="ltdval_frequency" id="product_edit_ltdval_frequency" class="form-control pr-0" data-toggle="tooltip" data-placement="top" title="@lang('Set Billing Frequency')">
                        <option selected="" disabled="" value="" style="display: none;">@lang('Select')</option>
                        @for ($i = 1; $i <= 40; $i++)
                            <option value="{{ $i }}" {{ $data->ltdval_frequency == $i ? 'selected' : null }}>@lang($i)</option>
                        @endfor
                    </select>
                    <select name="ltdval_cycle" id="product_edit_ltdval_cycle" class="form-control" data-toggle="tooltip" data-placement="top" title="@lang('Set Billing Cycle')">
                        <option selected="" disabled="" value="" style="display: none;">@lang('Select')</option>
                        @foreach (table('subscription.cycle') as $key => $val)
                            <option value="{{ $key }}" {{ $data->ltdval_cycle == $key ? 'selected' : null }}>@lang($val)</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="position-relative form-group">
                <label for="product_edit_url" class="">@lang('URL')</label>
                <input name="url" id="product_edit_url" value="{{ $data->url }}" maxlength="{{ len()->products->url }}" type="url" class="form-control" onchange="app.ui.modal_favicon(this);">
            </div>
            <div class="position-relative form-group">
                <label for="product_edit_url_app" class="">@lang('App URL')</label>
                <div class="input-group mb-3">
                    <input name="url_app" id="product_edit_url_app" value="{{ $data->url_app }}" maxlength="{{ len()->products->url_app }}" type="url" class="form-control">
                </div>
            </div>
            <div class="position-relative form-group">
                <label for="product_edit_refund_days" class="">@lang('Refund days')</label>
                <input name="refund_days" id="product_edit_refund_days" value="{{ $data->refund_days }}" min="0" type="number" class="form-control">
            </div>
            <div class="position-relative form-group">
                <label for="product_edit_currency_code" class="">@lang('Currency Code')</label>
                <select name="currency_code" id="product_edit_currency_code" class="form-control">
                    <option selected="" disabled="" value="" style="display: none;">@lang('Select')</option>
                    @foreach (lib()->config->currency as $val)
                        <option value="{{ $val['code'] }}" {{ $data->currency_code == $val['code'] ? 'selected' : null }}>{{ $val['code'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="position-relative form-group">
                <label for="product_edit_sub_ltd" class="">@lang('Sub or LTD')</label>
                <br>
                <input name="sub_ltd" id="product_edit_sub_ltd" value="1" type="checkbox" data-toggle="toggle" {{ $data->sub_ltd == 1 ? 'checked' : null }}>
            </div>
        </div>
        <div class="col-12">
            <div class="row">
                <div class="col-md-6 col-xl-9">
                </div>
                <div class="col-md-6 col-xl-3">
                    <button type="submit" class="btn btn-primary btn-lg btn-block pull-right" onclick="app.product.update(this, '{{ $data->id }}');">
                        <i class="fa fa-save"></i>&nbsp;
                        @lang('Save')
                    </button>
                </div>
            </div>
        </div>
    </form>

    <script>
        // Load filepond image
        var filepond_options = Object.assign({}, app.global.filepond_options);
        filepond_options.files = [{
            source: btoa('{{ empty($data->image) ? SUB_DImg : $data->image }}'),
            options: {
                type: 'local'
            }
        }];
        var filepond_options_favicon = Object.assign({}, app.global.filepond_options);
        filepond_options_favicon.files = [{
            source: btoa('{{ empty($data->favicon) ? SUB_DImg : $data->favicon }}'),
            options: {
                type: 'local'
            }
        }];
        if (app.product.o.edit.img) {
            app.product.o.edit.img.destroy();
            app.product.o.edit.img.destroy = null;
        }
        if (app.product.o.edit.img_fav) {
            app.product.o.edit.img_fav.destroy();
            app.product.o.edit.img_fav.destroy = null;
        }
        lib.sleep(100).then(function() {
            app.product.o.edit.img = lib.img.filepond(app.product.e.edit.img, filepond_options);
            app.product.o.edit.img_fav = lib.img.filepond(app.product.e.edit.img_fav, filepond_options_favicon);
            // app.product.o.edit.img.on('removefile', app.product.edit_img_on_addfile);
        });


        // Status check for toggle buttons
        var product_edit_status = $('#product_edit_status').val();
        var product_edit_status_toggle = $('#product_edit_status_toggle');
        if (product_edit_status_toggle.length > 0) {

            // Check draft status
            if (product_edit_status == 0) {
                product_edit_status_toggle.get(0).checked = false;
                product_edit_status_toggle.closest('.toggle[data-toggle="toggle"]').addClass('off');
            }

            // Check status status
            else if (product_edit_status == 1) {
                product_edit_status_toggle.get(0).checked = true;
                product_edit_status_toggle.closest('.toggle[data-toggle="toggle"]').removeClass('off');
            }
        }


        // Load url favicon
        app.ui.modal_favicon("{{ $data->url }}", '#product_edit_modal .modal-header img.favicon');
    </script>

@endsection
