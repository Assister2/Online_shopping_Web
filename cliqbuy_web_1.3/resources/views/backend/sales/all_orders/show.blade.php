@extends('backend.layouts.app')
@section('content')
<div class="card">
   <div class="card-header">
      <h1 class="h2 fs-16 mb-0">{{ translate('Order Details') }}</h1>
   </div>
   <div class="card-body">
      <div class="row gutters-5">
         <div class="col text-center text-md-left">
         </div>
         @php
         $delivery_status = $order->delivery_status;
         $payment_status = $order->payment_status;
         $payment_type = $order->payment_type;
         @endphp
         <!--Assign Delivery Boy-->
         @if (\App\Addon::where('unique_identifier', 'delivery_boy')->first() != null &&
         \App\Addon::where('unique_identifier', 'delivery_boy')->first()->activated)
         <div class="col-md-3 ml-auto">
            <label for="assign_deliver_boy">{{translate('Assign Deliver Boy')}}</label>
            @if($delivery_status == 'pending' || $delivery_status == 'picked_up')
            <select class="form-control aiz-selectpicker" data-live-search="true" data-minimum-results-for-search="Infinity" id="assign_deliver_boy">
               <option value="">{{translate('Select Delivery Boy')}}</option>
               @foreach($delivery_boys as $delivery_boy)
               <option value="{{ $delivery_boy->id }}" @if($order->assign_delivery_boy == $delivery_boy->id) selected @endif>
               {{ $delivery_boy->name }}
               </option>
               @endforeach
            </select>
            @else
            <input type="text" class="form-control" value="{{ optional($order->delivery_boy)->name }}" disabled>
            @endif
         </div>
         @endif
      </div>
      <div class="row gutters-5">
         <div class="col text-center text-md-left">
            <address>
               @if($order->shipping_address != "[]")
               <strong class="text-main">{{ ($order->user->deleted_at == '') ? json_decode(@$order->shipping_address)->name : 'Deleted User' }}</strong><br>
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
               @endif
            </address>
            @if ($order->manual_payment && is_array(json_decode($order->manual_payment_data, true)))
            <br>
            <strong class="text-main">{{ translate('Payment Information') }}</strong><br>
            {{ translate('Name') }}: {{ json_decode($order->manual_payment_data)->name }}, {{ translate('Amount') }}: {{ single_price(json_decode($order->manual_payment_data)->amount) }}, {{ translate('TRX ID') }}: {{ json_decode($order->manual_payment_data)->trx_id }}
            <br>
            <a href="{{ uploaded_asset(json_decode($order->manual_payment_data)->photo) }}" target="_blank"><img src="{{ uploaded_asset(json_decode($order->manual_payment_data)->photo) }}" alt="" height="100"></a>
            @endif
         </div>
         <div class="col-md-4 ml-auto">
            <table>
               <tbody>
                  <tr>
                     <td class="text-main text-bold">{{translate('Order #')}}</td>
                     <td class="text-right text-info text-bold">    {{ $order->code }}</td>
                  </tr>
                  <tr>
                     <td class="text-main text-bold">{{translate('Order Status')}}</td>
                     <td class="text-right">
                        @if($delivery_status == 'delivered')
                        <span class="badge badge-inline badge-success">{{ translate(ucfirst(str_replace('_', ' ', $delivery_status))) }}</span>
                        @else
                        <span class="badge badge-inline badge-info">{{ translate(ucfirst(str_replace('_', ' ', $delivery_status))) }}</span>
                        @endif
                     </td>
                  </tr>
                  <tr>
                     <td class="text-main text-bold">{{translate('Order Date')}}    </td>
                     <td class="text-right">{{ date('d-m-Y h:i A', $order->date) }}</td>
                  </tr>
                  <tr>
                     <td class="text-main text-bold">
                        {{translate('Total amount')}}
                     </td>
                     <td class="text-right">
                        {{ single_price($order->grand_total) }}
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
      <!-- <hr class="new-section-sm bord-no"> -->
      <div class="row">
         <div class="col-md-12 ml-auto">
            @foreach ($order->orderDetails as $key => $orderDetail)
            <div class="card">
               <div class="card-header">
                  <h5 class="mb-0 h6">{{ $key+1 }} . {{translate('Product Details')}}</h5>
               </div>
               <div class="card-body">
                  <!-- <div class="form-group row">
                     <label class="col-md-3 col-form-label">#</label>
                     <div class="col-md-9">
                         {{ $key+1 }}
                     </div>
                     </div> -->
                  <div class="row">
                     <div class="col-md-6">
                        <div class="form-group row">
                           <!-- <div class="col-md-3">
                              <label class="col-from-label">{{translate('Photo')}}</label>
                              </div> -->
                           <div class="col-md-12">
                              @if ($orderDetail->product != null && $orderDetail->product->auction_product == 0)
                              <a href="{{ route('product', $orderDetail->product->slug) }}" target="_blank"><img height="150" width="150" src="{{ uploaded_asset($orderDetail->product->thumbnail_img) }}"></a>
                              @elseif ($orderDetail->product != null && $orderDetail->product->auction_product == 1)
                              <a href="{{ route('auction-product', $orderDetail->product->slug) }}" target="_blank"><img height="50" src="{{ uploaded_asset($orderDetail->product->thumbnail_img) }}"></a>
                              @else
                              <strong>{{ translate('N/A') }}</strong>
                              @endif
                           </div>
                        </div>
                        <div class="form-group d-flex">
                           <div class="col-md-6 px-0">
                              <label class="col-from-label">{{translate('Merchant Name')}}</label>
                           </div>
                           @php
                           $sellers = \DB::table('users')->where('id',$orderDetail->seller_id)->get();
                           @endphp
                           <div class="col-md-6 px-0">
                              @foreach($sellers as $seller)
                              {{$seller->name}}
                              @endforeach
                           </div>
                        </div>
                        <div class="form-group d-flex">
                           <div class="col-md-6 px-0">
                              <label class="col-from-label">{{translate('Product Name')}}</label>
                           </div>
                           <div class="col-md-6 px-0">
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
                           <div class="col-md-6 px-0">
                              <label class="col-from-label">{{translate('Delivery Type')}}</label>
                           </div>
                           <div class="col-md-6 px-0">
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
                           <div class="col-md-6 px-0">
                              <label class="col-from-label">{{translate('Qty')}}</label>
                           </div>
                           <div class="col-md-6 px-0">
                              {{ $orderDetail->quantity }}
                           </div>
                        </div>
                        <div class="form-group d-flex">
                           <div class="col-md-6 px-0">
                              <label class="col-from-label">{{translate('Price')}}</label>
                           </div>
                           <div class="col-md-6  px-0">
                              {{ single_price($orderDetail->price/$orderDetail->quantity) }}
                           </div>
                        </div>
                        <div class="form-group d-flex">
                           <div class="col-md-6 px-0">
                              <label class="col-from-label">{{translate('Total')}}</label>
                           </div>
                           <div class="col-md-6 px-0">
                              {{ single_price($orderDetail->price) }}
                           </div>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="row">
                           <div class="col-md-6">
                              <div class="form-group">
                                 <div class="col-md-12 text-center">
                                    <label class="col-from-label">{{translate('Payment Status')}}</label>
                                    @if($orderDetail->delivery_status != 'delivered' && $orderDetail->delivery_status != 'cancelled')
                                    <select class="form-control aiz-selectpicker" id="update_detail_payment_status-{{ $orderDetail->id }}" data-id="{{ $orderDetail->id }}"   data-minimum-results-for-search="Infinity" >
                                    @if($payment_type == 'cash_on_delivery')
                                    <option value="unpaid" @if ($orderDetail->payment_status == 'unpaid') selected @endif>{{translate('Unpaid')}}</option>
                                    @endif
                                    <option value="paid" @if ($orderDetail->payment_status == 'paid') selected @endif>{{translate('Paid')}}</option>
                                    </select>
                                    @else
                                    <input type="text" class="form-control" value="{{ $orderDetail->payment_status }}" disabled>
                                    @endif
                                 </div>
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-group">
                                 <div class="col-md-12 text-center">
                                    <label class="col-from-label">{{translate('Delivery Status')}}</label>
                                    @if($orderDetail->delivery_status != 'delivered' && $orderDetail->delivery_status != 'cancelled')
                                    <select class="form-control aiz-selectpicker" id="update_delivery_status-{{ $orderDetail->id }}" data-id="{{ $orderDetail->id }}"  data-minimum-results-for-search="Infinity">
                                       <option value="pending" @if ($orderDetail->delivery_status == 'pending') selected @endif>{{translate('Pending')}}</option>
                                       <option value="confirmed" @if ($orderDetail->delivery_status == 'confirmed') selected @endif>{{translate('Confirmed')}}</option>
                                       <!-- <option value="picked_up" @if ($orderDetail->delivery_status == 'picked_up') selected @endif>{{translate('Picked Up')}}</option> -->
                                       <option value="on_the_way" @if ($orderDetail->delivery_status == 'on_the_way') selected @endif>{{translate('On The Way')}}</option>
                                       <option value="delivered" @if ($orderDetail->delivery_status == 'delivered') selected @endif>{{translate('Delivered')}}</option>
                                       <!-- <option value="cancelled" @if ($orderDetail->delivery_status == 'cancelled') selected @endif>{{translate('Cancel')}}</option> -->
                                    </select>
                                    @else
                                    <input type="text" class="form-control" value="{{ $orderDetail->delivery_status }}" disabled>
                                    @endif
                                 </div>
                              </div>
                           </div>
                           @php
                           $admin_user_id = \App\User::where('user_type', 'admin')->first()->id;
                           @endphp
                           @if(get_setting('ship_engine') && !$order->orderDetails->first()->tracking_number && $order->orderDetails->first()->shipping_type == 'ship_engine' && ($order->orderDetails->first()->product->user_id == $admin_user_id))
                            <div class="col-md-12 text-right">
                                <div class="">
                                    <button class="btn btn-primary ship_engine_label" data-id="{{ $order->orderDetails->first()->id }}">{{ translate('Shipping Label')}}</button> 
                                </div>
                            </div>
                            @endif
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            @endforeach
         </div>
      </div>
      <div class="grand_total">
         <div class="d-flex justify-content-center">
            <div class=" col-md-3">
               <strong class="text-muted">{{translate('Sub Total')}} :</strong>
            </div>
            <div class="col-md-3">
               {{ single_price($order->orderDetails->sum('price')) }}
            </div>
         </div>
         <div class="d-flex justify-content-center mt-3">
            <div class=" col-md-3">
               <strong class="text-muted">{{translate('Tax')}} :</strong>
            </div>
            <div class="col-md-3 ">
               {{ single_price($order->orderDetails->sum('tax')) }}
            </div>
         </div>
         <div class="d-flex justify-content-center mt-3">
            <div class=" col-md-3 ">
               <strong class="text-muted">{{translate('Shipping')}} :</strong>
            </div>
            <div class="col-md-3 ">
               {{ single_price($order->orderDetails->sum('shipping_cost')) }}
            </div>
         </div>
         <div class="d-flex justify-content-center mt-3">
            <div class=" col-md-3 ">
               <strong class="text-muted">{{translate('Coupon')}} :</strong>
            </div>
            <div class="col-md-3 ">
               {{ single_price($order->coupon_discount) }}
            </div>
         </div>
         <div class="d-flex justify-content-center mt-3">
            <div class=" col-md-3 ">
               <strong class="text-muted">{{translate('TOTAL')}} :</strong>
            </div>
            <div class="col-md-3 text-muted h5">
               {{ single_price($order->grand_total) }} 
            </div>
         </div>
      </div>
      <div class="text-right no-print">
         <a href="{{ route('invoice.download', $order->id) }}" type="button" class="btn btn-icon btn-light"><i class="las la-download"></i></a>
      </div>
      <!-- <div class="offset-md-3 col-md-3">
         <strong class="text-muted">{{translate('Tax')}} :</strong>
         </div>
         <div class="col-md-3">
         {{ single_price($order->orderDetails->sum('tax')) }}
         </div>
         
         <div class="offset-md-3 col-md-3">
         <strong class="text-muted">{{translate('Shipping')}} :</strong>
         </div>
         <div class="col-md-3">
         {{ single_price($order->orderDetails->sum('shipping_cost')) }}
         </div>
         
         <div class="offset-md-3 col-md-3">
         <strong class="text-muted">{{translate('Coupon')}} :</strong>
         </div>
         <div class="col-md-3">
         {{ single_price($order->coupon_discount) }}
         </div>
         
         <div class="offset-md-3 col-md-3">
         <strong class="text-muted">{{translate('TOTAL')}} :</strong>
         </div>
         <div class="col-md-3">
         {{ single_price($order->coupon_discount) }}
         </div> -->
   </div>
   <!-- <div class="clearfix float-right">
      <table class="table">
          <tbody>
              <tr>
                  <td>
                      
                  </td>
                  <td>
                      
                  </td>
              </tr>
              <tr>
                  <td>
                      
                  </td>
                  <td>
                      
                  </td>
              </tr>
              <tr>
                  <td>
                      
                  </td>
                  <td>
                      
                  </td>
              </tr>
              <tr>
                  <td>
                      
                  </td>
                  <td>
                      
                  </td>
              </tr>
              <tr>
                  <td>
                      
                  </td>
                  <td class="text-muted h5">
                      
                  </td>
              </tr>
          </tbody>
      </table>
      
      </div> -->
</div>
</div>
@endsection
@section('script')
<script type="text/javascript">
   $('#assign_deliver_boy').on('change', function(){
       var order_id = {{ $order->id }};
       var delivery_boy = $('#assign_deliver_boy').val();
       $.post('{{ route('orders.delivery-boy-assign') }}', {
           _token          :'{{ @csrf_token() }}',
           order_id        :order_id,
           delivery_boy    :delivery_boy
       }, function(data){
           AIZ.plugins.notify('success', '{{ translate('Delivery boy has been assigned') }}');
       });
   });
   
   $(document).on('change', '[id^="update_delivery_status-"]', function() {
       var order_id = {{ $order->id }};
       var payment_status = $('[id^="update_detail_payment_status-"]').val();
       var order_detail_id = $(this).attr("data-id");
       var status = $(this).val();
       if(status=="delivered" && payment_status!='paid'){
           AIZ.plugins.notify('danger', '{{ translate('Still Payment Status is Unpaid') }}');
           location.reload().setTimeOut(500);
       }
           $.post('{{ route('orders.update_payment_status') }}', {
               _token:'{{ @csrf_token() }}',
               order_id:order_id,
               order_detail_id:order_detail_id,
               delivery_status:status
           }, function(data){
               AIZ.plugins.notify('success', '{{ translate('Delivery status has been updated') }}');
               location.reload().setTimeOut(500);
           });
       
   });
   $(document).on('change', '[id^="update_detail_payment_status-"]', function() {
       var order_id = {{ $order->id }};
       var order_detail_id = $(this).attr("data-id");
       var status = $(this).val();
       $.post('{{ route('orders.update_payment_status') }}', {_token:'{{ @csrf_token() }}',order_id:order_id,order_detail_id:order_detail_id,status:status}, function(data){
           // location.reload();
           AIZ.plugins.notify('success', '{{ translate('Payment status has been updated') }}');
           location.reload().setTimeOut(500);
       });
   });
</script>
@endsection