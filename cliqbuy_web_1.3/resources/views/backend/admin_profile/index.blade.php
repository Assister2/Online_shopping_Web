@extends('backend.layouts.app')

@section('content')

    <div class="col-lg-6  mx-auto" ng-controller="admin_profile">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{translate('Profile')}}</h5>
            </div>
            <div class="card-body">
                <form class="form-horizontal" action="{{ route('profile.update', Auth::user()->id) }}" method="POST" enctype="multipart/form-data">
                    <input name="_method" type="hidden" value="PATCH">
                	@csrf
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="name">{{translate('Name')}}</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" placeholder="{{translate('Name')}}" name="name" value="{{ Auth::user()->name }}" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="name">{{translate('Email')}}</label>
                        <div class="col-sm-9">
                            <input type="email" class="form-control" placeholder="{{translate('Email')}}" name="email" value="{{ Auth::user()->email }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="new_password">{{translate('New Password')}}</label>
                        <div class="col-sm-9">
                            <input type="password" class="form-control" placeholder="{{translate('New Password')}}" name="new_password">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="confirm_password">{{translate('Confirm Password')}}</label>
                        <div class="col-sm-9">
                            <input type="password" class="form-control" placeholder="{{translate('Confirm Password')}}" name="confirm_password">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" for="signinSrEmail">{{translate('Avatar')}} <small>(90x90)</small></label>
                        <div class="col-md-9">
                            <div class="input-group" data-toggle="aizuploader" data-type="image">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                                </div>
                                <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                <input type="hidden" name="avatar" class="selected-files" value="{{ Auth::user()->avatar_original }}">
                            </div>
                            <div class="file-preview box sm">
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-primary">{{translate('Save')}}</button>
                    </div>
                </form>
            </div>
        </div>

        @if(get_setting('ship_engine'))
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{translate('Address')}}</h5>
            </div>

            <div class="card-body">
                @php
                $address = Auth::user()->addresses->where('set_default', '1')->first();
                @endphp               
                <form class="form-horizontal address_form" ng-init="address_id='{{ $address ? $address->id : '0' }}'">
                    {{-- start of address section--}}     
                    <div class="row">
                        <div class="col-md-2">
                            <label>{{ translate('Address')}}</label>
                        </div>
                        <div class="col-md-10">
                            <input type="text" class="form-control mb-3" placeholder="{{ translate('Your Address')}}" name="address" required value="{{ $address ? $address->address : '' }}" id="address"></input>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <label>{{ translate('Country')}}</label>
                        </div>
                        <div class="col-md-10">
                            <div class="mb-3">
                                <select class="form-control aiz-selectpicker" data-live-search="true"  name="country" id="edit_country" required>
                                    @foreach (\App\Country::where('status', 1)->get() as $key => $country)
                                    <option value="{{ $country->id }}" @if($address && $address->country == $country->name) selected @endif>{{ $country->name }}</option>
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
                            @if($address)
                                <select class="form-control mb-3 aiz-selectpicker" data-live-search="true" name="state_id" id="edit_state" required>
                                    @foreach (\App\State::where('country_id',@\App\Country::where('name',$address->country)->first()->id)->get() as $key => $state)
                                        <option value="{{ $state->id }}" {{ $address->state == $state->name ? 'selected': ''}}>{{ $state->getTranslation('name') }}</option>
                                    @endforeach
                                </select>
                            @else
                                <select class="form-control mb-3 aiz-selectpicker" data-live-search="true" name="state_id" required>

                                </select>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-2">
                            <label>{{ translate('City')}}</label>
                        </div>
                        <div class="col-md-10">
                            @if($address)
                            <select class="form-control mb-3 aiz-selectpicker" data-live-search="true" name="city" id="edit_city" required>
                                @foreach (\App\City::where('state_id',@\App\State::where('name',$address->state)->first()->id)->get() as $key => $city)
                                    <option value="{{ $city->id }}" {{ $address->city == $city->name ? 'selected': ''}}>{{ $city->getTranslation('name') }}</option>
                                @endforeach
                            </select>
                            @else
                            <select class="form-control mb-3 aiz-selectpicker" data-live-search="true" name="city" required>

                            </select>
                            @endif
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-2">
                            <label>{{ translate('Postal code')}}</label>
                        </div>
                        <div class="col-md-10">
                            <input type="text" class="form-control mb-3" placeholder="{{ translate('Your Postal Code')}}" value="{{ $address ? $address->postal_code : '' }}" name="postal_code" value="" required id="postal_code">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <label>{{ translate('Phone')}}</label>
                        </div>
                        <div class="col-md-10">
                            <input type="text" class="form-control mb-3" placeholder="{{ translate('+880')}}" value="{{ $address ? $address->phone : '' }}" name="phone" value="" required id="phone_number">
                        </div>
                    </div>
                    <div class="form-group text-right">
                        <span class="text-danger ship_engine_msg d-none"></span>
                    </div>
                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-primary address-save">{{translate('Save')}}</button>
                    </div>
                    {{-- end of address section--}}
                </form>
            </div>
        </div>
        @endif
        
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
    </div>
@endsection
