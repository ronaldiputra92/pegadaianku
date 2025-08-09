<?php

namespace App\Console\Commands;

use App\Models\PawnTransaction;
use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Console\Command;
use Carbon\Carbon;

class DueDateReminderCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminder:due-date';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send due date reminders to customers';

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
        $this->info('Starting due date reminder process...');

        // Ambil transaksi yang akan jatuh tempo dalam 7 hari, 3 hari, dan 1 hari
        $reminderDays = [7, 3, 1];
        $totalSent = 0;

        foreach ($reminderDays as $days) {
            $targetDate = Carbon::now()->addDays($days)->format('Y-m-d');
            
            $transactions = PawnTransaction::where('status', 'active')
                ->whereDate('due_date', $targetDate)
                ->with(['customer'])
                ->get();

            foreach ($transactions as $transaction) {
                // Cek apakah reminder untuk hari ini sudah dikirim
                $existingReminder = Notification::where('user_id', $transaction->customer_id)
                    ->where('pawn_transaction_id', $transaction->id)
                    ->where('type', 'due_date_reminder')
                    ->whereDate('created_at', Carbon::now())
                    ->where('message', 'like', "%{$days} hari%")
                    ->exists();

                if (!$existingReminder) {
                    // Buat notifikasi dalam sistem
                    $notification = Notification::create([
                        'user_id' => $transaction->customer_id,
                        'pawn_transaction_id' => $transaction->id,
                        'title' => "Pengingat Jatuh Tempo - {$days} Hari Lagi",
                        'message' => "Transaksi {$transaction->transaction_code} akan jatuh tempo dalam {$days} hari pada tanggal " . $transaction->due_date->format('d/m/Y') . ". Sisa tagihan: Rp " . number_format($transaction->remaining_balance, 0, ',', '.'),
                        'type' => 'due_date_reminder',
                        'is_read' => false,
                        'scheduled_at' => now(),
                    ]);

                    // Kirim notifikasi via email/SMS/WhatsApp
                    $this->notificationService->sendDueDateReminder($transaction, $days);
                    
                    $totalSent++;
                    $this->info("Reminder sent to {$transaction->customer->name} for transaction {$transaction->transaction_code} ({$days} days)");
                }
            }
        }

        $this->info("Due date reminder process completed. Total reminders sent: {$totalSent}");
        return 0;
    }
}