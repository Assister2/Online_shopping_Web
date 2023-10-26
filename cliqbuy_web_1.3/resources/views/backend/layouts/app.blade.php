<!doctype html>
@if(\App\Language::where('code', Session::get('locale', Config::get('app.locale')))->first()->rtl == 1)
<html dir="rtl" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@else
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@endif
<head>
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta name="app-url" content="{{ getBaseURL() }}">
	<meta name="file-base-url" content="{{ getFileBaseURL() }}">

	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<!-- Favicon -->
	<link rel="icon" href="{{ uploaded_asset(get_setting('site_icon')) }}">
	<title>{{ get_setting('website_name').' | '.get_setting('site_motto') }}</title>

	<!-- google font -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700">

	<!-- aiz core css -->
	<link rel="stylesheet" href="{{ static_asset('assets/css/vendors.css') }}">
    @if(\App\Language::where('code', Session::get('locale', Config::get('app.locale')))->first()->rtl == 1)
    <link rel="stylesheet" href="{{ static_asset('assets/css/bootstrap-rtl.min.css') }}">
    @endif
	<link rel="stylesheet" href="{{ static_asset('assets/css/aiz-core.css') }}">
	<link rel="stylesheet" href="{{ static_asset('assets/css/custom-admin.css') }}">

    <style>
        body {
            font-size: 12px;
        }
    </style>
	<script>
    	var AIZ = AIZ || {};
        AIZ.local = {
            nothing_selected: '{{ translate('Nothing selected') }}',
            nothing_found: '{{ translate('Nothing found') }}',
            choose_file: '{{ translate('Choose file') }}',
            file_selected: '{{ translate('File selected') }}',
            files_selected: '{{ translate('Files selected') }}',
            add_more_files: '{{ translate('add_more_files') }}',
            add_more: '{{ translate('add_more') }}',
            adding_more_files: '{{ translate('Adding more files') }}',
            drop_files_here_paste_or: 'Drop files here, paste or',
            browse: '{{ translate('Browse') }}',
            upload_complete: '{{ translate('Upload complete') }}',
            upload_paused: '{{ translate('Upload paused') }}',
            resume_upload: '{{ translate('Resume upload') }}',
            pause_upload: '{{ translate('Pause upload') }}',
            retry_upload: '{{ translate('Retry upload') }}',
            cancel_upload: '{{ translate('Cancel upload') }}',
            uploading: '{{ translate('Uploading') }}',
            processing: '{{ translate('Processing') }}',
            complete: '{{ translate('Complete') }}',
            file: '{{ translate('File') }}',
            files: '{{ translate('Files') }}',
            apply: '{{ translate('Apply') }}',
            clear: '{{ translate('Clear') }}',
            Su: '{{ translate('Su') }}',
            Mo: '{{ translate('Mo') }}',
            Tu: '{{ translate('Tu') }}',
            We: '{{ translate('We') }}',
            Th: '{{ translate('Th') }}',
            Fr: '{{ translate('Fr') }}',
            Sa: '{{ translate('Sa') }}',
            january: '{{ translate('January') }}',
            february: '{{ translate('February') }}',
            march: '{{ translate('March') }}',
            april: '{{ translate('April') }}',
            may: '{{ translate('May') }}',
            june: '{{ translate('June') }}',
            july: '{{ translate('July') }}',
            august: '{{ translate('August') }}',
            september: '{{ translate('September') }}',
            october: '{{ translate('October') }}',
            november: '{{ translate('November') }}',
            december: '{{ translate('December') }}',
            left: '{{ translate('left') }}',
            of:'{{ translate('of') }}',
            filesuploaded:'{{ translate('files_uploaded') }}',
            fileuploaded:'{{ translate('file_uploaded') }}',
            retry:'{{ translate('retry') }}',
            invalid_address:'{{ translates('invalid_address') }}',
        }
	</script>

</head>

<body class="admin_body" ng-app="APP" ng-controller="appController" ng-cloak>
	<div class="aiz-main-wrapper">
        @include('backend.inc.admin_sidenav')
		<div class="aiz-content-wrapper">
            @include('backend.inc.admin_nav')
			<div class="aiz-main-content" >
				<div class="px-15px px-lg-25px" >
					
                    @yield('content')
				</div>
				<div class="bg-white text-center py-3 px-15px px-lg-25px mt-auto">
					<p class="mb-0">&copy; {{ get_setting('site_name') }} v{{ get_setting('current_version') }}</p>
				</div>
			</div><!-- .aiz-main-content -->
		</div><!-- .aiz-content-wrapper -->
	</div><!-- .aiz-main-wrapper -->

    @yield('modal')

    @if(\Route::currentRouteName() == 'inhouse_orders.show' || \Route::currentRouteName() == 'seller_orders.show' || \Route::currentRouteName() == 'all_orders.show')
    	<div class="modal fade" id="ship_engine_label" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
	        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
	            <div class="modal-content">
	            <div class="modal-header">
	                <h5 class="modal-title strong-600 heading-5">{{ translates('Shipping')}}</h5>
	                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	                    <span aria-hidden="true">&times;</span>
	                </button>
	            </div>
	            <div class="modal-body ship_engine_label_body gry-bg px-3 pt-0">
	                <div class="mt-4">
	                    <div class="text-center">
	                        <h5 class="fw-600">{{ translates('manual_shipment') }}</h5>
	                    </div>
	                    <div class="">
	                        <form action="{{ route('manual_tracking_number') }}" method="POST" id="manual_tracking_number">
	                            <div class="form-group">
	                                <div class="d-flex">
	                                    <div id="carrier_name_container">
	                                        <select name="carrier_name" id="carrier_name_manual" class="form-control w-auto" data-error-placement="container" data-error-container="#carrier_name_container" required>
	                                            @foreach($shipengines as $engines)
	                                                <option value=""> Select </option>
	                                                <option value="{{ $engines }}"> {{ ucwords(str_replace('_', ' ', $engines)) }} </option>
	                                            @endforeach
	                                        </select>
	                                    </div>

	                                    <div id="tracking_number_container" class="w-100">
	                                        <input type="text" placeholder="{{translates('tracking_number')}}" value="" name="tracking_number" class="form-control ml-2" required data-error-placement="container" data-error-container="#tracking_number_container">
	                                    </div>
	                                </div>
	                                <span class="text-danger">{{ $errors->first('name') }}</span>
	                            </div>

	                            <div class="mb-3 text-center">
	                                <button type="submit" class="btn btn-primary">{{translate('Submit')}}</button>
	                            </div>
	                        </form>
	                        
	                        <hr>

	                        <form action="{{ route('shipping_tracking_number') }}" method="POST" id="shipping_tracking_number">
	                        <div class="address_section">
	                            <div class="row form-group">
	                                <div class="col-md-3">
	                                    <label>{{ translates('shipping_method') }}</label>
	                                </div>
	                                <div class="col-md-6">
	                                    <select name="carrier_name" id="" class="form-control" required>
	                                        <option value=""> Select </option>
	                                        @foreach($shipengines as $engines)
	                                            <option value="{{ $engines }}"> {{ ucwords(str_replace('_', ' ', $engines)) }} </option>
	                                        @endforeach
	                                    </select>
	                                </div>
	                            </div>
	                            <div class="row form-group">
	                                <div class="col-md-3">
	                                    <label>{{ translates('shipping_from') }}:</label>
	                                </div>
	                                <div class="col-md-6">
	                                    <p>
	                                        <strong> @{{ merchant_address.address }} </strong>
	                                        @{{ merchant_address.city }},
	                                        @{{ merchant_address.state }},
	                                        @{{ merchant_address.country }} -
	                                        @{{ merchant_address.postal_code }}
	                                    </p>
	                                </div>
	                            </div>
	                            <div class="row form-group">
	                                <div class="col-md-3">
	                                    <label>{{ translates('shipping_to') }}:</label>
	                                </div>
	                                <div class="col-md-6">
	                                    <p>
	                                        <strong> @{{ user_address.address }} </strong>
	                                        @{{ user_address.city }},
	                                        @{{ user_address.state }},
	                                        @{{ user_address.country }} -
	                                        @{{ user_address.postal_code }}
	                                    </p>
	                                </div>
	                            </div>
	                        </div>

	                        <hr>

	                        <div class="my-4 py-4">
	                            <div class="">
	                                <p> {{ trans('messages.ship_engine.create_label_content', ['site_name' => get_setting('site_name')]) }}</p>
	                            </div>
	                        </div>

	                        <div class="my-4 multiple_boxes_first">
	                            <div class="row">
	                                <div class="col-md-6">
	                                    <div class="">
	                                        <div class="form-group row">
	                                            <div class="col-md-6">
	                                                <label>{{ translates('package_unit') }}:</label>
	                                            </div>
	                                            <div class="col-md-6">
	                                                <select class="form-control package_unit" name="package_unit[]" id="package_unit_1" required>
	                                                    <option value="pound">{{ translates('pound') }}</option>
	                                                    <option value="ounce">{{ translates('ounce') }}</option>
	                                                    <option value="gram">{{ translates('gram') }}</option>
	                                                    <option value="kilogram">{{ translates('kilogram') }}</option>
	                                                </select>
	                                            </div>
	                                        </div>
	                                        <div class="form-group row">
	                                            <div class="col-md-6">
	                                                <label>{{ translates('weight') }}: </label>
	                                            </div>
	                                            <div class="col-md-6">
	                                                <input type="number" class="form-control" name="package_weight[]" id="package_weight_1" required min="1">
	                                            </div>
	                                        </div>
	                                        <div class="form-group row">
	                                            <div class="col-md-6">
	                                                <label>{{ translates('length') }}: </label>
	                                            </div>
	                                            <div class="col-md-6">
	                                                <input type="number" class="form-control" name="package_length[]" id="package_length_1" required min="1">
	                                            </div>
	                                        </div>
	                                    </div>
	                                </div>
	                                <div class="col-md-6">
	                                    <div class="">
	                                        <div class="form-group row">
	                                            <div class="col-md-6">
	                                                <label> {{ translates('dimensions_unit') }}:</label>
	                                            </div>
	                                            <div class="col-md-6">
	                                                <select name="dimension_unit[]" id="dimension_unit_1" class="form-control dimension_unit" required>
	                                                    <option value="inch">Inch</option>
	                                                    <option value="centimeter">Centimeter</option>
	                                                </select>
	                                            </div>
	                                        </div>
	                                        <div class="form-group row">
	                                            <div class="col-md-6">
	                                                <label>{{ translates('width') }}: </label>
	                                            </div>
	                                            <div class="col-md-6">
	                                                <input type="number" class="form-control" name="dimension_width[]" id="dimension_width_1" required min="1">
	                                            </div>
	                                        </div>
	                                        <div class="form-group row">
	                                            <div class="col-md-6">
	                                                <label> {{ translates('height') }}:</label>
	                                            </div>
	                                            <div class="col-md-6">
	                                                <input type="number" class="form-control" name="dimension_height[]" id="dimension_height_1" required min="1">
	                                            </div>
	                                        </div>
	                                    </div>
	                                </div>
	                            </div>
	                        </div>
	                        
	                        <div class="text-right">
	                            <input type="hidden" id="increment" value="1">
	                            {{-- <button type="button" class="btn btn-primary add_shipping_boxes">{{ translates('add') }}</button> --}}
	                        </div>
	                        <div class="multiple_boxes">

	                        </div>
	                        <div class="mt-4 text-center">
	                            <p class="shipping_tracking_number_error d-none text-danger"></p>
	                        </div>
	                        <div class="my-4 text-center">
	                            <button type="submit" class="btn btn-primary multiple_boxes_submit">{{translate('Submit')}}</button>
	                        </div>
	                        </form>
	                    </div>
	                </div>
	            </div>
	            </div>
	        </div>
	    </div>

	    <div class="modal fade" id="create_label_model" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
	        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
	            <div class="modal-content">
	                <div class="modal-header">
	                    <h5 class="modal-title strong-600 heading-5">{{translates('ship_engine_label')}}</h5>
	                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	                        <span aria-hidden="true">&times;</span>
	                    </button>
	                </div>
	                <div id="label_modal_body" class="modal-body">
	                    <div class="form-group text-center">
	                        <div class="d-flex justify-content-center">
	                            <select class="form-control select w-auto" data-live-search="true"  name="shipping_type" id="shipping_type" required>
	                                
	                            </select>
	                        </div>
	                    </div>

	                    <div class="mt-4 text-center">
	                        <p class="create_label_model_error d-none text-danger"></p>
	                    </div>

	                    <div class="mt-4 text-center">
	                        <button type="button" class="btn btn-primary create_ship_engine_label">{{translate('Submit')}}</button>
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>
    @endif

	<script src="{{ static_asset('assets/js/vendors.js') }}" ></script>
	<script src="{{ static_asset('assets/js/aiz-core.js') }}" ></script>

    @yield('script')

    <script type="text/javascript">


    	 function deleteClickEvent(link) {
             // disable subsequent clicks
           
             link.onclick = function(event) {
                event.preventDefault();
             }
           }  

           
	    @foreach (session('flash_notification', collect())->toArray() as $message)
	        AIZ.plugins.notify('{{ $message['level'] }}', '{{ $message['message'] }}');
	    @endforeach


        if ($('#lang-change').length > 0) {
            $('#lang-change .dropdown-menu a').each(function() {
                $(this).on('click', function(e){
                    e.preventDefault();
                    var $this = $(this);
                    var locale = $this.data('flag');
                    $.post('{{ route('language.change') }}',{_token:'{{ csrf_token() }}', locale:locale}, function(data){
                        location.reload();
                    });

                });
            });
        }
        function menuSearch(){
			var filter, item;
			filter = $("#menu-search").val().toUpperCase();
			items = $("#main-menu").find("a");
			items = items.filter(function(i,item){
				if($(item).find(".aiz-side-nav-text")[0].innerText.toUpperCase().indexOf(filter) > -1 && $(item).attr('href') !== '#'){
					return item;
				}
			});

			if(filter !== ''){
				$("#main-menu").addClass('d-none');
				$("#search-menu").html('')
				if(items.length > 0){
					for (i = 0; i < items.length; i++) {
						const text = $(items[i]).find(".aiz-side-nav-text")[0].innerText;
						const link = $(items[i]).attr('href');
						 $("#search-menu").append(`<li class="aiz-side-nav-item"><a href="${link}" class="aiz-side-nav-link"><i class="las la-ellipsis-h aiz-side-nav-icon"></i><span>${text}</span></a></li`);
					}
				}else{
					$("#search-menu").html(`<li class="aiz-side-nav-item"><span	class="text-center text-muted d-block">{{ translate('Nothing Found') }}</span></li>`);
				}
			}else{
				$("#main-menu").removeClass('d-none');
				$("#search-menu").html('')
			}
        }
    </script>
    
<script src="{{ static_asset('assets/js/angular.js') }}"></script>
<script src="{{ static_asset('assets/js/angular-sanitize.js') }}"></script>

<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>

<script> 
	
var app = angular.module('APP', []);
var subscription_histroy = '{!! route("sellers.subscription_history")  !!}';
var APP_URL = '{{ url('/admin') }}';
var BASE_URL = '{{ url('/') }}';
</script>
<script src="{{ static_asset('assets/js/common.js') }}"></script>
</body>


</html>
