<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'name',
        'qris_image',
        'bank_name',
        'account_number',
        'account_holder',
        'instructions',
        'is_active',
        'display_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'display_order' => 'integer',
    ];

    /**
     * Scope for active payment methods
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('display_order');
    }

    /**
     * Scope for QRIS payment methods
     */
    public function scopeQris($query)
    {
        return $query->where('type', 'qris');
    }

    /**
     * Scope for bank account payment methods
     */
    public function scopeBankAccount($query)
    {
        return $query->where('type', 'bank_account');
    }

    /**
     * Get formatted account number (masked)
     */
    public function getMaskedAccountNumberAttribute()
    {
        if (!$this->account_number) {
            return null;
        }
        
        $length = strlen($this->account_number);
        if ($length <= 4) {
            return $this->account_number;
        }
        
        return str_repeat('*', $length - 4) . substr($this->account_number, -4);
    }
}
