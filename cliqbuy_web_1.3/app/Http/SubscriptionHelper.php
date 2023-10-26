<?php
/**
 * Subscription Helper
 *
 * @package     Makent
 * @subpackage  Helper
 * @category    Helper
 * @author      Trioangle Product Team
 * @version     2.0
 * @link        http://trioangle.com
 */

namespace App\Http;
use App\Models\PaymentGateway;
use App\Models\UserSubscriptionPlan;
use App\Models\SubscriptionPlan;
use App\BusinessSetting;
use App\Models\UserSubscriptionRenewal;
use App\Http\Helper\PaymentHelper;

class SubscriptionHelper
{
	

	/*public function subscription_update()
	{
	\Log::error('webhook');
		$headers = apache_request_headers();

		$stripe = PaymentGateway::where('site', 'Stripe')->get();

	    \Stripe\Stripe::setApiKey($stripe[0]->value);

	    $payload = @file_get_contents("php://input");

	    $endpoint_secret = 'whsec_He1K09o12ViWL9ViqmpIp2HvNHx5G7W8';

		$sig_header = $_SERVER["HTTP_STRIPE_SIGNATURE"];
		$event = null;

		try {
		  $event = \Stripe\Webhook::constructEvent(
		    $payload, $sig_header, $endpoint_secret
		  );
		} catch(\UnexpectedValueException $e) {
		  // Invalid payload
			\Log::error('webhook error responce 400');
		  http_response_code(400); // PHP 5.4 or greater
		  exit();
		} catch(\Stripe\Error\SignatureVerification $e) {
			dd($e);
		  // Invalid signature
		  http_response_code(400); // PHP 5.4 or greater
		  \Log::error('webhook error 400');
		  exit();
		}

		if (isset($event)) 
		{
		  $customer = \Stripe\Customer::retrieve($event->data->object->customer);
		  $customer_id = $customer->id;

		  	if($event->data->type == 'invoice.payment_succeeded')
		  	{
		  		\Log::error('webhook success');
		  		// customer create successfully
		  		echo 'payment renewal success'; 
		   	}


		}
		  		\Log::error('last line webhook success');
	}*/


	public function subscription_update()
	{
		\Log::error('stripe helper webhook');
		

		// Set your secret key: remember to change this to your live secret key in production
		// See your keys here: https://dashboard.stripe.com/account/apikeys
		\Stripe\Stripe::setApiKey(get_setting('stripe_secret'));

		// If you are testing your webhook locally with the Stripe CLI you
		// can find the endpoint's secret by running `stripe trigger`
		// Otherwise, find your endpoint's secret in your webhook settings in the Developer Dashboard
		$endpoint_secret = get_setting('stripe_webhook_secret_key');

		$payload = @file_get_contents('php://input');
		$sig_header = $_SERVER["HTTP_STRIPE_SIGNATURE"];
		$event = null;
		\Log::info('Log Detail'.json_encode($payload));
		try {
		    $event = \Stripe\Webhook::constructEvent(
		        $payload, $sig_header, $endpoint_secret
		    );
		    \Log::Info('Stripe Webhook Details1'.json_encode($event));
		} catch(\UnexpectedValueException $e) {
		    // Invalid payload
		    http_response_code(400);
		    exit();
		} catch(\Stripe\Exception\SignatureVerificationException $e) {
		    // Invalid signature
		    http_response_code(400);
		    exit();
		}
		if (isset($event)) {


			// Handle the event
			switch ($event->type) {
			    case 'customer.subscription.updated':
			    	\Log::error($event);
			        $paymentIntent = $event->data->object; // contains a StripePaymentIntent
			        $this->update_subscription($paymentIntent);
			        break;/*
			    case 'payment_method.attached':
			        $paymentMethod = $event->data->object; // contains a StripePaymentMethod
			        handlePaymentMethodAttached($paymentMethod);
			        break;*/
			    // ... handle other event types
			    default:
			        // Unexpected event type
			        $this->update_subscription([]);
			        exit();
			}
		}

		http_response_code(200);
	}


	public function update_subscription($data){
		\Log::error($data);
		if(count($data)){
			$customer_id = $data->customer;

		$user_subscribe = UserSubscriptionPlan::where('customer_id',$customer_id)->first();
			 $data['status_code'] = 0 ;
	        if($user_subscribe)
	        {
	        	$user_renewal = new UserSubscriptionRenewal;
	        	$user_renewal->user_plan_id = $user_subscribe->id;
		        $user_renewal->user_id = $user_subscribe->user_id;
		        $user_renewal->transaction_id = $data->id;
		        $user_renewal->subscription_id = $data->id;		        
		        $user_subscribe->status = 'Active';
	            $user_renewal->payment_type = 'stripe';
	            $user_renewal->payment_status = 'Success';
	            $user_renewal->name = $user_subscribe->name;
	            $user_renewal->description = $user_subscribe->description;
	            $user_renewal->tagline = $user_subscribe->tagline;
	            $user_renewal->duration = $user_subscribe->duration;
	            $user_renewal->no_of_product = $user_subscribe->no_of_product;
	            $user_renewal->plan_type = 'Paid';           
	            $user_renewal->price = $user_subscribe->price;
	            $user_renewal->currency = $user_subscribe->currency;
	            $user_renewal->save();
		        $user_subscribe->save();
	        	$data['status_code'] = 1; 
	            $data['status_message'] = translate('Please Subscribe and then create listing');

	             $product_count = Product::where('user_id',$user_subscribe->user_id)->where('published',1)->count();
                if($product_count>$user_subscribe->no_of_product){
                 $product = Product::where('user_id',$user_subscribe->user_id)->where('published',1)/*->where('approved',1)*/->Orderby('id')->take($user_subscribe->no_of_product)->pluck('id');
                 Product::where('user_id',$user_subscribe->user_id)->whereNotIn('id',$product)->update(['published'=>0]);

                }
                $this->subscriptionSuccessMail(Auth::user(),$user_subscribe,'Stripe',$data->id);
	        }
    	}

	}
	public function subscribe($data,$customer_id)
	{
		$stripe_secret = get_setting('stripe_secret');

		\Stripe\Stripe::setApiKey($stripe_secret);
		//create Plan

		$plan_name = @$data['plan_name'];
		$plan_id = $data['plan_id'];
		$amount = $data['amount'];
		$currency_code = $data['currency_code'];
		$email = $data['email'];
		$plan_month = $data['plan_month'];
		$billing_card_number = $data['number'];
		$billing_card_month = $data['expiryMonth'];
		$billing_card_year = $data['expiryYear'];
		$billing_card_cvc = $data['cvv'];

		// //create customer

		$return['status_code'] = 0;

	
		//create product
		$product = \Stripe\Product::create([
		  'name' => $plan_id,
		  'type' => 'service',
		]);
		//create plan
		$plan = \Stripe\Plan::create(array(
			"nickname" => $plan_name,
			"product" => $product->id,
			// "interval" => "day",
			"interval" => "day",
			// "interval_count" => 1,
			"interval_count" => ($plan_month*28),
			"currency" => $currency_code,
			"amount" => (float)($amount*100),
			));
		//create subscription 
		try{
			$subscribe =  \Stripe\Subscription::create(array(
			"customer" => $customer_id,
				"items" => array(
					array(
					"plan" => $plan->id,
					),
				)
				// ,
				// 'trial_period_days' => $plan_month,
			));
		}catch(\Exception $e){
			\Log::info('Plan Subscription Create Error'.json_encode($e));
			$return['status_message'] = $e->getMessage();
			return $return;
		}
		$return['status_code'] = 1;
		$return['status_message'] = 'success';
		$return['subscription_id'] = $subscribe->id;
		// $return['transaction_id'] = $charge->id;
		$return['customer_id'] = $customer_id;
			return $return;
	}

	public function cancel_subscription($subscription_id,$customer_id='')
	{
		if($customer_id){
			$stripe_secret = get_setting('stripe_secret');

			\Stripe\Stripe::setApiKey($stripe_secret);
			try{
				
				$sub = \Stripe\Subscription::retrieve($subscription_id);
				$sub->cancel();
				
				\Log::info('retrieve'.json_encode($sub));
			}catch (\Stripe\Error\InvalidRequest $e) {
				$return['status_code'] = 0;
			}catch (Exception $e) {
				$return['status_code'] = 0;
			}
			// $sub->cancel();

		}
		else{
			$data = json_encode(['reason'=>'this not use to me']);
			$this->paypal_subscription($data,'billing/subscriptions/'.$subscription_id.'/cancel');
		}
		$return['status_code'] = 1;
			return $return;
	}

	
	public function paypal($subscription_id=1)
	{
		$paypal_product =  BusinessSetting::where('type', 'paypal_product_id')->first();
		$return = ['status_code' => 0];
		if(@!$paypal_product->value){
			$data = [
					'name' => get_setting('site_name').' Subscription',
					'description' => get_setting('site_name').' Subscription plan',
					'type' => 'SERVICE',
					'category' => 'SOFTWARE',
			];
	        $data=json_encode($data);
	        $product =  $this->paypal_subscription($data,'catalogs/products');
	        if($product=='error')
        		return $return;
	        BusinessSetting::where('type', 'paypal_product_id')->update(['value'=>$product->id]);
    		$product_id = $product->id;
    	}
    	else
    		$product_id = $paypal_product->value;
    	\Log::info('Request All Data'.json_encode(request()->type));
    	if(request()->flow_type=='upgrade' ||request()->type=='upgrade'){ 
            $subscription = UserSubscriptionPlan::find($subscription_id);
            \Log::info('Act Upgrade Data'.json_encode($subscription));
            }
            else{
            $subscription = SubscriptionPlan::find($subscription_id);
            	\Log::info('Non Upgrade Data'.json_encode($subscription));
            }
    	 $paypal_price = $subscription->price;
    	 $payment_currency = currency_code();
         $paypal_price = (float)currencyConvert($subscription->currency,'',$paypal_price);
         
        // $paypal_price = $payment_helper->currency_convert($subscription->currency_code, PAYPAL_CURRENCY_CODE, $subscription->amount);
        $data = [
				'product_id' => $product_id,
				'name' => $subscription->name,
				'description' => substr($subscription->description,0,128),
				'status' => 'ACTIVE',
				 "billing_cycles"=> [
				    
				    /*[
				      "frequency"=> [
				        "interval_unit"=> "DAY",
				        "interval_count"=> $subscription->period,
				      ],
				      "tenure_type"=> "TRIAL",
				      "sequence"=> 1,
				      "pricing_scheme"=> [
				        "fixed_price"=> [
				          "value"=> round($subscription->usd_amount),
				          "currency_code"=> "USD"
				        ]
				      ]
				    ],*/
				    [
				      "frequency"=> [
				        "interval_unit"=> "DAY",
				        "interval_count"=> $subscription->duration*28,
				        // "interval_count"=> 1,
				      ],
				      "tenure_type"=> "REGULAR",
				      "sequence"=> 1,
				      "total_cycles"=> 0,
				      "pricing_scheme"=> [
				        "fixed_price"=> [
				          "value"=> $paypal_price,
				          "currency_code"=>$payment_currency
				        ]
				      ]
				    ]
				  ],
				'payment_preferences' => [
						'auto_bill_outstanding' => true,
				]
		];

        $data=json_encode($data);
         \Log::info('Plan Data Request From Paypal Plan1. '.$data);
        $plan =  $this->paypal_subscription($data,'billing/plans');
        // dump($plan);
        // dd($plan->id);
         \Log::info('Plan Data Request From Paypal Plan2. '.json_encode($plan));
         // $plan=json_decode($plan);
        if($plan=='error')
        	return $return;
        if(@$plan->name=='INTERNAL_SERVER_ERROR'){
        	$return['message'] = @$plan->message.' '.@$plan->details[0]->description;
        	return $return;
        }
               $data = [
				'plan_id' => $plan->id,
				"application_context"=> [
			    "brand_name"=> get_setting('site_name'),
			    "locale"=> "en-US",
			    "shipping_preference"=> "NO_SHIPPING",
			    "user_action"=> "SUBSCRIBE_NOW",
			    "payment_method"=> [
			      "payer_selected"=> "PAYPAL",
			      "payee_preferred"=> "IMMEDIATE_PAYMENT_REQUIRED"
			    ],
			    "return_url"=> url('subscriptions_success?plan_id='.$subscription_id.'&flow_type='.request()->flow_type),
			    "cancel_url"=> url('subscriptions_cancel?plan_id='.$subscription_id.'&flow_type='.request()->flow_type)
			  ]
				
		];
		 

        $data=json_encode($data);
        \Log::info('Plan Data Request From Paypal . '.json_encode($data));
        $subscription =  $this->paypal_subscription($data,'billing/subscriptions');
        \Log::info('Plan Data Return Request From Paypal . '.json_encode($data));
       	
        if($subscription=='error')
        		return $return;
        $return = [
        	'status_code' => 1,
        	'status_message' => 'Success',
        	'paypal_url' => $subscription->links[0]->href,
        	'subscription_id' => $subscription->id,
        ];
       	return $return;
		
	}

	 public function paypal_subscription($data=false,$url)
    {
        global $environment;                
        $environment  = get_setting('paypal_sandbox')=='1'?'.sandbox.':'.';
        // $environment  = '.sandbox.';
        // $environment  = '.';
        $client  = get_setting('paypal_client_id');
        $secret  = get_setting('paypal_client_secret');

        
        

         $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://api".$environment."paypal.com/v1/oauth2/token");
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        curl_setopt($ch, CURLOPT_USERPWD, $client.":".$secret);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");

  //         curl_setopt_array($ch, array(
		//   CURLOPT_URL => "https://api".$environment."paypal.com/v1/oauth2/token?grant_type=client_credentials",
		//   CURLOPT_RETURNTRANSFER => true,
		//   CURLOPT_ENCODING => '',
		//   CURLOPT_MAXREDIRS => 10,
		//   CURLOPT_TIMEOUT => 0,
		//   CURLOPT_FOLLOWLOCATION => true,
		//   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		//   CURLOPT_CUSTOMREQUEST => 'POST',
		//   CURLOPT_HTTPHEADER => array(
		//     ': ',
		//     'Authorization: Basic QWJacXh3R004Ny1mUkhJLUhuR19wbEJvei1aX2oyT2djQUtSRlF6Z2RSNHFkNWRzemhRWFM1bms2RlRQZDlzdzB2U1NMTWFkSVNCYzJfbEE6RURGWVFmOGl0YnFvV2ktOUJJemd6ck52R1dMSTYyVUVsaVQxaThmX0FQaV9NQUprdGVaTHduWEdtVHZCa0JJUkFWeS1qQ0JpLVBtWXlOVWE='
		//   ),
		// ));

        $result = curl_exec($ch);
        // dd($result);
        $json = json_decode($result);
        
        if(!isset($json->error))
        {
            curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE);
            curl_setopt($ch, CURLOPT_URL, "https://api".$environment."paypal.com/v1/".$url);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            if(!$data){
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        	}
            if($data){
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data); 
            	}
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json","Authorization: Bearer ".$json->access_token,""));

            $result = curl_exec($ch);
          //   if($url=='billing/subscriptions')
         	// dd($result);
            if(empty($result))
            {
                $json ="error";
            }
            else
            {
                $json = json_decode($result);
            }
            curl_close($ch);
              
        }
        else
        {
            $json ="error";
            
        }
        return $json;
    }

}