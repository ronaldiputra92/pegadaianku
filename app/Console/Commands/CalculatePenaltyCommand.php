<?php

namespace App\Console\Commands;

use App\Models\PawnTransaction;
use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Console\Command;
use Carbon\Carbon;

class CalculatePenaltyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'penalty:calculate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate and apply penalty for overdue transactions';

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
        $this->info('Starting penalty calculation process...');

        // Ambil transaksi yang sudah jatuh tempo
        $overdueTransactions = PawnTransaction::where('status', 'overdue')
            ->where('due_date', '<', Carbon::now())
            ->with(['customer'])
            ->get();

        $totalProcessed = 0;

        foreach ($overdueTransactions as $transaction) {
            $daysOverdue = Carbon::now()->diffInDays($transaction->due_date);
            
            // Hitung denda (misalnya 1% per hari dari sisa tagihan, maksimal 30 hari)
            $penaltyRate = 0.01; // 1% per hari
            $maxPenaltyDays = 30; // Maksimal 30 hari denda
            
            $applicableDays = min($daysOverdue, $maxPenaltyDays);
            $dailyPenalty = $transaction->remaining_balance * $penaltyRate;
            $totalPenalty = $dailyPenalty * $applicableDays;
            
            // Update penalty amount jika belum ada atau berbeda
            $currentPenalty = $transaction->penalty_amount ?? 0;
            
            if ($currentPenalty != $totalPenalty) {
                $transaction->update([
                    'penalty_amount' => $totalPenalty,
                    'penalty_days' => $applicableDays,
                ]);

                // Buat notifikasi tentang denda
                $notification = Notification::create([
                    'user_id' => $transaction->customer_id,
                    'pawn_transaction_id' => $transaction->id,
                    'title' => 'Denda Keterlambatan Diterapkan',
                    'message' => "Denda keterlambatan sebesar Rp " . number_format($totalPenalty, 0, ',', '.') . " telah diterapkan pada transaksi {$transaction->transaction_code} karena terlambat {$applicableDays} hari. Total tagihan sekarang: Rp " . number_format($transaction->remaining_balance + $totalPenalty, 0, ',', '.'),
                    'type' => 'penalty',
                    'is_read' => false,
                    'scheduled_at' => now(),
                ]);

                // Kirim notifikasi via email/SMS/WhatsApp
                $this->notificationService->sendPenaltyNotification($transaction, $totalPenalty, $applicableDays);
                
                $totalProcessed++;
                $this->info("Penalty calculated for {$transaction->customer->name} - Transaction {$transaction->transaction_code}: Rp " . number_format($totalPenalty, 0, ',', '.'));
            }

            // Jika sudah lebih dari 120 hari, ubah status menjadi auction
            if ($daysOverdue > 120) {
                $transaction->update(['status' => 'auction']);
                
                $notification = Notification::create([
                    'user_id' => $transaction->customer_id,
                    'pawn_transaction_id' => $transaction->id,
                    'title' => 'Barang Akan Dilelang',
                    'message' => "Transaksi {$transaction->transaction_code} telah melewati batas waktu maksimal (120 hari). Barang gadai akan diproses untuk lelang.",
                    'type' => 'auction',
                    'is_read' => false,
                    'scheduled_at' => now(),
                ]);

                $this->notificationService->sendAuctionNotification($transaction);
                $this->warn("Transaction {$transaction->transaction_code} moved to auction status");
            }
        }

        $this->info("Penalty calculation process completed. Total transactions processed: {$totalProcessed}");
        return 0;
    }
}