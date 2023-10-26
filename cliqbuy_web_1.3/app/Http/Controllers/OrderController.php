<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AffiliateController;
use App\Http\Controllers\OTPVerificationController;
use Illuminate\Http\Request;
use App\Http\Controllers\ClubPointController;
use App\Order;
use App\Cart;
use App\Address;
use App\Product;
use App\ProductStock;
use App\CommissionHistory;
use App\Color;
use App\OrderDetail;
use App\CouponUsage;
use App\Coupon;
use App\OtpConfiguration;
use App\User;
use App\BusinessSetting;
use App\SmsTemplate;
use App\Models\OweAmount;
use App\Models\ShipEngine;
use Auth;
use Session;
use DB;
use Mail;
use App\Mail\InvoiceEmailManager;
use App\Repositories\StripePayment;
use App\Http\SubscriptionHelper;
use Validator;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use App\Utility\SmsUtility;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource to seller.
     *
     * @return \Illuminate\Http\Response
     */
    
    // public function __construct(SubscriptionHelper $subscription)
    //  {
    //     $this->subscription_helper = $subscription;
    //  }

    public function index(Request $request)
    {
        $payment_status = null;
        $delivery_status = null;
        $sort_search = null;
        $orders = DB::table('orders')
            ->orderBy('id', 'desc')
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->where('order_details.seller_id', Auth::user()->id)
            ->select('orders.id')
            ->groupBy('order_id')
            ->distinct();

            $merchant_amount = Order::select('orders.id','code')->with(['owe_amount','order_details'])
                ->whereHas('order_details', function ($query) {
                    $query->where('seller_id', Auth::user()->id);
                })->whereHas('owe_amount', function ($query) {
                    $query->where('status', 'Pending');
                })->get();

            $owe_amount_order = $merchant_amount->map(function($query){
                return (object) [
                    'id' => $query->id,
                    'code' => $query->code,
                    'remain_amount' => $query->owe_amount->convert_remain_amount,
                ];
            });

        if ($request->payment_status != null) {
            $orders = $orders->where('orders.payment_status', $request->payment_status);
            $payment_status = $request->payment_status;
        }
        if ($request->delivery_status != null) {
            $orders = $orders->where('orders.delivery_status', $request->delivery_status);
            $delivery_status = $request->delivery_status;
        }
        if ($request->has('search') && $request->search!=null) {
            $sort_search = $request->search;
            $orders = $orders->where('code', 'like', '%' . $sort_search . '%');
        }

        $orders = $orders->paginate(15);

        foreach ($orders as $key => $value) {
            $order = \App\Order::find($value->id);
            $order->viewed = 1;
            $order->save();
        }

        $shipengines = ShipEngine::pluck('name')->toArray();

        return view('frontend.user.seller.orders', compact('orders', 'payment_status', 'delivery_status', 'sort_search','owe_amount_order', 'shipengines'));
    }

    // All Orders
    public function all_orders(Request $request)
    {
        

        $date = $request->date;
        $sort_search = null;
        $delivery_status = null;

        $orders = Order::orderBy('id', 'desc');
        if ($request->has('search')) {
            $sort_search = $request->search;
            $orders = $orders->where('code', 'like', '%' . $sort_search . '%');
        }
        if ($request->delivery_status != null) {
            $orders = $orders->where('delivery_status', $request->delivery_status);
            $delivery_status = $request->delivery_status;
        }
        if ($date != null) {
            $orders = $orders->where('created_at', '>=', date('Y-m-d', strtotime(explode(" to ", $date)[0])))->where('created_at', '<=', date('Y-m-d', strtotime(explode(" to ", $date)[1])));
        }
        $orders = $orders->paginate(15);
        return view('backend.sales.all_orders.index', compact('orders', 'sort_search', 'delivery_status', 'date'));
    }

    public function all_orders_show($id)
    {
        $order = Order::findOrFail(decrypt($id));
        $order_shipping_address = json_decode($order->shipping_address);
        $city = $order_shipping_address->city ?? '';
        $delivery_boys = User::where('city', $city)
            ->where('user_type', 'delivery_boy')
            ->get();

        $shipengines = ShipEngine::pluck('name')->toArray();
        return view('backend.sales.all_orders.show', compact('order', 'delivery_boys', 'shipengines'));
    }

    // Inhouse Orders
    public function admin_orders(Request $request)
    {    
        $date = $request->date;
        $payment_status = null;
        $delivery_status = null;
        $sort_search = null;
        $admin_user_id = User::where('user_type', 'admin')->first()->id;
        $orders = DB::table('orders')
            ->orderBy('id', 'desc')
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->where('order_details.seller_id', $admin_user_id)
            ->select('orders.id')
            ->groupBy('order_id')
            ->distinct();

        if ($request->payment_type != null) {
            $orders = $orders->where('orders.payment_status', $request->payment_type);
            $payment_status = $request->payment_type;
        }
        if ($request->delivery_status != null) {
            $orders = $orders->where('orders.delivery_status', $request->delivery_status);
            $delivery_status = $request->delivery_status;
        }
        if ($request->has('search') && $request->search!=null) {
            $sort_search = $request->search;
            $orders = $orders->where('code', 'like', '%' . $sort_search . '%');
        }
        if ($date != null) {
            $orders = $orders->whereDate('orders.created_at', '>=', date('Y-m-d', strtotime(explode(" to ", $date)[0])))->whereDate('orders.created_at', '<=', date('Y-m-d', strtotime(explode(" to ", $date)[1])));
        }
        $orders = $orders->paginate(15);
        return view('backend.sales.inhouse_orders.index', compact('orders', 'payment_status', 'delivery_status', 'sort_search', 'admin_user_id', 'date'));
    }

    public function show($id)
    {
        $order = Order::findOrFail(decrypt($id));
        $order_shipping_address = json_decode($order->shipping_address);
        $delivery_boys = User::where('city', $order_shipping_address->city)
            ->where('user_type', 'delivery_boy')
            ->get();

        $order->viewed = 1;
        $order->save();

        $shipengines = ShipEngine::pluck('name')->toArray();
        return view('backend.sales.inhouse_orders.show', compact('order', 'delivery_boys', 'shipengines'));
    }

    // Seller Orders
    public function seller_orders(Request $request)
    {
        $date = $request->date;
        $seller_id = $request->seller_id;
        $payment_status = null;
        $delivery_status = null;
        $sort_search = null;
        $admin_user_id = User::where('user_type', 'admin')->first()->id;
        $orders = DB::table('orders')
            ->orderBy('code', 'desc')
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->where('order_details.seller_id', '!=', $admin_user_id)
            ->where('order_details.shipping_type','!=','pickup_point')
            ->select('orders.id')
            ->groupBy('order_id')
            ->distinct();

        if ($request->payment_type != null) {
            $orders = $orders->where('orders.payment_status', $request->payment_type);
            $payment_status = $request->payment_type;
        }
        if ($request->delivery_status != null) {
            $orders = $orders->where('orders.delivery_status', $request->delivery_status);
            $delivery_status = $request->delivery_status;
        }
        if ($request->has('search') && $request->search!=null) {
            $sort_search = $request->search;
            $orders = $orders->where('code', 'like', '%' . $sort_search . '%');
        }
        if ($date != null) {
            $orders = $orders->whereDate('orders.created_at', '>=', date('Y-m-d', strtotime(explode(" to ", $date)[0])))->whereDate('orders.created_at', '<=', date('Y-m-d', strtotime(explode(" to ", $date)[1])));
        }
        if ($request->seller_id!=null) {
            $orders = $orders->where('order_details.seller_id', $request->seller_id);
            $seller_id = $request->seller_id;
        }

        $orders = $orders->paginate(15);

        return view('backend.sales.seller_orders.index', compact('orders', 'payment_status', 'delivery_status', 'sort_search', 'admin_user_id', 'seller_id', 'date'));
    }

    public function seller_orders_show($id)
    {
        $order = Order::findOrFail(decrypt($id));
        $order->viewed = 1;
        $order->save();

        $shipengines = ShipEngine::pluck('name')->toArray();

        return view('backend.sales.seller_orders.show', compact('order', 'shipengines'));
    }


    // Pickup point orders
    public function pickup_point_order_index(Request $request)
    {
        $date = $request->date;
        $sort_search = null;

        if (Auth::user()->user_type == 'staff' && Auth::user()->staff->pick_up_point != null) {
            //$orders = Order::where('pickup_point_id', Auth::user()->staff->pick_up_point->id)->get();
            $orders = DB::table('orders')
                ->orderBy('code', 'desc')
                ->join('order_details', 'orders.id', '=', 'order_details.order_id')
                ->where('order_details.pickup_point_id', Auth::user()->staff->pick_up_point->id)
                ->select('orders.id')
                ->distinct();

            if ($request->has('search') && $request->search!=null) {
                $sort_search = $request->search;
                $orders = $orders->where('code', 'like', '%' . $sort_search . '%');
            }
            if ($date != null) {
                $orders = $orders->whereDate('orders.created_at', '>=', date('Y-m-d', strtotime(explode(" to ", $date)[0])))->whereDate('orders.created_at', '<=', date('Y-m-d', strtotime(explode(" to ", $date)[1])));
            }

            $orders = $orders->paginate(15);

            return view('backend.sales.pickup_point_orders.index', compact('orders', 'sort_search', 'date'));
        } else {
            //$orders = Order::where('shipping_type', 'Pick-up Point')->get();
            $orders = DB::table('orders')
                ->orderBy('code', 'desc')
                ->join('order_details', 'orders.id', '=', 'order_details.order_id')
                ->where('order_details.shipping_type', 'pickup_point')
                ->select('orders.id')
                ->distinct();

            if ($request->has('search') && $request->search!=null) {
                $sort_search = $request->search;
                $orders = $orders->where('code', 'like', '%' . $sort_search . '%');
            }
            if ($date != null) {
                $orders = $orders->whereDate('orders.created_at', '>=', date('Y-m-d', strtotime(explode(" to ", $date)[0])))->whereDate('orders.created_at', '<=', date('Y-m-d', strtotime(explode(" to ", $date)[1])));
            }

            $orders = $orders->paginate(15);

            return view('backend.sales.pickup_point_orders.index', compact('orders', 'sort_search', 'date'));
        }
    }

    public function pickup_point_order_sales_show($id)
    {
        if (Auth::user()->user_type == 'staff') {
            $order = Order::findOrFail(decrypt($id));
            $order_shipping_address = json_decode($order->shipping_address);
            $delivery_boys = User::where('city', $order_shipping_address->city)
                ->where('user_type', 'delivery_boy')
                ->get();

            return view('backend.sales.pickup_point_orders.show', compact('order', 'delivery_boys'));
        } else {
            $order = Order::findOrFail(decrypt($id));
            $order_shipping_address = json_decode($order->shipping_address);

            $delivery_boys = User::where('city', $order_shipping_address->city)
                ->where('user_type', 'delivery_boy')
                ->get();

            return view('backend.sales.pickup_point_orders.show', compact('order', 'delivery_boys'));
        }
    }

    /**
     * Display a single sale to admin.
     *
     * @return \Illuminate\Http\Response
     */


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $carts = Cart::where('user_id', Auth::user()->id)
            ->get();

        if ($carts->isEmpty()) {
            flash(translate('your_cart_is_empty'))->warning();
            return redirect()->route('home');
        }

        $shipping_info = Address::where('id', $carts[0]['address_id'])->first();
        $shipping_info->name = Auth::user()->name;
        $shipping_info->email = Auth::user()->email;
        if ($shipping_info->latitude || $shipping_info->longitude) {
            $shipping_info->lat_lang = $shipping_info->latitude . ',' . $shipping_info->longitude;
        }

        $subtotal = 0;
        $tax = 0;
        $shipping = 0;

        $order_ids = [];

        //Order Details Storing
        foreach ($carts as $key => $cartItem) {
            $order = new Order;
            if (Auth::check()) {
                $order->user_id = Auth::user()->id;
            } else {
                $order->guest_id = mt_rand(100000, 999999);
            }

            $order->shipping_address = json_encode($shipping_info);
            $order->payment_type = $request->payment_option;
            $order->shipping_method = get_setting('shipping_type');
            $order->delivery_viewed = '0';
            $order->payment_status_viewed = '0';
            $order->code = date('Ymd-His') . rand(10, 99);
            $order->date = strtotime('now');
            $order->save();

            $order_ids[$key] = $order->id;

            $product = Product::find($cartItem['product_id']);

            $subtotal = $cartItem['price'] * $cartItem['quantity'];
            $tax = $cartItem['tax'] * $cartItem['quantity'];

            $product_variation = $cartItem['variation'];

            $product_stock = $product->stocks->where('variant', $product_variation)->first();
            if ($product->digital != 1 && $cartItem['quantity'] > $product_stock->qty) {
                flash(translate('req_quantity_not_avail') . $product->getTranslation('name'))->warning();
                $order->delete();
                return redirect()->route('cart')->send();
            } elseif ($product->digital != 1) {
                $product_stock->qty -= $cartItem['quantity'];
                $product_stock->save();
            }

            $order_detail = new OrderDetail;
            $order_detail->order_id = $order->id;
            $order_detail->seller_id = $cartItem['owner_id'];
            $order_detail->product_id = $product->id;
            $order_detail->variation = $product_variation;
            $order_detail->price = $cartItem['price'] * $cartItem['quantity'];
            $order_detail->tax = $cartItem['tax'] * $cartItem['quantity'];
            $order_detail->shipping_type = $cartItem['shipping_type'];
            $order_detail->product_referral_code = $cartItem['product_referral_code'];
            $order_detail->shipping_cost = $cartItem['shipping_cost'];
            $order_detail->rate_id = $cartItem['rate_id'];
            $order_detail->service_code = $cartItem['service_type'];
            $order_detail->package_type = $cartItem['package_type'];

            $shipping = $order_detail->shipping_cost;

            if ($cartItem['shipping_type'] == 'pickup_point') {
                $order_detail->pickup_point_id = $cartItem['pickup_point'];
            }
            //End of storing shipping cost

            $order_detail->quantity = $cartItem['quantity'];
            $order_detail->save();

            $product->num_of_sale++;
            $product->save();

            if (\App\Addon::where('unique_identifier', 'affiliate_system')->first() != null &&
                \App\Addon::where('unique_identifier', 'affiliate_system')->first()->activated) {
                if ($order_detail->product_referral_code) {
                    $referred_by_user = User::where('referral_code', $order_detail->product_referral_code)->first();

                    $affiliateController = new AffiliateController;
                    $affiliateController->processAffiliateStats($referred_by_user->id, 0, $order_detail->quantity, 0, 0);
                }
            }

            $order->grand_total = $subtotal + $tax + $shipping;

            if($cartItem->coupon_code != '') {
                $order->grand_total -= $cartItem->discount;
                if ($order->grand_total < 0) {
                    $order->grand_total = 0;
                    $order->payment_status = 'paid';
                }
                if (Session::has('club_point')) {
                    $order->club_point = Session::get('club_point');
                }
                $order->coupon_discount = $cartItem->discount;

                $coupon_usage = new CouponUsage;
                $coupon_usage->user_id = Auth::user()->id;
                $coupon_usage->coupon_id = $cartItem->coupon_code;
                $coupon_usage->save();
            }

            $order->save();

            if (\App\Addon::where('unique_identifier', 'otp_system')->first() != null &&
            \App\Addon::where('unique_identifier', 'otp_system')->first()->activated &&
            SmsTemplate::where('identifier', 'order_placement')->first()->status == 1) {
                try {
                    $otpController = new OTPVerificationController;
                    $otpController->send_order_code($order);
                } catch (\Exception $e) {

                }
            }

            //sends Notifications to user
            send_notification($order, 'placed');
            if (get_setting('google_firebase') == 1 && $order->user->device_token != null) {
                $request->device_token = $order->user->device_token;
                $request->title = "Order placed !";
                $request->text = " An order {$order->code} has been placed";

                $request->type = "order";
                $request->id = $order->id;
                $request->user_id = $order->user->id;

                send_firebase_notification($request);
            }

            $array['view'] = 'emails.invoice';
            $array['subject'] = translate('Your order has been placed') . ' - ' . $order->code;
            $array['from'] = get_setting('mail_from_address');
            $array['order'] = $order;

            //sends email to customer with the invoice pdf attached
            if (get_setting('mail_username') != null) {
                try {
                    Mail::to(Auth::user()->email)->queue(new InvoiceEmailManager($array));
                    Mail::to(User::where('user_type', 'admin')->first()->email)->queue(new InvoiceEmailManager($array));
                } catch (\Exception $e) {
                    logger('mail error - '. $e->getMessage());
                }
            }

            if ($product->added_by != 'admin') {
                try {
                    Mail::to(\App\User::find($product->user_id)->email)->queue(new InvoiceEmailManager($array));
                } catch (\Exception $e) {
                    logger('mail error - '. $e->getMessage());
                }
            }
        }

        $request->session()->put('order_id', $order_ids);
    }

    public function checkout_store($payment_option)
    {  
        $carts = Cart::where('user_id', Auth::user()->id)
            ->get();    
        if ($carts->isEmpty()) {
            flash(translate('your_cart_is_empty'))->warning();
            return redirect()->route('home');
        }

        $shipping_info = Address::where('id', $carts[0]['address_id'])->first();
        $shipping_info->name = Auth::user()->name;
        $shipping_info->email = Auth::user()->email;
        if ($shipping_info->latitude || $shipping_info->longitude) {
            $shipping_info->lat_lang = $shipping_info->latitude . ',' . $shipping_info->longitude;
        }

        $subtotal = 0;
        $tax = 0;
        $shipping = 0;

        //calculate shipping is to get shipping costs of different types
        $admin_products = array();
        $seller_products = array();

        //Order Details Storing
        foreach ($carts as $key => $cartItem) {
            $request = new Request;
            $order = new Order;
            if (Auth::check()) {
                $order->user_id = Auth::user()->id;
            } else {
                $order->guest_id = mt_rand(100000, 999999);
            }

            $order->shipping_address = json_encode($shipping_info);
            $order->payment_type = $payment_option;
            $order->shipping_method = get_setting('shipping_type');
            $order->delivery_viewed = '0';
            $order->payment_status_viewed = '0';
            $order->code = date('Ymd-His') . rand(10, 99);
            $order->date = strtotime('now');
            $order->save();

            $order_ids[$key] = $order->id;

            $product = Product::find($cartItem['product_id']);

            $subtotal = $cartItem['price'] * $cartItem['quantity'];
            $tax = $cartItem['tax'] * $cartItem['quantity'];

            $product_variation = $cartItem['variation'];

            $product_stock = $product->stocks->where('variant', $product_variation)->first();
            if ($product->digital != 1 && $cartItem['quantity'] > $product_stock->qty) {
                flash(translate('req_quantity_not_avail') . $product->getTranslation('name'))->warning();
                $order->delete();
                return redirect()->route('cart')->send();
            } elseif ($product->digital != 1) {
                $product_stock->qty -= $cartItem['quantity'];
                $product_stock->save();
            }

            $order_detail = new OrderDetail;
            $order_detail->order_id = $order->id;
            $order_detail->seller_id = $cartItem['owner_id'];
            $order_detail->product_id = $product->id;
            $order_detail->variation = $product_variation;
            $order_detail->price = $cartItem['price'] * $cartItem['quantity'];
            $order_detail->tax = $cartItem['tax'] * $cartItem['quantity'];
            $order_detail->shipping_type = $cartItem['shipping_type'];
            $order_detail->product_referral_code = $cartItem['product_referral_code'];
            $order_detail->shipping_cost = $cartItem['shipping_cost'];
            $order_detail->rate_id = $cartItem['rate_id'];
            $order_detail->service_code = $cartItem['service_type'];
            $order_detail->package_type = $cartItem['package_type'];
            
            $shipping = $order_detail->shipping_cost;

            if ($cartItem['shipping_type'] == 'pickup_point') {
                $order_detail->pickup_point_id = $cartItem['pickup_point'];
            }
            //End of storing shipping cost

            $order_detail->quantity = $cartItem['quantity'];
            $order_detail->save();

            $product->num_of_sale++;
            $product->save();

            if (\App\Addon::where('unique_identifier', 'affiliate_system')->first() != null &&
                \App\Addon::where('unique_identifier', 'affiliate_system')->first()->activated) {
                if ($order_detail->product_referral_code) {
                    $referred_by_user = User::where('referral_code', $order_detail->product_referral_code)->first();

                    $affiliateController = new AffiliateController;
                    $affiliateController->processAffiliateStats($referred_by_user->id, 0, $order_detail->quantity, 0, 0);
                }
            }

            $order->grand_total = $subtotal + $tax + $shipping;

            if($cartItem->coupon_code != '') {
                $order->grand_total -= $cartItem->discount;
                if ($order->grand_total < 0) {
                    $order->grand_total = 0;
                    $order->payment_status = 'paid';
                }
                if (Session::has('club_point')) {
                    $order->club_point = Session::get('club_point');
                }
                $order->coupon_discount = $cartItem->discount;

                $coupon_usage = new CouponUsage;
                $coupon_usage->user_id = Auth::user()->id;
                $coupon_usage->coupon_id = $cartItem->coupon_code;
                $coupon_usage->save();
            }

            $order->save();
            
            if (\App\Addon::where('unique_identifier', 'otp_system')->first() != null &&
            \App\Addon::where('unique_identifier', 'otp_system')->first()->activated &&
            SmsTemplate::where('identifier', 'order_placement')->first()->status == 1) {
                try {
                    $otpController = new OTPVerificationController;
                    $otpController->send_order_code($order);
                } catch (\Exception $e) {

                }
            }

            //sends Notifications to user
            send_notification($order, 'placed');
            if (get_setting('google_firebase') == 1 && $order->user->device_token != null) {
                $request->device_token = $order->user->device_token;
                $request->title = "Order placed !";
                $request->text = " An order {$order->code} has been placed";

                $request->type = "order";
                $request->id = $order->id;
                $request->user_id = $order->user->id;

                send_firebase_notification($request);
            }

            $array['view'] = 'emails.invoice';
            $array['subject'] = translate('Your order has been placed') . ' - ' . $order->code;
            $array['from'] = get_setting('mail_from_address');
            $array['order'] = $order;

            //sends email to customer with the invoice pdf attached
            if (get_setting('mail_username') != null) {
                try {
                    Mail::to(Auth::user()->email)->queue(new InvoiceEmailManager($array));
                    Mail::to(User::where('user_type', 'admin')->first()->email)->queue(new InvoiceEmailManager($array));
                } catch (\Exception $e) {
                    logger('mail error - '. $e->getMessage());
                }
            }

            if ($product->added_by != 'admin') {
                try {
                    Mail::to(\App\User::find($product->user_id)->email)->queue(new InvoiceEmailManager($array));
                } catch (\Exception $e) {
                    logger('mail error - '. $e->getMessage());
                }
            }
        }

        Session::put('order_id', $order_ids);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */


    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (isLiveEnv() && auth()->user()->user_type == 'admin') {
            flash(translate('live_restriction'))->error();
            return redirect(url()->previous());
        }
        $order = Order::findOrFail($id);
        if ($order != null) {
            foreach ($order->orderDetails as $key => $orderDetail) {
                try {

                    $product_stock = ProductStock::where('product_id', $orderDetail->product_id)->where('variant', $orderDetail->variation)->first();
                    if ($product_stock != null) {
                        $product_stock->qty += $orderDetail->quantity;
                        $product_stock->save();
                    }

                } catch (\Exception $e) {

                }

                $orderDetail->delete();
            }
            $order->delete();
            flash(translate('order_deleted'))->success();
        } else {
            flash(translate('something_went_wrong'))->error();
        }
        return back();
    }

    public function bulk_order_delete(Request $request)
    {
        if ($request->id) {
            foreach ($request->id as $order_id) {
                $this->destroy($order_id);
            }
        }

        return 1;
    }

    public function order_details(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        $order->save();
        return view('frontend.user.seller.order_details_seller', compact('order'));
    }

    public function update_delivery_status(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        $order->delivery_viewed = '0';
        $order->delivery_status = $request->status;
        $order->save();

        if ($request->status == 'cancelled' && $order->payment_type == 'wallet') {
            $user = User::where('id', $order->user_id)->first();
            $user->balance += $order->grand_total;
            $user->save();
        }

        if (Auth::user()->user_type == 'seller') {
            foreach ($order->orderDetails->where('seller_id', Auth::user()->id) as $key => $orderDetail) {
                $orderDetail->delivery_status = $request->status;
                $orderDetail->save();

                if ($request->status == 'cancelled') {
                    $variant = $orderDetail->variation;
                    if ($orderDetail->variation == null) {
                        $variant = '';
                    }

                    $product_stock = ProductStock::where('product_id', $orderDetail->product_id)
                        ->where('variant', $variant)
                        ->first();

                    if ($product_stock != null) {
                        $product_stock->qty += $orderDetail->quantity;
                        $product_stock->save();
                    }
                }
            }
        } else {
            foreach ($order->orderDetails as $key => $orderDetail) {

                $orderDetail->delivery_status = $request->status;
                $orderDetail->save();

                if ($request->status == 'cancelled') {
                    $variant = $orderDetail->variation;
                    if ($orderDetail->variation == null) {
                        $variant = '';
                    }

                    $product_stock = ProductStock::where('product_id', $orderDetail->product_id)
                        ->where('variant', $variant)
                        ->first();

                    if ($product_stock != null) {
                        $product_stock->qty += $orderDetail->quantity;
                        $product_stock->save();
                    }
                }

                if (\App\Addon::where('unique_identifier', 'affiliate_system')->first() != null && \App\Addon::where('unique_identifier', 'affiliate_system')->first()->activated) {
                    if (($request->status == 'delivered' || $request->status == 'cancelled') &&
                        $orderDetail->product_referral_code) {

                        $no_of_delivered = 0;
                        $no_of_canceled = 0;

                        if ($request->status == 'delivered') {
                            $no_of_delivered = $orderDetail->quantity;
                        }
                        if ($request->status == 'cancelled') {
                            $no_of_canceled = $orderDetail->quantity;
                        }

                        $referred_by_user = User::where('referral_code', $orderDetail->product_referral_code)->first();

                        $affiliateController = new AffiliateController;
                        $affiliateController->processAffiliateStats($referred_by_user->id, 0, 0, $no_of_delivered, $no_of_canceled);
                    }
                }
            }
        }
        if (\App\Addon::where('unique_identifier', 'otp_system')->first() != null &&
            \App\Addon::where('unique_identifier', 'otp_system')->first()->activated &&
            SmsTemplate::where('identifier', 'delivery_status_change')->first()->status == 1) {
            try {
                SmsUtility::delivery_status_change($order->user->phone, $order);
            } catch (\Exception $e) {

            }
        }

        //sends Notifications to user
        send_notification($order, $request->status);
        if (get_setting('google_firebase') == 1 && $order->user->device_token != null) {
            $request->device_token = $order->user->device_token;
            $request->title = "Order updated !";
            $status = str_replace("_", "", $order->delivery_status);
            $request->text = " Your order {$order->code} has been {$status}";

            $request->type = "order";
            $request->id = $order->id;
            $request->user_id = $order->user->id;

            send_firebase_notification($request);
        }


        if (\App\Addon::where('unique_identifier', 'delivery_boy')->first() != null &&
            \App\Addon::where('unique_identifier', 'delivery_boy')->first()->activated) {

            if (Auth::user()->user_type == 'delivery_boy') {
                $deliveryBoyController = new DeliveryBoyController;
                $deliveryBoyController->store_delivery_history($order);
            }
        }

        return 1;
    }

//    public function bulk_order_status(Request $request) {
////        dd($request->all());
//        if($request->id) {
//            foreach ($request->id as $order_id) {
//                $order = Order::findOrFail($order_id);
//                $order->delivery_viewed = '0';
//                $order->save();
//
//                $this->change_status($order, $request);
//            }
//        }
//
//        return 1;
//    }

    public function update_payment_status(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        if ($request->has('order_detail_id')) {
            $order_detail = OrderDetail::findOrFail($request->order_detail_id);
        }
        $order->payment_status_viewed = '0';
        $order->save();
            
        
            if ($request->has('status')) {
                $order_detail->payment_status = $request->status;
                $order_detail->save();

                $payment_status = \DB::table('order_details')->where('order_id',$request->order_id)->where('payment_status',$request->status)->count();
                $order_payment_status = \DB::table('order_details')->where('order_id',$request->order_id)->count();

                // order payment status check
                // if ($payment_status == $order_payment_status) {
                //     $order->payment_status = $request->status;
                //     $order->save();
                // } elseif ($payment_status < 1) {
                //     $order->payment_status = $request->status;
                //     $order->save();
                // }

                // update least delivery status of order details
                $pay_status = get_least_status($order, 'payment');
                $order->payment_status = $pay_status;
                $order->save();
            } elseif ($request->has('delivery_status')){
                $order_detail->delivery_status = $request->delivery_status;
                $order_detail->save();

                $delivery_status = \DB::table('order_details')->where('order_id',$request->order_id)->where('delivery_status',$request->delivery_status)->count();
                $order_delivery_status = \DB::table('order_details')->where('order_id',$request->order_id)->count();
                $cancel_delivery_status = \DB::table('order_details')->where('order_id',$request->order_id)->where('delivery_status','cancelled')->count();
                $delivery_status = $delivery_status + $cancel_delivery_status;

                
                // order delivery status check
                // if ($delivery_status  == $order_delivery_status) {
                //     $order->delivery_status = $request->delivery_status;
                //     $order->save();
                // } elseif($delivery_status < 1){
                    //     $order->delivery_status = $request->delivery_status;
                    //     $order->save();
                    // }
                
                // update least delivery status of order details
                $del_status = get_least_status($order, 'delivery');
                $order->delivery_status = $del_status;
                $order->save();
            }
        
        $payment_status = \DB::table('order_details')->where('order_id',$request->order_id)->where('payment_status',$request->status)->count();

        if ($payment_status < 1) {
            if ($order->payment_status == 'paid' && $order->commission_calculated == 0) {
                commission_calculation($order);

                $order->commission_calculated = 1;
                $order->save();
            }
            //sends Notifications to user
            $req_status = $request->has('delivery_status') ? $request->delivery_status : $request->status;
            send_notification($order, $req_status);

            if (get_setting('google_firebase') == 1 && $order->user->device_token != null) {
                $request->device_token = $order->user->device_token;
                $request->title = "Order updated !";
                $status = str_replace("_", "", $order->payment_status);
                $request->text = " Your order {$order->code} has been {$status}";

                $request->type = "order";
                $request->id = $order->id;
                $request->user_id = $order->user->id;

                send_firebase_notification($request);
            }


            if (\App\Addon::where('unique_identifier', 'otp_system')->first() != null &&
                \App\Addon::where('unique_identifier', 'otp_system')->first()->activated &&
                SmsTemplate::where('identifier', 'payment_status_change')->first()->status == 1) {
                try {
                    SmsUtility::payment_status_change($order->user->phone, $order);
                } catch (\Exception $e) {

                }
            }
        }    
        return 1;
    }

    public function assign_delivery_boy(Request $request)
    {
        if (\App\Addon::where('unique_identifier', 'delivery_boy')->first() != null && \App\Addon::where('unique_identifier', 'delivery_boy')->first()->activated) {

            $order = Order::findOrFail($request->order_id);
            $order->assign_delivery_boy = $request->delivery_boy;
            $order->delivery_history_date = date("Y-m-d H:i:s");
            $order->save();

            $delivery_history = \App\DeliveryHistory::where('order_id', $order->id)
                ->where('delivery_status', $order->delivery_status)
                ->first();

            if (empty($delivery_history)) {
                $delivery_history = new \App\DeliveryHistory;

                $delivery_history->order_id = $order->id;
                $delivery_history->delivery_status = $order->delivery_status;
                $delivery_history->payment_type = $order->payment_type;
            }
            $delivery_history->delivery_boy_id = $request->delivery_boy;

            $delivery_history->save();

            if (get_setting('mail_username') != null && get_setting('delivery_boy_mail_notification') == '1') {
                $array['view'] = 'emails.invoice';
                $array['subject'] = translate('You are assigned to delivery an order. Order code') . ' - ' . $order->code;
                $array['from'] = get_setting('mail_from_address');
                $array['order'] = $order;

                try {
                    Mail::to($order->delivery_boy->email)->queue(new InvoiceEmailManager($array));
                } catch (\Exception $e) {

                }
            }

            if (\App\Addon::where('unique_identifier', 'otp_system')->first() != null &&
                \App\Addon::where('unique_identifier', 'otp_system')->first()->activated &&
                SmsTemplate::where('identifier', 'assign_delivery_boy')->first()->status == 1) {
                try {
                    SmsUtility::assign_delivery_boy($order->delivery_boy->phone, $order->code);
                } catch (\Exception $e) {

                }
            }
        }

        return 1;
    }
    public function owe_amount_payment(Request $request)
    {
        $user = Auth::user();
        $order_id = json_decode($request->id);

        if(!$_POST){
            if(Session::get('payment_intent_client_secret')){
                $data['payment_intent_client_secret']=Session::get('payment_intent_client_secret');
                Session::forget('payment_intent_client_secret');
            }
            else{
                $data['payment_intent_client_secret']='';
            }

            $data['merchant_amount'] = OweAmount::whereIn('order_id',$order_id)->get()->sum('convert_remain_amount');

            return view('frontend.owe_amount_payment', $data);
        } else {
            $amount = $request->merchant_owe_amount;
            if($request->payment_option=='stripe'){
            if(!$request->payment_intent_id){

                $rules = [
                'card_no'        => 'required|numeric|digits_between:12,20|validateluhn',
                'exp_month'  => 'required|expires:exp_month,exp_year',
                'exp_year'   => 'required|expires:exp_month,exp_year',
                'cvv' => 'required|numeric|digits_between:0,4',
            ];

            $niceNames = [
                'card_no'        => 'Card number',
                'exp_month'  => 'Month',
                'exp_year'   => 'Year',
                'cvv' => 'Cvv',
            ];

            $messages = [
                'exp_month.expires'      => 'Card month has expired',
                'exp_year.expires'      => 'Card year has expired',
                'validateluhn' => 'Card number is invalid'
            ];

            $validator = Validator::make($request->all(), $rules, $messages);
            $validator->setAttributeNames($niceNames);
                if ($validator->fails()) {
                    return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
                }
            }
            $payment_currency = currency_code();
            

            \Log::info('Payment Currency .'.$payment_currency);
            $plan_detail = [
                'email'           => Auth::user()->email,
                'number'          => $request->card_no, 
                'expiryMonth'     => $request->exp_month, 
                'expiryYear'      => $request->exp_year, 
                'cvv'             => $request->cvv,
                'amount'          => $amount,
                'currency_code'   => $payment_currency,
            ];
                if($amount >= 1) {

                    $stripe_card =  array(
                        "number" => $request->card_no,
                        "exp_month" => $request->exp_month,
                        "exp_year" => $request->exp_year,
                        "cvc" => $request->cvv,
                    );
                    $purchaseData   =   [
                        'amount'              => $amount*100,
                        'description'         => get_setting('site_name').' Owe Amount',
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
                        $payment['transaction_id'] = $stripe_response->transaction_id;
                        $payment['customer_id'] = $stripe_response->customer_id;

                     $this->update_owe_amount($order_id);
                     flash(translates('owe_amount_payment_completed'))->success();                
                     return redirect('orders');
                    }else if($stripe_response->status == 'requires_action') {
                        Session::put('payment_intent_client_secret',$stripe_response->payment_intent_client_secret);                   
                        return back()->withInput();
                    }
                    else
                    {
                     flash(translate($stripe_response->status_message))->error();
                     return back();
                    }
                } else {
                    flash(translate('please_try_again'))->success();
                    return back();
                }
            
            }else {       
               $clientId = get_setting('paypal_client_id');
               $clientSecret = get_setting('paypal_client_secret');

            if (get_setting('paypal_sandbox') == 1) {
                $environment = new SandboxEnvironment($clientId, $clientSecret);
            }
            else {
                $environment = new ProductionEnvironment($clientId, $clientSecret);
            }
            $client = new PayPalHttpClient($environment);

            $request = new OrdersCreateRequest();
            $request->prefer('return=representation');
            $request->body = [
                         "intent" => "CAPTURE",
                         "purchase_units" => [[
                             "reference_id" => rand(000000,999999),
                             "amount" => [
                                 "value" => number_format($amount, 2, '.', ''),
                                 "currency_code" => \App\Currency::findOrFail(get_setting('system_default_currency'))->code
                             ]
                         ]],
                         "application_context" => [
                              "cancel_url" => url('owe_amount_payment/cancel'),
                              "return_url" => url('owe_amount_payment/done?order_id='.json_encode($order_id))
                         ]
                     ];

                    $response = $client->execute($request);
                    return redirect()->to($response->result->links[1]->href);
                }
            } 
    }
    public function payment_update(Request $request) {
        if($request->payment_option=='stripe'){
            if(!$request->payment_intent_id){

                $rules = [
                'card_no'        => 'required|numeric|digits_between:12,20|validateluhn',
                'exp_month'  => 'required|expires:exp_month,exp_year',
                'exp_year'   => 'required|expires:exp_month,exp_year',
                'cvv' => 'required|numeric|digits_between:0,4',
            ];

            $niceNames = [
                'card_no'        => 'Card number',
                'exp_month'  => 'Month',
                'exp_year'   => 'Year',
                'cvv' => 'Cvv',
            ];

            $messages = [
                'exp_month.expires'      => 'Card month has expired',
                'exp_year.expires'      => 'Card year has expired',
                'validateluhn' => 'Card number is invalid'
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

    public function update_owe_amount($order) {
        $order_owe_amount = OweAmount::whereIn('order_id',$order)->get();
        foreach($order_owe_amount as $update_amount){
            $owe_amount = OweAmount::find($update_amount->id);            
            $owe_amount->remain_amount = 0;
            $owe_amount->paid_amount = $update_amount->total_amount;
            $owe_amount->status = 'Completed';
            $owe_amount->save();
        }

        return "success";
    }

    public function getDone(Request $request)
    {
        $this->update_owe_amount(json_decode($request->order_id));
        flash(translates('owe_amount_payment_completed'))->success();                
        return redirect('orders');
    }    
    public function getCancel(Request $request)
    {
        // Curse and humiliate the user for cancelling this most sacred payment (yours)
        flash(translate('payment_cancelled'))->success();
        return redirect()->route('seller.payment_update');
    }
}
