<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_code',
        'pawn_transaction_id',
        'officer_id',
        'payment_type',
        'payment_method',
        'bank_name',
        'reference_number',
        'amount',
        'interest_amount',
        'principal_amount',
        'remaining_balance',
        'is_final_payment',
        'payment_date',
        'notes',
        'receipt_printed',
        'receipt_printed_at',
        'receipt_number',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'receipt_printed_at' => 'datetime',
        'amount' => 'decimal:2',
        'interest_amount' => 'decimal:2',
        'principal_amount' => 'decimal:2',
        'remaining_balance' => 'decimal:2',
        'receipt_printed' => 'boolean',
        'is_final_payment' => 'boolean',
    ];

    /**
     * Generate unique payment code
     */
    public static function generatePaymentCode(): string
    {
        $prefix = 'PAY';
        $date = now()->format('Ymd');
        $lastPayment = self::whereDate('created_at', today())
            ->orderBy('id', 'desc')
            ->first();
        
        $sequence = $lastPayment ? 
            intval(substr($lastPayment->payment_code, -4)) + 1 : 1;
        
        return $prefix . $date . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Pawn transaction relationship
     */
    public function pawnTransaction()
    {
        return $this->belongsTo(PawnTransaction::class);
    }

    /**
     * Officer relationship
     */
    public function officer()
    {
        return $this->belongsTo(User::class, 'officer_id');
    }

    /**
     * Generate unique receipt number
     */
    public static function generateReceiptNumber(): string
    {
        $prefix = 'RCP';
        $date = now()->format('Ymd');
        $lastReceipt = self::whereNotNull('receipt_number')
            ->whereDate('created_at', today())
            ->orderBy('id', 'desc')
            ->first();
        
        $sequence = $lastReceipt ? 
            intval(substr($lastReceipt->receipt_number, -4)) + 1 : 1;
        
        return $prefix . $date . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Check if this is a full payment (pelunasan)
     */
    public function isFullPayment(): bool
    {
        return $this->payment_type === 'full' || $this->is_final_payment;
    }

    /**
     * Get payment method display name
     */
    public function getPaymentMethodDisplayAttribute(): string
    {
        $methods = [
            'cash' => 'Tunai',
            'transfer' => 'Transfer Bank',
            'debit' => 'Kartu Debit',
            'credit' => 'Kartu Kredit',
        ];

        return $methods[$this->payment_method] ?? ucfirst($this->payment_method);
    }

    /**
     * Get payment type display name
     */
    public function getPaymentTypeDisplayAttribute(): string
    {
        $types = [
            'interest' => 'Pembayaran Bunga',
            'partial' => 'Pembayaran Sebagian',
            'full' => 'Pelunasan Penuh',
        ];

        return $types[$this->payment_type] ?? ucfirst($this->payment_type);
    }

    /**
     * Boot method to auto-generate payment code
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($payment) {
            if (empty($payment->payment_code)) {
                $payment->payment_code = self::generatePaymentCode();
            }
        });
    }
}