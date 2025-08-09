<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\PawnTransaction;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['pawnTransaction.customer', 'officer']);

        // Filter by payment type
        if ($request->filled('payment_type')) {
            $query->where('payment_type', $request->payment_type);
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('payment_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('payment_date', '<=', $request->end_date);
        }

        // Search by payment code or transaction code
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('payment_code', 'like', "%{$search}%")
                  ->orWhereHas('pawnTransaction', function($transactionQuery) use ($search) {
                      $transactionQuery->where('transaction_code', 'like', "%{$search}%");
                  });
            });
        }

        $payments = $query->latest()->paginate(15);

        return view('payments.index', compact('payments'));
    }

    public function create(Request $request)
    {
        $transactionId = $request->get('transaction_id');
        $transaction = null;

        if ($transactionId) {
            $transaction = PawnTransaction::with('customer')->find($transactionId);
            
            if (!$transaction) {
                return redirect()->route('payments.create')
                    ->with('error', 'Transaksi tidak ditemukan.');
            }
            
            if (!in_array($transaction->status, ['active', 'extended', 'overdue'])) {
                return redirect()->route('payments.create')
                    ->with('error', 'Transaksi ini tidak dapat menerima pembayaran.');
            }
        }

        $activeTransactions = PawnTransaction::with('customer')
            ->whereIn('status', ['active', 'extended', 'overdue'])
            ->get();

        return view('payments.create', compact('transaction', 'activeTransactions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pawn_transaction_id' => 'required|exists:pawn_transactions,id',
            'payment_type' => 'required|in:interest,partial,full',
            'payment_method' => 'required|in:cash,transfer,debit,credit',
            'bank_name' => 'required_if:payment_method,transfer|nullable|string|max:100',
            'reference_number' => 'required_if:payment_method,transfer|nullable|string|max:100',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $transaction = PawnTransaction::findOrFail($request->pawn_transaction_id);

        if (!in_array($transaction->status, ['active', 'extended', 'overdue'])) {
            return back()->with('error', 'Transaksi ini tidak dapat menerima pembayaran.');
        }

        DB::beginTransaction();
        try {
            // Calculate current interest and total
            $currentInterest = $transaction->calculateInterest();
            $currentTotal = $transaction->loan_amount + $currentInterest;
            
            // Calculate paid amounts
            $totalPaid = $transaction->payments->sum('amount');
            $remainingAmount = $currentTotal - $totalPaid;

            // Validate payment amount
            if ($request->payment_type === 'full') {
                // For full payment, use remaining amount
                $paymentAmount = $remainingAmount;
            } else {
                $paymentAmount = $request->amount;
                if ($paymentAmount > $remainingAmount) {
                    return back()->withInput()
                        ->with('error', 'Jumlah pembayaran melebihi sisa tagihan (Rp ' . number_format($remainingAmount, 0, ',', '.') . ').');
                }
            }

            // Calculate interest and principal portions
            $interestPaid = $transaction->payments->sum('interest_amount');
            $remainingInterest = $currentInterest - $interestPaid;
            
            $interestAmount = min($paymentAmount, $remainingInterest);
            $principalAmount = $paymentAmount - $interestAmount;
            $remainingBalance = $remainingAmount - $paymentAmount;

            // Determine if this is the final payment
            $isFinalPayment = $remainingBalance <= 0 || $request->payment_type === 'full';

            // Create payment record
            $payment = Payment::create([
                'pawn_transaction_id' => $request->pawn_transaction_id,
                'officer_id' => auth()->id(),
                'payment_type' => $request->payment_type,
                'payment_method' => $request->payment_method,
                'bank_name' => $request->bank_name,
                'reference_number' => $request->reference_number,
                'amount' => $paymentAmount,
                'interest_amount' => $interestAmount,
                'principal_amount' => $principalAmount,
                'remaining_balance' => max(0, $remainingBalance),
                'is_final_payment' => $isFinalPayment,
                'payment_date' => $request->payment_date,
                'notes' => $request->notes,
            ]);

            // Update transaction status
            if ($isFinalPayment) {
                $transaction->update(['status' => 'paid']);
                $notificationTitle = 'Transaksi Lunas';
                $notificationMessage = "Transaksi {$transaction->transaction_code} telah lunas. Barang dapat diambil.";
            } else {
                // Update status to active if it was overdue
                if ($transaction->status === 'overdue') {
                    $transaction->update(['status' => 'active']);
                }
                $notificationTitle = 'Pembayaran Diterima';
                $notificationMessage = "Pembayaran sebesar Rp " . number_format($paymentAmount, 0, ',', '.') . " untuk transaksi {$transaction->transaction_code} telah diterima. Sisa tagihan: Rp " . number_format($remainingBalance, 0, ',', '.');
            }

            // Note: Customer notifications removed since customers are not users
            // Customer notifications should be handled via SMS/email if needed

            // Create notification for officer/admin who processed the payment
            if (auth()->id() !== $transaction->customer_id) {
                $officerNotificationTitle = $isFinalPayment ? 'Pelunasan Diproses' : 'Pembayaran Diproses';
                $officerNotificationMessage = $isFinalPayment 
                    ? "Anda telah memproses pelunasan untuk transaksi {$transaction->transaction_code}. Total: Rp " . number_format($paymentAmount, 0, ',', '.')
                    : "Anda telah memproses pembayaran sebesar Rp " . number_format($paymentAmount, 0, ',', '.') . " untuk transaksi {$transaction->transaction_code}";
                
                Notification::create([
                    'user_id' => auth()->id(),
                    'pawn_transaction_id' => $transaction->id,
                    'title' => $officerNotificationTitle,
                    'message' => $officerNotificationMessage,
                    'type' => 'payment',
                ]);
            }

            // Create notification for admin (if current user is not admin)
            if (!auth()->user()->isAdmin()) {
                $adminUsers = \App\Models\User::where('role', 'admin')->get();
                foreach ($adminUsers as $admin) {
                    $adminNotificationTitle = $isFinalPayment ? 'Pelunasan Baru' : 'Pembayaran Baru';
                    $adminNotificationMessage = $isFinalPayment 
                        ? "Pelunasan transaksi {$transaction->transaction_code} telah diproses oleh " . auth()->user()->name . ". Total: Rp " . number_format($paymentAmount, 0, ',', '.')
                        : "Pembayaran sebesar Rp " . number_format($paymentAmount, 0, ',', '.') . " untuk transaksi {$transaction->transaction_code} telah diproses oleh " . auth()->user()->name;
                    
                    Notification::create([
                        'user_id' => $admin->id,
                        'pawn_transaction_id' => $transaction->id,
                        'title' => $adminNotificationTitle,
                        'message' => $adminNotificationMessage,
                        'type' => 'payment',
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('payments.show', $payment)
                ->with('success', $isFinalPayment ? 'Pelunasan berhasil diproses. Transaksi telah lunas!' : 'Pembayaran berhasil diproses.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->with('error', 'Terjadi kesalahan saat memproses pembayaran: ' . $e->getMessage());
        }
    }

    public function show(Payment $payment)
    {
        $payment->load(['pawnTransaction.customer', 'officer']);
        return view('payments.show', compact('payment'));
    }

    public function getTransactionDetails(Request $request)
    {
        try {
            $request->validate([
                'transaction_id' => 'required|exists:pawn_transactions,id'
            ]);

            $transaction = PawnTransaction::with('customer', 'payments')
                ->find($request->transaction_id);

            if (!$transaction) {
                return response()->json([
                    'error' => 'Transaksi tidak ditemukan'
                ], 404);
            }

            $currentInterest = $transaction->calculateInterest();
            $currentTotal = $transaction->loan_amount + $currentInterest;
            $totalPaid = $transaction->payments->sum('amount');
            $remainingAmount = $currentTotal - $totalPaid;

            return response()->json([
                'transaction' => $transaction,
                'current_interest' => $currentInterest,
                'current_total' => $currentTotal,
                'total_paid' => $totalPaid,
                'remaining_amount' => $remainingAmount,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function receipt(Payment $payment)
    {
        $payment->load(['pawnTransaction.customer', 'officer']);

        // Generate receipt number if not exists
        if (!$payment->receipt_number) {
            $payment->update([
                'receipt_number' => Payment::generateReceiptNumber(),
                'receipt_printed' => true,
                'receipt_printed_at' => now(),
            ]);
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('payments.receipt', compact('payment'));
        
        $filename = $payment->isFullPayment() ? 
            "bukti_pelunasan_{$payment->payment_code}.pdf" : 
            "bukti_pembayaran_{$payment->payment_code}.pdf";
        
        return $pdf->download($filename);
    }
}