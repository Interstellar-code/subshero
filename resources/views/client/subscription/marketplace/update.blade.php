@extends(request()->ajax() ? 'client/layouts/ajax' : 'client/layouts/default')

@section('head')
@endsection

@section('content')
    <form id="frm_subscription_marketplace_edit" action="{{ route('app/subscription/marketplace/update', $marketplace->id) }}" method="POST">
        @csrf
        <input type="hidden" name="_method" value="PUT">
        <input type="hidden" name="id" id="subscription_marketplace_edit_id" value="{{ $marketplace->id }}">

        <div class="modal-header subscription_modal_header">
            <img class="favicon img-thumbnail mr-1" src="{{ asset_ver('assets/images/favicon.ico') }}">
            <h5 class="modal-title">
                <span id="modal_subscription_marketplace_edit_title">{{ $marketplace->product_name }}</span>
            </h5>

            <div class="header_toggle_btn_container toggle btn btn-warning mr-4" id="subscription_marketplace_edit_status_toggle_container" data-toggle="toggle">
                <input type="checkbox" name="status" id="subscription_marketplace_edit_status_toggle" value="1" {{ $marketplace->status ? 'checked' : null }} data-toggle="toggle" data-onstyle="success" data-offstyle="danger">
                <div class="toggle-group" data-toggle="tooltip" data-placement="left" title="@lang('If Active your Subs/Lifetime is tracked , Draft can not be track your Subs/Lifetime')">
                    <label class="btn btn-success toggle-on">@lang('Active')</label>
                    <label class="btn btn-warning toggle-off">@lang('Draft')</label>
                    <span class="toggle-handle btn btn-light"></span>
                </div>
            </div>

            <button type="button" class="btn_close btn_ripple" data-dismiss="modal" aria-label="Close">
                <img class="icon" src="{{ asset_ver('assets/icons/close.svg') }}">
            </button>
        </div>
        <div class="modal-body row">
            <div class="col-md-6 col-xl-4">
                <div class="position-relative form-group" data-toggle="tooltip" data-placement="left" title="@lang('Select Type')">
                    <label for="subscription_marketplace_edit_type" class="">@lang('Type')</label>
                    <select name="type" id="subscription_marketplace_edit_type" class="form-control" disabled>
                        @foreach (table('subscription.type') as $key => $val)
                            <option value="{{ $key }}" {{ $marketplace->subscription->type == $key ? 'selected' : null }}>@lang($val)</option>
                        @endforeach
                    </select>
                </div>
                <div class="position-relative form-group text-center mx-auto" style="max-width: 200px; max-height: 200px; min-height: 200px;" data-toggle="tooltip" data-placement="left" title="@lang('Browser image')">
                    <input type="file" class="filepond" id="subscription_marketplace_edit_image_file" name="image" accept="image/*" data-size="200x200" style="display: none;">
                </div>
            </div>

            <div class="col-md-6 col-xl-4">
                <div class="position-relative form-group" data-toggle="tooltip" data-placement="top" title="@lang('Select Company Name')">
                    <label for="subscription_marketplace_edit_brand_id" class="">@lang('Company name')</label>
                    <br>
                    <select name="brand_id" id="subscription_marketplace_edit_brand_id" class="form-control" disabled>
                        @php
                            if ($marketplace->brand_id <= PRODUCT_RESERVED_ID) {
                                echo '<option value="' . $marketplace->product_name . '" selected>' . $marketplace->product_name . '</option>';
                            } else {
                                echo '<option value="' . $marketplace->brand_id . '" selected>' . $marketplace->product_name . '</option>';
                            }
                        @endphp
                    </select>
                </div>
                <div class="position-relative form-group">
                    <label for="subscription_marketplace_edit_product_url" class="">@lang('Product URL')</label>
                    <input name="product_url" id="subscription_marketplace_edit_product_url" value="{{ $marketplace->product_url }}" type="url" maxlength="{{ len()->subscription_cart->product_url }}" class="form-control" data-toggle="tooltip" data-placement="top" title="@lang('Product URL')">
                </div>
                <div class="position-relative form-group">
                    <label for="subscription_marketplace_edit_product_category_id" class="">@lang('Category')</label>
                    <select name="product_category_id" id="subscription_marketplace_edit_product_category_id" class="form-control" data-toggle="tooltip" data-placement="top" title="@lang('Product Category')">
                        <option selected="" disabled="" value="" style="display: none;">@lang('Select')</option>
                        @foreach (lib()->product->category->get_all() as $val)
                            <option value="{{ $val->id }}" {{ $marketplace->product_category_id == $val->id ? 'selected' : null }}>@lang($val->name)</option>
                        @endforeach
                    </select>
                </div>
                <div class="position-relative form-group">
                    <label for="subscription_marketplace_edit_notes" class="">@lang('Notes')</label>
                    <input name="notes" id="subscription_marketplace_edit_notes" value="{{ $marketplace->notes }}" type="text" maxlength="{{ len()->subscription_cart->notes }}" class="form-control" title="@lang('Enter Notes')">
                </div>
            </div>

            <div class="col-md-6 col-xl-4">
                <div class="position-relative form-group">
                    <label for="subscription_marketplace_edit_price" class="">@lang('Price')</label>
                    <div class="input-group">
                        <input name="sale_price" id="subscription_marketplace_edit_sale_price" value="{{ $marketplace->sale_price }}" min="0" type="number" class="form-control" placeholder="@lang('0.00')" data-toggle="tooltip" data-placement="top" title="@lang('Set Sale Price')">
                        <select name="currency_code" id="subscription_marketplace_edit_currency_code" class="form-control text-center" required data-toggle="tooltip" data-placement="top" title="@lang('Select Currency Code')">
                            <option selected="" disabled="" value="" style="display: none;">@lang('Select')</option>
                            @foreach (lib()->config->currency as $val)
                                <option value="{{ $val['code'] }}" {{ $marketplace->currency_code == $val['code'] ? 'selected' : null }}>{{ $val['code'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="position-relative form-group">
                    <label for="subscription_marketplace_edit_sales_url" class="">@lang('Sales URL')</label>
                    <input name="sales_url" id="subscription_marketplace_edit_sales_url" value="{{ $marketplace->sales_url }}" type="url" maxlength="{{ len()->subscription_cart->sales_url }}" class="form-control" data-toggle="tooltip" data-placement="top" title="@lang('Sales URL')">
                </div>
                <div class="position-relative form-group">
                    <label for="subscription_marketplace_add_product_platform_id" class="">@lang('Platform')</label>
                    <select name="product_platform_id" id="subscription_marketplace_add_product_platform_id" class="form-control" required>
                        <option selected="" disabled="" value="" style="display: none;">@lang('Select')</option>
                        @foreach (lib()->product->platform->get_all() as $val)
                            <option value="{{ $val->id }}" {{ $marketplace->product_platform_id == $val->id ? 'selected' : null }}>@lang($val->name)</option>
                        @endforeach
                    </select>
                </div>
                <div class="position-relative form-group">
                    <label for="subscription_marketplace_edit_plan_name" class="">@lang('Plan Name')</label>
                    <input name="plan_name" id="subscription_marketplace_edit_plan_name" value="{{ $marketplace->plan_name }}" type="text" maxlength="{{ len()->subscription_cart->plan_name }}" class="form-control" title="@lang('Enter Plan Name')">
                </div>
            </div>

            <div class="col-md-6 col-xl-4">
            </div>

            <div class="col-12">
                <div class="row">
                    <div class="col-md-6 col-xl-9">
                        <div class="position-relative form-group">
                            <label for="subscription_marketplace_edit_product_description" class="">@lang('Order Confirmation Text Note')</label>
                            <textarea name="product_description" id="subscription_marketplace_edit_product_description" maxlength="{{ len()->subscription_cart->product_description }}" rows="3" class="form-control non_resizable" data-toggle="tooltip" data-placement="left" title="@lang('Order Confirmation Text Note')">{{ $marketplace->product_description }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-3 mt-4 pt-1">
                        <button type="submit" class="btn btn-primary btn-lg btn-block pull-right" onclick="app.subscription.marketplace.update(this);" data-toggle="tooltip" data-placement="top" title="@lang('Save')">
                            <i class="fa fa-save"></i>&nbsp;
                            @lang('Save')
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </form>


    <script>
        app.ui.btn_ripple();
        app.ui.btn_toggle();

        // Load filepond image
        var filepond_options = Object.assign({}, app.global.filepond_options);
        filepond_options.files = [{
            source: btoa('{{ empty($marketplace->_product_logo) ? SUB_DImg : $marketplace->_product_logo }}'),
            options: {
                type: 'local'
            }
        }];
        if (app.subscription.marketplace.o.edit.image) {
            app.subscription.marketplace.o.edit.image.destroy();
            app.subscription.marketplace.o.edit.image.destroy = null;
        }
        lib.sleep(100).then(function() {
            app.subscription.marketplace.o.edit.image = lib.img.filepond('#subscription_marketplace_edit_image_file', filepond_options);
            // app.subscription.marketplace.o.add.image.on('removefile', app.subscription.img_edit_on_addfile);
        });

        $('#subscription_marketplace_edit_category_id').select2({
            tags: false,
            theme: 'bootstrap4',
            dropdownParent: $('#modal_subscription_marketplace_edit'),
            dropdownCssClass: "select2_search_below",
        });

        // Load favicon from url
        @if (empty($marketplace->subscription->_favicon))
            app.ui.modal_favicon("{{ $marketplace->subscription->url }}", '#modal_subscription_marketplace_edit .modal-header img.favicon');
        @else
            $('#modal_subscription_marketplace_edit .modal-header img.favicon').attr('src', "{{ img_url($marketplace->subscription->_favicon) }}");
        @endif


        app.load.tooltip();
    </script>
@endsection
