<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TalentBooking;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    /**
     * Upload payment proof for an order
     */
    public function uploadProof(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // Max 5MB
            'order_id' => 'required|integer',
            'order_type' => 'required|in:talent,ticket',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Upload file
            $file = $request->file('payment_proof');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('payment-proofs', $filename, 'public');

            // Update order based on type
            if ($request->order_type === 'talent') {
                $order = TalentBooking::where('id', $request->order_id)
                    ->where('user_id', $request->user()->id)
                    ->first();

                if (!$order) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Booking not found or unauthorized'
                    ], 404);
                }

                $order->update([
                    'payment_proof' => $path,
                    'status' => 'pending_verification'
                ]);
            } else {
                $order = \App\Models\Booking::where('id', $request->order_id)
                    ->where('user_id', $request->user()->id)
                    ->first();

                if (!$order) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Order not found or unauthorized'
                    ], 404);
                }

                $order->update([
                    'payment_proof' => $path,
                    // keep status as pending so admin can review, or change to custom status if needed
                    // User requirements say "status pending", so we might not change 'status' column, just 'payment_status'
                    'payment_status' => 'paid', // Mark payment as paid (meaning uploading proof), waiting for admin confirmation
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Payment proof uploaded successfully. Your order is now pending verification.',
                'data' => [
                    'order_id' => $order->id,
                    'order_type' => $request->order_type,
                    'payment_proof' => $path,
                    'status' => 'pending_verification'
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload payment proof',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get payment proof for an order
     */
    public function getProof(Request $request, $orderType, $orderId)
    {
        try {
            if ($orderType === 'talent') {
                $order = TalentBooking::where('id', $orderId)
                    ->where('user_id', $request->user()->id)
                    ->first();
            } else {
                $order = EventTicketOrder::where('id', $orderId)
                    ->where('user_id', $request->user()->id)
                    ->first();
            }

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found'
                ], 404);
            }

            if (!$order->payment_proof) {
                return response()->json([
                    'success' => false,
                    'message' => 'No payment proof uploaded'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'payment_proof' => Storage::url($order->payment_proof),
                    'status' => $order->status
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get payment proof',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
