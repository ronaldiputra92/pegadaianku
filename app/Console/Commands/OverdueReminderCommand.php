<?php

namespace App\Console\Commands;

use App\Models\PawnTransaction;
use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Console\Command;
use Carbon\Carbon;

class OverdueReminderCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminder:overdue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send overdue reminders to customers';

    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        parent::__construct();
        $this->notificationService = $notificationService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting overdue reminder process...');

        // Ambil transaksi yang sudah jatuh tempo
        $overdueTransactions = PawnTransaction::where('status', 'active')
            ->where('due_date', '<', Carbon::now())
            ->with(['customer'])
            ->get();

        $totalSent = 0;

        foreach ($overdueTransactions as $transaction) {
            $daysOverdue = Carbon::now()->diffInDays($transaction->due_date);
            
            // Update status transaksi menjadi overdue
            $transaction->update(['status' => 'overdue']);

            // Cek apakah reminder overdue untuk hari ini sudah dikirim
            $existingReminder = Notification::where('user_id', $transaction->customer_id)
                ->where('pawn_transaction_id', $transaction->id)
                ->where('type', 'overdue')
                ->whereDate('created_at', Carbon::now())
                ->exists();

            if (!$existingReminder) {
                // Buat notifikasi dalam sistem
                $notification = Notification::create([
                    'user_id' => $transaction->customer_id,
                    'pawn_transaction_id' => $transaction->id,
                    'title' => 'Transaksi Jatuh Tempo',
                    'message' => "Transaksi {$transaction->transaction_code} telah jatuh tempo {$daysOverdue} hari yang lalu. Segera lakukan pembayaran untuk menghindari denda tambahan. Sisa tagihan: Rp " . number_format($transaction->remaining_balance, 0, ',', '.'),
                    'type' => 'overdue',
                    'is_read' => false,
                    'scheduled_at' => now(),
                ]);

                // Kirim notifikasi via email/SMS/WhatsApp
                $this->notificationService->sendOverdueReminder($transaction, $daysOverdue);
                
                $totalSent++;
                $this->info("Overdue reminder sent to {$transaction->customer->name} for transaction {$transaction->transaction_code} ({$daysOverdue} days overdue)");
            }
        }

        $this->info("Overdue reminder process completed. Total reminders sent: {$totalSent}");
        return 0;
    }
}