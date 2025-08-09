<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class CustomerDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'document_type',
        'document_number',
        'document_path',
        'original_filename',
        'file_size',
        'mime_type',
        'notes',
        'uploaded_by',
        'verified_at',
        'verified_by'
    ];

    protected $casts = [
        'verified_at' => 'datetime',
        'file_size' => 'integer'
    ];

    // Relationships
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function uploadedBy()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // Accessors
    public function getDocumentTypeNameAttribute()
    {
        $types = [
            'ktp' => 'KTP',
            'sim' => 'SIM',
            'passport' => 'Passport',
            'kk' => 'Kartu Keluarga',
            'npwp' => 'NPWP'
        ];

        return $types[$this->document_type] ?? $this->document_type;
    }

    public function getFileSizeFormattedAttribute()
    {
        $bytes = $this->file_size;
        
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            return $bytes . ' bytes';
        } elseif ($bytes == 1) {
            return $bytes . ' byte';
        } else {
            return '0 bytes';
        }
    }

    public function getDocumentUrlAttribute()
    {
        if ($this->document_path) {
            // Try storage URL first
            $storageUrl = Storage::disk('public')->url($this->document_path);
            
            // If storage link doesn't work, use fallback route
            if (!$this->isStorageLinkWorking()) {
                return route('customer-documents.file', $this->id);
            }
            
            return $storageUrl;
        }
        return null;
    }

    /**
     * Check if storage link is working
     */
    private function isStorageLinkWorking()
    {
        $publicStoragePath = public_path('storage');
        return is_dir($publicStoragePath) && (is_link($publicStoragePath) || is_dir($publicStoragePath . '/customer-documents'));
    }

    /**
     * Get fallback URL for direct file serving
     */
    public function getDirectUrlAttribute()
    {
        return route('customer-documents.file', $this->id);
    }

    public function getIsImageAttribute()
    {
        return in_array($this->mime_type, [
            'image/jpeg',
            'image/jpg', 
            'image/png',
            'image/gif',
            'image/webp'
        ]);
    }

    public function getIsPdfAttribute()
    {
        return $this->mime_type === 'application/pdf';
    }

    public function getIsVerifiedAttribute()
    {
        return !is_null($this->verified_at);
    }

    // Scopes
    public function scopeVerified($query)
    {
        return $query->whereNotNull('verified_at');
    }

    public function scopeUnverified($query)
    {
        return $query->whereNull('verified_at');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('document_type', $type);
    }

    public function scopeByCustomer($query, $customerId)
    {
        return $query->where('customer_id', $customerId);
    }

    // Methods
    public function verify($userId = null)
    {
        $this->update([
            'verified_at' => now(),
            'verified_by' => $userId ?? auth()->id()
        ]);
    }

    public function unverify()
    {
        $this->update([
            'verified_at' => null,
            'verified_by' => null
        ]);
    }

    public function fileExists()
    {
        return $this->document_path && Storage::disk('public')->exists($this->document_path);
    }

    public function deleteFile()
    {
        if ($this->fileExists()) {
            Storage::disk('public')->delete($this->document_path);
        }
    }
}