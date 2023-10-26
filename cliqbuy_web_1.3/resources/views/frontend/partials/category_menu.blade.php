  
    <ul class="cls_sidenav_ul">
        @foreach (\App\Category::where('level', 0)->orderBy('order_level', 'desc')->get() as $key => $category)
            <li class="category-nav-element {{ count(\App\Utility\CategoryUtility::get_immediate_children_ids($category->id))>0 ? 'subyes' : 'subno' }}" data-id="{{ $category->id }}">
                <a href="{{ route('products.category', $category->slug) }}" class="text-truncate text-reset py-3 px-3 d-block cls_title_a">
                  <!--   <img
                        class="cat-image lazyload mr-2 opacity-60"
                        src="{{ static_asset('assets/img/placeholder.jpg') }}"
                        data-src="{{ uploaded_asset($category->icon) }}"
                        width="16"
                        alt="{{ $category->getTranslation('name') }}"
                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"
                    > -->
                    <span class="cat-name">{{ $category->getTranslation('name') }}</span>
                    
                </a>
                <div class="cat_plusminus">
                    <i class="la la-angle-right goright"></i>

                    <i class="la la-angle-left remv" style="background-color:#FEBD69;padding:6px 8px;line-height:18px;margin:20px 0 0 -14px;border-radius:5px;"></i>
                </div> 

                {{-- @if(count(\App\Utility\CategoryUtility::get_immediate_children_ids($category->id))>0 && \App::getLocale() != 'sa') --}}
                @if(count(\App\Utility\CategoryUtility::get_immediate_children_ids($category->id))>0)
                <div class="sub-cat-menu">                      
                        <div class="c-preloader text-center absolute-center">
                            <i class="las la-spinner la-spin la-3x opacity-70"></i>
                        </div>
                    </div>
                @endif
            </li>
        @endforeach
        <div class="cls_seeall">
             <a href="{{ route('categories.all') }}" class="text-reset">
                <span class="d-none d-lg-inline-block">{{ translate('See All') }} </span>
            </a>
        </div>
    </ul>
