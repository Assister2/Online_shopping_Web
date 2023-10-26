@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{translate('All Merchant')}}</h1>
        </div>
        <div class="col-md-6 text-md-right">
            <a href="{{ route('sellers.create') }}" class="btn btn-circle btn-info">
                <span>{{translate('Add New Merchant')}}</span>
            </a>
        </div>
    </div>
</div>

<div class="card">
    <form class="" id="sort_sellers" action="" method="GET">
        <div class="card-header row gutters-5">
            <div class="col">
                <h5 class="mb-md-0 h6">{{ translate('Merchant') }}</h5>
            </div>
            
            <div class="dropdown mb-2 mb-md-0">
                <button class="btn border dropdown-toggle" type="button" data-toggle="dropdown">
                    {{translate('Bulk Action')}}
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="#" onclick="bulk_delete()">{{translate('Delete selection')}}</a>
                </div>
            </div>
            
            <div class="col-md-3 ml-auto">
                <select class="form-control aiz-selectpicker" name="approved_status" id="approved_status" onchange="sort_sellers()">
                    <option value="">{{translate('Filter by Approval')}}</option>
                    <option value="1"  @isset($approved) @if($approved == 'paid') selected @endif @endisset>{{translate('Approved')}}</option>
                    <option value="0"  @isset($approved) @if($approved == 'unpaid') selected @endif @endisset>{{translate('Non-Approved')}}</option>
                </select>
            </div>
            <div class="col-md-3">
                <div class="form-group mb-0">
                  <input type="text" class="form-control" id="search" name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset placeholder="{{ translate('Type name or email & Enter') }}">
                </div>
            </div>
        </div>
    
        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                <tr>
                    <!--<th data-breakpoints="lg">#</th>-->
                    <th>
                        <div class="form-group">
                            <div class="aiz-checkbox-inline">
                                <label class="aiz-checkbox">
                                    <input type="checkbox" class="check-all">
                                    <span class="aiz-square-check"></span>
                                </label>
                            </div>
                        </div>
                    </th>
                    <th>{{translate('Name')}}</th>
                    <th>{{translate('Shop Name')}}</th>
                    <th data-breakpoints="lg">{{translate('Phone')}}</th>
                    <th data-breakpoints="lg">{{translate('Email Address')}}</th>
                    <th data-breakpoints="lg">{{translate('Verification Info')}}</th>
                    <th data-breakpoints="lg">{{translate('Approval')}}</th>
                    <th data-breakpoints="lg">{{ translate('Num of Products') }}</th>
                    @if(get_setting('subscription')=='1')
                    <th data-breakpoints="lg">{{ translate('Subscription Plan') }}</th>
                    @endif
                    <th data-breakpoints="lg">{{ translate('Due to Merchant') }}</th>
                    <th width="10%">{{translate('Options')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($sellers as $key => $seller)
                    @if($seller->user != null && $seller->user->shop != null)
                        <tr>
                            <!--<td>{{ ($key+1) + ($sellers->currentPage() - 1)*$sellers->perPage() }}</td>-->
                            <td>
                                <div class="form-group">
                                    <div class="aiz-checkbox-inline">
                                        <label class="aiz-checkbox">
                                            <input type="checkbox" class="check-one" name="id[]" value="{{$seller->id}}">
                                            <span class="aiz-square-check"></span>
                                        </label>
                                    </div>
                                </div>
                            </td>
                            <td>{{$seller->user->name}}</td>
                            <td>@if($seller->user->banned == 1) <i class="fa fa-ban text-danger" aria-hidden="true"></i> @endif {{$seller->user->shop->name}}</td>
                            @if(isLiveEnv())
                                <td>{{protectedString($seller->user->shop->phone)}}</td>
                                <td>{{protectedString($seller->user->email)}}</td>
                            @else
                                <td>{{$seller->user->shop->phone}}</td>
                                <td>{{$seller->user->email}}</td>
                            @endif
                            <td>
                                @if ($seller->verification_info != null)
                                    <a href="{{ route('sellers.show_verification_request', $seller->id) }}">
                                      <span class="badge badge-inline badge-info">{{translate('Show')}}</span>
                                    </a>
                                @endif
                            </td>
                            <td>
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input onchange="update_approved(this)" value="{{ $seller->id }}" type="checkbox" <?php if($seller->verification_status == 1) echo "checked";?> >
                                    <span class="slider round"></span>
                                </label>
                            </td>
                            <td>{{ \App\Product::where('user_id', $seller->user->id)->count() }}</td>
                            @if(get_setting('subscription')=='1')
                            <td>
                                @if(get_setting('subscription')=='1'  && $seller->user->user_subscription)
                                {{$seller->user->user_subscription->name}}
                                @else
                                -
                                @endif
                            </td>
                                @endif
                            <td>
                                @if ($seller->admin_to_pay >= 0)
                                    {{ single_price($seller->admin_to_pay) }}
                                @else
                                    {{ single_price(abs($seller->admin_to_pay)) }} (Due to Admin)
                                @endif
                            </td>
                            <td>

                                <div class="dropdown" ng-controller='MerchantSubscription'>
                                    <button type="button" class="btn btn-sm btn-circle btn-soft-primary btn-icon dropdown-toggle no-arrow current_merchant_id" data-toggle="dropdown" href="javascript:void(0);" role="button" aria-haspopup="false" aria-expanded="false" data-user-id="{{$seller->user->id}}">
                                      <i class="las la-ellipsis-v"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-xs">
                                        @if(env('APP_ENV') !='live')
                                        <a href="#" onclick="show_seller_profile('{{$seller->id}}');"  class="dropdown-item">
                                          {{translate('Profile')}}
                                        </a>
                                        <a href="{{route('sellers.login', encrypt($seller->id))}}" class="dropdown-item">
                                          {{translate('Log in as this Merchant')}}
                                        </a>
                                        @endif
                                        @if(get_setting('subscription')=='1'&&$seller->user->user_subscription && count($seller->user->user_subscription->subscription_renewal))
                                         <a href="#" class="show_subscription_histroy dropdown-item"  >
                                          {{translate('Subscription History')}}
                                        </a>
                                        @endif
                                          @if(get_setting('subscription')=='1')
                                        <!-- <a  href="#" data-toggle="modal" data-target="#upgrade_plan" class="dropdown-item" data-href="{{route('sellers.destroy', $seller->id)}}" class="">
                                          Upgrade Plan
                                        </a> -->
                                         @endif
                                        @if(env('APP_ENV') !='live')
                                        <a href="#" onclick="show_seller_payment_modal('{{$seller->id}}');" class="dropdown-item">
                                          {{translate('Go to Payment')}}
                                        </a>
                                        @endif
                                        <a href="{{route('sellers.payment_history', encrypt($seller->id))}}" class="dropdown-item">
                                          {{translate('Payment History')}}
                                        </a>
                                        <a href="{{route('sellers.edit', encrypt($seller->id))}}" class="dropdown-item">
                                          {{translate('Edit')}}
                                        </a>
                                        @if($seller->user->banned != 1)
                                          <a href="#" onclick="confirm_ban('{{route('sellers.ban', $seller->id)}}');" class="dropdown-item">
                                            {{translate('Ban this Merchant')}}
                                            <i class="fa fa-ban text-danger" aria-hidden="true"></i>
                                          </a>
                                        @else
                                          <a href="#" onclick="confirm_unban('{{route('sellers.ban', $seller->id)}}');" class="dropdown-item">
                                            {{translate('Unban this Merchant')}}
                                            <i class="fa fa-check text-success" aria-hidden="true"></i>
                                          </a>
                                        @endif
                                        <a href="#" class="dropdown-item confirm-delete" data-href="{{route('sellers.destroy', $seller->id)}}" class="">
                                          {{translate('Delete')}}
                                        </a>


                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endif
                @endforeach
                </tbody>
            </table>
        </div>
    </form>
</div>
<div class="aiz-pagination">
  {{ $sellers->appends(request()->input())->links() }}
</div>

@endsection

@section('modal')
    <!-- Delete Modal -->
    @include('modals.delete_modal')

    <!-- Seller Profile Modal -->
    <div class="modal fade" id="profile_modal">
        <div class="modal-dialog">
            <div class="modal-content" id="profile-modal-content">

            </div>
        </div>
    </div>

    <!-- Seller Payment Modal -->
    <div class="modal fade" id="payment_modal">
        <div class="modal-dialog">
            <div class="modal-content" id="payment-modal-content">

            </div>
        </div>
    </div>

    <!-- Ban Seller Modal -->
    <div class="modal fade" id="confirm-ban">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h6">{{translate('Confirmation')}}</h5>
                    <button type="button" class="close" data-dismiss="modal">
                    </button>
                </div>
                <div class="modal-body">
                        <p>{{translate('Do you really want to ban this merchant?')}}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">{{translate('Cancel')}}</button>
                    <a class="btn btn-primary" id="confirmation">{{translate('Proceed!')}}</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Unban Seller Modal -->
    <div class="modal fade" id="confirm-unban">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title h6">{{translate('Confirmation')}}</h5>
                        <button type="button" class="close" data-dismiss="modal">
                        </button>
                    </div>
                    <div class="modal-body">
                            <p>{{translate('Do you really want to ban this merchant?')}}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal">{{translate('Cancel')}}</button>
                        <a class="btn btn-primary" id="confirmationunban">{{translate('Proceed!')}}</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal bd-example-modal-lg fade" id="upgrade_plan" ng-controller='MerchantSubscription' ng-init="custom_plan='No';is_free='No';currency_data={{json_encode($currency)}};duration_data={{json_encode(range(1,12))}};subscription_plan={{json_encode($subscription)}};planData=[]">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    
                    <div class="modal-header">
                        <h5 class="modal-title h6">Upgrade plan for the merchant</h5>
                        <button type="button" class="close" data-dismiss="modal">
                        </button>
                    </div>
                    <form  action="{{ route('user_custom_subscription') }}" method="POST" id="merchantSubscriptionForm">
                    @csrf
                    <div class="modal-body">
                       <div class="card-body">
                            <div class="form-group row">
                                <label class="col-sm-3 col-from-label" for="name">Plan Name</label>
                                <div class="col-sm-9">
                                    <select  class="form-control" name="plan_name" ng-change="applyPlanData(plan_name_option)" ng-model="plan_name_option">
                                        <option value="">Select</option>
                                       <option value="@{{plan}}" ng-repeat="plan in subscription_plan">@{{plan.name}}</option>
                                    </select>
                                    <input type="hidden" name="merchant_id"  id="current_merchant_id" value="">
                                    <input type="hidden" name="plan_name_id" value="@{{planData.id}}">
                                    <input type="hidden" name="plan_name_value" value="@{{planData.name}}">
                                </div>
                            </div>
                            <div class="form-group row" ng-show='planData.id'>
                                <label class="col-sm-3 col-from-label" for="Description">Description</label>
                                <div class="col-sm-9">
                                    <textarea name="description"  autocomplete="off" rows="8" class="form-control" placeholder="Description">@{{planData.description}}</textarea>
                                </div>
                            </div>
                            <div class="form-group row" ng-show='planData.id'>
                                <label class="col-sm-3 col-from-label" for="Tagline">Plan Tagline</label>
                                <div class="col-sm-9">
                                    <input type="text" placeholder="Plan Tagline" name="tagline" class="form-control"autocomplete="off" value="@{{planData.tagline}}">
                                </div>
                            </div>
                           <!--  <div class="form-group row" ng-show="planData.custom_plan=='No'">
                                <label class="col-sm-3 col-from-label">Is Custom Plan Type</label>
                                <div class="col-sm-3">
                                    <label class="aiz-switch aiz-switch-success mb-0" style="margin-top:5px;">
                                         <input ng-true-value="'Yes'" ng-false-value="'No'" ng-model="planData.custom_plan" type="checkbox" name="custom_plan" >
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div> -->
                            <div ng-show="planData.custom_plan=='No'">
                            <div class="form-group row">
                                <label class="col-sm-3 col-from-label" for="Number of Products">Number of Products</label>
                                <div class="col-sm-9">
                                    <input type="text" placeholder="Number of Products" ng-model="planData.no_of_product" name="no_of_products" class="form-control" autocomplete="off" >
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-from-label">Is Free</label>
                                <div class="col-sm-3">
                                    <label class="aiz-switch aiz-switch-success mb-0" style="margin-top:5px;">
                                        <input type="checkbox" name="is_free" ng-true-value="'Yes'" ng-false-value="'No'" ng-model="planData.is_free" ng-click="resetCustomPlan(is_free)">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group row" ng-show="planData.is_free=='No'">
                                <label class="col-sm-3 col-from-label" for="Price">Price</label>
                                <div class="col-sm-9">
                                    <input type="text" placeholder="Price" name="price" class="form-control"autocomplete="off" value="@{{planData.price}}">
                                </div>
                            </div>
                            <div class="form-group row" ng-show="planData.is_free=='No'">
                                <label class="col-sm-3 col-from-label" for="name">Currency</label>
                                <div class="col-sm-9">
                                   <select name="currency" class="form-control" ng-model="planData.currency">
                                   <option value="">Select</option>
                                     <option ng-repeat="value in currency_data" value="@{{value}}" >@{{value}}</option>
                                </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-from-label" for="name">Duration (Months)</label>
                                <div class="col-sm-9">
                                   <select name="duration" class="form-control " ng-model="planData.duration">
                                        <option value="">Select</option>
                                       <option value="@{{value}}" ng-repeat="value in duration_data">@{{value}}</option>
                                    </select>
                                </div>
                            </div> 
                            </div> 
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal">{{translate('Cancel')}}</button>

                       <button type="submit" class="btn btn-primary">{{translate('Save')}}</button>
                    </div>
                </form>
                </div>
            </div>
        </div> 



        <div class="modal bd-example-modal-lg fade" id="subscription_history" ng-controller='MerchantSubscription' ng-init="subscription_history=[]">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    
                    <div class="modal-header">
                        <h5 class="modal-title h6">{{translate('Subscription history')}}</h5>
                        <button type="button" class="close" data-dismiss="modal">
                        </button>
                    </div>
                   <div class="modal-body">
                
                   <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>{{translate('Date')}}</th>
                                <th>{{translate('Plan name')}}</th>
                                <th>{{translate('Amount')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                           
                            <tr ng-repeat="history in subscription_history">
                                <td>@{{history.created_at_date}}</td>
                                <td>@{{history.name}}</td>
                                <td>@{{history.currency}} @{{history.price}}</td>
                            </tr>
                                              
                        </tbody>
                    </table>
                   </div>
            </div>
                </div>
            </div>
        </div>
@endsection

@section('script')
    <script type="text/javascript">


        $(document).on("change", ".check-all", function() {
            if(this.checked) {
                // Iterate each checkbox
                $('.check-one:checkbox').each(function() {
                    this.checked = true;                        
                });
            } else {
                $('.check-one:checkbox').each(function() {
                    this.checked = false;                       
                });
            }
          
        });
        
        function show_seller_payment_modal(id){
            $.post('{{ route('sellers.payment_modal') }}',{_token:'{{ @csrf_token() }}', id:id}, function(data){
                $('#payment_modal #payment-modal-content').html(data);
                $('#payment_modal').modal('show', {backdrop: 'static'});
                $('.demo-select2-placeholder').select2();
            });
        }

        function show_seller_profile(id){
            $.post('{{ route('sellers.profile_modal') }}',{_token:'{{ @csrf_token() }}', id:id}, function(data){
                $('#profile_modal #profile-modal-content').html(data);
                $('#profile_modal').modal('show', {backdrop: 'static'});
            });
        }

        function update_approved(el){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('{{ route('sellers.approved') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                if(data == 1){
                    AIZ.plugins.notify('success', '{{ translate('Approved merchants updated successfully') }}');
                }
                else{
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }

        function sort_sellers(el){
            $('#sort_sellers').submit();
        }

        function confirm_ban(url)
        {
            $('#confirm-ban').modal('show', {backdrop: 'static'});
            document.getElementById('confirmation').setAttribute('href' , url);
        }

        function confirm_unban(url)
        {
            $('#confirm-unban').modal('show', {backdrop: 'static'});
            document.getElementById('confirmationunban').setAttribute('href' , url);
        }
        
        function bulk_delete() {
            var data = new FormData($('#sort_sellers')[0]);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('bulk-seller-delete')}}",
                type: 'POST',
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                success: function (response) {
                    if(response == 1) {
                        location.reload();
                    }
                }
            });
        }

    </script>
@endsection
