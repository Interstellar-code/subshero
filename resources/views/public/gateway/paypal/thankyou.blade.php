@extends(request()->ajax() ? 'public/layouts/ajax' : 'public/layouts/default')

@section('head')
@endsection

@section('content')
    <div class="main-card mb-3 marketplace_thankyou_section mx-auto">
        <div class="row">
            <div class="col-md-10 col-xl-6 mx-auto">
                <h2 class="font-weight-bold text-center">@lang('Thank You for your Order')</h2>

                <br>
                <br>

                <div class="order_info">
                    <table class="text-center">
                        <thead>
                            <tr>
                                <th>@lang('Order number')</th>
                                <th>@lang('Date')</th>
                                <th>@lang('Total')</th>
                                <th>@lang('Payment method')</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>#{{ $marketplace_order->id }}</td>
                                <td>{{ $marketplace_order->created_at->format('d/m/Y') }}</td>
                                <td>
                                    {{ lib()->get->currency_symbol($marketplace_order->currency_code) }}
                                    {{ $marketplace_order->total }}
                                </td>
                                <td>@lang('PayPal')</td>
                            </tr>
                        </tbody>
                    </table>
                </div>


                <br>
                <br>
                <h5 class="font-weight-bold">@lang('Products in the Order')</h5>
                <table class="table mb-0 order_item_table">
                    <thead>
                        <tr>
                            <th scope="col">@lang('Item')</th>
                            <th scope="col" class="text-right">@lang('Price')</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="cart_item">
                            <td>
                                <img src="{{ $marketplace_order->product_logo }}" alt="{{ $marketplace_order->product_name }}" class="img-fluid logo" style="max-width: 50px;">
                                <span class="name ml-2">{{ $marketplace_order->product_name }}</span>
                            </td>
                            <td class="text-right">
                                {{ lib()->get->currency_symbol($marketplace_order->currency_code) }}
                                {{ $marketplace_order->sale_price }}
                            </td>
                        </tr>
                    </tbody>
                </table>

                <br>

                @if (!empty($marketplace_order->marketplace_item->product_description))
                    <div class="order_instruction">
                        <h5 class="font-weight-bold">@lang('Order Instructions'):</h5>
                        <p>{{ $marketplace_order->marketplace_item->product_description }}</p>
                    </div>
                    <br>
                @endif

                <div class="row">
                    <div class="col-md-8 col-xl-9">
                    </div>
                    <div class="col-md-4 col-xl-3">
                        <a class="btn btn_green btn-lg btn-block pull-right action_buttion" href="{{ url('/') }}">
                            @lang('Go to Dashboard')
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
