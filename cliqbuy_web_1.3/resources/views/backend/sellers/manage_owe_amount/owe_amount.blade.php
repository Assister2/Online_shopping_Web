@extends('backend.layouts.app')

@section('content')

<div class="card">
    <form class="" id="sort_orders" action="" method="GET">
      <div class="card-header row gutters-5">
        <div class="col text-center text-md-left">
          <h5 class="mb-md-0 h6">Manage Owe Amount</h5>
        </div>
      </div>
    </form> 

    <div class="card-body">
        <table class="table aiz-table mb-0">
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Merchant Name</th>
                    <th data-breakpoints="md">Total Owe Amount</th>
                    <th data-breakpoints="md">Paid Owe Amount</th>
                    <th data-breakpoints="md">Remaining Owe Amount</th>
                    <th data-breakpoints="md">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($owe_amount as $owe_data)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$owe_data->users->name}}</td>
                    <td>{{format_price($owe_data->convert_total_amount)}}</td>
                    <td>@if($owe_data->convert_paid_amount == 0) 0 @else {{format_price($owe_data->convert_paid_amount)}}@endif</td>
                    <td>@if($owe_data->convert_remain_amount == 0) 0 @else {{format_price($owe_data->convert_remain_amount)}}@endif</td>
                    <td>
                        <a class="btn btn-soft-primary btn-icon btn-circle btn-sm mr-2" href="{{route('merchant_orders.show', $owe_data->seller_id)}}" title="{{ translate('View') }}"><i class="las la-eye"></i></a>@if($owe_data->remain_amount == 0)<span class="badge badge-inline badge-success align-text-bottom">Paid</span>@endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="aiz-pagination">

    {{ $owe_amount_paginate->appends(request()->input())->links() }}
    
</div>

@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection

@section('script')
    <script type="text/javascript">
        function sort_orders(el){
            $('#sort_orders').submit();
        }
    </script>
@endsection
