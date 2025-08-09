<?php

namespace App\Http\Controllers;

use App\Models\PawnTransaction;
use App\Models\User;
use App\Models\Customer;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class PawnTransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = PawnTransaction::with(['customer', 'officer']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('start_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('start_date', '<=', $request->end_date);
        }

        // Search by transaction code or customer name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('transaction_code', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($customerQuery) use ($search) {
                      $customerQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $transactions = $query->latest()->paginate(15);

        return view('transactions.index', compact('transactions'));
    }

    public function create()
    {
        // Get customers from customers table (not users table)
        $customers = Customer::where('status', 'active')->orderBy('name')->get();
        return view('transactions.create', compact('customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'item_name' => 'required|string|max:255',
            'item_description' => 'nullable|string',
            'item_category' => 'required|string|max:100',
            'item_condition' => 'required|string|in:Baik,Rusak Ringan,Rusak Berat',
            'item_weight' => 'nullable|numeric|min:0',
            'item_photos.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'market_value' => 'nullable|numeric|min:0',
            'appraisal_value' => 'nullable|numeric|min:0',
            'appraisal_notes' => 'nullable|string',
            'loan_amount' => 'required|numeric|min:0',
            'interest_rate' => 'required|numeric|min:0|max:100',
            'loan_to_value_ratio' => 'nullable|numeric|min:0|max:100',
            'admin_fee' => 'nullable|numeric|min:0',
            'insurance_fee' => 'nullable|numeric|min:0',
            'loan_period_months' => 'required|integer|min:1|max:12',
            'start_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Handle photo uploads
            $photoNames = [];
            if ($request->hasFile('item_photos')) {
                foreach ($request->file('item_photos') as $photo) {
                    $photoName = time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
                    
                    // Simpan ke storage/app/public/transaction_photos (recommended Laravel way)
                    $photo->storeAs('transaction_photos', $photoName, 'public');
                    
                    // Juga copy ke public/images/transactions untuk backward compatibility
                    $uploadPath = public_path('images/transactions');
                    if (!file_exists($uploadPath)) {
                        mkdir($uploadPath, 0755, true);
                    }
                    copy(storage_path('app/public/transaction_photos/' . $photoName), $uploadPath . '/' . $photoName);
                    
                    $photoNames[] = $photoName;
                }
            }

            $transactionData = [
                'customer_id' => $request->customer_id,
                'officer_id' => auth()->id(),
                'item_name' => $request->item_name,
                'item_description' => $request->item_description,
                'item_category' => $request->item_category,
                'item_condition' => $request->item_condition,
                'item_photos' => $photoNames,
                'item_weight' => $request->item_weight,
                'market_value' => $request->market_value,
                'estimated_value' => $request->market_value ?? 0, // Use market_value as estimated_value or default to 0
                'loan_amount' => $request->loan_amount,
                'interest_rate' => $request->interest_rate,
                'loan_to_value_ratio' => $request->loan_to_value_ratio ?? 80.00,
                'admin_fee' => $request->admin_fee ?? 0,
                'insurance_fee' => $request->insurance_fee ?? 0,
                'loan_period_months' => $request->loan_period_months,
                'start_date' => $request->start_date,
                'notes' => $request->notes,
            ];

            // Add appraisal data if provided
            if ($request->filled('appraisal_value')) {
                $transactionData['appraisal_value'] = $request->appraisal_value;
                $transactionData['appraisal_notes'] = $request->appraisal_notes;
                $transactionData['appraised_at'] = now();
                $transactionData['appraiser_id'] = auth()->id();
            }

            $transaction = PawnTransaction::create($transactionData);

            // Create notification for officer/admin (not customer since customers are not users)
            // Note: Customers are separate entities and don't have user accounts for notifications
            // If you need customer notifications, consider implementing SMS/email notifications instead

            DB::commit();

            return redirect()->route('transactions.show', $transaction)
                ->with('success', 'Transaksi gadai berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->with('error', 'Terjadi kesalahan saat membuat transaksi: ' . $e->getMessage());
        }
    }

    public function show(PawnTransaction $transaction)
    {
        $transaction->load(['customer', 'officer', 'payments.officer', 'extensions.officer']);
        
        // Calculate current interest and total
        $currentInterest = $transaction->calculateInterest();
        $currentTotal = $transaction->loan_amount + $currentInterest;
        
        // Calculate paid amounts
        $totalPaid = $transaction->payments->sum('amount');
        $interestPaid = $transaction->payments->sum('interest_amount');
        $principalPaid = $transaction->payments->sum('principal_amount');
        
        return view('transactions.show', compact(
            'transaction', 
            'currentInterest', 
            'currentTotal',
            'totalPaid',
            'interestPaid',
            'principalPaid'
        ));
    }

    public function edit(PawnTransaction $transaction)
    {
        if ($transaction->status !== 'active') {
            return back()->with('error', 'Hanya transaksi aktif yang dapat diedit.');
        }

        // Get customers from customers table (not users table)
        $customers = Customer::where('status', 'active')->orderBy('name')->get();
        return view('transactions.edit', compact('transaction', 'customers'));
    }

    public function update(Request $request, PawnTransaction $transaction)
    {
        if ($transaction->status !== 'active') {
            return back()->with('error', 'Hanya transaksi aktif yang dapat diedit.');
        }

        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'item_name' => 'required|string|max:255',
            'item_description' => 'nullable|string',
            'item_category' => 'required|string|max:100',
            'item_condition' => 'required|string|in:Baik,Rusak Ringan,Rusak Berat',
            'item_weight' => 'nullable|numeric|min:0',
            'item_photos.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'removed_photos.*' => 'nullable|integer',
            'estimated_value' => 'required|numeric|min:0',
            'loan_amount' => 'required|numeric|min:0',
            'interest_rate' => 'required|numeric|min:0|max:100',
            'loan_period_months' => 'required|integer|min:1|max:12',
            'admin_fee' => 'nullable|numeric|min:0',
            'insurance_fee' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Handle photo updates
            $currentPhotos = $transaction->item_photos ?? [];
            
            // Remove photos that were marked for deletion
            if ($request->has('removed_photos')) {
                foreach ($request->removed_photos as $index) {
                    if (isset($currentPhotos[$index])) {
                        // Delete physical files from both locations
                        $photoName = $currentPhotos[$index];
                        
                        // Delete from storage/app/public/transaction_photos
                        Storage::delete('public/transaction_photos/' . $photoName);
                        
                        // Delete from public/images/transactions
                        $publicPath = public_path('images/transactions/' . $photoName);
                        if (file_exists($publicPath)) {
                            unlink($publicPath);
                        }
                        
                        // Remove from array
                        unset($currentPhotos[$index]);
                    }
                }
                // Reindex array
                $currentPhotos = array_values($currentPhotos);
            }

            // Add new photos
            if ($request->hasFile('item_photos')) {
                // Limit to 5 photos total
                $maxPhotos = 5;
                $currentPhotoCount = count($currentPhotos);
                $availableSlots = $maxPhotos - $currentPhotoCount;
                
                if ($availableSlots > 0) {
                    // Create directories if they don't exist
                    $uploadPath = public_path('images/transactions');
                    if (!file_exists($uploadPath)) {
                        mkdir($uploadPath, 0755, true);
                    }
                    
                    $storagePath = storage_path('app/public/transaction_photos');
                    if (!file_exists($storagePath)) {
                        mkdir($storagePath, 0755, true);
                    }
                    
                    $photosToProcess = array_slice($request->file('item_photos'), 0, $availableSlots);
                    
                    foreach ($photosToProcess as $photo) {
                        $photoName = time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
                        
                        // Save to storage/app/public/transaction_photos (Laravel recommended way)
                        $photo->storeAs('transaction_photos', $photoName, 'public');
                        
                        // Also copy to public/images/transactions for backward compatibility
                        copy(storage_path('app/public/transaction_photos/' . $photoName), $uploadPath . '/' . $photoName);
                        
                        $currentPhotos[] = $photoName;
                    }
                }
            }

            // Prepare update data
            $updateData = [
                'customer_id' => $request->customer_id,
                'item_name' => $request->item_name,
                'item_description' => $request->item_description,
                'item_category' => $request->item_category,
                'item_condition' => $request->item_condition,
                'item_weight' => $request->item_weight,
                'item_photos' => $currentPhotos,
                'estimated_value' => $request->estimated_value,
                'loan_amount' => $request->loan_amount,
                'interest_rate' => $request->interest_rate,
                'loan_period_months' => $request->loan_period_months,
                'admin_fee' => $request->admin_fee ?? 0,
                'insurance_fee' => $request->insurance_fee ?? 0,
                'notes' => $request->notes,
            ];

            // Validate loan amount against max if appraised
            if ($transaction->isAppraised()) {
                $maxLoanAmount = $transaction->calculateMaxLoanAmount();
                if ($request->loan_amount > $maxLoanAmount) {
                    throw new \Exception("Jumlah pinjaman tidak boleh melebihi maksimal pinjaman (Rp " . number_format($maxLoanAmount, 0, ',', '.') . ")");
                }
            }

            $transaction->update($updateData);

            DB::commit();

            return redirect()->route('transactions.show', $transaction)
                ->with('success', 'Transaksi berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->with('error', 'Terjadi kesalahan saat memperbarui transaksi: ' . $e->getMessage());
        }
    }

    public function extend(Request $request, PawnTransaction $transaction)
    {
        // Redirect to new extension system
        return redirect()->route('extensions.create', ['transaction_id' => $transaction->id]);
    }

    public function destroy(PawnTransaction $transaction)
    {
        if ($transaction->payments()->count() > 0) {
            return back()->with('error', 'Transaksi yang sudah memiliki pembayaran tidak dapat dihapus.');
        }

        $transaction->delete();

        return redirect()->route('transactions.index')
            ->with('success', 'Transaksi berhasil dihapus.');
    }

    /**
     * Show appraisal form
     */
    public function appraise(PawnTransaction $transaction)
    {
        if ($transaction->isAppraised()) {
            return back()->with('error', 'Barang sudah dinilai sebelumnya.');
        }

        return view('transactions.appraise', compact('transaction'));
    }

    /**
     * Store appraisal data
     */
    public function storeAppraisal(Request $request, PawnTransaction $transaction)
    {
        $request->validate([
            'market_value' => 'required|numeric|min:0',
            'appraisal_value' => 'required|numeric|min:0',
            'appraisal_notes' => 'nullable|string',
            'loan_to_value_ratio' => 'required|numeric|min:0|max:100',
        ]);

        DB::beginTransaction();
        try {
            $transaction->update([
                'market_value' => $request->market_value,
                'appraisal_value' => $request->appraisal_value,
                'appraisal_notes' => $request->appraisal_notes,
                'loan_to_value_ratio' => $request->loan_to_value_ratio,
                'appraised_at' => now(),
                'appraiser_id' => auth()->id(),
            ]);

            // Calculate max loan amount based on appraisal
            $maxLoanAmount = $transaction->calculateMaxLoanAmount();
            
            // Update loan amount if it exceeds max
            if ($transaction->loan_amount > $maxLoanAmount) {
                $transaction->update(['loan_amount' => $maxLoanAmount]);
            }

            DB::commit();

            return redirect()->route('transactions.show', $transaction)
                ->with('success', 'Penilaian barang berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan penilaian.');
        }
    }

    
    /**
     * Print transaction receipt
     */
    public function printReceipt(PawnTransaction $transaction)
    {
        $transaction->load(['customer', 'officer', 'appraiser']);

        // Generate receipt number if not exists
        if (!$transaction->receipt_number) {
            $transaction->update([
                'receipt_number' => PawnTransaction::generateReceiptNumber(),
                'receipt_printed' => true,
                'receipt_printed_at' => now(),
            ]);
        }

        $pdf = Pdf::loadView('transactions.receipt', compact('transaction'));
        
        return $pdf->download("bukti_gadai_{$transaction->transaction_code}.pdf");
    }

    /**
     * Calculate loan amount based on appraisal value
     */
    public function calculateLoan(Request $request)
    {
        $request->validate([
            'appraisal_value' => 'required|numeric|min:0',
            'loan_to_value_ratio' => 'required|numeric|min:0|max:100',
            'interest_rate' => 'required|numeric|min:0|max:100',
            'loan_period_months' => 'required|integer|min:1|max:12',
            'admin_fee' => 'nullable|numeric|min:0',
            'insurance_fee' => 'nullable|numeric|min:0',
        ]);

        $appraisalValue = $request->appraisal_value;
        $ltvRatio = $request->loan_to_value_ratio;
        $interestRate = $request->interest_rate;
        $loanPeriod = $request->loan_period_months;
        $adminFee = $request->admin_fee ?? 0;
        $insuranceFee = $request->insurance_fee ?? 0;

        $maxLoanAmount = $appraisalValue * ($ltvRatio / 100);
        $monthlyInterest = $maxLoanAmount * ($interestRate / 100);
        $totalInterest = $monthlyInterest * $loanPeriod;
        $totalAmount = $maxLoanAmount + $totalInterest;
        $totalFees = $adminFee + $insuranceFee;
        $netLoanAmount = $maxLoanAmount - $totalFees;

        return response()->json([
            'max_loan_amount' => $maxLoanAmount,
            'monthly_interest' => $monthlyInterest,
            'total_interest' => $totalInterest,
            'total_amount' => $totalAmount,
            'total_fees' => $totalFees,
            'net_loan_amount' => $netLoanAmount,
        ]);
    }
}