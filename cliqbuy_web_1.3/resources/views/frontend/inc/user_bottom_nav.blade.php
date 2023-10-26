@extends('frontend.layouts.app')
@section('content')
<section class="py-5">
    <div class="container-fluid">
        <div class="">
            <div class="aiz-user-panel">
                @yield('panel_content')
                <div class="cls_bottom_list">
                    <div class=" mb-3">
                        <ul class="aiz-side-nav-list row gutters-10">
                            <li class=" col-lg-4">
                                <div class="cls_menu_view">
                                    <a href="{{ route('dashboard') }}" class="cls_link {{ areActiveRoutes(['dashboard'])}}">
                                     <div class="icon"> <i class="las la-home "></i></div>
                                     <span class="aiz-side-nav-text">{{ translate('Dashboard') }}</span>
                                 </a>
                             </div>
                         </li>

                         @if(Auth::user()->user_type == 'delivery_boy')
                         <li class=" col-lg-4">
                            <div class="cls_menu_view">
                                <a href="{{ route('assigned-deliveries') }}" class="cls_link {{ areActiveRoutes(['completed-delivery'])}}">
                                 <div class="icon"> <i class="las la-hourglass-half "></i></div>
                                 <span class="aiz-side-nav-text">
                                    {{ translate('Assigned Delivery') }}
                                </span>
                            </a>
                        </div>
                    </li>
                    <li class=" col-lg-4">
                        <div class="cls_menu_view">
                            <a href="{{ route('pickup-deliveries') }}" class="cls_link {{ areActiveRoutes(['completed-delivery'])}}">
                             <div class="icon"> <i class="las la-luggage-cart "></i></div>
                             <span class="aiz-side-nav-text">
                                {{ translate('Pickup Delivery') }}
                            </span>
                        </a>
                    </div>
                </li>
                <li class=" col-lg-4">
                    <div class="cls_menu_view">
                        <a href="{{ route('on-the-way-deliveries') }}" class="cls_link {{ areActiveRoutes(['completed-delivery'])}}">
                         <div class="icon"> <i class="las la-running "></i></div>
                         <span class="aiz-side-nav-text">
                            {{ translate('On The Way Delivery') }}
                        </span>
                    </a>
                </div>
            </li>
            <li class=" col-lg-4">
                <div class="cls_menu_view">
                    <a href="{{ route('completed-deliveries') }}" class="cls_link {{ areActiveRoutes(['completed-delivery'])}}">
                     <div class="icon"> <i class="las la-check-circle "></i></div>
                     <span class="aiz-side-nav-text">
                        {{ translate('Completed Delivery') }}
                    </span>
                </a>
            </div>
        </li>
        <li class=" col-lg-4">
            <div class="cls_menu_view">
                <a href="{{ route('pending-deliveries') }}" class="cls_link {{ areActiveRoutes(['pending-delivery'])}}">
                 <div class="icon"> <i class="las la-clock "></i></div>
                 <span class="aiz-side-nav-text">
                    {{ translate('Pending Delivery') }}
                </span>
            </a>
        </div>
    </li>
    <li class=" col-lg-4">
        <div class="cls_menu_view">
            <a href="{{ route('cancelled-deliveries') }}" class="cls_link {{ areActiveRoutes(['cancelled-delivery'])}}">
             <div class="icon"> <i class="las la-times-circle "></i></div>
             <span class="aiz-side-nav-text">
                {{ translate('Cancelled Delivery') }}
            </span>
        </a>
    </div>
</li>
<li class=" col-lg-4">
    <div class="cls_menu_view">
        <a href="{{ route('cancel-request-list') }}" class="cls_link {{ areActiveRoutes(['cancel-request-list'])}}">
         <div class="icon"> <i class="las la-times-circle "></i></div>
         <span class="aiz-side-nav-text">
            {{ translate('Request Cancelled Delivery') }}
        </span>
    </a>
</div>
</li>
<li class=" col-lg-4">
    <div class="cls_menu_view">
        <a href="{{ route('total-collection') }}" class="cls_link {{ areActiveRoutes(['today-collection'])}}">
         <div class="icon"> <i class="las la-comment-dollar "></i></div>
         <span class="aiz-side-nav-text">
            {{ translate('Total Collections') }}
        </span>
    </a>
</div>
</li>
<li class=" col-lg-4">
    <div class="cls_menu_view">
        <a href="{{ route('total-earnings') }}" class="cls_link {{ areActiveRoutes(['total-earnings'])}}">
         <div class="icon"> <i class="las la-comment-dollar "></i></div>
         <span class="aiz-side-nav-text">
            {{ translate('Total Earnings') }}
        </span>
    </a>
</div>
</li>
@else

@php
$delivery_viewed = App\Order::where('user_id', Auth::user()->id)->where('delivery_viewed', 0)->get()->count();
$payment_status_viewed = App\Order::where('user_id', Auth::user()->id)->where('payment_status_viewed', 0)->get()->count();
@endphp
<li class=" col-lg-4">
    <div class="cls_menu_view">
        <a href="{{ route('purchase_history.index') }}" class="cls_link {{ areActiveRoutes(['purchase_history.index'])}}">
         <div class="icon"> <i class="las la-file-alt "></i></div>
         <span class="aiz-side-nav-text">{{ translate('Purchase History') }}</span>
         <!-- @if($delivery_viewed > 0 || $payment_status_viewed > 0)<span class="badge badge-inline badge-success">{{ translate('New') }}</span>@endif -->
     </a>
 </div>
</li>
{{--
<li class=" col-lg-4">
    <div class="cls_menu_view">
        <a href="{{ route('digital_purchase_history.index') }}" class="cls_link {{ areActiveRoutes(['digital_purchase_history.index'])}}">
         <div class="icon"> <i class="las la-download "></i></div>
         <span class="aiz-side-nav-text">{{ translate('Downloads') }}</span>
     </a>
 </div>
</li>
--}}
@php
$refund_request_addon = \App\Addon::where('unique_identifier', 'refund_request')->first();
$club_point_addon = \App\Addon::where('unique_identifier', 'club_point')->first();
@endphp
@if ($refund_request_addon != null && $refund_request_addon->activated == 1)
<li class=" col-lg-4">
    <div class="cls_menu_view">
        <a href="{{ route('customer_refund_request') }}" class="cls_link {{ areActiveRoutes(['customer_refund_request'])}}">
         <div class="icon"> <i class="las la-backward "></i></div>
         <span class="aiz-side-nav-text">{{ translate('Sent Refund Request') }}</span>
     </a>
 </div>
</li>
@endif

<li class=" col-lg-4">
    <div class="cls_menu_view">
        <a href="{{ route('wishlists.index') }}" class="cls_link {{ areActiveRoutes(['wishlists.index'])}}">
         <div class="icon"> <i class="la la-heart-o "></i></div>
         <span class="aiz-side-nav-text">{{ translate('Wishlist') }}</span>
     </a>
 </div>
</li>

@if(Auth::user()->user_type == 'seller')
<li class=" col-lg-4">
    <div class="cls_menu_view">
        <a href="{{ route('seller.products') }}" class="cls_link {{ areActiveRoutes(['seller.products', 'seller.products.upload', 'seller.products.edit'])}}">
         <div class="icon"> <i class="lab la-sketch "></i></div>
         <span class="aiz-side-nav-text">{{ translate('Products') }}</span>
     </a>
 </div>
</li>
<!-- <li class=" col-lg-4">
    <div class="cls_menu_view">
        <a href="{{route('product_bulk_upload.index')}}" class="cls_link {{ areActiveRoutes(['product_bulk_upload.index'])}}">
         <div class="icon"> <i class="las la-upload "></i></div>
         <span class="aiz-side-nav-text">{{ translate('Product Bulk Upload') }}</span>
     </a>
 </div>
</li> -->
{{--
<li class=" col-lg-4">
    <div class="cls_menu_view">
        <a href="{{ route('seller.digitalproducts') }}" class="cls_link {{ areActiveRoutes(['seller.digitalproducts', 'seller.digitalproducts.upload', 'seller.digitalproducts.edit'])}}">
         <div class="icon"> <i class="lab la-sketch "></i></div>
         <span class="aiz-side-nav-text">{{ translate('Digital Products') }}</span>
     </a>
 </div>
</li>
<li class=" col-lg-4">
    <div class="cls_menu_view">
        <a href="{{ route('my_uploads.all') }}" class="cls_link {{ areActiveRoutes(['my_uploads.new'])}}">
         <div class="icon"> <i class="las la-folder-open "></i></div>
         <span class="aiz-side-nav-text">{{ translate('Uploaded Files') }}</span>
     </a>
 </div>
</li>
@endif

@if(get_setting('classified_product') == 1)
<li class=" col-lg-4">
    <div class="cls_menu_view">
        <a href="{{ route('customer_products.index') }}" class="cls_link {{ areActiveRoutes(['customer_products.index', 'customer_products.create', 'customer_products.edit'])}}">
         <div class="icon"> <i class="lab la-sketch "></i></div>
         <span class="aiz-side-nav-text">{{ translate('Classified Products') }}</span>
     </a>
 </div>
</li>
--}}
@endif

@if(addon_activated('auction'))
<li class=" col-lg-4">
    <div class="cls_menu_view">
        <a href="javascript:void(0);" class="cls_link {{ areActiveRoutes(['auction_product_bids.index'])}}">
         <div class="icon"> <i class="las la-gavel "></i></div>
         <span class="aiz-side-nav-text">{{ translate('Auction Product') }}</span>
         <span class="aiz-side-nav-arrow"></span>
     </a>
 </div>
 <ul class="aiz-side-nav-list level-2">
    <li class=" col-lg-4">
        <div class="cls_menu_view">
            <a href="{{ route('auction_product_bids.index') }}" class="cls_link">
                <span class="aiz-side-nav-text">{{ translate('Bidded Products') }}</span>
            </a>
        </div>
    </li>
    <li class=" col-lg-4">
        <div class="cls_menu_view">
            <a href="{{ route('auction_product.purchase_history') }}" class="cls_link">
                <span class="aiz-side-nav-text">{{ translate('Purchase History') }}</span>
            </a>
        </div>
    </li>
</ul>
</li>
@endif

@if(Auth::user()->user_type == 'seller')
@if (\App\Addon::where('unique_identifier', 'pos_system')->first() != null && \App\Addon::where('unique_identifier', 'pos_system')->first()->activated)
@if (\App\BusinessSetting::where('type', 'pos_activation_for_seller')->first() != null && get_setting('pos_activation_for_seller') != 0)
<li class=" col-lg-4">
    <div class="cls_menu_view">
        <a href="{{ route('poin-of-sales.seller_index') }}" class="cls_link {{ areActiveRoutes(['poin-of-sales.seller_index'])}}">
         <div class="icon"> <i class="las la-fax "></i></div>
         <span class="aiz-side-nav-text">{{ translate('POS Manager') }}</span>
     </a>
 </div>
</li>
@endif
@endif

@php
$orders = DB::table('orders')
->orderBy('code', 'desc')
->join('order_details', 'orders.id', '=', 'order_details.order_id')
->where('order_details.seller_id', Auth::user()->id)
->where('orders.viewed', 0)
->select('orders.id')
->distinct()
->count();
@endphp
<li class=" col-lg-4">
    <div class="cls_menu_view">
        <a href="{{ route('orders.index') }}" class="cls_link {{ areActiveRoutes(['orders.index'])}}">
         <div class="icon"> <i class="las la-money-bill "></i></div>
         <span class="aiz-side-nav-text">{{ translate('Orders') }}</span>
         @if($orders > 0)<span class="badge badge-inline badge-lightblue">{{ $orders }}</span>@endif
     </a>
 </div>
</li>

@if ($refund_request_addon != null && $refund_request_addon->activated == 1)
<li class=" col-lg-4">
    <div class="cls_menu_view">
        <a href="{{ route('vendor_refund_request') }}" class="cls_link {{ areActiveRoutes(['vendor_refund_request','reason_show'])}}">
         <div class="icon"> <i class="las la-backward "></i></div>
         <span class="aiz-side-nav-text">{{ translate('Received Refund Request') }}</span>
     </a>
 </div>
</li>
@endif

@php
$review_count = DB::table('reviews')
->orderBy('code', 'desc')
->join('products', 'products.id', '=', 'reviews.product_id')
->where('products.user_id', Auth::user()->id)
->where('reviews.viewed', 0)
->select('reviews.id')
->distinct()
->count();
@endphp
<li class=" col-lg-4">
    <div class="cls_menu_view">
        <a href="{{ route('reviews.seller') }}" class="cls_link {{ areActiveRoutes(['reviews.seller'])}}">
         <div class="icon"> <i class="las la-star-half-alt "></i></div>
         <span class="aiz-side-nav-text">{{ translate('Product Reviews') }}</span>
         @if($review_count > 0)<span class="badge badge-inline badge-success">{{ $review_count }}</span>@endif
     </a>
 </div>
</li>

<li class=" col-lg-4">
    <div class="cls_menu_view">
        <a href="{{ route('shops.index') }}" class="cls_link {{ areActiveRoutes(['shops.index'])}}">
         <div class="icon"> <i class="las la-cog "></i></div>
         <span class="aiz-side-nav-text">{{ translate('Shop Setting') }}</span>
     </a>
 </div>
</li>

<li class=" col-lg-4">
    <div class="cls_menu_view">
        <a href="{{ route('payments.index') }}" class="cls_link {{ areActiveRoutes(['payments.index'])}}">
         <div class="icon"> <i class="las la-history "></i></div>
         <span class="aiz-side-nav-text">{{ translate('Payment History') }}</span>
     </a>
 </div>
</li>

<li class=" col-lg-4">
    <div class="cls_menu_view">
        <a href="{{ route('withdraw_requests.index') }}" class="cls_link {{ areActiveRoutes(['withdraw_requests.index'])}}">
         <div class="icon"> <i class="las la-money-bill-wave-alt "></i></div>
         <span class="aiz-side-nav-text">{{ translate('Money Withdraw') }}</span>
     </a>
 </div>
</li>

<li class=" col-lg-4">
    <div class="cls_menu_view">
        <a href="{{ route('commission-log.index') }}" class="cls_link">
         <div class="icon"> <i class="las la-file-alt "></i></div>
         <span class="aiz-side-nav-text">{{ translate('Commission History') }}</span>
     </a>
 </div>
</li>

@endif

@if (get_setting('conversation_system') == 1)
@php
$conversation = \App\Conversation::where('sender_id', Auth::user()->id)->where('sender_viewed', 0)->get();
@endphp

<li class=" col-lg-4">
    <div class="cls_menu_view">
        <a href="{{ route('conversations.index') }}" class="cls_link {{ areActiveRoutes(['conversations.index', 'conversations.show'])}}">
         <div class="icon"> <i class="las la-comment "></i></div>
         <span class="aiz-side-nav-text">{{ translate('Conversations') }}</span>
         @if (count($conversation) > 0)
         <span class="badge badge-success">({{ count($conversation) }})</span>
         @endif
     </a>
 </div>
</li>

@endif


@if (get_setting('wallet_system') == 1)
{{--
<li class=" col-lg-4">
    <div class="cls_menu_view">
        <a href="{{ route('wallet.index') }}" class="cls_link {{ areActiveRoutes(['wallet.index'])}}">
         <div class="icon"> <i class="las la-dollar-sign "></i></div>
         <span class="aiz-side-nav-text">{{translate('My Wallet')}}</span>
     </a>
 </div>
</li>
--}}
@endif

@if ($club_point_addon != null && $club_point_addon->activated == 1)
<li class=" col-lg-4">
    <div class="cls_menu_view">
        <a href="{{ route('earnng_point_for_user') }}" class="cls_link {{ areActiveRoutes(['earnng_point_for_user'])}}">
         <div class="icon"> <i class="las la-dollar-sign "></i></div>
         <span class="aiz-side-nav-text">{{translate('Earning Points')}}</span>
     </a>
 </div>
</li>
@endif

@if (\App\Addon::where('unique_identifier', 'affiliate_system')->first() != null && \App\Addon::where('unique_identifier', 'affiliate_system')->first()->activated && Auth::user()->affiliate_user != null && Auth::user()->affiliate_user->status)
<li class=" col-lg-4">
    <div class="cls_menu_view">
        <a href="javascript:void(0);" class="cls_link {{ areActiveRoutes(['affiliate.user.index', 'affiliate.payment_settings'])}}">
         <div class="icon"> <i class="las la-dollar-sign "></i></div>
         <span class="aiz-side-nav-text">{{ translate('Affiliate') }}</span>
         <span class="aiz-side-nav-arrow"></span>
     </a>
 </div>
 <ul class="aiz-side-nav-list level-2">
    <li class=" col-lg-4">
        <div class="cls_menu_view">
            <a href="{{ route('affiliate.user.index') }}" class="cls_link">
                <span class="aiz-side-nav-text">{{ translate('Affiliate System') }}</span>
            </a>
        </div>
    </li>
    <li class=" col-lg-4">
        <div class="cls_menu_view">
            <a href="{{ route('affiliate.user.payment_history') }}" class="cls_link">
                <span class="aiz-side-nav-text">{{ translate('Payment History') }}</span>
            </a>
        </div>
    </li>
    <li class=" col-lg-4">
        <div class="cls_menu_view">
            <a href="{{ route('affiliate.user.withdraw_request_history') }}" class="cls_link">
                <span class="aiz-side-nav-text">{{ translate('Withdraw request history') }}</span>
            </a>
        </div>
    </li>
</ul>
</li>
@endif

@php
$support_ticket = DB::table('tickets')
->where('client_viewed', 0)
->where('user_id', Auth::user()->id)
->count();
@endphp

<li class=" col-lg-4">
    <div class="cls_menu_view">
        <a href="{{ route('support_ticket.index') }}" class="cls_link {{ areActiveRoutes(['support_ticket.index'])}}">
         <div class="icon"> <i class="las la-atom "></i></div>
         <span class="aiz-side-nav-text">{{translate('Support Ticket')}}</span>
         @if($support_ticket > 0)<span class="badge badge-inline badge-success">{{ $support_ticket }}</span> @endif
     </a>
 </div>
</li>

@endif
<li class=" col-lg-4">
    <div class="cls_menu_view">
        <a href="{{ route('profile') }}" class="cls_link {{ areActiveRoutes(['profile'])}}">
         <div class="icon"> <i class="las la-user "></i></div>
         <span class="aiz-side-nav-text">{{translate('Manage Profile')}}</span>
     </a>
 </div>
</li>
@if(get_setting('subscription')=='1' && Auth::user()->user_type == 'seller')
<li class=" col-lg-4">
    <div class="cls_menu_view">
        <a href="{{ route('seller.subscription') }}" class="cls_link {{ areActiveRoutes(['subscription'])}}">
         <div class="icon"> <i class="las la-dollar-sign"></i></div>
         <span class="aiz-side-nav-text">{{translate('Subscription')}}</span>
     </a>
 </div>
</li>
@endif
@if(get_setting('ship_engine')=='1' && Auth::user()->user_type == 'seller')
<li class=" col-lg-4">
    <div class="cls_menu_view">
        <a href="{{ route('seller.show_carriers') }}" class="cls_link {{ areActiveRoutes(['show_carriers'])}}">
         <div class="icon"> <i class="las la-truck"></i></div>
         <span class="aiz-side-nav-text">{{translates('ship_eng_providers')}}</span>
     </a>
 </div>
</li>
@endif
@if(Auth::user()->user_type == 'seller' && Auth::user()->user_subscription)
<li class=" col-lg-4">
    <div class="cls_menu_view">
        <a href="{{ route('seller.subscription_history') }}" class="cls_link {{ areActiveRoutes(['subscription_history'])}}">
         <div class="icon"> <i class="la la-history"></i></div>
         <span class="aiz-side-nav-text">{{translate('Subscription History')}}</span>
     </a>
 </div>
</li>
@endif
</ul>
</div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection