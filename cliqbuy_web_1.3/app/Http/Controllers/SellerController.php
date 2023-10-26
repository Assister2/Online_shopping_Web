<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Seller;
use App\User;
use App\Shop;
use App\Country;
use App\State;
use App\City;
use App\Product;
use App\Order;
use App\OrderDetail;
use App\Currency;
use App\Language;
use App\Models\UserSubscriptionRenewal;
use App\Models\SubscriptionPlan;
use Illuminate\Support\Facades\Hash;
use App\Notifications\EmailVerificationNotification;

class SellerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort_search = null;
        $approved = null;
        $sellers = Seller::with(['user'=>function($query){
            $query->with(['user_subscription']);
        }])->whereIn('user_id', function ($query) {
            $query->select('id')
                ->from(with(new User)->getTable());
        })->orderBy('created_at', 'desc');

        if ($request->has('search')) {
            $sort_search = $request->search;
            $user_ids = User::where('user_type', 'seller')->where(function ($user) use ($sort_search) {
                $user->where('name', 'like', '%' . $sort_search . '%')->orWhere('email', 'like', '%' . $sort_search . '%');
            })->pluck('id')->toArray();
            $sellers = $sellers->where(function ($seller) use ($user_ids) {
                $seller->whereIn('user_id', $user_ids);
            });
        }
        if ($request->approved_status != null) {
            $approved = $request->approved_status;
            $sellers = $sellers->where('verification_status', $approved);
        }
        $sellers = $sellers->paginate(15);
         $currency = Currency::where('status','1')->pluck('code');
        $languages = Language::pluck('name','code');
        $subscription = SubscriptionPlan::where('status','Active')->where('custom_plan','!=','Yes')->get();
        return view('backend.sellers.index', compact('sellers', 'sort_search', 'approved','currency','languages','subscription'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.sellers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (User::where('email', $request->email)->first() != null) {
            flash(translate('email_already_exists'))->error();
            return back();
        }
        if (Shop::where('name', $request->shop_name)->first() != null) {
            flash(translate('shop_already_exists'))->error();
            return back();
        }
        if (Shop::where('phone', $request->phone)->first() != null) {
            flash(translate('phone_already_exists'))->error();
            return back();
        }
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->user_type = "seller";
        $user->password = Hash::make($request->password);

        if ($user->save()) {
            if (get_setting('email_verification') != 1) {
                $user->email_verified_at = date('Y-m-d H:m:s');
            } else {
                $user->notify(new EmailVerificationNotification());
            }
            $user->save();

            $seller = new Seller;
            $seller->user_id = $user->id;

            if ($seller->save()) {
                $shop = new Shop;
                $shop->user_id = $user->id;
                $shop->name = $request->shop_name;
                $shop->address = $request->address;
                $shop->country = Country::find($request->country)->name;
                $shop->city = City::find($request->city)->name;
                $shop->state = State::find($request->state_id)->name;
                $shop->postal_code = $request->postal_code;
                $shop->phone = $request->phone;
                $shop->slug = 'demo-shop-' . $user->id;
                $shop->save();

                flash(translate('merchant_inserted'))->success();
                return redirect()->route('sellers.index');
            }
        }
        flash(translate('something_went_wrong'))->error();
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $seller = Seller::findOrFail(decrypt($id));
        if ($seller) {
            $shop_detials = \DB::table('shops')->where('user_id',$seller->user_id)->first();
        }
        return view('backend.sellers.edit', compact('seller','shop_detials'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $seller = Seller::findOrFail($id);
        if (User::where('id','!=',$seller->user_id)->where('email', $request->email)->first() != null) {
            flash(translate('email_already_exists'))->error();
            return back();
        }
        if (Shop::where('user_id','!=',$seller->user_id)->where('name', $request->shop_name)->first() != null) {
            flash(translate('shop_already_exists'))->error();
            return back();
        }
        if (Shop::where('user_id','!=',$seller->user_id)->where('phone', $request->phone)->first() != null) {
            flash(translate('phone_already_exists'))->error();
            return back();
        }
        $user = $seller->user;
        $user->name = $request->name;
        $user->email = $request->email;
        if (strlen($request->password) > 0) {
            $user->password = Hash::make($request->password);
        }
        if ($seller) {
            $shop = Shop::where('user_id',$seller->user_id)->first();
            $shop->name = $request->shop_name;
            $shop->address = $request->address;
            $shop->country = Country::find($request->country)->name;
            $shop->city = City::find($request->city)->name;
            $shop->state = State::find($request->state_id)->name;
            $shop->postal_code = $request->postal_code;
            $shop->phone = $request->phone;
            $shop->save();
        }
        if ($user->save()) {
            if ($seller->save()) {
                flash(translate('merchant_updated'))->success();
                return redirect()->route('sellers.index');
            }
        }

        flash(translate('something_went_wrong'))->error();
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $seller = Seller::findOrFail($id);

        Shop::where('user_id', $seller->user_id)->delete();

        Product::where('user_id', $seller->user_id)->delete();

        $orders = Order::where('user_id', $seller->user_id)->get();
        Order::where('user_id', $seller->user_id)->delete();

        foreach ($orders as $key => $order) {
            OrderDetail::where('order_id', $order->id)->delete();
        }

        User::destroy($seller->user->id);

        if (Seller::destroy($id)) {
            flash(translate('merchant_deleted'))->success();
            return redirect()->route('sellers.index');
        } else {
            flash(translate('something_went_wrong'))->error();
            return back();
        }
    }

    public function bulk_seller_delete(Request $request)
    {
        if ($request->id) {
            foreach ($request->id as $seller_id) {
                $this->destroy($seller_id);
            }
        }

        return 1;
    }

    public function show_verification_request($id)
    {
        $seller = Seller::findOrFail($id);
        return view('backend.sellers.verification', compact('seller'));
    }

    public function approve_seller($id)
    {
        $seller = Seller::findOrFail($id);
        $seller->verification_status = 1;
        if ($seller->save()) {
            flash(translate('merchant_approved'))->success();
            return redirect()->route('sellers.index');
        }
        flash(translate('something_went_wrong'))->error();
        return back();
    }

    public function reject_seller($id)
    {
        $seller = Seller::findOrFail($id);
        $seller->verification_status = 0;
        $seller->verification_info = null;
        if ($seller->save()) {
            flash(translate('merchant_verification_request_has_been_rejected_successfully'))->success();
            return redirect()->route('sellers.index');
        }
        flash(translate('something_went_wrong'))->error();
        return back();
    }


    public function payment_modal(Request $request)
    {
        $seller = Seller::findOrFail($request->id);
        return view('backend.sellers.payment_modal', compact('seller'));
    }

    public function profile_modal(Request $request)
    {
        $seller = Seller::findOrFail($request->id);
        return view('backend.sellers.profile_modal', compact('seller'));
    }

    public function updateApproved(Request $request)
    {
        $seller = Seller::findOrFail($request->id);
        
        $seller->verification_status = $request->status;
        if ($seller->save()) {
            return 1;
        }
        return 0;
    }

    public function login($id)
    {
        $seller = Seller::findOrFail(decrypt($id));

        $user  = $seller->user;

        auth()->login($user, true);

        return redirect()->route('dashboard');
    }

    public function ban($id)
    {
        $seller = Seller::findOrFail($id);

        if ($seller->user->banned == 1) {
            $seller->user->banned = 0;
            flash(translate('merchant_unbanned'))->success();
        } else {
            $seller->user->banned = 1;
            flash(translate('merchant_banned'))->success();
        }

        $seller->user->save();
        return back();
    }
    public function subscription_history(Request $request)
    {   
        $user_subscription = UserSubscriptionRenewal::where('user_id',$request->user_id)->get();
        return json_encode($user_subscription);
    }
}
