<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Route;

class IsAdmin
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
        if (Auth::check() && (Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'staff')) {

            // Restrict Admin Route's
            if( isSingleStoreActivated() ) {
                if( in_array(Route::current()->uri(), getRestrictedRoutes()) )
                    abort(404);
            }

            return $next($request);
        }
        else {
            abort(404);
        }
    }
}
