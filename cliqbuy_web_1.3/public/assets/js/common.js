let csrf = $('meta[name="csrf-token"]').attr("content")

app.controller('MerchantSubscription', ['$scope', '$http', '$compile', '$timeout','$filter', function($scope, $http, $compile, $timeout,$filter) {
  $scope.applyPlanData = function(data){
    console.log(data)
      if(data=='' || data==undefined)
      $scope.planData=[];
      else
      $scope.planData=angular.fromJson(data);
    }

  $(document).ready(function(){
    $("#merchantSubscriptionForm").validate({
      ignore: ':hidden:not(.do-not-ignore)',
      onkeyup: false,
      onfocusout: false,
      rules: {       
        plan_name: { required: true },
        description: { required: true },
        tagline: { required: true },
        no_of_products: { required: true,digits: true,min:1 },
        price: {required: true,number: true,min:1 },
        currency: { required: true },
        duration: { required: true },
      },
      messages: {       
      },
      errorElement: "span",
      errorClass: "text-danger",
      errorPlacement: function( label, element ) {
        if(element.attr( "data-error-placement" ) === "container" ){
          container = element.attr('data-error-container');
          $(container).append(label);
        } else {
          label.insertAfter( element ); 
        }
      }
    });
  });



$(document).on('click','.current_merchant_id',function(){
   $('#current_merchant_id').val($(this).attr("data-user-id"));
});

$(document).on('click','.show_subscription_histroy',function(){
    var data = [];
  $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: subscription_histroy+'/'+$('#current_merchant_id').val(),
        type: 'GET',
        cache: false,
        contentType: false,
        processData: false,
        success: function (response) {
          $scope.subscription_history=JSON.parse(response);
           if(!$scope.$$phase) {
            $scope.$apply();
          }
          setTimeout(function() {
          $('#subscription_history').modal('show');
        },400);
          
          
          console.log($scope.subscription_history);
        }
    });
});

}]);
app.controller('Subscription', ['$scope', '$http', '$compile', '$timeout','$filter', function($scope, $http, $compile, $timeout,$filter) {
    
    $scope.resetCustomPlan = function(){
        if($scope.is_free=='Yes'){
            $scope.custom_plan='No';
        }
    }

    

    $("#subscriptionForm").validate({
      ignore: ':hidden:not(.do-not-ignore)',
      onkeyup: false,
      onfocusout: false,
      rules: {       
        plan_name: { required: true },
        description: { required: true },
        tagline: { required: true },
        no_of_products: { required: true,digits: true,min:1 },
        price: {required: true,number: true,min:1 },
        currency: { required: true },
        status: { required: true },
        duration: { required: true },
      },
      messages: {       
      },
      errorElement: "span",
      errorClass: "text-danger",
      errorPlacement: function( label, element ) {
        if(element.attr( "data-error-placement" ) === "container" ){
          container = element.attr('data-error-container');
          $(container).append(label);
        } else {
          label.insertAfter( element ); 
        }
      }
    });
$(document).on('click','.subscriptionSubmit',function(){
    $('.subscriptionForm').each(function () {
        $(this).rules("add", {
            required: true
        });
    });
});

}]);

app.filter('checkKeyValueUsedInStack', ["$filter", function($filter) {
  return function(value, key, stack) {
    var found = $filter('filter')(stack, {locale: value},true);
    var found_text = $filter('filter')(stack, {key: ''+value}, true);
    return !found.length && !found_text.length;
  };
}]);

app.controller('carriers', ['$scope', '$http', '$compile', '$timeout', '$filter', function($scope, $http, $compile, $timeout, $filter) {
  $scope.connect_carriers = function(carrier) {
      $http.post(APP_URL + '/connect_carriers', {carrier: carrier, _token: csrf}).then(function(response) {
          window.location.reload()
      })
  }

  $scope.disconnect_carriers = function(carrier) {
      $http.post(APP_URL + '/disconnect_carriers', {carrier: carrier, _token: csrf}).then(function(response) {
          window.location.reload()
      })
  }
}]);

app.controller('admin_profile', ['$scope', '$http', '$compile', '$timeout', '$filter', function($scope, $http, $compile, $timeout, $filter) {
  $(document).on('change', '[name=state_id]', function() {
      var state_id = $(this).val();
      get_city(state_id);
  });

  function get_city(state_id) {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: BASE_URL + '/get-city',
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

  $(document).on('change', '[name=country]', function() {
      var country = $(this).val();
      get_state(country);
  });

  function get_state(country) {
      $.ajax({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          url: BASE_URL + '/get-state',
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
  $(".address_form").on('submit', function(e) {
      e.preventDefault()
      $('.ship_engine_msg').addClass('d-none')
      $scope.address_id = $scope.address_id == '0' ? '' : $scope.address_id
      $scope.address_submit($scope.address_id);
  });

  $scope.address_submit = function(address_id) {
    $('.modal-body').addClass('loading')

    let address_data = {}
    address_data['address'] = $('input[name=address]').val()
    address_data['country'] = $('select[name=country] option').filter(':selected').text()
    address_data['state_id'] = $('select[name=state_id]').find(":selected").text()
    address_data['city'] = $('select[name=city]').find(":selected").text()
    address_data['postal_code'] = $('input[name=postal_code]').val()
    address_data['phone'] = $scope.phone = $('#phone_number').val()
    address_data['address_id'] = $scope.address_id = address_id

    $http.post(BASE_URL + '/validate_address_from_ship_engine', {data: address_data, _token: csrf}).then(function(response) {
      let data = response.data
      $('.modal-body').removeClass('loading')

      if(data.status) {
        $scope.matched_address = data.matched_address              
        $scope.original_address = data.original_address   
        $scope.selected_address = data.selected_address   

        $('#new-address-modal').modal('hide')         
        $('#edit-address-modal').modal('hide')         
        $('#ship_engine_address_modal').modal('show')
        $('.add_address_button').prop('disabled', false)

        if(!$scope.$$phase) $scope.$apply();
      } else {
        if(!data.ship_engine) {
          // this means ship engine toggle turned off from feature activation then submit address form
          let new_address_data = {}
          new_address_data['address_line1'] = address_data.address
          new_address_data['country_code'] = address_data.country
          new_address_data['state_province'] = address_data.state_id
          new_address_data['city_locality'] = address_data.city
          new_address_data['postal_code'] = address_data.postal_code
          new_address_data['phone'] = $scope.phone = $('#phone').val()
          new_address_data['address_id'] = $scope.address_id = address_id

          $scope.original_address = new_address_data
          $("#original_address").attr('checked', true).trigger('click')
          $scope.save_final_address()
        } else {
          $('.ship_engine_msg').removeClass('d-none').text(AIZ.local.invalid_address)
        }
      }
    })
  }

  $scope.save_final_address = function() {
    let data = {}
    data['matched_address'] = $scope.matched_address
    data['original_address'] = $scope.original_address
    data['selected_value'] = $('input[name=final_address]:checked').val()
    data['phone'] = $scope.phone
    data['address_id'] = $scope.address_id
    console.log(data)
    $http.post(BASE_URL + '/final_address_save', {data: data, _token: csrf}).then(function(response) {
        window.location.reload()
    })
  }
}]);

app.controller('appController', ['$scope', '$http', '$compile', '$timeout','$filter', function($scope, $http, $compile, $timeout,$filter) {
  $(document).on('click', '.ship_engine_label', function() {
      $('#order_details').modal('hide')
      $('#ship_engine_label').modal('show')
      $scope.order_detail_id = $(this).attr('data-id')
      $scope.get_shipping_address()
    })

    $scope.get_shipping_address = function() {
      $('.address_section').addClass('loading')
      $http.get(BASE_URL + '/get_shipping_address?order_detail_id='+ $scope.order_detail_id).then(function(response) {
        $('.address_section').removeClass('loading')
        let data = response.data
        $scope.merchant_address = data.merchant_address
        $scope.user_address = data.user_address
      })
    }

    $('form[id="manual_tracking_number"]').on('submit', function(event) {
        event.preventDefault()
        $scope.manual_tracking_number()
    })

    $scope.manual_tracking_number = function() {
      let data = {}
      data['carrier_name'] = $('#carrier_name_manual').find(":selected").val()
      data['tracking_number'] = $('input[name=tracking_number]').val()
      data['order_detail_id'] = $scope.order_detail_id

      $http.post(BASE_URL + '/manual_tracking_number', {data: data, _token: csrf}).then(function(response) {
          if(response.data.status) AIZ.plugins.notify('success', response.data.messages)
          window.location.reload()
      })
    }

    $(document).on('submit','form.manual_tracking_number',function(e){
        e.preventDefault()
        let address_id = $(this).attr('id')
        $('.ship_engine_msg').addClass('d-none')
        $('.edit_submit_'+address_id).prop('disabled', true)
        $scope.address_submit(address_id);
    })

    var count = $('#increment').val()
    $(document).on('click','.add_shipping_boxes',function(){ 
      count++
      $('.multiple_boxes:last').append('<div class="my-4 multiple_boxes_first"> <div class="row"> <div class="col-md-6"> <div class=""> <div class="form-group row"> <div class="col-md-6"> <label>Package Unit:</label> </div> <div class="col-md-6"> <select class="form-control" name="package_unit[]" id="package_unit_'+count+'"> <option value="pound">Pound</option> <option value="ounce">ounce</option> <option value="gram">Gram</option> <option value="kilogram">Kilogram</option> </select> </div> </div> <div class="form-group row"> <div class="col-md-6"> <label>Weight: </label> </div> <div class="col-md-6"> <input type="text" class="form-control" name="package_weight[]" id="package_weight_'+count+'"> </div> </div> <div class="form-group row"> <div class="col-md-6"> <label>Length: </label> </div> <div class="col-md-6"> <input type="text" class="form-control" name="package_length[]" id="package_length_'+count+'"> </div> </div> </div> </div> <div class="col-md-6"> <div class=""> <div class="form-group row"> <div class="col-md-6"> <label>Dimensions Unit:</label> </div> <div class="col-md-6"> <select name="dimension_unit[]" id="dimension_unit_'+count+'" class="form-control"> <option value="inch">Inch</option> <option value="centimeter">Centimeter</option> </select> </div> </div> <div class="form-group row"> <div class="col-md-6"> <label>Width: </label> </div> <div class="col-md-6"> <input type="text" class="form-control" name="dimension_width[]" id="dimension_width_'+count+'"> </div> </div> <div class="form-group row"> <div class="col-md-6"> <label>Height:</label> </div> <div class="col-md-6"> <input type="text" class="form-control" name="dimension_height[]" id="dimension_height_'+count+'"> </div> </div> </div> </div> </div> <button type="button" class="btn btn-danger remove_shipping_boxes">Remove</button></div>');
    })

    $(document).on('click','.remove_shipping_boxes',function(){
        count++
        $(this).closest(".multiple_boxes_first").remove();
    })
    
    $('form[id="shipping_tracking_number"]').on('submit', function(event) {
        event.preventDefault()
        $scope.shipping_tracking_number()
    })

    $scope.shipping_tracking_number = function() {
      // $('.ship_engine_label_body').addClass('loading')
      $('.multiple_boxes_submit').prop('disabled', true)
      $('.shipping_tracking_number_error').addClass('d-none')

      let data = {}
      data['package_unit'] = $scope.package_unit = $('select[name^="package_unit"] option:selected').map(function(){return $(this).val()}).get()
      data['package_weight'] = $scope.package_weight = $('input[name^="package_weight"]').map(function(){return $(this).val()}).get()
      data['package_length'] = $scope.package_length = $('input[name^="package_length"]').map(function(){return $(this).val()}).get()
      data['dimension_unit'] = $scope.dimension_unit = $('select[name^="dimension_unit"] option:selected').map(function(){return $(this).val()}).get()
      data['dimension_width'] = $scope.dimension_width = $('input[name^="dimension_width"]').map(function(){return $(this).val()}).get()
      data['dimension_height'] = $scope.dimension_height = $('input[name^="dimension_height"]').map(function(){return $(this).val()}).get()
      data['order_detail_id'] = $scope.order_detail_id = $scope.order_detail_id
      data['count'] = $scope.count = count

      $http.post(BASE_URL + '/get_ship_estimate_for_merchant', {data: data, _token: csrf}).then(function(response) {
          // $('.ship_engine_label_body').removeClass('loading')
          $('.multiple_boxes_submit').prop('disabled', false)
          if(response.data.status) {
            $('#ship_engine_label').modal('hide')
            $('#create_label_model').modal('show')

            let estimate_data = response.data.data
            let text = estimate_data.service_type + ' ' + estimate_data.shipping_amount.amount + ' (' + estimate_data.delivery_days + ') days'

            $('#shipping_type')
            .append($("<option></option>")
            .attr("value", estimate_data.service_code)
            .text(text))
          } else {
            $('.shipping_tracking_number_error').removeClass('d-none').text(response.data.data)
          }
      })
    }

    $(document).on('click','.create_ship_engine_label',function(){
      // $('.label_modal_body').addClass('loading')
      $(this).prop('disabled', true)
      $('.create_label_model_error').addClass('d-none')

      let data = {}
      data['service_code'] = $('select[name="shipping_type"] option:selected').val()
      data['carrier_name'] = $('select[name="carrier_name"] option:selected').val()
      data['order_detail_id'] = $scope.order_detail_id
      data['package_unit'] = $scope.package_unit
      data['package_weight'] = $scope.package_weight
      data['package_length'] = $scope.package_length
      data['dimension_unit'] = $scope.dimension_unit
      data['dimension_width'] = $scope.dimension_width
      data['dimension_height'] = $scope.dimension_height
      data['order_detail_id'] = $scope.order_detail_id
      data['count'] = $scope.count

      $http.post(BASE_URL + '/create_ship_engine_label', {data: data, _token: csrf}).then(function(response) {
          // $('.label_modal_body').removeClass('loading')
          $('.create_ship_engine_label').prop('disabled', false)

          if(response.data.status) {
            AIZ.plugins.notify((response.data.status ? 'success' : 'warning'), response.data.message)
            window.location.reload()
          } else {
            $('.create_label_model_error').removeClass('d-none').text(response.data.message)
          }
      })
    })
}]);
