@if (get_setting('best_selling') == 1)
    <section class="mb-4">
        <div class="container-fluid">
            <div class="p-5 bg-white cls_list_view">
               <div class="d-flex mb-4 align-items-baseline">
                <h3 class="h5 fw-700 mb-0">
                        <span class="d-inline-block mob_heading_width">{{ translate('Best Selling') }}</span>
                    </h3>
                    <a href="javascript:void(0)" class="ml-3 mr-0 btn btn-sm shadow-md" style="background-color:#FEBD69">{{ translate('Top 20') }}</a>
                </div>
                <div class="aiz-carousel gutters-10 half-outside-arrow" data-items="5" data-xl-items="5" data-lg-items="4"  data-md-items="3" data-sm-items="2" data-xs-items="1" data-arrows='true' data-infinite='true'>
                    @foreach (filter_products(\App\Product::where('published', 1)->orderBy('num_of_sale', 'desc'))->limit(20)->get() as $key => $product)
                        <div class="carousel-box">
                            @include('frontend.partials.product_box_1',['product' => $product])
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endif
