@extends('frontend.layouts.user_panel')

@section('panel_content')
<div class="cls_bread">
    <ul>
        <li><a href="{{ route('mainmenu') }}">{{ translate('main') }}</a></li>
        <li><a>{{ translate('Subscription')}}</a></li>
        <li><a>{{ translate('Payment')}}</a></li>
    </ul>
</div>
    <div class="aiz-titlebar mt-2 mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('Payment') }}</h1>
            </div>
        </div>
    </div>

    <section class="mb-4">
    <div class="container-fluid text-left">
        <div class="row">
            <div class="col-lg-8">
                    
                    <div class="card shadow-sm border-0 rounded">
                         <form class="subscription_payment_form" action="{{ route('seller.payment',$subscription->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf  
                            <input type="hidden" name="flow_type" value="{{$flow_type}}">
                        <div class="card-header p-3">
                            <h3 class="fs-16 fw-600 mb-0">
                                {{ translate('Select a payment option')}}
                            </h3>
                        </div>
                        <div class="card-body text-center">
                           
                          
                            <div class="row">
                                <div class="col-xxl-8 col-xl-10 mx-auto">
                                    <div class="row gutters-10">
                                     <input type="hidden" name="payment_intent_id" id="payment_intent_id">    
                                        @if(get_setting('paypal_payment') == 1)
                                            <div class="col-6 col-md-4">
                                                <label class="aiz-megabox d-block mb-3">
                                                    <input value="paypal" class="online_payment" type="radio" name="payment_option" {{(old('payment_option')!='' && old('payment_option')=='paypal')?'checked':(old('payment_option')==''?'checked':'')}}>
                                                    <span class="d-block p-3 aiz-megabox-elem">
                                                        <img src="{{ static_asset('assets/img/cards/paypal.png')}}" class="img-fluid mb-2">
                                                        <span class="d-block text-center">
                                                            <span class="d-block fw-600 fs-15">{{ translate('Paypal')}}</span>
                                                        </span>
                                                    </span>
                                                </label>
                                            </div>
                                        @endif
                                        @if(get_setting('stripe_payment') == 1)
                                            <div class="col-6 col-md-4">
                                                <label class="aiz-megabox d-block mb-3">
                                                    <input value="stripe" class="online_payment" type="radio" name="payment_option"  {{(old('payment_option')!='' && old('payment_option')=='stripe')?'checked':(get_setting('paypal_payment')=='0'?'checked':'')}}>
                                                    <span class="d-block p-3 aiz-megabox-elem">
                                                        <img src="{{ static_asset('assets/img/cards/stripe.png')}}" class="img-fluid mb-2">
                                                        <span class="d-block text-center">
                                                            <span class="d-block fw-600 fs-15">{{ translate('Stripe')}}</span>
                                                        </span>
                                                    </span>
                                                </label>
                                            </div>
                                        @endif
                                      
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="stripeCardSection">
                        <div class="card-header p-3">
                            <h3 class="fs-16 fw-600 mb-0">
                                {{ translate('Enter Card Detail')}}
                            </h3>
                        </div>
                        <div class="card-body text-center">
                            
                          
                                <div class="">
                                    <div class="form-group row">
                                        <label for="staticEmail" class="col-sm-3 col-form-label">{{ translate('Credit Card Number')}} :</label>
                                           <div class="col-sm-9">
                                               <input type="text" class="form-control" name="card_no" id="card_no" autocomplete="off" value="{{old('card_no')}}"}>                 
                                            <span class="text-danger">{{$errors->first('card_no')}}</span>
                                            </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="staticEmail" class="col-sm-3 col-form-label"> {{ translate('Expire Month')}} :</label>
                                             <div class="col-sm-9">
                                             <select name="exp_month" id="exp_month" class="form-control" required >
                                                @foreach(range(1,12) as $month)
                                                <option value="{{$month}}" {{old('exp_month')==$month?'selected':''}}>{{$month}}</option>
                                                @endforeach
                                            </select>             
                                            <span class="text-danger">{{$errors->first('exp_month')}}</span>
                                            </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="staticEmail" class="col-sm-3 col-form-label"> {{ translate('Expire Year')}} :</label>
                                            <div class="col-sm-9">
                                                <select name="exp_year" id="exp_year" class="form-control" required >
                                                    @foreach(range(date('Y'),date('Y')+30) as $year)
                                                        <option value="{{$year}}" {{old('exp_year')==$year?'selected':''}}>{{$year}}</option>
                                                    @endforeach
                                                </select>         
                                            <span class="text-danger">{{$errors->first('exp_year')}}</span>
                                            </div>
                                    </div>
                                     <div class="form-group row">
                                        <label for="staticEmail" class="col-sm-3 col-form-label"> {{ translate('CVV')}} :</label>
                                            <div class="col-sm-9">
                                               <input type="text" name="cvv" id="cvv" class="form-control" autocomplete="off" value="{{old('cvv')}}" >                 
                                            <span class="text-danger">{{$errors->first('cvv')}}</span>
                                            </div>
                                    </div>
                            </div>
                        </div>
                        </div>
                        </form>
                    </div>                
            </div>

            <div class="col-lg-4 mt-4 mt-lg-0" id="cart_summary">
                <div class="card border-0 shadow-sm rounded">
    <div class="card-header">
        <h3 class="fs-16 fw-600 mb-0">{{translate('Summary')}}</h3>       
    </div>

    <div class="card-body">
       
        <table class="table">
           
            <tbody>
                    
                    <tr class="cart_item">
                        @if($custom_way)
                        <td class="product-name"> @php $subscriptio_trans = \App\Models\SubscriptionPlan::find($subscription->subscription_plan_id); @endphp
                            {{translate('Plan')}} : {{ $subscriptio_trans->getTranslation('name')}}
                        </td>
                        @else
                        <td class="product-name"> @php $subscriptio_trans = \App\Models\SubscriptionPlan::find($subscription->id); @endphp
                            {{translate('Plan')}} : {{ $subscriptio_trans->getTranslation('name')}}
                        </td>
                        @endif
                        <td class="product-total text-right">
                            <span class="pl-4 pr-0">{{format_price(currencyConvert($subscription->currency,'',$subscription->price))}}</span>
                        </td>
                    </tr>               
            </tbody>
        </table>

        <table class="table">

            <tfoot>
                <tr class="cart-subtotal">
                    <th>{{translate('Total')}}</th>
                    <td class="text-right">
                        <span class="fw-600">{{format_price(currencyConvert($subscription->currency,'',$subscription->price))}}</span>
                    </td>
                </tr>
               
            </tfoot>
        </table>
        @if(get_setting('paypal_payment') == 1 || get_setting('stripe_payment') == 1)
         <div class="mt-3">                          
                <div class="input-group">                  
                    <div class="input-group-append">                        
                        <button type="button" id="subscript_payment" class="btn btn-primary">{{translate('Subscribe')}}</button>
                    </div>
                </div>
            
         </div>
         @endif


    </div>
</div>

            </div>
        </div>
    </div>
</section>

@endsection

@section('script')
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>

    <script type="text/javascript">
        /*var form_valid=$(".subscription_payment_form").validate({
             ignore: ':hidden:not(.do-not-ignore)',
             onkeyup: false,
             onfocusout: false,
             rules: {       
               card_no: { required: true,digits:true,minlength:12,maxlength:20},
               cvv: { required: true},               
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
           });*/

          $(document).on('click','#subscript_payment',function(){
            // form_valid.resetForm();
            // if(form_valid.valid())
             $(this).prop('disabled', true)
             $('.subscription_payment_form').submit();
          });

          $(document).on('click','.online_payment',function(){
            if($(this).val()=='stripe')
                $('#stripeCardSection').show();
            else
                $('#stripeCardSection').hide();
          });

          if($('.online_payment:checked').val()=='paypal')
            $('#stripeCardSection').hide();
          else
            $('#stripeCardSection').show();
        
    </script>
    <script src="https://js.stripe.com/v3/"></script>

    <script type="text/javascript">
        var payment_intent_client_secret  = "{!! $payment_intent_client_secret !!}";
    </script>
    <script type="text/javascript">
        
    // $scope.isDisabled = false;

    // $scope.disableButton = function() {
    // $scope.isDisabled = true;
    // }

    // Stripe 3D Secure Payment Starts
    var stripe_key = '{!! get_setting('stripe_key') !!}';
    var stripe = Stripe(stripe_key);
    $(document).ready(function() {
        if(payment_intent_client_secret != '') {
            handleServerResponse(payment_intent_client_secret);
        }
    $(document).on('keypress','#card_no',function(e){
        if(e.keyCode!=48 && e.keyCode!=49&& e.keyCode!=50&& e.keyCode!=51&& e.keyCode!=52&& e.keyCode!=53&& e.keyCode!=54&& e.keyCode!=55&& e.keyCode!=56&& e.keyCode!=57)
            return false;
    });
    });

    function handleServerResponse (payment_intent_client_secret) {

        $('#subscript_payment').addClass('disabled');
        $('#subscript_payment').attr('disabled','disabled');
        $('#card_no').attr('readonly',true);
        $('#exp_month').attr('disabled','disabled');
        $('#exp_year').attr('disabled','disabled');
        $('#cvv').attr('readonly',true);
        stripe.handleCardAction(payment_intent_client_secret)
        .then(function(result) {
          if (result.error) {
                $('#subscript_payment').removeClass('disabled');
                $('#subscript_payment').attr('disabled',false);
                $('#card_no').attr('readonly',false);
                $('#exp_month').attr('disabled',false);
                $('#exp_year').attr('disabled',false);
                $('#cvv').attr('readonly',false);
                $('#payment_intent_id').val('');
          }
          else {
            // The card action has been handled & The PaymentIntent can be confirmed again on the server
            $('#payment_intent_id').val(result.paymentIntent.id);
            
            $('.subscription_payment_form').submit();
            // Disable Payment Button and confirm Booking
            // $scope.disableButton();
          }
        });
    };
    // Stripe 3D Secure Payment Ends


    </script>
@endsection
