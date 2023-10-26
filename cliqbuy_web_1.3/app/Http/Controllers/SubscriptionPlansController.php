<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubscriptionPlan;
use App\Models\SubscriptionPlanTranslations;
use App\Models\UserSubscriptionPlan;
use App\Models\Language;
use App\Models\Currency;
use App\Product;

class SubscriptionPlansController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    // View Subscription Plan Detail
    public function index(Request $request)
    {
        $sort_search = null;            
        $subscription = SubscriptionPlan::orderBy('id', 'desc');

        if ($request->has('search')){
            $sort_search = $request->search;
            $subscription = SubscriptionPlan::where(function($user) use ($sort_search){
                $user->where('name', 'like', '%'.$sort_search.'%');
            });
        }

        $subscription = $subscription->paginate(15);
        return view('backend.subscription.index', compact('subscription', 'sort_search'));
        
    }

   // create New Subscription Plan
    public function add(Request $request)
    {   
        if(!$_POST){
            $data['currency'] = Currency::where('status','1')->pluck('code');
            $data['languages'] = Language::pluck('name','code');
            return view('backend.subscription.create',$data);            
        }else{
            
            $subscription = new SubscriptionPlan;
            $subscription->name=$request->plan_name;
            $subscription->description=$request->description;
            $subscription->tagline=$request->tagline;
             $subscription->status=$request->status;  
            if(!isset($request->custom_plan)){
                if(!isset($request->is_free)){
                    $subscription->price=$request->price;
                    $subscription->currency=$request->currency;
                }else
                $subscription->is_free='Yes'; 
                    $subscription->duration=$request->duration;                                 
                $subscription->no_of_product=$request->no_of_products;
            }else 
                $subscription->custom_plan='Yes'; 
                $subscription->save();

            foreach($request->translations ?: array() as $translation_data) {  
                $translation = $subscription->getTranslationById(@$translation_data['locale'], $translation_data['id']);
                $translation->name = $translation_data['name'];
                $translation->description = $translation_data['description'];
                $translation->tagline = $translation_data['tagline'];

                $translation->save();
            }
            
           flash(translate('subscription_inserted'))->success();
           return redirect()->route('subscription.index');
           
        }
    }

    // Update Subscription Plan
    public function edit(Request $request)
    {   
        if(!$_POST){
            $data['subscription'] = SubscriptionPlan::find($request->id);
            if(!$data['subscription']){
                flash(translate('subscription_already_deleted'))->error();
                return redirect()->route('subscription.index');
            }
            $data['currency'] = Currency::where('status','1')->pluck('code');
            $data['languages'] = Language::pluck('name','code');
            return view('backend.subscription.edit',$data);            
        }else{
            $subscription = SubscriptionPlan::find($request->id);
            $result=[];
            if(!$subscription){
                flash(translate('subscription_already_deleted'))->error();
                return redirect()->route('subscription.index');
            }
            $subscription->name=$request->plan_name;
            $subscription->description=$request->description;
            $subscription->tagline=$request->tagline;
             $subscription->status=$request->status;  
            // if(!isset($request->custom_plan)){
            //     $result['custom_plan']='No';
            //     if(!isset($request->is_free)){
            //         $result['is_free']='No';
            //         $subscription->price=$request->price;
            //         $subscription->currency=$request->currency;
            //     }else{
            //      $subscription->is_free='Yes'; 
            //      $result['price']=0;   
            //      $result['currency']=Null;   
            //     }
            // $subscription->duration=$request->duration;             
            $subscription->no_of_product=$request->no_of_products;                
            // }else{
            //     $subscription->no_of_product=Null;                
            //     $subscription->custom_plan='Yes'; 
            //     $result['is_free']='No';
            //     $result['price']=0;
            //     $result['duration']=0;
            //     $result['currency']=Null;
            // } 
            $subscription->save();

            $removed_translations = explode(',', $request->removed_translations);
                foreach(array_values($removed_translations) as $id) {
                    $subscription->deleteTranslationById($id);
                }

                foreach($request->translations ?: array() as $translation_data) {  
                    $translation = $subscription->getTranslationById(@$translation_data['locale'], $translation_data['id']);
                    $translation->name = $translation_data['name'];
                    $translation->description = $translation_data['description'];
                    $translation->tagline = $translation_data['tagline'];

                    $translation->save();
            }
            // if(count($result))
            // SubscriptionPlan::where('id',$request->id)->update($result);
            flash(translate('subscription_updated'))->success();
           return redirect()->route('subscription.index');
           
        }
    }

    // Delete Subscription Plan
    public function delete(Request $request)
    {
        $subscription = SubscriptionPlan::find($request->id);
        $user_subscription = UserSubscriptionPlan::where('subscription_plan_id',$request->id)->first();
        if($user_subscription){
            flash(translate('cant_delete_subscription'))->error();
            return redirect()->route('subscription.index');
        }
        if(!$subscription){
            flash(translate('subscription_already_deleted'))->error();
            return redirect()->route('subscription.index');
        }else{
            SubscriptionPlanTranslations::where('subscription_plan_id',$request->id)->delete();
            $subscription->delete();
            flash(translate('Subscription has been deleted successfully'))->success();
            return redirect()->route('subscription.index');
        }
    }

    // Delete Subscription Plan
    public function user_custom_subscription(Request $request)
    {  
        $exist_product_count=0;
        // if($request->is_free && $request->is_free=='on')
        // $user_subscription = UserSubscriptionPlan::where('user_id',$request->merchant_id)->first();
        // else
        $user_subscription = UserSubscriptionPlan::where('user_id',$request->merchant_id)->where('plan_type','Custom')->first();
        if(!$user_subscription)
        $user_subscription = new UserSubscriptionPlan;
        else{
            if($user_subscription->plan_type != 'Custom')
             $exist_product_count=$user_subscription->no_of_product;
        }
            
        // $request->is_free=='Yes'?'Custom':'Paid';  
        $user_subscription->user_id = $request->merchant_id;
        $user_subscription->subscription_plan_id = $request->plan_name_id;
        $user_subscription->name = $request->plan_name_value;
        $user_subscription->description = $request->description;
        $user_subscription->tagline = $request->tagline;
        $user_subscription->duration = $request->duration;
        $user_subscription->no_of_product = $request->no_of_products;
        $user_subscription->plan_type = 'Custom';       
        $user_subscription->currency = $request->currency??'USD';
        $user_subscription->price = (isset($request->is_free)&&$request->is_free=='on')?0:$request->price;
        $user_subscription->status ='Inactive';        
        $user_subscription->auto_renewal = 'On';
        $user_subscription->save();

        //  if($exist_product_count>$request->no_of_products){
        //  $product = Product::where('user_id',$request->merchant_id)->where('published',1)/*->where('approved',1)*/->Orderby('id')->take($request->no_of_product)->pluck('id');
        //  Product::where('user_id',$request->merchant_id)->whereNotIn('id',$product)->update(['published'=>0]);
            
        // }

         flash(translate('subs_added_for_merchant'))->success();
         return back();
    }

}
