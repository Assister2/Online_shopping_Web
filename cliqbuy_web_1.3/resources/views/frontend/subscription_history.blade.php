@extends('frontend.layouts.user_panel')

@section('panel_content')
<div class="cls_bread">
    <ul>
        <li><a href="{{ route('mainmenu') }}">{{ translate('main') }}</a></li>
        <li><a>{{translate('subscription_history')}}</a></li>
    </ul>
</div>
    <div class="aiz-titlebar mt-2 mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{translate('subscription_history')}}</h1>
            </div>
        </div>
    </div>

    <div class="row gutters-10">
        <div class="col-md-4 mb-3">
            <div class="subscrip_list sub_history mb-3">
                <div class="inner">
                    <h3>{{translate('Active Subscription plan')}}</h3>
                    @if($subscription_history && $subscription_history->status=='Active')
                    
                    <h5>{{$subscription_history->name}}</h5>
                    <div>
                         <div class="d-flex align-items-center ">
                        <p class="mr-auto">{{$subscription_history->no_of_product.' '. translate('Products')}}</p>
                        <p class="ml-auto"><strong>{{format_price(currencyConvert($subscription_history->currency,'',$subscription_history->price))}} / {{$subscription_history->duration}} {{$subscription_history->duration>1?translate('Months'):translate('Month')}}</strong></p>
                        </div>
                        @if($subscription_history->plan_type=='Paid')
                        <p>{{translate('Upcoming Renewal Date')}} : {{$subscription_history->next_renewel_date}}</p>
                        @endif
                        <p>{{$active_product_count}} {{translate('Active products')}}</p>
                        
                        @if(get_setting('subscription')=='1')
                        <div class="text-center mt-4">
                            <a href="{{route('seller.subscription')}}" class="btn btn-primary d-block">{{translate('Change Plan')}}</a>
                        </div>
                        @endif
                    </div>
                    @else
                    <div class="d-flex align-items-center my-4">
                        <i class="la la-frown-o la-3x"></i>
                        <div class="d-block ml-2" style="font-size: 18px;">{{ translate('No Active Subscription Plan Found')}}</div>    
                    </div>
                     @endif
                       
                </div>

            </div>
            @if($upgrade_plan && get_setting('subscription')=='1')
             <div class="subscrip_list sub_history mb-3">
                <div class="inner">
                    <h3>{{translate('Upgrade Plan')}}</h3>
                    <h5>@php $subscriptio_trans = \App\Models\SubscriptionPlan::find($upgrade_plan->subscription_plan_id); @endphp
                        {{ $subscriptio_trans->getTranslation('name')}}</h5>
                    <div>
                         <div class="d-flex align-items-center ">
                        <p class="mr-auto">{{$upgrade_plan->no_of_product.' '. translate('Products')}}</p>
                        <p class="ml-auto"><strong>{{format_price(currencyConvert($upgrade_plan->currency,'',$upgrade_plan->price))}} / {{$upgrade_plan->duration}} {{$upgrade_plan->duration >1?translate('Months'):translate('Month')}}</strong></p>
                        </div>
                        
                        @if(get_setting('subscription')=='1')
                        <div class="text-center mt-4">
                            <a href="{{route('seller.payment',['id'=>$upgrade_plan->id,'type'=>'upgrade'])}}" class="btn btn-primary d-block">{{translate('Accept')}}</a>
                        </div>
                        @endif
                    </div>
                  
                       
                </div>

            </div>
        @endif
        </div>

       

        <div class="col-md-8 mb-3">
            <div class="subscrip_list sub_history" style="height: 100%;">
                 <h3>{{ translate('Subscription history')}}</h3>
                   <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>{{ translate('Date')}}</th>
                                <th>{{ translate('Plan name')}}</th>
                                <th>{{ translate('Amount')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($subscription_history && @count($subscription_history->subscription_renewal))
                            @foreach($subscription_history->subscription_renewal as $history)
                            <tr>
                                <td>{{date('d/m/Y',strtotime($history->created_at))}}</td>
                                <td>                                    
                                    {{$history->name}}</td>
                                <td>{{format_price(currencyConvert($history->currency,'',$history->price))}}</td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="3">
                                    <div class="d-flex align-items-center justify-content-center">           
                                        <i class="la la-frown-o la-3x"></i>
                                        <div class="d-block sub-title ml-2">{{ translate('No Result Found')}}</div>    
                                     </div>
                                 </td>
                            </tr>
                            @endif                           
                        </tbody>
                    </table>
                   </div>
            </div>
        </div>
    </div>
@endsection
