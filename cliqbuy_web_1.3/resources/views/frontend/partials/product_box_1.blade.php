<div class="aiz-card-box mt-1 mb-2 has-transition bg-white">
    <div class="position-relative">
        <a href="{{ route('product', $product->slug) }}" class="d-block">
            <img
                class="img-fit lazyload mx-auto h-140px h-md-210px"
                src="{{ static_asset('assets/img/placeholder.jpg') }}"
                data-src="{{ uploaded_asset($product->thumbnail_img) }}"
                alt="{{  $product->getTranslation('name')  }}"
                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"
            >
        </a>
        <div class="absolute-top-right aiz-p-hov-icon">
            <?php
                $is_wished = is_wished($product->id);
            ?>
            <a href="javascript:void(0)" onclick="addToWishList({{ $product->id }}, this, 'anchor')" data-toggle="tooltip" data-original-title="{{ ($is_wished == 1) ? translate('Remove from wishlist') : translate('Add to wishlist') }}" data-placement="left" data-value="{{ $is_wished }}">
                <i class="la {{ ($is_wished == 1) ? 'la-heart' : 'la-heart-o' }}"></i>
            </a>
            <a href="javascript:void(0)" onclick="addToCompare({{ $product->id }})" data-toggle="tooltip" data-original-title="{{ translate('Add to compare') }}" data-placement="left">
                <i class="las la-sync"></i>
            </a>
            <a href="javascript:void(0)" onclick="showAddToCartModal({{ $product->id }})" data-toggle="tooltip" data-original-title="{{ translate('Add to cart') }}" data-placement="left">
                <i class="las la-shopping-cart"></i>
            </a>
        </div>
    </div>
    <div class="py-md-3 py-2 text-left">
        <div class="fs-13">
            @if(home_base_price($product) != home_discounted_base_price($product))
                <del class="fw-600 mr-1" style="color:#BFBFBF">{{ home_base_price($product) }}</del>
            @endif
            <span class="fw-600" style="color:#232F3E">{{ home_discounted_base_price($product) }}</span>
        </div>
        <div class="rating rating-sm my-1">
            {{ renderStarRating($product->rating) }}
        </div>
        <h3 class="fw-500 fs-13 text-truncate-2 lh-1-4 mb-0 h-35px">
            <a href="{{ route('product', $product->slug) }}" class="d-block text-reset">{{  $product->getTranslation('name')  }}</a>
        </h3>
        @if (\App\Addon::where('unique_identifier', 'club_point')->first() != null && \App\Addon::where('unique_identifier', 'club_point')->first()->activated)
            <div class="rounded px-2 mt-2 bg-soft-primary border-soft-primary border">
                {{ translate('Club Point') }}:
                <span class="fw-700 float-right">{{ $product->earn_point }}</span>
            </div>
        @endif
    </div>
</div>
