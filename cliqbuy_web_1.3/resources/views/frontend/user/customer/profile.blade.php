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

    <!-- Basic Info-->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('Basic Info')}}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('customer.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group row">
                    <label class="col-md-2 col-form-label">{{ translate('Your Name') }}</label>
                    <div class="col-md-10">
                        <input type="text" class="form-control" placeholder="{{ translate('Your Name') }}" name="name" required value="{{ Auth::user()->name }}">
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

                <div class="form-group mb-0 text-right">
                    <button type="submit" class="btn btn-primary">{{translate('Update Profile')}}</button>
                </div>
            </form>
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
                        <div class="border p-3 pr-5 rounded mb-3 position-relative">
                            <div class="form-group row">
                                <div class="col-md-4">
                                    <span class="w-50 fw-600">{{ translate('Address') }}:</span>
                                </div>
                                <div class="col-md-8">
                                    <span class="ml-md-0 ml-2">{{ $address->address }}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-4">
                                    <span class="w-50 fw-600">{{ translate('Postal Code') }}:</span>
                                </div>
                                <div class="col-md-8">
                                    <span class="ml-md-0 ml-2">{{ $address->postal_code }}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-4">
                                    <span class="w-50 fw-600">{{ translate('City') }}:</span>
                                </div>
                                <div class="col-md-8">
                                    <span class="ml-md-0 ml-2">{{ $address->city }}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-4">
                                    <span class="w-50 fw-600">{{ translate('State') }}:</span>
                                </div>
                                <div class="col-md-8">
                                    <span class="ml-md-0 ml-2">{{ $address->state }}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-4">
                                    <span class="w-50 fw-600">{{ translate('Country') }}:</span>
                                </div>
                                <div class="col-md-8">
                                    <span class="ml-md-0 ml-2">{{ $address->country }}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-4">
                                    <span class="w-50 fw-600">{{ translate('Phone') }}:</span>
                                </div>
                                <div class="col-md-8">
                                    <span class="ml-md-0 ml-2">{{ $address->phone }}</span>
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
                <div class="col-lg-3" ng-click="add_new_address()">
                    <div class="border p-3 rounded mb-3 c-pointer text-center bg-light">
                        <i class="la la-plus la-2x"></i>
                        <div class="alpha-7">{{ translate('Add New Address') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Email Change -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('Change your email')}}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('user.change.email') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-2">
                        <label>{{ translate('Your Email') }}</label>
                    </div>
                    <div class="col-md-10">
                        <div class="input-group mb-3">
                          <input type="email" class="form-control" placeholder="{{ translate('Your Email')}}" required name="email" value="{{ Auth::user()->email }}" />
                            <div class="input-group-append">
                                <button type="button" class="btn btn-outline-secondary new-email-verification">
                                    <span class="d-none loading">
                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                        {{ translate('sending_email') }}
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
            </form>
        </div>
    </div>

    {{-- delete button --}}

    <div class="Delete_account_btn text-right">
        <button id="deleteAccountBtn" type="button" class="btn btn-primary">{{translate('Delete Account')}}</button>
    </div>

    {{-- delete button end --}}

@endsection

@section('modal')
    <div class="modal fade" id="new-address-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ translate('new_address') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="form-default new-address-form" role="form" action="{{ route('addresses.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="p-3">
                            <div class="row">
                                <label class="col-md-2 col-form-label">{{ translate('Address') }}</label>
                                <div class="col-md-10">
                                    <input type="text" class="form-control  mb-3" placeholder="{{ translate('Your Address') }}" rows="1" name="address" required pattern=".*\S+.*">
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-2 col-form-label">{{ translate('Country') }}</label>
                                <div class="col-md-10">
                                    <div class="mb-3">
                                        <select class="form-control aiz-selectpicker" data-live-search="true" data-placeholder="{{translate('select_your_country')}}" name="country" required>
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

                            @if (get_setting('google_map') == 1)
                                <div class="row">
                                    <input id="searchInput" class="controls" type="text" placeholder="{{translate('Enter a location')}}">
                                    <div id="map"></div>
                                    <ul id="geoData">
                                        <li style="display: none;">Full Address: <span id="location"></span></li>
                                        <li style="display: none;">Postal Code: <span id="postal_code"></span></li>
                                        <li style="display: none;">Country: <span id="country"></span></li>
                                        <li style="display: none;">Latitude: <span id="lat"></span></li>
                                        <li style="display: none;">Longitude: <span id="lon"></span></li>
                                    </ul>
                                </div>

                                <div class="row">
                                    <div class="col-md-2" id="">
                                        <label for="exampleInputuname">Longitude</label>
                                    </div>
                                    <div class="col-md-10" id="">
                                        <input type="text" class="form-control mb-3" id="longitude" name="longitude" readonly="">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2" id="">
                                        <label for="exampleInputuname">Latitude</label>
                                    </div>
                                    <div class="col-md-10" id="">
                                        <input type="text" class="form-control mb-3" id="latitude" name="latitude" readonly="">
                                    </div>
                                </div>
                            @endif

                            <div class="row">
                                <label class="col-md-2 col-form-label">{{ translate('Postal code') }}</label>
                                <div class="col-md-10">
                                    <input type="text" class="form-control mb-3" placeholder="{{ translate('Your Postal Code')}}" name="postal_code" value="" required pattern=".*\S+.*">
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-2 col-form-label">{{ translate('Phone') }}</label>
                                <div class="col-md-10">
                                    <input type="number" oninput="if(value.length>16)value=value.slice(0,16)" class="form-control mb-3" placeholder="{{ translate('Your phone number')}}" name="phone" value="" id="phone_number" required>
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
    <div class="modal fade p-0" id="delete_account_modal" tabindex="-1" role="dialog" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteAccountModalLabel">{{ translate("Delete Account") }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body" id="delete_account_modal_body">
                    <p class="text-center">{{ translate("Loading") }}</p>
                </div>
                <div class="modal-footer">
                    <button id="delete_account_confirm" type="button" class="btn btn-primary delete_account_confirm">{{ translate("Confirm") }}</button>
                    <button id="delete_account_cancel" type="button" class="btn btn-secondary delete_account_cancel" data-dismiss="modal">{{ translate("Cancel") }}</button>
                </div>
            </div>
        </div>
    </div>
    {{-- delete account modal end --}}

    {{-- delete account OTP start --}}
    <div class="modal fade p-0" id="delete_acc_otp_modal" tabindex="-1" role="dialog" aria-labelledby="deleteAccountOTPModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteAccountOTPModalLabel">{{ translate("Enter OTP") }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body" id="delete_acc_otp_modal_body">
                    <p>
                        @lang('messages.front_end.otp_sent',['email' => protectedEmail(Auth::user()->email)])</p>
                    <div class="form-group">
                        <div class="col-md-6 col-12 pl-0">
                            <input type="text" name="delete_otp" id="delete_otp" class="form-control" value="" />
                            <p id="otp_error" class="mt-1"></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="delete_acc_otp_confirm" type="button" class="btn btn-primary delete_acc_otp_confirm">{{ translate("Confirm") }}</button>
                    <button id="delete_acc_otp_cancel" type="button" class="btn btn-secondary delete_acc_otp_cancel" data-dismiss="modal">{{ translate("Cancel") }}</button>
                </div>
            </div>
        </div>
    </div>
    {{-- delete account OTP end --}}
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

    $(document).ready(function() {
        let delete_account_modal = $('#delete_account_modal');
        let delete_acc_otp_modal = $('#delete_acc_otp_modal');

        $(document).on('click', '#deleteAccountBtn', function() {

            delete_account_modal.modal('show');

            $.post("{{ route('request_delete_account') }}", {_token:'{{ csrf_token() }}'}, function(data){

                $('#delete_account_modal_body').html('<p>'+data.status_message+'</p>');

            });
        });

        $(document).on('click', '#delete_account_confirm', function() {
            let _this = $(this);
            // _this.html('<span class="spinner spinner-border"></span><span>{{ translate("Please wait...") }}</span>');
            _this.attr('disabled', 'disabled');
            $.post("{{ route('otp_to_delete') }}", {_token:'{{ csrf_token() }}'}, function(data){

                // _this.html('{{ translate("Confirm") }}');
                _this.removeAttr('disabled');
                delete_account_modal.modal('hide');
                $('#delete_account_modal_body').html('<p class="text-center">Loading...</p>');
                if(data.status == 1) {
                    delete_acc_otp_modal.modal('show');
                } else {
                    AIZ.plugins.notify('danger', data.message);
                }

            });
        });

        $(document).on('click', '#delete_acc_otp_confirm', function() {
            let otp = $('#delete_otp').val();
            let _this = $(this);
            if(otp.trim() != '') {
                // _this.html('<span class="spinner spinner-border"></span><span>{{ translate("Please wait...") }}</span>');
                _this.attr('disabled', 'disabled');
                $.post("{{ route('confirm_otp_to_delete') }}", {_token:'{{ csrf_token() }}', otp: $('#delete_otp').val()}, function(data){

                    // _this.html('{{ translate("Confirm") }}');
                    _this.removeAttr('disabled');
                    if(data.status_code == 1) {
                        delete_acc_otp_modal.modal('hide');
                        window.location.reload();
                    } else {
                        $('#otp_error').html(data.status_message);
                    }

                });
            } else {
                $('#otp_error').html('<span class="text-danger">OTP is required</span>');
            }
        });

    });

</script>

@endsection
