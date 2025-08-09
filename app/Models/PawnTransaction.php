<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PawnTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_code',
        'customer_id',
        'officer_id',
        'item_name',
        'item_description',
        'item_category',
        'item_weight',
        'item_condition',
        'item_photos',
        'estimated_value',
        'market_value',
        'appraisal_value',
        'appraisal_notes',
        'appraised_at',
        'appraiser_id',
        'loan_amount',
        'interest_rate',
        'loan_to_value_ratio',
        'admin_fee',
        'insurance_fee',
        'loan_period_months',
        'start_date',
        'due_date',
        'status',
        'total_interest',
        'total_amount',
        'notes',
        'receipt_printed',
        'receipt_printed_at',
        'receipt_number',
    ];

    protected $casts = [
        'start_date' => 'date',
        'due_date' => 'date',
        'appraised_at' => 'datetime',
        'receipt_printed_at' => 'datetime',
        'estimated_value' => 'decimal:2',
        'market_value' => 'decimal:2',
        'appraisal_value' => 'decimal:2',
        'loan_amount' => 'decimal:2',
        'interest_rate' => 'decimal:2',
        'loan_to_value_ratio' => 'decimal:2',
        'admin_fee' => 'decimal:2',
        'insurance_fee' => 'decimal:2',
        'total_interest' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'item_weight' => 'decimal:2',
        'loan_period_months' => 'integer',
        'item_photos' => 'array',
        'receipt_printed' => 'boolean',
    ];

    /**
     * Generate unique transaction code
     */
    public static function generateTransactionCode(): string
    {
        $prefix = 'PG';
        $date = now()->format('Ymd');
        $lastTransaction = self::whereDate('created_at', today())
            ->orderBy('id', 'desc')
            ->first();
        
        $sequence = $lastTransaction ? 
            intval(substr($lastTransaction->transaction_code, -4)) + 1 : 1;
        
        return $prefix . $date . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Calculate interest amount
     */
    public function calculateInterest(): float
    {
        // For new transactions, use loan_period_months
        if (!$this->exists) {
            $months = (int) $this->loan_period_months;
        } else {
            // For existing transactions, calculate based on time passed
            $monthsPassed = $this->start_date->diffInMonths(now());
            $months = $monthsPassed < 1 ? 1 : $monthsPassed;
        }
        
        return $this->loan_amount * ($this->interest_rate / 100) * $months;
    }

    /**
     * Calculate total amount (loan + interest)
     */
    public function calculateTotalAmount(): float
    {
        return $this->loan_amount + $this->calculateInterest();
    }

    /**
     * Check if transaction is overdue
     */
    public function isOverdue(): bool
    {
        return $this->due_date->isPast() && $this->status === 'active';
    }

    /**
     * Get days until due date
     */
    public function getDaysUntilDue(): int
    {
        return now()->diffInDays($this->due_date, false);
    }

    /**
     * Customer relationship
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    /**
     * Officer relationship
     */
    public function officer()
    {
        return $this->belongsTo(User::class, 'officer_id');
    }

    /**
     * Appraiser relationship
     */
    public function appraiser()
    {
        return $this->belongsTo(User::class, 'appraiser_id');
    }

    /**
     * Payments relationship
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Notifications relationship
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Extensions relationship
     */
    public function extensions()
    {
        return $this->hasMany(PawnExtension::class, 'transaction_id');
    }

    /**
     * Generate unique receipt number
     */
    public static function generateReceiptNumber(): string
    {
        $prefix = 'RC';
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
     * Calculate loan amount based on appraisal value and LTV ratio
     */
    public function calculateMaxLoanAmount(): float
    {
        if (!$this->appraisal_value) {
            return 0;
        }
        
        return $this->appraisal_value * ($this->loan_to_value_ratio / 100);
    }

    /**
     * Calculate total fees (admin + insurance)
     */
    public function calculateTotalFees(): float
    {
        return $this->admin_fee + $this->insurance_fee;
    }

    /**
     * Calculate net loan amount (loan amount - fees)
     */
    public function calculateNetLoanAmount(): float
    {
        return $this->loan_amount - $this->calculateTotalFees();
    }

    /**
     * Check if item has been appraised
     */
    public function isAppraised(): bool
    {
        return !is_null($this->appraised_at) && !is_null($this->appraisal_value);
    }

    
    /**
     * Get item photos URLs
     */
    public function getItemPhotosUrlsAttribute(): array
    {
        if (!$this->item_photos) {
            return [];
        }

        return array_map(function($photo) {
            // Cek beberapa lokasi penyimpanan foto
            $paths = [
                'images/transactions/' . $photo,
                'storage/transaction_photos/' . $photo,
                'photos/transaction_photos/' . $photo,
                'images/transaction_photos/' . $photo
            ];
            
            // Cek setiap path untuk menemukan file yang ada
            foreach ($paths as $path) {
                $fullPath = public_path($path);
                if (file_exists($fullPath)) {
                    return asset($path);
                }
            }
            
            // Jika tidak ditemukan di manapun, coba cek di storage/app/public
            $storagePath = storage_path('app/public/transaction_photos/' . $photo);
            if (file_exists($storagePath)) {
                return asset('storage/transaction_photos/' . $photo);
            }
            
            // Fallback: return path default meskipun file tidak ada
            // Ini akan membantu debugging dengan menampilkan path yang diharapkan
            return asset('images/transactions/' . $photo);
        }, $this->item_photos);
    }

    /**
     * Boot method to auto-generate transaction code
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transaction) {
            if (empty($transaction->transaction_code)) {
                $transaction->transaction_code = self::generateTransactionCode();
            }
            
            // Calculate due date
            if (empty($transaction->due_date)) {
                $transaction->due_date = Carbon::parse($transaction->start_date)
                    ->addMonths((int) $transaction->loan_period_months);
            }
            
            // Calculate total amounts
            $transaction->total_interest = $transaction->calculateInterest();
            $transaction->total_amount = $transaction->calculateTotalAmount();
        });
    }
}