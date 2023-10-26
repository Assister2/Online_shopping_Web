@extends('frontend.layouts.user_panel')

@section('panel_content')
<div class="cls_bread">
    <ul>
        <li><a href="{{ route('mainmenu') }}">{{ translate('main') }}</a></li>
        <li><a>{{ translate('subscription') }}</a></li>
    </ul>
</div>
    <div class="aiz-titlebar mt-2 mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('subscription') }}</h1>
            </div>
        </div>
    </div>

    <div class="row gutters-10">
    @foreach($subscription as $key=>$plan)
      @php $subscriptio_trans = \App\Models\SubscriptionPlan::find($plan->id); @endphp
        <div class="col-md-4 mb-3">
            <div class="subscrip_list">
                <div class="inner">
                    <h3>{{ $subscriptio_trans->getTranslation('name')}}</h3>
                    <p>{{ $subscriptio_trans->getTranslation('tagline')}}</p>

                    @if($plan->is_free=='Yes')
                    <h4>{{format_price(currencyConvert($plan->currency,'',0))}} / {{$plan->duration}} {{ $plan->duration>1?translate('Months'):translate('Month')}}</h4>
                    @elseif($plan->custom_plan=='Yes')
                    <h4>{{translate('Custom pricing')}}</h4>
                    @else
                    <h4>{{format_price(currencyConvert($plan->currency,'',$plan->price))}} / {{$plan->duration}} {{ $plan->duration>1?translate('Months'):translate('Month')}}</h4>
                    @endif
                    <ul>
                        <li>{{ $subscriptio_trans->getTranslation('description')}}</li>
                    </ul>
                    <div class="text-center">
                        @if($plan->custom_plan=='Yes')
                        <a href="{{ route('contact_us')}}" class="btn btn-primary d-block {{!$user_subscription || ($user_subscription && $user_subscription->subscription_plan_id!=$plan->id) ? '' : 'disabled'}}">{{$plan->is_free=='Yes'? translate('Get Free') :($plan->custom_plan=='Yes'? translate('contact_us') : translate('Get Premium'))}}</a>
                        @else
                        <a href="{{ route('seller.payment',['id'=>$plan->id]) }}" class="btn btn-primary d-block {{!$user_subscription || ($user_subscription && $user_subscription->subscription_plan_id!=$plan->id) ? '' : 'disabled'}}">{{$plan->is_free=='Yes'? translate('Get Free'):($plan->custom_plan=='Yes'? translate('contact_us') : translate('Get Premium'))}}</a>
                        @endif
                </div>
                </div>

            </div>
        </div>
        @endforeach
        @if(count($subscription)<1)
         <div class="col-md-4">           
            <i class="la la-frown-o mb-2 la-3x"></i>
            <div class="d-block sub-title mb-2">{{ translate('No Package Found')}}</div>    
        </div>
        @endif
    </div>




@endsection
