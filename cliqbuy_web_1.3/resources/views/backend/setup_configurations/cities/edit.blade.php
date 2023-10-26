@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <h5 class="mb-0 h6">{{translate('City Information')}}</h5>
</div>

<div class="row">
  <div class="col-lg-8 mr-auto">
      <div class="card">
          <div class="card-body p-0">
            {{--
              <ul class="nav nav-tabs nav-fill border-light">
        				@foreach (\App\Language::all() as $key => $language)
        					<li class="nav-item">
        						<a class="nav-link text-reset @if ($language->code == $lang) active @else bg-soft-dark border-light border-left-0 @endif py-3" href="{{ route('cities.edit', ['id'=>$city->id, 'lang'=> $language->code] ) }}">
        							<img src="{{ static_asset('assets/img/flags/'.$language->code.'.png') }}" height="11" class="mr-1">
        							<span>{{ $language->name }}</span>
        						</a>
        					</li>
      	            @endforeach
        			</ul>
            --}}
              <form class="p-4" action="{{ route('cities.update', $city->id) }}" method="POST" enctype="multipart/form-data">
                  <input name="_method" type="hidden" value="PATCH">
                  <input type="hidden" name="lang" value="{{ $lang }}">
                  @csrf
                  <div class="form-group mb-3">
                      <label for="name">{{translate('Name')}}</label>
                      <input type="text" placeholder="{{translate('Name')}}" value="{{ $city->getTranslation('name', $lang) }}" name="name" class="form-control" required>
                      <span class="text-danger">{{ $errors->first('name') }}</span>
                  </div>

                  <div class="form-group">
                      <label for="country">{{translate('Country')}}</label>
                      <select class="select2 form-control aiz-selectpicker" name="country_id" data-toggle="select2" data-placeholder="Choose ..." data-live-search="true">
                          @foreach ($countries as $country)
                              <option value="{{ $country->id }}" @if($country->id == $city->country_id) selected @endif>{{ $country->name }}</option>
                          @endforeach
                      </select>
                  </div>
                  <div class="form-group">
                      <label for="state" class="control-label">{{ translate('State')}}</label>
                      <select class="form-control aiz-selectpicker" id="state" data-live-search="true" name="state_id" required>
                          @foreach (\App\State::get() as $key => $state)
                              <option value="{{ $state->id }}" @if($state->id == $state->state_id) selected @endif>{{ $state->getTranslation('name') }}</option>
                          @endforeach
                      </select>
                  </div>

                  <div class="form-group mb-3">
                      <label for="name">{{translate('Cost')}}</label>
                      <input type="number" min="0" step="0.01" placeholder="{{translate('Cost')}}" name="cost" class="form-control" value="{{ $city->cost }}" required>
                      <span class="text-danger">{{ $errors->first('cost') }}</span>
                  </div>


                  <div class="form-group mb-3 text-right">
                      <button type="submit" class="btn btn-primary">{{translate('Save')}}</button>
                  </div>
              </form>
          </div>
      </div>
  </div>
</div>

@endsection
@section('script')
    <script type="text/javascript">

        $(document).ready(function(){
            $('[name=country_id]').trigger('change');
        });

        $(document).on('change', '[name=country_id]', function() {
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
                        AIZ.plugins.bootstrapSelect('refresh');
                    }
                }
            });
        }

    </script>
@endsection