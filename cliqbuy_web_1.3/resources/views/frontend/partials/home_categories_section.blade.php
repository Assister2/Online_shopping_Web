@php $home_categories = json_decode(get_setting('home_categories')); @endphp
@foreach ($home_categories as $key => $value)
    @php $category = \App\Category::find($value); @endphp
    <section class="mb-4">
        <div class="container-fluid">
            <div class="p-5 bg-white cls_list_view">
                <div class="d-flex mb-4 align-items-baseline">
                    <h3 class="h5 fw-700 mb-0">
                        <span class="d-inline-block mob_heading_width">{{ $category->getTranslation('name') }}</span>
                    </h3>
                    
                </div>
                <div class="aiz-carousel gutters-10 half-outside-arrow" data-items="5" data-xl-items="5" data-lg-items="4"  data-md-items="3" data-sm-items="2" data-xs-items="1" data-arrows='true'>
                    @foreach (get_cached_products($category->id) as $key => $product)
                        <div class="carousel-box">
                            @include('frontend.partials.product_box_1',['product' => $product])
                        </div>
                    @endforeach
                </div>
                <div class="cls_btn d-block text-center mt-4">
                    <a href="{{ route('products.category', $category->slug) }}" class="ml-auto mr-0 btn btn-md px-4">{{ translate('View More') }}</a>
                </div>
            </div>
        </div>
    </section>
@endforeach
