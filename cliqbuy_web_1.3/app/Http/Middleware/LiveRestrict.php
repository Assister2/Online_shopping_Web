<?php

namespace App\Http\Middleware;

use Session;
use Route;

class LiveRestrict
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        if (isLiveEnv()) {
            if (in_array(request()->segment(1),['admin','seller'])) {
                $url = url()->current();
                $destroy_url = strlen((string)stripos($url,"destroy"));
                $delete_url = strlen((string)stripos($url,"delete"));
                $admin_unrestricted_url = [
                    '/authenticate',
                    '/shop/apply_for_verification',
                ];

                $unrestricted_routes = [
                    'flash_deals.update_status',
                    'flash_deals.update_featured',
                    'flash_deals.product_discount',
                    'flash_deals.product_discount_edit',
                    'shop.verify.store',
                    'seller.payment'
                ];
                $admin_url = explode(url('/'.auth()->user()->user_type), $url);
                $ip_whitelist = explode(",",env('IP_ADDRESS'));
                if (($_POST||$delete_url||$destroy_url) && !in_array($admin_url,$admin_unrestricted_url) && !in_array($request->route()->getName(),$unrestricted_routes) ) {
                     if(in_array($_SERVER['REMOTE_ADDR'], $ip_whitelist)) {
                        return $next($request);
                    }
                    flash(translate('live_restriction'))->error();
                    return redirect(url()->previous());
                }
            }
            else {  // restriction for user urls
                $user_unrestricted_url = [
                    '/authenticate',
                    '/home/section/featured',
                    '/home/section/best_selling',
                    '/home/section/auction_products',
                    '/home/section/home_categories',
                    '/home/section/best_sellers',
                    '/shop/apply_for_verification',
                ];

                if (in_array(Route::current()->uri(),$user_unrestricted_url)) {
                    flash(translate('live_restriction'))->error();
                    return redirect(url()->previous());
                }
            }
        }

        $response = $next($request);
        $response->headers->set('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', 'Sun, 02 Jan 1990 00:00:00 GMT');

        return $response;
    }
}
