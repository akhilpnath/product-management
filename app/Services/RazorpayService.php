<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Razorpay\Api\Api;

class RazorpayService
{
    protected $api;

    public function __construct()
    {
        $this->api = new Api(
            config('services.razorpay.key_id'),
            config('services.razorpay.key_secret')
        );
    }

    public function createOrder(Product $product)
    {
        $order = $this->api->order->create([
            'amount' => $product->price * 100,
            'currency' => 'INR',
            'notes' => [
                'product_id' => $product->id,
                'user_id' => Auth::id(),
            ],
        ]);

        Payment::create([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
            'razorpay_order_id' => $order->id,
            'amount' => $product->price,
            'status' => 'created',
        ]);

        return $order;
    }

    public function verifySignature($paymentId, $orderId, $signature)
    {
        try {
            $attributes = [
                'razorpay_payment_id' => $paymentId,
                'razorpay_order_id' => $orderId,
                'razorpay_signature' => $signature,
            ];

            $this->api->utility->verifyPaymentSignature($attributes);
            
            $payment = Payment::where('razorpay_order_id', $orderId)->first();
            if ($payment) {
                $payment->update([
                    'razorpay_payment_id' => $paymentId,
                    'razorpay_signature' => $signature,
                    'status' => 'completed',
                ]);
            }
            
            return true;
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Razorpay verification failed: ' . $e->getMessage());
            
            $payment = Payment::where('razorpay_order_id', $orderId)->first();
            if ($payment) {
                $payment->update(['status' => 'failed']);
            }
            
            return false;
        }
    }
}