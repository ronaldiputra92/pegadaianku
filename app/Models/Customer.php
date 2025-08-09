<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'id_number',
        'id_type',
        'date_of_birth',
        'place_of_birth',
        'gender',
        'occupation',
        'monthly_income',
        'emergency_contact_name',
        'emergency_contact_phone',
        'notes',
        'status'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'monthly_income' => 'decimal:2'
    ];

    // Relationships
    public function pawnTransactions()
    {
        return $this->hasMany(PawnTransaction::class);
    }

    public function payments()
    {
        return $this->hasManyThrough(Payment::class, PawnTransaction::class);
    }

    public function documents()
    {
        return $this->hasMany(CustomerDocument::class);
    }

    // Accessors
    public function getFullNameAttribute()
    {
        return $this->name;
    }

    public function getIdTypeNameAttribute()
    {
        $types = [
            'ktp' => 'KTP',
            'sim' => 'SIM',
            'passport' => 'Passport'
        ];

        return $types[$this->id_type] ?? $this->id_type;
    }

    public function getGenderNameAttribute()
    {
        return $this->gender === 'male' ? 'Laki-laki' : 'Perempuan';
    }

    public function getStatusNameAttribute()
    {
        $statuses = [
            'active' => 'Aktif',
            'inactive' => 'Tidak Aktif',
            'blocked' => 'Diblokir'
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    public function getAgeAttribute()
    {
        return $this->date_of_birth ? $this->date_of_birth->age : null;
    }

    public function getMonthlyIncomeFormattedAttribute()
    {
        return 'Rp ' . number_format($this->monthly_income, 0, ',', '.');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    public function scopeBlocked($query)
    {
        return $query->where('status', 'blocked');
    }

    public function scopeByGender($query, $gender)
    {
        return $query->where('gender', $gender);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('phone', 'like', "%{$search}%")
              ->orWhere('id_number', 'like', "%{$search}%");
        });
    }

    // Methods
    public function getTotalLoanAmount()
    {
        return $this->pawnTransactions()->sum('loan_amount');
    }

    public function getActiveLoanAmount()
    {
        return $this->pawnTransactions()->where('status', 'active')->sum('loan_amount');
    }

    public function getTotalPayments()
    {
        return $this->payments()->sum('amount');
    }

    public function getActiveTransactionsCount()
    {
        return $this->pawnTransactions()->where('status', 'active')->count();
    }

    public function getOverdueTransactionsCount()
    {
        return $this->pawnTransactions()->where('status', 'overdue')->count();
    }

    public function hasActiveTransactions()
    {
        return $this->getActiveTransactionsCount() > 0;
    }

    public function hasOverdueTransactions()
    {
        return $this->getOverdueTransactionsCount() > 0;
    }

    public function getLastTransactionDate()
    {
        $lastTransaction = $this->pawnTransactions()->latest()->first();
        return $lastTransaction ? $lastTransaction->created_at : null;
    }

    public function getLastPaymentDate()
    {
        $lastPayment = $this->payments()->latest('payment_date')->first();
        return $lastPayment ? $lastPayment->payment_date : null;
    }

    public function activate()
    {
        $this->update(['status' => 'active']);
    }

    public function deactivate()
    {
        $this->update(['status' => 'inactive']);
    }

    public function block()
    {
        $this->update(['status' => 'blocked']);
    }

    public function getVerifiedDocumentsCount()
    {
        return $this->documents()->verified()->count();
    }

    public function getUnverifiedDocumentsCount()
    {
        return $this->documents()->unverified()->count();
    }

    public function hasDocument($type)
    {
        return $this->documents()->where('document_type', $type)->exists();
    }

    public function getDocument($type)
    {
        return $this->documents()->where('document_type', $type)->first();
    }
}