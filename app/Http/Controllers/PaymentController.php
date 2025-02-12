<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class PaymentController extends Controller
{
    public function createPaymentIntent(Request $request)
    {
        try {
            Stripe::setApiKey(config('services.stripe.secret'));

            $paymentIntent = PaymentIntent::create([
                'amount' => (int) ($request->amount * 100), // Convert to cents
                'currency' => 'usd', // Change according to your currency
                'metadata' => [
                    'order_number' => $request->order_number
                ],
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
            ]);

            return response()->json([
                'clientSecret' => $paymentIntent->client_secret
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function paymentCompleted(Request $request, $orderNumber)
{
    try {
        $order = Order::where('number', $orderNumber)->firstOrFail();

        // Update order payment status
        $order->update([
            'payment_status' => 'paid',
            'payment_intent_id' => $request->payment_intent_id,
            'payment_method_id' => $request->payment_method_id,
            'status' => 'processing' // or whatever status you use for paid orders
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Payment recorded successfully'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage()
        ], 500);
    }
}
}

