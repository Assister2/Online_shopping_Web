<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OweAmount;

class OweAmountController extends Controller
{
    public function index(Request $request)
    {

        $owe_amount = OweAmount::with('users')->groupBy('seller_id')->selectRaw('*, sum(total_amount) as total_amount,sum(remain_amount) as remain_amount,sum(paid_amount) as paid_amount')->get();
        $owe_amount_paginate = OweAmount::with('users')->groupBy('seller_id')->paginate(15);
        return view('backend.sellers.manage_owe_amount.owe_amount', compact('owe_amount','owe_amount_paginate'));    
    }

    public function show($id)
    {
        $merchant_data = OweAmount::with('orders')->where('seller_id',$id)->get();

        return view('backend.sellers.manage_owe_amount.owe_amount_view', compact('merchant_data'));
    }    
}
