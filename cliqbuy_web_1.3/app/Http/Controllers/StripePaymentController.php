<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;
use App\BusinessSetting;
use App\Seller;
use Session;
use App\CustomerPackage;
use App\SellerPackage;
use Stripe\Stripe;

class StripePaymentController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function stripe()
    {
        return view('frontend.payment.stripe');
    }

    public function create_checkout_session(Request $request) {
        $amount = 0;
        if($request->session()->has('payment_type')){
            if($request->session()->get('payment_type') == 'cart_payment'){
                // $order = Order::findOrFail(Session::get('order_id'));
                $order_grand_total = Session::get('amount');
                $amount = round(convert_price($order_grand_total) * 100);
            }
            elseif ($request->session()->get('payment_type') == 'wallet_payment') {
                $amount = round($request->session()->get('payment_data')['amount'] * 100);
            }
            elseif ($request->session()->get('payment_type') == 'customer_package_payment') {
                $customer_package = CustomerPackage::findOrFail(Session::get('payment_data')['customer_package_id']);
                $amount = round($customer_package->amount * 100);
            }
            elseif ($request->session()->get('payment_type') == 'seller_package_payment') {
                $seller_package = SellerPackage::findOrFail(Session::get('payment_data')['seller_package_id']);
                $amount = round($seller_package->amount * 100);
            }
        }

        \Stripe\Stripe::setApiKey(get_setting('stripe_secret'));

        try {
            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [
                    [
                        'price_data' => [
                        'currency' => \App\Currency::findOrFail(get_setting('system_default_currency'))->code,
                        'product_data' => [
                            'name' => "Payment"
                        ],
                        'unit_amount' => $amount,
                        ],
                        'quantity' => 1,
                        ]
                    ],
                'mode' => 'payment',
                'success_url' => route('stripe.success'),
                'cancel_url' => route('stripe.cancel'),
            ]);
            $error_message = '';
        } catch(\Stripe\Error\Card $e) {
            $error_message = $this->retrieve_stripe_error($e);
        } catch (\Stripe\Error\RateLimit $e) {
            // Too many requests made to the API too quickly
            $error_message = $this->retrieve_stripe_error($e);
        } catch (\Stripe\Error\InvalidRequest $e) {
            // Invalid parameters were supplied to Stripe's API
            $error_message = $this->retrieve_stripe_error($e);
        } catch (\Stripe\Error\Authentication $e) {
            // Authentication with Stripe's API failed
            // (maybe you changed API keys recently)
            $error_message = $this->retrieve_stripe_error($e);
        } catch (\Stripe\Error\ApiConnection $e) {
            // Network communication with Stripe failed
            $error_message = $this->retrieve_stripe_error($e);
        } catch (\Stripe\Error\Base $e) {
            // Display a very generic error to the user, and maybe send
            // yourself an email
            $error_message = $this->retrieve_stripe_error($e);
        } catch (Exception $e) {
            // Something else happened, completely unrelated to Stripe
            $error_message = $this->retrieve_stripe_error($e);
        }

        return response()->json([
            'id' => (isset($session)) ? $session->id : '',
            'status' => 200,
            'error_message' => (isset($session)) ? '' : $error_message,
        ]);
    }

    public function retrieve_stripe_error($e) {
        $body = $e->getJsonBody();
        return ($body) ? $body['error']['message'] : '';
    }

    public function success() {
        try{
            $payment = ["status" => "Success"];

            $payment_type = Session::get('payment_type');

            if ($payment_type == 'cart_payment') {
                $checkoutController = new CheckoutController;
                return $checkoutController->checkout_done(session()->get('order_id'), json_encode($payment));
            }

            if ($payment_type == 'wallet_payment') {
                $walletController = new WalletController;
                return $walletController->wallet_payment_done(session()->get('payment_data'), json_encode($payment));
            }

            if ($payment_type == 'customer_package_payment') {
                $customer_package_controller = new CustomerPackageController;
                return $customer_package_controller->purchase_payment_done(session()->get('payment_data'), json_encode($payment));
            }
            if($payment_type == 'seller_package_payment') {
                $seller_package_controller = new SellerPackageController;
                return $seller_package_controller->purchase_payment_done(session()->get('payment_data'), json_encode($payment));
            }
        }
        catch (\Exception $e) {
            logger('payment error-'.$e->getMessage());
            flash(translate('payment_failed'))->error();
    	    return redirect()->route('home');
        }
    }

    public function cancel(Request $request){
        flash(translate('payment_is_cancelled'))->error();
        return redirect()->route('home');
    }
}
