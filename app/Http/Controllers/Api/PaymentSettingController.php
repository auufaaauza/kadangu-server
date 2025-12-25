<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PaymentSetting;
use Illuminate\Http\Request;

class PaymentSettingController extends Controller
{
    /**
     * Get all active payment settings for frontend
     */
    public function index()
    {
        $paymentSettings = PaymentSetting::active()->get()->map(function ($setting) {
            return [
                'id' => $setting->id,
                'type' => $setting->type,
                'name' => $setting->name,
                'qris_image' => $setting->qris_image ? asset('storage/' . $setting->qris_image) : null,
                'bank_name' => $setting->bank_name,
                'account_number' => $setting->account_number,
                'account_holder' => $setting->account_holder,
                'instructions' => $setting->instructions,
                'display_order' => $setting->display_order,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $paymentSettings
        ]);
    }

    /**
     * Get QRIS payment methods only
     */
    public function qris()
    {
        $qrisSettings = PaymentSetting::active()->qris()->get();

        return response()->json([
            'success' => true,
            'data' => $qrisSettings
        ]);
    }

    /**
     * Get bank account payment methods only
     */
    public function bankAccounts()
    {
        $bankAccounts = PaymentSetting::active()->bankAccount()->get();

        return response()->json([
            'success' => true,
            'data' => $bankAccounts
        ]);
    }
}
