<div class="container-fluid">
    @if( $carts && count($carts) > 0 )
        <div class="row">
            <div class="col-xxl-12 col-xl-12 mx-auto">
                <div class="shadow-sm bg-white p-3 p-lg-4 rounded text-left">
                    <div class="mb-4">
                        <div class="row gutters-5 d-none d-lg-flex border-bottom mb-3 pb-3">
                            <div class="col-md-5 fw-600">{{ translate('Product')}}</div>
                            <div class="col fw-600">{{ translate('Price')}}</div>
                            <!-- <div class="col fw-600">{{ translate('Tax')}}</div> -->
                            <div class="col fw-600 text-center">{{ translate('Quantity')}}</div>
                            <div class="col fw-600">{{ translate('Total')}}</div>
                            <div class="col-auto fw-600">{{ translate('Remove')}}</div>
                        </div>
                        <ul class="list-group list-group-flush">
                            @php
                                $total = 0;
                            @endphp
                            @foreach ($carts as $key => $cartItem)
                                @php
                                    $product = \App\Product::find($cartItem['product_id']);
                                    $product_stock = $product->stocks->where('variant', $cartItem['variation'])->first();
                                    $total = $total + ($cartItem['price']) * $cartItem['quantity'];
                                    $product_name_with_choice = $product->getTranslation('name');
                                    if ($cartItem['variation'] != null) {
                                        $product_name_with_choice = $product->getTranslation('name').' - '.$cartItem['variation'];
                                    }
                                @endphp
                                <li class="list-group-item px-0 px-lg-3">
                                    <div class="c-preloader text-center absolute-center">
                                        <i class="las la-spinner la-spin la-3x opacity-70"></i>
                                    </div>
                                    <div class="row gutters-5 align-items-center">
                                        <div class="col-lg-5 d-flex align-items-center">
                                            <span class="mr-2 ml-0">
                                                <img
                                                    src="{{ uploaded_asset($product->thumbnail_img) }}"
                                                    class="img-fit size-100px rounded"
                                                    alt="{{ $product->getTranslation('name')  }}"
                                                >
                                            </span>
                                            <span class="fs-14 opacity-60">{{ $product_name_with_choice }}</span>
                                        </div>

                                        <div class="col-lg-2 col-4 col-sm-3 order-1 order-lg-0 my-3 my-lg-0">
                                            <span class="opacity-60 fs-12 d-block d-lg-none">{{ translate('Price')}}</span>
                                            <span class="fw-600 fs-16">{{ single_price($cartItem['price']) }}</span>
                                        </div>
                                        <!-- <div class="col-lg-2 col-4 order-2 order-lg-0 my-3 my-lg-0">
                                            <span class="opacity-60 fs-12 d-block d-lg-none">{{ translate('Tax')}}</span>
                                            <span class="fw-600 fs-16">{{ single_price($cartItem['tax']) }}</span>
                                        </div> -->

                                        <div class="col-lg-2 ml-lg-5 col-sm-3 col-6 order-4 order-lg-0 quantity_padding">
                                            @if($cartItem['digital'] != 1)
                                                <div class="row no-gutters align-items-center aiz-plus-minus mr-2 ml-0">
                                                    <button class="btn col-auto btn-icon btn-sm btn-circle btn-light" type="button" data-type="minus" data-field="quantity[{{ $cartItem['id'] }}]">
                                                        <i class="las la-minus"></i>
                                                    </button>
                                                    <input type="number" name="quantity[{{ $cartItem['id'] }}]" class="col-sm-5 col-auto border-0 text-center flex-grow-1 fs-16 input-number" placeholder="1" value="{{ $cartItem['quantity'] }}" min="{{ $product->min_qty }}" max="{{ $product_stock->qty }}" onchange="updateQuantity({{ $cartItem['id'] }}, this)" data-min-message="{{ translates('min_limit_reached') }}" data-max-message="{{ translates('max_limit_reached') }}">
                                                    <button class="btn col-auto btn-icon btn-sm btn-circle btn-light" type="button" data-type="plus" data-field="quantity[{{ $cartItem['id'] }}]">
                                                        <i class="las la-plus"></i>
                                                    </button>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="col-lg col-sm-3 col-4 order-3 order-lg-0 my-3 my-lg-0">
                                            <span class="opacity-60 fs-12 d-block d-lg-none">{{ translate('Total')}}</span>
                                            <span class="fw-600 fs-16 text-primary">{{ single_price(($cartItem['price']) * $cartItem['quantity']) }}</span>
                                        </div>
                                        <div class="col-lg-auto col-sm-3 col-6 order-5 order-lg-0 text-right">
                                            <a href="javascript:void(0)" onclick="removeFromCartView(event, {{ $cartItem['id'] }})" class="btn btn-icon btn-sm btn-soft-primary btn-circle">
                                                <!-- <i class="las la-trash"></i> -->
                                                <img src="{{ static_asset('assets/img/trash-solid.svg') }}" alt="" width="18" height="18" style="vertical-align:initial;">
                                            </a>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="px-3 py-3 mb-4 border-top d-flex justify-content-between">
                        <span class="opacity-60 fs-15">{{translate('Subtotal')}}</span>
                        <span class="fw-600 fs-17">{{ single_price($total) }}</span>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-md-6 text-center text-md-left order-1 order-md-0">
                            <a href="{{ route('home') }}" class="btn btn-link">
                                <i class="las la-arrow-left"></i>
                                {{ translate('Return to shop')}}
                            </a>
                        </div>
                        <div class="col-md-6 text-center text-md-right">
                            @if(Auth::check())
                                <a href="{{ route('checkout.shipping_info') }}" class="btn btn-primary fw-600">
                                    {{ translate('Continue to Shipping')}}
                                </a>
                            @else
                                <button class="btn btn-primary fw-600" onclick="showCheckoutModal()">{{ translate('Continue to Shipping')}}</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-xl-8 mx-auto">
                <div class="shadow-sm bg-white p-4 rounded">
                    <div class="text-center p-3">
                        <i class="las la-frown la-3x opacity-60 mb-3"></i>
                        <h3 class="h4 fw-700">{{translate('Your Cart is empty')}}</h3>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<script type="text/javascript">
    AIZ.extra.plusMinus();
    $('.c-preloader').hide();
</script>
