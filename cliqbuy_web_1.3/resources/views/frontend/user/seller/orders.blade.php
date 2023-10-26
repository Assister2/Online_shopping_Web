@extends('frontend.layouts.user_panel')

@section('panel_content')
    <div class="cls_bread">
        <ul>
            <li><a href="{{ route('mainmenu') }}">{{ translate('main') }}</a></li>
            <li><a>{{ translate('Orders')}}</a></li>
        </ul>
    </div>
    <div class="card" ng-controller="merchant_owe_amount" ng-init="owe_amount_order ={{json_encode($owe_amount_order)}}">
        <form id="sort_orders" action="" method="GET">
          <div class="card-header row gutters-5">
            <div class="col text-center text-md-left">
              <h5 class="mb-md-0 h6">{{ translate('Orders') }}</h5>
            </div>
              <div class="col-md-3 ml-auto">
                  <select class="form-control aiz-selectpicker" data-placeholder="{{ translate('Filter by Payment Status')}}" name="payment_status" onchange="sort_orders()">
                      <option value="">{{ translate('Filter by Payment Status')}}</option>
                      <option value="paid" @isset($payment_status) @if($payment_status == 'paid') selected @endif @endisset>{{ translate('Paid')}}</option>
                      <option value="unpaid" @isset($payment_status) @if($payment_status == 'unpaid') selected @endif @endisset>{{ translate('unpaid')}}</option>
                  </select>
              </div>

              <div class="col-md-3 ml-auto">
                <select class="form-control aiz-selectpicker" data-placeholder="{{ translate('Filter by Payment Status')}}" name="delivery_status" onchange="sort_orders()">
                    <option value="">{{ translate('Filter by Delivery Status')}}</option>
                    <option value="pending" @isset($delivery_status) @if($delivery_status == 'pending') selected @endif @endisset>{{ translate('Pending')}}</option>
                    <option value="confirmed" @isset($delivery_status) @if($delivery_status == 'confirmed') selected @endif @endisset>{{ translate('Confirmed')}}</option>
                    <option value="on_delivery" @isset($delivery_status) @if($delivery_status == 'on_delivery') selected @endif @endisset>{{ translate('On delivery')}}</option>
                    <option value="delivered" @isset($delivery_status) @if($delivery_status == 'delivered') selected @endif @endisset>{{ translate('Delivered')}}</option>
                </select>
              </div>
              <div class="col-md-3">
                <div class="from-group mb-0">
                    <input type="text" class="form-control" id="search" name="search" @isset($sort_search) value="{{ $sort_search }}" @endisset placeholder="{{ translate('Type Order code & hit Enter') }}">
                </div>
              </div>
          </div>
        </form>

        @if (count($orders) > 0)
            <div class="card-body p-3">
                <div class="row px-3 py-2 justify-content-center">
                    <div class="col-md-6 px-0">
                        @if (count($owe_amount_order) > 0)
                        <div class="box-shadow" id="pay_owe_amount">
                            <div class="row align-items-start">
                                <div class="col">
                                    <p class="mb-0" id="order_code">@{{ order_code }}</p>
                                </div>
                                <div class="col">
                                    <div class="d-flex align-items-center flex-wrap flex-md-nowrap">
                                        <label class="text-nowrap mr-2">Owe Amount:</label>
                                        <input type="text" id="owe_amount" value="@{{ getTotal() }}" name="owe_amount" class="form-control" readonly>
                                    </div>
                                </div>

                                <div class="col-sm-2 mt-2 mt-md-0" ng-if="order_id.length">
                                    <a href ="{{ url('/') }}/owe_amount_payment/@{{order_id}}" class="btn btn-primary w-100">Pay</a>
                                </div>
                                <div class="col-sm-2 mt-2 mt-md-0" ng-if="!order_id.length">
                                    <button disabled='disabled' class="btn btn-primary w-100">Pay</button>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                <table class="table aiz-table mb-0">
                    <thead>
                        <tr>
                            <th></th>
                            <th>#</th>
                            <th>{{ translate('Order Code')}}</th>
                            <th data-breakpoints="lg">{{ translate('Num of Products')}}</th>
                            <th data-breakpoints="lg">{{ translate('Customer')}}</th>
                            <th data-breakpoints="md">{{ translate('Amount')}}</th>
                            <th data-breakpoints="lg">{{ translate('Delivery Status')}}</th>
                            <th>{{ translate('Payment Status')}}</th>
                            <th class="text-right">{{ translate('Options')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $key => $order_id)
                            @php
                                $order = \App\Order::find($order_id->id);
                                $order_detail = \DB::table('order_details')->where('seller_id',Auth::user()->id)->where('order_id',$order->id)->first();
                                $owe_amount = \DB::table('owe_amounts')->where('seller_id', Auth::user()->id)->where('order_id',$order->id)->first();
                            @endphp
                            @if($order != null || $order_detail != null)
                                <tr>
                                    @if($order->payment_type == 'cash_on_delivery' && $order->payment_status == 'paid' && isset($owe_amount) ? $owe_amount->status == 'Pending' : '')
                                    <td><input type="checkbox" name="owe_amount_status" id="merchan-owe-amount-{{$order->id}}" class="merchan-owe-amount" value={{$order->id}}></td>
                                    @else
                                    <td></td>
                                    @endif
                                    <td>
                                        {{ $key+1 }}
                                    </td>
                                    <td>
                                        <a href="#{{ $order->code }}" onclick="show_order_details({{ $order->id }})">{{ $order->code }}</a>
                                    </td>
                                    <td>
                                        {{ count($order->orderDetails->where('seller_id', Auth::user()->id)) }}
                                    </td>
                                    <td>
                                        @if ($order->user_id != null)
                                            {{ ($order->user->deleted_at == '') ? $order->user->name : 'Deleted User' }}
                                        @else
                                            Guest ({{ $order->guest_id }})
                                        @endif
                                    </td>
                                    <td>
                                        {{-- single_price($order->grand_total) --}}
                                        {{ single_price($order->orderDetails->where('seller_id', Auth::user()->id)->sum('price')) }}
                                    </td>
                                    <td>
                                        @php
                                            $status = $order->delivery_status;
                                        @endphp
                                        {{ translate(ucfirst(str_replace('_', ' ', $status))) }}
                                    </td>
                                    <td>
                                        @if ($order->payment_status == 'paid')
                                            <span class="badge badge-inline badge-success">{{ translate('Paid')}}</span>
                                        @else
                                            <span class="badge badge-inline badge-danger">{{ translate('Unpaid')}}</span>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        <a href="javascript:void(0)" class="btn btn-soft-info btn-icon btn-circle btn-sm fs-16" onclick="show_order_details({{ $order->id }})" title="{{ translate('Order Details') }}">
                                            <i class="las la-eye"></i>
                                        </a>
                                        <a href="{{ route('invoice.download', ['order_id' => $order->id, 'order_detail_id' => $order_detail->seller_id]) }}" class="btn btn-soft-warning btn-icon btn-circle btn-sm fs-16" title="{{ translate('Download Invoice') }}">
                                            <i class="las la-download"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
        
        <div class="aiz-pagination">
            {{ $orders->appends(request()->input())->links() }}
        </div>
@endsection

@section('modal')
    <div class="modal fade" id="order_details" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div id="order-details-modal-body">

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="order_details" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div id="order-details-modal-body">

                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="payment_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div id="payment_modal_body">

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="ship_engine_label" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title strong-600 heading-5">{{ translates('Shipping')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body ship_engine_label_body gry-bg px-3 pt-0">
                <div class="mt-4">
                    <div class="text-center">
                        <h5 class="fw-600">{{ translates('manual_shipment') }}</h5>
                    </div>
                    <div class="">
                        <form action="{{ route('manual_tracking_number') }}" method="POST" id="manual_tracking_number">
                            <div class="form-group">
                                <div class="d-flex">
                                    <div id="carrier_name_container">
                                        <select name="carrier_name" id="carrier_name_manual" class="form-control w-auto" data-error-placement="container" data-error-container="#carrier_name_container" required>
                                            @foreach($shipengines as $engines)
                                                <option value=""> Select </option>
                                                <option value="{{ $engines }}"> {{ ucwords(str_replace('_', ' ', $engines)) }} </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div id="tracking_number_container" class="w-100">
                                        <input type="text" placeholder="{{translates('tracking_number')}}" value="" name="tracking_number" class="form-control ml-2" required data-error-placement="container" data-error-container="#tracking_number_container">
                                    </div>
                                </div>
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            </div>

                            <div class="mb-3 text-center">
                                <button type="submit" class="btn btn-primary">{{translate('Submit')}}</button>
                            </div>
                        </form>
                        
                        <hr>

                        <form action="{{ route('shipping_tracking_number') }}" method="POST" id="shipping_tracking_number">
                        <div class="address_section">
                            <div class="row form-group">
                                <div class="col-md-3">
                                    <label>{{ translates('shipping_method') }}</label>
                                </div>
                                <div class="col-md-6">
                                    <select name="carrier_name" id="" class="form-control" required>
                                        <option value=""> Select </option>
                                        @foreach($shipengines as $engines)
                                            <option value="{{ $engines }}"> {{ ucwords(str_replace('_', ' ', $engines)) }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-3">
                                    <label>{{ translates('shipping_from') }}:</label>
                                </div>
                                <div class="col-md-6">
                                    <p>
                                        <strong> @{{ merchant_address.address }} </strong>
                                        @{{ merchant_address.city }},
                                        @{{ merchant_address.state }},
                                        @{{ merchant_address.country }} -
                                        @{{ merchant_address.postal_code }}
                                    </p>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-3">
                                    <label>{{ translates('shipping_to') }}:</label>
                                </div>
                                <div class="col-md-6">
                                    <p>
                                        <strong> @{{ user_address.address }} </strong>
                                        @{{ user_address.city }},
                                        @{{ user_address.state }},
                                        @{{ user_address.country }} -
                                        @{{ user_address.postal_code }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="my-4 py-4">
                            <div class="">
                                <p> {{ trans('messages.ship_engine.create_label_content', ['site_name' => get_setting('site_name')]) }}</p>
                            </div>
                        </div>

                        <div class="my-4 multiple_boxes_first">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="">
                                        <div class="form-group row">
                                            <div class="col-md-6">
                                                <label>{{ translates('package_unit') }}:</label>
                                            </div>
                                            <div class="col-md-6">
                                                <select class="form-control package_unit" name="package_unit[]" id="package_unit_1" required>
                                                    <option value="pound">{{ translates('pound') }}</option>
                                                    <option value="ounce">{{ translates('ounce') }}</option>
                                                    <option value="gram">{{ translates('gram') }}</option>
                                                    <option value="kilogram">{{ translates('kilogram') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-6">
                                                <label>{{ translates('weight') }}: </label>
                                            </div>
                                            <div class="col-md-6">
                                                <input type="number" class="form-control" name="package_weight[]" id="package_weight_1" required min="1">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-6">
                                                <label>{{ translates('length') }}: </label>
                                            </div>
                                            <div class="col-md-6">
                                                <input type="number" class="form-control" name="package_length[]" id="package_length_1" required min="1">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="">
                                        <div class="form-group row">
                                            <div class="col-md-6">
                                                <label> {{ translates('dimensions_unit') }}:</label>
                                            </div>
                                            <div class="col-md-6">
                                                <select name="dimension_unit[]" id="dimension_unit_1" class="form-control dimension_unit" required>
                                                    <option value="inch">Inch</option>
                                                    <option value="centimeter">Centimeter</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-6">
                                                <label>{{ translates('width') }}: </label>
                                            </div>
                                            <div class="col-md-6">
                                                <input type="number" class="form-control" name="dimension_width[]" id="dimension_width_1" required min="1">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-6">
                                                <label> {{ translates('height') }}:</label>
                                            </div>
                                            <div class="col-md-6">
                                                <input type="number" class="form-control" name="dimension_height[]" id="dimension_height_1" required min="1">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-right">
                            <input type="hidden" id="increment" value="1">
                            {{-- <button type="button" class="btn btn-primary add_shipping_boxes">{{ translates('add') }}</button> --}}
                        </div>
                        <div class="multiple_boxes">

                        </div>
                        <div class="mt-4 text-center">
                            <p class="shipping_tracking_number_error d-none text-danger"></p>
                        </div>
                        <div class="my-4 text-center">
                            <button type="submit" class="btn btn-primary multiple_boxes_submit">{{translate('Submit')}}</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="create_label_model" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title strong-600 heading-5">{{translates('ship_engine_label')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="label_modal_body" class="modal-body">
                    <div class="form-group text-center">
                        <div class="d-flex justify-content-center">
                            <select class="form-control select w-auto" data-live-search="true"  name="shipping_type" id="shipping_type" required>
                                
                            </select>
                        </div>
                    </div>

                    <div class="mt-4 text-center">
                        <p class="create_label_model_error d-none text-danger"></p>
                    </div>

                    <div class="mt-4 text-center">
                        <button type="button" class="btn btn-primary create_ship_engine_label">{{translate('Submit')}}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        function show_order_details(order_id)
        {
            $('#order-details-modal-body').html(null);

            if(!$('#modal-size').hasClass('modal-lg')){
                $('#modal-size').addClass('modal-lg');
            }

            $.post('{{ route('orders.details') }}', { _token : AIZ.data.csrf, order_id : order_id}, function(data){
                $('#order-details-modal-body').html(data);
                $('#order_details').modal();
                $('.c-preloader').hide();
            });
        }
        function sort_orders(el){
            $('#sort_orders').submit();
        }
       

        $(document).ready(function() {
            var header = $('header').outerHeight();
            $('.sticky_sort_orders').css('top', + header +'px');
        })
    </script>
@endsection
