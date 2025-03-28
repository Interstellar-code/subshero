@extends(request()->ajax() ? 'public/layouts/ajax' : 'public/layouts/marketplace')

@section('head')
@endsection

@section('content')
    <div class="main-card mb-3 marketplace_user_section">
        <div class="row">
            <div class="col-md-6">
                <div class="profile_container">
                    <div class="">
                        <img class="img-fluid profile_image" src="{{ img_url($seller->image) }}" alt="{{ $seller->name }}">
                    </div>
                    <div class="section_1">
                        <h3 class="m-0 font-weight-bold">{{ $seller->name }}</h3>
                        @if (!empty($seller->created_at))
                            <p>@lang('User since') {{ $seller->created_at->format('M Y') }}</p>
                        @endif
                        @if (!empty($seller->country_short_name))
                            <p class="mb-2 font-weight-bold">
                                <img class="icon mr-1" src="{{ asset_ver('assets/icons/marker.svg') }}">
                                {{ $seller->country_short_name }}
                            </p>
                        @endif
                        <p class="font-weight-bold">
                            <img class="icon mr-1" src="{{ asset_ver('assets/icons/badge-check.svg') }}">
                            @lang('Verified User')
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                @if (!empty($seller->company_name))
                    <p class="font-weight-bold mb-2">
                        <img class="icon icon_border_round" src="{{ asset_ver('assets/icons/building.svg') }}">
                        {{ $seller->company_name }}
                    </p>
                @endif
                @if (!empty($seller->facebook_username))
                    <p class="font-weight-bold mb-2">
                        <img class="icon icon_border_round" src="{{ asset_ver('assets/icons/facebook.svg') }}">
                        /{{ $seller->facebook_username }}
                    </p>
                @endif
                <p class="font-weight-bold mb-2">
                    <img class="icon icon_border_round" src="{{ asset_ver('assets/icons/envelope-regular.svg') }}">
                    @if ($seller->status == 1)
                        @lang('Email Verified')
                    @else
                        @lang('Email Not Verified')
                    @endif
                </p>
                <p class="font-weight-bold mb-2">
                    <img class="icon icon_border_round" src="{{ asset_ver('assets/icons/logo-paypal.svg') }}">
                    @lang('Payments via') PayPal
                </p>
            </div>
            <div class="col-md-3">
                @if (!empty($seller->facebook_username))
                    <a class="btn btn_grey pull-right" href="https://facebook.com/{{ $seller->facebook_username }}" target="_blank">
                        @lang('View on Facebook')
                    </a>
                @endif
            </div>
        </div>

        <br>
        <br>

        <h2 class="font-weight-bold">@lang('Products on Marketplace')</h2>
        <div class="row marketplace_product_section">
            @forelse ($marketplace_items as $item)
                @if (empty($item->subscription))
                    @continue
                @endif
                <div class="col-md-6 col-xl-4 pr-5 mb-4">
                    <div class="card product_card">
                        <div class="card-body">
                            <form class="marketplace_item_details" action="{{ route('app/marketplace/buy') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{ $item->id }}">

                                <div class="product_body">
                                    <img class="img-fluid product_image" src="{{ $item->product_logo }}" alt="{{ $item->name }}">
                                    <h4 class="m-0 font-weight-bold mt-4">{{ $item->product_name }}</h4>
                                    <div class="row mt-2">
                                        @if (!empty($item->product_url))
                                            <div class="col-lg-6">
                                                <a class="m-0 font-weight-bold text-decoration-none" href="{{ $item->product_url }}" target="_blank">
                                                    @lang('Product Home')
                                                    <img class="icon" src="{{ asset_ver('assets/icons/arrow-up-right.svg') }}">
                                                </a>
                                            </div>
                                        @endif
                                        @if (!empty($item->sales_url))
                                            <div class="col-lg-6">
                                                <a class="m-0 font-weight-bold text-decoration-none" href="{{ $item->sales_url }}" target="_blank">
                                                    @lang('Sales Page')
                                                    <img class="icon" src="{{ asset_ver('assets/icons/arrow-up-right.svg') }}">
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                    <hr>
                                    <p class="m-0">
                                        {{ $item->product_description }}
                                    </p>

                                    <br>
                                </div>

                                <div class="spacer"></div>

                                <div class="product_footer">

                                    <div class="product_property">
                                        <p class="mb-2">
                                            <img class="icon mr-1" src="{{ asset_ver('assets/icons/marker.svg') }}">
                                            @lang('Category'):
                                            <strong>{{ $item->product_category->name }}</strong>
                                        </p>
                                        <p class="mb-2">
                                            <img class="icon mr-1" src="{{ asset_ver('assets/icons/marker.svg') }}">
                                            @lang('Bought From'):
                                            <strong>{{ $item->product_platform->name }}</strong>
                                        </p>
                                        @if (!empty($item->plan_name))
                                            <p class="mb-2">
                                                <img class="icon mr-1" src="{{ asset_ver('assets/icons/marker.svg') }}">
                                                @lang('Plan'):
                                                <strong>{{ $item->plan_name }}</strong>
                                            </p>
                                        @endif
                                    </div>

                                    <br>

                                    {{-- <div class="product_gallery">
                                        <img class="img-fluid" src="{{ asset_ver('assets/images/marketplace/rectangle_1.svg') }}" alt="">
                                        <img class="img-fluid" src="{{ asset_ver('assets/images/marketplace/rectangle_2.svg') }}" alt="">
                                        <img class="img-fluid" src="{{ asset_ver('assets/images/marketplace/rectangle_3.svg') }}" alt="">
                                        <img class="img-fluid" src="{{ asset_ver('assets/images/marketplace/rectangle_4.svg') }}" alt="">
                                    </div> --}}

                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="sale_price">
                                                {{ lib()->get->currency_symbol($item->currency_code) }}
                                                {{ $item->sale_price }}
                                            </p>
                                            <p class="original_price">
                                                @lang('Original Price'):
                                                {{ lib()->get->currency_symbol($item->currency_code) }}
                                            @empty($item->subscription)
                                                {{ $item->sale_price }}
                                            @else
                                                @if ($item->subscription->ltdval_price <= 0)
                                                    {{ $item->subscription->price }}
                                                @else
                                                    {{ $item->subscription->ltdval_price }}
                                                @endif
                                            @endempty
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <button type="submit" class="btn btn_green btn-lg btn-block pull-right" onclick="app.marketplace.cart_item_create(this);">
                                            @lang('Buy Now')
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-md-6 col-xl-3">
                <div class="alert alert-info">
                    @lang('No products found.')
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection
