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
                    <div class="col active">
                        <div class="text-center text-primary cart_timeline">
                            <i class="la-3x mb-2 las la-truck"></i>
                            <h3 class="fs-14 fw-600 d-none d-lg-block">{{ translate('3 Delivery info')}}</h3>
                        </div>
                    </div>
                    <div class="col">
                        <div class="text-center cart_timeline">
                            <i class="la-3x mb-2 opacity-50 las la-credit-card"></i>
                            <h3 class="fs-14 fw-600 d-none d-lg-block opacity-50">{{ translate('4 Payment')}}</h3>
                        </div>
                    </div>
                    <div class="col">
                        <div class="text-center cart_timeline">
                            <i class="la-3x mb-2 opacity-50 las la-check-circle"></i>
                            <h3 class="fs-14 fw-600 d-none d-lg-block opacity-50">{{ translate('5 Confirmation')}}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-4 gry-bg">
    <div class="container-fluid">
        <div class="row cols-xs-space cols-sm-space cols-md-space">
            <div class="col-12 mx-auto text-left">
                @php
                    $admin_products = array();
                    $seller_products = array();
                    foreach ($carts as $key => $cartItem){
                        if(\App\Product::find($cartItem['product_id'])->added_by == 'admin'){
                            array_push($admin_products, $cartItem['product_id']);
                        }
                        else{
                            $product_ids = array();
                            if(array_key_exists(\App\Product::find($cartItem['product_id'])->user_id, $seller_products)){
                                $product_ids = $seller_products[\App\Product::find($cartItem['product_id'])->user_id];
                            }
                            array_push($product_ids, $cartItem['product_id']);
                            $seller_products[\App\Product::find($cartItem['product_id'])->user_id] = $product_ids;
                        }
                    }
                @endphp

                @if (!empty($admin_products))
                <form class="form-default" action="{{ route('checkout.store_delivery_info') }}" role="form" method="POST">
                    @csrf
                    <div class="card mb-3 shadow-sm border-0 rounded">
                        <div class="card-header p-3">
                            <h5 class="fs-16 fw-600 mb-0">{{ get_setting('site_name') }} {{ translate('Products') }}</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                @foreach ($admin_products as $key => $cartItem)
                                @php
                                    $product = \App\Product::find($cartItem);
                                @endphp
                                <li class="list-group-item">
                                    <div class="d-flex align-items-center">
                                        <span class="mr-2">
                                            <img
                                                src="{{ uploaded_asset($product->thumbnail_img) }}"
                                                class="img-fit size-100px rounded"
                                                alt="{{  $product->getTranslation('name')  }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"
                                            >
                                        </span>
                                        <span class="fs-16 opacity-60">{{ $product->getTranslation('name') }}</span>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                            @foreach ($carts as $cartItem)
                                @php
                                    $users = $cartItem->where('user_id',Auth::user()->id)->groupBy('owner_id')->get();
                                    $users = $users->count();
                                @endphp    
                            @endforeach
                            
                        </div>
                    </div>
                    @if ($users <= 1)
                        <div class="card mx-auto row border-top pt-3">
                            <div class="card-header col-md-12">
                                <h6 class="fs-15 fw-600">{{ translate('Choose Delivery Type') }}</h6>
                            </div>
                            <div class="card-body col-md-8">
                                <div class="row gutters-5">
                                    
                                    <div class="col-md-6 col-lg-3">
                                        <label class="aiz-megabox d-block bg-white mb-0">
                                            <input
                                                type="radio"
                                                name="shipping_type"
                                                class="shipping_type"
                                                value="home_delivery"
                                                onchange="show_pickup_point(this)"
                                                data-target=".pickup_point_id_admin"
                                                checked
                                            >
                                            <span class="d-flex p-3 aiz-megabox-elem">
                                                <span class="aiz-rounded-check flex-shrink-0 mt-1"></span>
                                                <span class="flex-grow-1 pl-3 fw-600">{{  translate('Home Delivery') }}</span>
                                            </span>
                                        </label>
                                    </div>
                                    
                                    @if (\App\BusinessSetting::where('type', 'pickup_point')->first()->value == 1)
                                    <div class="col-md-6 col-lg-3 my-3 my-md-0">
                                        <label class="aiz-megabox d-block bg-white mb-0">
                                            <input
                                                type="radio"
                                                name="shipping_type"
                                                class="shipping_type"
                                                value="pickup_point"
                                                onchange="show_pickup_point(this)"
                                                data-target=".pickup_point_id_admin"
                                            >
                                            <span class="d-flex p-3 aiz-megabox-elem">
                                                <span class="aiz-rounded-check flex-shrink-0 mt-1"></span>
                                                <span class="flex-grow-1 pl-3 fw-600">{{  translate('Local Pickup') }}</span>
                                            </span>
                                        </label>
                                    </div>
                                    @endif
                                </div>
                                <div class="mt-4 pickup_point_id_admin d-none">
                                    <select
                                        class="form-control aiz-selectpicker"
                                        name="pickup_point_id" id="local_pickup_point"
                                        data-live-search="true"
                                    >
                                            <option value=''>{{ translate('Select your nearest pickup point')}}</option>
                                        @foreach (\App\PickupPoint::where('pick_up_status',1)->get() as $key => $pick_up_point)
                                            <option
                                                value="{{ $pick_up_point->id }}"
                                                data-content="<span class='d-block'>
                                                                <span class='d-block fs-16 fw-600 mb-2'>{{ $pick_up_point->getTranslation('name') }}</span>
                                                                <span class='d-block opacity-50 fs-12'><i class='las la-map-marker'></i> {{ $pick_up_point->getTranslation('address') }}</span>
                                                                <span class='d-block opacity-50 fs-12'><i class='las la-phone'></i>{{ $pick_up_point->phone }}</span>
                                                            </span>"
                                            >
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
                <form class="form-default"  action="{{ route('checkout.store_delivery_info') }}" role="form" method="POST">
                    @csrf
                    @if (!empty($seller_products))
                        @foreach ($seller_products as $key => $seller_product)
                            <div class="card mb-3 shadow-sm border-0 rounded">
                                <div class="card-header p-3">
                                    <h5 class="fs-16 fw-600 mb-0">{{ \App\Shop::where('user_id', $key)->first()->name }} {{ translate('Products') }}</h5>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group list-group-flush">
                                        @foreach ($seller_product as $cartItem)
                                        @php
                                            $product = \App\Product::find($cartItem);
                                        @endphp
                                        <li class="list-group-item">
                                            <div class="d-flex align-items-center">
                                                <span class="mr-2">
                                                    <img
                                                        src="{{ uploaded_asset($product->thumbnail_img) }}"
                                                        class="img-fit size-100px rounded"
                                                        alt="{{  $product->getTranslation('name')  }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"
                                                    >
                                                </span>
                                                <span class="fs-16 opacity-60">{{ $product->getTranslation('name') }}</span>
                                            </div>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <!-- <div class="card-footer justify-content-end">
                                    <button type="submit" name="owner_id" value="{{ $key }}" class="btn fw-600 btn-primary">{{ translate('Continue to Payment')}}</a>
                                </div> -->
                            </div>
                        @endforeach
                        <div class="card row border-top pt-3 mx-auto">
                            <div class="card-header col-md-12">
                                <h6 class="fs-15 fw-600">{{ translate('Choose Delivery Type') }}</h6>
                            </div>
                            <div class="card-body col-md-8">
                                <div class="row gutters-5">
                                    <div class="col-3">
                                        
                                        <label class="aiz-megabox d-block bg-white mb-0">
                                            <input
                                                type="radio"
                                                name="shipping_type"
                                                class="shipping_type"
                                                value="home_delivery"
                                                onchange="show_pickup_point(this)"
                                                data-target=".pickup_point_id_{{ $key }}"
                                                checked
                                            >
                                            <span class="d-flex p-3 aiz-megabox-elem">
                                                <span class="aiz-rounded-check flex-shrink-0 mt-1"></span>
                                                <span class="flex-grow-1 pl-3 fw-600">{{  translate('Home Delivery') }}</span>
                                            </span>
                                        </label>
                                        
                                    </div>
                                    @foreach ($carts as $cartItem)
                                        @php
                                           $users = $cartItem->where('user_id',Auth::user()->id)->groupBy('owner_id')->get();
                                           $users = $users->count();
                                        @endphp    
                                    @endforeach
                                    @if (\App\BusinessSetting::where('type', 'pickup_point')->first()->value == 1 && $users <= 1)
                                        @if (is_array(json_decode(\App\Shop::where('user_id', $key)->first()->pick_up_point_id)))
                                        <div class="col-3">
                                            <label class="aiz-megabox d-block bg-white mb-0">
                                                <input
                                                    type="radio"
                                                    name="shipping_type"
                                                    class="shipping_type"
                                                    value="pickup_point"
                                                    onchange="show_pickup_point(this)"
                                                    data-target=".pickup_point_id_{{ $key }}"
                                                >
                                                <span class="d-flex p-3 aiz-megabox-elem">
                                                    <span class="aiz-rounded-check flex-shrink-0 mt-1"></span>
                                                    <span class="flex-grow-1 pl-3 fw-600">{{  translate('Local Pickup') }}</span>
                                                </span>
                                            </label>
                                        </div>
                                        @endif
                                    @endif
                                </div>
                                @if (\App\BusinessSetting::where('type', 'pickup_point')->first()->value == 1 && $users <= 1)
                                    @if (is_array(json_decode(\App\Shop::where('user_id', $key)->first()->pick_up_point_id)))
                                    <div class="mt-4 pickup_point_id_{{ $key }} d-none">
                                        <select
                                            class="form-control aiz-selectpicker"
                                            name="pickup_point_id" id="local_pickup_point"
                                            data-live-search="true"
                                        >
                                                <option>{{ translate('Select your nearest pickup point')}}</option>
                                            @foreach (json_decode(\App\Shop::where('user_id', $key)->first()->pick_up_point_id) as $pick_up_point)
                                                @if (\App\PickupPoint::find($pick_up_point) != null)
                                                <option
                                                    value="{{ \App\PickupPoint::find($pick_up_point)->id }}"
                                                    data-content="<span class='d-block'>
                                                                    <span class='d-block fs-16 fw-600 mb-2'>{{ \App\PickupPoint::find($pick_up_point)->getTranslation('name') }}</span>
                                                                    <span class='d-block opacity-50 fs-12'><i class='las la-map-marker'></i> {{ \App\PickupPoint::find($pick_up_point)->getTranslation('address') }}</span>
                                                                    <span class='d-block opacity-50 fs-12'><i class='las la-phone'></i> {{ \App\PickupPoint::find($pick_up_point)->phone }}</span>
                                                                </span>"
                                                >
                                                </option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    @endif
                <div class="row align-items-center">    
                    <div class="col-md-6 text-center text-md-left order-1 order-md-0 pt-4">
                        <a href="{{ route('home') }}" >
                            <i class="la la-angle-left"></i>
                            {{ translate('Return to shop')}}
                        </a>
                    </div>
                    <input type="hidden" name="" id="shipping_type" value="">
                    <div class="col-md-6 text-center text-md-right pt-4">
                        <button type="submit" name="owner_id" id="payment_button" class="btn fw-600 btn-primary">{{ translate('Continue to Payment')}}</a>
                    </div>
                </div>
                </form>
                </form>
            </div>
        </div>
    </div>
</section>

@endsection

@section('script')
    <script type="text/javascript">
        $(document).on('click', '#payment_button',function(){
            if($('#shipping_type').val()=='pickup_point' && $('#local_pickup_point').val()==''){
                AIZ.plugins.notify('danger','{{ translate('Please select your nearest pickupoint') }}');
                $('#payment_button').attr('disabled','disabled');
                return false;
            } else {
                $('#payment_button').attr('disabled',false);
            }
        });

        function display_option(key){

        }
        function show_pickup_point(el) {
            var value = $(el).val();
            $('#shipping_type').val(value);
            var target = $(el).data('target');

            // console.log(value);

            if(value == 'home_delivery'){
                if(!$(target).hasClass('d-none')){
                    $(target).addClass('d-none');
                }
            }else{
                $(target).removeClass('d-none');
            }
            $('#payment_button').attr('disabled',false);
        }

    </script>
@endsection
