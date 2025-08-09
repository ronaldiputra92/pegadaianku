<?php

namespace Database\Seeders;

use App\Models\PawnTransaction;
use App\Models\User;
use App\Models\Payment;
use App\Models\Notification;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PawnTransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = User::where('role', 'nasabah')->get();
        $officer = User::where('role', 'petugas')->first();

        if ($customers->count() === 0 || !$officer) {
            return;
        }

        // Transaction 1 - Active
        $transaction1 = PawnTransaction::create([
            'customer_id' => $customers->first()->id,
            'officer_id' => $officer->id,
            'item_name' => 'Emas Kalung 24K',
            'item_description' => 'Kalung emas 24 karat dengan berat 10 gram',
            'item_category' => 'Perhiasan',
            'item_weight' => 10.00,
            'estimated_value' => 8000000,
            'loan_amount' => 6000000,
            'interest_rate' => 1.25,
            'loan_period_months' => 4,
            'start_date' => Carbon::now()->subDays(30),
            'status' => 'active',
            'notes' => 'Kondisi barang sangat baik',
        ]);

        // Transaction 2 - Extended
        $transaction2 = PawnTransaction::create([
            'customer_id' => $customers->skip(1)->first()->id,
            'officer_id' => $officer->id,
            'item_name' => 'Cincin Berlian',
            'item_description' => 'Cincin berlian 1 karat dengan setting emas putih',
            'item_category' => 'Perhiasan',
            'item_weight' => 5.50,
            'estimated_value' => 15000000,
            'loan_amount' => 10000000,
            'interest_rate' => 1.25,
            'loan_period_months' => 6,
            'start_date' => Carbon::now()->subDays(60),
            'status' => 'extended',
            'notes' => 'Diperpanjang 2 bulan',
        ]);

        // Transaction 3 - Paid
        $transaction3 = PawnTransaction::create([
            'customer_id' => $customers->skip(2)->first()->id,
            'officer_id' => $officer->id,
            'item_name' => 'Gelang Emas',
            'item_description' => 'Gelang emas 22 karat dengan ukiran tradisional',
            'item_category' => 'Perhiasan',
            'item_weight' => 8.00,
            'estimated_value' => 5000000,
            'loan_amount' => 3500000,
            'interest_rate' => 1.25,
            'loan_period_months' => 3,
            'start_date' => Carbon::now()->subDays(90),
            'status' => 'paid',
            'notes' => 'Sudah lunas',
        ]);

        // Create some payments for transaction 3
        Payment::create([
            'pawn_transaction_id' => $transaction3->id,
            'officer_id' => $officer->id,
            'payment_type' => 'interest',
            'amount' => 131250, // 3 months interest
            'interest_amount' => 131250,
            'principal_amount' => 0,
            'payment_date' => Carbon::now()->subDays(30),
            'notes' => 'Pembayaran bunga 3 bulan',
        ]);

        Payment::create([
            'pawn_transaction_id' => $transaction3->id,
            'officer_id' => $officer->id,
            'payment_type' => 'full',
            'amount' => 3500000,
            'interest_amount' => 0,
            'principal_amount' => 3500000,
            'payment_date' => Carbon::now()->subDays(15),
            'notes' => 'Pelunasan pokok pinjaman',
        ]);

        // Create notifications
        foreach ([$transaction1, $transaction2, $transaction3] as $transaction) {
            Notification::create([
                'user_id' => $transaction->customer_id,
                'pawn_transaction_id' => $transaction->id,
                'title' => 'Transaksi Gadai Baru',
                'message' => "Transaksi gadai dengan kode {$transaction->transaction_code} telah dibuat untuk barang {$transaction->item_name}.",
                'type' => 'general',
                'is_read' => false,
            ]);
        }

        // Create due date notifications for active transactions
        foreach ([$transaction1, $transaction2] as $transaction) {
            if ($transaction->getDaysUntilDue() <= 7) {
                Notification::create([
                    'user_id' => $transaction->customer_id,
                    'pawn_transaction_id' => $transaction->id,
                    'title' => 'Peringatan Jatuh Tempo',
                    'message' => "Transaksi {$transaction->transaction_code} akan jatuh tempo dalam {$transaction->getDaysUntilDue()} hari.",
                    'type' => 'due_date',
                    'is_read' => false,
                ]);
            }
        }
    }
}