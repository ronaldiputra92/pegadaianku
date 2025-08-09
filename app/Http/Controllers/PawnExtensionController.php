<?php

namespace App\Http\Controllers;

use App\Models\PawnTransaction;
use App\Models\PawnExtension;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class PawnExtensionController extends Controller
{
    /**
     * Display a listing of extensions
     */
    public function index(Request $request)
    {
        $query = PawnExtension::with(['transaction.customer', 'officer']);

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Search by extension code or transaction code
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('extension_code', 'like', "%{$search}%")
                  ->orWhereHas('transaction', function($transactionQuery) use ($search) {
                      $transactionQuery->where('transaction_code', 'like', "%{$search}%");
                  });
            });
        }

        $extensions = $query->latest()->paginate(15);

        return view('extensions.index', compact('extensions'));
    }

    /**
     * Show the form for creating a new extension
     */
    public function create(Request $request)
    {
        $transactionId = $request->get('transaction_id');
        $transaction = null;

        if ($transactionId) {
            $transaction = PawnTransaction::with(['customer', 'extensions'])->findOrFail($transactionId);
            
            // Check if transaction can be extended
            if (!in_array($transaction->status, ['active', 'extended', 'overdue'])) {
                return back()->with('error', 'Transaksi ini tidak dapat diperpanjang.');
            }
        }

        return view('extensions.create', compact('transaction'));
    }

    /**
     * Store a newly created extension
     */
    public function store(Request $request)
    {
        $request->validate([
            'transaction_id' => 'required|exists:pawn_transactions,id',
            'extension_months' => 'required|integer|min:1|max:6',
            'notes' => 'nullable|string|max:1000',
        ]);

        $transaction = PawnTransaction::findOrFail($request->transaction_id);

        // Check if transaction can be extended
        if (!in_array($transaction->status, ['active', 'extended', 'overdue'])) {
            return back()->with('error', 'Transaksi ini tidak dapat diperpanjang.');
        }

        DB::beginTransaction();
        try {
            // Ensure extension_months is integer
            $extensionMonths = (int) $request->extension_months;
            
            // Calculate extension fees
            $fees = PawnExtension::calculateExtensionFees($transaction, $extensionMonths);

            // Create extension record
            $extension = PawnExtension::create([
                'transaction_id' => $transaction->id,
                'officer_id' => auth()->id(),
                'extension_code' => PawnExtension::generateExtensionCode(),
                'original_due_date' => $transaction->due_date,
                'new_due_date' => $transaction->due_date->copy()->addMonths($extensionMonths),
                'extension_months' => $extensionMonths,
                'interest_amount' => $fees['interest_amount'],
                'penalty_amount' => $fees['penalty_amount'],
                'admin_fee' => $fees['admin_fee'],
                'total_amount' => $fees['total_amount'],
                'notes' => $request->notes,
            ]);

            // Update transaction
            $transaction->update([
                'due_date' => $extension->new_due_date,
                'loan_period_months' => $transaction->loan_period_months + $extensionMonths,
                'status' => 'extended',
            ]);

            DB::commit();

            return redirect()->route('extensions.show', $extension)
                ->with('success', 'Perpanjangan gadai berhasil diproses.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                ->with('error', 'Terjadi kesalahan saat memproses perpanjangan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified extension
     */
    public function show(PawnExtension $extension)
    {
        $extension->load(['transaction.customer', 'officer']);
        
        return view('extensions.show', compact('extension'));
    }

    /**
     * Calculate extension fees (AJAX)
     */
    public function calculateFees(Request $request)
    {
        $request->validate([
            'transaction_id' => 'required|exists:pawn_transactions,id',
            'extension_months' => 'required|integer|min:1|max:6',
        ]);

        $transaction = PawnTransaction::findOrFail($request->transaction_id);
        
        // Ensure extension_months is integer
        $extensionMonths = (int) $request->extension_months;
        
        $fees = PawnExtension::calculateExtensionFees($transaction, $extensionMonths);

        return response()->json([
            'success' => true,
            'fees' => $fees,
            'formatted' => [
                'interest_amount' => 'Rp ' . number_format($fees['interest_amount'], 0, ',', '.'),
                'penalty_amount' => 'Rp ' . number_format($fees['penalty_amount'], 0, ',', '.'),
                'admin_fee' => 'Rp ' . number_format($fees['admin_fee'], 0, ',', '.'),
                'total_amount' => 'Rp ' . number_format($fees['total_amount'], 0, ',', '.'),
            ]
        ]);
    }

    /**
     * Print extension receipt
     */
    public function printReceipt(PawnExtension $extension)
    {
        $extension->load(['transaction.customer', 'officer']);

        // Generate receipt number if not exists
        if (!$extension->receipt_number) {
            $extension->update([
                'receipt_number' => PawnExtension::generateReceiptNumber(),
                'receipt_printed' => true,
                'receipt_printed_at' => now(),
            ]);
        }

        $pdf = Pdf::loadView('extensions.receipt', compact('extension'));
        
        return $pdf->download("bukti_perpanjangan_{$extension->extension_code}.pdf");
    }

    /**
     * Get transaction details for extension (AJAX)
     */
    public function getTransactionDetails(Request $request)
    {
        try {
            $transactionCode = $request->get('transaction_code');
            
            Log::info('Searching for transaction: ' . $transactionCode);
            
            if (!$transactionCode) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kode transaksi harus diisi.'
                ]);
            }

            $transaction = PawnTransaction::with(['customer', 'extensions'])
                ->where('transaction_code', $transactionCode)
                ->first();

            if (!$transaction) {
                Log::info('Transaction not found: ' . $transactionCode);
                return response()->json([
                    'success' => false,
                    'message' => 'Transaksi tidak ditemukan.'
                ]);
            }

            Log::info('Transaction found: ' . $transaction->transaction_code . ' - Status: ' . $transaction->status);

            if (!in_array($transaction->status, ['active', 'extended', 'overdue'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Transaksi ini tidak dapat diperpanjang. Status: ' . $transaction->status
                ]);
            }

            // Check if customer exists
            if (!$transaction->customer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data nasabah tidak ditemukan untuk transaksi ini.'
                ]);
            }

            return response()->json([
                'success' => true,
                'transaction' => [
                    'id' => $transaction->id,
                    'transaction_code' => $transaction->transaction_code,
                    'customer_name' => $transaction->customer->name,
                    'item_name' => $transaction->item_name,
                    'loan_amount' => $transaction->loan_amount,
                    'interest_rate' => $transaction->interest_rate,
                    'due_date' => $transaction->due_date->format('d/m/Y'),
                    'status' => $transaction->status,
                    'is_overdue' => $transaction->isOverdue(),
                    'days_until_due' => $transaction->getDaysUntilDue(),
                    'extensions_count' => $transaction->extensions ? $transaction->extensions->count() : 0,
                    'formatted' => [
                        'loan_amount' => 'Rp ' . number_format($transaction->loan_amount, 0, ',', '.'),
                        'interest_rate' => $transaction->interest_rate . '%',
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error in getTransactionDetails: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }
}