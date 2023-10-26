@extends($blade)

@section($section)
	@if($auth_user_type != 'admin')
	<div class="cls_bread">
	    <ul>
	        <li><a href="{{ route('mainmenu') }}">{{ translate('main') }}</a></li>
	        <li><a>{{ translates('shipping_providers')}}</a></li>
	    </ul>
	</div>
	@endif
	<section ng-controller="carriers">
	    <div class="card">
			<div class="card-header">
	            <h5 class="mb-0 h6">{{ translates('shipping_providers')}}</h5>
	        </div>

	        <div class="card-body">
				<div class="form-group">
					<div class="col-md-8 mx-auto">
						@foreach($ship_engines as $engines)
						{{-- temporarily png images only added --}}
						<div class="d-flex justify-content-between align-items-center">
							<div>
								<img src="{{ static_asset('img/'. $engines->name. '.png') }}" placeholder="{{ $engines->name }}" width="225" height="225">
							</div>
							<div>
								@if(check_if_user_connected_to_carrier($engines->name, Auth::id()))
									<button class="btn btn-primary" ng-click="disconnect_carriers('{{ $engines->name }}')"> {{ translates('disconnect') }} </button>
								@else
									<button class="btn btn-primary" ng-click="connect_carriers('{{ $engines->name }}')"> {{ translates('connect') }} </button>
								@endif
							</div>
						</div>
						@endforeach
	                </div>
	            </div>
	        </div>
		</div>
	</section>
@endsection