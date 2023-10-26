<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\SubCategory;
use App\SubSubCategory;
use App\Coupon;
use App\Product;
use Schema;


class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $coupons = Coupon::orderBy('id','desc')->get();
        return view('backend.marketing.coupons.index', compact('coupons'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.marketing.coupons.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $date_var                 = explode(" - ", $request->date_range);
         
        if (strtotime(date('d-m-Y')) > strtotime($date_var[0]) && strtotime(date('d-m-Y')) > strtotime($date_var[1])) 
        {
            flash(translate('coupon_is_expired'))->error();
            return back();
        }
        if(count(Coupon::where('code', $request->coupon_code)->get()) > 0){
            flash(translate('coupon_already_exists'))->error();
            return back();
        }
        if ($request->discount_type == "percent" && $request->discount >= 100) {
            flash(translate('coupon_max_percent'))->error();
            return back();   
        }
        
        if ($request->product_ids) {
            foreach($request->product_ids as $product_id) {
                $data['product_id'] = $product_id;
                $product = Product::where('id',$product_id)->first();
                if ($product->unit_price < $request->discount) {
                    flash(translate('coupon_amount_err_msg'))->error();
                    return back();
                }
            }   
        }

        $coupon = new Coupon;
          if ($request->coupon_type == "product_base") {
              $coupon->type = $request->coupon_type;
              $coupon->code = $request->coupon_code;
              $coupon->discount = $request->discount;
              $coupon->discount_type = $request->discount_type;
              $date_var                 = explode(" - ", $request->date_range);
              $coupon->start_date       = strtotime($date_var[0]);
              $coupon->end_date         = strtotime( $date_var[1]);
              $cupon_details = array();
              foreach($request->product_ids as $product_id) {
                  $data['product_id'] = $product_id;
                  array_push($cupon_details, $data);
              }
              $coupon->details = json_encode($cupon_details);
              if ($coupon->save()) {
                  flash(translate('coupon_has_been_saved_successfully'))->success();
                  return redirect()->route('coupon.index');
              }
              else{
                  flash(translate('something_went_wrong'))->danger();
                  return back();
              }
          }
          elseif ($request->coupon_type == "cart_base") {
                
                if ($request->max_discount > $request->discount && $request->discount_type == 'amount') {
                    flash(translate('maximum_discount_amount_err_msg'))->error();
                    return back();
                }

              $coupon->type             = $request->coupon_type;
              $coupon->code             = $request->coupon_code;
              $coupon->discount         = $request->discount;
              $coupon->discount_type    = $request->discount_type;
              $date_var                 = explode(" - ", $request->date_range);
              $coupon->start_date       = strtotime($date_var[0]);
              $coupon->end_date         = strtotime( $date_var[1]);
              $data                     = array();
              $data['min_buy']          = $request->min_buy;
              $data['max_discount']     = $request->max_discount;
              $coupon->details          = json_encode($data);
              if ($coupon->save()) {
                  flash(translate('coupon_has_been_saved_successfully'))->success();
                  return redirect()->route('coupon.index');
              }
              else{
                  flash(translate('something_went_wrong'))->danger();
                  return back();
              }
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
      $coupon = Coupon::findOrFail(decrypt($id));
      return view('backend.marketing.coupons.edit', compact('coupon'));
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
        $date_var                 = explode(" - ", $request->date_range);
         
        if (strtotime(date('d-m-Y')) > strtotime($date_var[0]) && strtotime(date('d-m-Y')) > strtotime($date_var[1])) 
        {
            flash(translate('coupon_is_expired'))->error();
            return back();
        }
        if(count(Coupon::where('id', '!=' , $id)->where('code', $request->coupon_code)->get()) > 0){
            flash(translate('coupon_already_exists'))->error();
            return back();
        }

        if ($request->discount_type == "percent" && $request->discount >= 100) {
            flash(translate('coupon_max_percent'))->error();
            return back();   
        }
        if ($request->product_ids) {
            foreach($request->product_ids as $product_id) {
                $data['product_id'] = $product_id;
                $product = Product::where('id',$product_id)->first();
                if ($product->unit_price < $request->discount) {
                    flash(translate('coupon_amount_err_msg'))->error();
                    return back();
                }
            }
        }

      $coupon = Coupon::findOrFail($id);
        if ($request->coupon_type == "product_base") {
            $coupon->type = $request->coupon_type;
            $coupon->code = $request->coupon_code;
            $coupon->discount = $request->discount;
            $coupon->discount_type  = $request->discount_type;
            $date_var                 = explode(" - ", $request->date_range);
            $coupon->start_date       = strtotime($date_var[0]);
            $coupon->end_date         = strtotime( $date_var[1]);
            $cupon_details = array();
            foreach($request->product_ids as $product_id) {
                $data['product_id'] = $product_id;
                array_push($cupon_details, $data);
            }
            $coupon->details = json_encode($cupon_details);
            if ($coupon->save()) {
                flash(translate('coupon_has_been_saved_successfully'))->success();
                return redirect()->route('coupon.index');
            }
            else{
                flash(translate('something_went_wrong'))->danger();
                return back();
            }
        }
        elseif ($request->coupon_type == "cart_base") {

            if ($request->max_discount > $request->discount && $request->discount_type == 'amount') {
                flash(translate('maximum_discount_amount_err_msg'))->error();
                return back();
            }

            $coupon->type           = $request->coupon_type;
            $coupon->code           = $request->coupon_code;
            $coupon->discount       = $request->discount;
            $coupon->discount_type  = $request->discount_type;
            $date_var               = explode(" - ", $request->date_range);
            $coupon->start_date     = strtotime($date_var[0]);
            $coupon->end_date       = strtotime( $date_var[1]);
            $data                   = array();
            $data['min_buy']        = $request->min_buy;
            $data['max_discount']   = $request->max_discount;
            $coupon->details        = json_encode($data);
            if ($coupon->save()) {
                flash(translate('coupon_has_been_saved_successfully'))->success();
                return redirect()->route('coupon.index');
            }
            else{
                flash(translate('something_went_wrong'))->danger();
                return back();
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $coupon = Coupon::findOrFail($id);
        if(Coupon::destroy($id)){
            flash(translate('coupon_has_been_deleted_successfully'))->success();
            return redirect()->route('coupon.index');
        }

        flash(translate('something_went_wrong'))->error();
        return back();
    }

    public function get_coupon_form(Request $request)
    {
        if($request->coupon_type == "product_base") {
            return view('backend.marketing.coupons.product_base_coupon');
        }
        elseif($request->coupon_type == "cart_base"){
            return view('backend.marketing.coupons.cart_base_coupon');
        }
    }

    public function get_coupon_form_edit(Request $request)
    {
        if($request->coupon_type == "product_base") {
            $coupon = Coupon::findOrFail($request->id);
            return view('backend.marketing.coupons.product_base_coupon_edit',compact('coupon'));
        }
        elseif($request->coupon_type == "cart_base"){
            $coupon = Coupon::findOrFail($request->id);
            return view('backend.marketing.coupons.cart_base_coupon_edit',compact('coupon'));
        }
    }

}
