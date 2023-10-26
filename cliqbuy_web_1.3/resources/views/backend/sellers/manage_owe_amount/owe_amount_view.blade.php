@extends('backend.layouts.app')

@section('content')
 
<div class="card">
  <form class="" id="sort_orders" action="" method="GET">
    <div class="card-header row gutters-5">
      <div class="text-center text-md-left">
        <h5 class="mb-md-0 h6">{{$merchant_data[0]->users->name}}</h5>
      </div>        
    </div>
    <div class="card-body">
      <div class="mb-4">
        @php
        $pending_count = $merchant_data->where('status','Pending')->count();
        $count = 1;
        @endphp
        @if($pending_count > 0)
        <div class="text-center text-md-left mb-3">
          <h5 class="mb-md-0 h6">Remaining Owe Amount</h5>
        </div>
        @foreach ($merchant_data as $owe_data)
        @if($owe_data->status == 'Pending')
          <p>{{$count++}}. {{$owe_data->orders->code}}</p>
        @endif
        @endforeach
        @endif
      </div>
      
      <div>
        @php
        $complete_count = $merchant_data->where('status','Completed')->count();
        $count = 1;
        @endphp
        @if($complete_count > 0)
        <div class="text-center text-md-left mb-3">
          <h5 class="mb-md-0 h6">Paid Owe Amount</h5>
        </div>
        @foreach ($merchant_data as $owe_data)
        @if($owe_data->status == 'Completed')
          <p>{{$count++}}. {{$owe_data->orders->code}}</p>
        @endif
        @endforeach
        @endif
      </div>
    </div>      
  </form>
</div>

<div class="aiz-pagination"></div>

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
