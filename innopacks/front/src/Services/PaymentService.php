<?php

namespace InnoShop\Front\Services;

use Exception;
use Illuminate\Support\Str;
use InnoShop\Common\Models\Order;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class PaymentService
{
    protected ?Order $order;
    protected string $billingMethodCode;

    public function __construct(Order $order)
    {
        $this->order = $order;
        $this->billingMethodCode = $order->billing_method_code;
        $this->initStripe();
    }

    public static function getInstance(Order $order): static
    {
        return new static($order);
    }

    protected function initStripe()
    {
        Stripe::setApiKey(env('STRIPE_KEY'));
    }

    public function pay(): mixed
    {
        try {
            if ($this->order->status != 'unpaid') {
                throw new Exception("Order status must be unpaid, now is {$this->order->status}!");
            }

            $originCode = $this->billingMethodCode;
            $paymentCode = Str::studly($originCode);
            $viewPath = fire_hook_filter("service.payment.pay.$originCode.view", "$paymentCode::payment");

            if (!view()->exists($viewPath)) {
                throw new Exception("Cannot find {$paymentCode} view {$viewPath}");
            }

            $paymentData = [
                'order' => $this->order,
                'payment_setting' => plugin_setting($paymentCode),
                'stripe_public_key' => env('STRIPE_PUBLIC_KEY'),
            ];

            $paymentData = fire_hook_filter("service.payment.pay.$originCode.data", $paymentData);
            $viewContent = view($viewPath, $paymentData)->render();

            return view('orders.pay', ['order' => $this->order, 'payment_view' => $viewContent]);
        } catch (Exception $e) {
            return view('orders.pay', ['order' => $this->order, 'error' => $e->getMessage()]);
        }
    }

    public function apiPay(): array
    {
        $order = $this->order;
        $orderPaymentCode = $this->billingMethodCode;

        $paymentData = [
            'order' => $order,
            'payment_setting' => plugin_setting($orderPaymentCode),
            'params' => null,
        ];

        $hookName = "service.payment.api.$orderPaymentCode.data";
        $paymentData = fire_hook_filter($hookName, $paymentData);

        $paramError = $paymentData['error'] ?? '';
        if ($paramError) {
            throw new Exception($paramError);
        }

        $params = $paymentData['params'] ?? [];
        if (empty($params)) {
            throw new Exception("Empty payment params for {$orderPaymentCode}, please add filter hook: $hookName");
        }

        // Create a PaymentIntent for Stripe
        $paymentIntent = PaymentIntent::create([
            'amount' => $order->total * 100, // amount in cents
            'currency' => 'usd',
            'payment_method_types' => ['card'],
            'description' => "Payment for order {$order->number}",
            'metadata' => [
                'order_id' => $order->id,
            ],
        ]);

        return [
            'order_id' => $order->id,
            'order_number' => $order->number,
            'billing_method_code' => $order->billing_method_code,
            'billing_method_name' => $order->billing_method_name,
            'billing_params' => $params,
            'client_secret' => $paymentIntent->client_secret,
        ];
    }
}
