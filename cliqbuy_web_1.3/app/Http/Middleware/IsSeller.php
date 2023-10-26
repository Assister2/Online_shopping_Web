<?php

namespace App\Http\Middleware;
use App\Models\UserSubscriptionPlan;
use App\Product;
use Closure;
use Auth;
use Route;

class IsSeller
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->user_type == 'seller'  && !Auth::user()->banned) {

            // Restrict Seller Route's
            if( isSingleStoreActivated() ) {
                abort(404);
            }

            if(!Auth::user()->subscription_applied && (Route::currentRouteName()=='seller.products.upload' || Route::currentRouteName()=='products.store'|| Route::currentRouteName()=='seller.products.edit')){
                $subscription=UserSubscriptionPlan::where('user_id',Auth::user()->id)->where('status','Active')->first();
                $total_product_count = Product::where('user_id',Auth::user()->id)->where('published',1)->count();

                if(!$subscription && get_setting('subscription')=='1')
                return redirect()->route('seller.subscription');
                elseif($subscription && get_setting('subscription')=='1' && $total_product_count>=$subscription->no_of_product){
                    if(Route::currentRouteName()=='seller.products.edit'){
                        $product = Product::findOrFail($request->id);
                        if($product->published!='1'){
                        flash(translate('kindly_upgrade'))->error();
                        return redirect()->route('seller.subscription');
                        }
                }else{
                 flash(translate('kindly_upgrade'))->error();
                 return redirect()->route('seller.subscription');
                }
                   
                }
            }
            if(get_setting('subscription')!='1' && (Route::currentRouteName()=='seller.subscription' ||  Route::currentRouteName()=='seller.subscription_history')){
                    $subscription=UserSubscriptionPlan::where('user_id',Auth::user()->id)->first();
                    if(Route::currentRouteName()=='seller.subscription_history'&& $subscription)
                        return $next($request);
                    else
                    abort(404);
                }
             
            return $next($request);
        } elseif(Auth::check() && Auth::user()->user_type == 'admin'){
            if(!Auth::user()->subscription_applied && (Route::currentRouteName()=='seller.products.upload' || Route::currentRouteName()=='products.store'|| Route::currentRouteName()=='seller.products.edit')){
                $subscription=UserSubscriptionPlan::where('user_id',Auth::user()->id)->where('status','Active')->first();
                $total_product_count = Product::where('user_id',Auth::user()->id)->where('published',1)->count();

                if(!$subscription && get_setting('subscription')=='1')
                return redirect()->route('seller.subscription');
                elseif($subscription && get_setting('subscription')=='1' && $total_product_count>=$subscription->no_of_product){
                    if(Route::currentRouteName()=='seller.products.edit'){
                        $product = Product::findOrFail($request->id);
                        if($product->published!='1'){
                        flash(translate('kindly_upgrade'))->error();
                        return redirect()->route('seller.subscription');
                        }
                }else{
                 flash(translate('kindly_upgrade'))->error();
                 return redirect()->route('seller.subscription');
                }
                   
                }
            }
            if(get_setting('subscription')!='1' && (Route::currentRouteName()=='seller.subscription' ||  Route::currentRouteName()=='seller.subscription_history')){
                    $subscription=UserSubscriptionPlan::where('user_id',Auth::user()->id)->first();
                    if(Route::currentRouteName()=='seller.subscription_history'&& $subscription)
                        return $next($request);
                    else
                    abort(404);
                }
             
            return $next($request);
        }
        else{
            abort(404);
        }
    }
}
