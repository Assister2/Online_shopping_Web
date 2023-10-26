<form class="form-default edit-address-form" id="{{ $address_data->id }}">
    <div class="p-3">
        <div class="row">
            <div class="col-md-2">
                <label>{{ translate('Address')}}</label>
            </div>
            <div class="col-md-10">
                <input class="form-control mb-3" placeholder="{{ translate('Your Address')}}" rows="2" name="address" required value="{{ $address_data->address }}" id="address_{{ $address_data->id }}"></input>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2">
                <label>{{ translate('Country')}}</label>
            </div>
            <div class="col-md-10">
                <div class="mb-3">
                    <select class="form-control aiz-selectpicker" data-live-search="true"  name="country" id="edit_country_{{ $address_data->id }}" required>
                        @foreach (\App\Country::where('status', 1)->get() as $key => $country)
                        <option value="{{ $country->id }}" @if($address_data->country == $country->name) selected @endif>{{ $country->name }}</option>
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
                <select class="form-control mb-3 aiz-selectpicker" data-live-search="true" name="state_id" id="edit_state_{{ $address_data->id }}" required>
                    @foreach (\App\State::where('country_id',@\App\Country::where('name',$address_data->country)->first()->id)->get() as $key => $state)
                        <option value="{{ $state->id }}" {{ $address_data->state == $state->name ? 'selected': ''}}>{{ $state->getTranslation('name') }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col-md-2">
                <label>{{ translate('City')}}</label>
            </div>
            <div class="col-md-10">
                <select class="form-control mb-3 aiz-selectpicker" data-live-search="true" name="city" id="edit_city_{{ $address_data->id }}" required>
                    @foreach (\App\City::where('state_id',@\App\State::where('name',$address_data->state)->first()->id)->get() as $key => $city)
                        <option value="{{ $city->id }}" {{ $address_data->city == $city->name ? 'selected': ''}}>{{ $city->getTranslation('name') }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        
        @if (get_setting('google_map') == 1)
            <div class="row">
                <input id="edit_searchInput" class="controls" type="text" placeholder="Enter a location">
                <div id="edit_map"></div>
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
                    <input type="text" class="form-control mb-3" id="edit_longitude" name="longitude" value="{{ $address_data->longitude }}" readonly="">
                </div>
            </div>
            <div class="row">
                <div class="col-md-2" id="">
                    <label for="exampleInputuname">Latitude</label>
                </div>
                <div class="col-md-10" id="">
                    <input type="text" class="form-control mb-3" id="edit_latitude" name="latitude" value="{{ $address_data->latitude }}" readonly="">
                </div>
            </div>
        @endif
        
        <div class="row">
            <div class="col-md-2">
                <label>{{ translate('Postal code')}}</label>
            </div>
            <div class="col-md-10">
                <input type="text" class="form-control mb-3" placeholder="{{ translate('Your Postal Code')}}" value="{{ $address_data->postal_code }}" name="postal_code" value="" required id="postal_code_{{ $address_data->id }}">
            </div>
        </div>
        <div class="row">
            <div class="col-md-2">
                <label>{{ translate('Phone')}}</label>
            </div>
            <div class="col-md-10">
                <input type="text" class="form-control mb-3" placeholder="{{ translate('+880')}}" value="{{ $address_data->phone }}" name="phone" value="" required id="phone_{{ $address_data->id }}">
            </div>
        </div>
        <div class="form-group text-right">
            <span class="text-danger ship_engine_msg d-none"></span>
        </div>
        <div class="form-group text-right">
            <button class="btn btn-sm btn-primary edit-address-save" type="submit" id="edit_submit_{{ $address_data->id }}">{{translate('Save')}}</button>
        </div>
    </div>
</form>