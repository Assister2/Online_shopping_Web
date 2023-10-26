<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubscriptionPlan;
use App\Models\Language;
use App\Models\Currency;
use App\Http\SubscriptionHelper;
use App\Models\UserSubscriptionPlan;
use App\Models\Product;
use App\User;
use App\Mail\CommonMailManager;
use App\Models\UserSubscriptionRenewal;
use App\Repositories\StripePayment;
use Session;
use Validator;
use Auth;
class SubscriptionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function __construct(SubscriptionHelper $subscription)
     {
        $this->subscription_helper = $subscription;
     }
    // View Subscription Plan Detail
    public function index(Request $request)
    {       
        $data['subscription'] = SubscriptionPlan::where('status', 'Active')->get();
        $data['user_subscription'] =  UserSubscriptionPlan::where('user_id',Auth::user()->id)/*->where('flow_type','!=','Custom')*/->where('status','Active')->first();
        return view('frontend.subscriptionplan', $data);
        
    }
    //View Subsscription History Detail
    public function subscription_history()
    {   
        $data['subscription_history'] =  UserSubscriptionPlan::with('subscription_renewal')->where('user_id',Auth::user()->id)->where('plan_type','!=','Custom')->first();
        // dd($data);
        $data['upgrade_plan'] =  UserSubscriptionPlan::where('user_id',Auth::user()->id)->where('plan_type','Custom')->first();
        $data['active_product_count'] = Product::where('user_id',Auth::user()->id)->where('published',1)/*->where('approved',1)*/->count();
        return view('frontend.subscription_history',$data);
    }

    // Redirect Subscription Payment Page
    public function payment(Request $request)
    {   
        $user = Auth::user();
        if(!$_POST){

            if(Session::get('payment_intent_client_secret')){
                $data['payment_intent_client_secret']=Session::get('payment_intent_client_secret');
                Session::forget('payment_intent_client_secret');
            }
            else
                $data['payment_intent_client_secret']='';
            if($request->type && $request->type=='upgrade'){
            $data['subscription'] = UserSubscriptionPlan::where('id', $request->id)->first();
            $data['flow_type'] = 'upgrade';
            }
            else{
            $data['subscription'] = SubscriptionPlan::where('id', $request->id)->first();
            $data['flow_type'] = 'normal';            
            }
            if(!$data['subscription']){
                flash(translate('invalid_subscription_plan'))->error();
               return back();
            }
            $data['custom_way'] = false;
           if(!$request->type && $data['subscription']->is_free=='Yes'){
              $this->store_subscription($data['subscription'],'',$user,'');
               flash(translate('subscription_added_successfully'))->success();                
               return redirect()->route('seller.subscription_history');
            }elseif(!$request->type && $data['subscription']->custom_plan=='Yes'){
                  flash(translate('pls_wait_subs_sent_to')." ".get_setting('site_name'))->success();  
                $this->contactSubscriptionMail($user,$data['subscription']);
                return back();
            }else{
                if($request->type=='upgrade'){
                     if($data['subscription']){
                        if((float)($data['subscription']->price)>0){
                            $upgrade_plan_price = currencyConvert($data['subscription']->currency,'',$data['subscription']->price);

                            $currenct_subscription =  UserSubscriptionPlan::where('user_id',Auth::user()->id)->where('plan_type','!=','Custom')->first();
                            if($currenct_subscription){
                            $current_plan_price = currencyConvert($currenct_subscription->currency,'',$currenct_subscription->price);
                            }else
                            $current_plan_price = 0;


                            if($current_plan_price!=$upgrade_plan_price || $currenct_subscription->plan_type=='Free'){
                                $data['custom_way'] = true;
                               return view('frontend.subscription_payment', $data); 
                           }else{
                            $this->store_subscription($currenct_subscription,'',$user,'');
                            flash(translate('subscription_added_successfully'))->success();
                            return redirect()->route('seller.subscription_history');
                           }                            
                        }else{
                            $this->store_subscription($data['subscription'],'',$user,'');
                            flash(translate('subscription_added_successfully'))->success();
                            return redirect()->route('seller.subscription_history');
                        }
                    }
                }

             return view('frontend.subscription_payment', $data);
            } 
        }
        else{
            if($request->flow_type=='upgrade'){
                $user_subscription_upgrade = UserSubscriptionPlan::where('id',$request->id)->first();
                 $subscription_plan = SubscriptionPlan::find($user_subscription_upgrade->subscription_plan_id)->first();
            }else
            $subscription_plan = SubscriptionPlan::find($request->id);           
            if(!$subscription_plan){
                flash(translate('invalid_subscription_plan'))->error();
                return back();
            }elseif($request->flow_type!='upgrade' && $subscription_plan->is_free=='Yes'){
              $this->store_subscription($subscription_plan,'',$user,'');
               flash(translate('subscription_added_successfully'))->success();                
               return redirect()->route('seller.subscription_history');
            }elseif($request->flow_type!='upgrade' && $subscription_plan->custom_plan=='Yes'){
                
            }
            if($request->flow_type=='upgrade'){                
            $amount=(float)$user_subscription_upgrade->price;
            $subscription_plan_id=$user_subscription_upgrade->id;
            $subscription_plan_name=$subscription_plan->name;
            $subscription_plan_description=$user_subscription_upgrade->description;
            $subscription_plan_tagline=$user_subscription_upgrade->tagline;
            $subscription_plan_duration=$user_subscription_upgrade->duration;
            $subscription_plan_price=(float)$user_subscription_upgrade->price;
            $subscription_plan_currency=$user_subscription_upgrade->currency;
            $subscription_plan_product=(int)$user_subscription_upgrade->no_of_product;
            }
            else{
            $amount=(float)$subscription_plan->price;
            $subscription_plan_id=$subscription_plan->id;
            $subscription_plan_name=$subscription_plan->name;
            $subscription_plan_description=$subscription_plan->description;
            $subscription_plan_tagline=$subscription_plan->tagline;
            $subscription_plan_duration=$subscription_plan->duration;
            $subscription_plan_price=(float)$subscription_plan->price;
            $subscription_plan_currency=$subscription_plan->currency;
            $subscription_plan_product=(int)$subscription_plan->no_of_product;
            }

            $payment_currency = currency_code();
            $amount = currencyConvert($subscription_plan_currency,'',$amount);
            $subscription_plan_price = currencyConvert($subscription_plan_currency,'',$subscription_plan_price);
            if($request->payment_option=='stripe'){
            if(!$request->payment_intent_id){

                $rules = [
                'card_no'        => 'required|numeric|digits_between:12,20|validateluhn',
                'exp_month'  => 'required|expires:exp_month,exp_year',
                'exp_year'   => 'required|expires:exp_month,exp_year',
                'cvv' => 'required|numeric|digits_between:0,4',
            ];

            $niceNames = [
                'card_no'        => translate('Card number'),
                'exp_month'  => translate('Month'),
                'exp_year'   => translate('Year'),
                'cvv' => translate('Cvv'),
            ];

            $messages = [
                'exp_month.expires'      => translate('card_month_expire'),
                'exp_year.expires'      => translate('card_year_expire'),
                'validateluhn' => translate('card_number_invalid')
            ];

            $validator = Validator::make($request->all(), $rules, $messages);
            $validator->setAttributeNames($niceNames);
            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
            }
            }
            
            \Log::info('Payment Currency .'.$payment_currency);
            $plan_detail = [
                'email'           => $user->email,
                'number'          => $request->card_no, 
                'expiryMonth'     => $request->exp_month, 
                'expiryYear'      => $request->exp_year, 
                'cvv'             => $request->cvv, 
                'amount'          => $amount,
                'currency_code'   => $payment_currency,
                'plan_name'       => $subscription_plan_name,
                'plan_id'         => $subscription_plan_name.' '.$subscription_plan_id.' '.$user->id,
                'plan_month'      => $subscription_plan_duration,
            ];
            if($subscription_plan_price >= 1) {

                $stripe_card =  array(
                    "number" => $request->card_no,
                    "exp_month" => $request->exp_month,
                    "exp_year" => $request->exp_year,
                    "cvc" => $request->cvv,
                );
                $purchaseData   =   [
                    'amount'              => $amount*100,
                    'description'         => $subscription_plan_description,
                    'currency'            => $payment_currency,
                    'confirmation_method' => 'manual',
                    'confirm'             => true,
                ];
                $stripe_payment = new StripePayment();
                if(@$request->payment_intent_id != '') {
                    $stripe_response = $stripe_payment->CompletePayment($request->payment_intent_id);
                }
                else {
                    $payment_method = $stripe_payment->createPaymentMethod($stripe_card);
                    if($payment_method->status != 'success') {
                         flash(translate($payment_method->status_message))->error();
                        return back();
                    }
                    
                    $purchaseData['payment_method'] = $payment_method->payment_method_id;
                    try {
                        $stripe_response = $stripe_payment->CreatePayment($purchaseData,$user->email,$stripe_card);
                    } catch (\Exception $e) {
                         flash(translate($e->getMessage()))->error();
                        return back();
                    }
                } 
                if($stripe_response->status == 'success') {
                    $exist_user_subscription = UserSubscriptionPlan::where('user_id',$user->id)->where('plan_type','!=','Custom')->first();
                    if($exist_user_subscription){
                     $renewel=UserSubscriptionRenewal::where('user_plan_id',$exist_user_subscription->id)->first();
                     if($renewel){
                        $cancel = $this->subscription_helper->cancel_subscription($renewel->subscription_id,$exist_user_subscription->customer_id);
                        if($cancel['status_code']!=1){
                             flash(translate('something_went_wrong_contact_admin'))->error();
                            return back();
                        }else{
                             $renewel->cancelled=1;  
                            $renewel->save();
                        }
                     }
                    }
                  $payment = $this->subscription_helper->subscribe($plan_detail,$stripe_response->customer_id);
                  $payment['transaction_id'] = $stripe_response->transaction_id;
                  $payment['customer_id'] = $stripe_response->customer_id;
                  $payment['subscription_id'] = $payment['subscription_id'];
                 $this->store_subscription($subscription_plan,$payment,$user,'stripe');
                 flash(translate('subscription_added_successfully'))->error();                
                 return redirect()->route('seller.subscription_history');
                }else if($stripe_response->status == 'requires_action') {
                    Session::put('payment_intent_client_secret',$stripe_response->payment_intent_client_secret);                   
                    return back()->withInput();
                }
                else
                {
                 flash(translate($stripe_response->status_message))->error();
                 return back();
                }
            }
                 // $this->store_subscription($subscription_plan,$payment,$user,'stripe');
                 flash(translate('subscribe_amount_low').' '.currency_code().' '.translate('currency'))->error();                
                 return back();
            }else {
           
                 $paypal_data = $this->subscription_helper->paypal($subscription_plan_id);
                if($paypal_data['status_code']==1){
                   
                  \Session::put('paypal_subscription_id', $paypal_data['subscription_id']);
                  \Session::put('paypal_subscriped_id',$subscription_plan->id);
                  // dd($request);
                  return redirect()->to($paypal_data['paypal_url']);
                }
                else
                {
                   flash(translate('please_try_again'))->success();
                  return back();
                }

        }

        }
        
    }

    // Store User Subscription Detail after Complete Payment
    public function store_subscription($subscription,$payment,$user,$payment_type)
    { 
        $transaction_id='';
        $exist_product_count=0;
        if((request()->flow_type && request()->flow_type=='upgrade') || (request()->type && request()->type=='upgrade'))
            $subscription = UserSubscriptionPlan::where('user_id',$user->id)->where('plan_type','Custom')->first();
            $user_subscription = UserSubscriptionPlan::where('user_id',$user->id)->where('plan_type','!=','Custom')->first();
        if(!$user_subscription)
        $user_subscription = new UserSubscriptionPlan;
        else{
            $exist_product_count=$user_subscription->no_of_product;
        }
        $user_subscription->user_id = $user->id;
        if((request()->flow_type && request()->flow_type=='upgrade') || (request()->type && request()->type=='upgrade'))
        $user_subscription->subscription_plan_id = $subscription->subscription_plan_id;
        else
        $user_subscription->subscription_plan_id = $subscription->id;
        $user_subscription->name = $subscription->name;
        $user_subscription->description = $subscription->description;
        $user_subscription->tagline = $subscription->tagline;
        $user_subscription->duration = $subscription->duration;
        $user_subscription->no_of_product = $subscription->no_of_product;
        $user_subscription->alert_subscription = 0;
        if((request()->flow_type && request()->flow_type=='upgrade') || (request()->type && request()->type=='upgrade')){
        $user_subscription->plan_type = (float)$subscription->price>0?'Paid':'Free';
        $user_subscription->flow_type = 'Custom';
        }
        else
        $user_subscription->plan_type = $subscription->custom_plan=='Yes'?'Custom':($subscription->is_free=='Yes'?'Free':'Paid');       
        $user_subscription->currency = $subscription->currency??'USD';
        $user_subscription->price = $subscription->price;
        $user_subscription->status = 'Active';        
        $user_subscription->auto_renewal = 'On';
        if($subscription->is_free != 'Yes' && isset($payment['customer_id'])) 
        $user_subscription->customer_id = $payment['customer_id'];
        $user_subscription->save();
        if((($subscription->is_free != 'Yes')||((request()->flow_type && request()->flow_type=='upgrade') || (request()->type && request()->type=='upgrade')))&& isset($payment['customer_id'])){
            $transaction_id=$payment['transaction_id'];
            $user_renewal = new UserSubscriptionRenewal;
            $user_renewal->user_id = $user->id;
            $user_renewal->user_plan_id = $user_subscription->id;
            $user_renewal->transaction_id = $payment['transaction_id'];
            $user_renewal->subscription_id = $payment['subscription_id'];
            $user_renewal->payment_type = $payment_type;
            $user_renewal->payment_status = 'Success';
            $user_renewal->name = $subscription->name;
            $user_renewal->description = $subscription->description;
            $user_renewal->tagline = $subscription->tagline;
            $user_renewal->duration = $subscription->duration;
            $user_renewal->no_of_product = $subscription->no_of_product;
            if((request()->flow_type && request()->flow_type=='upgrade') || (request()->type && request()->type=='upgrade')){
            $user_renewal->plan_type = 'Paid';
            $user_renewal->flow_type = 'Custom';
            }
            else
            $user_renewal->plan_type = $subscription->custom_plan=='Yes'?'Custom':($subscription->is_free=='Yes'?'Free':'Paid');
            $user_renewal->price = $subscription->price;
            $user_renewal->currency = $subscription->currency;
            $user_renewal->save();

        }

         if((request()->flow_type && request()->flow_type=='upgrade') || (request()->type && request()->type=='upgrade')){
                $subscription->delete();
         }
         $product_count = Product::where('user_id',Auth::user()->id)->where('published',1)->count();
        if($product_count>$subscription->no_of_product){
         $product = Product::where('user_id',Auth::user()->id)->where('published',1)/*->where('approved',1)*/->Orderby('id')->take($subscription->no_of_product)->pluck('id');
         Product::where('user_id',Auth::user()->id)->whereNotIn('id',$product)->update(['published'=>0]);

        }

        $this->newSubscriptionEmail(Auth::user(),$user_subscription,ucfirst($payment_type),$transaction_id);
        $this->subscriptionSuccessMail(Auth::user(),$user_subscription,ucfirst($payment_type),$transaction_id);



        return "Success";
      
    } 

// Redirect User When User Complete the subscription Payment From Paypal Payment Page(Complete Subscription)
    
public function subscriptions_success(Request $request){

    if(!Session::has('subscription_token')){
        Session::put('subscription_token',$request->subscription_id);
        Session::save();
         $user  = Auth::user();
           $exist_user_subscription = UserSubscriptionPlan::where('user_id',$user->id)->where('plan_type','!=','Custom')->first();
            if($exist_user_subscription){
             $renewel=UserSubscriptionRenewal::where('user_plan_id',$exist_user_subscription->id)->first();
             if($renewel){
                $cancel = $this->subscription_helper->cancel_subscription($renewel->subscription_id,'');
                $renewel->cancelled=1;  
                $renewel->save();              
             }
            }
            $user_renewal = UserSubscriptionRenewal::where('subscription_id',$request->subscription_id)->first();
          if(!$user_renewal)
              {
                // dd($request->all());
                $plan =  $this->subscription_helper->paypal_subscription('','billing/subscriptions/'.$request->subscription_id);
                  \Log::error('entery paypal token');
                if(@$plan->status=='ACTIVE'){
                  \Log::error('success paypal token');
                  if($request->flow_type=='upgrade')
                  $subscription  = UserSubscriptionPlan::find($request->plan_id);
                  else
                  $subscription  = SubscriptionPlan::find($request->plan_id);
                 
                
                  
                  $payment = ['status_code' => 1,
                      'status_message' => 'success',
                      'subscription_id' =>  $request->subscription_id,
                      'transaction_id' => '',
                      'customer_id' => ''];
                  $value = $this->store_subscription($subscription,$payment,$user,'paypal');
                  Session::forget('subscription_token');
                  Session::save();

                  if($value == 'Success'){
                    flash(translate('subscription_added_successfully'))->success();
                    return redirect()->route('seller.subscription_history');
                  }
                }
                
                Session::forget('subscription_token');
                Session::save();
                flash(translate('please_try_again'))->error();
                return redirect()->route('seller.payment',['id'=>$request->plan_id]);
            }
                Session::forget('subscription_token');
                Session::save();
                flash(translate('duplicate_request'))->error();
                abort(404);
          }
              Session::forget('subscription_token');
              Session::save();
              flash(translate('subscription_added_successfully'))->error();
              return redirect()->route('seller.payment',['id'=>$request->plan_id]);              
      
    }

    // Redirect User When User Cancel the subscription From Paypal Payment Page(Cancel Subscription)
    public function subscriptions_cancel(Request $request){
          $request->session()->forget('paypal_subscription_id');
          $request->session()->forget('paypal_subscriped_id');          
          flash(translate('subscription_was_cancelled'))->error();
          return redirect()->route('seller.payment',['id'=>$request->plan_id]);  

        }


    public function webhook(Request $request)
    { 
        \Log::error('stripe controller webhook');

        // $payment = $this->subscription_helper->subscription_update();
        // return json_encode(array('success'=>'success'));

         $stripe_secret = get_setting('stripe_secret');
         $endpoint_secret = get_setting('stripe_webhook_secret_key');
        \Stripe\Stripe::setApiKey(@$stripe_secret);

        $payload = @file_get_contents("php://input");

        //$event_json = json_decode($payload);

        //dd($event_json);

        // $endpoint_secret = env('STRIPE_WEBHOOK_KEY');

        $sig_header = $_SERVER["HTTP_STRIPE_SIGNATURE"];
        $event = null;
\Log::info('HTTP_STRIPE_SIGNATURE'.$sig_header);
\Log::info('stripe_secret'.$stripe_secret);
\Log::info('$event$event$Hi key'.$endpoint_secret);
\Log::info('payload'.$payload);
        try {
          $event = \Stripe\Webhook::constructEvent(
            $payload, $sig_header, $endpoint_secret
          );
          \Log::info('$event$event$event'.json_encode($event));
        } catch(\UnexpectedValueException $e) {
            \Log::info('ex payload1 desc'.$e->getMessage());
            \Log::info('ex payload1'.json_encode($e));
          // Invalid payload
            
          http_response_code(400); // PHP 5.4 or greater
          exit();
        } catch(\Stripe\Error\SignatureVerification $e) {
          // Invalid signature
            \Log::info('ex payload2 des'.$e->getMessage());
            \Log::info('ex payload2'.json_encode($e));
          http_response_code(400); // PHP 5.4 or greater
          exit();
        }
        \Log::info('ex payload'.json_encode(@$event));
        if (isset($event)) 
        {
          $customer = \Stripe\Customer::retrieve($event->data->object->customer);
          $customer_id = $customer->id;
         // $email_controller->subscription_create($customer_id,$event);
          \Log::info('stripe event '.$event->type);
          \Log::info('stripe event '.$event->type);
          if($event->type == 'invoice.payment_succeeded')
          {
            $paymentIntent = $event->data->object;
            $this->update_subscription($paymentIntent);
          }elseif($event->type == 'invoice.payment_failed')
          {
            $paymentIntent = $event->data->object;
            $this->stripe_failed_subscription($paymentIntent->customer,$paymentIntent->attempt_count);
          }


        }
    }


public function stripe_failed_subscription($customer_id,$count){
     $user_subscribe = UserSubscriptionPlan::where('customer_id',$customer_id)->first();
      if($user_subscribe){
        // $email_controller = new EmailController;
        $user['email'] = $user_subscribe->users->email;
        $user['first_name'] = $user_subscribe->users->first_name;
        $user['plan_name'] = $user_subscribe->name;
          // $email_controller->insufficient_fund($user);
        \Log::info('update entry ent');
        if($count>=0){
            \Log::info('update entry');
          $user_subscribe->status ='Inactive';
          $user_subscribe->save();
           Product::where('user_id',$user_subscribe->user_id)->update(['published'=>0]);
           $this->insufficientFundMail($user_subscribe->users, $user_subscribe,'Stripe');
           $user_subscribe->customer_id=Null;
           $user_subscribe->save();
         }
      }
}
public function update_subscription($data){
        if(count($data)){
            $data = $event->data->object;
            $customer_id = $data->customer->id;

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
                $user_subscribe->alert_subscription = 0;
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


                 $product_count = Product::where('user_id',$user_subscribe->user_id)->where('published',1)->count();
                    if($product_count>$user_subscribe->no_of_product){
                     $product = Product::where('user_id',$user_subscribe->user_id)->where('published',1)/*->where('approved',1)*/->Orderby('id')->take($user_subscribe->no_of_product)->pluck('id');
                     Product::where('user_id',$user_subscribe->user_id)->whereNotIn('id',$product)->update(['published'=>0]);

                    }

                 $this->subscriptionSuccessMail(Auth::user(),$user_subscribe,'Stripe',$data->id);
                // $data['status_code'] = 1; 
                // $data['status_message'] =  trans('messages.subscription.subscribe_to_make_listing');

                // $user = [
                //     'first_name' => $user_subscribe->user_details->first_name,
                //     'plan_name' => $user_subscribe->name,
                //     'end_date' => $user_subscribe->end_date,
                //     'no_listing' => $user_subscribe->no_of_listings,
                //     'email' => $user_subscribe->user_details->email,
                // ];
                // $email_controller = new EmailController;
                // $email_controller->subscription_email($user);
            }
        }

    }

    public function paypal_webhook(Request $request){
     
     $paypal_webhook_key =  get_setting('paypal_webhook_key');
     $event = $request->all();
     $data = [
      'transmission_id' => $_SERVER['HTTP_PAYPAL_TRANSMISSION_ID'],
      'transmission_time' => $_SERVER['HTTP_PAYPAL_TRANSMISSION_TIME'],
      'transmission_sig' => $_SERVER['HTTP_PAYPAL_TRANSMISSION_SIG'],
      'cert_url' => $_SERVER['HTTP_PAYPAL_CERT_URL'],
      'auth_algo' => $_SERVER['HTTP_PAYPAL_AUTH_ALGO'],
      'webhook_id' => $paypal_webhook_key,
      'webhook_event' => $event,

     ];
     \Log::info('Paypal Event :'.json_encode($data));
     \Log::info('Paypal Event Type:'.$request->event_type);
     if($request->event_type=='BILLING.SUBSCRIPTION.UPDATED')
      $this->update_paypal_subscription($event,'UPDATED');
     else if($request->event_type=='BILLING.SUBSCRIPTION.EXPIRED')
      $this->update_paypal_subscription($event,'EXPIRED');
     else if($request->event_type=='BILLING.SUBSCRIPTION.SUSPENDED')
      $this->update_paypal_subscription($event,'SUSPENDED');
     else if($request->event_type=='BILLING.SUBSCRIPTION.CANCELLED')
      $this->update_paypal_subscription($event,'CANCELLED'); 
    else if($request->event_type=='BILLING.SUBSCRIPTION.PAYMENT.FAILED')
      $this->update_paypal_subscription($event,'PAYMENT_FAILED');
      \Log::error($data);
     $plan =  $this->subscription_helper->paypal_subscription(json_encode($data),'notifications/verify-webhook-signature');
     if($plan->verification_status=='SUCCESS'){
      \Log::error('paypal webhook success');
     }else
      \Log::error('paypal webhook failed');


    }
public function update_paypal_subscription($data,$status){
        if(count($data)){
            $subscription_id = $data['resource']['id'];

            $user_subscribe = UserSubscriptionRenewal::where('subscription_id',$subscription_id)->first();
            \Log::info('Enter Paypal Subscripe object'.json_encode($user_subscribe ));
            if($user_subscribe){
              $user_subscription_plan = $user_subscribe->user_subscription_plan;
            // dd($user_subscribe,$user_subscription_plan);
              $data['status_code'] = 0 ;
              \Log::info('Enter Paypal Subscripe Plan object'.json_encode($user_subscription_plan ));
              if($user_subscription_plan)
              {
                // $user = [
                //     'first_name' => $user_subscription_plan->user_details->first_name,
                //     'plan_name' => $user_subscription_plan->name,
                //     'end_date' => $user_subscription_plan->end_date,
                //     'no_listing' => $user_subscription_plan->no_of_listings,
                //     'email' => $user_subscription_plan->user_details->email,
                // ];
                
\Log::info('Enter Paypal Status'.json_encode($user_subscription_plan ));
                if($status == 'UPDATED'){
                    $user_renewal = new UserSubscriptionRenewal;
                    $user_renewal->user_plan_id = $user_subscription_plan->id;
                    $user_renewal->subscription_id = $subscription_id;
                    $user_renewal->user_plan_id = $user_subscription_plan->id;
                    $user_renewal->user_id = $user_subscription_plan->user_id;
                    $user_renewal->transaction_id = $subscription_id;
                    $user_renewal->subscription_id = $subscription_id;             
                    $user_subscription_plan->status = 'Active';
                    $user_subscription_plan->alert_subscription = 0;
                    $user_renewal->payment_type = 'paypal';
                    $user_renewal->payment_status = 'Success';
                    $user_renewal->name = $user_subscription_plan->name;
                    $user_renewal->description = $user_subscription_plan->description;
                    $user_renewal->tagline = $user_subscription_plan->tagline;
                    $user_renewal->duration = $user_subscription_plan->duration;
                    $user_renewal->no_of_product = $user_subscription_plan->no_of_product;
                    $user_renewal->plan_type = 'Paid';           
                    $user_renewal->price = $user_subscription_plan->price;
                    $user_renewal->currency = $user_subscription_plan->currency;
                    $user_renewal->save();
                    $user_subscription_plan->save();
                    $product_count = Product::where('user_id',$user_subscription_plan->user_id)->where('published',1)->count();
                    if($product_count>$user_subscription_plan->no_of_product){
                     $product = Product::where('user_id',$user_subscription_plan->user_id)->where('published',1)/*->where('approved',1)*/->Orderby('id')->take($user_subscription_plan->no_of_product)->pluck('id');
                     Product::where('user_id',$user_subscription_plan->user_id)->whereNotIn('id',$product)->update(['published'=>0]);

                    }
                   $this->subscriptionSuccessMail($user_subscribe->user_details, $user_subscribe,'Paypal',$subscription_id);


                  // $email_controller = new EmailController;
                  // $email_controller->subscription_email($user);
                }elseif($status == 'CANCELLED'){
                  $user_subscription_plan->status = 'Inactive';
                  $user_subscription_plan->auto_renewal = 'Off';
                  $user_subscription_plan->customer_id=Null;
                  $user_subscription_plan->save();

                  Product::where('user_id',$user_subscription_plan->user_id)->update(['published'=>0]);
                   // $this->insufficientFundMail($user_subscribe->user_details, $user_subscribe,'Paypal');

                }else{
                  $user_subscription_plan->status = 'Inactive';
                  $user_subscription_plan->auto_renewal = 'Off';
                  $user_subscription_plan->customer_id=Null;
                  $user_subscription_plan->save();
                  // $email_controller = new EmailController;
                  // $email_controller->insufficient_fund($user);
                  Product::where('user_id',$user_subscription_plan->user_id)->update(['published'=>0]);
                  $this->insufficientFundMail($user_subscribe->user_details, $user_subscribe,'Paypal');
                }
                $data['status_code'] = 1; 
                $data['status_message'] =  translate('pls_subscribe_then_create_list');

              }
                return $data;
            }
        }

    }

     public function insufficientFundMail($user,$subscription,$payment_type='Stripe')
    {  
         // $user=User::first();
        // $subscription=SubscriptionPlan::first();
        
        $array['from_user'] =translate("hi")." ".$user->name;
        $array['subject'] = get_setting('site_name').' '.translate("subscription_canceled");
        $array['from'] = get_setting('mail_from_address');
        $array['content_1'] = translate("your_subscription_for")." ".$subscription->name." ".translate("plan_on")." ".get_setting('site_name')." ".translate("cancel_insufficient_fund");
        $array['content_2'] = translate("publish_your_products");
        $array['contact'] = translate("if_you_have_any_queries_contact_us_at").' '.get_setting('contact_phone').' & '.get_setting('contact_email'); 
        $array['view_file'] ='emails.common_mail';
        // return view('emails.common_mail',$array);
        \Log::info('Insuf Mail'.$user->email);
        try {
            \Mail::to($user->email)->queue(new CommonMailManager($array));
        } catch (\Exception $e) {   
        \Log::info('Insuf Mail error1'.json_encode($e));        
        } 
        $array=[];
        $user_subscribe = UserSubscriptionRenewal::where('user_plan_id',$subscription->id)->orderBy('id','desc')->first();
        $admin =  \App\User::where('user_type', 'admin')->first();
        $array['from_user'] =translate("hi")." ".$admin->name;
        $array['subject'] = translate("merchant_unsubscription_alert");
        $array['from'] = get_setting('mail_from_address');
        $array['content_1'] = translate("auto_cancel");
        $array['content_2'] = translate("merchant_id").': '.$user->id;
        $array['content_3'] = translate("merchant_name").': '.$user->name;
        $array['content_4'] = translate("plan_name").': '.$subscription->name;
        $array['content_5'] = translate("plan_cost").': '.$subscription->currency.$subscription->price;
        $array['content_6'] = translate("plan_duration").': '.$subscription->duration.' '.translate("months");
        $array['content_7'] = translate("payment_metdod").': '.$payment_type;
         \Log::info('Insuf admin Mail'.$admin->email);
        $array['content_8'] = translate("date_of_renewal").': '.@$subscription->next_renewel_date;
        $array['contact'] = ''; 
        $array['view_file'] ='emails.common_mail'; 
        \Log::info('Insufff admin Mail'.$admin->email);
         // return view('emails.common_mail',$array);
        try {
            \Mail::to($admin->email)->queue(new CommonMailManager($array));
        } catch (\Exception $e) {     \Log::info('Insuf Mail error2'.json_encode($e));              
        }

        return 'success';
    }
    public function newSubscriptionEmail($user,$subscription,$payment_type='Stripe',$transaction_id='')
    {  
         // $user=User::first();
        // $subscription=SubscriptionPlan::first();
        
      
        $array=[];
        $user_subscribe = UserSubscriptionRenewal::where('user_plan_id',$subscription->id)->orderBy('id','desc')->first();
        $admin =  \App\User::where('user_type', 'admin')->first();
        $array['from_user'] =translate("hi")." ".$admin->name;
        $array['subject'] = translate("new_merchant_subscription");
        $array['from'] = get_setting('mail_from_address');
        $array['content_1'] = translate("subscription_success");
        $array['content_2'] = translate("merchant_id").': '.$user->id;
        $array['content_3'] = translate("merchant_name").': '.$user->name;
        $array['content_4'] = translate("plan_name").': '.$subscription->name;
        $array['content_5'] = translate("plan_cost").': '.$subscription->currency.$subscription->price;
        $array['content_6'] = translate("plan_duration").': '.$subscription->duration.' '.translate("Months");
        $array['content_7'] = translate("payment_method").': '.translate($payment_type);
        $array['content_8'] = translate("date_of_payment").': '.@$user_subscribe->created_at_date;
        if($transaction_id)
        $array['content_9'] = translate("order_id").': '.@$transaction_id;
    
        $array['contact'] = ''; 
        $array['view_file'] ='emails.common_mail'; 
         // return view('emails.common_mail',$array);
        try {
            \Mail::to($admin->email)->queue(new CommonMailManager($array));
        } catch (\Exception $e) {           
        }

        return 'success';
    }
    public function subscriptionSuccessMail($user,$subscription,$payment_type='',$transaction_id='')
    {  
     // $user=User::first();
        // $subscription=SubscriptionPlan::first();
        $array=[];
        $array['from_user'] =translate("hi")." ".$user->name;
        $array['subject'] = translate("your")." ".get_setting('site_name')." ".$subscription->name.translate("subscription_is_successful");
        $array['subject'] = translate("your")." ".get_setting('site_name')." ".$subscription->name." ".translate("subscription_is_successful");
        $array['from'] = get_setting('mail_from_address');
        $array['content_1'] = translate("your_subscription_for")." ".$subscription->name." ".translate("plan_on")." ".get_setting('site_name')." ".translate("is_sub_success");
        $array['content_2'] = translate("subscription_date").' : '.date('F d, Y',strtotime($subscription->updated_at));
        $array['content_3'] = translate("plan_name").' : '.$subscription->name;
        $array['content_4'] = translate("price").' : '.format_price(currencyConvert($subscription->currency,'',$subscription->price));
        $array['content_5'] = translate("duration").' : '.$subscription->duration.' '.translate('Month');
        if($payment_type)
        $array['content_6'] = translate("payment_method").' : '.translate($payment_type);
        else
        $array['content_6'] = '';
        if($transaction_id)
        $array['content_7'] = translate("order_id").' : '.$transaction_id;
        $array['contact'] = translate("if_you_have_any_queries_contact_us_at").' '.get_setting('contact_phone').' & '.get_setting('contact_email'); 
        $array['view_file'] ='emails.common_mail';
        // return view('emails.common_mail',$array);
        try {
            \Mail::to($user->email)->queue(new CommonMailManager($array));
        } catch (\Exception $e) {   

        }

        return 'success';
    }  

    public function contactSubscriptionMail($user)
    {  
     // $user=User::first();
        // $subscription=SubscriptionPlan::first();
        $array=[];
        $admin =  \App\User::where('user_type', 'admin')->first();
        $array['from_user'] =translate("hi")." ".$admin->name;
        $array['subject'] = translate("new_contact_email");
        $array['from'] = get_setting('mail_from_address');
        $array['content_1'] = translate('name').' : '.$user['name'];
        $array['content_2'] = translate('email').' : '.$user['email'];
        $array['content_3'] = translate('feedback').' : '.$user['description'];
        $array['content_4'] = translate('thanks');
        $array['content_5'] = $user['name'];
        $array['contact'] = ''; 
        $array['view_file'] ='emails.common_mail'; 
        // return view('emails.common_mail',$array);
        
        try {
            \Mail::to($admin->email)->queue(new CommonMailManager($array));
        } catch (\Exception $e) {   
        // dd(1);        
        }

        return 'success';
    } 

    public function expiredSubscriptionAlert()
    {
        $user_subscription = UserSubscriptionPlan::with('users')->where('status','Active')->where('plan_type','Paid')->where('alert_subscription',0)->get(); 
        foreach ($user_subscription as $key => $value) {
            $next_renewel_date = date('Y-m-d',strtotime($value->next_renewel_date_convert.' -3 days'));
            $today_date = date('Y-m-d');
            if($next_renewel_date<=$today_date){
                $array=[];
                $array['from_user'] =translate("hi")." ".$value->users->name;
                $array['subject'] = translate("subscription_renewal_on").' '.get_setting('site_name');
                $array['from'] = get_setting('mail_from_address');

                $array['content_1'] = translate("your_subscription_for")." ".$value->name." ".translate("plan_on")." ".get_setting('site_name')." ".translate("will_renew_on")." ".$value->next_renewel_date.".". translate("keep_your_account_fund");
                $array['contact'] = translate("if_you_have_any_queries_contact_us_at").' '.get_setting('contact_phone').' & '.get_setting('contact_email'); 
                $array['view_file'] ='emails.common_mail'; 
                // return view('emails.common_mail',$array);
                try {
                    \Mail::to($value->users->email)->queue(new CommonMailManager($array));
                } catch (\Exception $e) {           
                }
               $value->alert_subscription = 1;
               $value->save();
            }
        }  
       

        return 'success';
    }
   
    public function contact()
    {   
        if(!$_POST)
        return view('frontend.contact');
        else{
             flash(translate('email_send_successfully_to').get_setting('site_name'))->success();  
             $data=request()->all();
            $this->contactSubscriptionMail($data);
            return back();
        }
    }
}
