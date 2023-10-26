@extends('frontend.layouts.user_panel')

@section('panel_content')
<div class="cls_bread">
    <ul>
        <li><a href="{{ route('mainmenu') }}">{{ translate('main') }}</a></li>
        <li><a>{{ translate('Manage Profile')}}</a></li>
    </ul>
</div>
    <div class="aiz-titlebar mt-2 mb-4">
      <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{ translate('Manage Profile') }}</h1>
        </div>
      </div>
    </div>
    <form action="{{ route('seller.profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <!-- Basic Info-->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{ translate('Basic Info')}}</h5>
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <label class="col-md-2 col-form-label">{{ translate('Your Name') }}</label>
                    <div class="col-md-10">
                        <input type="text" class="form-control" placeholder="{{ translate('Your Name') }}" required name="name" value="{{ Auth::user()->name }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-2 col-form-label">{{ translate('Your Phone') }}</label>
                    <div class="col-md-10">
                        <input type="number" oninput="if(value.length>16)value=value.slice(0,16)" class="form-control" placeholder="{{ translate('Your Phone')}}" name="phone" id="phone" value="{{ Auth::user()->phone }}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-form-label">{{ translate('Photo') }}</label>
                    <div class="col-md-10">
                        <div class="input-group" data-toggle="aizuploader" data-type="image">
                            <div class="input-group-prepend">
                                <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                            </div>
                            <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                            <input type="hidden" name="photo" value="{{ Auth::user()->avatar_original }}" class="selected-files">
                        </div>
                        <div class="file-preview box sm">
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-form-label">{{ translate('Your Password') }}</label>
                    <div class="col-md-10">
                        <input type="password" class="form-control" placeholder="{{ translate('New Password') }}" name="new_password">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-form-label">{{ translate('Confirm Password') }}</label>
                    <div class="col-md-10">
                        <input type="password" class="form-control" placeholder="{{ translate('Confirm Password') }}" name="confirm_password">
                    </div>
                </div>

            </div>
        </div>

        <!-- Address -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{ translate('Address')}}</h5>
            </div>
            <div class="card-body">
                <div class="row gutters-10">
                    @foreach (Auth::user()->addresses as $key => $address)
                        <div class="col-xl-4 col- col-sm-6">
                            <div class="border p-3 pr-5 rounded mb-3 position-relative" style="min-height:250px;">
                                <div class="form-group row">
                                    <div class="col-md-4 col-12">
                                        <span class="w-50 fw-600">{{ translate('Address') }}:</span>
                                    </div>
                                    <div class="col-md-8 col-12">
                                        <span class="ml-md-0 ml-2" align="center">{{ $address->address }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-4 col-12">
                                        <span class="w-50 fw-600">{{ translate('Postal Code') }}:</span>
                                    </div>
                                    <div class="col-md-8 col-12">
                                        <span class="ml-md-0 ml-2"  align="center">{{ $address->postal_code }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-4 col-12">
                                        <span class="w-50 fw-600">{{ translate('City') }}:</span>
                                    </div>
                                    <div class="col-md-8 col-12">
                                        <span class="ml-md-0 ml-2"  align="center">{{ $address->city }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-4 col-12">
                                        <span class="w-50 fw-600">{{ translate('State') }}:</span>
                                    </div>
                                    <div class="col-md-8 col-12">
                                        <span class="ml-md-0 ml-2"  align="center">{{ $address->state }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-4 col-12">
                                        <span class="w-50 fw-600">{{ translate('Country') }}:</span>
                                    </div>
                                    <div class="col-md-8 col-12">
                                        <span class="ml-md-0 ml-2"  align="center">{{ $address->country }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-4 col-12">
                                        <span class="w-50 fw-600">{{ translate('Phone') }}:</span>
                                    </div>
                                    <div class="col-md-8 col-12">
                                        <span class="ml-md-0 ml-2"  align="center">{{ $address->phone }}</span>
                                    </div>
                                </div>
                                @if ($address->set_default)
                                    <div class="arabic_left position-absolute right-0 bottom-0 pr-2 pb-3">
                                        <span class="badge badge-inline badge-primary">{{ translate('Default') }}</span>
                                    </div>
                                @endif
                                <div class="arabic_left dropdown position-absolute right-0 top-0">
                                    <button class="btn bg-gray px-2" type="button" data-toggle="dropdown">
                                        <i class="la la-ellipsis-v"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item" ng-click="edit_address('{{$address->id}}')">
                                            {{ translate('Edit') }}
                                        </a>
                                        @if (!$address->set_default)
                                            <a class="dropdown-item" href="{{ route('addresses.set_default', $address->id) }}">{{ translate('Make This Default') }}</a>
                                        @endif
                                        <a class="dropdown-item" href="{{ route('addresses.destroy', $address->id) }}">{{ translate('Delete') }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="col-xl-4 col- col-sm-6" ng-click="add_new_address()">
                        <div class="d-flex flex-column align-items-center justify-content-center border p-3 rounded mb-3 c-pointer text-center bg-light" style="min-height:250px;">
                            <i class="la la-plus la-2x address_border mb-3"></i>
                            <div class="alpha-7 address_font" style="color:#007185;">{{ translate('Add New Address') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment System -->
        <div class="card">
          <div class="card-header">
              <h5 class="mb-0 h6">{{ translate('Payment Setting')}}</h5>
          </div>
          <div class="card-body">
            <div class="row">
                <label class="col-md-3 col-form-label">{{ translate('Cash Payment') }}</label>
                <div class="col-md-9">
                    <label class="aiz-switch aiz-switch-success mb-3">
                        <input value="1" name="cash_on_delivery_status" type="checkbox" @if (Auth::user()->seller->cash_on_delivery_status == 1) checked @endif>
                        <span class="slider round"></span>
                    </label>
                </div>
            </div>
            <div class="row">
                <label class="col-md-3 col-form-label">{{ translate('Bank Payment') }}</label>
                <div class="col-md-9">
                    <label class="aiz-switch aiz-switch-success mb-3">
                        <input value="1" name="bank_payment_status" id = "bank_payment_status" onClick = "bankPaymentStatus()" type="checkbox" @if (Auth::user()->seller->bank_payment_status == 1) checked @endif>
                        <span class="slider round"></span>
                    </label>
                </div>
            </div>
            <div class="row">
                <label class="col-md-3 col-form-label bank_status">{{ translate('Bank Name') }}</label>
                <div class="col-md-9">
                    <input type="text" class="form-control mb-3 bank_status" placeholder="{{ translate('Bank Name')}}" value="{{ Auth::user()->seller->bank_name }}" name="bank_name">
                </div>
            </div>
            <div class="row">
                <label class="col-md-3 col-form-label bank_status">{{ translate('Bank Account Name') }}</label>
                <div class="col-md-9">
                    <input type="text" class="form-control mb-3 bank_status" placeholder="{{ translate('Bank Account Name')}}" value="{{ Auth::user()->seller->bank_acc_name }}" name="bank_acc_name">
                </div>
            </div>
            <div class="row">
                <label class="col-md-3 col-form-label bank_status">{{ translate('Bank Account Number') }}</label>
                <div class="col-md-9">
                    <input type="text" class="form-control mb-3 bank_status" placeholder="{{ translate('Bank Account Number')}}" value="{{ Auth::user()->seller->bank_acc_no }}" name="bank_acc_no">
                </div>
            </div>
            <div class="row">
                <label class="col-md-3 col-form-label bank_status">{{ translate('Bank Routing Number') }}</label>
                <div class="col-md-9">
                    <input type="number" lang="en" class="form-control mb-3 bank_status" placeholder="{{ translate('Bank Routing Number')}}" value="{{ Auth::user()->seller->bank_routing_no }}" name="bank_routing_no">
                </div>
            </div>
          </div>
      </div>
      <div class="form-group mb-0 text-right">
          <button type="submit" class="btn btn-primary">{{translate('Update Profile')}}</button>
      </div>
    </form>
    <br>

    <!-- Change Email -->
    <form action="{{ route('user.change.email') }}" method="POST">
        @csrf
        <div class="card">
          <div class="card-header">
              <h5 class="mb-0 h6">{{ translate('Change your email')}}</h5>
          </div>
          <div class="card-body">
              <div class="row">
                  <div class="col-md-2">
                      <label>{{ translate('Your Email') }}</label>
                  </div>
                  <div class="col-md-10">
                      <div class="input-group mb-3">
                        <input type="email" class="form-control" placeholder="{{ translate('Your Email')}}" name="email" required value="{{ Auth::user()->email }}" />
                        <div class="input-group-append">
                           <button type="button" class="btn btn-outline-secondary new-email-verification">
                               <span class="d-none loading">
                                   <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>{{ translate('sending_email') }}
                               </span>
                               <span class="default">{{ translate('Verify') }}</span>
                           </button>
                        </div>
                      </div>
                      <div class="form-group mb-0 text-right">
                          <button type="submit" class="btn btn-primary">{{translate('Update Email')}}</button>
                      </div>
                  </div>
              </div>
          </div>
        </div>

        {{-- delete account button start --}}

        <div class="Delete_account_btn text-right">
            <button type="button" class="btn btn-primary" disabled="disabled">{{ translate("Delete Account") }}</button>
        </div>
        {{-- delete account button end --}}
    </form>

@endsection

@section('modal')
<div class="modal fade" id="new-address-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ translate('new_address') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="form-default new-address-form" role="form" action="{{ route('addresses.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="p-3">
                        <div class="row">
                            <div class="col-md-2">
                                <label>{{ translate('Address')}}</label>
                            </div>
                            <div class="col-md-10">
                                <input type="text" class="form-control mb-3" placeholder="{{ translate('Your Address')}}" rows="2" name="address" required pattern=".*\S+.*">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <label>{{ translate('Country')}}</label>
                            </div>
                            <div class="col-md-10">
                                <div class="mb-3">
                                    <select class="form-control aiz-selectpicker" data-live-search="true" data-placeholder="{{ translate('select_your_country')}}" name="country" required>
                                        <option value="">{{translate('select_country')}}</option>
                                        @foreach (\App\Country::where('status', 1)->get() as $key => $country)
                                            <option value="{{ $country->id }}">{{ $country->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-2">
                                <label>{{ translate('State')}}</label>
                            </div>
                            <div class="col-md-10">
                                <select class="form-control mb-3 aiz-selectpicker" data-live-search="true" name="state_id" required>

                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-2">
                                <label>{{ translate('City')}}</label>
                            </div>
                            <div class="col-md-10">
                                <select class="form-control mb-3 aiz-selectpicker" data-live-search="true" name="city" required>

                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-2">
                                <label>{{ translate('Postal code')}}</label>
                            </div>
                            <div class="col-md-10">
                                <input type="text" class="form-control mb-3" placeholder="{{ translate('Your Postal Code')}}" name="postal_code" value="" required pattern=".*\S+.*">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <label>{{ translate('Phone')}}</label>
                            </div>
                            <div class="col-md-10">
                                <input type="number" oninput="if(value.length>16)value=value.slice(0,16)" class="form-control mb-3" placeholder="{{ translate('+880')}}" id="phone_number" name="phone" required>
                            </div>
                        </div>
                        <div class="form-group text-right">
                            <span class="text-danger ship_engine_msg d-none"></span>
                        </div>
                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-sm btn-primary address-save">{{translate('Save')}}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="edit-address-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ translate('address_edit') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body" id="edit_modal_body">

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ship_engine_address_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ translates('select_address') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="p-3">
                    <div class="form-group">
                        <input type="radio" id="original_address" name="final_address" value="original_address"> 
                        {{ translates('original_address') }}
                        <span class="d-block"> {{ translates('Address Line 1')}} : @{{ original_address.address_line1 }}</span>
                        <span class="d-block">{{ translates('City')}} : @{{ original_address.city_locality }}</span>
                        <span class="d-block">{{ translates('State')}} : @{{ selected_address.state_id }} </span>
                        <span class="d-block">{{ translates('Country')}} : @{{ selected_address.country }}</span>
                        <span class="d-block">{{ translates('Postal Code')}} : @{{ original_address.postal_code }}</span>
                    </div>

                    <div class="form-group">
                        <input type="radio" id="matched_address" name="final_address" value="matched_address" checked> 
                        {{ translates('matched_address') }}
                        <span class="d-block"> {{ translates('Address Line 1')}} : @{{ matched_address.address_line1 }}</span>
                        <span class="d-block"> {{ translates('City')}} : @{{ matched_address.city_locality }}</span>
                        <span class="d-block"> {{ translates('State')}} : @{{ selected_address.state_id }}</span>
                        <span class="d-block"> {{ translates('Country')}} : @{{ selected_address.country }}</span>
                        <span class="d-block"> {{ translates('Postal Code')}} : @{{ matched_address.postal_code }}</span>
                    </div>

                    <div class="form-group text-right">
                        <button class="btn btn-sm btn-primary add_address_button" ng-click="save_final_address()">{{translates('Save')}}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- delete account modal start --}}
<div class="modal fade" id="delete_account_modal" tabindex="-1" role="dialog" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteAccountModalLabel">Are you sure to delete your account?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body" id="delete_account_modal_body">
                <p>"You have ongoing orders in your account, if you delete the account you cannot track your order, however your order will reach you on time"</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary delete_account_confirm">Confirm</button>
                <button type="button" class="btn btn-secondary delete_account_cancel" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
{{-- delete account modal end --}}

@endsection

@if (get_setting('google_map') == 1)
    @include('frontend.partials.google_map')
@endif

@section('script')
    <script type="text/javascript">
        var google_map = {{ get_setting('google_map') }}; 
        var edit_address_url = '{{ route("addresses.edit", ":id") }}';

        $('.new-email-verification').on('click', function() {
            $(this).find('.loading').removeClass('d-none');
            $(this).find('.default').addClass('d-none');
            var email = $("input[name=email]").val();

            $.post('{{ route('user.new.verify') }}', {_token:'{{ csrf_token() }}', email: email}, function(data){
                data = JSON.parse(data);
                $('.default').removeClass('d-none');
                $('.loading').addClass('d-none');
                if(data.status == 2)
                    AIZ.plugins.notify('warning', data.message);
                else if(data.status == 1)
                    AIZ.plugins.notify('success', data.message);
                else
                    AIZ.plugins.notify('danger', data.message);
            });
        });


        $(document).ready(function(){
            var bank_payment_status = {!! json_encode(Auth::user()->seller->bank_payment_status) !!};
            if (bank_payment_status == 0) {
                $(".bank_status").css("display", "none");
            }
            else{
                $(".bank_status").attr('required', '');
                $(".bank_status").css("display", "block");
            }

        });
        function bankPaymentStatus(){
            var bank_status = $('input[name=bank_payment_status]:checked').length;
            console.log(bank_status);
            if (bank_status == 0) {
                $(".bank_status").css("display", "none");
            }
            else{
                $(".bank_status").attr('required', '');
                $(".bank_status").css("display", "block");
            }
        }

        $(document).ready(function() {

            $(document).on('click', '#deleteAccountBtn', function() {
                let delete_account_modal = $('#delete_account_modal');
                delete_account_modal.modal('show');

                $.post("{{ route('request_delete_account') }}", {_token:'{{ csrf_token() }}'}, function(data){
                    data = JSON.parse(data);
                    // $('.default').removeClass('d-none');
                    // $('.loading').addClass('d-none');
                    // if(data.status == 2)
                    //     AIZ.plugins.notify('warning', data.message);
                    // else if(data.status == 1)
                    //     AIZ.plugins.notify('success', data.message);
                    // else
                    //     AIZ.plugins.notify('danger', data.message);
                });
            });

        });
    </script>
@endsection
