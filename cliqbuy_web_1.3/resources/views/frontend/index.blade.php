@extends('frontend.layouts.app')

@section('content')
    {{-- Categories , Sliders . Today's deal --}}
    <div class="home-banner-area mb-4">
                @php
                    $num_todays_deal = count(filter_products(\App\Product::where('published', 1)->where('todays_deal', 1 ))->get());
                    $featured_categories = \App\Category::where('featured', 1)->get();
                @endphp

                <div class="">
                    @if (get_setting('home_slider_images') != null)
                        <div class="aiz-carousel banner-arrow dots-inside-bottom mobile-img-auto-height" data-arrows="true" data-dots="false" data-autoplay="true">
                            
                            @php $slider_images = json_decode(get_setting('home_slider_images'), true);  @endphp
                            @foreach ($slider_images as $key => $value)
                                <div class="carousel-box">
                                    <a href="{{ json_decode(get_setting('home_slider_links'), true)[$key] }}">
                                        <img
                                            class="d-block cls_slider_img mw-100 img-fit overflow-hidden"
                                            src="{{ uploaded_asset($slider_images[$key]) }}"
                                            alt="{{ env('APP_NAME')}} promo"
                                            @if(count($featured_categories) == 0)
                                            height="457"
                                            @else
                                            height="315"
                                            @endif
                                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';"
                                        >
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @endif
                  
                </div>
    </div>
    <div class="cls_todaydeals mb-4">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-8 col-12">
                    <div class="bg-white cls_top_deals shadow-sm">
                        <div class="rounded-top p-3 d-flex align-items-center">
                            <span class="fw-600 fs-16 mr-2 text-truncate">
                                {{ translate('Todays Deal') }}
                            </span>
                            <span class="badge badge-primary badge-inline cls_badge">{{ translate('Hot') }}</span>
                        </div>
                        <div class="c-scrollbar-light h-lg-400px p-2 rounded-bottom" style="overflow: hidden auto;">
                             <div class="row">
                            @foreach (filter_products(\App\Product::where('published', 1)->where('todays_deal', '1'))->get() as $key => $product)
                                @if ($product != null)
                                <div class="col-lg-6 col-md-6 mb-3 producct_deal">
                                    <a href="{{ route('product', $product->slug) }}" class="d-block p-2 text-reset bg-white h-100 rounded">
                                        <div class="row gutters-5 align-items-center">
                                            <div class="col-md-3">
                                                <div class="img w-100 ">
                                                    <img
                                                    style="object-fit: contain;"
                                                        class="lazyload w-100 h-140px h-lg-80px rounded"
                                                        src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                        data-src="{{ uploaded_asset($product->thumbnail_img) }}"
                                                        alt="{{ $product->getTranslation('name') }}"
                                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"

                                                    >
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="fs-14 ml-3 mt-lg-0 mt-3">                                                   
                                                  <div class="d-flex">
                                                 
                                                    @if(home_base_price($product) != home_discounted_base_price($product))
                                                    <del class="d-block opacity-100" style="color: #BFBFBF;">{{ home_base_price($product) }}</del>
                                                    <span class="mx-1">-</span>
                                                    @endif
                                                    
                                                    <span class="d-block fw-500 bace_val">
                                                     {{ home_discounted_base_price($product) }}</span>
                                                 </div>

                                                    {{$product->name}}
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                @endif
                            @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-12">
                       @if (get_setting('home_banner1_images') != null)   
                        @php $banner_1_imags = json_decode(get_setting('home_banner1_images')); @endphp
                        @foreach ($banner_1_imags as $key => $value)
                            @if($key == 0)
                            <div class="image_banner_list cls_leftbanner_img">
                                <div class="mb-3 mb-lg-0">
                                    <a href="{{ json_decode(get_setting('home_banner1_links'), true)[$key] }}" class="d-block text-reset mt-3 mt-md-0">
                                        <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}" data-src="{{ uploaded_asset($banner_1_imags[$key]) }}" alt="{{ env('APP_NAME') }} promo" class="img-fluid lazyload">
                                    </a>
                                </div>
                            </div>
                            @endif
                        @endforeach
                        @endif

                </div>
            </div>
        </div>
    </div>
   


    {{-- Banner section 1 --}}
    @if (get_setting('home_banner1_images') != null)
    <div class="mb-4">
        <div class="container-fluid">
            <div class="row align-items-center">
                @php $banner_1_imags = json_decode(get_setting('home_banner1_images')); @endphp
                @foreach ($banner_1_imags as $key => $value)
                 @if($key != 0)
                    <div class="col-md-6 col-lg-3">
                        <div class="mb-3 mb-lg-0">
                            <a href="{{ json_decode(get_setting('home_banner1_links'), true)[$key] }}" class="d-block text-reset">
                                <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}" data-src="{{ uploaded_asset($banner_1_imags[$key]) }}" alt="{{ env('APP_NAME') }} promo" class="img-fluid lazyload w-100" style="border-radius:20px;height: 206px;object-fit: contain;">
                            </a>
                        </div>
                    </div>
                     @endif
                @endforeach
            </div>
        </div>
    </div>
    @endif


    {{-- Flash Deal --}}
    @php
        $flash_deal = \App\FlashDeal::where('status', 1)->where('featured', 1)->first();
    @endphp
    @if($flash_deal != null && strtotime(date('Y-m-d H:i:s')) >= $flash_deal->start_date && strtotime(date('Y-m-d H:i:s')) <= $flash_deal->end_date)
    <section class="mb-4">
        <div class="container-fluid">
            <div class="px-2 py-4 px-md-4 py-md-3 bg-white shadow-sm rounded">

                <div class="d-flex flex-wrap mb-3 align-items-baseline border-bottom">
                    <h3 class="h5 fw-700 mb-0">
                        <span class="pb-3 d-inline-block">{{ translate('Flash Sale') }}</span>
                    </h3>
                    <div class="aiz-count-down ml-auto ml-lg-3 align-items-center" data-date="{{ date('Y/m/d H:i:s', $flash_deal->end_date) }}"></div>
                    <a href="{{ route('flash-deal-details', $flash_deal->slug) }}" class="ml-auto mr-0 btn btn-primary btn-sm shadow-md w-100 w-md-auto">{{ translate('View More') }}</a>
                </div>

                <div class="aiz-carousel gutters-10 half-outside-arrow" data-items="6" data-xl-items="5" data-lg-items="4"  data-md-items="3" data-sm-items="2" data-xs-items="2" data-arrows='true'>
                    @foreach ($flash_deal->flash_deal_products as $key => $flash_deal_product)
                        @php
                            $product = \App\Product::find($flash_deal_product->product_id);
                        @endphp
                        @if ($product != null && $product->published != 0)
                            <div class="carousel-box">
                                @include('frontend.partials.product_box_1',['product' => $product])
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    @endif


    {{-- Featured Section --}}
    <div id="section_featured">

    </div>

    {{-- Best Selling  --}}
    <div id="section_best_selling">

    </div>

    <!-- Auction Product -->
    @if(addon_activated('auction'))
        <div id="auction_products">

        </div>
    @endif



    {{-- Banner Section 2 --}}
    @if (get_setting('home_banner2_images') != null)
    <div class="mb-4">
        <div class="container-fluid">
            <div class="row">
                @php $banner_2_imags = json_decode(get_setting('home_banner2_images')); @endphp
                @foreach ($banner_2_imags as $key => $value)
                    <div class="col-12 col-lg-4">
                        <div class="mb-3 mb-lg-0">
                            <a href="{{ json_decode(get_setting('home_banner2_links'), true)[$key] }}" class="d-block text-reset">
                                <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}" data-src="{{ uploaded_asset($banner_2_imags[$key]) }}" alt="{{ env('APP_NAME') }} promo" class="img-fluid lazyload w-100">
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    {{-- Category wise Products --}}
    <div id="section_home_categories">

    </div>

    {{-- Classified Product --}}
    @if(get_setting('classified_product') == 1)
        @php
            $classified_products = \App\CustomerProduct::where('status', '1')->where('published', '1')->take(10)->get();
        @endphp
           @if (count($classified_products) > 0)
               <section class="mb-4">
                   <div class="container-fluid">
                       <div class="px-2 py-4 px-md-4 py-md-3 bg-white shadow-sm rounded">
                            <div class="d-flex mb-3 align-items-baseline border-bottom">
                                <h3 class="h5 fw-700 mb-0">
                                    <span class="pb-3 d-inline-block">{{ translate('Classified Ads') }}</span>
                                </h3>
                                <a href="{{ route('customer.products') }}" class="ml-auto mr-0 btn btn-primary btn-sm shadow-md">{{ translate('View More') }}</a>
                            </div>
                           <div class="aiz-carousel gutters-10 half-outside-arrow" data-items="6" data-xl-items="5" data-lg-items="4"  data-md-items="3" data-sm-items="2" data-xs-items="2" data-arrows='true'>
                               @foreach ($classified_products as $key => $classified_product)
                                   <div class="carousel-box">
                                        <div class="aiz-card-box border border-light rounded my-2 has-transition">
                                            <div class="position-relative">
                                                <a href="{{ route('customer.product', $classified_product->slug) }}" class="d-block">
                                                    <img
                                                        class="img-fit lazyload mx-auto h-140px h-md-210px"
                                                        src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                        data-src="{{ uploaded_asset($classified_product->thumbnail_img) }}"
                                                        alt="{{ $classified_product->getTranslation('name') }}"
                                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"
                                                    >
                                                </a>
                                                <div class="absolute-top-left pt-2 pl-2">
                                                    @if($classified_product->conditon == 'new')
                                                       <span class="badge badge-inline badge-success">{{translate('new')}}</span>
                                                    @elseif($classified_product->conditon == 'used')
                                                       <span class="badge badge-inline badge-danger">{{translate('Used')}}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="py-md-3 py-2 text-left">
                                                <div class="fs-15 mb-1">
                                                    <span class="fw-700 text-primary">{{ single_price($classified_product->unit_price) }}</span>
                                                </div>
                                                <h3 class="fw-600 fs-13 text-truncate-2 lh-1-4 mb-0 h-35px">
                                                    <a href="{{ route('customer.product', $classified_product->slug) }}" class="d-block text-reset">{{ $classified_product->getTranslation('name') }}</a>
                                                </h3>
                                            </div>
                                       </div>
                                   </div>
                               @endforeach
                           </div>
                       </div>
                   </div>
               </section>
           @endif
       @endif

    {{-- Banner Section 2 --}}
    @if (get_setting('home_banner3_images') != null)
    <div class="mb-4">
        <div class="container-fluid">
            <div class="row">
                @php $banner_3_imags = json_decode(get_setting('home_banner3_images')); @endphp
                @foreach ($banner_3_imags as $key => $value)
                    <div class="col-12 col-lg-3 col-md-6">
                        <div class="mb-3 mb-lg-0">
                            <a href="{{ json_decode(get_setting('home_banner3_links'), true)[$key] }}" class="d-block text-reset">
                                <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}" data-src="{{ uploaded_asset($banner_3_imags[$key]) }}" alt="{{ env('APP_NAME') }} promo" class="img-fluid lazyload w-100" style="border-radius:20px;height: 206px;object-fit: contain;">
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    
   

    {{-- Top 10 categories and Brands --}}
    @if (get_setting('top10_categories') != null && get_setting('top10_brands') != null)
    <section class="mb-4">
        <div class="container-fluid">
            <div class="row gutters-10">
                {{-- Best Seller --}}
                 @if (!isSingleStoreActivated())
                 <div class="col-lg-4">
                    <div id="section_best_sellers">

                    </div>
                </div>
                    @endif
                @if (get_setting('top10_categories') != null)
                    <div class="col-lg-4 ">
                        <div class="pb-2 pb-md-3 bg-white shadow-sm rounded">
                            <div class="px-2 pt-4 px-md-4 pt-md-3 mb-3 cls_btn d-flex align-items-baseline">
                                <h3 class="h5 fw-700 mb-0">
                                    <span class="pb-3 d-inline-block">{{ translate('Top 10 Categories') }}</span>
                                </h3>
                                <a href="{{ route('categories.all') }}" class="ml-auto mr-0 btn btn-md px-4">{{ translate('View All Categories') }}</a>
                            </div>
                            <div class="cls_scroll px-2 pb-4 px-md-4 pb-md-3 ">
                                <div class="row gutters-5 ">
                                    @php $top10_categories = json_decode(get_setting('top10_categories')); @endphp
                                    @foreach ($top10_categories as $key => $value)
                                        @php $category = \App\Category::find($value); @endphp
                                        @if ($category != null)
                                            <div class="col-sm-6">
                                                <a href="{{ route('products.category', $category->slug) }}" class="bg-white d-block text-reset rounded p-2  mb-2">
                                                    <div class="row align-items-center no-gutters">
                                                        <div class="col-3 text-center">
                                                            <img
                                                                src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                                data-src="{{ uploaded_asset($category->banner) }}"
                                                                alt="{{ $category->getTranslation('name') }}"
                                                                class="img-fluid img lazyload h-60px"
                                                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"
                                                            >
                                                        </div>
                                                        <div class="col-7">
                                                            <div class="text-truncat-2 pl-3 fs-14 fw-600 text-left">{{ $category->getTranslation('name') }}</div>
                                                        </div>
                                                        <div class="col-2 text-center grey_arrow_icon">
                                                            <i class="la la-angle-right text-primary"></i>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                @if (get_setting('top10_brands') != null)
                    <div class="col-lg-4">
                        <div class="pb-2 pb-md-3 bg-white shadow-sm rounded">
                            <div class="px-2 pt-4 px-md-4 pt-md-3 mb-3  cls_btn d-flex align-items-baseline">
                                <h3 class="h5 fw-700 mb-0">
                                    <span class="pb-3 d-inline-block">{{ translate('Top 10 Brands') }}</span>
                                </h3>
                                <a href="{{ route('brands.all') }}" class="ml-auto mr-0 btn btn-md px-4">{{ translate('View All Brands') }}</a>
                            </div>
                            <div class="cls_scroll px-2 pb-4 px-md-4 pb-md-3 ">
                                <div class="row gutters-5">
                                    @php $top10_brands = json_decode(get_setting('top10_brands')); @endphp
                                    @foreach ($top10_brands as $key => $value)
                                        @php $brand = \App\Brand::find($value); @endphp
                                        @if ($brand != null)
                                            <div class="col-sm-6">
                                                <a href="{{ route('products.brand', $brand->slug) }}" class="bg-white d-block text-reset rounded p-2 mb-2">
                                                    <div class="row align-items-center no-gutters">
                                                        <div class="col-4 text-center">
                                                            <img
                                                                src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                                data-src="{{ uploaded_asset($brand->logo) }}"
                                                                alt="{{ $brand->getTranslation('name') }}"
                                                                class="img-fluid img lazyload h-60px"
                                                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"
                                                            >
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="text-truncate-2 pl-3 fs-14 fw-600 text-left">{{ $brand->getTranslation('name') }}</div>
                                                        </div>
                                                        <div class="col-2 text-center grey_arrow_icon">
                                                            <i class="la la-angle-right text-primary"></i>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
    @endif

@endsection

@section('script')
    <script>
        $(document).ready(function(){
            $.post('{{ route('home.section.featured') }}', {_token:'{{ csrf_token() }}'}, function(data){
                $('#section_featured').html(data);
                AIZ.plugins.slickCarousel();
            });
            $.post('{{ route('home.section.best_selling') }}', {_token:'{{ csrf_token() }}'}, function(data){
                $('#section_best_selling').html(data);
                AIZ.plugins.slickCarousel();
            });
            $.post('{{ route('home.section.auction_products') }}', {_token:'{{ csrf_token() }}'}, function(data){
                $('#auction_products').html(data);
                AIZ.plugins.slickCarousel();
            });
            $.post('{{ route('home.section.home_categories') }}', {_token:'{{ csrf_token() }}'}, function(data){
                $('#section_home_categories').html(data);
                AIZ.plugins.slickCarousel();
            });

            @if (!isSingleStoreActivated())
            $.post('{{ route('home.section.best_sellers') }}', {_token:'{{ csrf_token() }}'}, function(data){
                $('#section_best_sellers').html(data);
                AIZ.plugins.slickCarousel();
            });
            @endif
        });
    </script>
@endsection
