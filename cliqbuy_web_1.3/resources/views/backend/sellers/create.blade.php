@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3 row">
    <div class="col-sm-3">
        <h5 class="mb-0 h6">{{translate('Add New Merchant')}}</h5>
    </div>
    <div class="col-sm-9 text-right">
        <a href="{{ route('sellers.index') }}">{{translate('Back')}}</a>
    </div>
</div>

<div class="col-lg-6 mx-auto">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{translate('Merchant Information')}}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('sellers.store') }}" method="POST">
            	@csrf
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="name">{{translate('User Name')}}<span class="text-primary">*</span></label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{translate('User Name')}}" id="name" name="name" class="form-control" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="name">{{translate('Shop Name')}}<span class="text-primary">*</span></label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{translate('Shop Name')}}" id="shop_name" name="shop_name" class="form-control" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="name">{{translate('Address')}}<span class="text-primary">*</span></label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{translate('Address')}}" id="address" name="address" class="form-control" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label">{{ translate('Country')}} <span class="text-primary">*</span></label>
                    <div class="col-sm-9">
                        <select class="form-control aiz-selectpicker" data-live-search="true" data-placeholder="{{ translate('Select your country')}}" name="country" required>
                        <option value="">Select Country</option>
                            @foreach (\App\Country::where('status', 1)->get() as $key => $country)
                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                            @endforeach
                        </select>
                    </div>    
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label">{{ translate('State')}} <span class="text-primary">*</span></label>
                    <div class="col-sm-9">
                        <select class="form-control mb-3 aiz-selectpicker" data-live-search="true" name="state_id" required>

                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label">{{ translate('City')}} <span class="text-primary">*</span></label>
                    <div class="col-sm-9">
                        <select class="form-control mb-3 aiz-selectpicker" data-live-search="true" name="city" required>

                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label">{{ translate('Postal code')}} <span class="text-primary">*</span></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control mb-3" oninput="if(value.length>16)value=value.slice(0,16)" placeholder="{{ translate('Your Postal Code')}}" name="postal_code" value="" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label">{{ translate('Phone')}} <span class="text-primary">*</span></label>
                    <div class="col-sm-9">
                        <input type="number" oninput="if(value.length>16)value=value.slice(0,16)" class="form-control mb-3" placeholder="{{ translate('+880')}}" name="phone" value="" required>
                    </div>    
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="email">{{translate('Email Address')}}<span class="text-primary">*</span></label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{translate('Email Address')}}" id="email" name="email" class="form-control" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="password">{{translate('Password')}}<span class="text-primary">*</span></label>
                    <div class="col-sm-9">
                        <input type="password" placeholder="{{translate('Password')}}" id="password" name="password" class="form-control" required>
                    </div>
                </div>
                <div class="form-group mb-0 text-right">
                    <button type="submit" class="btn btn-primary">{{translate('Save')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('script')
<script type="text/javascript">
     $(document).on('change', '[name=state_id]', function() {
            var state_id = $(this).val();
            get_city(state_id);
        });

        function get_city(state_id) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('get-city')}}",
                type: 'POST',
                data: {
                    state_id: state_id
                },
                success: function (response) {
                    var obj = JSON.parse(response);
                    if(obj != '') {
                        $('[name="city"]').html(obj);
                        AIZ.plugins.bootstrapSelect('refresh');
                    }
                }
            });
        }

        $(document).ready(function(){
            $('[name=country]').trigger('change');
            $('[name=state_id]').trigger('change');
        });

        $(document).on('change', '[name=country]', function() {
            var country = $(this).val();
            get_state(country);
        });

        function get_state(country) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('get-state')}}",
                type: 'POST',
                data: {
                    country_id: country
                },
                success: function (response) {
                    var obj = JSON.parse(response);
                    if(obj != '') {
                        $('[name="state_id"]').html(obj);
                         $('[name=state_id]').trigger('change');
                        AIZ.plugins.bootstrapSelect('refresh');

                    }
                }
            });
        }


</script>
@endsection