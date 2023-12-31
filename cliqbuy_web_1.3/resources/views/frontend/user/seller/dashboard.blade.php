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
        <div class="col-md-3">
            <div class="bg-white text-dark mb-4 overflow-hidden card-voilet" style="border-radius:15px;">
              <div class="px-3 pt-3">
                <div class="h3 fw-700">
                  {{ count(\App\Product::where('user_id', Auth::user()->id)->get()) }}
                </div>
                <div class="opacity-50">{{ translate('Products')}}</div>
              </div>
             
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                <path fill="#E4E7F3" fill-opacity="1" d="M0,160L48,133.3C96,107,192,53,288,53.3C384,53,480,107,576,128C672,149,768,139,864,112C960,85,1056,43,1152,26.7C1248,11,1344,21,1392,26.7L1440,32L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
            </svg>
            </div>
        </div>

        <div class="col-md-3">
            <div class="bg-white text-dark mb-4 overflow-hidden card-voilet" style="border-radius:15px;">
                <div class="px-3 pt-3">
                    <div class="h3 fw-700">
                      {{ count(\App\OrderDetail::where('seller_id', Auth::user()->id)->where('delivery_status', 'delivered')->get()) }}
                    </div>
                    <div class="opacity-50">{{ translate('Total sale')}}</div>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                  <path fill="#E4E7F3" fill-opacity="1" d="M0,160L48,133.3C96,107,192,53,288,53.3C384,53,480,107,576,128C672,149,768,139,864,112C960,85,1056,43,1152,26.7C1248,11,1344,21,1392,26.7L1440,32L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
              </svg>
              
            </div>

        </div>

        <div class="col-md-3">
            <div class="bg-white text-dark mb-4 overflow-hidden card-voilet" style="border-radius:15px;">
                <div class="px-3 pt-3">
                    @php
                        $orderDetails = \App\OrderDetail::where('seller_id', Auth::user()->id)->get();
                        $total = 0;
                        foreach ($orderDetails as $key => $orderDetail) {
                            if($orderDetail->order != null && $orderDetail->order->payment_status == 'paid'){
                                $total += $orderDetail->price;
                            }
                        }
                    @endphp
                    <div class="h3 fw-700">{{ single_price($total) }}</div>
                    <div class="opacity-50">{{ translate('Total earnings') }}</div>
                  </div>
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                <path fill="#E4E7F3" fill-opacity="1" d="M0,160L48,133.3C96,107,192,53,288,53.3C384,53,480,107,576,128C672,149,768,139,864,112C960,85,1056,43,1152,26.7C1248,11,1344,21,1392,26.7L1440,32L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
            </svg>
            </div>
        </div>

        <div class="col-md-3">
            <div class="bg-white text-dark mb-4 overflow-hidden card-voilet" style="border-radius:15px;">
              <div class="px-3 pt-3">
                  @php
                  $orders = \App\Order::where('user_id', Auth::user()->id)->get();
                  $total = 0;
                  foreach ($orders as $key => $order) {
                  $total += count($order->orderDetails);
                  }
                  @endphp
                  <div class="h3 fw-700">
                      {{ count(\App\OrderDetail::where('seller_id', Auth::user()->id)->where('delivery_status', 'delivered')->get()) }}
                  </div>
                  <div class="opacity-50">{{ translate('Successful orders')}}</div>
              </div>
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                <path fill="#E4E7F3" fill-opacity="1" d="M0,160L48,133.3C96,107,192,53,288,53.3C384,53,480,107,576,128C672,149,768,139,864,112C960,85,1056,43,1152,26.7C1248,11,1344,21,1392,26.7L1440,32L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
            </svg>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-7">
          <div class="card border-0">
              <div class="card-header">
                  <h5 class="mb-0 h6">{{ translate('Orders') }}</h5>
              </div>
              <div class="card-body">
                  <table class="table aiz-table mb-0">
                      <tr>
                          <td>{{ translate('Total orders')}}:</td>
                          <td>{{ count(\App\OrderDetail::where('seller_id', Auth::user()->id)->get()) }}</strong></td>
                      </tr>
                      <tr>
                          <td>{{ translate('Pending orders')}}:</td>
                          <td>{{ count(\App\OrderDetail::where('seller_id', Auth::user()->id)->where('delivery_status', 'pending')->get()) }}</strong></td>
                      </tr>
                      <tr>
                          <td>{{ translate('Cancelled orders')}}:</td>
                          <td>{{ count(\App\OrderDetail::where('seller_id', Auth::user()->id)->where('delivery_status', 'cancelled')->get()) }}</strong></td>
                      </tr>
                      <tr>
                          <td>{{ translate('Successful orders')}}:</td>
                          <td>{{ count(\App\OrderDetail::where('seller_id', Auth::user()->id)->where('delivery_status', 'delivered')->get()) }}</strong></td>
                      </tr>
                  </table>
              </div>
          </div>
        </div>
        
        <div class="col-md-5">
          <div class="card border-0 p-5 text-center">
              <div class="mb-3">
                  @if(Auth::user()->seller->verification_status == 0)
                      <img loading="lazy"  src="{{ static_asset('assets/img/non_verified.png') }}" alt="" width="130">
                  @else
                      <img loading="lazy"  src="{{ static_asset('assets/img/verified.png') }}" alt="" width="130">
                  @endif
              </div>
              @if(Auth::user()->seller->verification_status == 0)
                  <a href="{{ route('shop.verify') }}" class="btn btn-primary">{{ translate('verify_now')}}</a>
              @endif
          </div>
        </div>
        
    </div>

    <div class="row">
      <div class="col-md-8">
          <div class="card">
              <div class="card-header">
                  <h6 class="mb-0">{{ translate('Products') }}</h6>
              </div>
    		          <div class="card-body">
                <table class="table aiz-table mb-0">
                  <thead>
                      <tr>
                          <th>{{ translate('Category')}}</th>
                          <th>{{ translate('Product')}}</th>
                      </tr>
                  </thead>
                  <tbody>
                    @foreach (\App\Category::all() as $key => $category)
                        @if(count($category->products->where('user_id', Auth::user()->id))>0)
                          <tr>
                              <td>{{ $category->getTranslation('name') }}</td>
                              <td>{{ count($category->products->where('user_id', Auth::user()->id)) }}</td>
                          </tr>
                      @endif
                  @endforeach
                </table>
                <br>
                <div class="text-center">
                    <a href="{{ route('seller.products.upload')}}" class="btn btn-primary d-inline-block">{{ translate('Add New Product')}}</a>
                </div>
              </div>
          </div>
      </div>
      <div class="col-md-4">
          @if (\App\Addon::where('unique_identifier', 'seller_subscription')->first() != null && \App\Addon::where('unique_identifier', 'seller_subscription')->first()->activated)

              <div class="card">
                  <div class="card-header">
                      <h6 class="mb-0">{{ translate('Purchased Package') }}</h6>
                  </div>
                  @php
                      $seller_package = \App\SellerPackage::find(Auth::user()->seller->seller_package_id);
                  @endphp
                  <div class="card-body text-center">
                      @if($seller_package != null)
                        <img src="{{ uploaded_asset($seller_package->logo) }}" class="img-fluid mb-4 h-110px">
                        <p class="mb-1 text-muted">{{ translate('Product Upload Remaining') }}: {{ Auth::user()->seller->remaining_uploads }} {{ translate('Times')}}</p>
                        <p class="text-muted mb-1">{{ translate('Digital Product Upload Remaining') }}: {{ Auth::user()->seller->remaining_digital_uploads }} {{ translate('Times')}}</p>
                        <p class="text-muted mb-4">{{ translate('Package Expires at') }}: {{ Auth::user()->seller->invalid_at }}</p>
                        <h6 class="fw-600 mb-3 text-primary">{{ translate('Current Package') }}: {{ $seller_package->name }}</h6>
                      @else
                          <h6 class="fw-600 mb-3 text-primary">{{translate('Package Not Found')}}</h6>
                      @endif
                      <div class="text-center">
                          <a href="{{ route('seller_packages_list') }}" class="btn btn-soft-primary">{{ translate('Upgrade Package')}}</a>
                      </div>
                  </div>
              </div>
          @endif
          <div class="card mb-4 p-4 text-center">
              <div class="h5 fw-600">{{ translate('Shop')}}</div>
              <p>{{ translate('Manage & organize your shop')}}</p>
              <a href="{{ route('shops.index') }}" class="btn btn-soft-primary">{{ translate('Go to setting')}}</a>
          </div>
          <div class="card mb-4 p-4 text-center">
              <div class="h5 fw-600">{{ translate('Payment')}}</div>
              <p>{{ translate('Configure your payment method')}}</p>
              <a href="{{ route('profile') }}" class="btn btn-soft-primary">{{ translate('Configure Now')}}</a>
          </div>
      </div>
    </div>

@endsection
