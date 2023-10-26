@extends('backend.layouts.app')

@section('content')

    <div class="card">
        <div class="card-header">
            <h1 class="h2 fs-16 mb-0">{{ translate('Order Details') }}</h1>
        </div>

    	<div class="card-body">
            <div class="row gutters-5">
                <div class="col text-center text-md-left">
                    <address>
                        <strong class="text-main">{{ json_decode($order->shipping_address)->name }}</strong><br>
                         
                            @if(isLiveEnv())
                                {{ protectedString(json_decode($order->shipping_address)->email) }}<br>
                                {{ protectedString(json_decode($order->shipping_address)->phone) }}<br>
                            @else
                                {{ json_decode($order->shipping_address)->email }}<br>
                                {{ json_decode($order->shipping_address)->phone }}<br>
                            @endif
                            <div class="col-md-6 px-0">
                            {{ json_decode($order->shipping_address)->address }}, {{ json_decode($order->shipping_address)->city }}, {{ json_decode(@$order->shipping_address)->state??'' }}, {{ json_decode($order->shipping_address)->postal_code }}<br>
                         {{ json_decode($order->shipping_address)->country }}
                            </div>
                         
                    </address>
                    @if ($order->manual_payment && is_array(json_decode($order->manual_payment_data, true)))
                        <br>
                        <strong class="text-main">{{ translate('Payment Information') }}</strong><br>
                        Name: {{ json_decode($order->manual_payment_data)->name }}, Amount: {{ single_price(json_decode($order->manual_payment_data)->amount) }}, TRX ID: {{ json_decode($order->manual_payment_data)->trx_id }}
                        <br>
                        <a href="{{ uploaded_asset(json_decode($order->manual_payment_data)->photo) }}" target="_blank"><img src="{{ uploaded_asset(json_decode($order->manual_payment_data)->photo) }}" alt="" height="100"></a>
                    @endif
                </div>
                <div class="col-md-4 ml-auto">
                  <table class="table table-bordered aiz-table">
                      <tbody>
                        <tr>
                            <td class="text-main text-bold">{{translate('Order #')}}</td>
                            <td class="text-right text-info text-bold">{{ $order->code }}</td>
                        </tr>
                        <tr>
                            <td class="text-main text-bold">{{translate('Order Status')}}</td>
                                @php
                                    $status = $order->orderDetails->first()->delivery_status;
                                    $admin_user_id = \App\User::where('user_type', 'admin')->first()->id;
                                    $count = 0;
                                    $order_detials = $order->orderDetails->where('seller_id', '!=', $admin_user_id);
                                @endphp
                            <td class="text-right">
                                @if($status == 'delivered')
                                    <span class="badge badge-inline badge-success">{{ translate(ucfirst(str_replace('_', ' ', $status))) }}</span>
                                @else
                                    <span class="badge badge-inline badge-info">{{ translate(ucfirst(str_replace('_', ' ', $status))) }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="text-main text-bold">{{translate('Order Date')}}</td>
                            <td class="text-right">{{ date('d-m-Y h:i A', $order->date) }}</td>
                        </tr>
                        <tr>
                            <td class="text-main text-bold">{{translate('Total amount')}}</td>
                            <td class="text-right">
                              {{ single_price($order_detials->sum('price') + $order_detials->sum('tax') + $order_detials->sum('shipping_cost')) }}
                            </td>
                        </tr>
                        <tr>
                            <td class="text-main text-bold">{{translate('Payment method')}}</td>
                            <td class="text-right">{{ ucfirst(str_replace('_', ' ', $order->payment_type)) }}</td>
                        </tr>
                        @if($order->orderDetails->first()->tracking_number != '')
                        <tr>
                            <td class="">{{ translates('tracking_number')}}:</td>
                            <td class="text-right"><a href="{{ $order->orderDetails->first()->label_download }}">{{ $order->orderDetails->first()->tracking_number }}</a></td>
                        </tr>
                        @endif
                      </tbody>
                  </table>
                </div>
            </div>
    		<div class="invoice-bill row">
    			<div class="col-sm-6">

    			</div>
    			<div class="col-sm-6">

    			</div>
    		</div>
    		<hr class="new-section-sm bord-no">
    		<div class="">
                @foreach ($order_detials as $key => $orderDetail)
                <div class="card">
                    <div class="card-header">
                        @php
                            $count += 1;
                        @endphp
                        <h5 class="mb-0 h6">{{ $count }} . {{translate('Product Details')}}</h5>
                    </div>
                    <div class="card-body">
                        <!-- <div class="form-group row">
                            <label class="col-md-3 col-form-label">#</label>
                            <div class="col-md-9">
                                {{ $key+1 }}
                            </div>
                        </div> -->
                        
                        <div class="form-group row">
                            <!-- <div class="col-md-3">
                                <label class="col-from-label">{{translate('Photo')}}</label>
                            </div> -->
                            <div class="col-md-9">
                                @if ($orderDetail->product != null && $orderDetail->product->auction_product == 0)
                                    <a href="{{ route('product', $orderDetail->product->slug) }}" target="_blank"><img height="150" width="150" src="{{ uploaded_asset($orderDetail->product->thumbnail_img) }}"></a>
                                @elseif ($orderDetail->product != null && $orderDetail->product->auction_product == 1)
                                    <a href="{{ route('auction-product', $orderDetail->product->slug) }}" target="_blank"><img height="150" width="150" src="{{ uploaded_asset($orderDetail->product->thumbnail_img) }}"></a>
                                @else
                                    <strong>{{ translate('N/A') }}</strong>
                                @endif
                            </div>
                        </div>
                        {{-- <div class="text-right">
                            @if(get_setting('ship_engine') && !$order->orderDetails->first()->tracking_number && $order->orderDetails->first()->shipping_type == 'ship_engine')
                                <button class="btn btn-primary ship_engine_label" data-id="{{ $order->orderDetails->first()->id }}">{{ translate('Shipping Label')}}</button> 
                            @endif
                        </div> --}}
                        <div class="form-group d-flex">
                            <div class="col-md-3 px-0">
                                <label class="col-from-label">{{translate('Merchant Name')}}</label>
                            </div>
                            @php
                                $sellers = \DB::table('users')->where('id',$orderDetail->seller_id)->get();
                            @endphp
                            <div class="col-md-9 px-0">
                                @foreach($sellers as $seller)
                                    {{$seller->name}}
                                @endforeach
                            </div>
                        </div>
                        <div class="form-group d-flex">
                            <div class="col-md-3 px-0">
                                <label class="col-from-label">{{translate('Product Name')}}</label>
                            </div>
                            <div class="col-md-9 px-0">
                                @if ($orderDetail->product != null && $orderDetail->product->auction_product == 0)
                                    <strong><a href="{{ route('product', $orderDetail->product->slug) }}" target="_blank" class="text-muted">{{ $orderDetail->product->getTranslation('name') }}</a></strong>
                                    <small>{{ $orderDetail->variation }}</small>
                                @elseif ($orderDetail->product != null && $orderDetail->product->auction_product == 1)
                                    <strong><a href="{{ route('auction-product', $orderDetail->product->slug) }}" target="_blank" class="text-muted">{{ $orderDetail->product->getTranslation('name') }}</a></strong>
                                @else
                                    <strong>{{ translate('Product Unavailable') }}</strong>
                                @endif
                            </div>
                        </div>
                        <div class="form-group d-flex">
                            <div class="col-md-3 px-0">
                                <label class="col-from-label">{{translate('Delivery Type')}}</label>
                            </div>
                            <div class="col-md-9 px-0">
                                @if (($orderDetail->shipping_type != null && $orderDetail->shipping_type == 'home_delivery') || $orderDetail->shipping_type == '')
                                    {{ translate('Home Delivery') }}
                                @elseif ($orderDetail->shipping_type == 'pickup_point')
                                    @if ($orderDetail->pickup_point != null)
                                    {{ $orderDetail->pickup_point->getTranslation('name') }} ({{ translate('Pickup Point') }})
                                    @else
                                    {{ translate('Pickup Point') }}
                                    @endif
                                @elseif ($orderDetail->shipping_type == 'ship_engine')
                                        {{  translates('ship_engine') }}
                                @endif
                            </div>
                        </div>
                        <div class="form-group d-flex">
                            <div class="col-md-3 px-0">
                                <label class="col-from-label">{{translate('Qty')}}</label>
                            </div>
                            <div class="col-md-9 px-0">
                                {{ $orderDetail->quantity }}
                            </div>
                        </div>
                        <div class="form-group d-flex">
                            <div class="col-md-3 px-0">
                                <label class="col-from-label">{{translate('Price')}}</label>
                            </div>
                            <div class="col-md-9 px-0">
                                {{ single_price($orderDetail->price/$orderDetail->quantity) }}
                            </div>
                        </div>
                        <div class="form-group d-flex">
                            <div class="col-md-3 px-0">
                                <label class="col-from-label">{{translate('Total')}}</label>
                            </div>
                            <div class="col-md-9 px-0">
                                {{ single_price($orderDetail->price) }}
                            </div>
                        </div>
                       
                    </div>
                </div>
                @endforeach
    		</div>
            <div class="grand_total">
                    <div class="d-flex justify-content-center mt-3">
                        <div class=" col-md-3">
                        <strong class="text-muted">{{translate('Sub Total')}} :</strong>
                        </div>
                        <div class="col-md-3">
                        {{ single_price($order_detials->sum('price')) }}
                        </div>
                    </div>

                    <div class="d-flex justify-content-center mt-3">
                        <div class=" col-md-3">
                        <strong class="text-muted">{{translate('Tax')}} :</strong>
                        </div>
                        <div class="col-md-3">
                        {{ single_price($order_detials->sum('tax')) }}
                        </div>
                    </div>

                    <div class="d-flex justify-content-center mt-3">
                        <div class=" col-md-3">
                        <strong class="text-muted"> {{translate('Shipping')}} :</strong>
                        </div>
                        <div class="col-md-3">
                        {{ single_price($order_detials->sum('shipping_cost')) }}
                        </div>
                    </div>

                    <div class="d-flex justify-content-center mt-3">
                        <div class=" col-md-3">
                        <strong class="text-muted">{{translate('Coupon')}} :</strong>
                        </div>
                        <div class="col-md-3">
                        {{ single_price($order->coupon_discount) }}
                        </div>
                    </div>

                    <div class="d-flex justify-content-center mt-3">
                        <div class=" col-md-3">
                        <strong class="text-muted">{{translate('TOTAL')}} :</strong>
                        </div>
                        <div class="col-md-3 text-muted h5">
                        {{ single_price($order_detials->sum('price') + $order_detials->sum('tax') + $order_detials->sum('shipping_cost')) }}
                        </div>
                    </div>
                    <div class="text-right no-print">
                    <a href="{{ route('invoice.download', $order->id) }}" type="button" class="btn btn-icon btn-light"><i class="las la-download"></i></a>
                </div>
            </div>
    		<!-- <div class="clearfix float-right">
    			<table class="table">
          			<tbody>
            			<tr>
            				<td><strong class="text-muted">{{translate('Sub Total')}} :</strong></td>
            				<td>
            					{{ single_price($order->orderDetails->sum('price')) }}
            				</td>
            			</tr>
            			<tr>
            				<td><strong class="text-muted">{{translate('Tax')}} :</strong></td>
            				<td>{{ single_price($order->orderDetails->sum('tax')) }}</td>
            			</tr>
                        <tr>
            				<td><strong class="text-muted"> {{translate('Shipping')}} :</strong></td>
            				<td>{{ single_price($order->orderDetails->sum('shipping_cost')) }}</td>
            			</tr>
                        <tr>
            				<td>
            					<strong class="text-muted">{{translate('Coupon')}} :</strong>
            				</td>
            				<td>
            					{{ single_price($order->coupon_discount) }}
            				</td>
            			</tr>
            			<tr>
            				<td><strong class="text-muted">{{translate('TOTAL')}} :</strong></td>
            				<td class="text-muted h5">
            					{{ single_price($order->grand_total) }}
            				</td>
            			</tr>
          			</tbody>
    			</table>
                <div class="text-right no-print">
                    <a href="{{ route('invoice.download', $order->id) }}" type="button" class="btn btn-icon btn-light"><i class="las la-download"></i></a>
                </div>
    		</div> -->
    	</div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        $('#update_delivery_status').on('change', function(){
            var order_id = {{ $order->id }};
            var status = $('#update_delivery_status').val();
            $.post('{{ route('orders.update_delivery_status') }}', {_token:'{{ @csrf_token() }}',order_id:order_id,status:status}, function(data){
                AIZ.plugins.notify('success', '{{ translate('Delivery status has been updated') }}');
            });
        });

        $('#update_payment_status').on('change', function(){
            var order_id = {{ $order->id }};
            var status = $('#update_payment_status').val();
            $.post('{{ route('orders.update_payment_status') }}', {_token:'{{ @csrf_token() }}',order_id:order_id,status:status}, function(data){
                AIZ.plugins.notify('success', '{{ translate('Payment status has been updated') }}');
            });
        });
    </script>
@endsection
