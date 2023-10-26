<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Route;

class IsUser
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
        if (Auth::check() && 
                (Auth::user()->user_type == 'customer' || 
                Auth::user()->user_type == 'seller' || 
                Auth::user()->user_type == 'delivery_boy') ) {

            if (Auth::user()->user_type == 'seller' && isSingleStoreActivated() ) {
                abort(404);
            }
            
            return $next($request);
        } elseif (Auth::check() && Auth::user()->user_type == 'admin' && Route::current()->uri() == 'checkout') {
            flash(translate('admin_cant_buy'))->error();
            return back();
        }
        else{
            session(['link' => url()->current()]);
            return redirect()->route('user.login');
        }
    }
}
