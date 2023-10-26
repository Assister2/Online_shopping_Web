
@extends('frontend.layouts.user_panel')

@section('panel_content')
<div class="cls_bread">
    <ul>
        <li><a href="{{ route('mainmenu') }}">{{ translate('main') }}</a></li>
        <li><a>{{ translate('Dashboard') }}</a></li>
    </ul>
</div>
<div class="aiz-titlebar mt-2 mb-4">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{ translate('Dashboard') }}</h1>
        </div>
    </div>
</div>
<div class="row gutters-10">
    <div class="col-md-4">
        <div class="bg-white text-dark mb-4 overflow-hidden" style="border-radius:15px;">
            <div class="px-3 pt-3">
                @php
                    $user_id = Auth::user()->id;
                    $cart = \App\Cart::where('user_id', $user_id)->get();
                @endphp
                @if(count($cart) > 0)
                <div class="h3 fw-700">
                    {{ count($cart) }} {{ translate('Product(s)') }}
                </div>
                @else
                <div class="h3 fw-700">
                    0 {{ translate('Product') }}
                </div>
                @endif
                <div class="opacity-50">{{ translate('in your cart') }}</div>
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                <path fill="#fff7f2" fill-opacity="1" d="M0,160L48,133.3C96,107,192,53,288,53.3C384,53,480,107,576,128C672,149,768,139,864,112C960,85,1056,43,1152,26.7C1248,11,1344,21,1392,26.7L1440,32L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
            </svg>
            <!-- <a href="{{ route('cart') }}" class="navi_link">View Details <i class="la la-arrow-alt-circle-right"></i></a> -->
        </div>
    </div>
    <div class="col-md-4">
        <div class="bg-white text-dark mb-4 overflow-hidden" style="border-radius:15px;">
            <div class="px-3 pt-3">
                @php
                    $orders = \App\Order::where('user_id', Auth::user()->id)->get();
                    $total = 0;
                    foreach ($orders as $key => $order) {
                        $total += count($order->orderDetails);
                    }
                @endphp
                <div class="h3 fw-700">{{ count(Auth::user()->wishlists)}} {{ translate('Product(s)') }}</div>
                <div class="opacity-50">{{ translate('in your wishlist') }}</div>
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                <path fill="#fff7f2" fill-opacity="1" d="M0,160L48,133.3C96,107,192,53,288,53.3C384,53,480,107,576,128C672,149,768,139,864,112C960,85,1056,43,1152,26.7C1248,11,1344,21,1392,26.7L1440,32L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
            </svg>
            <!-- <a href="{{ route('wishlists.index') }}" class="navi_link">View Details <i class="la la-arrow-alt-circle-right"></i></a> -->
        </div>
    </div>
    <div class="col-md-4">
        <div class="bg-white text-dark mb-4 overflow-hidden" style="border-radius:15px;">
            <div class="px-3 pt-3">
                @php
                    $orders = \App\Order::where('user_id', Auth::user()->id)->get();
                    $total = 0;
                    foreach ($orders as $key => $order) {
                        $total += count($order->orderDetails);
                    }
                @endphp
                <div class="h3 fw-700">{{ $total }} {{ translate('Product(s)') }}</div>
                <div class="opacity-50">{{ translate('you ordered') }}</div>
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                <path fill="#fff7f2" fill-opacity="1" d="M0,160L48,133.3C96,107,192,53,288,53.3C384,53,480,107,576,128C672,149,768,139,864,112C960,85,1056,43,1152,26.7C1248,11,1344,21,1392,26.7L1440,32L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
            </svg>
        </div>
    </div>
</div>
<div class="row gutters-10">
    <div class="col-md-6">
        <div class="card border-0">
            <div class="card-header">
                <h6 class="mb-0">{{ translate('Default Shipping Address') }}</h6>
            </div>
            <div class="card-body">
                @if(Auth::user()->addresses != null)
                    @php
                        $address = Auth::user()->addresses->where('set_default', 1)->first();
                    @endphp
                    @if($address != null)
                        <ul class="list-unstyled mb-0">
                            <li class=" py-2"><span>{{ translate('Address') }} : {{ $address->address }}</span></li>
                            <li class=" py-2"><span>{{ translate('Country') }} : {{ $address->country }}</span></li>
                            <li class=" py-2"><span>{{ translate('City') }} : {{ $address->city }}</span></li>
                            <li class=" py-2"><span>{{ translate('Postal Code') }} : {{ $address->postal_code }}</span></li>
                            <li class=" py-2"><span>{{ translate('Phone') }} : {{ $address->phone }}</span></li>
                        </ul>
                    @endif
                @endif
            </div>
        </div>
    </div>
    @if (get_setting('classified_product'))
    <div class="col-md-6">
        <div class="card border-0">
            <div class="card-header">
                <h6 class="mb-0">{{ translate('Purchased Package') }}</h6>
            </div>
            <div class="card-body text-center">
                @php
                    $customer_package = \App\CustomerPackage::find(Auth::user()->customer_package_id);
                @endphp
                @if($customer_package != null)
                    <img src="{{ uploaded_asset($customer_package->logo) }}" class="img-fluid mb-4 h-110px">
                    <p class="mb-1 text-muted">{{ translate('Product Upload') }}: {{ $customer_package->product_upload }} {{ translate('Times')}}</p>
                    <p class="text-muted mb-4">{{ translate('Product Upload Remaining') }}: {{ Auth::user()->remaining_uploads }} {{ translate('Times')}}</p>
                    <h5 class="fw-600 mb-3 text-primary">{{ translate('Current Package') }}: {{ $customer_package->getTranslation('name') }}</h5>
                @else
                    <h5 class="fw-600 mb-3 text-primary">{{translate('Package Not Found')}}</h5>
                @endif
                    <a href="{{ route('customer_packages_list_show') }}" class="btn btn-success d-inline-block">{{ translate('Upgrade Package') }}</a>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
