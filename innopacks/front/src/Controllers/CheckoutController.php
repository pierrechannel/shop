<?php

namespace InnoShop\Front\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use InnoShop\Common\Exceptions\Unauthorized;
use InnoShop\Common\Repositories\OrderRepo;
use InnoShop\Common\Services\CheckoutService;
use InnoShop\Common\Services\StateMachineService;
use InnoShop\Front\Requests\CheckoutConfirmRequest;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Throwable;

class CheckoutController extends Controller
{
    public function index(): mixed
    {
        try {
            $checkout = CheckoutService::getInstance();
            $result   = $checkout->getCheckoutResult();
            if (empty($result['cart_list'])) {
                return redirect(front_route('carts.index'))->withErrors(['error' => 'Empty Cart']);
            }

            return inno_view('checkout.index', $result);
        } catch (Unauthorized $e) {
            return redirect(front_route('login.index'))->withErrors(['error' => $e->getMessage()]);
        } catch (Exception $e) {
            return redirect(front_route('carts.index'))->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function update(Request $request): JsonResponse
    {
        $data     = $request->all();
        $checkout = CheckoutService::getInstance();
        $checkout->updateValues($data);
        $result = $checkout->getCheckoutResult();

        return json_success('更新成功', $result);
    }

    public function confirm(CheckoutConfirmRequest $request): JsonResponse
    {
        try {
            $checkout = CheckoutService::getInstance();
            $data     = $request->all();
            if ($data) {
                $checkout->updateValues($data);
            }

            $order = $checkout->confirm();
            StateMachineService::getInstance($order)->changeStatus(StateMachineService::UNPAID, '', true);

            // Initialize Stripe
            Stripe::setApiKey(env('STRIPE_KEY'));

            // Create a PaymentIntent
            $paymentIntent = PaymentIntent::create([
                'amount' => $order->total * 100, // amount in cents
                'currency' => 'usd',
                'payment_method_types' => ['card'],
                'description' => "Payment for order {$order->number}",
                'metadata' => [
                    'order_id' => $order->id,
                ],
            ]);

            // Confirm the PaymentIntent
            $paymentIntent->confirm();

            // Update order status to paid
            StateMachineService::getInstance($order)->changeStatus(StateMachineService::PAID, '', true);

            return json_success(front_trans('common.submitted_success'), $order);
        } catch (Exception $e) {
            return json_fail($e->getMessage());
        }
    }

    public function success(Request $request): mixed
    {
        $orderNumber = $request->get('order_number');
        $data        = [
            'order' => OrderRepo::getInstance()->builder(['number' => $orderNumber])->firstOrFail(),
        ];

        return inno_view('checkout.success', $data);
    }
}
