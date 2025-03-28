@extends(request()->ajax() ? 'public/layouts/ajax' : 'public/layouts/marketplace')

@section('head')
@endsection

@section('content')
    <div class="main-card mb-3 marketplace_checkout_section">
        <form id="marketplace_checkout_form" action="{{ route('app/marketplace/checkout/save') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-5 col-xl-6">
                    <h2 class="font-weight-bold">@lang('Checkout')</h2>
                    <br>
                    <h5 class="font-weight-bold">@lang('Contact Info')</h5>
                    <br>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="marketplace_checkout_first_name" class="">@lang('First Name')</label>
                                <input name="buyer_first_name" id="marketplace_checkout_first_name" value="{{ $buyer->first_name ?? null }}" maxlength="{{ len()->marketplace_orders->buyer_first_name }}" type="text" class="form-control" required title="@lang('Enter your First Name')" data-toggle="tooltip" data-placement="left">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="marketplace_checkout_last_name" class="">@lang('Last Name')</label>
                                <input name="buyer_last_name" id="marketplace_checkout_last_name" value="{{ $buyer->last_name ?? null }}" maxlength="{{ len()->marketplace_orders->buyer_last_name }}" type="text" class="form-control" required title="@lang('Enter your Last Name')" data-toggle="tooltip" data-placement="right">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="marketplace_checkout_email" class="">@lang('Email')</label>
                                <input name="buyer_email" id="marketplace_checkout_email" value="{{ $buyer->email ?? null }}" type="email" maxlength="{{ len()->marketplace_orders->buyer_email }}" class="form-control" required title="@lang('Enter your Email ID')" data-toggle="tooltip" data-placement="left">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="marketplace_checkout_phone" class="">@lang('Phone Number')</label>
                                <input name="buyer_phone" id="marketplace_checkout_phone" value="{{ $buyer->phone ?? null }}" type="text" maxlength="{{ len()->marketplace_orders->buyer_phone }}" class="form-control" required title="@lang('Enter your Phone Number')" data-toggle="tooltip" data-placement="right">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="marketplace_checkout_company_name" class="">@lang('Company')</label>
                                <input name="buyer_company_name" id="marketplace_checkout_company_name" value="{{ $buyer->company_name ?? null }}" type="text" maxlength="{{ len()->marketplace_orders->buyer_company_name }}" class="form-control" title="@lang('Enter your Company Name')" data-toggle="tooltip" data-placement="left">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="marketplace_checkout_country" class="">@lang('Country')</label>
                                <select name="buyer_country" id="marketplace_checkout_country" class="form-control" required data-toggle="tooltip" data-placement="right" title="@lang('Select the Country')">
                                    <option selected="" disabled="" value="" style="display: none;">@lang('Select')</option>
                                    @foreach (lib()->config->country as $val)
                                        <option value="{{ $val['isocode'] }}" {{ !empty($buyer->country) && $buyer->country == $val['isocode'] ? 'selected' : null }}>{{ $val['shortname'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-md-2 col-xl-2">
                </div>
                <div class="col-md-5 col-xl-4">
                    <div class="items_container">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">@lang('Item')</th>
                                    <th scope="col" class="text-right">@lang('Price')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!empty($marketplace_item))
                                    <tr class="cart_item">
                                        <td>
                                            <img src="{{ $marketplace_item->product_logo }}" alt="{{ $marketplace_item->product_name }}" class="img-fluid logo" style="max-width: 50px;">
                                            <span class="name ml-2">{{ $marketplace_item->product_name }}</span>
                                        </td>
                                        <td class="text-right">
                                            {{ lib()->get->currency_symbol($marketplace_item->currency_code) }}
                                            {{ $marketplace_item->sale_price }}
                                        </td>
                                    </tr>
                                @endif

                                <tr class="subtotal_row">
                                    <td class="text-center">@lang('Subtotal')</td>
                                    <td class="text-right">
                                        {{ lib()->get->currency_symbol($marketplace_item->currency_code) }}
                                        {{ $charges->subtotal }}
                                    </td>
                                </tr>
                                <tr class="charges_row">
                                    <td class="text-center">@lang('Charges')</td>
                                    <td class="text-right">
                                        {{ lib()->get->currency_symbol($marketplace_item->currency_code) }}
                                        {{ $charges->tax }}
                                    </td>
                                </tr>
                                <tr class="total_row">
                                    <td class="text-right">@lang('Total')</td>
                                    <td class="text-right">
                                        {{ lib()->get->currency_symbol($marketplace_item->currency_code) }}
                                        {{ $charges->total }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <br>
                    <h5 class="font-weight-bold">@lang('Choose Payment Method')</h5>
                    <br>
                    <div class="payment_methods_container">
                        @foreach ($payment_methods as $method)
                            <div class="payment_method">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="custom-control custom-radio mt-1">
                                            <input type="radio" id="marketplace_checkout_payment_method_{{ $method->id }}" name="payment_method" value="{{ $method->id }}" class="custom-control-input" checked required>
                                            <label class="custom-control-label" for="marketplace_checkout_payment_method_{{ $method->id }}">{{ $method->title }}</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <img src="{{ asset_ver($method->image) }}" alt="{{ $method->name }}" class="img-fluid pull-right">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <br>
                    <button type="submit" class="btn btn_green btn-lg btn-block pull-right" onclick="app.marketplace.checkout_save(this);">
                        @lang('Confirm and Pay')
                    </button>
                </div>
            </div>
        </form>

    </div>
@endsection
