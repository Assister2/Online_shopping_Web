@extends('backend.layouts.app')

@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{ translate('All Plans') }}</h1>
        </div>
        <div class="col-md-6 text-md-right">
            <a href="{{ route('subscription.add') }}" class="btn btn-circle btn-info">
                <span>{{ translate('Add New Plan') }}</span>
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header row gutters-5">
        <div class="col text-center text-md-left">
            <h5 class="mb-md-0 h6">{{ translate('Subscription Plans') }}</h5>
        </div>
        <form class="" id="sort_subscription" action="" method="GET">
                    <!-- <div class="card-header"> -->
                        <!-- <h5 class="mb-0 h6">{{ translate('Colors') }}</h5> -->
                        <!-- <div class="col-md-5"> -->
                            <div class="form-group mb-0">
                                <input type="text" class="form-control form-control-sm" id="search" name="search"
                                    @isset($sort_search) value="{{ $sort_search }}" @endisset
                                    placeholder="{{ translate('Type subscription name & Enter') }}">
                            </div>
                        <!-- </div> -->
                    <!-- </div> -->
                </form>
    </div>

    <div class="card-body">
        <table class="table aiz-table mb-0">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Duration (Months)</th>
                    <th>Number of Products</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th class="text-right">{{translate('Options')}}</th>
                </tr>
            </thead>
            <tbody>
                 @foreach($subscription as $key => $plan)
                    <tr>
                        <td>{{$plan->id}}</td>
                        <td>{{$plan->name}}</td>
                        <td>{{$plan->duration?$plan->duration.' '.($plan->duration>1?translate('Months'):translate('Month')):'-'}} </td>
                        <td>{{$plan->no_of_product>0?$plan->no_of_product:'-'}}</td>
                        <td>{{$plan->price>0?$plan->currency.' '.$plan->price:'-'}}</td>
                        <td>{{$plan->status}}</td>
                        
                        <td class="text-right">
                            <a href="{{route('subscription.edit',['id'=>$plan->id])}}" class="btn btn-soft-primary btn-icon btn-circle btn-sm"  title="{{ translate('Edit') }}">
                                <i class="las la-edit"></i>
                            </a>
                            <!-- href="{{route('subscription.delete',['id'=>$plan->id])}}" -->
                            <a  href="javascript:void(0);" data-href="{{route('subscription.delete',['id'=>$plan->id])}}" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete " title="{{ translate('Delete') }}">
                                <i class="las la-trash"></i>
                            </a>
                        </td>
                    </tr>
                 @endforeach
            </tbody>
        </table>        
    </div>
</div>
<div class="aiz-pagination">
   {{ $subscription->appends(request()->input())->links() }}
</div>

@endsection
@section('modal')
    @include('modals.delete_modal')
@endsection