<div class="aiz-sidebar-wrap">
    <div class="aiz-sidebar left c-scrollbar">
        <div class="aiz-side-nav-logo-wrap">
            <a href="{{ route('admin.dashboard') }}" class="d-block text-left">
                @if(get_setting('system_logo_white') != null)
                    <img class="mw-100" src="{{ uploaded_asset(get_setting('system_logo_white')) }}" class="brand-icon" alt="{{ get_setting('site_name') }}">
                @else
                    <img class="mw-100" src="{{ static_asset('assets/img/logo.png') }}" class="brand-icon" alt="{{ get_setting('site_name') }}">
                @endif
            </a>
        </div>
        <div class="aiz-side-nav-wrap">
          <!--   <div class="px-20px mb-3">
                <input class="form-control bg-soft-secondary border-0 form-control-sm text-white" type="text" name="" placeholder="{{ translate('Search in menu') }}" id="menu-search" onkeyup="menuSearch()">
            </div> -->
            <ul class="aiz-side-nav-list" id="search-menu">
            </ul>
            <ul class="aiz-side-nav-list cls_sidemenu" id="main-menu" data-toggle="aiz-side-menu">
                <li class="aiz-side-nav-item">
                    <a href="{{route('admin.dashboard')}}" class="aiz-side-nav-link">

                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18" height="18" viewBox="0 0 50 50"> <defs> <clipPath id="clip-Dashboard"> <rect width="50" height="50"/> </clipPath> </defs> <g id="Dashboard" clip-path="url(#clip-Dashboard)"> <g id="dashboard_customize-white-18dp" transform="translate(-3.07 -3.07)"> <g id="Group_2648" data-name="Group 2648" transform="translate(4.07 4.07)"> <g id="Group_2647" data-name="Group 2647"> <path id="Path_4872" data-name="Path 4872" d="M2,23.333H23.333V2H2Zm5.333-16H18V18H7.333Z" transform="translate(-2 -2)" fill="#fff"/> <path id="Path_4873" data-name="Path 4873" d="M8.667,2V23.333H30V2Zm16,16H14V7.333H24.667Z" transform="translate(18 -2)" fill="#fff"/> <path id="Path_4874" data-name="Path 4874" d="M2,30H23.333V8.667H2ZM7.333,14H18V24.667H7.333Z" transform="translate(-2 18)" fill="#fff"/> <path id="Path_4875" data-name="Path 4875" d="M22,8.667H16.667v8h-8V22h8v8H22V22h8V16.667H22Z" transform="translate(18 18)" fill="#fff"/> </g> </g> </g> </g></svg>
                        <span class="aiz-side-nav-text">{{translate('Dashboard')}}</span>
                    </a>
                </li>
               
                @if(Auth::user()->user_type == 'admin' || in_array('20', json_decode(Auth::user()->staff->role->permissions)))
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18" height="18" viewBox="0 0 50 50"> <defs> <clipPath id="clip-Sellers"> <rect width="50" height="50"/> </clipPath> </defs> <g id="Sellers" clip-path="url(#clip-Sellers)"> <path id="Path_8806" data-name="Path 8806" d="M22.211,22.877A10.105,10.105,0,1,0,12.105,12.772,10.1,10.1,0,0,0,22.211,22.877Zm0-15.158a5.053,5.053,0,1,1-5.053,5.053A5.067,5.067,0,0,1,22.211,7.719ZM7.053,38.035c.505-1.592,6.493-4.244,12.531-4.9l5.154-5.053a23.6,23.6,0,0,0-2.526-.152C15.465,27.93,2,31.315,2,38.035v5.053H24.737l-5.053-5.053ZM46.463,24.14,33.5,37.2l-5.229-5.255-3.537,3.562L33.5,44.351,50,27.7Z" transform="translate(-1 1.333)" fill="#fff"/> </g></svg>
                            <span class="aiz-side-nav-text">{{translate('Manage Admin')}}</span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('staffs.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['staffs.index', 'staffs.create', 'staffs.edit'])}}">
                                    <span class="aiz-side-nav-text">{{translate('Admin Users')}}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{route('roles.index')}}" class="aiz-side-nav-link {{ areActiveRoutes(['roles.index', 'roles.create', 'roles.edit'])}}">
                                    <span class="aiz-side-nav-text">{{translate('Roles & Permissions')}}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

                <!-- POS Addon-->
                @if (\App\Addon::where('unique_identifier', 'pos_system')->first() != null && \App\Addon::where('unique_identifier', 'pos_system')->first()->activated)
                    @if(Auth::user()->user_type == 'admin' || in_array('1', json_decode(Auth::user()->staff->role->permissions)))
                      <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <i class="las la-tasks aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text">{{translate('POS System')}}</span>
                            @if (env("DEMO_MODE") == "On")
                            <span class="badge badge-inline badge-danger">Addon</span>
                            @endif
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            <li class="aiz-side-nav-item">
                                <a href="{{route('poin-of-sales.index')}}" class="aiz-side-nav-link {{ areActiveRoutes(['poin-of-sales.index', 'poin-of-sales.create'])}}">
                                    <span class="aiz-side-nav-text">{{translate('POS Manager')}}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{route('poin-of-sales.activation')}}" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text">{{translate('POS Configuration')}}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endif
                @endif

                <!-- Product -->
                @if(Auth::user()->user_type == 'admin' || in_array('2', json_decode(Auth::user()->staff->role->permissions)))
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18" height="18" viewBox="0 0 50 50"> <defs> <clipPath id="clip-Products"> <rect width="50" height="50"/> </clipPath> </defs> <g id="Products" clip-path="url(#clip-Products)"> <path id="Path_8805" data-name="Path 8805" d="M38.571,12.1H34a11.429,11.429,0,0,0-22.857,0H6.571A4.585,4.585,0,0,0,2,16.667V44.1a4.585,4.585,0,0,0,4.571,4.571h32A4.585,4.585,0,0,0,43.143,44.1V16.667A4.585,4.585,0,0,0,38.571,12.1Zm-16-6.857A6.848,6.848,0,0,1,29.429,12.1H15.714A6.848,6.848,0,0,1,22.571,5.238Zm16,38.857h-32V16.667h32Zm-16-18.286a6.848,6.848,0,0,1-6.857-6.857H11.143a11.429,11.429,0,0,0,22.857,0H29.429A6.848,6.848,0,0,1,22.571,25.81Z" transform="translate(2 0.333)" fill="#fff"/> </g></svg>
                            <span class="aiz-side-nav-text">{{translate('Manage Products')}}</span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <!--Submenu-->
                        <ul class="aiz-side-nav-list level-2">
                            <li class="aiz-side-nav-item">
                                <a class="aiz-side-nav-link" href="{{route('products.create')}}">
                                    <span class="aiz-side-nav-text">{{translate('Add New product')}}</span>
                                </a>
                            </li>

                            @if(!isSingleStoreActivated())
                            <li class="aiz-side-nav-item">
                                <a href="{{route('products.all')}}" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text">{{ translate('All Products') }}</span>
                                </a>
                            </li>
                            @endif

                            <li class="aiz-side-nav-item">
                                <a href="{{route('products.admin')}}" class="aiz-side-nav-link {{ areActiveRoutes(['products.admin', 'products.admin.edit']) }}" >
                                    <span class="aiz-side-nav-text">{{ translate('In House Products') }}</span>
                                </a>
                            </li>

                            @if(!isSingleStoreActivated())
                            <li class="aiz-side-nav-item">
                                <a href="{{route('products.seller')}}" class="aiz-side-nav-link {{ areActiveRoutes(['products.seller', 'products.seller.edit']) }}">
                                    <span class="aiz-side-nav-text">{{ translate('Merchant Products') }}</span>
                                </a>
                            </li>
                            @endif
                            {{--
                            <li class="aiz-side-nav-item">
                                <a href="{{route('digitalproducts.index')}}" class="aiz-side-nav-link {{ areActiveRoutes(['digitalproducts.index', 'digitalproducts.create', 'digitalproducts.edit']) }}">
                                    <span class="aiz-side-nav-text">{{ translate('Digital Products') }}</span>
                                </a>
                            </li>
                            --}}
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('product_bulk_upload.index') }}" class="aiz-side-nav-link" >
                                    <span class="aiz-side-nav-text">{{ translate('Bulk Import') }}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{route('product_bulk_export.index')}}" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text">{{translate('Bulk Export')}}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{route('reviews.index')}}" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text">{{translate('Product Reviews')}}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="aiz-side-nav-item">
                        <a href="{{route('categories.index')}}" class="aiz-side-nav-link">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18" height="18" viewBox="0 0 50 50">
                              <defs>
                                <clipPath id="clip-Blog_System">
                                  <rect width="50" height="50"/>
                                </clipPath>
                              </defs>
                              <g id="Blog_System" data-name="Blog System" clip-path="url(#clip-Blog_System)">
                                <g id="blog" transform="translate(-3.5 -3.5)">
                                  <path id="Path_8761" data-name="Path 8761" d="M4.5,27h20v4H4.5Z" transform="translate(0 17.5)" fill="#fff" stroke="#fff" stroke-width="0.5"/>
                                  <path id="Path_8762" data-name="Path 8762" d="M4.5,20.25h20v4H4.5Z" transform="translate(0 12.25)" fill="#fff" stroke="#fff" stroke-width="0.5"/>
                                  <path id="Path_8763" data-name="Path 8763" d="M48.5,24.5H8.5a4,4,0,0,1-4-4V8.5a4,4,0,0,1,4-4h40a4,4,0,0,1,4,4v12A4,4,0,0,1,48.5,24.5Zm-40-16v12h40V8.5Z" transform="translate(0 0)" fill="#fff" stroke="#fff" stroke-width="0.5"/>
                                  <path id="Path_8764" data-name="Path 8764" d="M36.25,40.25h-12a4,4,0,0,1-4-4v-12a4,4,0,0,1,4-4h12a4,4,0,0,1,4,4v12A4,4,0,0,1,36.25,40.25Zm-12-16v12h12v-12Z" transform="translate(12.25 12.25)" fill="#fff" stroke="#fff" stroke-width="0.5"/>
                                </g>
                              </g>
                            </svg>
                            <span class="aiz-side-nav-text">{{translate('Manage Category')}}</span>
                        </a>
                    </li>
                    <li class="aiz-side-nav-item">
                        <a href="{{route('brands.index')}}" class="aiz-side-nav-link">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18" height="18" viewBox="0 0 50 50"> <defs> <clipPath id="clip-Marketing"> <rect width="50" height="50"/> </clipPath> </defs> <g id="Marketing" clip-path="url(#clip-Marketing)"> <path id="Path_8807" data-name="Path 8807" d="M2496.291,805.987a7.748,7.748,0,0,0-4.128-4.271v-8.7a3.071,3.071,0,0,0-.981-2.152,3,3,0,0,0-2.218-.824,3.1,3.1,0,0,0-2.022.848,1.912,1.912,0,0,0-.159.173c-2.132,2.643-8.886,9.508-14.466,9.508h-16.933a3.263,3.263,0,0,0-3.259,3.259v.626a5.8,5.8,0,0,0,0,7.8v.623a3.262,3.262,0,0,0,3.259,3.259h4.376v14.093a2.507,2.507,0,0,0,2.5,2.5h6.748a3.262,3.262,0,0,0,3.257-3.26,3.228,3.228,0,0,0-.147-.964c-.224-.717-.525-1.459-.854-2.255a20.123,20.123,0,0,1-1.637-5.617,3.263,3.263,0,0,0,2.919-3.241v-1.244c5.537.187,12.13,6.914,14.231,9.533a1.581,1.581,0,0,0,.225.234,3.132,3.132,0,0,0,4.41-.332,3.193,3.193,0,0,0,.749-1.915v-8.7a7.84,7.84,0,0,0,3.809-3.523A6.758,6.758,0,0,0,2496.291,805.987Zm-34.3,6.654v-8.572h10.082v8.572h-2.676c-.034,0-.066-.009-.1-.009h-1.51c-.018,0-.033.009-.051.009Zm-6.365-.972a1.785,1.785,0,0,0,0-.616v-6.984h2.867v8.572h-2.867Zm12.16,5.49a1.749,1.749,0,0,0-1.75,1.749,21.256,21.256,0,0,0,2,8.681c.245.6.478,1.156.654,1.65h-5.429v-13.1h5.794v1.02Zm7.785-4.021v-9.564c5.8-1.646,11.092-7.072,13.1-9.333l.007,28.276C2486.667,820.246,2481.367,814.79,2475.566,813.137Zm17.286-3.267a3.957,3.957,0,0,1-.69.927v-4.87a3.823,3.823,0,0,1,.849,1.279A3.259,3.259,0,0,1,2492.852,809.87Z" transform="matrix(0.966, -0.259, 0.259, 0.966, -2573.447, -118.012)" fill="#fff"/> </g></svg>
                            <span class="aiz-side-nav-text">{{translate('Manage Brand')}}</span>
                        </a>
                    </li>
                    <li class="aiz-side-nav-item">
                        <a href="{{route('attributes.index')}}" class="aiz-side-nav-link">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18" height="18" viewBox="0 0 50 50">
                              <defs>
                                <clipPath id="clip-Copy">
                                  <rect width="50" height="50"/>
                                </clipPath>
                              </defs>
                              <g id="Copy" clip-path="url(#clip-Copy)">
                                <path id="Icon_material-content-copy" data-name="Icon material-content-copy" d="M33.545,1.5H7.364A4.376,4.376,0,0,0,3,5.864V36.409H7.364V5.864H33.545Zm6.545,8.727h-24a4.376,4.376,0,0,0-4.364,4.364V45.136A4.376,4.376,0,0,0,16.091,49.5h24a4.376,4.376,0,0,0,4.364-4.364V14.591A4.376,4.376,0,0,0,40.091,10.227Zm0,34.909h-24V14.591h24Z" transform="translate(1 -0.5)" fill="#fff"/>
                              </g>
                            </svg>
                            <span class="aiz-side-nav-text">{{translate('Manage Attribute')}}</span>
                        </a>
                    </li>

                    <li class="aiz-side-nav-item">
                        <a href="{{route('colors')}}" class="aiz-side-nav-link">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18" height="18" viewBox="0 0 50 50"> <defs> <clipPath id="clip-Products"> <rect width="50" height="50"/> </clipPath> </defs> <g id="Products" clip-path="url(#clip-Products)"> <path id="Path_8805" data-name="Path 8805" d="M38.571,12.1H34a11.429,11.429,0,0,0-22.857,0H6.571A4.585,4.585,0,0,0,2,16.667V44.1a4.585,4.585,0,0,0,4.571,4.571h32A4.585,4.585,0,0,0,43.143,44.1V16.667A4.585,4.585,0,0,0,38.571,12.1Zm-16-6.857A6.848,6.848,0,0,1,29.429,12.1H15.714A6.848,6.848,0,0,1,22.571,5.238Zm16,38.857h-32V16.667h32Zm-16-18.286a6.848,6.848,0,0,1-6.857-6.857H11.143a11.429,11.429,0,0,0,22.857,0H29.429A6.848,6.848,0,0,1,22.571,25.81Z" transform="translate(2 0.333)" fill="#fff"/> </g></svg>
                            <span class="aiz-side-nav-text">{{translate('Manage Colors')}}</span>
                        </a>
                    </li>
                    
                @endif
       
                @if((Auth::user()->user_type == 'admin' || in_array('25', json_decode(Auth::user()->staff->role->permissions)))&& get_setting('subscription')=='1')
                 <li class="aiz-side-nav-item">
                    <a href="{{route('subscription.index')}}" class="aiz-side-nav-link">

                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18" height="18" viewBox="0 0 50 50"> <defs> <clipPath id="clip-Dashboard"> <rect width="50" height="50"/> </clipPath> </defs> <g id="Dashboard" clip-path="url(#clip-Dashboard)"> <g id="dashboard_customize-white-18dp" transform="translate(-3.07 -3.07)"> <g id="Group_2648" data-name="Group 2648" transform="translate(4.07 4.07)"> <g id="Group_2647" data-name="Group 2647"> <path id="Path_4872" data-name="Path 4872" d="M2,23.333H23.333V2H2Zm5.333-16H18V18H7.333Z" transform="translate(-2 -2)" fill="#fff"/> <path id="Path_4873" data-name="Path 4873" d="M8.667,2V23.333H30V2Zm16,16H14V7.333H24.667Z" transform="translate(18 -2)" fill="#fff"/> <path id="Path_4874" data-name="Path 4874" d="M2,30H23.333V8.667H2ZM7.333,14H18V24.667H7.333Z" transform="translate(-2 18)" fill="#fff"/> <path id="Path_4875" data-name="Path 4875" d="M22,8.667H16.667v8h-8V22h8v8H22V22h8V16.667H22Z" transform="translate(18 18)" fill="#fff"/> </g> </g> </g> </g></svg>
                        <span class="aiz-side-nav-text">{{translate('Manage Subscriptions')}}</span>
                    </a>
                </li>
                @endif
                <!-- Sale -->
                @if(Auth::user()->user_type == 'admin' || in_array('3', json_decode(Auth::user()->staff->role->permissions)) || in_array('4', json_decode(Auth::user()->staff->role->permissions)) || in_array('5', json_decode(Auth::user()->staff->role->permissions)) || in_array('6', json_decode(Auth::user()->staff->role->permissions)))
                <li class="aiz-side-nav-item">
                    <a href="{{ isSingleStoreActivated() ? route('inhouse_orders.index') : '#' }}" class="aiz-side-nav-link">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18" height="18" viewBox="0 0 50 50"> <defs> <clipPath id="clip-Sales"> <rect width="50" height="50"/> </clipPath> </defs> <g id="Sales" clip-path="url(#clip-Sales)"> <g id="Group_8469" data-name="Group 8469" transform="translate(-2536.319 -738.77)"> <g id="Group_8438" data-name="Group 8438" transform="translate(2537.319 745.77)"> <path id="Path_8751" data-name="Path 8751" d="M2582.379,745.77h-42.112a2.95,2.95,0,0,0-2.948,2.95v30.427a2.946,2.946,0,0,0,2.939,2.955h42.114a2.947,2.947,0,0,0,2.947-2.947V748.723A2.948,2.948,0,0,0,2582.379,745.77Zm-1.825,31.487v0h-38.469V750.615h38.469Z" transform="translate(-2537.319 -745.77)" fill="#fff"/> </g> <path id="Path_8750" data-name="Path 8750" d="M2553.122,768.1a1.875,1.875,0,0,1-1.326-.55l-2.619-2.618-3.186,2.638a1.877,1.877,0,0,1-2.47-2.827l.077-.065,4.5-3.727a1.876,1.876,0,0,1,2.525.12l2.177,2.178,4.8-7.478a1.876,1.876,0,0,1,3.267.213l2.839,5.991,5.472-9.618a1.879,1.879,0,0,1,3.267,1.857h0l-7.254,12.756a1.892,1.892,0,0,1-1.7.947,1.871,1.871,0,0,1-1.633-1.075l-2.927-6.2-4.231,6.591a1.875,1.875,0,0,1-1.579.863Z" transform="translate(3.706 3.761)" fill="#fff"/> </g> </g></svg>
                        <span class="aiz-side-nav-text">{{translate('Manage Orders')}}</span>

                        @if(!isSingleStoreActivated())
                        <span class="aiz-side-nav-arrow"></span>
                        @endif

                    </a>

                    @if( !isSingleStoreActivated() )
                    <!--Submenu-->
                    <ul class="aiz-side-nav-list level-2">
                        @if((Auth::user()->user_type == 'admin' || in_array('3', json_decode(Auth::user()->staff->role->permissions))) && !isSingleStoreActivated())
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('all_orders.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['all_orders.index', 'all_orders.show'])}}">
                                    <span class="aiz-side-nav-text">{{translate('All Orders')}}</span>
                                </a>
                            </li>
                        @endif

                        @if(Auth::user()->user_type == 'admin' || in_array('4', json_decode(Auth::user()->staff->role->permissions)))
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('inhouse_orders.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['inhouse_orders.index', 'inhouse_orders.show'])}}" >
                                    <span class="aiz-side-nav-text">{{translate('Inhouse orders')}}</span>
                                </a>
                            </li>
                        @endif
                        @if((Auth::user()->user_type == 'admin' || in_array('5', json_decode(Auth::user()->staff->role->permissions))) && !isSingleStoreActivated())
                          <li class="aiz-side-nav-item">
                            <a href="{{ route('seller_orders.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['seller_orders.index', 'seller_orders.show'])}}">
                                <span class="aiz-side-nav-text">{{translate('Seller Orders')}}</span>
                            </a>
                        </li>
                        @endif
                        
                        @if(get_setting('pickup_point') == 1)
                            @if(Auth::user()->user_type == 'admin' || in_array('6', json_decode(Auth::user()->staff->role->permissions)))
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('pick_up_point.order_index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['pick_up_point.order_index','pick_up_point.order_show'])}}">
                                        <span class="aiz-side-nav-text">{{translate('Pick-up Point Order')}}</span>
                                    </a>
                                </li>
                            @endif
                        @endif
                        
                    </ul>
                    @endif

                </li>
                @endif
                <!-- Deliver Boy Addon-->
                @if (\App\Addon::where('unique_identifier', 'delivery_boy')->first() != null && \App\Addon::where('unique_identifier', 'delivery_boy')->first()->activated)
                    @if(Auth::user()->user_type == 'admin' || in_array('1', json_decode(Auth::user()->staff->role->permissions)))
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <i class="las la-truck aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text">{{translate('Delivery Boy')}}</span>
                            @if (env("DEMO_MODE") == "On")
                            <span class="badge badge-inline badge-danger">Addon</span>
                            @endif
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            <li class="aiz-side-nav-item">
                                <a href="{{route('delivery-boys.index')}}" class="aiz-side-nav-link {{ areActiveRoutes(['delivery-boys.index'])}}">
                                    <span class="aiz-side-nav-text">{{translate('All Delivery Boy')}}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{route('delivery-boys.create')}}" class="aiz-side-nav-link {{ areActiveRoutes(['delivery-boys.create'])}}">
                                    <span class="aiz-side-nav-text">{{translate('Add Delivery Boy')}}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{route('delivery-boy.cancel-request')}}" class="aiz-side-nav-link {{ areActiveRoutes(['delivery-boy.cancel-request'])}}">
                                    <span class="aiz-side-nav-text">{{translate('Cancel Request')}}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{route('delivery-boy-configuration')}}" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text">{{translate('Configuration')}}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endif
                @endif

                <!-- Refund addon -->
                @if (\App\Addon::where('unique_identifier', 'refund_request')->first() != null && \App\Addon::where('unique_identifier', 'refund_request')->first()->activated)
                    @if(Auth::user()->user_type == 'admin' || in_array('7', json_decode(Auth::user()->staff->role->permissions)))
                      <li class="aiz-side-nav-item">
                          <a href="#" class="aiz-side-nav-link">
                              <i class="las la-backward aiz-side-nav-icon"></i>
                              <span class="aiz-side-nav-text">{{ translate('Refunds') }}</span>
                              @if (env("DEMO_MODE") == "On")
                                <span class="badge badge-inline badge-danger">Addon</span>
                                @endif
                              <span class="aiz-side-nav-arrow"></span>
                          </a>
                          <ul class="aiz-side-nav-list level-2">
                              <li class="aiz-side-nav-item">
                                  <a href="{{route('refund_requests_all')}}" class="aiz-side-nav-link {{ areActiveRoutes(['refund_requests_all', 'reason_show'])}}">
                                      <span class="aiz-side-nav-text">{{translate('Refund Requests')}}</span>
                                  </a>
                              </li>
                              <li class="aiz-side-nav-item">
                                  <a href="{{route('paid_refund')}}" class="aiz-side-nav-link">
                                      <span class="aiz-side-nav-text">{{translate('Approved Refunds')}}</span>
                                  </a>
                              </li>
                              <li class="aiz-side-nav-item">
                                  <a href="{{route('rejected_refund')}}" class="aiz-side-nav-link">
                                      <span class="aiz-side-nav-text">{{translate('rejected Refunds')}}</span>
                                  </a>
                              </li>
                              <li class="aiz-side-nav-item">
                                  <a href="{{route('refund_time_config')}}" class="aiz-side-nav-link">
                                      <span class="aiz-side-nav-text">{{translate('Refund Configuration')}}</span>
                                  </a>
                              </li>
                          </ul>
                      </li>
                    @endif
                @endif


                <!-- Customers -->
                @if(Auth::user()->user_type == 'admin' || in_array('8', json_decode(Auth::user()->staff->role->permissions)))
                    <li class="aiz-side-nav-item">
                        <a href="{{ route('customers.index') }}" class="aiz-side-nav-link">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18" height="18" viewBox="0 0 50 50"> <defs> <clipPath id="clip-Customers"> <rect width="50" height="50"/> </clipPath> </defs> <g id="Customers" clip-path="url(#clip-Customers)"> <g id="Group_8470" data-name="Group 8470" transform="translate(-2504.429 -746.933)"> <path id="Path_8752" data-name="Path 8752" d="M2526.2,763.327h-.882v4.94h.882a7.068,7.068,0,0,1,7.061,7.061v8.826h4.942v-8.828A12.032,12.032,0,0,0,2526.2,763.327Z" transform="translate(15.221 11.013)" fill="#fff"/> <path id="Path_8753" data-name="Path 8753" d="M2526.963,763.327h-9.532a12.032,12.032,0,0,0-12,12v8.826h4.934v-8.826a7.068,7.068,0,0,1,7.061-7.061h9.531a7.068,7.068,0,0,1,7.061,7.061v8.826h4.94l.01-8.826A12.029,12.029,0,0,0,2526.963,763.327Z" transform="translate(0 11.013)" fill="#fff"/> <path id="Path_8754" data-name="Path 8754" d="M2535.511,760.936a12.018,12.018,0,0,0-12-12h-.882v4.944h.882a7.061,7.061,0,1,1,0,14.121h-.882v4.942h.882A12.019,12.019,0,0,0,2535.511,760.936Z" transform="translate(13.158)" fill="#fff"/> <path id="Path_8755" data-name="Path 8755" d="M2520.129,772.941a12,12,0,1,0-12-12A12.018,12.018,0,0,0,2520.129,772.941Zm-7.061-12a7.061,7.061,0,1,1,7.061,7.061A7.068,7.068,0,0,1,2513.068,760.938Z" transform="translate(2.064)" fill="#fff"/> </g> </g></svg>
                            <span class="aiz-side-nav-text">{{ translate('Manage Users') }}</span>
                            <!-- <span class="aiz-side-nav-arrow"></span> -->
                        </a>
                        {{--
                        <ul class="aiz-side-nav-list level-2">
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('customers.index') }}" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text">{{ translate('Users list') }}</span>
                                </a>
                            </li>
                            @if(get_setting('classified_product') == 1)
                            <li class="aiz-side-nav-item">
                                <a href="{{route('classified_products')}}" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text">{{translate('Classified Products')}}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('customer_packages.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['customer_packages.index', 'customer_packages.create', 'customer_packages.edit'])}}">
                                    <span class="aiz-side-nav-text">{{ translate('Classified Packages') }}</span>
                                </a>
                            </li>
                            @endif
                        </ul>
                        --}}
                    </li>
                    @endif
                      <li class="aiz-side-nav-item">
                        <a href="{{route('deleted_user_reports')}}" class="aiz-side-nav-link">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-x" viewBox="0 0 16 16">
                            <path d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H1s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C9.516 10.68 8.289 10 6 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
                            <path fill-rule="evenodd" d="M12.146 5.146a.5.5 0 0 1 .708 0L14 6.293l1.146-1.147a.5.5 0 0 1 .708.708L14.707 7l1.147 1.146a.5.5 0 0 1-.708.708L14 7.707l-1.146 1.147a.5.5 0 0 1-.708-.708L13.293 7l-1.147-1.146a.5.5 0 0 1 0-.708z"/>
                            </svg>
                            <span>{{ translate('Deleted Users Reports') }}</span>
                        </a>
                    </li> 

                <!-- Sellers -->
                @if((Auth::user()->user_type == 'admin' || in_array('9', json_decode(Auth::user()->staff->role->permissions))) && !isSingleStoreActivated())
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18" height="18" viewBox="0 0 50 50"> <defs> <clipPath id="clip-Sellers"> <rect width="50" height="50"/> </clipPath> </defs> <g id="Sellers" clip-path="url(#clip-Sellers)"> <path id="Path_8806" data-name="Path 8806" d="M22.211,22.877A10.105,10.105,0,1,0,12.105,12.772,10.1,10.1,0,0,0,22.211,22.877Zm0-15.158a5.053,5.053,0,1,1-5.053,5.053A5.067,5.067,0,0,1,22.211,7.719ZM7.053,38.035c.505-1.592,6.493-4.244,12.531-4.9l5.154-5.053a23.6,23.6,0,0,0-2.526-.152C15.465,27.93,2,31.315,2,38.035v5.053H24.737l-5.053-5.053ZM46.463,24.14,33.5,37.2l-5.229-5.255-3.537,3.562L33.5,44.351,50,27.7Z" transform="translate(-1 1.333)" fill="#fff"/> </g></svg>
                            <span class="aiz-side-nav-text">{{ translate('Manage Merchant') }}</span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            <li class="aiz-side-nav-item">
                                @php
                                    $sellers = \App\Seller::where('verification_status', 0)->where('verification_info', '!=', null)->count();
                                @endphp
                                <a href="{{ route('sellers.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['sellers.index', 'sellers.create', 'sellers.edit', 'sellers.payment_history','sellers.approved','sellers.profile_modal','sellers.show_verification_request'])}}">
                                    <span class="aiz-side-nav-text">{{ translate('All Merchant') }}</span>
                                    @if($sellers > 0)<span class="badge badge-info">{{ $sellers }}</span> @endif
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('sellers.payment_histories') }}" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text">{{ translate('Payouts') }}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('withdraw_requests_all') }}" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text">{{ translate('Payout Requests') }}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('business_settings.vendor_commission') }}" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text">{{ translate('Merchant Commission') }}</span>
                                </a>
                            </li>
                            @if (\App\Addon::where('unique_identifier', 'seller_subscription')->first() != null && \App\Addon::where('unique_identifier', 'seller_subscription')->first()->activated)
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('seller_packages.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['seller_packages.index', 'seller_packages.create', 'seller_packages.edit'])}}">
                                        <span class="aiz-side-nav-text">{{ translate('Seller Packages') }}</span>
                                      @if (env("DEMO_MODE") == "On")
                                        <span class="badge badge-inline badge-danger">Addon</span>
                                        @endif
                                    </a>
                                </li>
                            @endif
                            
                            
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('seller_verification_form.index') }}" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text">{{ translate('Merchant Verification Form') }}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('manage_owe_amount') }}" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text">Manage Owe Amount</span>
                                </a>
                            </li>                                                        
                        </ul>
                    </li>
                @endif

                
                @if(Auth::user()->user_type == 'admin' || in_array('22', json_decode(Auth::user()->staff->role->permissions)))
                {{--
                <li class="aiz-side-nav-item">
                    <a href="{{ route('uploaded-files.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['uploaded-files.create'])}}">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18" height="18" viewBox="0 0 50 50"> <defs> <clipPath id="clip-Uploaded_Files"> <rect width="50" height="50"/> </clipPath> </defs> <g id="Uploaded_Files" data-name="Uploaded Files" clip-path="url(#clip-Uploaded_Files)"> <g id="file-earmark-arrow-down" transform="translate(-0.5 -1.25)"> <path id="Path_8756" data-name="Path 8756" d="M11.357,2.25H28.5V5.679H11.357A3.429,3.429,0,0,0,7.929,9.107V43.393a3.429,3.429,0,0,0,3.429,3.429H38.786a3.429,3.429,0,0,0,3.429-3.429v-24h3.429v24a6.857,6.857,0,0,1-6.857,6.857H11.357A6.857,6.857,0,0,1,4.5,43.393V9.107A6.857,6.857,0,0,1,11.357,2.25Z" transform="translate(0 0)" fill="#fff"/> <path id="Path_8757" data-name="Path 8757" d="M20.25,14.25v-12L37.393,19.393h-12A5.143,5.143,0,0,1,20.25,14.25Z" transform="translate(8.25)" fill="#fff"/> <path id="Path_8758" data-name="Path 8758" d="M12.877,20.752a1.714,1.714,0,0,1,2.427,0L20.947,26.4l5.644-5.647a1.716,1.716,0,0,1,2.427,2.427l-6.857,6.857a1.714,1.714,0,0,1-2.427,0l-6.857-6.857a1.714,1.714,0,0,1,0-2.427Z" transform="translate(4.124 9.428)" fill="#fff" fill-rule="evenodd"/> <path id="Path_8759" data-name="Path 8759" d="M18.589,13.5A1.714,1.714,0,0,1,20.3,15.214V28.929a1.714,1.714,0,1,1-3.429,0V15.214A1.714,1.714,0,0,1,18.589,13.5Z" transform="translate(6.482 5.893)" fill="#fff" fill-rule="evenodd"/> </g> </g></svg>
                        <span class="aiz-side-nav-text">{{ translate('Uploaded Files') }}</span>
                    </a>
                </li>
                --}}
                @endif
                <!-- Reports -->
                @if(Auth::user()->user_type == 'admin' || in_array('10', json_decode(Auth::user()->staff->role->permissions)))
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18" height="18" viewBox="0 0 50 50"> <defs> <clipPath id="clip-Reports"> <rect width="50" height="50"/> </clipPath> </defs> <g id="Reports" clip-path="url(#clip-Reports)"> <g id="Group_8472" data-name="Group 8472" transform="translate(-2494.548 -787.118)"> <rect id="Rectangle_17821" data-name="Rectangle 17821" width="4.125" height="7.5" transform="translate(2517.423 818.492)" fill="#fff"/> <rect id="Rectangle_17822" data-name="Rectangle 17822" width="4.125" height="10.875" transform="translate(2525.86 815.117)" fill="#fff"/> <rect id="Rectangle_17823" data-name="Rectangle 17823" width="4.125" height="17.625" transform="translate(2508.985 808.368)" fill="#fff"/> <path id="Path_8760" data-name="Path 8760" d="M2534.672,793.18h-4.687v-1.312a3.753,3.753,0,0,0-3.75-3.75h-13.5a3.753,3.753,0,0,0-3.75,3.75v1.313H2504.3a3.753,3.753,0,0,0-3.75,3.75v35.438a3.753,3.753,0,0,0,3.75,3.75h30.375a3.754,3.754,0,0,0,3.75-3.75V796.93A3.753,3.753,0,0,0,2534.672,793.18Zm-22.261,9.188h14.146a3.431,3.431,0,0,0,3.428-3.427v-1.635h4.312v34.687h-29.625V797.305h4.313v1.635A3.43,3.43,0,0,0,2512.411,802.368Zm.7-4.125v-6h12.75v6Z" transform="translate(0)" fill="#fff"/> </g> </g></svg>
                            <span class="aiz-side-nav-text">{{ translate('Manage Reports') }}</span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('in_house_sale_report.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['in_house_sale_report.index'])}}">
                                    <span class="aiz-side-nav-text">{{ translate('In House Product Order') }}</span>
                                </a>
                            </li>

                            @if(!isSingleStoreActivated())
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('seller_sale_report.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['seller_sale_report.index'])}}">
                                    <span class="aiz-side-nav-text">{{ translate('Merchant Products Order') }}</span>
                                </a>
                            </li>
                            @endif

                            <li class="aiz-side-nav-item">
                                <a href="{{ route('stock_report.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['stock_report.index'])}}">
                                    <span class="aiz-side-nav-text">{{ translate('Products Stock') }}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('wish_report.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['wish_report.index'])}}">
                                    <span class="aiz-side-nav-text">{{ translate('Products wishlist') }}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('user_search_report.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['user_search_report.index'])}}">
                                    <span class="aiz-side-nav-text">{{ translate('User Searches') }}</span>
                                </a>
                            </li>
                            @if(!isSingleStoreActivated())
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('commission-log.index') }}" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text">{{ translate('Commission History') }}</span>
                                </a>
                            </li>
                            @endif
                            {{--
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('wallet-history.index') }}" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text">{{ translate('Wallet Recharge History') }}</span>
                                </a>
                            </li>
                            --}}
                        </ul>
                    </li>
                @endif
                @if(Auth::user()->user_type == 'admin' || in_array('23', json_decode(Auth::user()->staff->role->permissions)))
                <!--Blog System-->
                {{--
                <li class="aiz-side-nav-item">
                    <a href="#" class="aiz-side-nav-link">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18" height="18" viewBox="0 0 50 50"> <defs> <clipPath id="clip-Blog_System"> <rect width="50" height="50"/> </clipPath> </defs> <g id="Blog_System" data-name="Blog System" clip-path="url(#clip-Blog_System)"> <g id="blog" transform="translate(-3.5 -3.5)"> <path id="Path_8761" data-name="Path 8761" d="M4.5,27h20v4H4.5Z" transform="translate(0 17.5)" fill="#fff" stroke="#fff" stroke-width="0.5"/> <path id="Path_8762" data-name="Path 8762" d="M4.5,20.25h20v4H4.5Z" transform="translate(0 12.25)" fill="#fff" stroke="#fff" stroke-width="0.5"/> <path id="Path_8763" data-name="Path 8763" d="M48.5,24.5H8.5a4,4,0,0,1-4-4V8.5a4,4,0,0,1,4-4h40a4,4,0,0,1,4,4v12A4,4,0,0,1,48.5,24.5Zm-40-16v12h40V8.5Z" transform="translate(0 0)" fill="#fff" stroke="#fff" stroke-width="0.5"/> <path id="Path_8764" data-name="Path 8764" d="M36.25,40.25h-12a4,4,0,0,1-4-4v-12a4,4,0,0,1,4-4h12a4,4,0,0,1,4,4v12A4,4,0,0,1,36.25,40.25Zm-12-16v12h12v-12Z" transform="translate(12.25 12.25)" fill="#fff" stroke="#fff" stroke-width="0.5"/> </g> </g></svg>
                        <span class="aiz-side-nav-text">{{ translate('Blog System') }}</span>
                        <span class="aiz-side-nav-arrow"></span>
                    </a>
                    <ul class="aiz-side-nav-list level-2">
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('blog.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['blog.create', 'blog.edit'])}}">
                                <span class="aiz-side-nav-text">{{ translate('All Posts') }}</span>
                            </a>
                        </li>
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('blog-category.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['blog-category.create', 'blog-category.edit'])}}">
                                <span class="aiz-side-nav-text">{{ translate('Categories') }}</span>
                            </a>
                        </li>
                    </ul>
                </li>
                --}}
                @endif

                <!-- marketing -->
                @if(Auth::user()->user_type == 'admin' || in_array('11', json_decode(Auth::user()->staff->role->permissions)))
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18" height="18" viewBox="0 0 50 50"> <defs> <clipPath id="clip-Marketing"> <rect width="50" height="50"/> </clipPath> </defs> <g id="Marketing" clip-path="url(#clip-Marketing)"> <path id="Path_8807" data-name="Path 8807" d="M2496.291,805.987a7.748,7.748,0,0,0-4.128-4.271v-8.7a3.071,3.071,0,0,0-.981-2.152,3,3,0,0,0-2.218-.824,3.1,3.1,0,0,0-2.022.848,1.912,1.912,0,0,0-.159.173c-2.132,2.643-8.886,9.508-14.466,9.508h-16.933a3.263,3.263,0,0,0-3.259,3.259v.626a5.8,5.8,0,0,0,0,7.8v.623a3.262,3.262,0,0,0,3.259,3.259h4.376v14.093a2.507,2.507,0,0,0,2.5,2.5h6.748a3.262,3.262,0,0,0,3.257-3.26,3.228,3.228,0,0,0-.147-.964c-.224-.717-.525-1.459-.854-2.255a20.123,20.123,0,0,1-1.637-5.617,3.263,3.263,0,0,0,2.919-3.241v-1.244c5.537.187,12.13,6.914,14.231,9.533a1.581,1.581,0,0,0,.225.234,3.132,3.132,0,0,0,4.41-.332,3.193,3.193,0,0,0,.749-1.915v-8.7a7.84,7.84,0,0,0,3.809-3.523A6.758,6.758,0,0,0,2496.291,805.987Zm-34.3,6.654v-8.572h10.082v8.572h-2.676c-.034,0-.066-.009-.1-.009h-1.51c-.018,0-.033.009-.051.009Zm-6.365-.972a1.785,1.785,0,0,0,0-.616v-6.984h2.867v8.572h-2.867Zm12.16,5.49a1.749,1.749,0,0,0-1.75,1.749,21.256,21.256,0,0,0,2,8.681c.245.6.478,1.156.654,1.65h-5.429v-13.1h5.794v1.02Zm7.785-4.021v-9.564c5.8-1.646,11.092-7.072,13.1-9.333l.007,28.276C2486.667,820.246,2481.367,814.79,2475.566,813.137Zm17.286-3.267a3.957,3.957,0,0,1-.69.927v-4.87a3.823,3.823,0,0,1,.849,1.279A3.259,3.259,0,0,1,2492.852,809.87Z" transform="matrix(0.966, -0.259, 0.259, 0.966, -2573.447, -118.012)" fill="#fff"/> </g></svg>
                            <span class="aiz-side-nav-text">{{ translate('Manage Promote') }}</span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            @if(Auth::user()->user_type == 'admin' || in_array('2', json_decode(Auth::user()->staff->role->permissions)))
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('flash_deals.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['flash_deals.index', 'flash_deals.create', 'flash_deals.edit'])}}">
                                        <span class="aiz-side-nav-text">{{ translate('Flash deals') }}</span>
                                    </a>
                                </li>
                            @endif
                            @if(Auth::user()->user_type == 'admin' || in_array('7', json_decode(Auth::user()->staff->role->permissions)))
                                <li class="aiz-side-nav-item">
                                    <a href="{{route('newsletters.index')}}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text">{{ translate('Newsletters') }}</span>
                                    </a>
                                </li>
                                @if (\App\Addon::where('unique_identifier', 'otp_system')->first() != null && \App\Addon::where('unique_identifier', 'otp_system')->first()->activated)
                                    <li class="aiz-side-nav-item">
                                        <a href="{{route('sms.index')}}" class="aiz-side-nav-link">
                                            <span class="aiz-side-nav-text">{{ translate('Bulk SMS') }}</span>
                                            @if (env("DEMO_MODE") == "On")
                                            <span class="badge badge-inline badge-danger">Addon</span>
                                            @endif
                                        </a>
                                    </li>
                                @endif
                            @endif
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('subscribers.index') }}" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text">{{ translate('Subscribers') }}</span>
                                </a>
                            </li>
                            @if(Auth::check() && get_setting('coupon_system') == 1)
                            <li class="aiz-side-nav-item">
                                <a href="{{route('coupon.index')}}" class="aiz-side-nav-link {{ areActiveRoutes(['coupon.index','coupon.create','coupon.edit'])}}">
                                    <span class="aiz-side-nav-text">{{ translate('Coupon') }}</span>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </li>
                @endif

                <!-- Support -->
                @if(Auth::user()->user_type == 'admin' || in_array('12', json_decode(Auth::user()->staff->role->permissions)))
                @if(get_setting('conversation_system') == 1)
                  <li class="aiz-side-nav-item">
                      <a href="#" class="aiz-side-nav-link">
                          <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18" height="18" viewBox="0 0 50 50"> <defs> <clipPath id="clip-Support"> <rect width="50" height="50"/> </clipPath> </defs> <g id="Support" clip-path="url(#clip-Support)"> <g id="Group_8473" data-name="Group 8473" transform="translate(-2536.412 -789.454)"> <path id="Path_8766" data-name="Path 8766" d="M2554.516,790.454h2.532a2.38,2.38,0,0,0,.357.08,16.636,16.636,0,0,1,6.194,1.689,18.043,18.043,0,0,1,10.43,15.155.786.786,0,0,0,.633.8,15.283,15.283,0,0,1,10.207,10.617,29.71,29.71,0,0,1,.583,2.975v2.342a2.812,2.812,0,0,0-.082.361,15.7,15.7,0,0,1-1.875,6.037,1.1,1.1,0,0,0-.08.731c.591,2.2,1.207,4.39,1.814,6.584.048.167.082.338.135.557-2.367-.655-4.653-1.28-6.933-1.924a1.3,1.3,0,0,0-1.092.1,14.912,14.912,0,0,1-6.6,1.875,15.525,15.525,0,0,1-15.593-10.867.6.6,0,0,0-.633-.515,17.7,17.7,0,0,1-7.648-2.272,1,1,0,0,0-.833-.1c-2.041.574-4.087,1.136-6.13,1.7-.77.213-1.541.422-2.357.646.032-.142.043-.2.059-.26.758-2.735,1.521-5.469,2.26-8.208a1.138,1.138,0,0,0-.061-.779,17.638,17.638,0,0,1-1.843-13.132c1.851-7.279,6.52-11.859,13.8-13.73C2552.658,790.692,2553.6,790.608,2554.516,790.454Zm-12.952,32.536c.152-.036.237-.055.323-.078,1.5-.414,3.011-.817,4.508-1.252a1.062,1.062,0,0,1,.957.139,15.342,15.342,0,0,0,11.247,2.232,15.506,15.506,0,1,0-16.382-7.8,3.5,3.5,0,0,1,.344,3.2C2542.146,820.57,2541.9,821.765,2541.565,822.99Zm39.783,11.36c-.317-1.153-.589-2.238-.923-3.3a1.34,1.34,0,0,1,.192-1.252,12.672,12.672,0,0,0-2.243-16.348,12.181,12.181,0,0,0-4.3-2.515q-2.581,13.589-16.133,16.139c.034.1.07.235.119.363a12.323,12.323,0,0,0,3.418,4.964,12.629,12.629,0,0,0,15.4,1.166,1.179,1.179,0,0,1,1.083-.167C2579.059,833.73,2580.168,834.021,2581.348,834.35Z" fill="#fff" stroke="#fff" stroke-width="0.5"/> <path id="Path_8767" data-name="Path 8767" d="M2546.916,800.495h-2.793a5.488,5.488,0,0,1,2.552-4.667,5.615,5.615,0,0,1,6.927,8.784c-.7.669-1.415,1.322-2.142,1.96a.849.849,0,0,0-.314.729c.02.574.006,1.149.006,1.73h-2.812c0-1.1-.011-2.171.014-3.244,0-.15.167-.321.3-.443,1.016-.946,2.059-1.867,3.062-2.83a2.759,2.759,0,0,0,.593-3.151,2.805,2.805,0,0,0-5.333.739C2546.955,800.223,2546.937,800.345,2546.916,800.495Z" transform="translate(6.037 3.995)" fill="#fff" stroke="#fff" stroke-width="0.5"/> <path id="Path_8768" data-name="Path 8768" d="M2546.354,806.6v-2.755h2.752V806.6Z" transform="translate(8.044 12.045)" fill="#fff" stroke="#fff" stroke-width="0.5"/> </g> </g></svg>
                          <span class="aiz-side-nav-text">{{translate('Support')}}</span>
                          <span class="aiz-side-nav-arrow"></span>
                      </a>
                      <ul class="aiz-side-nav-list level-2">
                          @if(Auth::user()->user_type == 'admin' || in_array('12', json_decode(Auth::user()->staff->role->permissions)))
                              @php
                                  $support_ticket = DB::table('tickets')
                                              ->where('viewed', 0)
                                              ->select('id')
                                              ->count();
                              @endphp
                              <li class="aiz-side-nav-item">
                                  <a href="{{ route('support_ticket.admin_index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['support_ticket.admin_index', 'support_ticket.admin_show'])}}">
                                      <span class="aiz-side-nav-text">{{translate('Ticket')}}</span>
                                      @if($support_ticket > 0)<span class="badge badge-info">{{ $support_ticket }}</span>@endif
                                  </a>
                              </li>
                          @endif

                          @php
                              $conversation = \App\Conversation::where('receiver_id', Auth::user()->id)->where('receiver_viewed', '1')->get();
                          @endphp
                          @if(Auth::user()->user_type == 'admin' || in_array('12', json_decode(Auth::user()->staff->role->permissions)))
                              <li class="aiz-side-nav-item">
                                  <a href="{{ route('conversations.admin_index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['conversations.admin_index', 'conversations.admin_show'])}}">
                                      <span class="aiz-side-nav-text">{{translate('Product Queries')}}</span>
                                      @if (count($conversation) > 0)
                                          <span class="badge badge-info">{{ count($conversation) }}</span>
                                      @endif
                                  </a>
                              </li>
                          @endif
                      </ul>
                  </li>
                @endif
                @endif

                <!-- Affiliate Addon -->
                @if (\App\Addon::where('unique_identifier', 'affiliate_system')->first() != null && \App\Addon::where('unique_identifier', 'affiliate_system')->first()->activated)
                    @if(Auth::user()->user_type == 'admin' || in_array('15', json_decode(Auth::user()->staff->role->permissions)))
                      <li class="aiz-side-nav-item">
                          <a href="#" class="aiz-side-nav-link">
                              <i class="las la-link aiz-side-nav-icon"></i>
                              <span class="aiz-side-nav-text">{{translate('Affiliate System')}}</span>
                                @if (env("DEMO_MODE") == "On")
                                <span class="badge badge-inline badge-danger">Addon</span>
                                @endif
                              <span class="aiz-side-nav-arrow"></span>
                          </a>
                          <ul class="aiz-side-nav-list level-2">
                              <li class="aiz-side-nav-item">
                                  <a href="{{route('affiliate.configs')}}" class="aiz-side-nav-link">
                                      <span class="aiz-side-nav-text">{{translate('Affiliate Registration Form')}}</span>
                                  </a>
                              </li>
                              <li class="aiz-side-nav-item">
                                  <a href="{{route('affiliate.index')}}" class="aiz-side-nav-link">
                                      <span class="aiz-side-nav-text">{{translate('Affiliate Configurations')}}</span>
                                  </a>
                              </li>
                              <li class="aiz-side-nav-item">
                                  <a href="{{route('affiliate.users')}}" class="aiz-side-nav-link {{ areActiveRoutes(['affiliate.users', 'affiliate_users.show_verification_request', 'affiliate_user.payment_history'])}}">
                                      <span class="aiz-side-nav-text">{{translate('Affiliate Users')}}</span>
                                  </a>
                              </li>
                              <li class="aiz-side-nav-item">
                                  <a href="{{route('refferals.users')}}" class="aiz-side-nav-link">
                                      <span class="aiz-side-nav-text">{{translate('Referral Users')}}</span>
                                  </a>
                              </li>
                              <li class="aiz-side-nav-item">
                                  <a href="{{route('affiliate.withdraw_requests')}}" class="aiz-side-nav-link">
                                      <span class="aiz-side-nav-text">{{translate('Affiliate Withdraw Requests')}}</span>
                                  </a>
                              </li>
                              <li class="aiz-side-nav-item">
                                  <a href="{{route('affiliate.logs.admin')}}" class="aiz-side-nav-link">
                                      <span class="aiz-side-nav-text">{{translate('Affiliate Logs')}}</span>
                                  </a>
                              </li>
                          </ul>
                      </li>
                    @endif
                @endif

                <!-- Offline Payment Addon-->
                @if (\App\Addon::where('unique_identifier', 'offline_payment')->first() != null && \App\Addon::where('unique_identifier', 'offline_payment')->first()->activated)
                    @if(Auth::user()->user_type == 'admin' || in_array('16', json_decode(Auth::user()->staff->role->permissions)))
                      <li class="aiz-side-nav-item">
                          <a href="#" class="aiz-side-nav-link">
                              <i class="las la-money-check-alt aiz-side-nav-icon"></i>
                              <span class="aiz-side-nav-text">{{translate('Offline Payment System')}}</span>
                                @if (env("DEMO_MODE") == "On")
                                <span class="badge badge-inline badge-danger">Addon</span>
                                @endif
                              <span class="aiz-side-nav-arrow"></span>
                          </a>
                          <ul class="aiz-side-nav-list level-2">
                              <li class="aiz-side-nav-item">
                                  <a href="{{ route('manual_payment_methods.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['manual_payment_methods.index', 'manual_payment_methods.create', 'manual_payment_methods.edit'])}}">
                                      <span class="aiz-side-nav-text">{{translate('Manual Payment Methods')}}</span>
                                  </a>
                              </li>
                              <li class="aiz-side-nav-item">
                                  <a href="{{ route('offline_wallet_recharge_request.index') }}" class="aiz-side-nav-link">
                                      <span class="aiz-side-nav-text">{{translate('Offline Wallet Recharge')}}</span>
                                  </a>
                              </li>
                              @if(get_setting('classified_product') == 1)
                                  <li class="aiz-side-nav-item">
                                      <a href="{{ route('offline_customer_package_payment_request.index') }}" class="aiz-side-nav-link">
                                          <span class="aiz-side-nav-text">{{translate('Offline Customer Package Payments')}}</span>
                                      </a>
                                  </li>
                              @endif
                              @if (\App\Addon::where('unique_identifier', 'seller_subscription')->first() != null && \App\Addon::where('unique_identifier', 'seller_subscription')->first()->activated)
                                  <li class="aiz-side-nav-item">
                                      <a href="{{ route('offline_seller_package_payment_request.index') }}" class="aiz-side-nav-link">
                                          <span class="aiz-side-nav-text">{{translate('Offline Seller Package Payments')}}</span>
                                            @if (env("DEMO_MODE") == "On")
                                            <span class="badge badge-inline badge-danger">Addon</span>
                                            @endif
                                      </a>
                                  </li>
                              @endif
                          </ul>
                      </li>
                    @endif
                @endif

                <!-- Paytm Addon -->
                @if (\App\Addon::where('unique_identifier', 'paytm')->first() != null && \App\Addon::where('unique_identifier', 'paytm')->first()->activated)
                    @if(Auth::user()->user_type == 'admin' || in_array('17', json_decode(Auth::user()->staff->role->permissions)))
                      <li class="aiz-side-nav-item">
                          <a href="#" class="aiz-side-nav-link">
                              <i class="las la-mobile-alt aiz-side-nav-icon"></i>
                              <span class="aiz-side-nav-text">{{translate('Paytm Payment Gateway')}}</span>
                                @if (env("DEMO_MODE") == "On")
                                <span class="badge badge-inline badge-danger">Addon</span>
                                @endif
                              <span class="aiz-side-nav-arrow"></span>
                          </a>
                          <ul class="aiz-side-nav-list level-2">
                              <li class="aiz-side-nav-item">
                                  <a href="{{ route('paytm.index') }}" class="aiz-side-nav-link">
                                      <span class="aiz-side-nav-text">{{translate('Set Paytm Credentials')}}</span>
                                  </a>
                              </li>
                          </ul>
                      </li>
                    @endif
                @endif

                <!-- Club Point Addon-->
                @if (\App\Addon::where('unique_identifier', 'club_point')->first() != null && \App\Addon::where('unique_identifier', 'club_point')->first()->activated)
                  @if(Auth::user()->user_type == 'admin' || in_array('18', json_decode(Auth::user()->staff->role->permissions)))
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <i class="lab la-btc aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text">{{translate('Club Point System')}}</span>
                            @if (env("DEMO_MODE") == "On")
                            <span class="badge badge-inline badge-danger">Addon</span>
                            @endif
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('club_points.configs') }}" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text">{{translate('Club Point Configurations')}}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{route('set_product_points')}}" class="aiz-side-nav-link {{ areActiveRoutes(['set_product_points', 'product_club_point.edit'])}}">
                                    <span class="aiz-side-nav-text">{{translate('Set Product Point')}}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{route('club_points.index')}}" class="aiz-side-nav-link {{ areActiveRoutes(['club_points.index', 'club_point.details'])}}">
                                    <span class="aiz-side-nav-text">{{translate('User Points')}}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                  @endif
                @endif

                <!--OTP addon -->
                @if (\App\Addon::where('unique_identifier', 'otp_system')->first() != null && \App\Addon::where('unique_identifier', 'otp_system')->first()->activated)
                  @if(Auth::user()->user_type == 'admin' || in_array('19', json_decode(Auth::user()->staff->role->permissions)))
                      <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <i class="las la-phone aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text">{{translate('OTP System')}}</span>
                            @if (env("DEMO_MODE") == "On")
                            <span class="badge badge-inline badge-danger">Addon</span>
                            @endif
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('otp.configconfiguration') }}" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text">{{translate('OTP Configurations')}}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{route('sms-templates.index')}}" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text">{{translate('SMS Templates')}}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{route('otp_credentials.index')}}" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text">{{translate('Set OTP Credentials')}}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                  @endif
                @endif

                @if(\App\Addon::where('unique_identifier', 'african_pg')->first() != null && \App\Addon::where('unique_identifier', 'african_pg')->first()->activated)
                  @if(Auth::user()->user_type == 'admin' || in_array('19', json_decode(Auth::user()->staff->role->permissions)))
                      <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <i class="las la-phone aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text">{{translate('African Payment Gateway Addon')}}</span>
                            @if (env("DEMO_MODE") == "On")
                            <span class="badge badge-inline badge-danger">Addon</span>
                            @endif
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('african.configuration') }}" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text">{{translate('African PG Configurations')}}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{route('african_credentials.index')}}" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text">{{translate('Set African PG Credentials')}}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                  @endif
                @endif

                <!-- Website Setup -->
                @if(Auth::user()->user_type == 'admin' || in_array('13', json_decode(Auth::user()->staff->role->permissions)))
                  <li class="aiz-side-nav-item">
                    <a href="javascript:void(0);"  class="aiz-side-nav-link align-items-start">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18" height="18" viewBox="0 0 50 50"> <defs> <clipPath id="clip-Website_Setup"> <rect width="50" height="50"/> </clipPath> </defs> <g id="Website_Setup" data-name="Website Setup" clip-path="url(#clip-Website_Setup)"> <g id="Group_8474" data-name="Group 8474" transform="translate(1066.781 -961.991)"> <path id="Path_8808" data-name="Path 8808" d="M-1050.813,993.233h8.652a2.372,2.372,0,0,0,2.369-2.369v-17.3a2.372,2.372,0,0,0-2.369-2.369h-8.652a2.372,2.372,0,0,0-2.369,2.369v17.3A2.372,2.372,0,0,0-1050.813,993.233Zm2.371-4.736V975.929h3.912V988.5Z" transform="translate(13.355 4.452)" fill="#fff"/> <path id="Path_8809" data-name="Path 8809" d="M-1059.212,975.929h8.654a2.37,2.37,0,0,0,2.367-2.369,2.372,2.372,0,0,0-2.367-2.369h-8.654a2.373,2.373,0,0,0-2.369,2.369A2.372,2.372,0,0,0-1059.212,975.929Z" transform="translate(4.452 4.452)" fill="#fff"/> <path id="Path_8810" data-name="Path 8810" d="M-1059.212,980.126h8.654a2.37,2.37,0,0,0,2.367-2.367,2.37,2.37,0,0,0-2.367-2.367h-8.654a2.372,2.372,0,0,0-2.369,2.367A2.372,2.372,0,0,0-1059.212,980.126Z" transform="translate(4.452 8.905)" fill="#fff"/> <path id="Path_8811" data-name="Path 8811" d="M-1059.212,984.327h8.654a2.372,2.372,0,0,0,2.367-2.369,2.37,2.37,0,0,0-2.367-2.367h-8.654a2.372,2.372,0,0,0-2.369,2.367A2.373,2.373,0,0,0-1059.212,984.327Z" transform="translate(4.452 13.356)" fill="#fff"/> <path id="Path_8812" data-name="Path 8812" d="M-1024.476,966.991h-34.61a6.7,6.7,0,0,0-6.695,6.7v25.956a6.7,6.7,0,0,0,6.695,6.695h34.61a6.7,6.7,0,0,0,6.7-6.695V973.686A6.7,6.7,0,0,0-1024.476,966.991Zm-36.565,6.7a1.958,1.958,0,0,1,1.955-1.957h34.61a1.96,1.96,0,0,1,1.955,1.957v25.956a1.96,1.96,0,0,1-1.955,1.959h-34.61a1.959,1.959,0,0,1-1.955-1.959Z" transform="translate(0 0)" fill="#fff"/> </g> </g></svg>
                        <span class="aiz-side-nav-text">{{translate('Website Pages')}}</span>
                        <span class="aiz-side-nav-arrow"></span>
                    </a>
               
                    <ul class="aiz-side-nav-list level-2">
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('website.header') }}" class="aiz-side-nav-link">
                                <span class="aiz-side-nav-text">{{translate('Header')}}</span>
                            </a>
                        </li>
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('website.footer') }}" class="aiz-side-nav-link">
                                <span class="aiz-side-nav-text">{{translate('Footer')}}</span>
                            </a>
                        </li>
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('website.pages') }}" class="aiz-side-nav-link {{ areActiveRoutes(['website.pages', 'custom-pages.create' ,'custom-pages.edit'])}}">
                                <span class="aiz-side-nav-text">{{translate('Pages')}}</span>
                            </a>
                        </li>
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('website.appearance') }}" class="aiz-side-nav-link">
                                <span class="aiz-side-nav-text">{{translate('Appearance')}}</span>
                            </a>
                        </li>
                    </ul>
                </li>
               
                @endif

                <!-- Setup & Configurations -->
                @if(Auth::user()->user_type == 'admin' || in_array('14', json_decode(Auth::user()->staff->role->permissions)))
                  <li class="aiz-side-nav-item">
                    <a href="#" class="aiz-side-nav-link align-items-start">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18" height="18" viewBox="0 0 50 50"> <defs> <clipPath id="clip-Setup_Configurations"> <rect width="50" height="50"/> </clipPath> </defs> <g id="Setup_Configurations" data-name="Setup &amp; Configurations" clip-path="url(#clip-Setup_Configurations)"> <g id="settings-outline-badged" transform="translate(-1)"> <path id="Path_8774" data-name="Path 8774" d="M11.1,21.182A10.182,10.182,0,1,0,21.282,11,10.119,10.119,0,0,0,11.1,21.182Zm17.455,0a7.273,7.273,0,1,1-7.273-7.273A7.2,7.2,0,0,1,28.555,21.182Z" transform="translate(4.136 4.545)" fill="#fff"/> <path id="Path_8775" data-name="Path 8775" d="M46.8,20.473l-4.073-1.309-.145-.436a14.6,14.6,0,0,1-3.491-.582,22.379,22.379,0,0,1,1.164,2.764l.145.727,5.236,1.6v4.073l-5.236,1.6-.145.727-1.309,3.055-.436.727,2.618,4.8-2.909,2.909-4.8-2.618-.727.436a13.809,13.809,0,0,1-3.055,1.309l-.727.145-1.6,5.236H23.236l-1.6-5.236-.727-.145-3.055-1.309-.727-.436-4.8,2.618L9.418,38.218l2.618-4.8-.436-.727a13.809,13.809,0,0,1-1.309-3.055l-.145-.727-5.236-1.6V23.236l4.945-1.455.291-.727a12.346,12.346,0,0,1,1.309-3.2l.436-.727-2.473-4.8,2.909-2.909,4.655,2.618.727-.436a12.346,12.346,0,0,1,3.2-1.309L21.636,10l1.6-5.091h4.073L28.909,10l.727.291A15.763,15.763,0,0,1,32.4,11.455a10.043,10.043,0,0,1-.582-3.636l-.582-.291L29.927,3.455A2.455,2.455,0,0,0,27.745,2H22.8a2.039,2.039,0,0,0-2.036,1.745L19.455,7.818a6.426,6.426,0,0,0-2.327.873L13.345,6.655a2.446,2.446,0,0,0-2.764.436L7.091,10.582a2.446,2.446,0,0,0-.436,2.764l1.891,3.636c-.291.727-.582,1.6-.873,2.327L3.6,20.618A2.364,2.364,0,0,0,2,22.8v4.945a2.317,2.317,0,0,0,1.745,2.182l4.073,1.309.873,2.182L6.655,37.2a2.446,2.446,0,0,0,.436,2.764l3.491,3.491a2.446,2.446,0,0,0,2.764.436l3.782-2.036,2.182.873,1.309,4.218a2.364,2.364,0,0,0,2.182,1.6h4.945a2.364,2.364,0,0,0,2.182-1.6l1.309-4.218,2.182-.873L37.2,43.891a2.446,2.446,0,0,0,2.764-.436l3.491-3.491a2.446,2.446,0,0,0,.436-2.764l-2.036-3.782.873-2.182,4.218-1.309a2.364,2.364,0,0,0,1.6-2.182V22.8A2.49,2.49,0,0,0,46.8,20.473Z" transform="translate(0 0.455)" fill="#fff"/> <path id="Path_8776" data-name="Path 8776" d="M39.545,8.273A7.273,7.273,0,1,1,32.273,1a7.273,7.273,0,0,1,7.273,7.273Z" transform="translate(10.455 0)" fill="#fff"/> </g> </g></svg>
                        <span class="aiz-side-nav-text">{{translate('General Settings')}}</span>
                        <span class="aiz-side-nav-arrow"></span>
                    </a>
                    
                    <ul class="aiz-side-nav-list level-2">
                        <li class="aiz-side-nav-item">
                            <a href="{{route('general_setting.index')}}" class="aiz-side-nav-link">
                                <span class="aiz-side-nav-text">{{translate('Settings')}}</span>
                            </a>
                        </li>

                        {{--
                        <li class="aiz-side-nav-item">
                            <a href="{{route('activation.index')}}" class="aiz-side-nav-link">
                                <span class="aiz-side-nav-text">{{translate('Features activation')}}</span>
                            </a>
                        </li>
                        --}}

                        <li class="aiz-side-nav-item">
                            <a href="{{route('languages.index')}}" class="aiz-side-nav-link {{ areActiveRoutes(['languages.index', 'languages.create', 'languages.store', 'languages.show', 'languages.edit'])}}">
                                <span class="aiz-side-nav-text">{{translate('Languages')}}</span>
                            </a>
                        </li>
                        {{--
                        <li class="aiz-side-nav-item">
                            <a href="{{route('currency.index')}}" class="aiz-side-nav-link">
                                <span class="aiz-side-nav-text">{{translate('Currency')}}</span>
                            </a>
                        </li>
                        <li class="aiz-side-nav-item">
                            <a href="{{route('tax.index')}}" class="aiz-side-nav-link {{ areActiveRoutes(['tax.index', 'tax.create', 'tax.store', 'tax.show', 'tax.edit'])}}">
                                <span class="aiz-side-nav-text">{{translate('Vat & TAX')}}</span>
                            </a>
                        </li>
                        --}}               
                        @if(get_setting('pickup_point') == 1)
                            <li class="aiz-side-nav-item">
                                <a href="{{route('pick_up_points.index')}}" class="aiz-side-nav-link {{ areActiveRoutes(['pick_up_points.index','pick_up_points.create','pick_up_points.edit'])}}">
                                    <span class="aiz-side-nav-text">{{translate('Pickup point')}}</span>
                                </a>
                            </li>
                        @endif
                        {{--
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('smtp_settings.index') }}" class="aiz-side-nav-link">
                                <span class="aiz-side-nav-text">{{translate('SMTP Settings')}}</span>
                            </a>
                        </li>
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('payment_method.index') }}" class="aiz-side-nav-link">
                                <span class="aiz-side-nav-text">{{translate('Payment Methods')}}</span>
                            </a>
                        </li>
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('file_system.index') }}" class="aiz-side-nav-link">
                                <span class="aiz-side-nav-text">{{translate('File System Configuration')}}</span>
                            </a>
                        </li>
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('social_login.index') }}" class="aiz-side-nav-link">
                                <span class="aiz-side-nav-text">{{translate('Social media Logins')}}</span>
                            </a>
                        </li>
                    
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('google_analytics.index') }}" class="aiz-side-nav-link">
                                <span class="aiz-side-nav-text">{{translate('Analytics Tools')}}</span>
                            </a>
                        </li>

                        <li class="aiz-side-nav-item">
                            <a href="javascript:void(0);" class="aiz-side-nav-link">
                                <span class="aiz-side-nav-text">{{translate('Facebook')}}</span>
                                <span class="aiz-side-nav-arrow"></span>
                            </a>
                            <ul class="aiz-side-nav-list level-3">
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('facebook_chat.index') }}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text">{{translate('Facebook Chat')}}</span>
                                    </a>
                                </li>
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('facebook-comment') }}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text">{{translate('Facebook Comment')}}</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('google_recaptcha.index') }}" class="aiz-side-nav-link">
                                <span class="aiz-side-nav-text">{{translate('Google reCAPTCHA')}}</span>
                            </a>
                        </li>
                        
                        <li class="aiz-side-nav-item">
                            <a href="javascript:void(0);" class="aiz-side-nav-link">
                                <span class="aiz-side-nav-text">{{translate('Shipping')}}</span>
                                <span class="aiz-side-nav-arrow"></span>
                            </a>
                            <ul class="aiz-side-nav-list level-3">
                                <li class="aiz-side-nav-item">
                                    <a href="{{route('shipping_configuration.index')}}" class="aiz-side-nav-link {{ areActiveRoutes(['shipping_configuration.index','shipping_configuration.edit','shipping_configuration.update'])}}">
                                        <span class="aiz-side-nav-text">{{translate('Shipping Configuration')}}</span>
                                    </a>
                                </li>
                                <li class="aiz-side-nav-item">
                                    <a href="{{route('countries.index')}}" class="aiz-side-nav-link {{ areActiveRoutes(['countries.index','countries.edit','countries.update'])}}">
                                        <span class="aiz-side-nav-text">{{translate('Shipping Countries')}}</span>
                                    </a>
                                </li>
                                 <li class="aiz-side-nav-item">
                                    <a href="{{route('states.index')}}" class="aiz-side-nav-link {{ areActiveRoutes(['states.index','states.edit','states.update'])}}">
                                        <span class="aiz-side-nav-text">{{translate('Shipping States')}}</span>
                                    </a>
                                </li>
                                <li class="aiz-side-nav-item">
                                    <a href="{{route('cities.index')}}" class="aiz-side-nav-link {{ areActiveRoutes(['cities.index','cities.edit','cities.update'])}}">
                                        <span class="aiz-side-nav-text">{{translate('Shipping Cities')}}</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        --}}
                    </ul>
                </li>
                <li class="aiz-side-nav-item">
                    <a href="{{route('activation.index')}}" class="aiz-side-nav-link">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18" height="18" viewBox="0 0 50 50">
                          <defs>
                            <clipPath id="clip-Blog_System">
                              <rect width="50" height="50"/>
                            </clipPath>
                          </defs>
                          <g id="Blog_System" data-name="Blog System" clip-path="url(#clip-Blog_System)">
                            <g id="blog" transform="translate(-3.5 -3.5)">
                              <path id="Path_8761" data-name="Path 8761" d="M4.5,27h20v4H4.5Z" transform="translate(0 17.5)" fill="#fff" stroke="#fff" stroke-width="0.5"/>
                              <path id="Path_8762" data-name="Path 8762" d="M4.5,20.25h20v4H4.5Z" transform="translate(0 12.25)" fill="#fff" stroke="#fff" stroke-width="0.5"/>
                              <path id="Path_8763" data-name="Path 8763" d="M48.5,24.5H8.5a4,4,0,0,1-4-4V8.5a4,4,0,0,1,4-4h40a4,4,0,0,1,4,4v12A4,4,0,0,1,48.5,24.5Zm-40-16v12h40V8.5Z" transform="translate(0 0)" fill="#fff" stroke="#fff" stroke-width="0.5"/>
                              <path id="Path_8764" data-name="Path 8764" d="M36.25,40.25h-12a4,4,0,0,1-4-4v-12a4,4,0,0,1,4-4h12a4,4,0,0,1,4,4v12A4,4,0,0,1,36.25,40.25Zm-12-16v12h12v-12Z" transform="translate(12.25 12.25)" fill="#fff" stroke="#fff" stroke-width="0.5"/>
                            </g>
                          </g>
                        </svg>
                        <span class="aiz-side-nav-text">{{translate('Features activation')}}</span>
                    </a>
                </li>
                <li class="aiz-side-nav-item">
                    <a href="{{route('currency.index')}}" class="aiz-side-nav-link">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18" height="18" viewBox="0 0 50 50">
                          <defs>
                            <clipPath id="clip-Website_Setup">
                              <rect width="50" height="50"/>
                            </clipPath>
                          </defs>
                          <g id="Website_Setup" data-name="Website Setup" clip-path="url(#clip-Website_Setup)">
                            <g id="Group_8474" data-name="Group 8474" transform="translate(1066.781 -961.991)">
                              <path id="Path_8808" data-name="Path 8808" d="M-1050.813,993.233h8.652a2.372,2.372,0,0,0,2.369-2.369v-17.3a2.372,2.372,0,0,0-2.369-2.369h-8.652a2.372,2.372,0,0,0-2.369,2.369v17.3A2.372,2.372,0,0,0-1050.813,993.233Zm2.371-4.736V975.929h3.912V988.5Z" transform="translate(13.355 4.452)" fill="#fff"/>
                              <path id="Path_8809" data-name="Path 8809" d="M-1059.212,975.929h8.654a2.37,2.37,0,0,0,2.367-2.369,2.372,2.372,0,0,0-2.367-2.369h-8.654a2.373,2.373,0,0,0-2.369,2.369A2.372,2.372,0,0,0-1059.212,975.929Z" transform="translate(4.452 4.452)" fill="#fff"/>
                              <path id="Path_8810" data-name="Path 8810" d="M-1059.212,980.126h8.654a2.37,2.37,0,0,0,2.367-2.367,2.37,2.37,0,0,0-2.367-2.367h-8.654a2.372,2.372,0,0,0-2.369,2.367A2.372,2.372,0,0,0-1059.212,980.126Z" transform="translate(4.452 8.905)" fill="#fff"/>
                              <path id="Path_8811" data-name="Path 8811" d="M-1059.212,984.327h8.654a2.372,2.372,0,0,0,2.367-2.369,2.37,2.37,0,0,0-2.367-2.367h-8.654a2.372,2.372,0,0,0-2.369,2.367A2.373,2.373,0,0,0-1059.212,984.327Z" transform="translate(4.452 13.356)" fill="#fff"/>
                              <path id="Path_8812" data-name="Path 8812" d="M-1024.476,966.991h-34.61a6.7,6.7,0,0,0-6.695,6.7v25.956a6.7,6.7,0,0,0,6.695,6.695h34.61a6.7,6.7,0,0,0,6.7-6.695V973.686A6.7,6.7,0,0,0-1024.476,966.991Zm-36.565,6.7a1.958,1.958,0,0,1,1.955-1.957h34.61a1.96,1.96,0,0,1,1.955,1.957v25.956a1.96,1.96,0,0,1-1.955,1.959h-34.61a1.959,1.959,0,0,1-1.955-1.959Z" transform="translate(0 0)" fill="#fff"/>
                            </g>
                          </g>
                        </svg>
                        <span class="aiz-side-nav-text">{{translate('Manage Currency')}}</span>
                    </a>
                </li>
                <li class="aiz-side-nav-item">
                    <a href="{{route('tax.index')}}" class="aiz-side-nav-link {{ areActiveRoutes(['tax.index', 'tax.create', 'tax.store', 'tax.show', 'tax.edit'])}}">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18" height="18" viewBox="0 0 50 50"> <defs> <clipPath id="clip-Reports"> <rect width="50" height="50"/> </clipPath> </defs> <g id="Reports" clip-path="url(#clip-Reports)"> <g id="Group_8472" data-name="Group 8472" transform="translate(-2494.548 -787.118)"> <rect id="Rectangle_17821" data-name="Rectangle 17821" width="4.125" height="7.5" transform="translate(2517.423 818.492)" fill="#fff"/> <rect id="Rectangle_17822" data-name="Rectangle 17822" width="4.125" height="10.875" transform="translate(2525.86 815.117)" fill="#fff"/> <rect id="Rectangle_17823" data-name="Rectangle 17823" width="4.125" height="17.625" transform="translate(2508.985 808.368)" fill="#fff"/> <path id="Path_8760" data-name="Path 8760" d="M2534.672,793.18h-4.687v-1.312a3.753,3.753,0,0,0-3.75-3.75h-13.5a3.753,3.753,0,0,0-3.75,3.75v1.313H2504.3a3.753,3.753,0,0,0-3.75,3.75v35.438a3.753,3.753,0,0,0,3.75,3.75h30.375a3.754,3.754,0,0,0,3.75-3.75V796.93A3.753,3.753,0,0,0,2534.672,793.18Zm-22.261,9.188h14.146a3.431,3.431,0,0,0,3.428-3.427v-1.635h4.312v34.687h-29.625V797.305h4.313v1.635A3.43,3.43,0,0,0,2512.411,802.368Zm.7-4.125v-6h12.75v6Z" transform="translate(0)" fill="#fff"/> </g> </g></svg>
                        <span class="aiz-side-nav-text">{{translate('Manage Vat & TAX')}}</span>
                    </a>
                </li>
                <li class="aiz-side-nav-item">
                    <a href="{{ route('smtp_settings.index') }}" class="aiz-side-nav-link">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18" height="18" viewBox="0 0 50 50"> <defs> <clipPath id="clip-Sales"> <rect width="50" height="50"/> </clipPath> </defs> <g id="Sales" clip-path="url(#clip-Sales)"> <g id="Group_8469" data-name="Group 8469" transform="translate(-2536.319 -738.77)"> <g id="Group_8438" data-name="Group 8438" transform="translate(2537.319 745.77)"> <path id="Path_8751" data-name="Path 8751" d="M2582.379,745.77h-42.112a2.95,2.95,0,0,0-2.948,2.95v30.427a2.946,2.946,0,0,0,2.939,2.955h42.114a2.947,2.947,0,0,0,2.947-2.947V748.723A2.948,2.948,0,0,0,2582.379,745.77Zm-1.825,31.487v0h-38.469V750.615h38.469Z" transform="translate(-2537.319 -745.77)" fill="#fff"/> </g> <path id="Path_8750" data-name="Path 8750" d="M2553.122,768.1a1.875,1.875,0,0,1-1.326-.55l-2.619-2.618-3.186,2.638a1.877,1.877,0,0,1-2.47-2.827l.077-.065,4.5-3.727a1.876,1.876,0,0,1,2.525.12l2.177,2.178,4.8-7.478a1.876,1.876,0,0,1,3.267.213l2.839,5.991,5.472-9.618a1.879,1.879,0,0,1,3.267,1.857h0l-7.254,12.756a1.892,1.892,0,0,1-1.7.947,1.871,1.871,0,0,1-1.633-1.075l-2.927-6.2-4.231,6.591a1.875,1.875,0,0,1-1.579.863Z" transform="translate(3.706 3.761)" fill="#fff"/> </g> </g></svg>
                        <span class="aiz-side-nav-text">{{translate('Manage Email Settings')}}</span>
                    </a>
                </li>
                <li class="aiz-side-nav-item">
                    <a href="{{ route('payment_method.index') }}" class="aiz-side-nav-link">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18" height="18" viewBox="0 0 50 50"> <defs> <clipPath id="clip-Setup_Configurations"> <rect width="50" height="50"/> </clipPath> </defs> <g id="Setup_Configurations" data-name="Setup &amp; Configurations" clip-path="url(#clip-Setup_Configurations)"> <g id="settings-outline-badged" transform="translate(-1)"> <path id="Path_8774" data-name="Path 8774" d="M11.1,21.182A10.182,10.182,0,1,0,21.282,11,10.119,10.119,0,0,0,11.1,21.182Zm17.455,0a7.273,7.273,0,1,1-7.273-7.273A7.2,7.2,0,0,1,28.555,21.182Z" transform="translate(4.136 4.545)" fill="#fff"/> <path id="Path_8775" data-name="Path 8775" d="M46.8,20.473l-4.073-1.309-.145-.436a14.6,14.6,0,0,1-3.491-.582,22.379,22.379,0,0,1,1.164,2.764l.145.727,5.236,1.6v4.073l-5.236,1.6-.145.727-1.309,3.055-.436.727,2.618,4.8-2.909,2.909-4.8-2.618-.727.436a13.809,13.809,0,0,1-3.055,1.309l-.727.145-1.6,5.236H23.236l-1.6-5.236-.727-.145-3.055-1.309-.727-.436-4.8,2.618L9.418,38.218l2.618-4.8-.436-.727a13.809,13.809,0,0,1-1.309-3.055l-.145-.727-5.236-1.6V23.236l4.945-1.455.291-.727a12.346,12.346,0,0,1,1.309-3.2l.436-.727-2.473-4.8,2.909-2.909,4.655,2.618.727-.436a12.346,12.346,0,0,1,3.2-1.309L21.636,10l1.6-5.091h4.073L28.909,10l.727.291A15.763,15.763,0,0,1,32.4,11.455a10.043,10.043,0,0,1-.582-3.636l-.582-.291L29.927,3.455A2.455,2.455,0,0,0,27.745,2H22.8a2.039,2.039,0,0,0-2.036,1.745L19.455,7.818a6.426,6.426,0,0,0-2.327.873L13.345,6.655a2.446,2.446,0,0,0-2.764.436L7.091,10.582a2.446,2.446,0,0,0-.436,2.764l1.891,3.636c-.291.727-.582,1.6-.873,2.327L3.6,20.618A2.364,2.364,0,0,0,2,22.8v4.945a2.317,2.317,0,0,0,1.745,2.182l4.073,1.309.873,2.182L6.655,37.2a2.446,2.446,0,0,0,.436,2.764l3.491,3.491a2.446,2.446,0,0,0,2.764.436l3.782-2.036,2.182.873,1.309,4.218a2.364,2.364,0,0,0,2.182,1.6h4.945a2.364,2.364,0,0,0,2.182-1.6l1.309-4.218,2.182-.873L37.2,43.891a2.446,2.446,0,0,0,2.764-.436l3.491-3.491a2.446,2.446,0,0,0,.436-2.764l-2.036-3.782.873-2.182,4.218-1.309a2.364,2.364,0,0,0,1.6-2.182V22.8A2.49,2.49,0,0,0,46.8,20.473Z" transform="translate(0 0.455)" fill="#fff"/> <path id="Path_8776" data-name="Path 8776" d="M39.545,8.273A7.273,7.273,0,1,1,32.273,1a7.273,7.273,0,0,1,7.273,7.273Z" transform="translate(10.455 0)" fill="#fff"/> </g> </g></svg>
                        <span class="aiz-side-nav-text">{{translate('Manage Payment Gateway')}}</span>
                    </a>
                </li>
                <li class="aiz-side-nav-item">
                    <a href="{{ route('file_system.index') }}" class="aiz-side-nav-link">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18" height="18" viewBox="0 0 50 50"> <defs> <clipPath id="clip-Uploaded_Files"> <rect width="50" height="50"/> </clipPath> </defs> <g id="Uploaded_Files" data-name="Uploaded Files" clip-path="url(#clip-Uploaded_Files)"> <g id="file-earmark-arrow-down" transform="translate(-0.5 -1.25)"> <path id="Path_8756" data-name="Path 8756" d="M11.357,2.25H28.5V5.679H11.357A3.429,3.429,0,0,0,7.929,9.107V43.393a3.429,3.429,0,0,0,3.429,3.429H38.786a3.429,3.429,0,0,0,3.429-3.429v-24h3.429v24a6.857,6.857,0,0,1-6.857,6.857H11.357A6.857,6.857,0,0,1,4.5,43.393V9.107A6.857,6.857,0,0,1,11.357,2.25Z" transform="translate(0 0)" fill="#fff"/> <path id="Path_8757" data-name="Path 8757" d="M20.25,14.25v-12L37.393,19.393h-12A5.143,5.143,0,0,1,20.25,14.25Z" transform="translate(8.25)" fill="#fff"/> <path id="Path_8758" data-name="Path 8758" d="M12.877,20.752a1.714,1.714,0,0,1,2.427,0L20.947,26.4l5.644-5.647a1.716,1.716,0,0,1,2.427,2.427l-6.857,6.857a1.714,1.714,0,0,1-2.427,0l-6.857-6.857a1.714,1.714,0,0,1,0-2.427Z" transform="translate(4.124 9.428)" fill="#fff" fill-rule="evenodd"/> <path id="Path_8759" data-name="Path 8759" d="M18.589,13.5A1.714,1.714,0,0,1,20.3,15.214V28.929a1.714,1.714,0,1,1-3.429,0V15.214A1.714,1.714,0,0,1,18.589,13.5Z" transform="translate(6.482 5.893)" fill="#fff" fill-rule="evenodd"/> </g> </g></svg>
                        <span class="aiz-side-nav-text">{{translate('File System Configuration')}}</span>
                    </a>
                </li>
                <li class="aiz-side-nav-item">
                    <a href="#" class="aiz-side-nav-link">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18" height="18" viewBox="0 0 50 50">
                          <defs>
                            <clipPath id="clip-Blog_System">
                              <rect width="50" height="50"/>
                            </clipPath>
                          </defs>
                          <g id="Blog_System" data-name="Blog System" clip-path="url(#clip-Blog_System)">
                            <g id="blog" transform="translate(-3.5 -3.5)">
                              <path id="Path_8761" data-name="Path 8761" d="M4.5,27h20v4H4.5Z" transform="translate(0 17.5)" fill="#fff" stroke="#fff" stroke-width="0.5"/>
                              <path id="Path_8762" data-name="Path 8762" d="M4.5,20.25h20v4H4.5Z" transform="translate(0 12.25)" fill="#fff" stroke="#fff" stroke-width="0.5"/>
                              <path id="Path_8763" data-name="Path 8763" d="M48.5,24.5H8.5a4,4,0,0,1-4-4V8.5a4,4,0,0,1,4-4h40a4,4,0,0,1,4,4v12A4,4,0,0,1,48.5,24.5Zm-40-16v12h40V8.5Z" transform="translate(0 0)" fill="#fff" stroke="#fff" stroke-width="0.5"/>
                              <path id="Path_8764" data-name="Path 8764" d="M36.25,40.25h-12a4,4,0,0,1-4-4v-12a4,4,0,0,1,4-4h12a4,4,0,0,1,4,4v12A4,4,0,0,1,36.25,40.25Zm-12-16v12h12v-12Z" transform="translate(12.25 12.25)" fill="#fff" stroke="#fff" stroke-width="0.5"/>
                            </g>
                          </g>
                        </svg>
                        <span class="aiz-side-nav-text">{{translate('Manage Shipping')}}</span>
                        <span class="aiz-side-nav-arrow"></span>
                    </a>
                    <ul class="aiz-side-nav-list level-2">
                        @if(get_setting('ship_engine') == '0')
                            <li class="aiz-side-nav-item">
                                <a href="{{route('shipping_configuration.index')}}" class="aiz-side-nav-link {{ areActiveRoutes(['shipping_configuration.index','shipping_configuration.edit','shipping_configuration.update'])}}">
                                    <span class="aiz-side-nav-text">{{translate('Shipping Configuration')}}</span>
                                </a>
                            </li>
                        @endif
                        @if(get_setting('ship_engine'))
                            <li class="aiz-side-nav-item">
                                <a href="{{route('shipping_configuration.carriers')}}" class="aiz-side-nav-link {{ areActiveRoutes(['shipping_configuration.carriers'])}}">
                                    <span class="aiz-side-nav-text">{{translate('Shipping Providers')}}</span>
                                </a>
                            </li>
                        @endif
                        <li class="aiz-side-nav-item">
                            <a href="{{route('countries.index')}}" class="aiz-side-nav-link {{ areActiveRoutes(['countries.index','countries.edit','countries.update'])}}">
                                <span class="aiz-side-nav-text">{{translate('Shipping Countries')}}</span>
                            </a>
                        </li>
                        <li class="aiz-side-nav-item">
                            <a href="{{route('states.index')}}" class="aiz-side-nav-link {{ areActiveRoutes(['states.index','states.edit','states.update'])}}">
                                <span class="aiz-side-nav-text">{{translate('Shipping States')}}</span>
                            </a>
                        </li>
                        <li class="aiz-side-nav-item">
                            <a href="{{route('cities.index')}}" class="aiz-side-nav-link {{ areActiveRoutes(['cities.index','cities.edit','cities.update'])}}">
                                <span class="aiz-side-nav-text">{{translate('Shipping Cities')}}</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="aiz-side-nav-item">
                    <a href="{{ route('google_recaptcha.index') }}" class="aiz-side-nav-link">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18" height="18" viewBox="0 0 50 50"> <defs> <clipPath id="clip-Reports"> <rect width="50" height="50"/> </clipPath> </defs> <g id="Reports" clip-path="url(#clip-Reports)"> <g id="Group_8472" data-name="Group 8472" transform="translate(-2494.548 -787.118)"> <rect id="Rectangle_17821" data-name="Rectangle 17821" width="4.125" height="7.5" transform="translate(2517.423 818.492)" fill="#fff"/> <rect id="Rectangle_17822" data-name="Rectangle 17822" width="4.125" height="10.875" transform="translate(2525.86 815.117)" fill="#fff"/> <rect id="Rectangle_17823" data-name="Rectangle 17823" width="4.125" height="17.625" transform="translate(2508.985 808.368)" fill="#fff"/> <path id="Path_8760" data-name="Path 8760" d="M2534.672,793.18h-4.687v-1.312a3.753,3.753,0,0,0-3.75-3.75h-13.5a3.753,3.753,0,0,0-3.75,3.75v1.313H2504.3a3.753,3.753,0,0,0-3.75,3.75v35.438a3.753,3.753,0,0,0,3.75,3.75h30.375a3.754,3.754,0,0,0,3.75-3.75V796.93A3.753,3.753,0,0,0,2534.672,793.18Zm-22.261,9.188h14.146a3.431,3.431,0,0,0,3.428-3.427v-1.635h4.312v34.687h-29.625V797.305h4.313v1.635A3.43,3.43,0,0,0,2512.411,802.368Zm.7-4.125v-6h12.75v6Z" transform="translate(0)" fill="#fff"/> </g> </g></svg>
                        <span class="aiz-side-nav-text">{{translate('Manage Api Credentials')}}</span>
                        <!-- <span class="aiz-side-nav-arrow"></span> -->
                    </a>
                    <ul class="aiz-side-nav-list level-2">
                        {{--
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('google_recaptcha.index') }}" class="aiz-side-nav-link">
                                <span class="aiz-side-nav-text">{{translate('Google reCAPTCHA')}}</span>
                            </a>
                        </li>
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('social_login.index') }}" class="aiz-side-nav-link">
                                <span class="aiz-side-nav-text">{{translate('Social media Logins')}}</span>
                            </a>
                        </li>
                        --}}
                    </ul>
                </li>
                @endif


                <!-- Staffs -->
                @if(Auth::user()->user_type == 'admin' || in_array('20', json_decode(Auth::user()->staff->role->permissions)))
                {{--
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18" height="18" viewBox="0 0 50 50"> <defs> <clipPath id="clip-Staffs"> <rect width="50" height="50"/> </clipPath> </defs> <g id="Staffs" clip-path="url(#clip-Staffs)"> <g id="Group_8471" data-name="Group 8471" transform="translate(-2504.429 -746.933)"> <path id="Path_8752" data-name="Path 8752" d="M2526.2,763.327h-.882v4.94h.882a7.068,7.068,0,0,1,7.061,7.061v8.826h4.942v-8.828A12.032,12.032,0,0,0,2526.2,763.327Z" transform="translate(15.221 11.013)" fill="#fff"/> <path id="Path_8753" data-name="Path 8753" d="M2526.963,763.327h-9.532a12.032,12.032,0,0,0-12,12v8.826h4.934v-8.826a7.068,7.068,0,0,1,7.061-7.061h9.531a7.068,7.068,0,0,1,7.061,7.061v8.826h4.94l.01-8.826A12.029,12.029,0,0,0,2526.963,763.327Z" transform="translate(0 11.013)" fill="#fff"/> <path id="Path_8754" data-name="Path 8754" d="M2535.511,760.936a12.018,12.018,0,0,0-12-12h-.882v4.944h.882a7.061,7.061,0,1,1,0,14.121h-.882v4.942h.882A12.019,12.019,0,0,0,2535.511,760.936Z" transform="translate(13.158)" fill="#fff"/> <path id="Path_8755" data-name="Path 8755" d="M2520.129,772.941a12,12,0,1,0-12-12A12.018,12.018,0,0,0,2520.129,772.941Zm-7.061-12a7.061,7.061,0,1,1,7.061,7.061A7.068,7.068,0,0,1,2513.068,760.938Z" transform="translate(2.064)" fill="#fff"/> <path id="Path_8777" data-name="Path 8777" d="M2520.588,769.691v7h4.94l.01-7Z" transform="translate(-0.872 10.936)" fill="#fff"/> </g> </g></svg>
                            <span class="aiz-side-nav-text">{{translate('Staffs')}}</span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('staffs.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['staffs.index', 'staffs.create', 'staffs.edit'])}}">
                                    <span class="aiz-side-nav-text">{{translate('All staffs')}}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{route('roles.index')}}" class="aiz-side-nav-link {{ areActiveRoutes(['roles.index', 'roles.create', 'roles.edit'])}}">
                                    <span class="aiz-side-nav-text">{{translate('Staff permissions')}}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                --}}
                @endif
                @if(Auth::user()->user_type == 'admin' || in_array('24', json_decode(Auth::user()->staff->role->permissions)))
                {{--
                <li class="aiz-side-nav-item">
                    <a href="#" class="aiz-side-nav-link">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18" height="18" viewBox="0 0 50 50"> <defs> <clipPath id="clip-System"> <rect width="50" height="50"/> </clipPath> </defs> <g id="System" clip-path="url(#clip-System)"> <g id="server" transform="translate(0 -2)"> <path id="Path_8778" data-name="Path 8778" d="M14.4,9a2.4,2.4,0,1,0,0,4.8H28.8a2.4,2.4,0,1,0,0-4.8Z" transform="translate(3.6 3.6)" fill="#fff"/> <path id="Path_8779" data-name="Path 8779" d="M14.4,15a2.4,2.4,0,1,0,0,4.8H28.8a2.4,2.4,0,1,0,0-4.8Z" transform="translate(3.6 7.2)" fill="#fff"/> <path id="Path_8780" data-name="Path 8780" d="M21.3,26.4A2.4,2.4,0,1,1,18.9,24,2.4,2.4,0,0,1,21.3,26.4Z" transform="translate(6.3 12.6)" fill="#fff"/> <path id="Path_8781" data-name="Path 8781" d="M6,10.2A7.2,7.2,0,0,1,13.2,3h24a7.2,7.2,0,0,1,7.2,7.2V43.8A7.2,7.2,0,0,1,37.2,51h-24A7.2,7.2,0,0,1,6,43.8Zm7.2-2.4h24a2.4,2.4,0,0,1,2.4,2.4V43.8a2.4,2.4,0,0,1-2.4,2.4h-24a2.4,2.4,0,0,1-2.4-2.4V10.2A2.4,2.4,0,0,1,13.2,7.8Z" transform="translate(0 0)" fill="#fff" fill-rule="evenodd"/> </g> </g></svg>
                        <span class="aiz-side-nav-text">{{translate('System')}}</span>
                        <span class="aiz-side-nav-arrow"></span>
                    </a>
                    <ul class="aiz-side-nav-list level-2">
                        
                        <li class="aiz-side-nav-item">
                            <a href="{{route('system_server')}}" class="aiz-side-nav-link">
                                <span class="aiz-side-nav-text">{{translate('Server status')}}</span>
                            </a>
                        </li>
                    </ul>
                </li>
                --}}
                @endif

               
            </ul><!-- .aiz-side-nav -->
        </div><!-- .aiz-side-nav-wrap -->
    </div><!-- .aiz-sidebar -->
    <div class="aiz-sidebar-overlay"></div>
</div><!-- .aiz-sidebar -->
