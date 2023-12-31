<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class subscriptionCheck
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
        
        if(Auth::check() && Auth::user()->user_type == 'seller' && !Auth::user()->subscription_applied){
            return redirect()->route('subscription');
        }   
        return $next($request);        
    }
}
