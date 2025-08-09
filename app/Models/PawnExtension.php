<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PawnExtension extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'officer_id',
        'extension_code',
        'original_due_date',
        'new_due_date',
        'extension_months',
        'interest_amount',
        'penalty_amount',
        'admin_fee',
        'total_amount',
        'notes',
        'receipt_number',
        'receipt_printed',
        'receipt_printed_at',
    ];

    protected $casts = [
        'original_due_date' => 'date',
        'new_due_date' => 'date',
        'interest_amount' => 'decimal:2',
        'penalty_amount' => 'decimal:2',
        'admin_fee' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'receipt_printed' => 'boolean',
        'receipt_printed_at' => 'datetime',
    ];

    /**
     * Relationship with PawnTransaction
     */
    public function transaction()
    {
        return $this->belongsTo(PawnTransaction::class);
    }

    /**
     * Relationship with User (Officer)
     */
    public function officer()
    {
        return $this->belongsTo(User::class, 'officer_id');
    }

    /**
     * Generate unique extension code
     */
    public static function generateExtensionCode()
    {
        $date = Carbon::now()->format('Ymd');
        $lastExtension = self::whereDate('created_at', Carbon::today())
            ->orderBy('id', 'desc')
            ->first();

        $sequence = $lastExtension ? (int) substr($lastExtension->extension_code, -4) + 1 : 1;
        
        return 'EXT' . $date . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Generate unique receipt number
     */
    public static function generateReceiptNumber()
    {
        $date = Carbon::now()->format('Ymd');
        $lastExtension = self::whereDate('created_at', Carbon::today())
            ->whereNotNull('receipt_number')
            ->orderBy('id', 'desc')
            ->first();

        $sequence = $lastExtension ? (int) substr($lastExtension->receipt_number, -4) + 1 : 1;
        
        return 'RCP-EXT-' . $date . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Calculate extension fees
     */
    public static function calculateExtensionFees($transaction, $extensionMonths)
    {
        // Ensure extension months is integer
        $extensionMonths = (int) $extensionMonths;
        
        // Calculate interest for extension period
        $monthlyInterestRate = $transaction->interest_rate / 100;
        $interestAmount = $transaction->loan_amount * $monthlyInterestRate * $extensionMonths;

        // Calculate penalty if overdue
        $penaltyAmount = 0;
        if ($transaction->due_date < Carbon::now()) {
            $overdueDays = Carbon::now()->diffInDays($transaction->due_date);
            $dailyPenaltyRate = config('pawn.penalty_rate_per_day', 0.001); // 0.1% per day
            $penaltyAmount = $transaction->loan_amount * $dailyPenaltyRate * $overdueDays;
        }

        // Admin fee for extension (configurable)
        $adminFee = config('pawn.extension_admin_fee', 50000); // Default Rp 50,000

        $totalAmount = $interestAmount + $penaltyAmount + $adminFee;

        return [
            'interest_amount' => $interestAmount,
            'penalty_amount' => $penaltyAmount,
            'admin_fee' => $adminFee,
            'total_amount' => $totalAmount,
        ];
    }
}