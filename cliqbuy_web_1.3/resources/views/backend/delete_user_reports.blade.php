@extends('backend.layouts.app')

@section('content')

<div class="card">
    <form class="" id="sort_orders" action="" method="GET">
        <div class="card-header">
            <div>
                <h5 class="mb-md-0 h6 text-center text-md-left">{{ translate('Deleted Users Reports') }}</h5>
            </div>        
        </div>
        <div class="row px-4 py-3">
            <div class="col-md-6 col-lg-6 mt-2 mt-md-0">
                <h6>Users</h6>
                <select class="form-control aiz-selectpicker" name="user_name" id="user" onchange="sort_orders()">
                    <option value="">Select User</option>
                    @foreach($users as $user_info)
                        <option value="{{ $user_info->id }}" @isset($user_name) @if($user_name == $user_info->id) selected @endif @endisset>{{$user_info->name}} </option>
                        @endforeach
                </select>
            </div>
            <div class="col-md-6 col-lg-6 mt-3 mt-md-0">
                <h6>Category</h6>
                <select class="form-control aiz-selectpicker" name="category" id="category" onchange="sort_orders()">
                    <option value="users" @isset($category) @if($category == 'users') selected @endif @endisset>{{translate('User Details')}}</option>
                    <option value="orders" @isset($category) @if($category == 'orders') selected @endif @endisset>{{translate('Order Details')}}</option>
                    <option value="support_tickets" @isset($category) @if($category == 'support_tickets') selected @endif @endisset>{{translate('Support Tickets')}}</option>
                    <option value="product_queries" @isset($category) @if($category == 'product_queries') selected @endif @endisset>{{translate('Product Queries')}}</option>
                </select>
            </div>
        </div>
        <div class="col-auto">
            <div class="form-group mb-0">
              <button type="submit" class="btn btn-primary d-none">{{ translate('Filter') }}</button>
            </div>
        </div>
    </form>

    @if($user_name=='' && ($category=='' || $category=='users'))
        <div class="card-body">
            <div class="table-responsive">
                <table class="table aiz-table mb-0">
                    <thead>
                        <th>{{translate('Name')}}</th>
                        <th data-breakpoints="lg">{{translate('Email Address')}}</th>
                        <th data-breakpoints="lg">{{translate('Phone Number')}}</th>
                    </thead>
                    <tbody>
                        @foreach($results as $result)
                        <tr>
                            <td class="text">{{$result->name}}</td>
                            <td>{{$result->email}}</td>
                            <td>{{$result->phone}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
       
        </div>
        <div class="aiz-pagination">
            {{ $results->appends(request()->input())->links() }}
        </div>
    @endif
    @if($user_name!='' && $category=='users')
        @php
            $user_info = App\User::onlyTrashed()->where('id',$user_name)->first();
        @endphp
        <div class="card-body">
            <div class="table-responsive">
                <table class="table aiz-table mb-0">
                    <tbody>
                        <tr>
                            <td><b>{{translate('Name')}}</b></td>
                            <td class="text">{{$user_info->name}}</td>
                        </tr>
                        <tr>
                            <td><b>{{translate('Email')}}</b></td>
                            <td>{{$user_info->email}}</td>
                        </tr>
                        <tr>
                            <td><b>{{translate('Phone Number')}}</b></td>
                            <td>{{$user_info->phone}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    @endif
    @if($category=='orders')
        <div class="card-body">
            <div class="table-responsive">
                <table class="table aiz-table mb-0">
                    <thead>
                        <th>{{translate('Order Code')}}</th>
                        <th data-breakpoints="lg">{{translate('Number Of Products')}}</th>
                        <th data-breakpoints="lg">{{translate('Amount')}}</th>
                        <th data-breakpoints="lg">{{translate('Delivery Status')}}</th>
                        <th data-breakpoints="lg">{{translate('Payment Status')}}</th>
                        <th data-breakpoints="lg">{{translate('Options')}}</th>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td>{{$order->code}}</td>
                            <td>{{count($order->orderDetails)}}</td>
                            <td>{{single_price($order->grand_total)}}</td>
                            <td>{{translate($order->delivery_status)}}</td>
                            <td>{{translate($order->payment_status)}}</td>
                            <td>
                                <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{route('all_orders.show', encrypt($order->id))}}" title="{{ translate('View') }}">
                                <i class="las la-eye"></i>
                                </a>
                                <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{ route('invoice.download', $order->id) }}" title="{{ translate('Download Invoice') }}">
                                <i class="las la-download"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>       
        </div>
        <div class="aiz-pagination">
            {{ $orders->appends(request()->input())->links() }}
        </div>
    @endif
    @if($category=='support_tickets')
        <div class="card-body">
            <div class="table-responsive">
                <table class="table aiz-table mb-0">
                    <thead>
                        <th data-breakpoints="lg">{{ translate('Ticket ID') }}</th>
                        <th data-breakpoints="lg">{{ translate('Sending Date') }}</th>
                        <th>{{ translate('Subject') }}</th>
                        <th data-breakpoints="lg">{{ translate('User') }}</th>
                        <th data-breakpoints="lg">{{ translate('Status') }}</th>
                        <th data-breakpoints="lg">{{ translate('Last reply') }}</th>
                        <th>{{ translate('Options') }}</th>
                    </thead>
                    <tbody>
                        @foreach($support_tickets as $support_ticket)
                        <tr>
                            <td>{{$support_ticket->code}}</td>
                            <td>{{$support_ticket->created_at}}</td>
                            <td>{{$support_ticket->subject}}</td>
                            <td>{{@$support_ticket->user->name}}</td>
                            <td>{{translate($support_ticket->status)}}</td>
                            <td>{{(count($support_ticket->ticketreplies) > 0) ? $support_ticket->ticketreplies->last()->created_at : $support_ticket->created_at}}</td>
                            <td>
                                 <a href="{{route('support_ticket.admin_show', encrypt($support_ticket->id))}}" class="btn btn-soft-primary btn-icon btn-circle btn-sm" title="{{ translate('View Details') }}">
                                    <i class="las la-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>       
        </div>
        <div class="aiz-pagination">
            {{ $support_tickets->appends(request()->input())->links() }}
        </div>
    @endif
    @if($category=='product_queries')
        <div class="card-body">
            <div class="table-responsive">
                <table class="table aiz-table mb-0">
                    <thead>
                        <th>{{translate('Date')}}</th>
                        <th data-breakpoints="lg">{{translate('Title')}}</th>
                        <th data-breakpoints="lg">{{translate('Sender')}}</th>
                        <th data-breakpoints="lg">{{translate('Reciever')}}</th>
                        <th data-breakpoints="lg">{{translate('Options')}}</th>
                    </thead>
                    <tbody>
                        @foreach($product_queries as $product_query)
                        <tr>
                            <td>{{$product_query->created_at}}</td>
                            <td>{{$product_query->title}}</td>
                            <td>{{$product_query->sender->name}}</td>
                            <td>{{$product_query->receiver->name}}</td>
                            <td>
                                <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{route('conversations.admin_show', encrypt($product_query->id))}}" title="{{ translate('View') }}">
                                <i class="las la-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>       
        </div>
    </div>
    <div class="aiz-pagination">
        {{ $product_queries->appends(request()->input())->links() }}
    </div>
    @endif
@endsection
@section('script')
<script type="text/javascript">
     function sort_orders(el){
            $('#sort_orders').submit();
        }
</script>
  
@endsection