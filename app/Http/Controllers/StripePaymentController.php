<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Session;

class StripePaymentController extends Controller
{
    public function stripe()
    {
        return view('stripe.payment');
    }

    public function create_checkout_session(Request $request) {
        $amount = 0;
        $amount = round(10 * 100);
        
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'price_data' => [
                    'currency' => 'INR',
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

        return response()->json(['id' => $session->id, 'status' => 200]);
    }

    public function success() {
        try{
            $payment = ["status" => "Success"];

            $payment_type = Session::get('payment_type');

            if ($payment_type == 'cart_payment') {
                $checkoutController = new CheckoutController;
                return $checkoutController->checkout_done(session()->get('combined_order_id'), json_encode($payment));
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
            flash(translate('Payment failed'))->error();
            return redirect()->route('home');
        }
    }

    public function cancel(Request $request){
        flash(translate('Payment is cancelled'))->error();
        return redirect()->route('home');
    }
}
