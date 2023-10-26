@if (!isSingleStoreActivated())
    @php
        $array = array();
        foreach (\App\Seller::where('verification_status', 1)->get() as $key => $seller) {
            if($seller->user != null && $seller->user->shop != null){
                $total_sale = 0;
                foreach ($seller->user->products as $key => $product) {
                    $total_sale += $product->num_of_sale;
                }
                $array[$seller->id] = $total_sale;
            }
        }
        asort($array);
    @endphp
    @if(!empty($array))
        <section class="mb-4">
        <div class="container-fluid">
            <div class="px-2 py-4 px-md-4 py-md-3 bg-white shadow-sm rounded">
                <div class="cls_btn d-flex mb-3 align-items-baseline">
                    <h3 class="h5 fw-700 mb-0">
                        <span class="pb-3 d-inline-block">{{ translate('Best Sellers')}}</span>
                    </h3>
                    <a href="{{ route('sellers') }}" class="ml-auto mr-0 btn btn-md px-4">{{ translate('View All Sellers') }}</a>
                </div>
                <div class="cls_scroll">
                    @php
                        $count = 0;
                    @endphp
                    @foreach ($array as $key => $value)
                        @if ($count < 20)
                            @php
                                $count ++;
                                $seller = \App\Seller::find($key);
                                $total = 0;
                                $rating = 0;
                                foreach ($seller->user->products as $key => $seller_product) {
                                    $total += $seller_product->reviews->count();
                                    $rating += $seller_product->reviews->sum('rating');
                                }
                            @endphp
                            
                                <div class="carousel-box ">
                                    <div class="row no-gutters box-3 align-items-center my-2 has-transition">
                                        <div class="col-4">
                                            <a href="{{ route('shop.visit', $seller->user->shop->slug) }}" class="d-block p-3">
                                                <img
                                                    src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                    data-src="@if ($seller->user->shop->logo !== null) {{ uploaded_asset($seller->user->shop->logo) }} @else {{ static_asset('assets/img/placeholder.jpg') }} @endif"
                                                    alt="{{ $seller->user->shop->name }}"
                                                    class="img-fluid lazyload"
                                                >
                                            </a>
                                        </div>
                                        <div class="col-8">
                                            <div class="p-3 text-left visit_store_btn">
                                                <h2 class="h6 fw-600 text-truncate">
                                                    <a href="{{ route('shop.visit', $seller->user->shop->slug) }}" class="text-reset">{{ $seller->user->shop->name }}</a>
                                                </h2>
                                                <div class="rating rating-sm mb-2">
                                                    @if ($total > 0)
                                                        {{ renderStarRating($rating/$total) }}
                                                    @else
                                                        {{ renderStarRating(0) }}
                                                    @endif
                                                </div>
                                                <a href="{{ route('shop.visit', $seller->user->shop->slug) }}" class="btn btn-soft-primary btn-sm">
                                                    {{ translate('Visit Store') }} 
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                        @endif
                    @endforeach
                    </div>
            </div>
        </div>
    </section>
    @endif
@endif
