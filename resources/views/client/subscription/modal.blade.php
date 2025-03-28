<style>
    /*Select2 ReadOnly Start*/
    select[readonly].select2-hidden-accessible+.select2-container {
        pointer-events: none;
        touch-action: none;
    }

    select[readonly].select2-hidden-accessible+.select2-container .select2-selection {
        background: #eee;
        box-shadow: none;
    }

    select[readonly].select2-hidden-accessible+.select2-container .select2-selection__arrow,
    select[readonly].select2-hidden-accessible+.select2-container .select2-selection__clear {
        display: none;
    }

    /*Select2 ReadOnly End*/
</style>

<div id="modal_subscription_add" class="modal fade subscription_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form id="frm_subscription_add" action="{{ route('app/subscription/create') }}" method="POST">
                @csrf
                <input type="hidden" name="image_path" id="subscription_add_image_path" value="">
                <input type="hidden" name="img_path_or_file" id="subscription_add_img_path_or_file" value="0">
                <input type="hidden" name="alert_type" id="subscription_add_alert_type" value="1">
                {{-- <input type="hidden" name="status" id="subscription_add_status" value="1"> --}}

                <div class="modal-header subscription_modal_header">
                    <img class="favicon img-thumbnail mr-1" src="{{ asset_ver('assets/images/favicon.ico') }}">
                    <h5 class="modal-title">
                        <span id="modal_subscription_add_title">@lang('Add Subscription')</span>&nbsp;
                        {{-- <small id="subscription_add_company_type_label" class="type"></small> --}}
                    </h5>

                    {{-- <div class="subscription_header_barrating_container">
                        <select class="bars-movie" name="rating">
                            @foreach (table('subscription.rating') as $key => $val)
                                <option value="{{ $key }}">@lang($val)</option>
                    @endforeach
                    </select>
                </div> --}}

                    <div class="header_toggle_btn_container toggle btn btn-warning mr-3" id="subscription_add_status_toggle_container" data-toggle="toggle">
                        <input type="checkbox" name="status" id="subscription_add_status" value="1" data-toggle="toggle" data-onstyle="success" data-offstyle="danger" checked>
                        <div class="toggle-group" data-toggle="tooltip" data-placement="left" title="@lang('If Active your Subs/Lifetime is tracked , Draft can not be track your Subs/Lifetime')">
                            <label class="btn btn-success toggle-on">@lang('Active')</label>
                            <label class="btn btn-warning toggle-off">@lang('Draft')</label>
                            <span class="toggle-handle btn btn-light"></span>
                        </div>
                    </div>

                    <div class="header_toggle_btn_container toggle btn btn-warning mr-3" id="subscription_add_recurring_toggle_container" data-toggle="toggle">
                        <input type="checkbox" name="recurring" id="subscription_add_recurring" onchange="app.subscription.create_recurring_check(this);" value="1" data-toggle="toggle" data-onstyle="success" data-offstyle="danger" checked>
                        <div class="toggle-group" data-toggle="tooltip" data-placement="bottom" title="@lang('If Product is subscription  toggle to Recur , If the product is lifetime toggle Once')">
                            <label class="btn btn_toggle_recurring toggle-on">@lang('Recur')</label>
                            <label class="btn btn-info toggle-off">@lang('Once')</label>
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
                            <div class="position-relative form-group" data-toggle="tooltip" data-placement="left" title="@lang('Select Type')">
                                <label for="subscription_add_type" class="">@lang('Type')</label>
                                <select name="type" id="subscription_add_type" onchange="app.subscription.create_type_check(this);" class="form-control" required>
                                    @foreach (table('subscription.type') as $key => $val)
                                        <option value="{{ $key }}">@lang($val)</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="position-relative form-group" data-toggle="tooltip" data-placement="left" title="@lang('Select Folder')">
                                <label for="subscription_add_folder_id" class="">@lang('Folder')</label>
                                <select name="folder_id" id="subscription_add_folder_id" class="form-control">
                                    <option selected="" disabled="" value="" style="display: none;">@lang('Select')</option>
                                    @foreach (lib()->folder->get_by_user() as $val)
                                        <option value="{{ $val->id }}" {{ $val->is_default == 1 ? 'selected' : null }}>{{ $val->name }} <span class="badge badge_recurring">{{ lib()->config->currency_symbol[$val->price_type] ?? 'All' }}</span></option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="position-relative form-group" data-toggle="tooltip" data-placement="left" title="@lang('Add tags')">
                                <label for="subscription_add_tags" class="">@lang('Tags')</label>
                                <select name="tags[]" id="subscription_add_tags" class="form-control select2_init_multi" multiple>
                                    @foreach (lib()->tag->get_by_user() as $val)
                                        <option value="{{ $val->id }}">{{ $val->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="position-relative form-group text-center mx-auto" style="max-width: 200px; max-height: 200px; min-height: 200px;" data-toggle="tooltip" data-placement="left" title="@lang('Browser image')">
                                <input type="file" class="filepond" id="subscription_add_image_file" name="image" accept="image/*" data-size="200x200" style="display: none;">
                            </div>
                        </div>

                        <div class="col-md-6 col-xl-3">
                            <div class="position-relative form-group" data-toggle="tooltip" data-placement="top" title="@lang('Select Company Name')">
                                <label for="subscription_add_brand_id" class="">@lang('Company')</label>
                                <br>
                                <select name="brand_id" id="subscription_add_brand_id" class="form-control">
                                    {{-- <option value=""></option>
                                    @foreach (lib()->product->get_all_except_default() as $val)
                                        <option value="{{ $val->id }}" data-path="{{ $val->image }}" data-product_type="{{ $val->product_type_name }}" data-description="{{ $val->description }}" data-price1_value="{{ $val->price1_value }}" data-currency_code="{{ $val->currency_code }}" data-url="{{ $val->url }}" data-billing_frequency="{{ $val->billing_frequency }}" data-billing_cycle="{{ $val->billing_cycle }}" data-pricing_type="{{ $val->pricing_type }}" data-favicon="{{ img_link($val->favicon) }}" data-category_id="{{ $val->category_id }}" data-ltdval_price="{{ $val->ltdval_price }}" data-ltdval_cycle="{{ $val->ltdval_cycle }}" data-ltdval_frequency="{{ $val->ltdval_frequency }}" data-refund_days="{{ $val->refund_days }}">{{ $val->product_name }}</option>
                                    @endforeach --}}
                                </select>
                            </div>
                            <div class="position-relative form-group">
                                <label for="subscription_add_url" class="">@lang('URL')</label>
                                <input name="url" id="subscription_add_url" type="url" class="form-control" onchange="app.ui.modal_favicon(this);" data-toggle="tooltip" data-placement="top" title="@lang('Website URL')">
                            </div>
                            <div class="position-relative form-group">
                                <label for="subscription_add_discount_voucher" class="">@lang('Discount Voucher')</label>
                                <input name="discount_voucher" id="subscription_add_discount_voucher" type="text" class="form-control" data-toggle="tooltip" data-placement="top" title="@lang('Enter Discount Voucher ')">
                            </div>
                            <div class="position-relative form-group">
                                <label for="subscription_add_payment_mode_id" class="">@lang('Payment mode')</label>
                                <select name="payment_mode_id" id="subscription_add_payment_mode_id" class="form-control" required data-toggle="tooltip" data-placement="top" title="@lang('Select Payment mode ')">
                                    <option selected="" disabled="" value="" style="display: none;">@lang('Select')</option>
                                    @foreach (lib()->user->payment_methods as $val)
                                        <option value="{{ $val->id }}" {{ lib()->user->default->payment_mode_id == $val->id ? 'selected' : null }}>{{ $val->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="position-relative form-group">
                                <label for="subscription_add_support_details" class="">@lang('Support Details')</label>
                                <input name="support_details" id="subscription_add_support_details" type="text" class="form-control" data-toggle="tooltip" data-placement="top" title="@lang('Enter Support Details')">
                            </div>
                        </div>

                        <div class="col-md-6 col-xl-3">
                            <div class="position-relative form-group" data-toggle="tooltip" data-placement="top" title="@lang('Set Payment Date')">
                                <label for="subscription_add_payment_date" class="">@lang('Payment Date')</label>
                                <div class="input-group">
                                    <input name="payment_date" id="subscription_add_payment_date" value="{{ date('Y-m-d') }}" type="text" placeholder="yyyy-mm-dd" maxlength="{{ len()->subscriptions->payment_date }}" class="form-control" data-toggle="datepicker-and-icon" required>
                                    <div class="input-group-append datepicker-trigger">
                                        <div class="input-group-text">
                                            <i class="fa fa-calendar-alt"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="subscription_add_billing_container" class="position-relative form-group">

                                <div class="switch_container">
                                    <label for="subscription_add_billing_frequency" class="">@lang('Billing Cycle')</label>

                                    <label class="switch ml-2" data-toggle="tooltip" data-placement="right" title="{{ lib()->lang->get_billing_type(lib()->prefer->billing_type) }}">
                                        <input type="checkbox" name="billing_type" value="2" onclick="lib.do.billing_toggle_switch(this);" {{ lib()->prefer->billing_type == 2 ? 'checked' : null }}>
                                        <span class="slider round"></span>
                                    </label>
                                </div>

                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <label for="subscription_add_billing_frequency" class="input-group-text">@lang('Every')</label>
                                    </div>
                                    <select name="billing_frequency" id="subscription_add_billing_frequency" class="form-control pr-0" required data-toggle="tooltip" data-placement="top" title="@lang('Set Billing Frequency')">
                                        @for ($i = 1; $i <= 40; $i++)
                                            <option value="{{ $i }}">@lang($i)</option>
                                        @endfor
                                    </select>
                                    <select name="billing_cycle" id="subscription_add_billing_cycle" class="form-control" required data-toggle="tooltip" data-placement="top" title="@lang('Set Billing Cycle')">
                                        @foreach (table('subscription.cycle') as $key => $val)
                                            <option value="{{ $key }}">@lang($val)</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="position-relative form-group">
                                <label for="subscription_add_price" class="col-6 p-0">@lang('Price')</label>
                                <label for="subscription_add_price_type" class="col-3 p-0">@lang('Currency')</label>
                                <div class="input-group">
                                    <input name="price" id="subscription_add_price" min="0" type="number" class="form-control" placeholder="@lang('0.00')" required data-toggle="tooltip" data-placement="top" title="@lang('Set Price')">
                                    <select name="price_type" id="subscription_add_price_type" class="form-control text-center" required data-toggle="tooltip" data-placement="top" title="@lang('Select Currency Code')">
                                        <option selected="" disabled="" value="" style="display: none;">@lang('Select')</option>
                                        @foreach (lib()->config->currency as $val)
                                            <option value="{{ $val['code'] }}" {{ lib()->prefer->currency == $val['code'] ? 'selected' : null }}>{{ $val['code'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="card mb-2 subscription_ltdval_card p-0">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="position-relative form-group">
                                            <label for="subscription_add_ltdval_price" class="">@lang('Market Recurring Price')</label>
                                            <input name="ltdval_price" id="subscription_add_ltdval_price" min="0" type="number" class="form-control" placeholder="@lang('0.00')" data-toggle="tooltip" data-placement="top" title="@lang('Set Price')">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="position-relative form-group">
                                            <label for="subscription_add_ltdval_frequency" class="">@lang('MRP Billing Cycle')</label>

                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <label for="subscription_add_ltdval_frequency" class="input-group-text">@lang('Every')</label>
                                                </div>
                                                <select name="ltdval_frequency" id="subscription_add_ltdval_frequency" class="form-control pr-0" data-toggle="tooltip" data-placement="top" title="@lang('Set Billing Frequency')">
                                                    <option selected="" disabled="" value="" style="display: none;">@lang('Select')</option>
                                                    @for ($i = 1; $i <= 40; $i++)
                                                        <option value="{{ $i }}">@lang($i)</option>
                                                    @endfor
                                                </select>
                                                <select name="ltdval_cycle" id="subscription_add_ltdval_cycle" class="form-control" data-toggle="tooltip" data-placement="top" title="@lang('Set Billing Cycle')">
                                                    <option selected="" disabled="" value="" style="display: none;">@lang('Select')</option>
                                                    @foreach (table('subscription.cycle') as $key => $val)
                                                        <option value="{{ $key }}">@lang($val)</option>
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
                                    <label for="subscription_add_note" class="">@lang('Notes')</label>
                                    <label class="switch ml-2" data-toggle="tooltip" data-placement="right" title="@lang('Include Notes in alert')">
                                        <input type="checkbox" name="include_notes" value="1">
                                        <span class="slider round"></span>
                                    </label>
                                </div>

                                <input name="note" id="subscription_add_note" maxlength="{{ len()->subscriptions->note }}" type="text" class="form-control" data-toggle="tooltip" data-placement="right" title="@lang('Enter Notes')">
                            </div>

                            <div class="position-relative form-group">
                                <div class="input-group">
                                    <div class="col-lg-7 p-0">

                                        <div class="switch_container">
                                            <label for="subscription_add_alert_id" class="">@lang('Alert Profile')</label>
                                            <label class="switch ml-2" data-toggle="tooltip" data-placement="right" title="@lang('Alert notification')">
                                                <input type="checkbox" name="alert_type" value="1" checked>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>

                                        <select name="alert_id" id="subscription_add_alert_id" class="form-control multiselect_init pr-2">
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
                                                <option value="{{ $val->id }}" data-alert_subs_type="{{ $val->alert_subs_type }}">{{ $val->alert_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-5 p-0">
                                        <label for="subscription_add_refund_days" class="">@lang('Refund Days')</label>
                                        <input name="refund_days" id="subscription_add_refund_days" type="number" min="1" max="{{ len()->subscriptions->refund_days }}" class="form-control" data-toggle="tooltip" data-placement="right" title="@lang('Enter Refund Days')">
                                    </div>
                                </div>
                            </div>

                            <div class="position-relative form-group" data-toggle="tooltip" data-placement="top" title="@lang('Set Expiry Date')">
                                <label for="subscription_add_expiry_date" class="">@lang('Expiry Date')</label>
                                <div class="input-group">
                                    <input name="expiry_date" id="subscription_add_expiry_date" type="text" placeholder="yyyy-mm-dd" maxlength="{{ len()->subscriptions->expiry_date }}" class="form-control" data-toggle="datepicker-and-icon">
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
                                        <label for="subscription_add_description" class="">@lang('Description')</label>
                                        <textarea name="description" id="subscription_add_description" maxlength="{{ len()->subscriptions->description }}" rows="3" class="form-control non_resizable" data-toggle="tooltip" data-placement="left" title="@lang('Product Description')"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-3 d-none">
                                    <div class="position-relative form-group">
                                        <label for="subscription_add_company_type" class="">@lang('Type')</label>
                                        <input name="company_type" id="subscription_add_company_type" type="text" class="form-control" readonly data-toggle="tooltip" data-placement="top" title="@lang('Product Type')">
                                    </div>
                                </div>
                                <div class="col-md-12 col-xl-6">
                                    <div class="row">
                                        <div class="col-md-12 col-xl-6">
                                            <div class="position-relative form-group">
                                                <label for="subscription_add_category_id" class="">@lang('Category')</label>
                                                <select name="category_id" id="subscription_add_category_id" class="form-control select2_init_tags" data-toggle="tooltip" data-placement="top" title="@lang('Product Category')">
                                                    {{-- <option selected="" disabled="" value="" style="display: none;">@lang('Select')</option> --}}
                                                    @foreach (lib()->product->category->get_all() as $val)
                                                        <option value="{{ $val->id }}">@lang($val->name)</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-xl-6 mt-4 pt-1">
                                            <button type="submit" class="btn btn-primary btn-lg btn-block pull-right" onclick="app.subscription.create(this);" data-toggle="tooltip" data-placement="top" title="@lang('Save your Subs/lifetime')">
                                                <i class="fa fa-save"></i>&nbsp;
                                                @lang('Add')
                                            </button>
                                        </div>
                                    </div>
                                    <div class="container barrating_container">
                                        <span>@lang('Subshero Rating'):&nbsp;</span>
                                        <div class="subscription_footer_barrating_container" data-toggle="tooltip" data-placement="top" title="@lang('Rate the Product')">
                                            <select class="bars-movie" name="rating">
                                                @foreach (table('subscription.rating') as $key => $val)
                                                    <option value="{{ $key }}">@lang($val)</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="add_msg_success" class="text-center" style="display: none;">
                        <i class="fa fa-check-circle fa-3x"></i>
                        <br>
                        <h5 class="m-4">@lang('Subscription has been added')</h5>
                        <button class="mb-2 mr-2 btn-transition btn btn-primary" onclick="lib.sub.modal_subscription_add();">@lang('Add More')</button>
                        <button class="mb-2 mr-2 btn-transition btn btn-primary" data-dismiss="modal">@lang('Back to Dashboard')</button>
                    </div>
                </div>
                <!-- <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">@lang('Add')</button>
                </div> -->

            </form>
        </div>
    </div>
</div>

<div id="modal_subscription_edit" class="modal fade subscription_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header subscription_modal_header">
                <img class="favicon img-thumbnail mr-1" src="{{ asset_ver('assets/images/favicon.ico') }}">
                <h5 class="modal-title">
                    <span id="modal_subscription_edit_title">@lang('Subscription Update')</span>
                    {{-- <small id="subscription_edit_company_type_label" class="type"></small> --}}
                </h5>

                {{-- <div class="subscription_header_barrating_container">
                    <select id="subscription_edit_rating_bar" class="bars-movie" name="rating">
                        @foreach (table('subscription.rating') as $key => $val)
                            <option value="{{ $key }}">@lang($val)</option>
                @endforeach
                </select>
            </div> --}}

                <div class="header_toggle_btn_container toggle btn btn-warning mr-3" id="subscription_edit_status_toggle_container" data-toggle="toggle" data-placement="top" title="@lang('Save your Subs/Lifetime')">
                    <input type="checkbox" id="subscription_edit_status_toggle" data-toggle="toggle" data-onstyle="success" data-offstyle="danger" checked>
                    <div class="toggle-group" data-toggle="tooltip" data-placement="left" title="@lang('If Active your Subs/Lifetime is tracked , Draft can not be track your Subs/Lifetime')">
                        <label class="btn btn-success toggle-on">@lang('Active')</label>
                        <label class="btn btn-warning toggle-off">@lang('Draft')</label>
                        <span class="toggle-handle btn btn-light"></span>
                    </div>
                </div>

                <div class="header_toggle_btn_container toggle btn btn-warning" id="subscription_edit_recurring_toggle_container" data-toggle="toggle">
                    <input type="checkbox" name="recurring" id="subscription_edit_recurring_toggle" onchange="app.subscription.update_recurring_check(this);" value="1" data-toggle="toggle" data-onstyle="success" data-offstyle="danger" checked>
                    <div class="toggle-group" data-toggle="tooltip" data-placement="left" title="@lang('If Product is subscription  toggle to Recur , If the product is lifetime toggle Once')">
                        <label class="btn btn_toggle_recurring toggle-on">@lang('Recur')</label>
                        <label class="btn btn-info toggle-off">@lang('Once')</label>
                        <span class="toggle-handle btn btn-light"></span>
                    </div>
                </div>

                <div class="header_toggle_btn_container btn" id="subscription_edit_recurring_toggle_container" data-toggle="toggle">
                    <div class="btn_expand_container btn_expand_hover mx-auto">
                        {{-- <button class="btn btn_collapse" onclick="app.subscription.pause(this);" data-toggle="tooltip" data-placement="top" title="@lang('Pause')">
                        <span class="fa fa-pause"></span>
                    </button> --}}

                        <button id="subscription_edit_popup_attachment" class="btn btn_collapse warning not_for_draft" onclick="app.subscription.attachment(this);" type="button" data-toggle="tooltip" data-placement="top" title="@lang('Attachments')">
                            <span class="badge p-0">
                                <i class="fa fa-paperclip fa-lg"></i>
                                <span class="attachment_count"></span>
                            </span>
                        </button>

                        <button id="subscription_edit_popup_addon" class="btn btn_collapse black not_for_draft" onclick="app.subscription.update_popup_addon(this);" type="button" data-toggle="tooltip" data-placement="top" title="@lang('Addon')">
                            <span class="fa fa-puzzle-piece mb-2"></span>
                        </button>

                        <button class="btn btn_collapse warning not_for_draft" onclick="app.subscription.update_popup_refund(this);" type="button" data-toggle="tooltip" data-placement="top" title="@lang('Refund')">
                            <span class="fa fa-hand-holding-usd mb-2"></span>
                        </button>

                        <button class="btn btn_collapse primary" onclick="app.subscription.update_popup_clone(this);" type="button" data-toggle="tooltip" data-placement="top" title="@lang('Clone')">
                            <ion-icon name="copy-outline" size="small"></ion-icon>
                        </button>
                        <button class="btn btn_collapse danger" onclick="app.subscription.update_popup_delete(this);" type="button" data-toggle="tooltip" data-placement="top" title="@lang('Delete')">
                            <ion-icon name="trash-outline" size="small"></ion-icon>
                        </button>

                        {{-- Button display expect cancel status --}}
                        <button class="btn btn_collapse warning not_for_draft" onclick="app.subscription.update_popup_cancel(this);" type="button" data-toggle="tooltip" data-placement="top" title="@lang('Cancel')">
                            <ion-icon name="close-circle-outline" size="small"></ion-icon>
                        </button>

                        <button class="btn btn_toggle p-0 pr-2" type="button" data-toggle="tooltip" data-placement="top" title="@lang('Actions')">
                            <ion-icon name="settings-outline" size!="large" style="font-size: 25px;"></ion-icon>
                        </button>
                    </div>
                </div>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
            <!-- <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">@lang('Add')</button>
                </div> -->
        </div>
    </div>
</div>

<div id="modal_subscription_clone" class="modal fade subscription_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header subscription_modal_header">
                <img class="favicon img-thumbnail mr-1" src="{{ asset_ver('assets/images/favicon.ico') }}">
                <h5 class="modal-title">
                    <span id="modal_subscription_clone_title">@lang('Subscription Clone')</span>
                    {{-- <small id="subscription_clone_company_type_label" class="type"></small> --}}
                </h5>

                {{-- <div class="subscription_header_barrating_container">
                    <select id="subscription_clone_rating_bar" class="bars-movie" name="rating">
                        @foreach (table('subscription.rating') as $key => $val)
                            <option value="{{ $key }}">@lang($val)</option>
                @endforeach
                </select>
            </div> --}}

                <div class="header_toggle_btn_container toggle btn btn-warning mr-3" id="subscription_clone_status_toggle_container" data-toggle="toggle">
                    <input type="checkbox" id="subscription_clone_status_toggle" data-toggle="toggle" data-onstyle="success" data-offstyle="danger" checked>
                    <div class="toggle-group" data-toggle="tooltip" data-placement="left" title="@lang('If Active your Subs/Lifetime is tracked , Draft can not be track your Subs/Lifetime')">
                        <label class="btn btn-success toggle-on">@lang('Active')</label>
                        <label class="btn btn-warning toggle-off">@lang('Draft')</label>
                        <span class="toggle-handle btn btn-light"></span>
                    </div>
                </div>

                <div class="header_toggle_btn_container toggle btn btn-warning mr-3" id="subscription_clone_recurring_toggle_container" data-toggle="toggle">
                    <input type="checkbox" name="recurring" id="subscription_clone_recurring_toggle" onchange="app.subscription.clone_recurring_check(this);" value="1" data-toggle="toggle" data-onstyle="success" data-offstyle="danger" checked>
                    <div class="toggle-group" data-toggle="tooltip" data-placement="bottom" title="@lang('If Product is subscription  toggle to Recur , If the product is lifetime toggle Once')">
                        <label class="btn btn_toggle_recurring toggle-on">@lang('Recur')</label>
                        <label class="btn btn-info toggle-off">@lang('Once')</label>
                        <span class="toggle-handle btn btn-light"></span>
                    </div>
                </div>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
            <!-- <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">@lang('Add')</button>
                </div> -->

        </div>
    </div>
</div>

<div id="modal_subscription_addon" class="modal fade subscription_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header subscription_modal_header">
                <img class="favicon img-thumbnail mr-1" src="{{ asset_ver('assets/images/favicon.ico') }}">
                <h5 class="modal-title">
                    <span id="modal_subscription_addon_title">@lang('Lifetime Addon')</span>
                    {{-- <small id="subscription_addon_company_type_label" class="type"></small> --}}
                </h5>

                {{-- <div class="subscription_header_barrating_container">
                    <select id="subscription_addon_rating_bar" class="bars-movie" name="rating">
                        @foreach (table('subscription.rating') as $key => $val)
                            <option value="{{ $key }}">@lang($val)</option>
                @endforeach
                </select>
            </div> --}}

                <div class="header_toggle_btn_container toggle btn btn-warning mr-3" id="subscription_addon_status_toggle_container" data-toggle="toggle">
                    <input type="checkbox" id="subscription_addon_status_toggle" data-toggle="toggle" data-onstyle="success" data-offstyle="danger" checked>
                    <div class="toggle-group" data-toggle="tooltip" data-placement="left" title="@lang('If Active your Subs/Lifetime is tracked , Draft can not be track your Subs/Lifetime')">
                        <label class="btn btn-success toggle-on">@lang('Active')</label>
                        <label class="btn btn-warning toggle-off">@lang('Draft')</label>
                        <span class="toggle-handle btn btn-light"></span>
                    </div>
                </div>

                <div class="header_toggle_btn_container toggle btn btn-warning mr-3" id="subscription_addon_recurring_toggle_container" data-toggle="toggle">
                    <input type="checkbox" name="recurring" id="subscription_addon_recurring_toggle" onchange="app.subscription.addon_recurring_check(this);" value="1" data-toggle="toggle" data-onstyle="success" data-offstyle="danger" checked>
                    <div class="toggle-group" data-toggle="tooltip" data-placement="bottom" title="@lang('If Product is subscription  toggle to Recur , If the product is lifetime toggle Once')">
                        <label class="btn btn_toggle_recurring toggle-on">@lang('Recur')</label>
                        <label class="btn btn-info toggle-off">@lang('Once')</label>
                        <span class="toggle-handle btn btn-light"></span>
                    </div>
                </div>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
            <!-- <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">@lang('Add')</button>
                </div> -->

        </div>
    </div>
</div>

<div id="modal_subscription_quick_add" class="modal fade subscription_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form id="frm_main_quick_add" action="{{ route('app/subscription/create_quick') }}" method="POST">
                @csrf
                <input type="hidden" name="url" id="main_quick_add_url">
                {{-- <input type="hidden" name="status" id="main_quick_add_status" value="1"> --}}
                <input type="hidden" name="alert_type" id="subscription_add_alert_type" value="1">

                <div class="modal-header subscription_modal_header">
                    <img class="favicon img-thumbnail mr-1" src="{{ asset_ver('assets/images/favicon.ico') }}">
                    <h5 class="modal-title">
                        <span id="modal_subscription_quick_add_title">@lang('Add Subscription (Quick)')</span>
                        {{-- <small id="main_quick_add_company_type_label" class="type"></small> --}}
                    </h5>

                    {{-- <div class="subscription_header_barrating_container">
                        <select class="bars-movie" name="rating">
                            @foreach (table('subscription.rating') as $key => $val)
                                <option value="{{ $key }}">@lang($val)</option>
                    @endforeach
                    </select>
                </div> --}}

                    <div class="header_toggle_btn_container toggle btn btn-warning mr-3" id="main_quick_add_status_toggle_container" data-toggle="toggle">
                        <input type="checkbox" name="status" id="main_quick_add_status" value="1" data-toggle="toggle" data-onstyle="success" data-offstyle="danger" checked>
                        <div class="toggle-group" data-toggle="tooltip" data-placement="left" title="@lang('If Active your Subs/Lifetime is tracked , Draft can not be track your Subs/Lifetime')">
                            <label class="btn btn-success toggle-on">@lang('Active')</label>
                            <label class="btn btn-warning toggle-off">@lang('Draft')</label>
                            <span class="toggle-handle btn btn-light"></span>
                        </div>
                    </div>

                    <div class="header_toggle_btn_container toggle btn btn-warning mr-3" id="main_quick_add_recurring_toggle_container" data-toggle="toggle">
                        <input type="checkbox" name="recurring" id="main_quick_add_recurring" onchange="app.subscription.create_quick_recurring_check(this);" value="1" data-toggle="toggle" data-onstyle="success" data-offstyle="danger" checked>
                        <div class="toggle-group" data-toggle="tooltip" data-placement="bottom" title="@lang('If Product is subscription  toggle to Recur , If the product is lifetime toggle Once')">
                            <label class="btn btn_toggle_recurring toggle-on">@lang('Recur')</label>
                            <label class="btn btn-info toggle-off">@lang('Once')</label>
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
                            <div class="position-relative form-group" data-toggle="tooltip" data-placement="left" title="@lang('Select Type')">
                                <label for="main_quick_add_type" class="">@lang('Type')</label>
                                <select name="type" id="main_quick_add_type" onchange="app.subscription.create_quick_type_check(this);" class="form-control" required>
                                    @foreach (table('subscription.type') as $key => $val)
                                        <option value="{{ $key }}">@lang($val)</option>
                                    @endforeach
                                </select>
                            </div>
                            <!--folder-->
                            <div class="position-relative form-group" data-toggle="tooltip" data-placement="left" title="@lang('Select Folder')">
                                <label for="main_quick_add_folder" class="">@lang('Folder')</label>
                                <select name="folder_id" id="main_quick_add_folder" class="form-control">
                                    <option selected="" disabled="" value="" style="display: none;">@lang('Select')</option>
                                    @foreach (lib()->folder->get_by_user() as $val)
                                        <option value="{{ $val->id }}" {{ $val->is_default == 1 ? 'selected' : null }}>{{ $val->name }} <span class="badge badge_recurring">{{ lib()->config->currency_symbol[$val->price_type] ?? 'All' }}</span></option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- <div class="position-relative form-group">
                                <label for="main_quick_add_price" class="">@lang('Price')</label>
                                <div class="input-group">
                                    <input name="price" id="main_quick_add_price" min="0" type="number" class="form-control" placeholder="@lang('0.00')" required data-toggle="tooltip" data-placement="left" title="@lang('Set Price value')">
                                    <select name="price_type" id="main_quick_add_price_type" class="form-control" required data-toggle="tooltip" data-placement="bottom" title="@lang('Select Currency Code')">
                                        @foreach (lib()->config->currency as $val)
                                            <option value="{{ $val['code'] }}" {{ lib()->prefer->currency == $val['code'] ? 'selected' : null }}>{{ $val['code'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div> -->
                         
                        </div>
                        <div class="col-md-6 col-xl-3">
                            <div class="position-relative form-group" data-toggle="tooltip" data-placement="top" title="@lang('Select Company name')">
                                <label for="main_quick_add_brand_id" class="">@lang('Company')</label>
                                <select name="brand_id" id="main_quick_add_brand_id" class="form-control">
                                    {{-- <option value=""></option>
                                    @foreach (lib()->product->get_all_except_default() as $val)
                                        <option value="{{ $val->id }}" data-path="{{ $val->image }}" data-product_type="{{ $val->product_type_name }}" data-description="{{ $val->description }}" data-price1_value="{{ $val->price1_value }}" data-currency_code="{{ $val->currency_code }}" data-url="{{ $val->url }}" data-billing_frequency="{{ $val->billing_frequency }}" data-billing_cycle="{{ $val->billing_cycle }}" data-pricing_type="{{ $val->pricing_type }}" data-favicon="{{ img_link($val->favicon) }}" data-category_id="{{ $val->category_id }}" data-refund_days="{{ $val->refund_days }}">
                                            {{ $val->product_name }}
                                        </option>
                                    @endforeach --}}
                                </select>
                            </div>
                            <div class="position-relative form-group">
                                <label for="main_quick_add_description" class="">@lang('Description (optional)')</label>
                                <input name="description" id="main_quick_add_description" maxlength="{{ len()->subscriptions->description }}" type="text" class="form-control" data-toggle="tooltip" data-placement="bottom" title="@lang('Description (optional)')">
                            </div>
                        </div>

                        <div class="col-md-6 col-xl-3">
                            <div class="position-relative form-group" data-toggle="tooltip" data-placement="top" title="@lang('Payment Date')">
                                <label for="main_quick_add_payment_date" class="">@lang('Payment Date')</label>
                                <div class="input-group">
                                    <input name="payment_date" id="main_quick_add_payment_date" value="{{ date('Y-m-d') }}" type="text" placeholder="yyyy-mm-dd" maxlength="{{ len()->subscriptions->payment_date }}" class="form-control bg-white" data-toggle="datepicker-and-icon" required>
                                    <div class="input-group-append datepicker-trigger">
                                        <div class="input-group-text">
                                            <i class="fa fa-calendar-alt"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="main_quick_add_billing_container" class="position-relative form-group">

                                <div class="switch_container">
                                    <label for="main_quick_add_billing_frequency" class="">@lang('Billing Cycle')</label>

                                    <label class="switch ml-2" data-toggle="tooltip" data-placement="right" title="{{ lib()->lang->get_billing_type(lib()->prefer->billing_type) }}">
                                        <input type="checkbox" name="billing_type" value="2" onclick="lib.do.billing_toggle_switch(this);" {{ lib()->prefer->billing_type == 2 ? 'checked' : null }}>
                                        <span class="slider round"></span>
                                    </label>
                                </div>

                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <label for="main_quick_add_billing_frequency" class="input-group-text">@lang('Every')</label>
                                    </div>
                                    <select name="billing_frequency" id="main_quick_add_billing_frequency" class="form-control pr-0" required data-toggle="tooltip" data-placement="bottom" title="@lang('Select Billing Frequency')">
                                        @for ($i = 1; $i <= 40; $i++)
                                            <option value="{{ $i }}">@lang($i)</option>
                                        @endfor
                                    </select>
                                    <select name="billing_cycle" id="main_quick_add_billing_cycle" class="form-control" required data-toggle="tooltip" data-placement="bottom" title="@lang('Set Billing Cycle')">
                                        @foreach (table('subscription.cycle') as $key => $val)
                                            <option value="{{ $key }}">@lang($val)</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                           
                            
                        </div>
                        <div class="col-md-6 col-xl-3">
                        <div class="position-relative form-group">
                                <label for="main_quick_add_price" class="">@lang('Price')</label>
                                <div class="input-group">
                                    <input name="price" id="main_quick_add_price" min="0" type="number" class="form-control" placeholder="@lang('0.00')" required data-toggle="tooltip" data-placement="left" title="@lang('Set Price value')">
                                    <select name="price_type" id="main_quick_add_price_type" class="form-control" required data-toggle="tooltip" data-placement="bottom" title="@lang('Select Currency Code')">
                                        @foreach (lib()->config->currency as $val)
                                            <option value="{{ $val['code'] }}" {{ lib()->prefer->currency == $val['code'] ? 'selected' : null }}>{{ $val['code'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                        <!-- <div class="col-md-6 col-xl-3"> -->
                            <div class="position-relative form-group">
                                <div class="input-group">
                                    <div class="col-7 p-0">

                                        <div class="switch_container">
                                            <label for="main_quick_add_alert_id" class="">@lang('Alert Profile')</label>

                                            <label class="switch ml-2" data-toggle="tooltip" data-placement="right" title="@lang('Alert notification')">
                                                <input type="checkbox" name="alert_type" value="1" checked>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>

                                        <select name="alert_id" id="main_quick_add_alert_id" class="form-control multiselect_init pr-2">
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
                                                <option value="{{ $val->id }}" data-alert_subs_type="{{ $val->alert_subs_type }}">{{ $val->alert_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-5 p-0">
                                        <label for="main_quick_add_refund_days" class="">@lang('Refund Days')</label>
                                        <input name="refund_days" id="main_quick_add_refund_days" type="number" min="1" max="{{ len()->subscriptions->refund_days }}" class="form-control" data-toggle="tooltip" data-placement="right" title="@lang('Enter Refund Days')">
                                    </div>
                                </div>
                            </div>
                          </div> 

                        <div class="col-md-6 col-xl-9">
                        </div>
                        <div class="col-md-6 col-xl-3">
                            <button type="submit" class="btn btn-primary btn-lg btn-block" onclick="app.subscription.create_quick(this);" data-toggle="tooltip" data-placement="top" title="@lang('Save your Subs/Lifetime')">
                                <i class="fa fa-save"></i>&nbsp;
                                @lang('Quick Add')
                            </button>
                        </div>

                        <div class="col-12">
                            {{-- <br> --}}
                            <div class="container barrating_container">
                                <span>@lang('Subshero Rating'):&nbsp;</span>
                                <div class="subscription_footer_barrating_container" data-toggle="tooltip" data-placement="top" title="@lang('Rate the Product')">
                                    <select class="bars-movie" name="rating">
                                        @foreach (table('subscription.rating') as $key => $val)
                                            <option value="{{ $key }}">@lang($val)</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div id="quick_add_msg_success" class="text-center" style="display: none;">
                        <i class="fa fa-check-circle fa-3x"></i>
                        <br>
                        <h5 class="m-4">@lang('Subscription has been added')</h5>
                        <button class="mb-2 mr-2 btn-transition btn btn-primary" onclick="lib.sub.modal_subscription_quick_add();">@lang('Add More')</button>
                        <button class="mb-2 mr-2 btn-transition btn btn-primary" data-dismiss="modal">@lang('Back to Dashboard')</button>
                    </div>
                </div>
                <!-- <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">@lang('Add')</button>
                </div> -->
            </form>

        </div>
    </div>
</div>

<div id="modal_subscription_attachment" class="modal fade subscription_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <span id="modal_subscription_attachment_title">@lang('Subscription Attachments')</span>
                    {{-- <small id="subscription_attachment_company_type_label" class="type"></small> --}}
                </h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="position-relative form-group text-center mx-auto pond" data-toggle="tooltip" data-placement="left">
                    <input type="file" class="filepond" id="subscription_attachment_image_file" name="file" tyle="display: none;">
                </div>

                <div class="item_container custom_scrollbar">
                    <table class="table table-hover">
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">@lang('Add')</button>
                </div> -->
        </div>
    </div>
</div>

<template id="tpl_subscription_attachment_row">
    <tr data-id="__FILE_ID__">
        <td>
            <span class="file_name">__FILE_NAME__</span>
        </td>
        <td class="status_container">
            {{-- <span class="file_size">__FILE_SIZE__</span> --}}
            <span class="file_size">
                <div class="progress">
                    <div class="progress-bar" data-id="__FILE_ID__" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <span class="status_failed text-danger" style="display: none;">@lang('Failed')</span>
            </span>
        </td>
        <td class="btn_container">
            <div class="upload_buttons">
                <button class="btn btn_cancel px-1" onclick="app.subscription.attachment_cancel('__FILE_ID__');" title="@lang('Cancel')" data-toggle="tooltip" data-placement="top">
                    <i class="fa fa-times"></i>
                </button>
                <button class="btn btn_retry px-1" onclick="app.subscription.attachment_upload('__FILE_ID__');" title="@lang('Retry')" data-toggle="tooltip" data-placement="top" style="display: none;">
                    <i class="fa fa-redo fa-sm"></i>
                </button>
            </div>
            <div class="action_buttons" style="display: none;">
                <a class="btn btn_view px-1" href="#" target="_blank" title="@lang('View')" data-toggle="tooltip" data-placement="top">
                    <i class="fa fa-eye"></i>
                </a>
                <button class="btn btn_download px-1" onclick="lib.do.download_file('__FILE_PATH__', '__FILE_NAME__', this);" title="@lang('Download')" data-toggle="tooltip" data-placement="top">
                    <i class="fa fa-download"></i>
                </button>
                <button class="btn px-1" onclick="app.subscription.attachment_delete(this);" title="@lang('Delete')" data-toggle="tooltip" data-placement="top">
                    <i class="fa fa-trash"></i>
                </button>
            </div>
        </td>
    </tr>
</template>

<div id="modal_subscription_history" class="modal fade subscription_history_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

        </div>
    </div>
</div>

<div id="modal_subscription_marketplace_edit" class="modal fade subscription_history_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

        </div>
    </div>
</div>

<div id="modal_subscription_invoice_parse" class="modal fade subscription_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header subscription_modal_header">
                <h5 class="modal-title">
                    <span id="modal_subscription_invoice_parse_title">@lang('Invoice Parser')</span>
                </h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="frm_main_invoice_parse" action="{{ route('pdfparser/parse') }}" method="POST">
                @csrf
                <div class="subscription_invoice_file_input position-relative form-group text-center mx-4 py-5" data-toggle="tooltip" data-placement="left" title="@lang('Invoice File')">
                    <input type="file" class="filepond" id="subscription_invoice_file" name="invoice_file" accept=".pdf" data-size="200x200" style="display: none;">
                </div>
            </form>
            <div id="subscription_invoice_load_msg" class="subscription_invoice_load_msg text-center my-4">
                <p class="m-0"><img src="{{ asset('/assets/images/subscription_pdf/scan.gif') }}"/></p>
                <p class="m-0">@lang('Scanning Documents')</p>
                <p class="m-0">@lang('It might take upto 30 seconds')</p>
            </div>
            <div id="subscription_invoice_preview_table" style="display: none;">
                <div class="table-responsive card p-3">
                    <table id="subscription_invoice_tbl_subscription" class="align-middle mb-0 table table-borderless table-striped table-hover text-center mb-4"></table>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="modal_notification_edit" class="modal fade subscription_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl-4">
        <div class="modal-content">
            <div class="modal-header subscription_modal_header">
                <h5 class="modal-title">
                    <span id="modal_notification_edit_title">@lang('Notification')</span>
                </h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="@lang('Close')">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        // Edit -> Status
        $(document).on('click', '#subscription_edit_status_toggle_container', function() {

            let checkbox = $('#subscription_edit_status_toggle');
            if (checkbox.length) {
                $('#subscription_edit_status').val(checkbox.get(0).checked ? '1' : '0');
            }
        });

        // Edit -> Recurring
        $(document).on('click', '#subscription_edit_recurring_toggle_container', function() {

            let checkbox = $('#subscription_edit_recurring_toggle');
            if (checkbox.length) {
                $('#subscription_edit_recurring').val(checkbox.get(0).checked ? '1' : '0');
            }
        });


        // Clone -> Status
        $(document).on('click', '#subscription_clone_status_toggle_container', function() {

            let checkbox = $('#subscription_clone_status_toggle');
            if (checkbox.length) {
                $('#subscription_clone_status').val(checkbox.get(0).checked ? '1' : '0');
            }
        });

        // Clone -> Recurring
        $(document).on('click', '#subscription_clone_recurring_toggle_container', function() {

            let checkbox = $('#subscription_clone_recurring_toggle');
            if (checkbox.length) {
                $('#subscription_clone_recurring').val(checkbox.get(0).checked ? '1' : '0');
            }
        });



        // Addon -> Status
        $(document).on('click', '#subscription_addon_status_toggle_container', function() {

            let checkbox = $('#subscription_addon_status_toggle');
            if (checkbox.length) {
                $('#subscription_addon_status').val(checkbox.get(0).checked ? '1' : '0');
            }
        });

        // Addon -> Recurring
        $(document).on('click', '#subscription_addon_recurring_toggle_container', function() {

            let checkbox = $('#subscription_addon_recurring_toggle');
            if (checkbox.length) {
                $('#subscription_addon_recurring').val(checkbox.get(0).checked ? '1' : '0');
            }
        });


        app.subscription.o.billing_frequency = '{{ lib()->user->default->billing_frequency }}';
        app.subscription.o.billing_cycle = '{{ lib()->user->default->billing_cycle }}';
        app.subscription.o.currency_code = '{{ lib()->user->default->currency_code }}';

        $('.multiselect_init').multiselect({
            inheritClass: true,
        });

        app.subscription.filter_alert_profile('#subscription_add_alert_id', 1);
        app.subscription.filter_alert_profile('#main_quick_add_alert_id', 1);




        // $('#subscription_add_folder_id').select2({
        //     ajax: {
        //         url: "{{ route('app/select2/folder/search') }}",
        //         dataType: 'json'
        //         // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
        //     }
        // });



    });
        function subscription_invoice_make_table(subscriptions) {
            const columns = [
                {
                    data: 'id',
                    visible: false,
                    orderable: false,
                    searchable: false,
                },
                {
                    data: 'brand_id',
                    visible: false,
                    orderable: false,
                    searchable: false,
                },
                {
                    data: 'user_id',
                    visible: false,
                    orderable: false,
                    searchable: false,
                },
                {
                    data: 'product_name',
                    title: lang('Name'),
                    orderable: false,
                    searchable: false,
                },
                {
                    data: 'type',
                    title: lang('Type'),
                    orderable: false,
                    searchable: false,
                },
                {
                    data: 'price',
                    title: lang('Price'),
                    orderable: false,
                    searchable: false,
                },
                {
                    data: 'payment_date',
                    title: lang('Payment Date'),
                    orderable: false,
                    searchable: false,
                },
                {
                    data: 'payment_mode',
                    title: lang('Payment Method'),
                    orderable: false,
                    searchable: false,
                },
                {
                    data: 'billing_cycle',
                    title: lang('Billing Cycle'),
                    orderable: false,
                    searchable: false,
                },
                {
                    data: 'status',
                    title: lang('Subscription Status'),
                    orderable: false,
                    searchable: false,
                },
                {
                    data: 'recurring',
                    title: lang('Subscription Recuring'),
                    orderable: false,
                    searchable: false,
                }
            ];
            app.subscription.o.subscription_invoice_table = $('#subscription_invoice_tbl_subscription').DataTable({
                processing: true,
                data: subscriptions['aaData'],
                order: [
                    [0, 'desc'],
                ],
                aLengthMenu: [
                    [5, 10, 25, 50, 100],
                    [5, 10, 25, 50, 100],
                ],
                iDisplayLength: 5,
                columns: columns,
            })
        }
</script>
