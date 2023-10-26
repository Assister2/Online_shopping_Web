<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SellerWithdrawRequest;
use Auth;
use App\Seller;
use App\BusinessSetting;
use App\Currency;

class SellerWithdrawRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $seller_withdraw_requests = SellerWithdrawRequest::where('user_id', Auth::user()->seller->id)->orderBy('id', 'desc')->paginate(9);
        return view('frontend.user.seller.seller_withdraw_requests.index', compact('seller_withdraw_requests'));
    }

    public function request_index()
    {
        $seller_withdraw_requests = SellerWithdrawRequest::paginate(15);
        return view('backend.sellers.seller_withdraw_requests.index', compact('seller_withdraw_requests'));
    }

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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $seller_withdraw_request = new SellerWithdrawRequest;
        $seller_withdraw_request->user_id = Auth::user()->seller->id;
        $seller_withdraw_request->amount = convert_to_usd($request->amount);
        $seller_withdraw_request->message = $request->message;
        $seller_withdraw_request->status = '0';
        $seller_withdraw_request->viewed = '0';
        $seller_withdraw_request->currency_id = system_default_currency();
        if ($seller_withdraw_request->save()) {
            flash(translate('request_has_been_sent_successfully'))->success();
            return redirect()->route('withdraw_requests.index');
        }
        else{
            flash(translate('something_went_wrong'))->error();
            return back();
        }
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function payment_modal(Request $request)
    {
        $seller = Seller::findOrFail($request->id);
        $business_settings = BusinessSetting::where('type', 'system_default_currency')->first();
        $currency = Currency::find($business_settings->value);
        $seller_withdraw_request = SellerWithdrawRequest::where('id', $request->seller_withdraw_request_id)->first();
        $requested_amount = number_format(($currency->exchange_rate * $seller_withdraw_request->amount), 2, '.', '');
        return view('backend.sellers.seller_withdraw_requests.payment_modal', compact('seller', 'seller_withdraw_request', 'requested_amount'));
    }

    public function message_modal(Request $request)
    {
        $seller_withdraw_request = SellerWithdrawRequest::findOrFail($request->id);
        if (Auth::user()->user_type == 'seller') {
            return view('frontend.partials.withdraw_message_modal', compact('seller_withdraw_request'));
        }
        elseif (Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'staff') {
            return view('backend.sellers.seller_withdraw_requests.withdraw_message_modal', compact('seller_withdraw_request'));
        }
    }
}
