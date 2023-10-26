csrf = $('meta[name="csrf-token"]').attr("content")

$.extend($.validator.messages, {
    required: "This field is required.",
    email: "Please enter a valid email address.",
    number: "Please enter a valid number.",
    digits: "Please enter only digits.",
})

app.controller('appController', ['$scope', '$http', '$compile', '$timeout', '$filter', function($scope, $http, $compile, $timeout, $filter) {

    $(document).on('change', '[name=state_id]', function() {
        var state_id = $(this).val();
        get_city(state_id);
    });

    function get_city(state_id) {
      $.ajax({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          url: APP_URL + '/get-city',
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
            url: APP_URL + '/get-state',
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

    $(".new-address-form").on('submit', function(e) {
        e.preventDefault()
        $('.ship_engine_msg').addClass('d-none')
        $('.add_address_button').prop('disabled', true)
        $scope.address_submit();
    });

    $(document).on('submit','form.edit-address-form',function(e){
        e.preventDefault()
        let address_id = $(this).attr('id')
        $('.ship_engine_msg').addClass('d-none')
        $('.edit_submit_'+address_id).prop('disabled', true)
        $scope.address_submit(address_id);
    });

    $scope.add_new_address = function(){
        $('input[name=address_id]').addClass('active')
        $('#new-address-modal').modal('show')
        // $('[name=country]').trigger('change')
        // $('[name=state_id]').trigger('change')
        $('.ship_engine_msg').addClass('d-none')
        $('form.new-address-form')[0].reset()
        $('[name=city]').children().remove().end()
        $('[name=state_id]').children().remove().end()
        AIZ.plugins.bootstrapSelect('refresh');
    }

    $scope.edit_address = function(address) {
      // edit_address_url and google_map value getting from respective blade - profile.blade
      var url = edit_address_url
      url = url.replace(':id', address);

      $.ajax({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          url: url,
          type: 'GET',
          success: function (response) {
              $('#edit_modal_body').html(response.html);
              $('#edit-address-modal').modal('show');
              AIZ.plugins.bootstrapSelect('refresh');

              if (google_map == 1) {
                  var lat     = -33.8688;
                  var long    = 151.2195;

                  if(response.data.address_data.latitude && response.data.address_data.longitude) {
                      lat     = parseFloat(response.data.address_data.latitude);
                      long    = parseFloat(response.data.address_data.longitude);
                  }

                  // initialize function loaded on google_map.blade and it included in respective profile blade
                  initialize(lat, long, 'edit_');
              }                    
          }
      });
  }

    $scope.address_submit = function(address_id = '') {
      let address_data = {}

      $('.modal-body').addClass('loading')
      if(address_id != '') {
        address_data['address'] = $('#address_'+address_id).val()
        address_data['country'] = $('#edit_country_'+address_id).find(":selected").text()
        address_data['state_id'] = $('#edit_state_'+address_id).find(":selected").text()
        address_data['city'] = $('#edit_city_'+address_id).find(":selected").text()
        address_data['postal_code'] = $('#postal_code_'+address_id).val()
        address_data['phone'] = $scope.phone = $('#phone_'+address_id).val()
        address_data['address_id'] = $scope.address_id = address_id
      } else {
        address_data['address'] = $('input[name=address]').val()
        address_data['country'] = $('select[name=country] option').filter(':selected').text()
        address_data['state_id'] = $('select[name=state_id]').find(":selected").text()
        address_data['city'] = $('select[name=city]').find(":selected").text()
        address_data['postal_code'] = $('input[name=postal_code]').val()
        address_data['phone'] = $scope.phone = $('#phone_number').val()
        address_data['address_id'] = $scope.address_id = address_id
      }

      $http.post(APP_URL + '/validate_address_from_ship_engine', {data: address_data, _token: csrf}).then(function(response) {
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
            $('.edit_submit_'+address_id).prop('disabled', false)

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
              new_address_data['phone'] = $scope.phone = $('#phone_number').val()
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
      
      $http.post(APP_URL + '/final_address_save', {data: data, _token: csrf}).then(function(response) {
          window.location.reload()
      })
    }

    // Product Detail page js //
    $scope.get_ship_price = function(carrier_id, product_user_id) {
      $('.shipping_price').addClass('loading')
      $http.post(APP_URL + '/get_ship_price', {carrier_id: carrier_id, product_user_id: product_user_id, product_id: $scope.product_id, _token: csrf}).then(function(response) {
        $('.shipping_price').removeClass('loading')        
        let data = response.data

        if(data.status) {
          $('#price_section').text(data.shipping_amount)
          $('#date_section').text(data.delivery_days)
        } else {
          $('.shipping_price').removeClass('d-flex').addClass('d-none')
        }
      });
    }

    $('form[id="ship_estimator_popup"]').validate({  
      rules: {  
        ship_quantity: { required: true, digits: true },  
        ship_zip_code: { required: true, digits: true },
      },  
      messages: {  
        ship_quantity: 'This field is required',  
        ship_zip_code: 'This field is required',
      },  
      errorElement: "span",
      errorClass: "text-danger d-block mr-3",
      submitHandler: function(form) {
        $scope.show_all_prices()
      }  
    });

    $('#ship_estimator_popup').on('submit', function(e){
      e.preventDefault()
    })

    $scope.show_all_prices = function() {
      $('.ship_estimator_section').addClass('loading')
      let data = {}
      data['carrier_id'] = $scope.carrier_id
      data['product_user_id'] = $scope.user_id
      data['from_zip_code'] = $('#ship_zip_code').val()
      data['ship_quantity'] = $('#ship_quantity').val()
      data['product_id'] = $scope.product_id

      $scope.ship_estimated = false
      $scope.show_error = false
      $http.post(APP_URL + '/show_all_prices', {data: data, _token: csrf}).then(function(response) {
        $('.ship_estimator_section').removeClass('loading')
        if(response.data.status) {
          $('.show_error').hide()
          $scope.ship_estimated = true
          $scope.estimated_values = response.data.data
          $scope.destination_zip_code = response.data.destination_zip_code
          $scope.ship_quantity = response.data.ship_quantity
        } else {
          $scope.show_error = true
          $('.show_error').show().text(response.data.message)
        }

        if(!$scope.$$phase) {
            $scope.$apply();
        }
      });
    }

    $('.change_value').click(function() {
      $scope.ship_estimated = false

      if(!$scope.$$phase) {
          $scope.$apply();
      }
    })
    // End of Product Detail page js //

    $('[id^="shipping_and_handling_"]').on("change", function() {
      var id = $(this).data('id');

      $('.product_section_'+id).addClass('loading')
      $('#payment_button').prop('disabled', true)
      $.post(APP_URL + '/cart/updateRateId', {
           _token   :  AIZ.data.csrf,
           id       :  id,
           rate_id :  $(this).val(),
       }, function(data){
           $('.product_section_'+id).removeClass('loading')
           $('#payment_button').prop('disabled', false)
           AIZ.plugins.notify((data.status ? 'success' : 'warning'), data.message)
           update_values(data.carts)
       })
    });

    $(document).ready(function() {
      if(AUTH_CHECK == 1 && CURRENT_ROUTE_NAME == 'checkout.store_shipping_infostore') $scope.get_ship_estimate()
    })

    $scope.get_ship_estimate = function() {
      $('.shipping_and_handling_section').removeClass('d-none').addClass('loading')
      $('#payment_button').prop('disabled', true)

      $http.get(APP_URL + '/get_ship_estimate').then(function(response) {
          $('.shipping_and_handling_section').removeClass('loading')
          $('#payment_button').prop('disabled', false)
          
          $scope.ship_estimate_data = response.data.shipengineData
          angular.forEach($scope.ship_estimate_data, function(value, key) {
            if(!value.shipengine) {
              $('#products_'+value.product_id).removeClass('d-inline-block').addClass('d-none')
            } else {
              $('#shipping_and_handling_'+value.product_id).children().remove().end()
              $.each(value.estimate_data, function(key, val) { 
                  let text = val.service_type + ' ' + val.shipping_amount.amount + ' (' + val.delivery_days + ') days'
                  let cart_product = $filter('filter')($scope.carts, {'product_id': value.product_id}, true)[0];
                  let selected_or_not = (cart_product.service_type == val.service_code && cart_product.package_type == val.package_type) ? true : false

                  $('#shipping_and_handling_'+value.product_id)
                  .append($("<option></option>")
                  .attr("value", val.rate_id)
                  .attr("selected", selected_or_not)
                  .text(text))

              })
              update_values(response.data.carts)
            }
          })
      })
    }
    
    $('[id^="quantity_"]').on("change", function(e) {
       let element = e.target
       let id = $(this).data('id');

       $('.product_section_'+id).addClass('loading')
       $('#payment_button').prop('disabled', true)
       $(element).closest('.aiz-plus-minus').find('button').attr('disabled', true);
       $(element).closest('li.list-group-item').find('.c-preloader').show();
       $.post(APP_URL + '/cart/updateQuantity', {
           _token   :  AIZ.data.csrf,
           id       :  id,
           quantity :  $(this).val(),
           type :  'delivery_info',
       }, function(data){
           $('.product_section_'+id).removeClass('loading')
           $('#payment_button').prop('disabled', false)
           $(element).closest('li.list-group-item').find('.c-preloader').hide();
           updateNavCart(data.nav_cart_view,data.cart_count);
           update_values(data.cart_view)
           $(element).closest('.aiz-plus-minus').find('button').attr('disabled', false);
       });
    })

    function update_values(carts) {
      angular.forEach(carts, function(value, key) {
          $('.price_'+key).text(value.price)
          $('.quantity_'+key).val(value.quantity)
          $('.shipping_cost_'+key).text(value.shipping_cost)
          $('.tax_'+key).text(value.tax)
          $('.total_'+key).text(value.total)
       })
    }

    $(document).on('click', '.ship_engine_label', function() {
      $('#order_details').modal('hide')
      $('#ship_engine_label').modal('show')
      $scope.order_detail_id = $(this).attr('data-id')
      $scope.get_shipping_address()
    })

    $scope.get_shipping_address = function() {
      $('.address_section').addClass('loading')
      $http.get(APP_URL + '/get_shipping_address?order_detail_id='+ $scope.order_detail_id).then(function(response) {
        $('.address_section').removeClass('loading')
        let data = response.data
        $scope.merchant_address = data.merchant_address
        $scope.user_address = data.user_address
      })
    }

    // save shipping label 
    // $('form[id="manual_tracking_number"]').validate({  
    //   rules: {  
    //     carrier_name: { required: true },  
    //     tracking_number: { required: true },
    //   },
    //   submitHandler: function(form) {
    //     $scope.manual_tracking_number()
    //   },
    //   errorElement: "span",
    //   errorClass: "text-danger d-block mr-3",
    //   errorPlacement: function( label, element ) {
    //     if(element.attr( "data-error-placement" ) === "container" ){
    //       container = element.attr('data-error-container');
    //       $(container).html(label);
    //     }
    //   }
    // });

    $('form[id="manual_tracking_number"]').on('submit', function(event) {
        event.preventDefault()
        $scope.manual_tracking_number()
    })

    $scope.manual_tracking_number = function() {
      let data = {}
      data['carrier_name'] = $('#carrier_name_manual').find(":selected").val()
      data['tracking_number'] = $('input[name=tracking_number]').val()
      data['order_detail_id'] = $scope.order_detail_id

      $http.post(APP_URL + '/manual_tracking_number', {data: data, _token: csrf}).then(function(response) {
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

    // save shipping label 
    // $('form[id="shipping_tracking_number"]').validate({  
      // rules: {
      //   "carrier_name": { required: true },  
      //   "package_weight[]": { required: true, digits: true, min: 1 },  
      //   "package_length[]": { required: true, digits: true, min: 1 },
      //   "dimension_width[]": { required: true, digits: true, min: 1 },
      //   "dimension_height[]": { required: true, digits: true, min: 1 },
      // },
      // submitHandler: function(form) {
      //   $scope.shipping_tracking_number()
      // },
      // errorElement: "span",
      // errorClass: "text-danger"
    // });

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

      $http.post(APP_URL + '/get_ship_estimate_for_merchant', {data: data, _token: csrf}).then(function(response) {
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

      $http.post(APP_URL + '/create_ship_engine_label', {data: data, _token: csrf}).then(function(response) {
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


app.controller('merchant_owe_amount', ['$scope', '$http', '$filter', function($scope, $http, $filter) {

      $scope.applyScope = function() {
        if(!$scope.$$phase) {
            $scope.$apply();
        }
      };
      $(document).ready(function() {
        $scope.merchant_amount = [];
        $scope.getTotal = function() {
          return 0;
        }
        $scope.applyScope();
      });

      $(document).on('change', '[id^="merchan-owe-amount"]', function(e){
        var id = $(this).attr('value');

        var order = $scope.owe_amount_order.find(order=>order.id == id);

        if(document.getElementById(e.target.id).checked == true) {
          $scope.merchant_amount.push(order);
          $scope.applyScope();
        } else {
            const indexOfObject = $scope.merchant_amount.findIndex(object => {
            return object.id === id;
            });
            $scope.merchant_amount.splice(indexOfObject,1);
            $scope.applyScope();
        }
        $scope.getTotal = function(){
              var total = 0;
              for(var i = 0; i < $scope.merchant_amount.length; i++){
                  var order = $scope.merchant_amount[i];
                  total += (order.remain_amount);
              }
              return total;
          }

          let result = $scope.merchant_amount.map(a => a.code);
          $scope.order_code = result.join(',');
          let order_id = $scope.merchant_amount.map(a => a.id);
          $scope.order_id = order_id;
          $scope.applyScope();
      });
}]);