<?php

namespace App\Http\Controllers;

use App\Models\PawnTransaction;
use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReminderController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Display reminder dashboard
     */
    public function index()
    {
        // Transaksi yang akan jatuh tempo dalam 7 hari
        $dueSoonTransactions = PawnTransaction::where('status', 'active')
            ->whereBetween('due_date', [Carbon::now(), Carbon::now()->addDays(7)])
            ->with(['customer'])
            ->orderBy('due_date')
            ->get();

        // Transaksi yang sudah jatuh tempo
        $overdueTransactions = PawnTransaction::where('status', 'overdue')
            ->with(['customer'])
            ->orderBy('due_date')
            ->get();

        // Transaksi yang akan dilelang
        $auctionTransactions = PawnTransaction::where('status', 'auction')
            ->with(['customer'])
            ->orderBy('due_date')
            ->get();

        // Statistik reminder
        $stats = [
            'due_soon_count' => $dueSoonTransactions->count(),
            'overdue_count' => $overdueTransactions->count(),
            'auction_count' => $auctionTransactions->count(),
            'total_penalty' => $overdueTransactions->sum('penalty_amount'),
        ];

        return view('reminders.index', compact(
            'dueSoonTransactions',
            'overdueTransactions', 
            'auctionTransactions',
            'stats'
        ));
    }

    /**
     * Send manual reminder for specific transaction
     */
    public function sendManualReminder(Request $request, PawnTransaction $transaction)
    {
        $request->validate([
            'type' => 'required|in:due_date,overdue,penalty,auction'
        ]);

        $type = $request->type;
        $success = false;

        try {
            switch ($type) {
                case 'due_date':
                    $daysUntilDue = Carbon::now()->diffInDays($transaction->due_date, false);
                    if ($daysUntilDue > 0) {
                        $this->notificationService->sendDueDateReminder($transaction, $daysUntilDue);
                        $success = true;
                    }
                    break;

                case 'overdue':
                    $daysOverdue = Carbon::now()->diffInDays($transaction->due_date);
                    if ($daysOverdue > 0) {
                        $this->notificationService->sendOverdueReminder($transaction, $daysOverdue);
                        $success = true;
                    }
                    break;

                case 'penalty':
                    if ($transaction->penalty_amount > 0) {
                        $this->notificationService->sendPenaltyNotification(
                            $transaction, 
                            $transaction->penalty_amount, 
                            $transaction->penalty_days
                        );
                        $success = true;
                    }
                    break;

                case 'auction':
                    $this->notificationService->sendAuctionNotification($transaction);
                    $success = true;
                    break;
            }

            if ($success) {
                // Note: Customer notifications removed since customers are not users
                // Customer notifications should be handled via SMS/email through NotificationService

                return back()->with('success', 'Reminder berhasil dikirim ke ' . $transaction->customer->name);
            } else {
                return back()->with('error', 'Gagal mengirim reminder. Periksa kondisi transaksi.');
            }

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Send bulk reminders
     */
    public function sendBulkReminders(Request $request)
    {
        $request->validate([
            'transaction_ids' => 'required|array',
            'transaction_ids.*' => 'exists:pawn_transactions,id',
            'type' => 'required|in:due_date,overdue,penalty,auction'
        ]);

        $transactions = PawnTransaction::whereIn('id', $request->transaction_ids)
            ->with(['customer'])
            ->get();

        $successCount = 0;
        $errors = [];

        foreach ($transactions as $transaction) {
            try {
                $type = $request->type;
                $success = false;

                switch ($type) {
                    case 'due_date':
                        $daysUntilDue = Carbon::now()->diffInDays($transaction->due_date, false);
                        if ($daysUntilDue > 0) {
                            $this->notificationService->sendDueDateReminder($transaction, $daysUntilDue);
                            $success = true;
                        }
                        break;

                    case 'overdue':
                        $daysOverdue = Carbon::now()->diffInDays($transaction->due_date);
                        if ($daysOverdue > 0) {
                            $this->notificationService->sendOverdueReminder($transaction, $daysOverdue);
                            $success = true;
                        }
                        break;

                    case 'penalty':
                        if ($transaction->penalty_amount > 0) {
                            $this->notificationService->sendPenaltyNotification(
                                $transaction, 
                                $transaction->penalty_amount, 
                                $transaction->penalty_days
                            );
                            $success = true;
                        }
                        break;

                    case 'auction':
                        $this->notificationService->sendAuctionNotification($transaction);
                        $success = true;
                        break;
                }

                if ($success) {
                    // Note: Customer notifications removed since customers are not users
                    // Customer notifications should be handled via SMS/email through NotificationService
                    $successCount++;
                } else {
                    $errors[] = "Transaksi {$transaction->transaction_code} tidak memenuhi kondisi untuk reminder {$type}";
                }

            } catch (\Exception $e) {
                $errors[] = "Error pada transaksi {$transaction->transaction_code}: " . $e->getMessage();
            }
        }

        $message = "Berhasil mengirim {$successCount} reminder";
        if (!empty($errors)) {
            $message .= ". Errors: " . implode(', ', $errors);
        }

        return back()->with($successCount > 0 ? 'success' : 'error', $message);
    }

    /**
     * Get notification title based on type
     */
    private function getNotificationTitle(string $type): string
    {
        switch ($type) {
            case 'due_date':
                return 'Pengingat Jatuh Tempo';
            case 'overdue':
                return 'Transaksi Jatuh Tempo';
            case 'penalty':
                return 'Denda Keterlambatan';
            case 'auction':
                return 'Pemberitahuan Lelang';
            default:
                return 'Notifikasi';
        }
    }

    /**
     * Get notification message based on type
     */
    private function getNotificationMessage(PawnTransaction $transaction, string $type): string
    {
        switch ($type) {
            case 'due_date':
                $days = Carbon::now()->diffInDays($transaction->due_date, false);
                return "Transaksi {$transaction->transaction_code} akan jatuh tempo dalam {$days} hari pada " . $transaction->due_date->format('d/m/Y');
                
            case 'overdue':
                $days = Carbon::now()->diffInDays($transaction->due_date);
                return "Transaksi {$transaction->transaction_code} telah jatuh tempo {$days} hari yang lalu. Segera lakukan pembayaran.";
                
            case 'penalty':
                return "Denda keterlambatan sebesar Rp " . number_format($transaction->penalty_amount, 0, ',', '.') . " telah diterapkan pada transaksi {$transaction->transaction_code}";
                
            case 'auction':
                return "Transaksi {$transaction->transaction_code} telah melewati batas waktu maksimal. Barang gadai akan diproses untuk lelang.";
                
            default:
                return "Notifikasi untuk transaksi {$transaction->transaction_code}";
        }
    }
}