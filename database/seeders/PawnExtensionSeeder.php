<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PawnTransaction;
use App\Models\PawnExtension;
use App\Models\User;
use Carbon\Carbon;

class PawnExtensionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some active transactions
        $activeTransactions = PawnTransaction::whereIn('status', ['active', 'extended'])
            ->take(3)
            ->get();

        // Get an officer
        $officer = User::where('role', 'petugas')->first();
        
        if (!$officer) {
            $officer = User::where('role', 'admin')->first();
        }

        if ($activeTransactions->count() > 0 && $officer) {
            foreach ($activeTransactions as $transaction) {
                // Create 1-2 extensions per transaction
                $extensionCount = rand(1, 2);
                
                for ($i = 0; $i < $extensionCount; $i++) {
                    $extensionMonths = rand(1, 3);
                    $fees = PawnExtension::calculateExtensionFees($transaction, $extensionMonths);
                    
                    $extension = PawnExtension::create([
                        'transaction_id' => $transaction->id,
                        'officer_id' => $officer->id,
                        'extension_code' => PawnExtension::generateExtensionCode(),
                        'original_due_date' => $transaction->due_date,
                        'new_due_date' => $transaction->due_date->copy()->addMonths($extensionMonths),
                        'extension_months' => $extensionMonths,
                        'interest_amount' => $fees['interest_amount'],
                        'penalty_amount' => $fees['penalty_amount'],
                        'admin_fee' => $fees['admin_fee'],
                        'total_amount' => $fees['total_amount'],
                        'notes' => 'Perpanjangan gadai periode ' . $extensionMonths . ' bulan',
                        'created_at' => Carbon::now()->subDays(rand(1, 30)),
                    ]);

                    // Update transaction due date and status
                    $transaction->update([
                        'due_date' => $extension->new_due_date,
                        'loan_period_months' => $transaction->loan_period_months + $extensionMonths,
                        'status' => 'extended',
                    ]);
                }
            }
        }
    }
}