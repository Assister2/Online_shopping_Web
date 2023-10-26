@extends('frontend.layouts.app')

@section('content')
    <section class="pt-5 mb-4">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-8 mx-auto">
                    <div class="row aiz-steps arrow-divider">
                        <div class="col done">
                            <div class="text-center text-success cart_timeline">
                                <i class="la-3x mb-2 las la-shopping-cart"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block">{{ translate('1 My Cart')}}</h3>
                            </div>
                        </div>
                        <div class="col done">
                            <div class="text-center text-success cart_timeline">
                                <i class="la-3x mb-2 las la-map"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block">{{ translate('2 Shipping info')}}</h3>
                            </div>
                        </div>
                        <div class="col done">
                            <div class="text-center text-success cart_timeline">
                                <i class="la-3x mb-2 las la-truck"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block">{{ translate('3 Delivery info')}}</h3>
                            </div>
                        </div>
                        <div class="col done">
                            <div class="text-center text-success cart_timeline">
                                <i class="la-3x mb-2 las la-credit-card"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block">{{ translate('4 Payment')}}</h3>
                            </div>
                        </div>
                        <div class="col active">
                            <div class="text-center text-primary cart_timeline">
                                <i class="la-3x mb-2 las la-check-circle"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block">{{ translate('5 Confirmation')}}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="py-4">
        <div class="container-fluid text-left">
            <div class="row">
                <div class="col-xl-8 mx-auto">
                    <div class="card shadow-sm border-0 rounded">
                        <div class="card-body">
                            <div class="text-center py-4 mb-4">
                                <i class="la la-check-circle la-3x text-success mb-3"></i>
                                <h1 class="h3 mb-3 fw-600">{{ translate('Thank You for Your Order!')}}</h1>
                                <p class="opacity-70 font-italic">{{  translate('A copy or your order summary has been sent to') }} {{ json_decode($orders->first()->shipping_address)->email }}</p>
                            </div>

                            @foreach($orders as $key => $order)
                                @if ($loop->first)
                                    <div class="mb-4">
                                        <h5 class="fw-600 mb-3 fs-17 pb-2">{{ translate('Order Summary')}}</h5>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <table class="table">
                                                    <tr>
                                                        <td class="w-50 fw-600">{{ translate('Name')}}:</td>
                                                        <td>{{ json_decode($order->shipping_address)->name }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="w-50 fw-600">{{ translate('Email')}}:</td>
                                                        <td>{{ json_decode($order->shipping_address)->email }}</td>
                                                    </tr>
                                                    @foreach ($order->orderDetails as $key => $orderDetail)
                                                        @php
                                                            $order_details = $orderDetail->where('order_id',$order->id)->first();
                                                        @endphp
                                                    @endforeach
                                                    
                                                    @if ($order->orderDetails->where('seller_id',$order_details->seller_id)->first()->shipping_type != 'pickup_point')
                                                    <tr>
                                                        <td class="w-50 fw-600">{{ translate('Shipping address')}}:</td>
                                                        <td>{{ json_decode($order->shipping_address)->address }}, {{ json_decode($order->shipping_address)->city }}, {{ json_decode(@$order->shipping_address)->state??'' }}, {{ json_decode($order->shipping_address)->country }}</td>
                                                    </tr>
                                                    @endif
                                                </table>
                                            </div>
                                            <div class="col-md-6">
                                                <table class="table">
                                                    <tr>
                                                        <td class="w-50 fw-600">{{ translate('Order date')}}:</td>
                                                        <td>{{ date('d-m-Y H:i A', $order->date) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="w-50 fw-600">{{ translate('Order status')}}:</td>
                                                        <td>{{ translate(ucfirst(str_replace('_', ' ', $order->delivery_status))) }}</td>
                                                    </tr>
                                                    @if ($order->orderDetails->where('seller_id',$order_details->seller_id)->first()->shipping_type != 'pickup_point')
                                                    <tr>
                                                        <td class="w-50 fw-600">{{ translate('Shipping')}}:</td>
                                                        <td>
                                                            @if($order->shipping_method=='product_wise_shipping')
                                                                {{ translate('product_wise_shipping_cost') }}
                                                            @elseif($order->shipping_method=='flat_rate')
                                                                {{ translate('flat_rate_shipping_cost') }}
                                                            @elseif($order->shipping_method=='seller_wise_shipping')
                                                                {{ translate('merchant_wise_flat_shipping_cost') }}
                                                            @else
                                                                {{ translate('area_wise_shipping_cost') }}
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @endif
                                                    <tr>
                                                        <td class="w-50 fw-600">{{ translate('Payment method')}}:</td>
                                                        <td>{{ ucfirst(str_replace('_', ' ', $order->payment_type)) }}</td>
                                                    </tr>

                                                    @if(get_setting('proxypay') == 1 && !$order->proxy_cart_reference_id->isEmpty())
                                                        <tr>
                                                            <td class="w-50 fw-600">{{ translate('Proxypay Reference')}}:</td>
                                                            <td>{{ $order->proxy_cart_reference_id->first()->reference_id }}</td>
                                                        </tr>
                                                    @endif
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <div>
                                    <h5 class="fw-600 mb-3 fs-17 pb-2">{{ translate('Order Details')}}</h5>
                                    <h5 class="fw-600 mb-3 fs-17 pb-2">{{ translate('Order Code')}}  {{ ' - '. $order->code }}</h5>

                                    <div>
                                        <table class="table table-responsive-md">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th width="30%">{{ translate('Product')}}</th>
                                                    <th>{{ translate('Variation')}}</th>
                                                    <th>{{ translate('Quantity')}}</th>
                                                    <th>{{ translate('Delivery Type')}}</th>
                                                    <th class="text-right">{{ translate('Price')}}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($order->orderDetails as $key => $orderDetail)
                                                    <tr>
                                                        <td>{{ $key+1 }}</td>
                                                        <td>
                                                            @if ($orderDetail->product != null)
                                                                <a href="{{ route('product', $orderDetail->product->slug) }}" target="_blank" class="text-reset">
                                                                    {{ $orderDetail->product->getTranslation('name') }}
                                                                </a>
                                                            @else
                                                                <strong>{{  translate('Product Unavailable') }}</strong>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            {{ $orderDetail->variation }}
                                                        </td>
                                                        <td>
                                                            {{ $orderDetail->quantity }}
                                                        </td>
                                                        <td>
                                                            @if (($orderDetail->shipping_type != null && $orderDetail->shipping_type == 'home_delivery') || $orderDetail->shipping_type == '')
                                                                {{  translate('Home Delivery') }}
                                                            @elseif ($orderDetail->shipping_type == 'pickup_point')
                                                                @if ($orderDetail->pickup_point != null)
                                                                    {{ $orderDetail->pickup_point->getTranslation('name') }} ({{ translate('Pickup Point') }})
                                                                @endif
                                                            @elseif ($orderDetail->shipping_type == 'ship_engine')
                                                                {{  translates('ship_engine') }}
                                                            @endif
                                                        </td>
                                                        <td class="text-right">{{ single_price($orderDetail->price) }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="row">
                                        <div class="col-xl-5 col-md-6 ml-auto mr-0">
                                            <table class="table ">
                                                <tbody>
                                                    <tr>
                                                        <th>{{ translate('Subtotal')}}</th>
                                                        <td class="text-right">
                                                            <span class="fw-600">{{ single_price($order->orderDetails->sum('price')) }}</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>{{ translate('Shipping')}}</th>
                                                        <td class="text-right">
                                                            <span class="font-italic">{{ single_price($order->orderDetails->sum('shipping_cost')) }}</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>{{ translate('Tax')}}</th>
                                                        <td class="text-right">
                                                            <span class="font-italic">{{ single_price($order->orderDetails->sum('tax')) }}</span>
                                                        </td>
                                                    </tr>
                                                    @if(Auth::check() && get_setting('coupon_system') == 1)
                                                    <tr>
                                                        <th>{{ translate('Coupon Discount')}}</th>
                                                        <td class="text-right">
                                                            <span class="font-italic">{{ single_price($order->coupon_discount) }}</span>
                                                        </td>
                                                    </tr>
                                                    @endif
                                                    <tr>
                                                        <th><span class="fw-600">{{ translate('Total')}}</span></th>
                                                        <td class="text-right">
                                                            <strong><span>{{ single_price($order->grand_total) }}</span></strong>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
