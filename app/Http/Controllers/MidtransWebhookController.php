<?php

namespace App\Http\Controllers;

use App\Models\PreOrder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MidtransWebhookController extends Controller
{
    public function handle(Request $request): JsonResponse
    {
        $payload = $request->all();

        if (! $this->hasValidSignature($payload)) {
            return response()->json([
                'message' => 'Invalid signature.',
            ], 403);
        }

        $preOrder = PreOrder::where('midtrans_order_id', $payload['order_id'] ?? null)->first();

        if (! $preOrder) {
            return response()->json([
                'message' => 'Pre-order not found.',
            ], 404);
        }

        $mappedStatus = $this->mapPaymentStatus(
            (string) ($payload['transaction_status'] ?? ''),
            (string) ($payload['fraud_status'] ?? '')
        );

        $updates = [
            'status' => $mappedStatus,
            'payment_method' => $payload['payment_type'] ?? $preOrder->payment_method,
            'midtrans_transaction_id' => $payload['transaction_id'] ?? $preOrder->midtrans_transaction_id,
        ];

        if ($mappedStatus === 'processing') {
            $updates['paid_at'] = $preOrder->paid_at ?? now();
        }

        $preOrder->update($updates);

        return response()->json([
            'message' => 'Notification processed.',
        ]);
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function hasValidSignature(array $payload): bool
    {
        $orderId = (string) ($payload['order_id'] ?? '');
        $statusCode = (string) ($payload['status_code'] ?? '');
        $grossAmount = (string) ($payload['gross_amount'] ?? '');
        $signatureKey = (string) ($payload['signature_key'] ?? '');

        if ($orderId === '' || $statusCode === '' || $grossAmount === '' || $signatureKey === '') {
            return false;
        }

        $expectedSignature = hash('sha512', $orderId.$statusCode.$grossAmount.config('midtrans.server_key'));

        return hash_equals($expectedSignature, $signatureKey);
    }

    private function mapPaymentStatus(string $transactionStatus, string $fraudStatus): string
    {
        return match ($transactionStatus) {
            'settlement' => 'processing',
            'capture' => $fraudStatus === 'accept' ? 'processing' : 'unpaid',
            'pending' => 'unpaid',
            'expire' => 'expired',
            'deny' => 'failed',
            'cancel' => 'cancelled',
            'refund', 'partial_refund', 'chargeback' => 'refunded',
            default => 'unpaid',
        };
    }
}
