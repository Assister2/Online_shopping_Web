<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;
use App\Shop;
use App\User;
use Session;
use Auth;
use Config;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class InvoiceController extends Controller
{
    //download invoice
    public function invoice_download($id)
    {
        if(Session::has('currency_code')){
            $currency_code = Session::get('currency_code');
        }
        else{
            $currency_code = \App\Currency::findOrFail(get_setting('system_default_currency'))->code;
        }
        $language_code = Session::get('locale', Config::get('app.locale'));

        if(\App\Language::where('code', $language_code)->first()->rtl == 1){
            $direction = 'rtl';
            $text_align = 'right';
            $not_text_align = 'left';
        }else{
            $direction = 'ltr';
            $text_align = 'left';
            $not_text_align = 'right';            
        }

        if($currency_code == 'BDT' || $language_code == 'bd'){
            // bengali font
            $font_family = "'Hind Siliguri','sans-serif'";
        }elseif($currency_code == 'KHR' || $language_code == 'kh'){
            // khmer font
            $font_family = "'Hanuman','sans-serif'";
        }elseif($currency_code == 'AMD'){
            // Armenia font
            $font_family = "'arnamu','sans-serif'";
        }elseif($currency_code == 'ILS'){
            // Israeli font
            $font_family = "'Varela Round','sans-serif'";
        }elseif($currency_code == 'AED' || $currency_code == 'EGP' || $language_code == 'sa' || $currency_code == 'IQD'){
            // middle east/arabic font
            $font_family = "'XBRiyazRegular','sans-serif'";
        }else{
            // general for all
            $font_family = "'Roboto','sans-serif'";
        }

        $order = Order::with('user')->findOrFail($id);
        $shop = Shop::where('user_id', $order->seller_id)->first();

        if(!$shop) {
            $user = User::where('user_type', 'admin')->withTrashed()->first();
            $shop = [
                'name' => get_setting('site_name'),
                'email' => get_setting('contact_email'),
                'phone' => get_setting('contact_phone'),
                'address' => get_setting('contact_address')
            ];
            $shop = (object) $shop;
        } else {
            $user = User::withTrashed()->find($order->seller_id);
        }

        if (Auth::user()->user_type == 'seller') {
            return PDF::loadView('backend.invoices.seller_invoice',[
                'order' => $order,
                'shop' => $shop,
                'user' => $user,
                'font_family' => $font_family,
                'direction' => $direction,
                'text_align' => $text_align,
                'not_text_align' => $not_text_align
            ])->download('order-'.$order->code.'.pdf');
        } else{
            return PDF::loadView('backend.invoices.invoice',[
                'order' => $order,
                'shop' => $shop,
                'user' => $user,
                'font_family' => $font_family,
                'direction' => $direction,
                'text_align' => $text_align,
                'not_text_align' => $not_text_align
            ])->download('order-'.$order->code.'.pdf');
        }
    }
}
