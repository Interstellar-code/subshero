<div id="product_add_modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form id="product_add_form" action="{{ route('admin/product/create') }}" method="POST">
                @csrf
                <div class="modal-header py-0 pl-3">
                    <div class="" style="width: 100px; ">
                        <input type="file" class="filepond m-0" id="product_add_image_favicon_file" name="imag_favicon" accept="image/*" data-size="100x100" style="display: none;">
                    </div>

                    <h5 class="modal-title">@lang('Product Create')</h5>

                    <div class="header_toggle_btn_container toggle btn btn-warning mr-3" data-toggle="toggle" style="min-width: 80px;">
                        <input type="checkbox" name="status" id="product_add_status_toggle" value="1" data-toggle="toggle" data-onstyle="success" data-offstyle="danger" checked>
                        <div class="toggle-group">
                            <label class="btn btn-success toggle-on">@lang('Active')</label>
                            <label class="btn btn-danger toggle-off">@lang('Inactive')</label>
                            <span class="toggle-handle btn btn-light"></span>
                        </div>
                    </div>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 col-xl-3">
                            <div class="position-relative form-group">
                                <label for="product_add_product_type" class="">@lang('Product Type')</label>
                                <select name="product_type" id="product_add_product_type" class="form-control" required>
                                    @foreach (lib()->product->type->get_all() as $val)
                                        <option value="{{ $val->id }}">@lang($val->name)</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="position-relative form-group">
                                <label for="product_add_pricing_type" class="">@lang('Pricing Type')</label>
                                <select name="pricing_type" id="product_add_pricing_type" class="form-control" required>
                                    @foreach (table('product.pricing_type') as $key => $val)
                                        <option value="{{ $key }}">@lang($val)</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="position-relative form-group text-center mx-auto" style="max-width: 200px; max-height: 200px; min-height: 200px;">
                                <input type="file" class="filepond" id="product_add_image_file" name="image" accept="image/*" data-size="200x200" style="display: none;">
                            </div>
                        </div>

                        <div class="col-md-6 col-xl-3">
                            <div class="position-relative form-group">
                                <label for="product_add_brandname" class="">@lang('Brand Name')</label>
                                <input name="brandname" id="product_add_brandname" maxlength="{{ len()->products->brandname }}" type="text" class="form-control">
                            </div>
                            <div class="position-relative form-group">
                                <label for="product_add_product_name" class="">@lang('Product Name')</label>
                                <div class="input-group">
                                    <input name="product_name" id="product_add_product_name" maxlength="{{ len()->products->product_name }}" type="text" class="form-control" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text search-logo-icon" id="product_add_search_logo" style="display: none; cursor: pointer;">
                                            <i class="fa fa-search"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="position-relative form-group">
                                <label for="product_add_description" class="">@lang('Description (optional)')</label>
                                <input name="description" id="product_add_description" maxlength="{{ len()->products->description }}" type="text" class="form-control">
                            </div>
                            <div class="position-relative form-group">
                                <label for="product_add_category_id" class="">@lang('Product Category')</label>
                                <select name="category_id" id="product_add_category_id" class="form-control">
                                    <option selected="" disabled="" value="" style="display: none;">@lang('Select')</option>
                                    @foreach (lib()->product->category->get_all() as $val)
                                        <option value="{{ $val->id }}">@lang($val->name)</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="position-relative form-group">
                                <label for="product_add_sub_platform" class="">@lang('Product Platform')</label>
                                <select name="sub_platform" id="product_add_sub_platform" class="form-control">
                                    <option selected="" disabled="" value="" style="display: none;">@lang('Select')</option>
                                    @foreach (lib()->product->platform->get_all() as $val)
                                        <option value="{{ $val->id }}">@lang($val->name)</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="position-relative form-group">
                                <label for="product_add_ltdval_price" class="">@lang('LTD Price')</label>
                                <input name="ltdval_price" id="product_add_ltdval_price" min="0" type="number" class="form-control" placeholder="@lang('0.00')" data-toggle="tooltip" data-placement="top" title="@lang('Set Price')">
                            </div>
                        </div>

                        <div class="col-md-6 col-xl-3">
                            <div class="position-relative form-group">
                                <label for="product_add_price1_name" class="">@lang('Price 1')</label>
                                <div class="input-group">
                                    <input name="price1_name" id="product_add_price1_name" maxlength="{{ len()->products->price1_name }}" type="text" class="form-control bg-white" placeholder="@lang('Name')">
                                    <input name="price1_value" id="product_add_price1_value" type="number" min="0" class="form-control bg-white" placeholder="@lang('Price')">
                                </div>
                            </div>
                            <div class="position-relative form-group">
                                <label for="product_add_price2_name" class="">@lang('Price 2')</label>
                                <div class="input-group">
                                    <input name="price2_name" id="product_add_price2_name" maxlength="{{ len()->products->price2_name }}" type="text" class="form-control bg-white" placeholder="@lang('Name')">
                                    <input name="price2_value" id="product_add_price2_value" type="number" min="0" class="form-control bg-white" placeholder="@lang('Price')">
                                </div>
                            </div>
                            <div class="position-relative form-group">
                                <label for="product_add_price3_name" class="">@lang('Price 3')</label>
                                <div class="input-group">
                                    <input name="price3_name" id="product_add_price3_name" maxlength="{{ len()->products->price3_name }}" type="text" class="form-control bg-white" placeholder="@lang('Name')">
                                    <input name="price3_value" id="product_add_price3_value" type="number" min="0" class="form-control bg-white" placeholder="@lang('Price')">
                                </div>
                            </div>
                            <div class="position-relative form-group">
                                <label for="product_add_billing_frequency" class="">@lang('Billing Cycle')</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <label for="product_add_billing_frequency" class="input-group-text">@lang('Every')</label>
                                    </div>
                                    <select name="billing_frequency" id="product_add_billing_frequency" class="form-control" required>
                                        @for ($i = 1; $i <= 40; $i++)
                                            <option>@lang($i)</option>
                                        @endfor
                                    </select>
                                    <select name="billing_cycle" id="product_add_billing_cycle" class="form-control" required>
                                        @foreach (table('subscription.cycle') as $key => $val)
                                            <option value="{{ $key }}">@lang($val)</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="position-relative form-group">
                                <label for="product_add_launch_year" class="">@lang('Launch year')</label>
                                <input name="launch_year" id="product_add_launch_year" type="number" min="0" max="9999" step="1" class="form-control">
                            </div>
                            <div class="position-relative form-group">
                                <label for="product_add_ltdval_frequency" class="">@lang('LTD Billing Cycle')</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <label for="product_add_ltdval_frequency" class="input-group-text">@lang('Every')</label>
                                    </div>
                                    <select name="ltdval_frequency" id="product_add_ltdval_frequency" class="form-control pr-0" data-toggle="tooltip" data-placement="top" title="@lang('Set Billing Frequency')">
                                        <option selected="" disabled="" value="" style="display: none;">@lang('Select')</option>
                                        @for ($i = 1; $i <= 40; $i++)
                                            <option value="{{ $i }}">@lang($i)</option>
                                        @endfor
                                    </select>
                                    <select name="ltdval_cycle" id="product_add_ltdval_cycle" class="form-control" data-toggle="tooltip" data-placement="top" title="@lang('Set Billing Cycle')">
                                        <option selected="" disabled="" value="" style="display: none;">@lang('Select')</option>
                                        @foreach (table('subscription.cycle') as $key => $val)
                                            <option value="{{ $key }}">@lang($val)</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-xl-3">
                            <div class="position-relative form-group">
                                <label for="product_add_url" class="">@lang('URL')</label>
                                <div class="input-group mb-3">
                                    <input name="url" id="product_add_url" maxlength="{{ len()->products->url }}" type="url" class="form-control" onchange="app.ui.modal_favicon(this);">
                                    {{-- <div class="input-group-append">
                                        <span class="input-group-text">
                                            <img id="product_add_favicon_img" src="" style="height: 24px; width: 24px; display: none;">
                                        </span>
                                    </div> --}}
                                </div>
                            </div>
                            <div class="position-relative form-group">
                                <label for="product_add_url_app" class="">@lang('App URL')</label>
                                <div class="input-group mb-3">
                                    <input name="url_app" id="product_add_url_app" maxlength="{{ len()->products->url_app }}" type="url" class="form-control">
                                </div>
                            </div>
                            <div class="position-relative form-group">
                                <label for="product_add_refund_days" class="">@lang('Refund days')</label>
                                <input name="refund_days" id="product_add_refund_days" type="number" min="0" class="form-control">
                            </div>
                            <div class="position-relative form-group">
                                <label for="product_add_currency_code" class="">@lang('Currency Code')</label>
                                <select name="currency_code" id="product_add_currency_code" class="form-control" required>
                                    <option selected="" disabled="" value="" style="display: none;">@lang('Select')</option>
                                    @foreach (lib()->config->currency as $val)
                                        <option value="{{ $val['code'] }}">{{ $val['code'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="position-relative form-group">
                                <label for="product_add_sub_ltd" class="">@lang('Sub or LTD')</label>
                                <br>
                                <input name="sub_ltd" id="product_add_sub_ltd" value="1" type="checkbox" data-toggle="toggle">
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="row">
                                <div class="col-md-6 col-xl-9">
                                </div>
                                <div class="col-md-6 col-xl-3">
                                    <button type="submit" class="btn btn-primary btn-lg btn-block pull-right" onclick="app.product.create(this);">
                                        <i class="fa fa-plus"></i>&nbsp;
                                        @lang('Add')
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>




<div id="product_edit_modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header py-0 pl-3">
                <div class="" style="width: 100px; ">
                    <input type="file" class="filepond m-0" id="product_edit_image_favicon_file" name="imag_favicon" accept="image/*" data-size="100x100" style="display: none;">
                </div>
                <h5 class="modal-title">@lang('Product Update')</h5>

                <div class="header_toggle_btn_container toggle btn btn-warning mr-3" data-toggle="toggle" style="min-width: 80px;">
                    <input type="checkbox" id="product_edit_status_toggle" value="1" data-toggle="toggle" data-onstyle="success" data-offstyle="danger" checked>
                    <div class="toggle-group">
                        <label class="btn btn-success toggle-on">@lang('Active')</label>
                        <label class="btn btn-danger toggle-off">@lang('Inactive')</label>
                        <span class="toggle-handle btn btn-light"></span>
                    </div>
                </div>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>

<!-- Logo Search Results Modal -->
<div id="logo_search_modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Logo Search Results')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="logo_search_results" class="row">
                    <!-- Search results will be displayed here -->
                </div>
                <div id="logo_search_loading" class="text-center" style="display: none;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <p>Searching for logos...</p>
                </div>
                <div id="logo_search_no_results" class="text-center" style="display: none;">
                    <p>No logos found. Try a different product name.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Close')</button>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        $('#product_add_url').on('change', function(e) {

            let url = e.target.value;
            if (url) {
                if (url.substr(url.length - 1) == '/') {
                    favicon_url = url + 'favicon.ico';
                } else {
                    favicon_url = url + '/favicon.ico';
                }

                $('#product_add_favicon_img').attr('src', favicon_url);
            } else {
                $('#product_add_favicon_img').attr('src', null);
            }

            $('#product_add_favicon_img').on('load', function(e) {
                e.target.style.display = '';
            }).on('error', function(e) {
                e.target.style.display = 'none';
            });
        });

        app.ui.btn_toggle();



        // Edit
        $(document).on('click', '#product_edit_modal .toggle[data-toggle="toggle"]', function() {

            let checkbox = $('#product_edit_status_toggle');
            if (checkbox.length) {
                $('#product_edit_status').val(checkbox.get(0).checked ? '1' : '0');
            }
        });

    })
</script>
