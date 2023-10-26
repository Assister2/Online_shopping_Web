@extends('backend.layouts.app')

@section('content')
@if(get_setting('mail_username') == null && get_setting('mail_password') == null)
    <div class="">
        <div class="alert alert-danger d-flex align-items-center">
            {{translate('Please Configure Email Setting to work all email sending functionality')}},
            <a class="alert-link ml-2" href="{{ route('smtp_settings.index') }}">{{ translate('Configure Now') }}</a>
        </div>
    </div>
@endif
@if(Auth::user()->user_type == 'admin' || in_array('1', json_decode(Auth::user()->staff->role->permissions)))
<div class="row gutters-10">
    <div class="col-lg-12">
        <div class="row gutters-10">
            <div class="col-3">
                <div class="dash1">
                    <div class="img">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="30" height="30" viewBox="0 0 50 50"> <defs> <clipPath id="clip-Total_Customers"> <rect width="50" height="50"/> </clipPath> </defs> <g id="Total_Customers" data-name="Total Customers" clip-path="url(#clip-Total_Customers)"> <g id="customer" transform="translate(1 -28.63)"> <circle id="Ellipse_77" data-name="Ellipse 77" cx="8.156" cy="8.156" r="8.156" transform="translate(15.844 32.631)" fill="#222222"/> <circle id="Ellipse_78" data-name="Ellipse 78" cx="5.156" cy="5.156" r="5.156" transform="translate(35.344 38.631)" fill="#222222"/> <circle id="Ellipse_79" data-name="Ellipse 79" cx="5.156" cy="5.156" r="5.156" transform="translate(2.344 38.631)" fill="#222222"/> <path id="Path_8718" data-name="Path 8718" d="M12.58,241.981c-2.03-1.663-3.868-1.443-6.215-1.443A6.354,6.354,0,0,0,0,246.865V257.1a2.755,2.755,0,0,0,2.757,2.747c6.565,0,5.774.119,5.774-.283C8.531,252.312,7.672,246.992,12.58,241.981Z" transform="translate(0 -188.407)" fill="#222222"/> <path id="Path_8719" data-name="Path 8719" d="M135.149,239.961c-4.1-.342-7.662,0-10.735,2.541-5.143,4.119-4.153,9.666-4.153,16.451a3.289,3.289,0,0,0,3.283,3.283c19.789,0,20.577.638,21.75-1.96.385-.879.279-.6.279-9.007C145.574,244.591,139.792,239.961,135.149,239.961Z" transform="translate(-108.917 -187.792)" fill="#222222"/> <path id="Path_8720" data-name="Path 8720" d="M384.025,240.539c-2.36,0-4.188-.218-6.215,1.443,4.872,4.974,4.049,9.931,4.049,17.587,0,.4-.656.283,5.676.283a2.853,2.853,0,0,0,2.856-2.844V246.866A6.354,6.354,0,0,0,384.025,240.539Z" transform="translate(-342.39 -188.408)" fill="#222222"/> </g> </g></svg>
                    </div>
                    <div class="text">
                        <span>{{ translate('Total') }} {{ translate('Customer') }}</span>
                        <strong>{{ \App\Customer::all()->count() }}</strong>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="dash1">
                    <div class="img">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="30" height="30" viewBox="0 0 50 50"> <defs> <clipPath id="clip-Total_Orders"> <rect width="50" height="50"/> </clipPath> </defs> <g id="Total_Orders" data-name="Total Orders" clip-path="url(#clip-Total_Orders)"> <g id="bxs-cart-alt" transform="translate(-2 -1)"> <path id="Path_8791" data-name="Path 8791" d="M48.6,6H3v4.8H8.52l8.449,23.237a4.809,4.809,0,0,0,4.51,3.161H41.4V32.4H21.479l-1.747-4.8H41.4A2.4,2.4,0,0,0,43.6,26.144l7.2-16.8A2.4,2.4,0,0,0,48.6,6Z" transform="translate(0 0)" fill="#1a1a26"/> <path id="Path_8792" data-name="Path 8792" d="M20.7,30.6A3.6,3.6,0,1,1,17.1,27,3.6,3.6,0,0,1,20.7,30.6Z" transform="translate(6.299 12.598)" fill="#1a1a26"/> <path id="Path_8793" data-name="Path 8793" d="M29.7,30.6A3.6,3.6,0,1,1,26.1,27,3.6,3.6,0,0,1,29.7,30.6Z" transform="translate(11.698 12.598)" fill="#1a1a26"/> </g> </g></svg>
                    </div>
                    <div class="text">
                        <span>{{ translate('Total') }} {{ translate('Order') }}</span>
                        <strong>{{ \App\Order::all()->count() }}</strong>
                    </div>
                </div>
               
            </div>
            <div class="col-3">
                  <div class="dash1">
                    <div class="img">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="30" height="30" viewBox="0 0 50 50"> <defs> <clipPath id="clip-Total_Product_Category"> <rect width="50" height="50"/> </clipPath> </defs> <g id="Total_Product_Category" data-name="Total Product Category" clip-path="url(#clip-Total_Product_Category)"> <path id="Icon_awesome-boxes" data-name="Icon awesome-boxes" d="M46.667,24H40v8l-2.667-1.775L34.667,32V24H28a1.337,1.337,0,0,0-1.333,1.333v16A1.337,1.337,0,0,0,28,42.667H46.667A1.337,1.337,0,0,0,48,41.333v-16A1.337,1.337,0,0,0,46.667,24Zm-32-5.333H33.333a1.337,1.337,0,0,0,1.333-1.333v-16A1.337,1.337,0,0,0,33.333,0H26.667V8L24,6.225,21.333,8V0H14.667a1.337,1.337,0,0,0-1.333,1.333v16A1.337,1.337,0,0,0,14.667,18.667ZM20,24H13.333v8l-2.667-1.775L8,32V24H1.333A1.337,1.337,0,0,0,0,25.333v16a1.337,1.337,0,0,0,1.333,1.333H20a1.337,1.337,0,0,0,1.333-1.333v-16A1.337,1.337,0,0,0,20,24Z" transform="translate(1 4)"/> </g></svg>
                    </div>
                    <div class="text">
                        <span>{{ translate('Total') }} {{ translate('Product category') }}</span>
                        <strong>{{ \App\Category::all()->count() }}</strong>
                    </div>
                </div>
            </div>
            <div class="col-3">
                 <div class="dash1">
                    <div class="img">
                         <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="30" height="30" viewBox="0 0 50 50"> <defs> <clipPath id="clip-Total_Product_Brand"> <rect width="50" height="50"/> </clipPath> </defs> <g id="Total_Product_Brand" data-name="Total Product Brand" clip-path="url(#clip-Total_Product_Brand)"> <g id="Group_8447" data-name="Group 8447" transform="translate(-2641.043 -648.471)"> <path id="Path_8794" data-name="Path 8794" d="M2867.386,664.4q-4.421,12.666-8.846,25.331a7.083,7.083,0,0,1-6.9,4.864,7,7,0,0,1-6.494-9.216c1.719-5.031,3.533-10.029,5.308-15.041,1.072-3.028,2.139-6.057,3.225-9.08a7.046,7.046,0,0,1,13.6,1.1c.026.151.068.3.1.45Zm-14.154,23.181a1.4,1.4,0,0,0-1.368-1.427,1.4,1.4,0,1,0-.041,2.809A1.394,1.394,0,0,0,2853.232,687.584Zm5.708-24.008a1.394,1.394,0,0,0,1.4,1.389,1.405,1.405,0,1,0-.031-2.81A1.4,1.4,0,0,0,2858.94,663.577Z" transform="translate(-177.342 -1.792)"/> <path id="Path_8795" data-name="Path 8795" d="M2667.37,863.607h-.53q-8.832,0-17.666,0a7.055,7.055,0,0,1-7.127-7.367,6.954,6.954,0,0,1,6.1-6.6.869.869,0,0,1,.552.14q9.238,6.807,18.462,13.633C2667.208,863.443,2667.247,863.488,2667.37,863.607Zm-18.291-8.446a1.409,1.409,0,0,0-1.4,1.4,1.443,1.443,0,0,0,1.4,1.413,1.414,1.414,0,0,0,1.412-1.436A1.387,1.387,0,0,0,2649.08,855.161Z" transform="translate(0 -170.717)"/> <path id="Path_8796" data-name="Path 8796" d="M2791.433,681.059c-.2-.653-.366-1.185-.528-1.718q-2.4-7.854-4.79-15.709a7,7,0,0,1,6.916-9.157,6.657,6.657,0,0,1,6.081,4,1.118,1.118,0,0,1,.04.781q-3.409,9.687-6.852,19.363Zm1.406-18.156a1.388,1.388,0,0,0,1.385-1.4,1.4,1.4,0,1,0-2.809.036A1.4,1.4,0,0,0,2792.839,662.9Z" transform="translate(-125.744)"/> <path id="Path_8797" data-name="Path 8797" d="M2699.592,750.98c-.19-.13-.315-.21-.434-.3q-7.063-5.218-14.125-10.439a7.048,7.048,0,0,1,.681-11.888,6.737,6.737,0,0,1,6.717-.007.945.945,0,0,1,.429.5q3.349,10.911,6.669,21.83C2699.551,750.744,2699.56,750.819,2699.592,750.98ZM2687.736,734.5a1.386,1.386,0,0,0,1.368,1.421,1.4,1.4,0,1,0,.034-2.809A1.389,1.389,0,0,0,2687.736,734.5Z" transform="translate(-35.035 -63.842)"/> </g> </g></svg>
                    </div>
                    <div class="text">
                        <span>{{ translate('Total') }}  {{ translate('Product brand') }}</span>
                        <strong>{{ \App\Brand::all()->count() }}</strong>
                    </div>
                </div>
                
            </div>
        </div>
    </div>

    <div class="col-lg-12 mt-5">
        <div class="row gutters-10">
            <div class="col-{{ (!isSingleStoreActivated()) ? 6 : 12 }}">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0 fs-14">{{ translate('Products') }}</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="pie-1" class="w-100 cls_canvas" height="305"></canvas>
                    </div>
                </div>
            </div>

            @if(!isSingleStoreActivated())
            <div class="col-6">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0 fs-14">{{ translate('Merchants') }}</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="pie-2" class="w-100 cls_canvas" height="305"></canvas>
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>
</div>
@endif

{{--
@if(Auth::user()->user_type == 'admin' || in_array('1', json_decode(Auth::user()->staff->role->permissions)))
    <div class="row gutters-10 mt-5">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0 fs-14">{{ translate('Category wise product sale') }}</h6>
                </div>
                <div class="card-body">
                    <canvas id="graph-1" class="w-100 cls_canvas" height="500"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0 fs-14">{{ translate('Category wise product stock') }}</h6>
                </div>
                <div class="card-body">
                    <canvas id="graph-2" class="w-100 cls_canvas" height="500"></canvas>
                </div>
            </div>
        </div>
    </div>
@endif

<div class="card mt-5">
    <div class="card-header">
        <h6 class="mb-0">{{ translate('Top 12 Products') }}</h6>
    </div>
    <div class="card-body">
        <div class="aiz-carousel cls_dash_slider new_slider gutters-10 half-outside-arrow" data-items="5" data-xl-items="4" data-lg-items="4" data-md-items="3" data-sm-items="2" data-arrows='true'>
            @foreach (filter_products(\App\Product::where('published', 1)->orderBy('num_of_sale', 'desc'))->limit(12)->get() as $key => $product)
                <div class="carousel-box">
                    <div class="aiz-card-box border border-light rounded shadow-sm hov-shadow-md mb-2 has-transition bg-white">
                        <div class="position-relative">
                            <a href="{{ route('product', $product->slug) }}" class="d-block">
                                <img
                                    class="img-fit lazyload mx-auto h-210px"
                                    src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                    data-src="{{ uploaded_asset($product->thumbnail_img) }}"
                                    alt="{{  $product->getTranslation('name')  }}"
                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"
                                >
                            </a>
                        </div>
                        <div class="py-md-3 py-2 text-left">
                            <div class="fs-15">
                                @if(home_base_price($product) != home_discounted_base_price($product))
                                    <del class="fw-600 opacity-50 mr-1">{{ home_base_price($product) }}</del>
                                @endif
                                <span class="fw-700 text-primary">{{ home_discounted_base_price($product) }}</span>
                            </div>
                            <div class="rating rating-sm my-2">
                                {{ renderStarRating($product->rating) }}
                            </div>
                            <h3 class="fw-600 fs-13 text-truncate-2 lh-1-4 mb-0">
                                <a href="{{ route('product', $product->slug) }}" class="d-block text-reset">{{ $product->getTranslation('name') }}</a>
                            </h3>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
--}}

@endsection
@section('script')
<script type="text/javascript">
    var vendor_system_activation = "{{ (!isSingleStoreActivated() ? true : false) }}"

    AIZ.plugins.chart('#pie-1',{
        type: 'doughnut',
        data: {
            labels: [
                '{{translate('Total published products')}}',
                /*!vendor_system_activation && */'{{translate('Total un-published products')}}',
                vendor_system_activation && '{{translate('Total Merchants products')}}',
                vendor_system_activation && '{{translate('Total admin products')}}'
            ].filter(word => word != ''),
            datasets: [
                {
                    data: [
                        {{ \App\Product::where('published', 1)->get()->count() }},
                        /*!vendor_system_activation && */{{ \App\Product::where('published', '!=', 1)->get()->count() }},
                        vendor_system_activation && {{ \App\Product::where('published', 1)->where('added_by', 'seller')->get()->count() }},
                        vendor_system_activation && {{ \App\Product::where('published', 1)->where('added_by', 'admin')->get()->count() }}
                    ].filter(word => word != '' || typeof word == 'number'),
                    backgroundColor: [
                        "#EA7713",
                        "#EA9E24",
                        "#FAC519",
                        '#fdcb6e',
                        '#d35400',
                        '#8e44ad',
                        '#006442',
                        '#4D8FAC',
                        '#CA6924',
                        '#C91F37'
                    ]
                }
            ]
        },
        options: {
            cutoutPercentage: 80,
            legend: {
                labels: {
                    fontFamily: 'Poppins',
                    boxWidth: 10,
                    usePointStyle: true,
                },
                onClick: function () {
                    return '';
                },
                position: 'bottom',
            }
        }
    });

    AIZ.plugins.chart('#pie-2',{
        type: 'doughnut',
        data: {
            labels: [
                '{{translate('Total Merchants')}}',
                '{{translate('Total approved Merchants')}}',
                '{{translate('Total pending Merchants')}}'
            ],
            datasets: [
                {
                    data: [
                        {{ \App\Seller::all()->count() }},
                        {{ \App\Seller::where('verification_status', 1)->get()->count() }},
                        {{ \App\Seller::where('verification_status', 0)->count() }}
                    ],
                    backgroundColor: [
                        "#EA7713",
                        "#EA9E24",
                        "#FAC519",
                        '#fdcb6e',
                        '#d35400',
                        '#8e44ad',
                        '#006442',
                        '#4D8FAC',
                        '#CA6924',
                        '#C91F37'
                    ]
                }
            ]
        },
        options: {
            cutoutPercentage: 80,
            legend: {
                labels: {
                    fontFamily: 'Montserrat',
                    boxWidth: 10,
                    usePointStyle: true
                },
                onClick: function () {
                    return '';
                },
                position: 'bottom'
            }
        }
    });
    var sfs = {
            labels: [
                @foreach (\App\Category::where('level', 0)->get() as $key => $category)
                '{{ $category->getTranslation('name') }}',
                @endforeach
            ],
            datasets: [
                @foreach (\App\Category::where('level', 0)->get() as $key => $category)
                {{ \App\Product::where('category_id', $category->id)->sum('num_of_sale') }},
                @endforeach
            ]
    }
    AIZ.plugins.chart('#graph-1',{
        type: 'bar',
        data: {
            labels: [
                @foreach (\App\Category::where('level', 0)->get() as $key => $category)
                '{{ $category->getTranslation('name') }}',
                @endforeach
            ],
            datasets: [{
                label: '{{ translate('Number of sale') }}',
                data: [
                    @foreach (\App\Category::where('level', 0)->get() as $key => $category)
                        @php
                            $category_ids = \App\Utility\CategoryUtility::children_ids($category->id);
                            $category_ids[] = $category->id;
                        @endphp
                    {{ \App\Product::whereIn('category_id', $category_ids)->sum('num_of_sale') }},
                    @endforeach
                ],
                backgroundColor: [
                    @foreach (\App\Category::where('level', 0)->get() as $key => $category)
                        'rgba(255, 207, 141, 1)',
                    @endforeach
                ],
                borderColor: [
                    @foreach (\App\Category::where('level', 0)->get() as $key => $category)
                        'rgba(255, 201, 141, 1)',
                    @endforeach
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    gridLines: {
                        color: '#f2f3f8',
                        zeroLineColor: '#f2f3f8'
                    },
                    ticks: {
                        fontColor: "#8b8b8b",
                        fontFamily: 'Poppins',
                        fontSize: 10,
                        beginAtZero: true
                    }
                }],
                xAxes: [{
                    gridLines: {
                        color: '#f2f3f8'
                    },
                    ticks: {
                        fontColor: "#8b8b8b",
                        fontFamily: 'Poppins',
                        fontSize: 10
                    }
                }]
            },
            legend:{
                labels: {
                    fontFamily: 'Poppins',
                    boxWidth: 10,
                    usePointStyle: true
                },
                onClick: function () {
                    return '';
                },
            }
        }
    });
    AIZ.plugins.chart('#graph-2',{
        type: 'bar',
        data: {
            labels: [
                @foreach (\App\Category::where('level', 0)->get() as $key => $category)
                '{{ $category->getTranslation('name') }}',
                @endforeach
            ],
            datasets: [{
                label: '{{ translate('Number of Stock') }}',
                data: [
                    @foreach (\App\Category::where('level', 0)->get() as $key => $category)
                        @php
                            $category_ids = \App\Utility\CategoryUtility::children_ids($category->id);
                            $category_ids[] = $category->id;

                            $products = \App\Product::whereIn('category_id', $category_ids)->get();
                            $qty = 0;
                            foreach ($products as $key => $product) {

                                foreach ($product->stocks as $key => $stock) {
                                    $qty += $stock->qty;
                                }


                            }
                        @endphp
                        {{ $qty }},
                    @endforeach
                ],
                backgroundColor: [
                    @foreach (\App\Category::where('level', 0)->get() as $key => $category)
                         'rgba(234, 119, 33, 1)',
                    @endforeach
                ],
                borderColor: [
                    @foreach (\App\Category::where('level', 0)->get() as $key => $category)
                        'rgba(234, 119, 33, 1)',
                    @endforeach
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    gridLines: {
                        color: '#f2f3f8',
                        zeroLineColor: '#f2f3f8'
                    },
                    ticks: {
                        fontColor: "#8b8b8b",
                        fontFamily: 'Poppins',
                        fontSize: 10,
                        beginAtZero: true
                    }
                }],
                xAxes: [{
                    gridLines: {
                        color: '#f2f3f8'
                    },
                    ticks: {
                        fontColor: "#8b8b8b",
                        fontFamily: 'Poppins',
                        fontSize: 10
                    }
                }]
            },
            legend:{
                labels: {
                    fontFamily: 'Poppins',
                    boxWidth: 10,
                    usePointStyle: true
                },
                onClick: function () {
                    return '';
                },
            }
        }
    });
</script>
@endsection
