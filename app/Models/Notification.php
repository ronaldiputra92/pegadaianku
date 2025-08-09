<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'pawn_transaction_id',
        'title',
        'message',
        'type',
        'is_read',
        'scheduled_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'scheduled_at' => 'datetime',
    ];

    /**
     * User relationship
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Pawn transaction relationship
     */
    public function pawnTransaction()
    {
        return $this->belongsTo(PawnTransaction::class);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead()
    {
        $this->update(['is_read' => true]);
    }

    /**
     * Scope for unread notifications
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope for due date notifications
     */
    public function scopeDueDate($query)
    {
        return $query->where('type', 'due_date');
    }

    /**
     * Scope for overdue notifications
     */
    public function scopeOverdue($query)
    {
        return $query->where('type', 'overdue');
    }
}